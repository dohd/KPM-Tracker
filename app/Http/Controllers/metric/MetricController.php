<?php

namespace App\Http\Controllers\metric;

use App\Http\Controllers\Controller;
use App\Models\metric\Metric;
use App\Models\programme\Programme;
use App\Models\team\Team;
use App\Models\team\TeamMember;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MetricController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $programmes = Programme::where('is_active', 1)->get();
        $teams = Team::where('is_active', 1)->get();

        return view('metrics.index', compact('programmes', 'teams'));
    }

    /** 
     * Metrics DataTable
     * */
    public function metricsDatatable()
    {
        $q = Metric::query();

        $q->when(request('date_from') && request('date_to'), function($q) {
            $q->whereBetween('date', [
                databaseDate(request('date_from')),
                databaseDate(request('date_to')),
            ]);
        });
        $q->when(request('programme_id'), fn($q) => $q->where('programme_id', request('programme_id')));
        $q->when(request('team_id'), fn($q) => $q->where('team_id', request('team_id')));
        $q->when(request('score_status'), function($q) {
            if (request('score_status') == 1) {
                $q->whereNotNull('in_score');
            } else {
                $q->whereNull('in_score');
            }
        });

        $q->orderBy('id', 'desc');

        return view('metrics.partial.metrics_data', ['metrics' => $q->get()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        // captain team composition error
        if (auth()->user()->user_type === 'captain' && !confirmTeamCompositionUpdated()) {
            return redirect()->route('metrics.index')
            ->with('error', 'Updated team composition is required!');
        }

        $teams = Team::get();
        $programmes = Programme::where('is_active', 1)->get();

        return view('metrics.create', compact('teams', 'programmes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => ['required', 'date'],
            'programme_id' => ['required', 'integer', 'exists:programmes,id'],
            'team_id' => ['required', 'integer', 'exists:teams,id'],

            'team_total' => ['nullable', 'numeric', 'min:0'],
            'grant_amount' => ['nullable', 'numeric', 'min:0'],
            'retreat_leader_total' => ['nullable', 'numeric', 'min:0'],
            'online_meeting_team_total' => ['nullable', 'numeric', 'min:0'],
            'activities_total' => ['nullable', 'numeric', 'min:0'],
            'summit_leader_total' => ['nullable', 'numeric', 'min:0'],
            'recruit_total' => ['nullable', 'numeric', 'min:0'],
            'initiative_total' => ['nullable', 'numeric', 'min:0'],
            'team_mission_total' => ['nullable', 'numeric', 'min:0'],
            'choir_member_total' => ['nullable', 'numeric', 'min:0'],
            'other_activities_total' => ['nullable', 'numeric', 'min:0'],
        ]);

       
        try {
            $teamMembersData = $request->only('team_member_id');
            $input = inputClean($request->except('_token'));
            unset($input['team_member_id']);
            foreach ($input as $key => $value) {
                $keys = [
                    'team_total', 'guest_total', 'grant_amount', 'retreat_leader_total', 'online_meeting_team_total', 'activities_total', 'summit_leader_total',
                    'recruit_total', 'initiative_total', 'team_mission_total', 'choir_member_total', 'other_activities_total', 'team_mission_amount',
                ];
                if (in_array($key, $keys)) {
                    $input[$key] = numberClean($value);
                }
            }

            // duplicate entry
            $is_exists = Metric::whereDate('date', $input['date'])
                ->where(['programme_id' => $input['programme_id'], 'team_id' => $input['team_id']])
                ->exists();
            if ($is_exists) return errorHandler('Metric input exists for a similar date');

            // duplicate meeting
            $is_exists = Metric::whereHas('programme', fn($q) => $q->where('metric', 'Online-Meeting'))
                ->whereMonth('date', date('m', strtotime($input['date'])))
                ->whereYear('date', date('Y', strtotime($input['date'])))
                ->where(['programme_id' => $input['programme_id'], 'team_id' => $input['team_id']])
                ->exists();
            if ($is_exists) return errorHandler('Metric input exists for a similar month');

            DB::beginTransaction();
                
            $metric = Metric::create($input);

            // create members data
            $n = $teamMembersData['team_member_id'];
            $teamMembersData = array_replace($teamMembersData, [
                'metric_id' => array_fill(0, count($n), $metric->id),
                'team_id' => array_fill(0, count($n), $metric->team_id),
                'programme_id' => array_fill(0, count($n), $metric->programme_id),
                'date' => array_fill(0, count($n), $metric->date),
                'checked' => array_fill(0, count($n), 1),
                'user_id' => array_fill(0, count($n), auth()->id()),
                'ins' => array_fill(0, count($n), auth()->user()->ins),
            ]);
            $teamMembersData = databaseArray($teamMembersData);
            $metric->metricMembers()->insert($teamMembersData);

            DB::commit();

            return redirect(route('metrics.index'))->with(['success' => 'Metric Input created successfully']);
        } catch (\Throwable $th) {
            return errorHandler('Error creating Metric Input! ', $th);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Metric $metric)
    {
        return view('metrics.view', compact('metric'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Metric $metric)
    {
        // restric non-chair users from editing scored metrics
        if ($metric->in_score && auth()->user()->user_type !== 'chair') {
            return redirect()->route('metrics.index')
            ->with('error', "You don't sufficient rights to perform this action!");
        }

        // captain team composition error
        if (auth()->user()->user_type === 'captain' && !confirmTeamCompositionUpdated()) {
            return redirect()->route('metrics.index')
            ->with('error', 'Updated team composition is required!');
        }

        $teams = Team::get();
        $programmes = Programme::get();
        
        return view('metrics.edit', compact('metric', 'teams', 'programmes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Metric $metric)
    { 
        $request->validate([
            'date' => ['required', 'date'],
            'programme_id' => ['required', 'integer', 'exists:programmes,id'],
            'team_id' => ['required', 'integer', 'exists:teams,id'],

            'team_total' => ['nullable', 'numeric', 'min:0'],
            'grant_amount' => ['nullable', 'numeric', 'min:0'],
            'retreat_leader_total' => ['nullable', 'numeric', 'min:0'],
            'online_meeting_team_total' => ['nullable', 'numeric', 'min:0'],
            'activities_total' => ['nullable', 'numeric', 'min:0'],
            'summit_leader_total' => ['nullable', 'numeric', 'min:0'],
            'recruit_total' => ['nullable', 'numeric', 'min:0'],
            'initiative_total' => ['nullable', 'numeric', 'min:0'],
            'team_mission_total' => ['nullable', 'numeric', 'min:0'],
            'choir_member_total' => ['nullable', 'numeric', 'min:0'],
            'other_activities_total' => ['nullable', 'numeric', 'min:0'],
        ]);

        try {     
            $teamMembersData = $request->only('team_member_id');
            $input = inputClean($request->except('_token'));
            unset($input['team_member_id']);
            foreach ($input as $key => $value) {
                $keys = [
                    'team_total', 'guest_total', 'grant_amount', 'retreat_leader_total', 'online_meeting_team_total', 'activities_total', 'summit_leader_total',
                    'recruit_total', 'initiative_total', 'team_mission_total', 'choir_member_total', 'other_activities_total', 'team_mission_amount',
                ];
                if (in_array($key, $keys)) {
                    $input[$key] = numberClean($value);
                }
            }

            // duplicate entry
            $is_exists = Metric::where('id', '!=', $metric->id)
                ->whereDate('date', $input['date'])
                ->where(['programme_id' => $input['programme_id'], 'team_id' => $input['team_id']])
                ->exists();
            if ($is_exists) return errorHandler('Metric input exists for a similar date');

            // duplicate meeting
            $is_exists = Metric::where('id', '!=', $metric->id)
                ->whereHas('programme', fn($q) => $q->where('metric', 'Online-Meeting'))
                ->whereMonth('date', date('m', strtotime($input['date'])))
                ->whereYear('date', date('Y', strtotime($input['date'])))
                ->where(['programme_id' => $input['programme_id'], 'team_id' => $input['team_id']])
                ->exists();
            if ($is_exists) return errorHandler('Metric input exists for a similar month');

            DB::beginTransaction();

            $metric->update($input);

            // create members data
            $n = $teamMembersData['team_member_id'];
            $teamMembersData = array_replace($teamMembersData, [
                'metric_id' => array_fill(0, count($n), $metric->id),
                'team_id' => array_fill(0, count($n), $metric->team_id),
                'programme_id' => array_fill(0, count($n), $metric->programme_id),
                'date' => array_fill(0, count($n), $metric->date),
                'checked' => array_fill(0, count($n), 1),
                'user_id' => array_fill(0, count($n), auth()->id()),
                'ins' => array_fill(0, count($n), auth()->user()->ins),
            ]);
            $teamMembersData = databaseArray($teamMembersData);
            $metric->metricMembers()->delete();
            $metric->metricMembers()->insert($teamMembersData);

            DB::commit();

            return redirect(route('metrics.index'))->with(['success' => 'Metric Input updated successfully']);              
        } catch (\Throwable $th) {
            return errorHandler('Error updating Metric Input! ', $th);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Metric $metric)
    {
        // restric non-chair users from deleting scored metrics
        if ($metric->in_score && auth()->user()->user_type != 'chair') {
            return errorHandler("You don't have rights to delete this metric!");
        }

        try {
            DB::beginTransaction();

            $metric->metricMembers()->delete();
            $metric->delete();

            DB::commit();

            return redirect(route('metrics.index'))->with(['success' => 'Metric Input deleted successfully']);
        } catch (\Throwable $th) {
            return errorHandler('Error deleting Metric Input! ', $th);
        }
    }

    /** 
     * Approve Unscored Metrics
     * */
    public function approveMetrics()
    {
        try {
            $action = request('action');
            $metricIds = request('metric_ids');
            $metricIds = explode(',', $metricIds);
            if ($action && $metricIds) {
                if ($action == 'approve') {
                    Metric::whereIn('id', $metricIds)->update(['is_approved' => 1]);
                } else {
                    Metric::whereIn('id', $metricIds)->update(['is_approved' => null]);
                }
            }
            
            return redirect(route('metrics.index'))->with(['success' => 'Successfully processed approved metrics']);
        } catch (\Throwable $th) {
            return errorHandler('Error processing approved metrics', $th);
        }
    }

    /**
     * Verified Team Members
     * */
    function verifiedTeamMembers(Request $request)
    {
        $year = Carbon::now()->year;
        $isMetricEdit = $request->is_metric_edit;
        $teamMembers = TeamMember::where('team_id', request('team_id'))
            ->whereHas('verify_members', fn($q) => $q->whereYear('date', $year))
            ->with(['metricMembers' => fn($q) => $q->select('id', 'team_member_id', 'checked')->where('team_id', request('team_id'))])
            ->get();

        return view('metrics.partial.verified_member', compact('teamMembers', 'isMetricEdit'));
    }
}
