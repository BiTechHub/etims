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

}
