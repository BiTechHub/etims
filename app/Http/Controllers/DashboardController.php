<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\AgencyType;
use App\Models\Group;
use App\Models\Nomination;
use App\Models\Participant;
use App\Models\ProgrammeManagement;
use App\Models\Sponsor;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

public function index()
{
    $facultiesCount   = User::where('user_type', 'Faculty')->count();
    $programmesCount  = ProgrammeManagement::count();
    $nominationsCount = Nomination::count();

    return view('dashboard.static-dashboard', compact(
        'facultiesCount',
        'programmesCount',
        'nominationsCount'
    ));
}

public function view_prog_list(Request $request)
{
     $distinctYears = ProgrammeManagement::select('financial_year')
                            ->distinct()
                            ->orderBy('financial_year','desc')
                            ->pluck('financial_year');
    
        // 2. All programmes
        $programmes = ProgrammeManagement::select(['id','financial_year','title'])
                        ->get();
        
        $groups=Group::select(['id','name'])->get();
        $sponsors=Sponsor::select(['id','name'])->get();


    return view('dashboard.totalprogramme', compact('groups','sponsors','programmes','distinctYears'));
}

public function getData(Request $request)
{
    $query = ProgrammeManagement::with([
        'group:id,name',
        'sponsor:id,name'
    ])->select([
        'id',
        'group_id',
        'title',
        'location',
        'venue',
        'sponsor_id',
        'department_id',
        'department_input',
        'participant_fee_check',
        'participant_fee',
        'program_fee_check',
        'program_fee',
        'from_date',
        'to_date',
        'duration',
        'session_start',
        'session_end',
        'fee_structure',
        'status',
        'created_at',
        'updated_at',
        'is_active',
        'unique_id', // Added since you filter by this
        'financial_year' // Added since you filter by this
    ]);

    // Apply filters
    $this->applyFilters($query, $request);

    return datatables()->eloquent($query)
        ->addColumn('group_name', function($programme) {
            return $programme->group->name ?? 'N/A';
        })
        ->addColumn('sponsor_name', function($programme) {
            return $programme->sponsor->name ?? 'N/A';
        })
        ->addColumn('duration', function($programme) {
            return $programme->duration . ' days';
        })
        ->addColumn('active_status', function($programme) {
            $isActive = now()->lte($programme->to_date);
            return $isActive 
                ? '<span class="badge bg-success">Active</span>'
                : '<span class="badge bg-secondary">Ended</span>';
        })
        ->rawColumns(['actions', 'status', 'is_active', 'active_status'])
        ->toJson();
}

protected function applyFilters($query, $request)
{
    // Status filter
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }
    
    // Group filter
    if ($request->filled('group')) {
        $query->where('group_id', $request->group);
    }
    
    // Sponsor filter
    if ($request->filled('sponsor')) {
        $query->where('sponsor_id', $request->sponsor);
    }
    
    // Year filter
    if ($request->filled('year')) {
        $query->whereYear('from_date', $request->year);
    }
    
    // Specific programme filter
    if ($request->filled('programme')) {
        $query->where('id', $request->programme);
    }
    
    // Financial year filter
    if ($request->filled('fanicial')) {
        $query->where('financial_year', $request->fanicial);
    }

    // Active programs filter (to_date >= today)
    if ($request->boolean('active_only')) {
        $query->where('to_date', '>=', now()->format('Y-m-d'));
    }

    // Programme code search
    if ($request->filled('programme_code')) {
        $query->where('code', 'like', '%' . $request->programme_code . '%');
    }
}
//public function view active programme

public function active_programme(){


    $programme = ProgrammeManagement::where('to_date', '>', Carbon::today())->where('status','Announced')->get();
    

    return view('dashboard.active_programme',compact('programme'));
    }

   public function view_nomination(Request $request, $id)
{
    $query = Participant::with(['programme', 'nomination.agencyType', 'nomination.agency'])
        ->where('programme_id', $id);

    if ($request->filled('agency_type')) {
        $query->whereHas('nomination', function ($q) use ($request) {
            $q->where('agency_type_id', $request->agency_type);
        });
    }

    if ($request->filled('agency')) {
        $query->whereHas('nomination', function ($q) use ($request) {
            $q->where('agency_id', $request->agency);
        });
    }

if ($request->filled('status')) {
    $query->whereRaw('LOWER(status) = ?', [strtolower($request->status)]);
}




    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%$search%")
              ->orWhere('email', 'like', "%$search%")
              ->orWhere('phone', 'like', "%$search%");
        });
    }

    $participant = $query->get();

    // Load dropdowns for filters
    $agencyTypes = AgencyType::all();
    $agencies = Agency::all();

    return view('dashboard.view_nomination', compact('participant', 'agencyTypes', 'agencies'));
}

public function view_faculty(){
    $user=User::where('user_type','Faculty')->get();

    return view('dashboard.faculty',compact('user'));
}

public function getAdminDashboardInfo(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | PROGRAM DATES
    |--------------------------------------------------------------------------
    */

    if ($request->type === 'program_dates') {

        $month = $request->month;
        $financialYear = $request->financial_year;

        $query = ProgrammeManagement::query()
            ->where('financial_year', $financialYear)
            ->whereMonth('from_date', $month)
            ->where('status', 'Announced')
            ->orderBy('from_date', 'asc');

        $programmes = $query->get();

        $data = $programmes
            ->groupBy(function ($programme) {
                return Carbon::parse($programme->from_date)
                    ->format('Y-m-d');
            })
            ->map(function ($items) {

                $first = $items->first();

                return [
                    'from_date' => Carbon::parse($first->from_date)
                        ->format('Y-m-d'),

                    'to_date' => Carbon::parse($first->to_date)
                        ->format('Y-m-d'),

                    'programmes' => $items->map(function ($programme) {
                        return [
                            'id' => $programme->id,
                            'programme_name' => $programme->title,

                            'director_1' => $programme->prog_dir_1 ?? '-',
                            'director_2' => $programme->prog_dir_2 ?? '-',
                        ];
                    })->values(),
                ];
            })
            ->values();

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | PROGRAM PARTICIPANTS
    |--------------------------------------------------------------------------
    */

    if ($request->type === 'program_participants') {

        $programmeId = $request->programme_id;

        $participants = Participant::with([
            'programme',
            'nomination.agency',
            'nomination.agencyType'
        ])
        ->where('programme_id', $programmeId)
        ->get();


        /*
        |--------------------------------------------------------------------------
        | Status wise participants
        |--------------------------------------------------------------------------
        */

        $confirmed = $participants->filter(function ($participant) {

            return strtolower(trim($participant->status ?? '')) === 'confirm';

        })->values();


        $cfa = $participants->filter(function ($participant) {

            return in_array(
                strtolower(trim($participant->status ?? '')),
                ['cfa', 'c.f.a']
            );

        })->values();


        $regret = $participants->filter(function ($participant) {

            return strtolower(trim($participant->status ?? '')) === 'regret';

        })->values();


        $unconfirmed = $participants->filter(function ($participant) {

            return in_array(
                strtolower(trim($participant->status ?? '')),
                [
                    'unconfirm',
                    'unconfirmed',
                    'pending',
                    ''
                ]
            );

        })->values();


        /*
        |--------------------------------------------------------------------------
        | Addresses
        |--------------------------------------------------------------------------
        */

        $firstParticipant = $participants->first();

        $officialAddress = '-';
        $nominatingAddress = '-';

        if ($firstParticipant) {

            $officialAddress =
                $firstParticipant->official_address
                ?? $firstParticipant->nomination->agency->address
                ?? '-';

            $nominatingAddress =
                $firstParticipant->nominating_address
                ?? $firstParticipant->nomination->agency->address
                ?? '-';
        }


        /*
        |--------------------------------------------------------------------------
        | Total nomination
        |--------------------------------------------------------------------------
        */

        $totalNomination = $participants->count();


        /*
        |--------------------------------------------------------------------------
        | Total attended
        |--------------------------------------------------------------------------
        |
        | NOTE:
        | Yahan attendance column ka exact naam aapke database ke
        | according lagana hoga.
        |
        */

        $totalAttended = $participants->filter(function ($participant) {

            return isset($participant->attended)
                && (
                    $participant->attended == 1
                    || strtolower(trim($participant->attended)) === 'yes'
                );

        })->count();


        return response()->json([

            'success' => true,

            'confirmed' => $confirmed->map(function ($participant) {
                return [
                    'id' => $participant->id,
                    'name' => $participant->name,
                    'status' => $participant->status,
                ];
            })->values(),

            'unconfirmed' => $unconfirmed->map(function ($participant) {
                return [
                    'id' => $participant->id,
                    'name' => $participant->name,
                    'status' => $participant->status,
                ];
            })->values(),

            'cfa_count' => $cfa->count(),

            'regret_count' => $regret->count(),

            'unconfirm_count' => $unconfirmed->count(),

            'confirm_count' => $confirmed->count(),

            'total_nomination' => $totalNomination,

            'total_attended' => $totalAttended,

            'official_address' => $officialAddress,

            'nominating_address' => $nominatingAddress,
        ]);
    }


    return response()->json([
        'success' => false,
        'message' => 'Invalid request type.'
    ], 400);
}

}
