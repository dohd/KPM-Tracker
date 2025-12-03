<?php

namespace App\Http\Controllers\assign_score;

use App\Http\Controllers\Controller;
use App\Models\assign_score\AssignScore;
use App\Models\metric\Metric;
use App\Models\programme\Programme;
use App\Models\team\TeamSize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AssignScoreController extends Controller
{
    use AssignScoreTrait;
    
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
     * Render datatable for loaded scores
     */
    public function load_scores_datatable(Request $request)
    {
        $input = $request->req_input;
        $teams = array_map(fn($v) => (object) $v, $request->teams);

        return view('assign_scores.partial.load_score_table', compact('teams', 'input'));
    }
}
