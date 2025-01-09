<?php

namespace App\Http\Controllers;

use App\Models\metric\Metric;
use App\Models\programme\Programme;
use App\Models\team\Team;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // counts
        $numProgrammes = Programme::count();
        $numTeams = Team::count();
        $sumContributions = Metric::sum(\DB::raw('grant_amount+team_mission_amount'));

        // charts
        $startDate = date('Y-m-d', strtotime(date('Y-01-01')));
        $endDate = date('Y-m-d', strtotime(date('Y-12-31')));

        $teamExists = Team::whereHas('team_sizes', fn($q) => $q->whereBetween('start_period', [$startDate, $endDate]))->exists();
        if (!$teamExists) {
            $yr = date('Y')-1;
            $startDate = date($yr . '-m-d', strtotime(date($yr . '-01-01')));
            $endDate = date($yr . '-m-d', strtotime(date($yr . '-12-31')));
        }

        $rankedTeams = rankTeamsFromScores([$startDate, $endDate]);
        $teams = Team::whereHas('team_sizes', fn($q) => $q->whereBetween('start_period', [$startDate, $endDate]))
            ->with(['team_sizes' => fn($q) => $q->whereBetween('start_period', [$startDate, $endDate])])
            ->get(['id', 'name'])
            ->map(function($v) {
                $v->local_size = 0;
                $v->diaspora_size = 0;
                $v->total = 0;
                if ($v->team_sizes->last()) {
                    $v->local_size = $v->team_sizes->last()->local_size;
                    $v->diaspora_size = $v->team_sizes->last()->diaspora_size;
                    $v->total = $v->local_size + $v->diaspora_size;
                }
                $v->team_sizes = $v->team_sizes->map(function($v1) {
                    $v1->month = date('m', strtotime($v1->start_period));
                    return $v1;
                });
                return $v;
            });

        return view('home', compact(
            // other
            'startDate', 'endDate',
            // counts
            'numProgrammes', 'numTeams', 'sumContributions',
            // charts
            'rankedTeams',
            'teams'
        ));
    }

    public function actualAndMissionGraphData(Request $request)
    {
        $input = $request->only('month', 'date_from', 'date_to');

        // team finance contribution metrics
        $financeMetrics = Metric::whereMonth('date', $input['month'])
            ->whereBetween('date', [$input['date_from'], $input['date_to']])
            ->where(fn($q) => $q->where('grant_amount', '>', 0)->orWhere('team_mission_amount', '>', 0))
            ->selectRaw("team_id, programme_id, DATE_FORMAT(date, '%Y-%m') month, SUM(grant_amount) amount")
            ->groupBY(\DB::raw("DATE_FORMAT(date, '%Y-%m'), programme_id, team_id"))
            ->having('amount', '>', 0)
            ->orderBy('month', 'ASC')
            ->with([
                'team' => fn($q) => $q->select('id', 'name'),
                'programme' => fn($q) => $q->select('id', 'metric'),
            ])
            ->get();

        // team mission contribution metrics
        $missionMetrics = Metric::whereMonth('date', $input['month'])
            ->whereBetween('date', [$input['date_from'], $input['date_to']])
            ->where(fn($q) => $q->where('grant_amount', '>', 0)->orWhere('team_mission_amount', '>', 0))
            ->selectRaw("team_id, programme_id, DATE_FORMAT(date, '%Y-%m') month, SUM(team_mission_amount) amount")
            ->groupBY(\DB::raw("DATE_FORMAT(date, '%Y-%m'), programme_id, team_id"))
            ->having('amount', '>', 0)
            ->orderBy('month', 'ASC')
            ->with([
                'team' => fn($q) => $q->select('id', 'name'),
                'programme' => fn($q) => $q->select('id', 'metric'),
            ])
            ->get();

        $metrics = collect()->merge($financeMetrics)->merge($missionMetrics);
        $metrics = $metrics->reduce(function($init, $curr) {
            $key = $curr->team_id;
            $mod = @$init[$key];
            if ($mod) {
                if ($mod['team_id'] == $curr->team_id) {
                    if ($curr->programme->metric == 'Finance') {
                        $mod['finance'] += floatval($curr->amount);
                    } else {
                        $mod['mission'] += floatval($curr->amount);
                    }
                    $mod['total'] = $mod['finance'] + $mod['mission'];
                    $init[$key] = $mod;
                }
            } else {
                $init[$key] = [
                    'team_id' => $curr->team_id,
                    'name' => $curr->team->name,
                    'metric' => $curr->programme->metric,
                    'finance' => 0,
                    'mission' => 0,
                    'total' => 0,
                ];
                if ($init[$key]['metric'] == 'Finance') {
                    $init[$key] = array_replace($init[$key], ['finance' => +$curr->amount, 'total' => +$curr->amount,]);
                } else {
                    $init[$key] = array_replace($init[$key], ['mission' => +$curr->amount, 'total' => +$curr->amount,]);
                }
            }
            return $init;
        }, []);
        
        return response()->json(array_values($metrics));
    }


    public function register()
    {
        return view('register');
    }

    public function login()
    {
        return view('login');
    }

    public function error_404()
    {
        return view('error_404');
    }
}
