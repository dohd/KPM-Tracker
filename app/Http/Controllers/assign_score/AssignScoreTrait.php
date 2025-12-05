<?php

namespace App\Http\Controllers\assign_score;

use App\Models\rating_scale\RatingScale;
use App\Models\team\Team;
use App\Models\programme\Programme;
use App\Models\metric\Metric;
use App\Models\assign_score\AssignScore;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

trait AssignScoreTrait
{
    /**
     * Load programe scores using rating scale 
     */
    public function loadScores(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'programme_id' => 'required',
        ]);
        if ($validator->fails()) return response()->json(['flash_error' => 'Fields required! programme']);
        $input = inputClean($request->except('_token'));

        $programme = Programme::findOrFail($input['programme_id']);
        $input['date_from'] = $programme->period_from;
        $input['date_to'] = $programme->period_to;
        if (!isset($input['date_from'], $input['date_to'])) {
            return response()->json(['flash_error' => 'Programme computation period required!']);
        }
        
        $scale = RatingScale::where('is_active', 1)->first();
        $metrics = Metric::whereNotNull('is_approved')
            ->where('programme_id', $programme->id)
            ->whereBetween('date', [$input['date_from'], $input['date_to']])
            ->orderBy('date', 'ASC')
            ->with('programme')
            ->get();
        if (!$metrics->count()) {
            return response()->json(['flash_error' => 'Approved metric inputs required!']);
        }
        
        $teams = Team::whereIn('id', $metrics->pluck('team_id')->toArray())->get();

        function getAncestors($model) {
        	$ancestors = collect();
		    $parent = $model->parent;
		    while ($parent) {
		        $ancestors->push($parent);
		        $parent = $parent->parent;
		    }
		    return $ancestors;
        }
        $ancestors = getAncestors($programme);

        // cumulative scores
        $cumulativeScores = collect();
        $ancestor = $ancestors->first();
        if ($ancestor) {
	    	$cumulativeScores = AssignScore::where('programme_id', $ancestor->id)
	            ->where('date_from', '>=', $input['date_from'])
	            ->where('date_to', '<=', $input['date_to'])
	            ->get(['id', 'programme_id', 'team_id', 'date_from', 'date_to', 'accrued_amount']);

	        $cumulativeTeams = Team::whereIn('id', $cumulativeScores->pluck('team_id')->toArray())
	        ->whereNotIn('id', $teams->pluck('id')->toArray())
	        ->get();  
	        $teams = collect($teams)->merge($cumulativeTeams);
        }

    	$modTeams = collect();
        switch ($programme->metric) {
            case 'Finance':
                foreach ($teams as $key => $team) {
                    $team->points = 0;
                    $team->extra_points = 0;
                    $team->accrued_amount = 0;
                    foreach ($metrics as $i => $metric) {
                        if ($metric->team_id == $team->id) {
                            $metricDate = Carbon::parse($metric->date);
                            $programmeDeadline = Carbon::parse($programme->amount_perc_by);
                            if ($metricDate->lte($programmeDeadline)) {
                                $team->accrued_amount += $metric->grant_amount;
                            }
                        }
                    }
                    // cumulate accrued amount per team
                    $teamCumulatedAmount = $cumulativeScores->where('team_id', $team->id)
                    	->sum('accrued_amount');
                    $team->accrued_amount += $teamCumulatedAmount;

                    // scoring bands
                    $bands = json_decode($programme->bandjson) ?: [];
                    foreach ($bands as $key => $band) {
                    	$conditionalAmount = round($band->threshold / 100 * $programme->target_amount);
                    	if ($team->accrued_amount >= $conditionalAmount) {
                    		$team->points = $band->points;
                    		break;
                    	}
                    }

                    // extra points
                    $above_conditional_amount = $team->accrued_amount - $programme->above_amount;
                    $every_conditional_amount = round($programme->every_amount_perc/100 * $programme->above_amount);
                    if ($programme->above_amount && $every_conditional_amount > 0) {
                        $team->extra_points = floor($above_conditional_amount / $every_conditional_amount);
                        if ($team->extra_points < 0) $team->extra_points = 0;
                        if ($programme->max_extra_score && $team->extra_points > $programme->max_extra_score) {
                            $team->extra_points = $programme->max_extra_score;
                        }
                    }

                    $team->net_points = $team->points + $team->extra_points;
                    $modTeams->push($team);
                }
                break;
            
            case 'Attendance':
                if ($metrics->count()) {
                    $dates = $metrics->pluck('date')->unique()->values()->toArray();
                    $days = count($dates);
                }

                $team_sizes_ids = [];
                foreach ($teams as $key => $team) {
                    $team->days = @$days ?: 1;
                    $team->team_total_att = $metrics->where('team_id', $team->id)->sum('team_total');
                    $team->guest_total_att = 0;

                    // guest attendance
                    $maxGuestScore = 0;
                    $maxDailyGuestScore = 0;
                    $maxDailyGuestSize = 0;
                    $dailyGuestAtt = [];
                    foreach ($metrics->where('team_id', $team->id) as $metric) {
                        if (@$dailyGuestAtt[$metric->date]) {
                            $dailyGuestAtt[$metric->date] += $metric->guest_total;
                        } else {
                            $dailyGuestAtt[$metric->date] = $metric->guest_total;
                        }
                        $maxDailyGuestSize = $metric->programme->max_daily_guest_size;
                        $maxDailyGuestScore = $metric->programme->max_daily_guest_score;
                        $maxGuestScore = $metric->programme->max_guest_score;
                    }
                    // limit guest attendance scores
                    foreach ($dailyGuestAtt as $guestCount) {
                        if ($guestCount >= $maxDailyGuestSize) $team->guest_total_att += $maxDailyGuestScore;
                        else $team->guest_total_att += $guestCount;
                    }
                    if ($maxGuestScore && $team->guest_total_att > $maxGuestScore) {
                        $team->guest_total_att = $maxGuestScore;
                    }

                    // team sizes
                    $team_sizes = collect();
                    $metric = $metrics->where('team_id', $team->id)->first();
                    $maxAggrScore = $metric->programme->max_aggr_score;

                    if ($programme->compute_type === 'Monthly' && $metric) {
                        $team->days = 1;
                        $metricDate = Carbon::parse($metric->date);
                        $team_sizes = $team->team_sizes()
                        ->whereYear('start_period', $metricDate->format('Y'))
                        ->whereMonth('start_period', $metricDate->format('m'))
                        ->orderBy('start_period', 'ASC')
                        ->take(1)
                        ->get();
                    } elseif ($programme->compute_type === 'Daily') {
                        $team_sizes = $team->team_sizes
                        ->where('start_period', '>=', $input['date_from'])
                        ->where('start_period', '<=', $input['date_to']);
                    }
                    $team_local_sizes = $team_sizes->pluck('local_size')->toArray();
                    $team_diasp_sizes = $team_sizes->pluck('diaspora_size')->toArray();
                    $team_sizes_ids  = array_merge($team_sizes_ids, $team_sizes->pluck('id')->toArray());                            

                    // team size count
                    $team->total = 0;

                    // choir programme
                    if ($programme->include_choir && $scale->choir_no > 0) {
                        $team->total = $scale->choir_no;
                        $team->team_avg_att = round($team->team_total_att / $team->days, 4);
                        $team->perc_score = 0;
                        $team->points = $team->team_avg_att;
                    } 
                    // other programme
                    else {
                        $local_sizes_sum = array_sum($team_local_sizes);
                        $diasp_sizes_sum = array_sum($team_diasp_sizes);
                        $computation_size = $programme->team_size;
                        if ($computation_size === 'total_size' && $team_local_sizes && $team_diasp_sizes) {
                            $team->total = round(($local_sizes_sum + $diasp_sizes_sum) / count($team_local_sizes),2);
                        } elseif ($computation_size === 'diaspora_size' && $team_diasp_sizes) {
                            $team->total = round($diasp_sizes_sum / count($team_diasp_sizes),2);
                        } elseif ($computation_size === 'local_size' && $team_local_sizes) {
                            $team->total = round($local_sizes_sum / count($team_local_sizes),2);
                        }
    
                        if ($team->total == 0) continue;
                        $team->team_avg_att = round($team->team_total_att / $team->days, 4);
                        $team->perc_score = round($team->team_avg_att / $team->total * 100, 4);
                        $team->points = 0;
                        foreach ($scale->items as $j => $item) {
                            $score = floor($team->perc_score);
                            if ($score >= $item->min && $score <= $item->max) {
                                $team->points = $item->point; 
                                break;
                            }
                            if ($score > $item->max && $j == count($scale->items) - 1) {
                                $team->points = $item->point; 
                            }
                        }
                    }

                    // net points
                    $team->net_points = $team->points? ($team->points + $team->guest_total_att) : 0;
                    if ($maxAggrScore > 0 && $team->net_points > $maxAggrScore) {
                        $team->net_points = $maxAggrScore;
                    }

                    $modTeams->push($team);
                }
                break;
            
            case 'Leader-Retreat': 
                foreach ($teams as $key => $team) {
                    $team->no_meetings = 0;
                    $team->no_leaders = 0;
                    foreach ($metrics as $i => $metric) {
                        if ($metric->team_id == $team->id) {
                            if ($metric->retreat_leader_total >= $scale->retreat_leader_no) {
                                $team->no_meetings++;
                                $team->no_leaders += $metric->retreat_leader_total;
                            }
                        }
                    }
                    $team->points = 0;
                    if ($scale->retreat_meeting_no && $scale->retreat_score) {
                        $team->points = floor(($team->no_meetings/$scale->retreat_meeting_no) * $scale->retreat_score);
                    }
                    $team->net_points = $team->points;
                    $modTeams->push($team);
                }
                break;
            
            case 'Online-Meeting': 
                foreach ($teams as $key => $team) {
                    $team->no_meetings = 0;
                    foreach ($metrics as $i => $metric) {
                        if ($metric->team_id == $team->id) {
                            $team->no_meetings++;
                        }
                    }
                    $team->points = 0;
                    if ($scale->online_meeting_no && $scale->online_meeting_score) {
                        $team->points = floor(($team->no_meetings/$scale->online_meeting_no) * $scale->online_meeting_score);
                    }
                    $team->net_points = $team->points;
                    $modTeams->push($team);
                }
                break;
            
            case 'Team-Bonding': 
                foreach ($teams as $key => $team) {
                    $team->tb_activities_total = 0;
                    foreach ($metrics as $i => $metric) {
                        if ($metric->team_id == $team->id) {
                            $team->tb_activities_total += $metric->activities_total;
                        }
                    }
                    $team->points = 0;
                    if ($scale->tb_activities_no && $scale->tb_activities_score) {
                        $team->points = floor(($team->tb_activities_total/$scale->tb_activities_no) * $scale->tb_activities_score);
                    }
                    $team->extra_points = 0;
                    if ($team->tb_activities_total > $scale->tb_activities_extra_min_no && $team->tb_activities_total <= $scale->tb_activities_extra_max_no) {
                        $team->extra_points += $scale->tb_activities_extra_score;
                    }
                    $team->net_points = $team->points + $team->extra_points;
                    $modTeams->push($team);
                }
                break;
            
            case 'Summit-Meeting': 
                foreach ($teams as $key => $team) {
                    $meetingsByMonth = [];
                    foreach ($metrics as $metric) {
                        if ($metric->team_id == $team->id) {
                            $month = dateFormat($metric->date, 'm-Y');
                            $meetingsByMonth[$month] = 1;
                        }
                    }
                    $team->summit_meetings_total = array_sum(array_values($meetingsByMonth));

                    $team->points = 0;
                    if ($scale->summit_meeting_no && $scale->summit_meeting_score) {
                        if ($scale->summit_meeting_no <= $team->summit_meetings_total) {
                            $team->points = $scale->summit_meeting_no;
                        } else {
                            $team->points = $team->summit_meetings_total;
                        }
                    }
                    $team->net_points = $team->points;
                    $modTeams->push($team);
                }
                break;
            
            case 'Member-Recruitment': 
                foreach ($teams as $key => $team) {
                    $team->recruits_total = 0;
                    foreach ($metrics as $i => $metric) {
                        if ($metric->team_id == $team->id) {
                            $team->recruits_total += $metric->recruit_total;
                        }
                    }
                    $team->points = 0;
                    if ($scale->recruit_no && $scale->recruit_score) {
                        $team->points = floor(($team->recruits_total/$scale->recruit_no) * $scale->recruit_score);
                    }
                    if ($scale->recruit_max_points && $team->points > $scale->recruit_max_points) {
                        $team->points = $scale->recruit_max_points;
                    }
                    $team->net_points = $team->points;
                    $modTeams->push($team);
                }
                break;
            
            case 'New-Initiative': 
                foreach ($teams as $key => $team) {
                    $team->initiatives_total = 0;
                    foreach ($metrics as $i => $metric) {
                        if ($metric->team_id == $team->id) {
                            $team->initiatives_total += $metric->initiative_total;
                        }
                    }
                    $team->points = 0;
                    if ($scale->initiative_no && $scale->initiative_score) {
                        if ($scale->initiative_max_no && $team->initiatives_total > $scale->initiative_max_no) {
                            $team->points = floor(($scale->initiative_max_no/$scale->initiative_no) * $scale->initiative_score);
                        } else {
                            $team->points = floor(($team->initiatives_total/$scale->initiative_no) * $scale->initiative_score);
                        }
                    }
                    $team->net_points = $team->points;
                    $modTeams->push($team);
                }
                break;
            
            case 'Team-Mission': 
                foreach ($teams as $key => $team) {
                    $team->missions_total = 0;
                    $team->pledged_total = 0;
                    foreach ($metrics as $i => $metric) {
                        if ($metric->team_id == $team->id) {
                            $team->missions_total += $metric->team_mission_total;
                            $team->pledged_total += $metric->team_mission_amount;
                        }
                    }
                    $team->points = 0;
                    if ($scale->mission_no && $scale->mission_score) {
                        $team->points = floor(($team->missions_total/$scale->mission_no) * $scale->mission_score);
                    }
                    $team->net_points = $team->points;
                    $modTeams->push($team);
                }
                break;
            
            case 'Choir-Member': 
                foreach ($teams as $key => $team) {
                    $team->choir_members_total = 0;
                    foreach ($metrics as $i => $metric) {
                        if ($metric->team_id == $team->id) {
                            $team->choir_members_total += $metric->choir_member_total;
                        }
                    }
                    $team->points = 0;
                    if ($scale->choir_no && $scale->choir_score) {
                        $team->points = floor(($team->choir_members_total/$scale->choir_no) * $scale->choir_score);
                    }
                    $team->net_points = $team->points;
                    $modTeams->push($team);
                }
                break;
            
            case 'Other-Activities': 
                foreach ($teams as $key => $team) {
                    $team->other_activities_total = 0;
                    foreach ($metrics as $i => $metric) {
                        if ($metric->team_id == $team->id) {
                            $team->other_activities_total += $metric->other_activities_total;
                        }
                    }
                    $team->points = 0;
                    if ($scale->other_activities_no && $scale->other_activities_score) {
                        $team->points = floor(($team->other_activities_total/$scale->other_activities_no) * $scale->other_activities_score);
                    }
                    $team->net_points = $team->points;
                    $modTeams->push($team);
                }
                break;
        }
        
        // throw error if no points computed
        $valid_teams = $modTeams->filter(fn($v) => $v->points > 0);
        if (!count($valid_teams)) {
            return response()->json(['flash_error' => 'Zero Points Computed! Please verify rating scale and metric data']);
        }

        $input = array_replace($input, [
            'programme_include_choir' => $programme->include_choir,
            'rating_scale_id' => $scale->id,
            'metric' => $programme->metric,
            'target_amount' => $programme->target_amount,
            'metric_ids' => $metrics->pluck('id')->implode(','),
            'team_sizes_ids' => @$team_sizes_ids? implode(',', $team_sizes_ids) : '',
        ]);
        return response()->json([
            'flash_success' => 'Scores assigned successfully', 
            'data' => [
                'teams' => $valid_teams, 
                'req_input' => $input,
            ]
        ]);
    }
}