<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\ProgrammeManagement;
use App\Models\Subtopic;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class FacultyController extends Controller
{
    public function login(){

        return view('faculty.login');
    }

    public function login_submit(Request $request){
        
          $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

	$password =	Crypt::decryptString($request->password);
      
         if (Auth::guard('faculty')->attempt(['username' => $request->username,'password' => $password])) {
            return redirect()->route('faculty.dash'); // Redirect on success
   
        }
    
        return back()->withErrors(['user' => 'Invalid credentials.']);
    }

    //code for the logout
//        public function logout()
// {
//     Auth::guard('faculty')->logout();
//     return redirect()->route('faculty.login.form');
// }

public function logout()
{
    Auth::guard('faculty')->logout();
    return redirect()->route('welcome');
}

public function dash()
{
    $faculty = Auth::guard('faculty')->user();

    // Get unique assigned programme IDs
    $programmeIds = Subtopic::where('faculty_id', $faculty->id)
        ->pluck('programme_id')
        ->unique()
        ->values();
    //  dd($programmeIds);
    // Count or fetch active (Announced + future) programmes
    $activeProgrammes = ProgrammeManagement::whereIn('id', $programmeIds)
        ->where('status', 'Announced')
      ->where('to_date', '>', Carbon::today()->format('Y-m-d'))
        ->get(); // or ->count() if you only want number
// dd($activeProgrammes);
    return view('faculty.dash', [
        'programmes' => $programmeIds,           // Just list of IDs
        'activeProgrammes' => $activeProgrammes  // Full data
    ]);
}

public function view_assign_programme(Request $request)
{
    $faculty = Auth::guard('faculty')->user();

    // Step 1: Get all assigned programme IDs
    $programmeIds = Subtopic::where('faculty_id', $faculty->id)
        ->pluck('programme_id')
        ->unique()
        ->values();

    // Step 2: Build base query
    $query = ProgrammeManagement::whereIn('id', $programmeIds)->where('status','Announced');

    // Step 3: Apply filters
    if ($request->filled('financial')) {
        $query->where('financial_year', $request->financial);
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('department')) {
        $query->where('department_id', $request->department);
    }

    if ($request->filled('unique_id')) {
        $query->where('unique_id', 'like', '%' . $request->unique_id . '%');
    }

    // Step 4: Paginate result
    $programmes = $query->paginate(10)->withQueryString(); // preserves query params on pagination

    // Step 5: Load departments and statuses
    $department = DB::table('departments')->pluck('name', 'id');
    $status = ProgrammeManagement::pluck('status')->unique()->filter()->values();
    $financial = ProgrammeManagement::pluck('financial_year')->unique()->filter()->sortDesc()->values();
    $programmeIdCounts = Subtopic::where('faculty_id', $faculty->id)
        ->selectRaw('programme_id, COUNT(*) as count')
        ->groupBy('programme_id')
        ->pluck('count', 'programme_id');


    // Step 6: Return view
    return view('faculty.totalprogramme', compact('programmes', 'department', 'status', 'financial','programmeIdCounts'));
}

public function view_active_assign_programme(Request $request)
{
    $faculty = Auth::guard('faculty')->user();

    // Step 1: Get all assigned programme IDs
    $programmeIds = Subtopic::where('faculty_id', $faculty->id)
        ->pluck('programme_id')
        ->unique()
        ->values();

    // Step 2: Build base query
  $query = ProgrammeManagement::whereIn('id', $programmeIds)
    ->where('status', 'Announced')
    ->where('to_date', '>', Carbon::today());

    // Step 3: Apply filters
    if ($request->filled('financial')) {
        $query->where('financial_year', $request->financial);
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('department')) {
        $query->where('department_id', $request->department);
    }

    if ($request->filled('unique_id')) {
        $query->where('unique_id', 'like', '%' . $request->unique_id . '%');
    }

    // Step 4: Paginate result
    $programmes = $query->paginate(10)->withQueryString(); // preserves query params on pagination

    // Step 5: Load departments and statuses
    $department = DB::table('departments')->pluck('name', 'id');
    $status = ProgrammeManagement::pluck('status')->unique()->filter()->values();
    $financial = ProgrammeManagement::pluck('financial_year')->unique()->filter()->sortDesc()->values();
    $programmeIdCounts = Subtopic::where('faculty_id', $faculty->id)
        ->selectRaw('programme_id, COUNT(*) as count')
        ->groupBy('programme_id')
        ->pluck('count', 'programme_id');


    // Step 6: Return view
    return view('faculty.active_programme', compact('programmes', 'department', 'status', 'financial','programmeIdCounts'));
}

public function view_nomination($id)
{
    $participants = Participant::where('programme_id', $id)->where('status','confirm')->get();
    return view('faculty.participant',compact('participants'));
}




}
