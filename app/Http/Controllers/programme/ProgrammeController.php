<?php

namespace App\Http\Controllers\programme;

use App\Http\Controllers\Controller;
use App\Models\programme\Programme;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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
        return view('programmes.create');
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

        try {     
            $input = $request->except('_token');
            $date_num_vars = $request->except('name', 'metric', 'memo', 'compute_type', 'include_choir', 'team_size');
            foreach ($date_num_vars as $key => $value) {
                if (in_array($key, ['period_from', 'period_to', 'amount_perc_by'])) $input[$key] = databaseDate($value);
                else $input[$key] = numberClean($value);
            }
            // compare month for compute type monthly
            $period_from = Carbon::parse($input['period_from']);
            $period_to = Carbon::parse($input['period_to']);
            if ($input['compute_type'] == 'Monthly') {
                if ($period_from->format('m') != $period_to->format('m')) {
                    throw ValidationException::withMessages(['Not Allowed! Computation period should be of the same month']);
                }
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

        return view('programmes.edit', compact('programme'));
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

        try {    
            $input = $request->except('_token');
            $date_num_vars = $request->except('name', 'metric', 'memo', 'compute_type', 'include_choir', 'team_size');
            foreach ($date_num_vars as $key => $value) {
                if (in_array($key, ['period_from', 'period_to', 'amount_perc_by'])) $input[$key] = databaseDate($value);
                else $input[$key] = numberClean($value);
            }
            // compare month for compute type monthly
            $period_from = Carbon::parse($input['period_from']);
            $period_to = Carbon::parse($input['period_to']);
            if ($input['compute_type'] == 'Monthly') {
                if ($period_from->format('m') != $period_to->format('m')) {
                    throw ValidationException::withMessages(['Not Allowed! Computation period should be of the same month']);
                }
            }

            if (!@$input['is_active']) $input['is_active'] = 0; 
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
