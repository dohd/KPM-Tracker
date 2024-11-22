<?php

namespace App\Http\Controllers\team_label;

use App\Http\Controllers\Controller;
use App\Models\team_label\TeamLabel;
use App\Models\team_label\TeamSize;
use Illuminate\Http\Request;
use DB;

class TeamLabelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $team_labels = TeamLabel::latest()->get();

        return view('team_labels.index', compact('team_labels'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('team_labels.create');
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
            'name' => 'required',
            'member_list' => 'required',
        ]);
        $input = $request->except('_token');

        try {    
            DB::beginTransaction();
               
            foreach ($input['start_date'] as $key => $date) {
                $size = @$input['local_size'][$key];
                if ($date && $size > 0) $input['start_date'][$key] = databaseDate($date);
                else unset($input['start_date'][$key], $input['local_size'][$key], $input['diaspora_size'][$key]);
            }
            $teamSizeArr = [
                'start_period' => $input['start_date'],
                'local_size' => $input['local_size'],
                'diaspora_size' => $input['diaspora_size'],
            ];

            // save Team
            unset($input['start_date'], $input['local_size'], $input['diaspora_size']);
            $team = TeamLabel::create($input);

            // save Team size
            $teamSizeArr['team_id'] = array_fill(0, count($teamSizeArr['local_size']), $team_label->id);
            $teamSizeArr = databaseArray($teamSizeArr);
            TeamSize::insert($teamSizeArr);

            DB::commit();

            return redirect(route('team_labels.index'))->with(['success' => 'Team Label created successfully']);
        } catch (\Throwable $th) {
            return errorHandler('Error creating team label!', $th);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(TeamLabel $team_label)
    {
        return view('team_labels.view', compact('team_label'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(TeamLabel $team_label)
    {
        return view('team_labels.edit', compact('team_label'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TeamLabel $team_label)
    {
        $request->validate([
            'name' => 'required',
            'member_list' => 'required',
        ]);
        $input = $request->except('_token');

        try {   
            DB::beginTransaction();

            foreach ($input['start_date'] as $key => $date) {
                $size = @$input['local_size'][$key];
                if ($date && $size > 0) $input['start_date'][$key] = databaseDate($date);
                else unset($input['start_date'][$key], $input['local_size'][$key], $input['diaspora_size'][$key]);
            }
            $teamSizeArr = [
                'start_period' => $input['start_date'],
                'local_size' => $input['local_size'],
                'diaspora_size' => $input['diaspora_size'],
            ];

            // update Team
            unset($input['start_date'], $input['local_size'], $input['diaspora_size']);
            $team_label->update($input);

            // save Team size
            $teamSizeArr['team_id'] = array_fill(0, count($teamSizeArr['local_size']), $team_label->id);
            $teamSizeArr = databaseArray($teamSizeArr);
            TeamSize::where('team_id', $team_label->id)->delete();
            TeamSize::insert($teamSizeArr);

            DB::commit();

            return redirect(route('team_labels.index'))->with(['success' => 'Team Label updated successfully']);
        } catch (\Throwable $th) {
            return errorHandler('Error updating team label!', $th);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(TeamLabel $team_label)
    {
        try {            
            $team_label->delete();
            return redirect(route('team_labels.index'))->with(['success' => 'Team Label deleted successfully']);
        } catch (\Throwable $th) {
            return errorHandler('Error deleting team label!', $th);
        }
    }
}
