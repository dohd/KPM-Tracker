<?php

namespace App\Http\Controllers\team;

use App\Http\Controllers\Controller;
use App\Models\team\Team;
use App\Models\team\TeamSize;
use Illuminate\Http\Request;
use DB;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teams = Team::latest()->with('team_sizes')->get();

        return view('teams.index', compact('teams'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // permit only chair to create a team 
        if (auth()->user()->user_type != 'chair') {
            return errorHandler("You don't have the rights to create a team!");
        }

        return view('teams.create');
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
            // unset($input['start_date'], $input['local_size'], $input['diaspora_size']);
            foreach ($input as $key => $value) {
                if (in_array($key, ['start_date', 'local_size', 'diaspora_size'])) {
                    $input[$key] = implode(',', $value);
                }
            }
            $team = Team::create($input);

            // save Team size
            $teamSizeArr['team_id'] = array_fill(0, count($teamSizeArr['local_size']), $team->id);
            $teamSizeArr = databaseArray($teamSizeArr);
            TeamSize::insert($teamSizeArr);

            DB::commit();

            return redirect(route('teams.index'))->with(['success' => 'Team created successfully']);
        } catch (\Throwable $th) {
            return errorHandler('Error creating Team!', $th);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Team $team)
    {
        return view('teams.view', compact('team'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Team $team)
    {
        return view('teams.edit', compact('team'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Team $team)
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
            // unset($input['start_date'], $input['local_size'], $input['diaspora_size']);
            foreach ($input as $key => $value) {
                if (in_array($key, ['start_date', 'local_size', 'diaspora_size'])) {
                    $input[$key] = implode(',', $value);
                }
            }
            $team->update($input);

            // save Team size
            $teamSizeArr['team_id'] = array_fill(0, count($teamSizeArr['local_size']), $team->id);
            $teamSizeArr = databaseArray($teamSizeArr);
            $team->team_sizes()->delete();
            TeamSize::insert($teamSizeArr);

            DB::commit();

            return redirect(route('teams.index'))->with(['success' => 'Team updated successfully']);
        } catch (\Throwable $th) {
            return errorHandler('Error updating Team!', $th);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Team $team)
    {
        // permit only chair to delete a team 
        if (auth()->user()->user_type != 'chair') {
            return errorHandler("You don't have the rights to delete a team!");
        }

        try {   
            DB::beginTransaction();    
            
            $team->team_sizes()->delete();
            $team->delete();

            DB::commit();
            return redirect(route('teams.index'))->with(['success' => 'Team deleted successfully']);
        } catch (\Throwable $th) {
            return errorHandler('Error deleting Team!', $th);
        }
    }
}
