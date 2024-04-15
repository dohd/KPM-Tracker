<?php

namespace App\Http\Controllers\score_card;

use App\Http\Controllers\Controller;
use App\Models\rating_scale\RatingScale;
use App\Models\rating_scale\RatingScaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScoreCardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rating_scales = RatingScale::orderBy('id', 'desc')->get();
 
        return view('score_cards.index', compact('rating_scales'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('score_cards.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
        try {     
            DB::beginTransaction();

            $rating_scale = RatingScale::create($request->except('min', 'max', 'point'));

            $data_items = $request->only('min', 'max', 'point');
            $data_items = databaseArray($data_items);
            $data_items = array_filter($data_items, fn($v) => ($v['min'] != null || $v['max'] != null));        
            $data_items = array_map(function($v) use($rating_scale) {
                $v['rating_scale_id'] = $rating_scale->id;
                $v['min'] = numberClean($v['min']);
                $v['max'] = numberClean($v['max']);
                $v['point'] = numberClean($v['point']);
                return $v;
            }, $data_items);
            RatingScaleItem::insert($data_items);

            DB::commit();
            return redirect(route('score_cards.index'))->with(['success' => 'Rating Scale created successfully']);
        } catch (\Throwable $th) {
            return errorHandler('Error creating Rating Scale!', $th);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(RatingScale $score_card)
    {
        return view('score_cards.view', compact('score_card'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(RatingScale $score_card)
    {
        return view('score_cards.edit', compact('score_card'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RatingScale $score_card)
    {   
        // update status
        if (isset($request->is_active)) {
            try {
                $score_card->update(['is_active' => request('is_active')]);
                return redirect()->back()->with('success', 'Status updated successfully');
            } catch (\Throwable $th) {
                return errorHandler('Error updating status!', $th);
            }
        }
            
        try {            
            DB::beginTransaction();

            $score_card->update($request->except('min', 'max', 'point'));

            $data_items = $request->only('min', 'max', 'point');
            $data_items = databaseArray($data_items);
            $data_items = array_filter($data_items, fn($v) => ($v['min'] != null || $v['max'] != null));        
            $data_items = array_map(function($v) use($score_card) {
                $v['rating_scale_id'] = $score_card->id;
                $v['min'] = numberClean($v['min']);
                $v['max'] = numberClean($v['max']);
                $v['point'] = numberClean($v['point']);
                return $v;
            }, $data_items);

            $score_card->items()->delete();
            RatingScaleItem::insert($data_items);

            DB::commit();
            return redirect(route('score_cards.index'))->with(['success' => 'Rating Scale updated successfully']);
        } catch (\Throwable $th) {
            return errorHandler('Error updating Rating Scale!', $th);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(RatingScale $rating_scale)
    {
        try {       
            DB::beginTransaction();

            $rating_scale->items()->delete();
            $rating_scale->delete();

            DB::commit();
            return redirect(route('score_cards.index'))->with(['success' => 'Rating Scale deleted successfully']);
        } catch (\Throwable $th) {
            return errorHandler('Error deleting Rating Scale!', $th);
        }
    }
}
