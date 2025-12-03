<?php

namespace App\Http\Controllers\programme;

use App\Http\Controllers\Controller;
use App\Models\programme\Programme;
use Illuminate\Http\Request;

class ProgrammeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $programmes = Programme::orderBy('id', 'desc')->get();

        return view('programmes.index', compact('programmes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cumulativeProgrammes = Programme::where('metric', 'Finance')->get();

        return view('programmes.create', compact('cumulativeProgrammes'));
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
            'metric' => 'required',
            'compute_type' => 'required',
            'period_from' => 'required',
            'period_to' => 'required',
            'score' => request('metric') == 'Finance'? 'required' : '',
            'max_extra_score' => request('extra_score')? 'required' : '',
        ]);
        $input = $request->except('_token');
        $numDateFields = $request->except([
            'name', 'is_cumulative', 'cumulative_programme_id', 'metric', 'team_size', 
            'compute_type', 'bandjson', 'memo',  'include_choir',                        
        ]);

        try {     
            foreach ($numDateFields as $key => $value) {
                if (in_array($key, ['period_from', 'period_to', 'amount_perc_by'])) $input[$key] = databaseDate($value);
                else $input[$key] = numberClean($value);
            }

            Programme::create($input);

            return redirect(route('programmes.index'))->with(['success' => 'Programme created successfully']);
        } catch (\Throwable $th) {
           return errorHandler('Error creating programme!', $th);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Programme $programme)
    {   
        return view('programmes.view', compact('programme'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Programme $programme)
    {
        // permit only the chair to edit 
        $hasScores = $programme->assignScores()->exists();
        if ($hasScores && auth()->user()->user_type != 'chair') {
            return errorHandler("You don't have the rights to edit this program");
        }

        $cumulativeProgrammes = Programme::where('metric', 'Finance')
        ->where('id', '!=', $programme->id)
        ->whereNotNull('is_cumulative')
        ->get();

        return view('programmes.edit', compact('programme', 'cumulativeProgrammes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Programme $programme)
    {
        $request->validate([
            'name' => 'required',
            'metric' => 'required',
            'compute_type' => 'required',
            'period_from' => 'required',
            'period_to' => 'required',
            'score' => request('metric') == 'Finance'? 'required' : '',
            'max_extra_score' => request('extra_score')? 'required' : '',
        ]);
        $input = $request->except('_token');
        $numDateFields = $request->except([
            'name', 'is_cumulative', 'cumulative_programme_id', 'metric', 'team_size', 
            'compute_type', 'bandjson', 'memo',  'include_choir',                        
        ]);

        try {    
            foreach ($numDateFields as $key => $value) {
                if (in_array($key, ['period_from', 'period_to', 'amount_perc_by'])) $input[$key] = databaseDate($value);
                else $input[$key] = numberClean($value);
            }
            // deactivate checkboxes if not set
            $input['is_active'] = $input['is_active'] ?? 0;
            $input['is_cumulative'] = $input['is_cumulative'] ?? null;

            $programme->update($input); 

            return redirect(route('programmes.index'))->with(['success' => 'Programme updated successfully']);
        } catch (\Throwable $th) {
            return errorHandler('Error updating programme!', $th);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Programme $programme)
    {
        // permit only the chair to edit 
        $hasScores = $programme->assignScores()->exists();
        if ($hasScores && auth()->user()->user_type != 'chair') {
            return errorHandler("You don't have the rights to delete this program");
        }

        try {            
            $programme->delete();
            return redirect(route('programmes.index'))->with(['success' => 'Programme deleted successfully']);
        } catch (\Throwable $th) {
            return errorHandler('Error deleting programme!', $th);
        }
    }
}
