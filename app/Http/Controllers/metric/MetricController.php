<?php

namespace App\Http\Controllers\metric;

use App\Http\Controllers\Controller;
use App\Models\metric\Metric;
use App\Models\programme\Programme;
use App\Models\team\Team;
use Illuminate\Http\Request;

class MetricController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $metrics = Metric::orderBy('id', 'desc')->get();

        return view('metrics.index', compact('metrics'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {        
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
            'date' => 'required',
            'programme_id' => 'required',
            'team_id' => 'required', 
            'team_total' => request('team_total')? 'required' : 'nullable',
            'grant_amount' => request('grant_amount')? 'required' : 'nullable',
            'retreat_leader_total' => request('retreat_leader_total')? 'required' : 'nullable',
            'online_meeting_team_total' => request('online_meeting_team_total')? 'required' : 'nullable',
            'activities_total' => request('activities_total')? 'required' : 'nullable',
            'summit_leader_total' => request('summit_leader_total')? 'required' : 'nullable',
            'recruit_total' => request('recruit_total')? 'required' : 'nullable',
            'initiative_total' => request('initiative_total')? 'required' : 'nullable',
            'team_mission_total' => request('team_mission_total')? 'required' : 'nullable',
            'choir_member_total' => request('choir_member_total')? 'required' : 'nullable',
            'other_activities_total' => request('other_activities_total')? 'required' : 'nullable',
        ]);
       
        try {
            $input = inputClean($request->except('_token'));
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
                ->where(['programme_id' => $input['programme_id'], 'team_id' => $input['team_id']])
                ->exists();
            if ($is_exists) return errorHandler('Metric input exists for a similar month');
                
            Metric::create($input);

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
        if ($metric->in_score && auth()->user()->user_type != 'chair') {
            return errorHandler("You don't have rights to edit this metric!");
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
            'date' => 'required',
            'programme_id' => 'required',
            'team_id' => 'required', 
            'team_total' => request('team_total')? 'required' : 'nullable',
            'grant_amount' => request('grant_amount')? 'required' : 'nullable',
            'retreat_leader_total' => request('retreat_leader_total')? 'required' : 'nullable',
            'online_meeting_team_total' => request('online_meeting_team_total')? 'required' : 'nullable',
            'activities_total' => request('activities_total')? 'required' : 'nullable',
            'summit_leader_total' => request('summit_leader_total')? 'required' : 'nullable',
            'recruit_total' => request('recruit_total')? 'required' : 'nullable',
            'initiative_total' => request('initiative_total')? 'required' : 'nullable',
            'team_mission_total' => request('team_mission_total')? 'required' : 'nullable',
            'choir_member_total' => request('choir_member_total')? 'required' : 'nullable',
            'other_activities_total' => request('other_activities_total')? 'required' : 'nullable',
        ]);

        try {     
            $input = inputClean($request->except('_token'));
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
                ->where(['programme_id' => $input['programme_id'], 'team_id' => $input['team_id']])
                ->exists();
            if ($is_exists) return errorHandler('Metric input exists for a similar month');

            $metric->update($input);

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
            $metric->delete();
            return redirect(route('metrics.index'))->with(['success' => 'Metric Input deleted successfully']);
        } catch (\Throwable $th) {
            return errorHandler('Error deleting Metric Input! ', $th);
        }
    }
}
