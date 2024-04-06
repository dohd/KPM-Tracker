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
        $programmes = Programme::latest()->get();

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
            'score' => request('metric') == 'Finance'? 'required' : '',
            'max_extra_score' => request('extra_score')? 'required' : '',
        ]);

        try {     
            $input = $request->except('_token');
            foreach ($request->except('name', 'metric', 'memo') as $key => $value) {
                if ($key == 'amount_perc_by') $input[$key] = databaseDate($value);
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
            'score' => request('metric') == 'Finance'? 'required' : '',
            'max_extra_score' => request('extra_score')? 'required' : '',
        ]);

        try {    
            $input = $request->except('_token');
            foreach ($request->except('name', 'metric', 'memo') as $key => $value) {
                if ($key == 'amount_perc_by') $input[$key] = databaseDate($value);
                else $input[$key] = numberClean($value);
            }

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
        try {            
            $programme->delete();
            return redirect(route('programmes.index'))->with(['success' => 'Programme deleted successfully']);
        } catch (\Throwable $th) {
            return errorHandler('Error deleting programme!', $th);
        }
    }
}
