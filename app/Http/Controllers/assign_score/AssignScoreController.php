<?php

namespace App\Http\Controllers\assign_score;

use App\Http\Controllers\Controller;
use App\Models\assign_score\AssignScore;
use App\Models\attendance\Attendance;
use App\Models\programme\Programme;
use App\Models\rating_scale\RatingScale;
use App\Models\team_label\TeamLabel;
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
        $programmes = Programme::get();
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
        if (!request('point')) 
            return errorHandler('Not Allowed! Generate Team Scores before submission');
        
        try {          
            DB::beginTransaction();

            AssignScore::where('programme_id', $request->programme_id[0])
            ->when(isset($request->rating_scale_id[0]), fn($q) => $q->where('rating_scale_id', $request->rating_scale_id[0]))
            ->whereDate('date_from', '>=', $request->date_from[0])
            ->whereDate('date_to', '<=', $request->date_to[0])
            ->whereIn('team_id', $request->team_id)
            ->delete();

            $input = $request->except('_token');
            $input['user_id'] = array_fill(0, count($request->team_id), auth()->user()->id);
            $input['ins'] = array_fill(0, count($request->team_id), auth()->user()->ins);
            $data_items = databaseArray($input);
            AssignScore::insert($data_items);

            DB::commit();
            return redirect(route('assign_scores.create'))->with(['success' => 'Assigned scores created successfully']);
        } catch (\Throwable $th) {
            return errorHandler('Error creating assigned scores!', $th);
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
     * Reset assigned scores
     */
    public function reset_scores(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'programme_id' => 'required'
        ]);
        if ($validator->fails()) return response()->json(['flash_error' => 'Fields required! programme']);

        try {            
            $programme = Programme::find(request('programme_id'));
            $year = date('Y', strtotime($programme->period_from));

            AssignScore::where('programme_id', $programme->id)
            ->whereYear('date_from', $year)
            ->whereYear('date_to', $year)
            ->delete();
            
            return response()->json(['flash_success' => 'Computed Scores reset successfully']);
        } catch (\Throwable $th) {
            return response()->json(['flash_error' => 'Something went wrong. Error reseting computed scores!']);
        }
    }

    /**
     * Assign scores based on rating scale
     */
    public function load_scores(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'programme_id' => 'required',
        ]);
        if ($validator->fails()) return response()->json(['flash_error' => 'Fields required! programme']);
        
        $input = inputClean($request->except('_token'));
        $programme = Programme::find($input['programme_id']);
        $input['date_from'] = $programme->period_from;
        $input['date_to'] = $programme->period_to;
        if (!isset($input['date_from'], $input['date_to'])) {
            return response()->json(['flash_error' => 'Programme computation period required!']);
        }
        
        $scale = RatingScale::where('is_active', 1)->first();
        $attendances = Attendance::where('programme_id', $programme->id)
            ->whereBetween('date', [$input['date_from'], $input['date_to']])
            ->orderBy('date', 'ASC')
            ->get();
        $teams = TeamLabel::whereIn('id', $attendances->pluck('team_id')->toArray())->get();   
        
        switch ($programme->metric) {
            case 'Finance':
                foreach ($teams as $key => $team) {
                    $team->points = 0;
                    $team->extra_points = 0;
                    $team->accrued_amount = 0;
                    foreach ($attendances as $i => $attendance) {
                        if ($attendance->team_id == $team->id) {
                            $date = Carbon::parse($attendance->date);
                            $prog_date = Carbon::parse($programme->amount_perc_by);
                            if ($date->lte($prog_date)) {
                                $team->accrued_amount += $attendance->grant_amount;
                            }
                        }
                    }
                    // points
                    $conditional_amount = round(0.01 * $programme->amount_perc * $programme->target_amount);
                    $team->points = round($team->accrued_amount / $conditional_amount * $programme->score);
                    if ($team->points > $programme->score) $team->points = $programme->score;

                    // extra points
                    $above_conditional_amount = $team->accrued_amount - $programme->above_amount;
                    $every_conditional_amount = round(0.01 * $programme->every_amount_perc * $above_conditional_amount);
                    if ($programme->above_amount && $every_conditional_amount) {
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
                if ($attendances->count()) {
                    $dates = array_unique($attendances->pluck('date')->toArray());
                    $days = count($dates);
                }
                foreach ($teams as $key => $team) {
                    $team->days = @$days ?: 1;
                    $team->team_total_att = 0;
                    $team->guest_total_att = 0;
                    foreach ($attendances as $i => $attendance) {
                        if ($attendance->team_id == $team->id) {
                            $team->team_total_att += $attendance->team_total;
                            $team->guest_total_att += $attendance->guest_total;
                        }
                    }

                    $team_local_sizes = [];
                    $team_diasp_sizes = [];
                    $date_from = Carbon::parse($input['date_from']);
                    $date_to = Carbon::parse($input['date_to']);
                    $start_date_vars = explode(',', $team->start_date);
                    $local_size_vars = explode(',', $team->local_size);
                    $diasp_size_vars = explode(',', $team->diaspora_size);
                    $date_local_size_obj = array_combine($start_date_vars, $local_size_vars);
                    $date_diasp_size_obj = array_combine($start_date_vars, $diasp_size_vars);
                    sort($start_date_vars);
                    foreach ($start_date_vars as $n => $date) {
                        $tc_date = Carbon::parse($date);
                        if ($tc_date->eq($date_from)) {
                            $team_local_sizes[] = $date_local_size_obj[$date];
                            $team_diasp_sizes[] = $date_diasp_size_obj[$date];
                        }
                        elseif ($tc_date->gte($date_from) && $tc_date->lte($date_to)) {
                            $team_local_sizes[] = $date_local_size_obj[$date];
                            $team_diasp_sizes[] = $date_diasp_size_obj[$date];
                            $prev_date = @$start_date_vars[$n-1];
                            if ($prev_date) {
                                $tc_prev_date = Carbon::parse($prev_date);
                                if ($tc_prev_date->lte($date_from)) {
                                    $team_local_sizes[] = $date_local_size_obj[$prev_date];
                                    $team_diasp_sizes[] = $date_diasp_size_obj[$date];
                                }
                            }
                        } 
                        $last_indx = count($start_date_vars) - 1;
                        if (!$team_local_sizes && $n == $last_indx && $tc_date->lte($date_from)) {
                            $team_local_sizes[] = $date_local_size_obj[$date];
                            $team_diasp_sizes[] = $date_diasp_size_obj[$date];
                        }
                    }
                    
                    if ($programme->include_choir) {
                        $team->total = $scale->choir_no;
                        if ($team->total == 0) continue;

                        $team->team_avg_att = round($team->team_total_att / $team->days, 4);
                        $team->perc_score = 0;
                        $team->points = $team->team_avg_att;
                    } 
                    else {
                        $team->total = 0;
                        $local_sizes_sum = array_reduce($team_local_sizes, fn($prev, $curr) => $prev+$curr, 0);
                        $diasp_sizes_sum = array_reduce($team_diasp_sizes, fn($prev, $curr) => $prev+$curr, 0);
                        if ($programme->team_size == 'total_size' && $team_local_sizes && $team_diasp_sizes) {
                            $team->total = ceil(($local_sizes_sum + $diasp_sizes_sum) / count($team_local_sizes));
                        } elseif ($programme->team_size == 'diaspora_size' && $team_diasp_sizes) {
                            $team->total = ceil($diasp_sizes_sum / count($team_diasp_sizes));
                        } elseif ($team_local_sizes) {
                            $team->total = ceil($local_sizes_sum / count($team_local_sizes));
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
                    foreach ($attendances as $i => $attendance) {
                        if ($attendance->team_id == $team->id) {
                            if ($attendance->retreat_leader_total >= $scale->retreat_leader_no) {
                                $team->no_meetings++;
                                $team->no_leaders += $attendance->retreat_leader_total;
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
                    foreach ($attendances as $i => $attendance) {
                        if ($attendance->team_id == $team->id) {
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
                    foreach ($attendances as $i => $attendance) {
                        if ($attendance->team_id == $team->id) {
                            $team->tb_activities_total += $attendance->activities_total;
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
                    $team->summit_meetings_total = 0;
                    foreach ($attendances as $i => $attendance) {
                        if ($attendance->team_id == $team->id) {
                            if ($attendance->summit_leader_total >= $scale->summit_leaders_no) {
                                $team->summit_meetings_total++;
                            }
                        }
                    }
                    $team->points = 0;
                    if ($scale->summit_meeting_no && $scale->summit_meeting_score) {
                        $team->points = floor(($team->summit_meetings_total/$scale->summit_meeting_no) * $scale->summit_meeting_score);
                    }
                    $team->net_points = $team->points;
                    $teams[$key] = $team;
                }
                break;
            case 'Member-Recruitment': 
                foreach ($teams as $key => $team) {
                    $team->recruits_total = 0;
                    foreach ($attendances as $i => $attendance) {
                        if ($attendance->team_id == $team->id) {
                            $team->recruits_total += $attendance->recruit_total;
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
                    foreach ($attendances as $i => $attendance) {
                        if ($attendance->team_id == $team->id) {
                            $team->initiatives_total += $attendance->initiative_total;
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
                    foreach ($attendances as $i => $attendance) {
                        if ($attendance->team_id == $team->id) {
                            $team->missions_total += $attendance->team_mission_total;
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
                    foreach ($attendances as $i => $attendance) {
                        if ($attendance->team_id == $team->id) {
                            $team->choir_members_total += $attendance->choir_member_total;
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
                    foreach ($attendances as $i => $attendance) {
                        if ($attendance->team_id == $team->id) {
                            $team->other_activities_total += $attendance->other_activities_total;
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
        $input = array_replace($input, [
            'programme_include_choir' => $programme->include_choir,
            'rating_scale_id' => $scale->id,
            'metric' => $programme->metric,
            'target_amount' => $programme->target_amount,
        ]);

        $valid_teams = $teams->filter(fn($v) => $v->points > 0);
        if (!$valid_teams->count()) return response()->json(['flash_error' => 'Computation Error! Please verify rating scale and metric data']);

        return response()->json(['flash_success' => 'Scores assigned successfully', 'data' => ['teams' => $valid_teams, 'req_input' => $input]]);
    }

    /**
     * Load assigned scores table rows
     */
    public function load_scores_datatable(Request $request)
    {
        $teams = array_map(fn($v) => (object) $v, $request->teams);
        $input = $request->req_input;
        return view('assign_scores.partial.load_score', compact('teams', 'input'));
    }
}
