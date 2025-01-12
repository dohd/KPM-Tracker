<?php

namespace App\Http\Controllers\report;

use App\Http\Controllers\Controller;
use App\Models\metric\Metric;
use App\Models\programme\Programme;
use App\Models\team\Team;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
        $rankedTeams = rankTeamsFromScores([$input['date_from'], $input['date_to']]);
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
        }
    }

    /**
     * Team Size Summary
     */
    public function teamSizeSummary(Request $request)
    {
        if (!$request->post()) {
            return view('reports.team_size_summary');
        }

        $input = inputClean($request->except('_token'));

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
        $records = Team::whereHas('team_sizes', fn($q) => $q->whereBetween('start_period', [$input['date_from'], $input['date_to']]))
            ->with(['team_sizes' => fn($q) => $q->whereBetween('start_period', [$input['date_from'], $input['date_to']])])
            ->get(['id', 'name'])
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
        }
    }

    /**
     *  Program Metrics Summary
     */
    public function metricSummary(Request $request)
    {
        if (!$request->post()) {
            $programmes = Programme::whereHas('metrics')->where('is_active', 1)->get();
            return view('reports.metric_summary', compact('programmes'));
        }
        
        $filename = 'Program Metrics Summary';
        $meta['title'] = 'Program Metrics Summary';
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
        }
    }

    /**
     * Monthly Pledge Vs Actual
     */
    public function monthlyPledge(Request $request)
    {
        if (!$request->post()) {
            return view('reports.monthly_pledge');
        }

        $input = inputClean($request->except('_token'));
        
        $filename = 'Team Monthly Pledge';
        $meta['title'] = 'Team Monthly Pledge Vs Actual';
        $meta['date_from'] = dateFormat($request->date_from);
        $meta['date_to'] = dateFormat($request->date_to);

        $pledgeProgrammes = Programme::where('metric', 'Finance')
            ->whereHas('metrics', fn($q) => $q->whereBetween('date', [$input['date_from'], $input['date_to']]))
            ->orderBy('amount_perc_by', 'ASC')
            ->get();

        // finance pledged metrics
        $records = Metric::whereBetween('date', [$input['date_from'], $input['date_to']])
            ->whereHas('programme', fn($q) => $q->where('metric', 'Finance'))
            ->selectRaw("team_id, DATE_FORMAT(date, '%Y-%m') month, SUM(grant_amount) amount")
            ->groupBY(\DB::raw("DATE_FORMAT(date, '%Y-%m'), team_id"))
            ->orderBy('month', 'ASC')
            ->with('team')
            ->get()
            ->map(function($v) use($pledgeProgrammes) {
                $endDate = Carbon::parse("{$v->month}-01")->endOfMonth()->format('Y-m-d');
                $programme = $pledgeProgrammes->where('amount_perc_by', '>=', $endDate)->first();
                if ($programme) {
                    $v->pledge = round($programme->target_amount*$programme->amount_perc*0.01, 2);
                } else {
                    $programme = $pledgeProgrammes->first();
                    if ($programme) $v->pledge = $programme->target_amount;
                }
                return $v;
            });
        
        switch ($request->output) {
            case 'pdf_print':
                $html = view('reports.pdf.print_monthly_pledge', compact('records', 'meta'))->render();
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
                $html = view('reports.pdf.print_monthly_pledge', compact('records', 'meta'))->render();
                $pdf = new \Mpdf\Mpdf(config('pdf'));
                $pdf->WriteHTML($html);
                return $pdf->Output($filename . '.pdf', 'D');
            
        }
    }

    /**
     * Monthly Pledge And Mission
     */
    public function monthlyPledgeVsMission(Request $request)
    {
        if (!$request->post()) {
            return view('reports.monthly_pledge_vs_mission');
        }

        $input = inputClean($request->except('_token'));
        
        $filename = 'Monthly Pledge And Mission Report';
        $meta['title'] = 'Monthly Pledge And Mission Report';
        $meta['date_from'] = dateFormat($request->date_from);
        $meta['date_to'] = dateFormat($request->date_to);
        $meta['programmes'] = Programme::whereHas('metrics', function($q) use($input) {
            $q->whereBetween('date', [$input['date_from'], $input['date_to']]);
            $q->where(fn($q) => $q->where('grant_amount', '>', 0)->orWhere('team_mission_amount', '>', 0));
        })
        ->whereIn('metric', ['Finance', 'Team-Mission'])
        ->get(['id', 'name', 'metric'])
        ->sortBy(function($v) {
            return array_search($v->metric, [
                'Finance', 'Team-Mission'
            ]);
        });

        // team finance contribution metrics
        $meta['finance_contrib_metrics'] = Metric::whereBetween('date', [$input['date_from'], $input['date_to']])
            ->whereIn('programme_id', $meta['programmes']->pluck('id')->toArray())
            ->selectRaw("team_id, programme_id, DATE_FORMAT(date, '%Y-%m') month, SUM(grant_amount) amount")
            ->groupBY(\DB::raw("DATE_FORMAT(date, '%Y-%m'), programme_id, team_id"))
            ->having('amount', '>', 0)
            ->orderBy('month', 'ASC')
            ->get();

        // team mission contribution metrics
        $meta['mission_contrib_metrics'] = Metric::whereBetween('date', [$input['date_from'], $input['date_to']])
            ->whereIn('programme_id', $meta['programmes']->pluck('id')->toArray())
            ->selectRaw("team_id, programme_id, DATE_FORMAT(date, '%Y-%m') month, SUM(team_mission_amount) amount")
            ->groupBY(\DB::raw("DATE_FORMAT(date, '%Y-%m'), programme_id, team_id"))
            ->having('amount', '>', 0)
            ->orderBy('month', 'ASC')
            ->get();
            
        // finance pledged metrics
        $records = Metric::whereBetween('date', [$input['date_from'], $input['date_to']])
            ->whereHas('programme', fn($q) => $q->whereIn('metric', ['Finance', 'Team-Mission']))
            ->where(function($q) {
                $q->where('grant_amount', '>', 0);
                $q->orWhere('team_mission_amount', '>', 0);
            })
            ->selectRaw("DATE_FORMAT(date, '%Y-%m') month, team_id")
            ->groupBY(\DB::raw("DATE_FORMAT(date, '%Y-%m'), team_id"))
            ->orderBy('month', 'ASC')
            ->get();
        
        switch ($request->output) {
            case 'pdf_print':
                $html = view('reports.pdf.print_monthly_pledge_vs_mission', compact('records', 'meta'))->render();
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
                $html = view('reports.pdf.print_monthly_pledge_vs_mission', compact('records', 'meta'))->render();
                $pdf = new \Mpdf\Mpdf(array_replace(config('pdf'), ['format' => 'A4-L']));
                $pdf->WriteHTML($html);
                return $pdf->Output($filename . '.pdf', 'D');
            
        }
    }

    /**
     * Score Variance
     */
    public function scoreVariance(Request $request)
    {
        if (!$request->post()) {
            $teams = Team::get();
            return view('reports.score_variance', compact('teams'));
        }

        $input = inputClean($request->except('_token'));
        
        $filename = 'Score Variance Summary';
        $meta['title'] = 'Score Variance Summary';
        $meta['date_from'] = dateFormat($request->date_from);
        $meta['date_to'] = dateFormat($request->date_to);

        $meta['team'] = Team::where('id', request('team_id'))
            ->whereHas('assigned_scores', function($q) use($input) {
                $q->whereDate('date_from', '>=', $input['date_from']);
                $q->whereDate('date_to', '<=', $input['date_to']);
            })
            ->first();

        $records = Programme::whereHas('assignScores', function($q) use($input) {
            $q->whereDate('date_from', '>=', $input['date_from']);
            $q->whereDate('date_to', '<=', $input['date_to']);
        })
        ->with(['assignScores' => function($q) use($input) {
            $q->select('team_id', 'programme_id', 'net_points');
            $q->whereDate('date_from', '>=', $input['date_from']);
            $q->whereDate('date_to', '<=', $input['date_to']);
        }])
        ->get();
        
        switch ($request->output) {
            case 'pdf_print':
                $html = view('reports.pdf.print_score_variance', compact('records', 'meta'))->render();
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
                $html = view('reports.pdf.print_score_variance', compact('records', 'meta'))->render();
                $pdf = new \Mpdf\Mpdf(config('pdf'));
                $pdf->WriteHTML($html);
                return $pdf->Output($filename . '.pdf', 'D');
            
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

        $rankedTeams = rankTeamsFromScores([$request->date_from, $request->date_to]);
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
        }
    }
}