<?php

namespace App\Http\Controllers;

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
        $numProgrammes = Programme::count();
        $numTeams = Team::count();
        $numMembers = User::count();

        return view('home', compact('numProgrammes', 'numTeams', 'numMembers'));
    }

    public function event_calendar()
    {
        $events = collect();
        
        return view('layouts.event_calendar', compact('events'));
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
