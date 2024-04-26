<?php

namespace App\Http\Controllers\report;

use App\Http\Controllers\Controller;
use App\Models\assign_score\AssignScore;
use App\Models\programme\Programme;
use App\Models\team_label\TeamLabel;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Create performance report page
     */
    public function create_performance()
    {
        $programmes = Programme::get();
        
        return view('reports.performance', compact('programmes'));
    }

    /**
     * Generate performance report
     */
    public function generate_performance(Request $request)
    {
        $request->validate([
            'date_from' => 'required',
            'date_to' => 'required',
            'output' => 'required',
        ]);
        $input = inputClean($request->except('_token'));

        $assigned_scores = AssignScore::whereDate('date_from', '>=', $input['date_from'])
        ->whereDate('date_to', '<=', $input['date_to'])
        ->get(['id', 'programme_id', 'team_id']);
        
        $teams = TeamLabel::whereIn('id', $assigned_scores->pluck('team_id')->toArray())->get(['id', 'name']);
        foreach ($teams as $key => $team) {
            $team->programme_scores = $team->assigned_scores()
                ->selectRaw('programme_id, SUM(net_points) as total')
                ->whereIn('assign_scores.id', $assigned_scores->pluck('id')->toArray())
                ->groupBy('programme_id')
                ->get();
            // apply max aggregate score limit
            foreach ($team->programme_scores as $i => $team_prog_score) {
                $programme = Programme::find($team_prog_score->programme_id);
                if ($programme->max_aggr_score && $team_prog_score->total > $programme->max_aggr_score) {
                    $team->programme_scores[$i]['total'] = $programme->max_aggr_score;
                }
            }
            $team->programme_score_total = $team->programme_scores->sum('total');
            $teams[$key] = $team;
        }
        
        // assign position
        $orderd_teams = $teams->sortByDesc('programme_score_total');
        foreach ($orderd_teams->keys() as $i => $pos) {
            $teams[$pos]['position'] = $i+1;
        }
        // resolve a tie
        $marked_keys = [];
        $marked_totals = [];
        $orderd_teams = $teams->sortBy('position');
        foreach ($orderd_teams->keys() as $i => $pos) {
            $score_total = $teams[$pos]->programme_score_total;
            if ($i && in_array($score_total, $marked_totals)) {
                $score_index = array_search($score_total, $marked_totals);
                $init_pos = $marked_keys[$score_index];
                $teams[$pos]['position'] = $teams[$init_pos]['position'];
                continue;
            } 
            $marked_keys[] = $pos;
            $marked_totals[] = $score_total;
        }

        $filename = 'Team_Performance';
        $meta['title'] = 'Team Performance';
        $meta['date_from'] = dateFormat($request->date_from);
        $meta['date_to'] = dateFormat($request->date_to);
        $meta['programmes'] = Programme::whereIn('id', $assigned_scores->pluck('programme_id')->toArray())->get(['id', 'name']);
        $records = $teams;

        switch ($request->output) {
            case 'pdf_print':
                $html = view('reports.pdf.team_performance', compact('records', 'meta'))->render();
                $headers = [
                    "Content-type" => "application/pdf",
                    "Pragma" => "no-cache",
                    "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                    "Expires" => "0"
                ];
                $pdf = new \Mpdf\Mpdf(array_replace(config('pdf'), ['format' => 'A4-L']));
                $pdf->WriteHTML($html);
                return response()->stream($pdf->Output($filename . '.pdf', 'I'), 200, $headers);
            case 'pdf':
                $html = view('reports.pdf.team_performance', compact('records', 'meta'))->render();
                $pdf = new \Mpdf\Mpdf(array_replace(config('pdf'), ['format' => 'A4-L']));
                $pdf->WriteHTML($html);
                return $pdf->Output($filename . '.pdf', 'D');
            case 'csv':
                $headers = [
                    "Content-type" => "text/csv",
                    "Content-Disposition" => "attachment; filename=$filename.csv",
                    "Pragma" => "no-cache",
                    "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                    "Expires" => "0"
                ];
                $callback = function() use($records, $meta) {
                    $programme_names = $meta['programmes']->pluck('name')->toArray();
                    $programme_ids = $meta['programmes']->pluck('id')->toArray();
                    
                    $file = fopen('php://output', 'w');
                    fputcsv($file, array_merge(['No.', 'Team Name'], $programme_names, ['Total', 'Position']));
                    foreach ($records as $i => $item) {
                        $programme_scores = array_map(function($id) use($item) {
                            $score_total = 0;
                            foreach ($item->programme_scores as $score) {
                                if ($score->programme_id == $id) {
                                    $score_total = $score->total;
                                    break;
                                }
                            }
                            return $score_total;
                        }, $programme_ids);
                        fputcsv($file, array_merge([$i+1, $item->name], $programme_scores, [$item->programme_score_total, $item->position]));
                    }
                    fclose($file);
                };
                return response()->stream($callback, 200, $headers);
        }
    }
}