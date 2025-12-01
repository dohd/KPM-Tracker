<?php

if (!function_exists('successFlashMessage')) {
    function successFlashMessage() {
        return '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-1"></i>
        <strong></strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    }
}

if (!function_exists('errorFlashMessage')) {
    function errorFlashMessage() {
        return '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-octagon me-1"></i>
        <strong></strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    }
}

if (!function_exists('spinner')) {
    function spinner() {
        return '<div class="d-flex justify-content-center"><div class="spinner-border text-primary ml-4" role="status"><span class="sr-only"></span></div></div>';
    }
}

if (!function_exists('inputClean')) {
    function inputClean($input=[])
    {   
        $dates = ['target_date', 'date', 'start_date', 'end_date', 'date_from', 'date_to', 'valid_from', 'valid_to'];
        $totals = ['target_amount', 'amount', 'total', 'grandtotal', 'subtotal', 'tax', 'rate', 'taxable', 'budget'];
        foreach ($input as $key => $value) {
            if (!is_array($value)) {
                $input[$key] = trim($value);
                if (in_array($key, $dates)) $input[$key] = databaseDate($value);
                if (in_array($key, $totals)) $input[$key] = numberClean($value);
            }
        }
        return $input;
    }
}

if (!function_exists('fillArray')) {
    function fillArray($main=[], $params=[])
    {
        foreach ($params as $key => $value) {
            $main[$key] = $value;
        }
        return $main;
    }
}

if (!function_exists('fillArrayRecurse')) {
    function fillArrayRecurse($main=[], $params=[])
    {
        foreach ($main as $i => $row) {
            foreach ($params as $key => $value) {
                $main[$i][$key] = $value;
            }
        }
        return $main;
    }
}

if (!function_exists('explodeArray')) {
    function explodeArray($separator='', $input=[])
    {
        $input_mod = [];
        foreach ($input as $key => $value) {
            $input_mod[] = explode($separator, $value);
        }
        return $input_mod;
    }
}

if (!function_exists('numberClean')) {
    function numberClean($value='')
    {
        return floatval(str_replace(',', '', $value)); 
    }
}

if (!function_exists('numberFormat')) {
    function numberFormat($number=0, $deci=2)
    {
        return number_format($number, $deci);
    }
}

if (!function_exists('dateFormat')) {
    function dateFormat($date='', $format='d-m-Y')
    {
        if (!$date) return date('d-m-Y');
        return date($format, strtotime($date));
    }
}

if (!function_exists('timeFormat')) {
    function timeFormat($time='', $format='h:i a')
    {
        if (!$time) return date("h:i:s a");
        return date($format, strtotime($time));
    }
}

if (!function_exists('databaseDate')) {
    function databaseDate($date='')
    {
        if (!$date) return date('Y-m-d');
        return date('Y-m-d', strtotime($date));
    }
}

if (!function_exists('databaseArray')) {
    function databaseArray($input=[])
    {
        $input_mod = [];
        foreach ($input as $key => $value) {
            foreach ($value as $j => $v) {
                $input_mod[$j][$key] = $v;
            }
        }
        return $input_mod;
    }
}

if (!function_exists('browserLog')) {
    function browserLog(...$messages)
    {
        foreach ($messages as $value) {
            echo '<script>console.log(' . json_encode($value) . ')</script>';
        }
    }
}

if (!function_exists('printLog')) {
    function printLog(...$messages)
    {
        foreach ($messages as $value) {
            error_log(print_r($value, 1));
        }
    }
}

if (!function_exists('errorHandler')) {
    function errorHandler($msg='', $e=null)
    {
        if ($e && env('APP_ENV') == 'local' && env('APP_DEBUG')) dd($e->getMessage(), $e);
        if ($e instanceof \Illuminate\Validation\ValidationException) throw $e;
        if ($e) \Illuminate\Support\Facades\Log::error($e->getMessage() . ' {user_id:'. auth()->user()->id . '} at ' . $e->getFile() . ':' . $e->getLine());
        return redirect()->back()->with(['error' => $msg ?: 'Internal server error! Please try again later.']);
    }
}

if (!function_exists('tidCode')) {
    function tidCode($prefix='', $num=0, $count=3)
    {
        if ($prefix) {
            $prefixInst = \Illuminate\Support\Facades\DB::table('prefixes')->where('name', $prefix)->first();
            if ($prefixInst) $prefix = "{$prefixInst->code}{$prefixInst->sep}";
        }
        return $prefix . sprintf('%0'.$count.'d', $num);
    }
}

if (!function_exists('rankTeamsFromScores')) {
    function rankTeamsFromScores($date_range=[])
    {
        $assigned_scores = \App\Models\assign_score\AssignScore::whereDate('date_from', '>=', $date_range[0])
            ->whereDate('date_to', '<=', $date_range[1])
            ->get(['id', 'programme_id', 'team_id']);
        $teams = \App\Models\team\Team::whereIn('id', $assigned_scores->pluck('team_id')->toArray())->get(['id', 'name']);
        foreach ($teams as $key => $team) {
            $team->programme_scores = $team->assigned_scores()
                ->selectRaw('programme_id, SUM(net_points) as total')
                ->whereIn('assign_scores.id', $assigned_scores->pluck('id')->toArray())
                ->groupBy('programme_id')
                ->with(['programme' => fn($q) => $q->select('id', 'name')])
                ->get();
            
            foreach ($team->programme_scores as $i => $team_prog_score) {
                $programme = \App\Models\programme\Programme::find($team_prog_score->programme_id);
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
}

if (!function_exists('confirmTeamCompositionUpdated')) {
    function confirmTeamCompositionUpdated() {
        $team = optional(auth()->user()->team);
        foreach ($team->team_sizes as $item) {
            $startPeriod = Carbon\Carbon::parse($item->start_period);
            $startMonth = Carbon\Carbon::today()->startOfMonth();
            $endMonth =  Carbon\Carbon::today()->endOfMonth();
            if ($startPeriod->between($startMonth, $endMonth)) {
                return true;
            }
        }
        return false;
    }
}
