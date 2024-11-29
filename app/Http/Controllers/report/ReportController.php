<?php

namespace App\Http\Controllers\report;

use App\Http\Controllers\Controller;
use App\Models\assign_score\AssignScore;
use App\Models\metric\Metric;
use App\Models\programme\Programme;
use App\Models\team\Team;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Generate Team Performance Summary
     */
    public function teamPerformanceSummary(Request $request)
    {
        if (!$request->post()) {
            return view('reports.team_summary_performance');
        }

        $request->validate([
            'date_from' => 'required',
            'date_to' => 'required',
            'output' => 'required',
        ]);
        $input = inputClean($request->except('_token'));

        $filename = 'Summary Team_Performance';
        $meta['title'] = 'Summary Team Performance';
        $meta['date_from'] = dateFormat($request->date_from);
        $meta['date_to'] = dateFormat($request->date_to);
        $meta['programmes'] = Programme::whereHas('assignScores', function($q) use($input) {
            $q->whereDate('date_from', '>=', $input['date_from'])->whereDate('date_to', '<=', $input['date_to']);
        })
        ->get(['id', 'name']);
        $rankedTeams = $this->rankTeamsFromScores([$input['date_from'], $input['date_to']]);
        $records = $rankedTeams;

        switch ($request->output) {
            case 'pdf_print':
                $html = view('reports.pdf.print_team_summary_performance', compact('records', 'meta'))->render();
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
                $html = view('reports.pdf.print_team_summary_performance', compact('records', 'meta'))->render();
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

    /**
     * Rank Teams from Computed Scores
     */
    public function rankTeamsFromScores($date_range=[])
    {
        $assigned_scores = AssignScore::whereDate('date_from', '>=', $date_range[0])
            ->whereDate('date_to', '<=', $date_range[1])
            ->get(['id', 'programme_id', 'team_id']);
        $teams = Team::whereIn('id', $assigned_scores->pluck('team_id')->toArray())->get(['id', 'name']);
        foreach ($teams as $key => $team) {
            $team->programme_scores = $team->assigned_scores()
                ->selectRaw('programme_id, SUM(net_points) as total')
                ->whereIn('assign_scores.id', $assigned_scores->pluck('id')->toArray())
                ->groupBy('programme_id')
                ->get();
            
            foreach ($team->programme_scores as $i => $team_prog_score) {
                $programme = Programme::find($team_prog_score->programme_id);
                // apply max aggregate score limit
                if ($programme->max_aggr_score && $team_prog_score->total > $programme->max_aggr_score) {
                    $team->programme_scores[$i]['total'] = $programme->max_aggr_score;
                }
                // allow decimals for only choir programmes
                if (!$programme->include_choir) $team->programme_scores[$i]['total'] = round($team_prog_score->total);
            }
            $team->programme_score_total = $team->programme_scores->sum('total');
            $team->programme_score_total = round($team->programme_score_total);
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
        // order by position
        $orderd_teams = $teams->sortBy('position');
        $teams = collect();
        $orderd_teams->each(fn($v) => $teams->add($v));
        return $teams;
    }

    /**
     * Team Size Summary
     */
    public function teamSizeSummary(Request $request)
    {
        if (!$request->post()) {
            return view('reports.team_size_summary');
        }

        $filename = 'Team Size Summary';
        $meta['title'] = 'Team Size Summary';
        $meta['date_from'] = dateFormat($request->date_from);
        $meta['date_to'] = dateFormat($request->date_to);
        $meta['months'] = [
            '01' => 'Jan',
            '02' => 'Feb',
            '03' => 'Mar',
            '04' => 'Apr',
            '05' => 'May',
            '06' => 'Jun',
            '07' => 'Jul',
            '08' => 'Aug',
            '09' => 'Sep',
            '10' => 'Oct',
            '11' => 'Nov',
            '12' => 'Dec',
        ];
        $records = Team::whereHas('team_sizes', fn($q) => $q->whereBetween('start_period', [databaseDate($request->date_from), databaseDate($request->date_to)]))
            ->with(['team_sizes' => fn($q) => $q->whereBetween('start_period', [databaseDate($request->date_from), databaseDate($request->date_to)])])
            ->get()
            ->map(function($v) {
                $v->local_size = $v->team_sizes->sum('local_size');
                $v->diaspora_size = $v->team_sizes->sum('diaspora_size');
                $v->total = $v->local_size+$v->diaspora_size;
                $v->team_sizes = $v->team_sizes->map(function($v1) {
                    $v1->month = date('m', strtotime($v1->start_period));
                    return $v1;
                });
                return $v;
            });

        switch ($request->output) {
            case 'pdf_print':
                $html = view('reports.pdf.print_team_size_summary', compact('records', 'meta'))->render();
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
                $html = view('reports.pdf.print_team_size_summary', compact('records', 'meta'))->render();
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
                    // $programme_names = $meta['programmes']->pluck('name')->toArray();
                    // $programme_ids = $meta['programmes']->pluck('id')->toArray();
                    
                    // $file = fopen('php://output', 'w');
                    // fputcsv($file, array_merge(['No.', 'Team Name'], $programme_names, ['Total', 'Position']));
                    // foreach ($records as $i => $item) {
                    //     $programme_scores = array_map(function($id) use($item) {
                    //         $score_total = 0;
                    //         foreach ($item->programme_scores as $score) {
                    //             if ($score->programme_id == $id) {
                    //                 $score_total = $score->total;
                    //                 break;
                    //             }
                    //         }
                    //         return $score_total;
                    //     }, $programme_ids);
                    //     fputcsv($file, array_merge([$i+1, $item->name], $programme_scores, [$item->programme_score_total, $item->position]));
                    // }
                    // fclose($file);
                };
                return response()->stream($callback, 200, $headers);
        }
    }

    /**
     * Metric Summary
     */
    public function metricSummary(Request $request)
    {
        if (!$request->post()) {
            $programmes = Programme::whereHas('metrics')->get();
            return view('reports.metric_summary', compact('programmes'));
        }
        
        $filename = 'Metric Summary';
        $meta['title'] = 'Metric Summary';
        $meta['date_from'] = dateFormat($request->date_from);
        $meta['date_to'] = dateFormat($request->date_to);
        $meta['programme'] = Programme::findOrFail($request->programme_id);
        $records = Metric::where('programme_id', request('programme_id'))
            ->whereBetween('date', [databaseDate(request('date_from')), databaseDate(request('date_to'))])
            ->with('team')
            ->get();

        switch ($request->output) {
            case 'pdf_print':
                $html = view('reports.pdf.print_metric_summary', compact('records', 'meta'))->render();
                $headers = [
                    "Content-type" => "application/pdf",
                    "Pragma" => "no-cache",
                    "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                    "Expires" => "0"
                ];
                $pdf = new \Mpdf\Mpdf(config('pdf'));
                $pdf->WriteHTML($html);
                return response()->stream($pdf->Output($filename . '.pdf', 'I'), 200, $headers);
            case 'pdf':
                $html = view('reports.pdf.print_metric_summary', compact('records', 'meta'))->render();
                $pdf = new \Mpdf\Mpdf(config('pdf'));
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
                    // $programme_names = $meta['programmes']->pluck('name')->toArray();
                    // $programme_ids = $meta['programmes']->pluck('id')->toArray();
                    
                    // $file = fopen('php://output', 'w');
                    // fputcsv($file, array_merge(['No.', 'Team Name'], $programme_names, ['Total', 'Position']));
                    // foreach ($records as $i => $item) {
                    //     $programme_scores = array_map(function($id) use($item) {
                    //         $score_total = 0;
                    //         foreach ($item->programme_scores as $score) {
                    //             if ($score->programme_id == $id) {
                    //                 $score_total = $score->total;
                    //                 break;
                    //             }
                    //         }
                    //         return $score_total;
                    //     }, $programme_ids);
                    //     fputcsv($file, array_merge([$i+1, $item->name], $programme_scores, [$item->programme_score_total, $item->position]));
                    // }
                    // fclose($file);
                };
                return response()->stream($callback, 200, $headers);
        }
    }

    /**
     * Monthly Pledge VS Mission
     */
    public function monthlyPledgeVsMission(Request $request)
    {
        if (!$request->post()) {
            return view('reports.monthly_pledge_vs_mission');
        }
        
        $filename = 'Monthly Pledge Vs Mission Summary';
        $meta['title'] = 'Monthly Pledge Vs Mission Summary';
        $meta['date_from'] = dateFormat($request->date_from);
        $meta['date_to'] = dateFormat($request->date_to);
        $meta['programmes'] = Programme::whereHas('metrics', fn($q) => $q->where('team_mission_amount', '>', 0))
            ->where('metric', 'Team-Mission')
            ->get(['id', 'name']); 
        $meta['pledge'] = Programme::where('metric', 'Finance')
            ->whereHas('metrics')
            ->limit(1)
            ->get()
            ->sum('target_amount');

        if (request('has_team')) {
            $filename = 'Team Monthly Pledge Vs Mission Summary';
            $meta['title'] = 'Team Monthly Pledge Vs Mission Summary';

            // team mission expense metrics
            $meta['expense_metrics'] = Metric::whereIn('programme_id', $meta['programmes']->pluck('id')->toArray())
                ->selectRaw("team_id, programme_id, DATE_FORMAT(date, '%Y-%m') month, SUM(team_mission_amount) amount")
                ->groupBY(\DB::raw("DATE_FORMAT(date, '%Y-%m'), programme_id, team_id"))
                ->having('amount', '>', 0)
                ->orderBy('month', 'ASC')
                ->get();
            // finance pledged metrics
            $records = Metric::whereHas('programme', fn($q) => $q->where('metric', 'Finance'))
                ->selectRaw("team_id, DATE_FORMAT(date, '%Y-%m') month")
                ->groupBY(\DB::raw("DATE_FORMAT(date, '%Y-%m'), team_id"))
                ->orderBy('month', 'ASC')
                ->with('team')
                ->get();
        } else {
            // team mission expense metrics
            $meta['expense_metrics'] = Metric::whereIn('programme_id', $meta['programmes']->pluck('id')->toArray())
            ->selectRaw("programme_id, DATE_FORMAT(date, '%Y-%m') month, SUM(team_mission_amount) amount")
            ->groupBY(\DB::raw("DATE_FORMAT(date, '%Y-%m'), programme_id"))
            ->having('amount', '>', 0)
            ->orderBy('month', 'ASC')
            ->get();
            // finance pledged metrics
            $records = Metric::whereHas('programme', fn($q) => $q->where('metric', 'Finance'))
                ->selectRaw("DATE_FORMAT(date, '%Y-%m') month, SUM(grant_amount) pledge")
                ->groupBY(\DB::raw("DATE_FORMAT(date, '%Y-%m')"))
                ->orderBy('month', 'ASC')
                ->get();
        }
        
        
        switch ($request->output) {
            case 'pdf_print':
                $html = view('reports.pdf.print_monthly_pledge_vs_mission', compact('records', 'meta'))->render();
                $headers = [
                    "Content-type" => "application/pdf",
                    "Pragma" => "no-cache",
                    "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                    "Expires" => "0"
                ];
                $pdf = new \Mpdf\Mpdf(config('pdf'));
                $pdf->WriteHTML($html);
                return response()->stream($pdf->Output($filename . '.pdf', 'I'), 200, $headers);
            case 'pdf':
                $html = view('reports.pdf.print_monthly_pledge_vs_mission', compact('records', 'meta'))->render();
                $pdf = new \Mpdf\Mpdf(config('pdf'));
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
                    // $programme_names = $meta['programmes']->pluck('name')->toArray();
                    // $programme_ids = $meta['programmes']->pluck('id')->toArray();
                    
                    // $file = fopen('php://output', 'w');
                    // fputcsv($file, array_merge(['No.', 'Team Name'], $programme_names, ['Total', 'Position']));
                    // foreach ($records as $i => $item) {
                    //     $programme_scores = array_map(function($id) use($item) {
                    //         $score_total = 0;
                    //         foreach ($item->programme_scores as $score) {
                    //             if ($score->programme_id == $id) {
                    //                 $score_total = $score->total;
                    //                 break;
                    //             }
                    //         }
                    //         return $score_total;
                    //     }, $programme_ids);
                    //     fputcsv($file, array_merge([$i+1, $item->name], $programme_scores, [$item->programme_score_total, $item->position]));
                    // }
                    // fclose($file);
                };
                return response()->stream($callback, 200, $headers);
        }
    }

    /**
     * Team Report Card
     */
    public function teamReportCard(Request $request)
    {
        if (!$request->post()) {
            $teams = Team::whereHas('metrics')->get();
            return view('reports.team_report_card', compact('teams'));
        }
        
        $filename = 'Team Report Card';
        $meta['title'] = "Report Card";
        $meta['date_from'] = dateFormat($request->date_from);
        $meta['date_to'] = dateFormat($request->date_to);
        $meta['team'] = Team::findOrFail($request->team_id);

        $rankedTeams = $this->rankTeamsFromScores([$request->date_from, $request->date_to]);
        $meta['rankedTeam'] = $rankedTeams->where('id', $meta['team']->id)->first(); 
        if (!$meta['rankedTeam']) return errorHandler('Programme scores required to generate report!');
        
        $programme_ids = $meta['rankedTeam']->programme_scores->pluck('programme_id');
        $records = Programme::whereIn('id', $programme_ids)->get();
            
        switch ($request->output) {
            case 'pdf_print':
                $html = view('reports.pdf.print_team_report_card', compact('records', 'meta'))->render();
                $headers = [
                    "Content-type" => "application/pdf",
                    "Pragma" => "no-cache",
                    "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                    "Expires" => "0"
                ];
                $pdf = new \Mpdf\Mpdf(config('pdf'));
                $pdf->WriteHTML($html);
                return response()->stream($pdf->Output($filename . '.pdf', 'I'), 200, $headers);
            case 'pdf':
                $html = view('reports.pdf.print_team_report_card', compact('records', 'meta'))->render();
                $pdf = new \Mpdf\Mpdf(config('pdf'));
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
                    // $programme_names = $meta['programmes']->pluck('name')->toArray();
                    // $programme_ids = $meta['programmes']->pluck('id')->toArray();
                    
                    // $file = fopen('php://output', 'w');
                    // fputcsv($file, array_merge(['No.', 'Team Name'], $programme_names, ['Total', 'Position']));
                    // foreach ($records as $i => $item) {
                    //     $programme_scores = array_map(function($id) use($item) {
                    //         $score_total = 0;
                    //         foreach ($item->programme_scores as $score) {
                    //             if ($score->programme_id == $id) {
                    //                 $score_total = $score->total;
                    //                 break;
                    //             }
                    //         }
                    //         return $score_total;
                    //     }, $programme_ids);
                    //     fputcsv($file, array_merge([$i+1, $item->name], $programme_scores, [$item->programme_score_total, $item->position]));
                    // }
                    // fclose($file);
                };
                return response()->stream($callback, 200, $headers);
        }
    }
}