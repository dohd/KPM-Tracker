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
        // charts
        $startDate = date('Y-m-d', strtotime(date('Y-01-01')));
        $endDate = date('Y-m-d', strtotime(date('Y-12-31')));

        $teamExists = Team::whereHas('team_sizes', fn($q) => $q->whereBetween('start_period', [$startDate, $endDate]))->exists();
        if (!$teamExists) {
            $yr = date('Y')-1;
            $startDate = date($yr . '-m-d', strtotime(date($yr . '-01-01')));
            $endDate = date($yr . '-m-d', strtotime(date($yr . '-12-31')));
        }

        // counts
        $numProgrammes = Programme::whereBetween('created_at', [$startDate, $endDate])->count();
        $numTeams = Team::whereHas('team_sizes', fn($q) => $q->whereBetween('start_period', [$startDate, $endDate]))->count();
        
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

        // finance and mission contributions
        $sumContributions = Metric::whereBetween('date', [$startDate, $endDate])
            ->sum(\DB::raw('grant_amount+team_mission_amount'));
        $metrics = Metric::whereBetween('date', [$startDate, $endDate])
            ->where(fn($q) => $q->where('grant_amount', '>', 0)->orWhere('team_mission_amount', '>', 0))
            ->selectRaw("team_id, programme_id, SUM(grant_amount) finance, SUM(team_mission_amount) mission")
            ->groupBY(\DB::raw("team_id, programme_id"))
            ->with([
                'team' => fn($q) => $q->select('id', 'name'),
                'programme' => fn($q) => $q->select('id', 'metric'),
            ])
            ->get()
            ->reduce(function($init, $curr) {
                $key = $curr->team_id;
                $mod = @$init[$key];
                if ($mod) {
                    if ($mod['team_id'] == $key) {
                        if ($curr->programme->metric == 'Finance') {
                            $mod['finance'] += floatval($curr->finance);
                        } else {
                            $mod['mission'] += floatval($curr->mission);
                        }
                        $mod['total'] = $mod['finance'] + $mod['mission'];
                        $init[$key] = $mod;
                    }
                } else {
                    $init[$key] = [
                        'team_id' => $curr->team_id,
                        'name' =>  $curr->team->name,
                        'metric' => $curr->programme->metric,
                        'finance' => +$curr->finance,
                        'mission' => +$curr->mission,
                        'total' => 0,
                    ];
                }
                return $init;
            }, []);        
        $contributions = array_values($metrics);

        return view('home', compact(
            // other
            'startDate', 'endDate',
            // counts
            'numProgrammes', 'numTeams', 'sumContributions',
            // charts
            'rankedTeams', 'teams', 'contributions',
        ));
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
