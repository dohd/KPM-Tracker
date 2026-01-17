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
            'df_name' => ['required', 'array', 'min:1'],
            'phone_no' => ['required', 'array', 'min:1'],
            'physical_addr' => ['required', 'array', 'min:1'],            
        ]);

        $basic_details = $request->only('is_active', 'name', 'max_guest');
        $member_details = $request->only('full_name', 'category', 'df_name', 'phone_no', 'physical_addr');

        try {    
            DB::beginTransaction();

            $team = Team::create($basic_details);

            $n = count($member_details['full_name']);
            $member_details['team_id'] = array_fill(0, $n, $team->id);
            $member_details['user_id'] = array_fill(0, $n, auth()->id());
            $member_details['ins'] = array_fill(0, $n, auth()->user()->ins);
            $member_details = collect(databaseArray($member_details))
                ->unique('full_name')->values()->toArray();
            TeamMember::insert($member_details);

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
            'full_name' => ['required', 'array', 'min:1'],
            'category' => ['required', 'array', 'min:1'],
            'df_name' => ['required', 'array', 'min:1'],
            'phone_no' => ['required', 'array', 'min:1'],
            'physical_addr' => ['required', 'array', 'min:1'],            
        ]);
        
        $basicDetails = $request->only('is_active', 'name', 'max_guest');
        $memberDetails = $request->only('member_id', 'full_name', 'category', 'df_name', 'phone_no', 'physical_addr');
        $confirmationDetails = $request->only('start_date', 'local_size', 'diaspora_size', 'dormant_size');
        $checkedRowIds = [];
        foreach ($request->all() as $key => $value) {
            if (preg_match('/^checked_\d+$/', $key)) {
                $checkedRowIds[] = $value;
            }
        }

        try {   
            DB::beginTransaction();

            $team->update($basicDetails);

            // create or update member
            $memberDetails = collect(databaseArray($memberDetails))
                ->unique('full_name')->values()->toArray();                
            foreach ($memberDetails as $key => $item) {
                $member = $team->members()->find($item['member_id'] ?? null);
                unset($item['member_id']);
                if ($member) $member->update($item);
                else $team->members()->create($item);
            }

            // manage team size and member verification
            $startDates = $confirmationDetails['start_date'] ?? [];
            foreach($startDates as $key => $date) {
                $date = databaseDate($date);
                $month = Carbon::parse($date)->month;
                $year = Carbon::parse($date)->year;

                // add member verification for the month
                $memberIds = $checkedRowIds[$key] ?? [];
                $teamMembers = $team->members()
                    ->whereIn('team_members.id', $memberIds)
                    ->get(['id', 'team_id', 'category']);                
                if ($teamMembers->count()) {
                    if ($key == 0) {
                        $team->verify_members()
                            ->whereYear('date', $year)
                            ->delete();                        
                    }
                    foreach ($teamMembers as $member) {
                        $team->verify_members()->create([
                            'team_member_id' => $member->id,
                            'category' => $member->category,
                            'date' => $date,
                            'checked' => 1,
                        ]);
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
                
                if ($key == 0) {
                    $team->team_sizes()
                        ->whereYear('start_period', $year)
                        ->delete();                    
                }
                $team->team_sizes()->create([
                    'start_period' => $date,
                    'local_size' => numberClean($localSize),
                    'diaspora_size' => numberClean($diasporaSize),
                    'dormant_size' => numberClean($dormantSize),
                ]);  
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

        $teams = Team::whereHas('team_sizes', function($q) use($request) {
            $q->whereYear('start_period', $request->year)->whereMonth('start_period', $request->month);
        })
        ->get(['id', 'name'])
        ->map(function($team) use($request) {
            $team->team_size = $team->teamSizesForPeriod($request->month, $request->year)->first();
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
