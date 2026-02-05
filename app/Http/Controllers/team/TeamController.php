<?php

namespace App\Http\Controllers\team;

use App\Http\Controllers\Controller;
use App\Models\team\Team;
use App\Models\team\TeamMember;
use App\Models\team\TeamSize;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teams = Team::latest()->with('team_sizes')->get();

        return view('teams.index', compact('teams'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // permit only chair to create a team 
        if (auth()->user()->user_type !== 'chair') {
            return redirect()->route('teams.index')
                ->with('error', "You don't have sufficient rights to perform this action!");
        }

        return view('teams.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            // member details
            'full_name' => ['required', 'array', 'min:1'],
            'category' => ['required', 'array', 'min:1'],           
        ]);

        $basicDetails = $request->only('is_active', 'name', 'max_guest');
        $memberDetails = $request->only('full_name', 'category', 'df_name', 'phone_no', 'physical_addr');

        try {    
            DB::beginTransaction();

            $basicDetails['max_guest'] = numberClean($basicDetails['max_guest']);
            $team = Team::create($basicDetails);

            $n = count($memberDetails['full_name']);
            $memberDetails['team_id'] = array_fill(0, $n, $team->id);
            $memberDetails['user_id'] = array_fill(0, $n, auth()->id());
            $memberDetails['ins'] = array_fill(0, $n, auth()->user()->ins);
            $memberDetails = collect(databaseArray($memberDetails))
                ->unique('full_name')->values()->toArray();
            TeamMember::insert($memberDetails);

            DB::commit();

            return redirect(route('teams.index'))->with(['success' => 'Team created successfully']);
        } catch (\Throwable $th) {
            return errorHandler('Error creating Team!', $th);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Team $team)
    {
        return view('teams.view', compact('team'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Team $team)
    {
        return view('teams.edit', compact('team'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Team $team)
    {
        $request->validate([
            'name' => 'required',
            // member details
            // 'full_name' => ['required', 'array', 'min:1'],
            // 'category' => ['required', 'array', 'min:1'],          
        ]);
        
        $basicDetails = $request->only('is_active', 'name', 'max_guest');
        $memberDetails = $request->only('master_id', 'full_name', 'category', 'df_name', 'phone_no', 'physical_addr');
        $confirmationDetails = $request->only('team_size_id', 'start_date', 'local_size', 'diaspora_size', 'dormant_size');
        $memberCategories = []; // pair member_id -> category
        $checkedRowIds = [];
        foreach ($request->all() as $key => $value) {
            if (preg_match('/^checked_\d+$/', $key)) {
                $checkedRowIds[] = $value;
            }
            if (preg_match('/^membercat_\d+$/', $key)) {
                $value1 = [];
                foreach ($value as $i => $cat) {
                    $pair = explode('-', $cat);
                    $value1[$pair[0]] = $pair[1];
                }
                $memberCategories[] = $value1;
            }
        }

        try {   
            DB::beginTransaction();

            $basicDetails['max_guest'] = numberClean($basicDetails['max_guest']);
            $basicDetails['is_active'] = $basicDetails['is_active'] ?? null; 
            $team->update($basicDetails);

            // create or update member
            $memberDetails = databaseArray($memberDetails);              
            foreach ($memberDetails as $key => $item) {
                $member = $team->members()->find($item['master_id'] ?? null);
                unset($item['master_id']);
                if ($member) $member->update($item);
                elseif ($item['full_name'] && $item['category']) {
                    $team->members()->create($item);
                }
            }

            $team->load(['members', 'team_sizes', 'verify_members']);
            $verifiedDates = $team->team_sizes
                ->whereNotNull('verified')
                ->pluck('start_period');

            // manage team size and member verification
            $verifyMembersData = [];
            $teamSizesData = [];
            $startDates = $confirmationDetails['start_date'] ?? [];
            foreach($startDates as $key => $date) {
                $date = databaseDate($date);
                $month = Carbon::parse($date)->month;
                $year = Carbon::parse($date)->year;

                // add member verification for the month
                $rowCategories = $memberCategories[$key] ?? [];
                $memberIds = $checkedRowIds[$key] ?? [];
                $teamMembers = $team->members->whereIn('id', $memberIds);
                // delete non-verified records 
                $team->verify_members()
                    ->whereNotIn('date', $verifiedDates)
                    ->whereYear('date', $year)
                    ->delete();
                foreach ($teamMembers as $member) {
                    if (!in_array($date, $verifiedDates->toArray())) {
                        $verifyMembersData[] = [
                            'team_member_id' => $member->id,
                            'category' => $rowCategories[$member->id] ?? $member->category,
                            'date' => $date,
                            'checked' => 1,
                        ];                     
                    }
                }

                // update team size for the month
                $localSize = $confirmationDetails['local_size'][$key] ?? 0;
                $diasporaSize = $confirmationDetails['diaspora_size'][$key] ?? 0;
                $dormantSize = $confirmationDetails['dormant_size'][$key] ?? 0;
                $verifiedMemberCount = $team->verify_members()
                    ->whereMonth('date', $month)
                    ->whereYear('date', $year)
                    ->selectRaw('YEAR(date) year, MONTH(date) month, category, COUNT(*) size')
                    ->groupBy('year', 'month', 'category')
                    ->pluck('size', 'category');
                if ($verifiedMemberCount->count()) {
                    $localSize = $verifiedMemberCount['local'] ?? 0;
                    $diasporaSize = $verifiedMemberCount['diaspora'] ?? 0;
                    $dormantSize = $verifiedMemberCount['dormant'] ?? 0;                    
                }
                
                // delete non-verified records
                $team->team_sizes()
                    ->whereNotIn('start_period', $verifiedDates)
                    ->whereYear('start_period', $year)
                    ->delete();
                if (!in_array($date, $verifiedDates->toArray())) {
                    $teamSizesData[] = [
                        'start_period' => $date,
                        'local_size' => numberClean($localSize),
                        'diaspora_size' => numberClean($diasporaSize),
                        'dormant_size' => numberClean($dormantSize),
                    ];                  
                }
            }

            foreach ($verifyMembersData as $key => $value) {
                $team->verify_members()->create($value);
            }
            foreach ($teamSizesData as $key => $value) {
                $team->team_sizes()->create($value);
            }

            DB::commit();

            return redirect(route('teams.index'))->with(['success' => 'Team updated successfully']);
        } catch (\Throwable $th) {
            return errorHandler('Error updating Team!', $th);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Team $team)
    {
        // permit only chair to delete a team 
        if (auth()->user()->user_type != 'chair') {
            return errorHandler("You don't have the rights to delete a team!");
        }

        try {   
            DB::beginTransaction();    
            
            $team->team_sizes()->delete();
            $team->delete();

            DB::commit();
            return redirect(route('teams.index'))->with(['success' => 'Team deleted successfully']);
        } catch (\Throwable $th) {
            return errorHandler('Error deleting Team!', $th);
        }
    }

    /**
     * Fetch Teams for verification
     * */
    public function verificationTeams(Request $request)
    {
        $request->validate([
            'month' => 'required',
            'year' => 'required',
        ]);

        $teams = Team::whereHas('team_sizes', function($q) {
            $q->whereYear('start_period', request('year'))
            ->whereMonth('start_period', request('month'));
        })
        ->get(['id', 'name'])
        ->map(function($team) use($request) {
            $teamSizes = $team->teamSizesForPeriod($request->month, $request->year);
            $team->team_size = $teamSizes->first();
            return $team;
        });

        return response()->json($teams);
    }

    public function verifyTeams(Request $request)
    {
        $dataItems = $request->except('_token');

        try {
            DB::beginTransaction();

            foreach ($dataItems['id'] as $key => $id) {
                $checked = $dataItems['verified'][$key];
                $note = $dataItems['verified_note'][$key];
                TeamSize::find($id)->update([
                    'verified_at' => now(),
                    'verified' => $checked,
                    'verified_by' => auth()->id(),
                    'verified_note' => $note,
                ]);
            }

            DB::commit();

            return redirect(route('teams.index'))->with(['success' => 'Team verification successfully']);
        } catch (Exception $e) {
            return errorHandler('Error verifying teams', $e);
        }
    }
}
