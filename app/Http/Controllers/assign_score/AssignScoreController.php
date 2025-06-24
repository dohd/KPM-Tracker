<?php

namespace App\Http\Controllers\assign_score;

use App\Http\Controllers\Controller;
use App\Models\assign_score\AssignScore;
use App\Models\metric\Metric;
use App\Models\programme\Programme;
use App\Models\rating_scale\RatingScale;
use App\Models\team\Team;
use App\Models\team\TeamSize;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AssignScoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $assign_scores = AssignScore::orderBy('id', 'desc')->get();
        return view('assign_scores.index', compact('assign_scores'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $programmes = Programme::where('is_active', 1)->get();
        
        return view('assign_scores.create', compact('programmes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {  
        if (!request('point')) {
            return errorHandler('Not Allowed! Generate Team Scores before submission');
        }

        try {          
            $metric_ids = explode(',', request('metric_ids'));
            $team_sizes_ids = explode(',', request('team_sizes_ids'));
            $input = $request->except('_token', 'metric_ids', 'team_sizes_ids');

            DB::beginTransaction();

            // overwrite previous scores
            AssignScore::where('programme_id', $request->programme_id[0])
                ->when(isset($request->rating_scale_id[0]), fn($q) => $q->where('rating_scale_id', $request->rating_scale_id[0]))
                ->whereDate('date_from', '>=', $request->date_from[0])
                ->whereDate('date_to', '<=', $request->date_to[0])
                ->whereIn('team_id', $request->team_id)
                ->delete();

            // save scores
            $input['user_id'] = array_fill(0, count($request->team_id), auth()->user()->id);
            $input['ins'] = array_fill(0, count($request->team_id), auth()->user()->ins);
            $data_items = databaseArray($input);
            AssignScore::insert($data_items);

            // mark as scored
            Metric::whereIn('id', $metric_ids)->update(['in_score' => 1]);
            TeamSize::whereIn('id', $team_sizes_ids)->update(['in_score' => 1]);

            DB::commit();
            return redirect(route('assign_scores.create'))->with(['success' => 'Scores created successfully']);
        } catch (\Throwable $th) {
            return errorHandler('Error creating Scores!', $th);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(AssignScore $assign_score)
    {
        return view('assign_scores.view', compact('assign_score'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(AssignScore $assign_score)
    {
        return view('assign_scores.edit', compact('assign_score'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AssignScore $assign_score)
    {
        try {            
            $assign_score->update($request->except(['_token']));
            return redirect(route('assign_scores.index'))->with(['success' => 'Assigned score updated successfully']);
        } catch (\Throwable $th) {
            return errorHandler('Error updating assigned score!', $th);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(AssignScore $assign_score)
    {
        try {            
            $assign_score->delete();
            return redirect(route('assign_scores.index'))->with(['success' => 'Assigned score deleted successfully']);
        } catch (\Throwable $th) {
            return errorHandler('Error deleting assigned score!', $th);
        }
    }

    /**
     * Reset Programme Scores for that year
     */
    public function reset_scores(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'programme_id' => 'required'
        ]);
        if ($validator->fails()) return response()->json(['flash_error' => 'programme is required']);

        try {            
            $programme = Programme::find(request('programme_id'));
            $year = date('Y', strtotime($programme->period_from));

            AssignScore::where('programme_id', $programme->id)
                ->whereYear('date_from', $year)
                ->whereYear('date_to', $year)
                ->delete();

            // update unscored status on metrics and team-sizes 
            Metric::where('programme_id', $programme->id)->update(['in_score' => null]);
            TeamSize::whereHas('team', function($q) {
                $q->whereHas('metrics', fn($q) => $q->where('programme_id', request('programme_id')));
            })->update(['in_score' => null]);
            
            return response()->json(['flash_success' => 'Computed Scores reset successfully']);
        } catch (\Throwable $th) {
            \Log::error($th->getMessage());
            return response()->json(['flash_error' => 'Something went wrong. Error reseting computed scores!']);
        }
    }

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
        $teams = Team::whereIn('id', $metrics->pluck('team_id')->toArray())->get();   
        
        switch ($programme->metric) {
            case 'Finance':
                foreach ($teams as $key => $team) {
                    $team->points = 0;
                    $team->extra_points = 0;
                    $team->accrued_amount = 0;
                    foreach ($metrics as $i => $metric) {
                        if ($metric->team_id == $team->id) {
                            $date = Carbon::parse($metric->date);
                            $prog_date = Carbon::parse($programme->amount_perc_by);
                            if ($date->lte($prog_date)) {
                                $team->accrued_amount += $metric->grant_amount;
                            }
                        }
                    }
                    // points
                    $conditional_amount = round(0.01 * $programme->amount_perc * $programme->target_amount);
                    $team->points = round($team->accrued_amount / $conditional_amount * $programme->score, 2);
                    if ($team->points > $programme->score) $team->points = $programme->score;

                    // extra points
                    $above_conditional_amount = $team->accrued_amount - $programme->above_amount;
                    $every_conditional_amount = round(0.01 * $programme->every_amount_perc * $above_conditional_amount);
                    if ($programme->above_amount && $every_conditional_amount > 0) {
                        $team->extra_points = floor($above_conditional_amount / $every_conditional_amount);
                        if ($programme->max_extra_score && $team->extra_points > $programme->max_extra_score) {
                            $team->extra_points = $programme->max_extra_score;
                        }
                    }
                    
                    $team->net_points = $team->points + $team->extra_points;
                    $teams[$key] = $team;
                }
                break;
            case 'Attendance':
                if ($metrics->count()) {
                    $dates = array_unique($metrics->pluck('date')->toArray());
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
                    $team_local_sizes = [];
                    $team_diasp_sizes = [];
                    // monthly team size
                    $team_sizes = $team->team_sizes->where('start_period', $input['date_from']);
                    if ($team_sizes->count() && $programme->compute_type == 'Monthly') {
                        $team_local_sizes = $team_sizes->pluck('local_size')->toArray();
                        $team_diasp_sizes = $team_sizes->pluck('diaspora_size')->toArray();
                        $team_sizes_ids  = array_merge($team_sizes_ids, $team_sizes->pluck('id')->toArray());
                    } else {
                        // team size within a date range (daily) including the previous last team size
                        $team_sizes = $team->team_sizes
                        ->where('start_period', '>=', $input['date_from'])
                        ->where('start_period', '<=', $input['date_to']);
                        if ($team_sizes->count()) {
                            $team_local_sizes = $team_sizes->pluck('local_size')->toArray();
                            $team_diasp_sizes = $team_sizes->pluck('diaspora_size')->toArray();
                            $team_sizes_ids  = array_merge($team_sizes_ids, $team_sizes->pluck('id')->toArray());
                            // previous last team size
                            $initial = $team->team_sizes()->where('start_period', '<', $input['date_from'])->latest()->first();
                            if ($initial) {
                                $team_local_sizes[] = $initial->local_size;
                                $team_diasp_sizes[] = $initial->diaspora_size;
                                $team_sizes_ids[]  = $initial->id;
                            }
                        } else {
                            // default team size
                            $initial = $team->team_sizes()->where('start_period', '<=', $input['date_from'])->latest()->first();
                            if ($initial) {
                                $team_local_sizes[] = $initial->local_size;
                                $team_diasp_sizes[] = $initial->diaspora_size;
                                $team_sizes_ids[]  = $initial->id;
                            }
                        }
                    }
                    
                    // check if is choir programme
                    if ($programme->include_choir) {
                        $team->total = $scale->choir_no;
                        if ($team->total == 0) continue;
                        $team->team_avg_att = round($team->team_total_att / $team->days, 4);
                        $team->perc_score = 0;
                        $team->points = $team->team_avg_att;
                    } else {
                        $team->total = 0;
                        $local_sizes_sum = array_sum($team_local_sizes);
                        $diasp_sizes_sum = array_sum($team_diasp_sizes);
                        if ($programme->team_size == 'total_size' && $team_local_sizes && $team_diasp_sizes) {
                            $team->total = round(($local_sizes_sum + $diasp_sizes_sum) / count($team_local_sizes),2);
                        } elseif ($programme->team_size == 'diaspora_size' && $team_diasp_sizes) {
                            $team->total = round($diasp_sizes_sum / count($team_diasp_sizes),2);
                        } elseif ($team_local_sizes) {
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
                    
                    $team->net_points = $team->points? ($team->points + $team->guest_total_att) : 0;
                    $teams[$key] = $team;
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
                    $teams[$key] = $team;
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
                    $teams[$key] = $team;
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
                    $teams[$key] = $team;
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
                    $teams[$key] = $team;
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
                    $teams[$key] = $team;
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
                    $teams[$key] = $team;
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
                    $teams[$key] = $team;
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
                    $teams[$key] = $team;
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
                    $teams[$key] = $team;
                }
                break;
        }
        
        // throw error if no points computed
        $valid_teams = $teams->filter(fn($v) => $v->points > 0);
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
            'data' => ['teams' => $valid_teams, 'req_input' => $input]
        ]);
    }

    /**
     * Render datatable for loaded scores
     */
    public function load_scores_datatable(Request $request)
    {
        $input = $request->req_input;
        $teams = array_map(fn($v) => (object) $v, $request->teams);
        return view('assign_scores.partial.load_score_table', compact('teams', 'input'));
    }
}
