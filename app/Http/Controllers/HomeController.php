<?php

namespace App\Http\Controllers;

use App\Models\metric\Metric;
use App\Models\programme\Programme;
use App\Models\team\Team;
use App\Models\User;

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
            // counts
            'numProgrammes', 'numTeams', 'sumContributions',
            // charts
            'rankedTeams',
            'teams'
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
