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
            'programme_id' => 'required',
            'date_from' => 'required',
            'date_to' => 'required',
        ]);
        if ($validator->fails()) return response()->json(['flash_error' => 'Fields required! programme, date_from, date_to']);

        try {            
            AssignScore::where('programme_id', request('programme_id'))
            ->whereDate('date_from', '<=', databaseDate(request('date_from')))
            ->whereDate('date_to', '>=', databaseDate(request('date_to')))
            ->delete();
            
            return response()->json(['flash_success' => 'Computed Scores reset successfully']);
        } catch (\Throwable $th) {
            return response()->json(['flash_error' => 'Error reseting computed scores. Something went wrong']);
        }
    }

    /**
     * Assign scores based on rating scale
     */
    public function load_scores(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'programme_id' => 'required',
            'date_from' => 'required',
            'date_to' => 'required',
        ]);
        if ($validator->fails()) return response()->json(['flash_error' => 'Fields required! programme, date_from, date_to']);
        
        $input = inputClean($request->except('_token'));
        $scale = RatingScale::where('is_active', 1)->first();
        $programme = Programme::find($input['programme_id']);
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
                        $date = Carbon::parse($attendance->date);
                        $prog_date = Carbon::parse($programme->amount_perc_by);
                        if ($date->lte($prog_date)) {
                            $team->accrued_amount += $attendance->grant_amount;
                        }
                    }
                    // points
                    $conditional_amount = round(0.01 * $programme->amount_perc * $programme->target_amount);
                    if ($team->accrued_amount >= $conditional_amount) {
                        $team->points = $programme->score;
                    }
                    // extra points
                    $above_conditional_amount = round(0.01 * $programme->above_amount_perc * $programme->target_amount);
                    $every_conditional_amount = round(0.01 * $programme->every_amount_perc * $programme->target_amount);
                    if ($team->accrued_amount > $above_conditional_amount) {
                        if ($above_conditional_amount && $every_conditional_amount) {
                            $every_amount_freq = floor(($team->accrued_amount - $above_conditional_amount)/$every_conditional_amount);
                            if ($every_amount_freq <= $programme->max_extra_score) {
                                $team->extra_points = $every_amount_freq;
                            } else {
                                $team->extra_points = $programme->max_extra_score;
                            }
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
            'rating_scale_id' => $scale->id,
            'metric' => $programme->metric,
            'target_amount' => $programme->target_amount,
        ]);

        $valid_teams = $teams->filter(fn($v) => $v->points > 0);
        if (!$valid_teams->count()) return response()->json(['flash_error' => 'Computation Error! Please verify rating scale and metric data']);

        return response()->json(['flash_success' => 'Scores assigned successfully', 'data' => ['teams' => $teams, 'req_input' => $input]]);
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
