<?php



namespace App\Http\Controllers\Admin;



use App\Http\Controllers\Controller;

use App\Mail\AnnouncementLetter;

use App\Mail\ProgrammePostponedMail;

use App\Models\Agency;

use App\Models\AgencyGroup;

use App\Models\AgencyType;

use App\Models\Classes;

use App\Models\Department;

use App\Models\Faculity;

use App\Models\Group;

use App\Models\Guest;

use App\Models\ProgramCalendar;

use App\Models\ProgrammeAgencyFee;

use App\Models\ProgrammeManagement;

use App\Models\ProgrammeManagementArchive;

use App\Models\Question;

use App\Models\Sponsor;

use App\Models\Subtopic;

use App\Models\User;

use Carbon\Carbon;

use App\Models\UserType;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Mail;

use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\SessionBreak;



class ProgrammeManageController extends Controller

{



    public function list(){



         $distinctYears = ProgrammeManagement::select('financial_year')

                            ->distinct()

                            ->orderBy('financial_year','desc')

                            ->pluck('financial_year');

    

        // 2. All programmes

        $programmes = ProgrammeManagement::select(['id','financial_year','title'])

                        ->get();

        

        $groups=Group::select(['id','name'])->where('is_active' ,1)->get();



        $users=User::select(['id','name'])->get();

       $locations = ProgrammeManagement::select('location')

    ->whereNotNull('location')

    ->distinct()

    ->orderBy('location')

    ->pluck('location');



        $sponsors=Sponsor::select(['id','name'])->get();

        return view('admin.programme.prog_mag_list',compact(['groups','sponsors','programmes','distinctYears','locations','users']));

    }

    

public function pro_management_index()

{



    // Fetch all data needed for the form

    $programmes = ProgrammeManagement::where('is_active', 1)->select(['title', 'id'])->get();

    $classes=Classes::where('is_active', 1)->select(['id','name'])->get();

    $faculties = User::where('is_active', 1)->where('deleted_at',0)->get();
	$guest_faculty = Guest::where('is_delete',0)->get();

    $groups=Group::all()->where('is_active',1);

    $departments=Department::select(['id','name'])->get();

    $sponsors=Sponsor::all();

   $agencies = AgencyType::where('is_active', 1)->where('is_deleted',0)->select(['id', 'name'])->get();



   return view('admin.programme.progMag',compact(['programmes','groups','sponsors','classes','faculties','departments','agencies','guest_faculty']));

}

public function pro_management_store(Request $request)

{

  

    $request->validate([

        'agency_type_id' => 'required|array',

        'agency_type_id.*' => 'exists:agency_types,id',



        'group' => 'required|exists:groups,id',

        'sponsor_id' => 'required|exists:sponsors,id',

        'location' => 'required|in:In-House,On-Location,On-line',

        'venue' => 'required|string|max:255',

        'from_date' => 'required|date',

        'to_date' => 'required|date|after_or_equal:from_date',

        'duration' => 'nullable|string|max:100',

        'fee_structure' => 'nullable|string|max:1000',

        'program_type' => 'required',



        'participant_fee_check' => 'nullable|boolean',

        'participant_fee' => 'nullable|numeric',

        'program_fee_check' => 'nullable|boolean',

        'program_fee' => 'nullable|numeric',



        'programme_id' => 'nullable|exists:programmes,id',

        'title' => 'nullable|string|max:255|required_without:programme_id',

        'hindi_title' => 'nullable|string|max:255|required_without:programme_id',



        'department_input' => 'nullable|string|max:255',

        'department_id' => 'nullable|exists:departments,id',



        'announced_on' => 'nullable|date',

        'last_nomination' => 'nullable|boolean',

        'last_nomination_date' => 'nullable|date',

        'strength' => 'nullable|string|max:255',

        'class_room' => 'nullable|exists:classes,id',   

        'boarding_plan' => 'nullable|string|max:255',

        'max_disc_amt' => 'nullable|numeric',

        'prog_dir_1' => 'nullable|exists:users,id',

        'prog_dir_2' => 'nullable|exists:users,id',

        'clientele_type' => 'nullable|in:Normal,Mixed',

        'clientele' => 'nullable|string|max:255',

        'claim_ref' => 'nullable|string|max:255',

        'remarks' => 'nullable|string|max:500',

        'agency_fees' => 'nullable|array',

'agency_fees.*' => 'nullable|numeric|min:0',

        'announcement_letter' => 'nullable|file|mimes:jpg,jpeg,pdf,zip,rar,doc,docx,xls,xlsx|max:2048',

        'nomination_form' => 'nullable|file|mimes:jpg,jpeg,pdf,zip,rar,doc,docx,xls,xlsx|max:2048',

        'pcr' => 'nullable|file|mimes:jpg,jpeg,pdf,zip,rar,doc,docx,xls,xlsx|max:2048',
		'guest_faculty'=>'nullable',

    ]);



    // Determine title

    if ($request->programme_id) {

        $programme = ProgrammeManagement::findOrFail($request->programme_id);

        $finalTitle = $programme->title;

        $finalHindiTitle = $programme->hindi_title ?? null;

    } else {

        $finalTitle = $request->title;

        $finalHindiTitle = $request->hindi_title;

    }



    // Handle file uploads

    $announcementLetterPath = $request->hasFile('announcement_letter') ? $request->file('announcement_letter')->store('public/files') : null;

    $nominationFormPath = $request->hasFile('nomination_form') ? $request->file('nomination_form')->store('public/files') : null;

    $pcrPath = $request->hasFile('pcr') ? $request->file('pcr')->store('public/files') : null;







//code for the unique_id

$group = \App\Models\Group::findOrFail($request->group);

$groupCode = $group->code;



// Step 2: Find latest unique_id starting with this group code

$lastProgramme = \App\Models\ProgrammeManagement::where('unique_id', 'like', $groupCode . '%')

    ->orderBy('unique_id', 'desc')

    ->first();



// Step 3: Determine next 3-digit number

if ($lastProgramme) {

    $lastNumber = (int)substr($lastProgramme->unique_id, strlen($groupCode));

    $nextNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

} else {

    $nextNumber = '001';

}



// Step 4: Combine to form unique_id

$uniqueId = $groupCode . $nextNumber;



    // Create without financial_year first

    $programme = ProgrammeManagement::create([

        'group_id' => $request->group,

         'unique_id' => $uniqueId,

        'agency_type_id' => array_map('intval', $request->agency_type_id),

        'sponsor_id' => $request->sponsor_id,

        'location' => $request->location,

        'venue' => $request->venue,

        'from_date' => $request->from_date,

        'to_date' => $request->to_date,

        'duration' => $request->duration,

        'session_start' => $request->session_start,

        'session_end' => $request->session_end,

        'fee_structure' => $request->fee_structure,

        'title' => $finalTitle,

        'hindi_title' => $finalHindiTitle,

        'program_type' => $request->program_type,

        'department_input' => $request->department_input,

        'department_id' => $request->department_id,

        'participant_fee_check' => $request->has('participant_fee_check'),

        'participant_fee' => $request->participant_fee,

        'program_fee_check' => $request->has('program_fee_check'),

        'program_fee' => $request->program_fee,

        'announced_on' => $request->announced_on,

        'last_nomination' => $request->has('last_nomination'),

        'last_nomination_date' => $request->last_nomination_date,

        'strength' => $request->strength,

        'class_room_id' => $request->class_room,

        'boarding_plan' => $request->boarding_plan,

        'max_disc_amt' => $request->max_disc_amt,

        'prog_dir_1' => $request->prog_dir_1,

        'prog_dir_2' => $request->prog_dir_2,

        'clientele_type' => $request->clientele_type,

        'clientele' => $request->clientele,

        'claim_ref' => $request->claim_ref,

        'remarks' => $request->remarks,

        'announcement_letter' => $announcementLetterPath,

        'nomination_form' => $nominationFormPath,

        'pcr' => $pcrPath,

											 'guest_faculty'=>$request->guest_faculty

        

    ]);





    if ($request->has('agency_fees')) {

        foreach ($request->agency_fees as $agencyTypeId => $fee) {

            ProgrammeAgencyFee::create([

                'programme_id' => $programme->id,

                'agency_type_id' => $agencyTypeId,

                'fee' => $fee,

            ]);

        }

    }

    // Calculate and update financial year based on created_at

    $programme->update([

        'financial_year' => $this->calculateFinancialYear($programme->created_at),

    ]);



  return redirect()->back()->with('success', 'Programme management entry created successfully. Unique ID: ' . $uniqueId);



}



private function calculateFinancialYear($date)

{

    $date = \Carbon\Carbon::parse($date);

    $year = $date->year;



    return ($date->month < 4)

        ? ($year - 1) . '-' . $year

        : $year . '-' . ($year + 1);

}





//this is the update function for the programme

public function pro_management_update(Request $request, $id)

{ 
      if (!auth('admin')->user()->hasAccess('programme', 'edit')) {
        abort(403, 'You do not have permission to edit programmes.');
    }

    $programme = ProgrammeManagement::with('agencyFees')->findOrFail($id);

    $previousStatus = $programme->status;



    $request->validate([

        'agency_type_id' => 'required|array',

        'agency_type_id.*' => 'exists:agency_types,id',

        'group_id' => 'required|exists:groups,id',

        'sponsor_id' => 'required|exists:sponsors,id',

        'location' => 'required|in:In-House,On-Location,On-line',

        'venue' => 'required|string|max:255',

        'from_date' => 'required|date',

        'to_date' => 'required|date|after_or_equal:from_date',

        'duration' => 'nullable|string|max:100',

        'session_start' => 'nullable|date_format:H:i',

        'session_end' => 'nullable|date_format:H:i',

        'fee_structure' => 'nullable|string|max:1000',

        'program_type' => 'required',

        'participant_fee_check' => 'nullable|boolean',

        'participant_fee' => 'nullable|numeric',

        'program_fee_check' => 'nullable|boolean',

        'program_fee' => 'nullable|numeric',

        'title' => 'nullable|string|max:255',

        'hindi_title' => 'nullable|string|max:255',

        'department_input' => 'nullable|string|max:255',

        'department_id' => 'nullable|exists:departments,id',

        'status' => 'required|in:NotAnnounce,Announced,Canceled,Postponed',

        'announced_on' => 'nullable|date',

        'last_nomination' => 'nullable|boolean',

        'last_nomination_date' => 'nullable|date',

        'strength' => 'nullable|string|max:255',

        'class_room' => 'nullable|exists:classes,id',

        'boarding_plan' => 'nullable|string|max:255',

        'max_disc_amt' => 'nullable|numeric',

        'prog_dir_1' => 'nullable|exists:users,id',

        'prog_dir_2' => 'nullable|exists:users,id',

        'clientele_type' => 'nullable|in:Normal,Mixed',

        'clientele' => 'nullable|string|max:255',

        'claim_ref' => 'nullable|string|max:255',

        'remarks' => 'nullable|string|max:500',

        'agency_fees' => 'nullable|array',

        'agency_fees.*' => 'nullable|numeric|min:0',

        'announcement_letter' => 'nullable|file|mimes:jpg,jpeg,pdf,zip,rar,doc,docx,xls,xlsx|max:2048',

        'nomination_form' => 'nullable|file|mimes:jpg,jpeg,pdf,zip,rar,doc,docx,xls,xlsx|max:2048',

        'pcr' => 'nullable|file|mimes:jpg,jpeg,pdf,zip,rar,doc,docx,xls,xlsx|max:2048',

    ]);



    // Handle file uploads

    $announcementLetterPath = $programme->announcement_letter;

    if ($request->hasFile('announcement_letter')) {

        // Delete old file if exists

        if ($announcementLetterPath) {

            Storage::delete($announcementLetterPath);

        }

        $announcementLetterPath = $request->file('announcement_letter')->store('public/files');

    }



    $nominationFormPath = $programme->nomination_form;

    if ($request->hasFile('nomination_form')) {

        if ($nominationFormPath) {

            Storage::delete($nominationFormPath);

        }

        $nominationFormPath = $request->file('nomination_form')->store('public/files');

    }



    $pcrPath = $programme->pcr;

    if ($request->hasFile('pcr')) {

        if ($pcrPath) {

            Storage::delete($pcrPath);

        }

        $pcrPath = $request->file('pcr')->store('public/files');

    }



        $agencyTypeIds = array_map('intval', $request->agency_type_id);



    // Update programme data

    $programme->update([

        'group_id' => $request->group_id,

        'agency_type_id' =>$agencyTypeIds,

        'sponsor_id' => $request->sponsor_id,

        'location' => $request->location,

        'venue' => $request->venue,

        'from_date' => $request->from_date,

        'to_date' => $request->to_date,

        'duration' => $request->duration,

        'session_start' => $request->session_start,

        'session_end' => $request->session_end,

        'fee_structure' => $request->fee_structure,

        'program_type' => $request->program_type,

        'title' => $request->title,

        'hindi_title' => $request->hindi_title,

        'department_input' => $request->department_input,

        'department_id' => $request->department_id,

        'participant_fee_check' => $request->has('participant_fee_check'),

        'participant_fee' => $request->participant_fee,

        'program_fee_check' => $request->has('program_fee_check'),

        'program_fee' => $request->program_fee,

        'status' => $request->status,

        'announced_on' => $request->announced_on,

        'last_nomination' => $request->has('last_nomination'),

        'last_nomination_date' => $request->last_nomination_date,

        'strength' => $request->strength,

        'class_room_id' => $request->class_room,

        'boarding_plan' => $request->boarding_plan,

        'max_disc_amt' => $request->max_disc_amt,

        'prog_dir_1' => $request->prog_dir_1,

        'prog_dir_2' => $request->prog_dir_2,

        'clientele_type' => $request->clientele_type,

        'clientele' => $request->clientele,

        'claim_ref' => $request->claim_ref,

        'remarks' => $request->remarks,

        'announcement_letter' => $announcementLetterPath,

        'nomination_form' => $nominationFormPath,

        'pcr' => $pcrPath,

    ]);



    // Handle agency fees for Paid sponsor

    if ($programme->sponsor->name == 'Paid' && $request->has('agency_fees')) {

        // First, delete any existing fees that aren't in the new selection

        $programme->agencyFees()

            ->whereNotIn('agency_type_id', array_keys($request->agency_fees))

            ->delete();



        // Update or create fees

        foreach ($request->agency_fees as $agencyTypeId => $fee) {

            $programme->agencyFees()->updateOrCreate(

                ['agency_type_id' => $agencyTypeId],

                ['fee' => $fee]

            );

        }

    }



    // Send email only if status changed to "Postponed"

    if ($request->status === 'Postponed' && $previousStatus !== 'Postponed') {

        $agencyIds = $programme->agency_type_id;



        if (is_array($agencyIds)) {

            $agencies = Agency::whereIn('agency_type_id', $agencyIds)->get();



            foreach ($agencies as $agency) {

                if (!empty($agency->emailid)) {

                    Mail::to($agency->emailid)->send(new ProgrammePostponedMail($programme));

                }

            }

        }

    }



    return redirect()->back()->with('success', 'Programme updated successfully.');

}

// In your controller (e.g., ProgrammeManagementController.php)

public function getData(Request $request)

{



$searchValue = $request->input('search.value');




        $agencyTypes =AgencyType::pluck('name', 'id')->toArray();

    $programmes = ProgrammeManagement::with([

        'group:id,name',

    'sponsor:id,name',

    'progDir1:id,name',

    'progDir2:id,name',

    

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

        'unique_id',

         'from_date',

         'to_date',

      'prog_dir_1',

      'prog_dir_2',

      'agency_type_id',

         

    ]);



    // 🔍 Apply status filter if present

    if ($request->has('status') && $request->status != '') {

        $programmes->where('status', $request->status);

    }

    if ($request->has('group') && $request->group != '') {

        $programmes->where('group_id', $request->group);

    }

    if ($request->has('sponsor') && $request->sponsor != '') {

        $programmes->where('sponsor_id', $request->sponsor);

    }

    if ($request->has('year') && $request->year != '') {

        $programmes->whereYear('from_date', $request->year);

    }

   if ($request->has('programme') && $request->programme != '') {

    $programmes->where('id', $request->programme);

}

if($searchValue){
    $programmes->where('title', 'like', '%' . $searchValue . '%')->orWhere('unique_id', 'like', '%' . $searchValue . '%');

}



if ($request->filled('from_date') && $request->filled('to_date')) {

    $programmes->whereDate('created_at', '>=', $request->from_date)

               ->whereDate('created_at', '<=', $request->to_date);

}

if ($request->filled('location')) {

    $programmes->where('location', $request->location);

}





if ($request->filled('faculty')) {

    $programmes->where(function ($query) use ($request) {

        $query->where('prog_dir_1', $request->faculty)

              ->orWhere('prog_dir_2', $request->faculty);

    });

}







 


   return DataTables::of($programmes)

        ->addColumn('group_name', function ($row) {

            return $row->group->name ?? '-';

        })

        ->addColumn('sponsor_name', function ($row) {

            return $row->sponsor->name ?? '-';

        })

          ->addColumn('dir_name1', function ($row) {

            return $row->progDir1->name ?? '-';

        })

     ->addColumn('dir_name2', function ($row) {

            return $row->progDir2->name ?? '-';

        })      ->addColumn('agency_type', function ($row) use ($agencyTypes) {

            $ids = is_array($row->agency_type_id) ? $row->agency_type_id : json_decode($row->agency_type_id, true);



            if (!is_array($ids)) return '-';



            $names = array_map(fn($id) => $agencyTypes[$id] ?? null, $ids);



            return implode(', ', array_filter($names));

        })

        ->rawColumns(['agency_type']) 

        ->make(true);

       

}





//function for the is active

public function toggleStatus($id, Request $request)

{
     if (!auth('admin')->user()->hasAccess('programme', 'edit')) {
        return response()->json(['message' => 'You do not have permission to perform this action.'], 403);
    }

    $programme = ProgrammeManagement::find($id);



    if (!$programme) {

        return response()->json(['message' => 'Programme not found'], 404);

    }



    // Toggle the status

    $programme->is_active = $request->status;

    $programme->save();



    return response()->json(['message' => 'Status updated successfully']);

}



public function pro_management_edit($id){

  if (!auth('admin')->user()->hasAccess('programme', 'edit')) {
        abort(403, 'You do not have permission to edit programmes.');
    }

    $programme = ProgrammeManagement::findOrFail($id);

    $groups = Group::select('id', 'name')->get();    

    $sponsors = Sponsor::select('id', 'name')->get();

    $classes=Classes::select('id','name')->get();      

    $faculties=User::select('id','name')->get();

    $AgencyTypes=AgencyType::select('id','name')->get();

    $departments=Department::select('id','name')->get();

 

    return view('admin.programme.prog_mag_edit', compact('programme', 'groups', 'sponsors','classes','faculties','AgencyTypes','departments'));

}

public function destroy($id)

    {

    if (!auth('admin')->user()->hasAccess('programme', 'delete')) {
        return response()->json(['error' => 'You do not have permission to delete.'], 403);
    }

        $agencyGroup = ProgrammeManagement::find($id);

    

        if (!$agencyGroup) {

            return response()->json(['error' => 'Agency  not found.'], 404);

        }

    

        $agencyGroup->delete();

    

        return response()->json(['success' => ' deleted successfully.']);

    }

    public function getProgrammesByYear(Request $request)

    {

        try {

            $request->validate([

                'year' => 'required|regex:/^\d{4}-\d{4}$/'

            ]);

    

            $yearRange = $request->year; // "2024-2025"

            $startYear = (int)explode('-', $yearRange)[0]; // 2024

    

            // Get programmes created in the START year only

            $programmes = ProgrammeManagement::whereYear('created_at', $startYear)

                ->select('id', 'title', 'created_at')

                ->orderBy('title')

                ->get();

    

            return response()->json([

                'success' => true,

                'data' => $programmes,

                'message' => $programmes->isEmpty() ? 'No programmes found for selected academic year' : ''

            ]);

    

        } catch (\Exception $e) {

            Log::error('Error fetching programmes: ' . $e->getMessage());

            return response()->json([

                'success' => false,

                'message' => 'Failed to fetch programmes'

            ], 500);

        }

    }



    /**

     * Store a new nomination.

     */

    // public function store(Request $request)

    // {

    //     $validated = $request->validate([

    //         'programme_year' => 'required|string|regex:/^\d{4}-\d{4}$/',

    //         'programme_id' => 'required|exists:programme_managements,id',

    //         'agency_type_id' => 'required|exists:agency_types,id',

    //     ]);



    //     try {

    //         // Your nomination creation logic here

    //         // Example:

    //         // $nomination = new Nomination();

    //         // $nomination->programme_year = $validated['programme_year'];

    //         // $nomination->programme_id = $validated['programme_id'];

    //         // $nomination->agency_type_id = $validated['agency_type_id'];

    //         // $nomination->save();



    //         return redirect()->back()

    //             ->with('success', 'Nomination created successfully!');



    //     } catch (\Exception $e) {

    //         Log::error('NominationController@store error: ' . $e->getMessage());

    //         return back()

    //             ->withInput()

    //             ->with('error', 'Failed to create nomination. Please try again.');

    //     }

    // }





    //get agency through its agency type

/**

 * Return agencies filtered by agency type (AJAX call)

 */







 //programme view

 public function view($id)

 {  

     $programme = ProgrammeManagement::findOrFail($id);

 

     $agency_type = AgencyType::findOrFail($programme->agency_type_id);

     $agencyName = $agency_type->name;

    

 

     $sponsor = Sponsor::find($programme->sponsor_id); // use find() because sponsor could be optional

     $sponsorName = $sponsor ? $sponsor->name : null;

 

     $group = Group::find($programme->group_id);

     $groupName = $group ? $group->name : null;

 

     $department = Department::find($programme->department_id);

     $departmentName = $department ? $department->name : null;

   

     $room = Classes::find($programme->class_room_id);

     $className = $room ? $room->name : null;



     $faculty=User::find($programme->prog_dir_1);

     

     $facultyFirst=$faculty? $faculty->name:null;



     $faculty=User::find($programme->prog_dir_2);

     $facultySecond=$faculty? $faculty->name:null;

     return view('admin.programme.view', compact('programme', 'agencyName', 'sponsorName', 'groupName', 'departmentName','className','facultyFirst','facultySecond'));

 }





//code for the view the annoucement letter



public function showAnnouncement($id)

{

    $programme = ProgrammeManagement::findOrFail($id);

    $agencyTypes = AgencyType::find($programme->agency_type_id);

    $sponsor = Sponsor::find($programme->sponsor_id);

    $users1 = User::find($programme->prog_dir_1);

    $users2 = User::find($programme->prog_dir_2);



    // Generate the default letter HTML from Blade views

    $defaultContent = view('admin.programme.default_announcement_template', compact('programme', 'agencyTypes', 'sponsor', 'users1', 'users2'))->render();

    $defaultContentHindi = view('admin.programme.default_hindi_annoucement', compact('programme', 'agencyTypes', 'sponsor', 'users1', 'users2'))->render();



    // Save to DB if content/hindi_content is null

    $updated = false;



    if (is_null($programme->content)) {

        $programme->content = $defaultContent;

        $updated = true;

    }



    if (is_null($programme->hindi_content)) {

        $programme->hindi_content = $defaultContentHindi;

        $updated = true;

    }



    if ($updated) {

        $programme->save();

    }



    return view('admin.programme.annoucement', compact('programme', 'agencyTypes', 'sponsor', 'users1', 'users2', 'defaultContent', 'defaultContentHindi'));

}











// public function updateContent(Request $request, $id)

// {

//     // Find the programme by its ID

//     $programme = ProgrammeManagement::findOrFail($id);



//     // Update the content

//     $programme->content = $request->content;



//     // Save the updated record

//     $programme->save();



//     // Return success response

//     return response()->json(['success' => true]);

// }



public function saveAnnouncement(Request $request, $id)

{

    $request->validate([

        'content' => 'required|string',

    ]);



    $programme = ProgrammeManagement::findOrFail($id);



    // Clear the old content in memory

    $programme->content = null;



    // Assign new content

    $programme->content = $request->content;



    // Now save once

    $programme->save();



    return response()->json(['success' => true]);   

}



public function facultySession() {

    $titles = Subtopic::select('id','title')->distinct()->get(); // Unique subtopic titles

    $faculties = User::select('id', 'name')->where('user_type', 'Faculty')->get();

    $programmes = ProgrammeManagement::select('id', 'title', 'from_date', 'to_date')->get();

    $facultyUsers = User::where('user_type', 'Faculty')->get();

$guestFaculties = Guest::select('id','name')->get();

    return view('admin.programme.session', compact('faculties', 'programmes', 'titles','facultyUsers','guestFaculties'));

}

  


public function CreateBreak($data)
{
 

 
 SessionBreak::create([
    'programme_id' => $data['programme_id'],
    'subtopic_id' => $data['subtopic_id'],
    'break_type' => $data['break_type'],
    'date' => $data['date'],
    'start_time' => $data['start_time'],
    'end_time' => $data['end_time'],
    'duration' => $data['duration'],
 ]);

 return true;
  
  

}


  

public function storeSession(Request $request)

{


    $validatedData = $request->validate([

        'programme_id' => 'required|exists:programmes,id',

        'from_date' => 'required|date',

        'to_date' => 'required|date|after_or_equal:from_date',

        'subtopics' => 'required|array',

        'subtopics.*.title' => 'required|string',
        'subtopics.*.session_name' => 'required|string',

        'subtopics.*.date' => 'required|date',

        'subtopics.*.start_time' => 'required|date_format:H:i',

        'subtopics.*.end_time' => 'required|date_format:H:i|after:subtopics.*.start_time',

        'subtopics.*.type' => 'required|in:faculty,guest,Both',

    ]);



    $programme = ProgrammeManagement::findOrFail($request->programme_id);

    try {

    DB::beginTransaction();
 



    foreach ($request->subtopics as $subtopicData) {

        $title = $subtopicData['title'];

        $date = $subtopicData['date'];

        $startTime = $subtopicData['start_time'];

        $endTime = $subtopicData['end_time'];
        $sessionName = $subtopicData['session_name'];



        if ($subtopicData['type'] === 'faculty') {

            // Create for each faculty_id

            foreach ($subtopicData['faculty_id'] ?? [] as $fid) {

                Subtopic::create([

                    'programme_id' => $programme->id,
                    'session_name' => $sessionName,

                    'title' => $title,

                    'date' => $date,

                    'start_time' => $startTime,

                    'end_time' => $endTime,

                    'faculty_id' => $fid,

                    'faculty_type' => 'faculty',

                    'is_break' => false,

                    'break_type' => null,

                    'duration' => null,

                ]);

            }

        } elseif ($subtopicData['type'] === 'guest') {

            // Create for each guest_faculty_id

            foreach ($subtopicData['guest_faculty_id'] ?? [] as $gid) {

                Subtopic::create([

                    'programme_id' => $programme->id,
                    'session_name' => $sessionName,

                    'title' => $title,

                    'date' => $date,

                    'start_time' => $startTime,

                    'end_time' => $endTime,

                    'faculty_id' => $gid,

                    'faculty_type' => 'guest',

                    'is_break' => false,

                    'break_type' => null,

                    'duration' => null,

                ]);

            }

        } elseif ($subtopicData['type'] === 'Both') {

            // Create for each faculty_id with type faculty

            foreach ($subtopicData['faculty_id'] ?? [] as $fid) {

                Subtopic::create([

                    'programme_id' => $programme->id,
                    'session_name' => $sessionName,

                    'title' => $title,

                    'date' => $date,

                    'start_time' => $startTime,

                    'end_time' => $endTime,

                    'faculty_id' => $fid,

                    'faculty_type' => 'faculty',

                    'is_break' => false,

                    'break_type' => null,

                    'duration' => null,

                ]);

            }

            // Create for each guest_faculty_id with type guest

            foreach ($subtopicData['guest_faculty_id'] ?? [] as $gid) {

                Subtopic::create([

                    'programme_id' => $programme->id,
                    'session_name' => $sessionName,

                    'title' => $title,

                    'date' => $date,

                    'start_time' => $startTime,

                    'end_time' => $endTime,

                    'faculty_id' => $gid,

                    'faculty_type' => 'guest',

                    'is_break' => false,

                    'break_type' => null,

                    'duration' => null,

                ]);

            }

        }

    }

    // Save Breaks
if ($request->has('breaks')) {

    foreach ($request->breaks as $breakData) {

        // Find subtopic by session name and date
        $subtopic = Subtopic::where('programme_id', $request->programme_id)
            ->where('session_name', $breakData['session_name'])
            ->where('date', $breakData['date'])
            ->first();

        if ($subtopic) {

            $startTime = Carbon::parse($breakData['time']);

            $endTime = $startTime->copy()->addMinutes((int) $breakData['duration']);

            $this->CreateBreak([
                'programme_id' => $request->programme_id,
                'subtopic_id'  => $subtopic->id,
                'break_type'   => $breakData['type'],
                'date'         => $breakData['date'],
                'start_time'   => $startTime->format('H:i'),
                'end_time'     => $endTime->format('H:i'),
                'duration'     => $breakData['duration'],
            ]);
        }
    }

}


 
DB::commit();

return redirect()->back()->with('success', 'Programme and subtopics saved successfully.');

 
   }
    catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Error while updating session: ' . $e->getMessage());
        Log::error('Error while updating session: ' . $e->getMessage());
    }

    // (Your existing break handling code remains unchanged)



  

}














// Helper function to dynamically select validation table based on faculty type

protected function getFacultyValidationTable($subtopics)

{

    // Check the type of faculty being selected (faculty or guest)

    if ($subtopics[0]['type'] === 'faculty') {

        return 'users'; // Use 'users' table for regular faculty

    }

    return 'faculties'; // Use 'faculties' table for guest faculty

}







public function getDataSubtopic(Request $request)

{

    $subtopics = Subtopic::with(['programme:id,title', 'faculty:id,name'])

        ->select([

            'id',

            'title',
            'session_name',

            'date',

            'start_time',

            'end_time',

            'faculty_id',

            'programme_id',

            'created_at',

            'updated_at'

        ]);



    // Filter by Subtopic Title

    if ($request->has('title') && $request->title != '') {

        $subtopics->where('id', $request->title); // Changed to filter by ID

        // OR if you want to filter by title string:

        // $subtopics->where('title', 'like', '%' . $request->title . '%');

    }

    if ($request->has('programme') && $request->programme != '') {

        $subtopics->where('programme_id', $request->programme);

    }

       // Date filter

       if ($request->has('date') && $request->date != '') {

        $subtopics->whereDate('date', $request->date);

    }

    return DataTables::of($subtopics)

        ->addColumn('programme_title', function($subtopic) {

            return $subtopic->programme->title ?? 'N/A';

        })

        ->addColumn('faculty_name', function($subtopic) {

            return $subtopic->faculty->name ?? 'N/A';

        })

        ->addColumn('action', function($subtopic) {

            return '

                <div class="btn-group">

                    <button class="btn btn-sm btn-primary edit-btn" 

                        data-id="' . $subtopic->id . '"

                        data-title="' . htmlspecialchars($subtopic->title) . '"

                        data-date="' . $subtopic->date . '"

                        data-start_time="' . $subtopic->start_time . '"

                        data-end_time="' . $subtopic->end_time . '"

                        data-faculty_id="' . $subtopic->faculty_id . '"

                        data-programme_id="' . $subtopic->programme_id . '">

                        <i class="fas fa-edit"></i> Edit

                    </button>

                    <button class="btn btn-sm btn-danger delete-btn" data-id="' . $subtopic->id . '">

                        <i class="fas fa-trash"></i> Delete

                    </button>

                </div>

            ';

        })

        ->make(true);

}







public function updateSession(Request $request, $programmeId)

{

    $validatedData = $request->validate([

        'programme_id' => 'required|exists:programmes,id',

        'subtopics' => 'required|array',

        'subtopics.*.title' => 'required|string',

        'subtopics.*.date' => 'required|date',

        'subtopics.*.start_time' => 'required',

        'subtopics.*.end_time' => 'required|after:subtopics.*.start_time',

        'subtopics.*.faculty_id' => 'required|exists:users,id',

    ]);



    foreach ($request->subtopics as $index => $subtopicData) {

        $id = $request->subtopic_id[$index] ?? null;



        if (!empty($id)) {

            $subtopic = Subtopic::find($id);

            if ($subtopic) {

                $subtopic->update([

                    'programme_id' => $request->programme_id,

                    'title' => $subtopicData['title'],

                    'date' => $subtopicData['date'],

                    'start_time' => $subtopicData['start_time'],

                    'end_time' => $subtopicData['end_time'],

                    'faculty_id' => $subtopicData['faculty_id'],

                ]);

            }

        } else {

            Subtopic::create([

                'programme_id' => $request->programme_id,

                'title' => $subtopicData['title'],

                'date' => $subtopicData['date'],

                'start_time' => $subtopicData['start_time'],

                'end_time' => $subtopicData['end_time'],

                'faculty_id' => $subtopicData['faculty_id'],

            ]);

        }

    }



    return redirect()->back()->with('success', 'Programme sessions updated successfully.');

}





public function destroySubtopic($id)

{

    try {

        $subtopic = Subtopic::findOrFail($id);

        $subtopic->delete();

        

        return response()->json([

            'success' => true,

            'message' => ' deleted successfully'

        ]);

    } catch (\Exception $e) {

        return response()->json([

            'success' => false,

            'message' => 'Error deleting : ' . $e->getMessage()

        ], 500);

    }



}



public function getProgrammeDates(Request $request)

{

    $programmeId = $request->programme_id;



    $dates = Subtopic::where('programme_id', $programmeId)

        ->orderBy('date')

        ->pluck('date')

        ->unique()

        ->values();



    return response()->json([

        'success' => true,

        'dates' => $dates,

    ]);

}



public function getDates($programmeId)

{

    $dates = Subtopic::where('programme_id', $programmeId)

        ->orderBy('date')

        ->pluck('date')

        ->unique()

        ->values();



    return response()->json($dates);

}



public function getSubtopicsByProgramme(Request $request)

{

    // Retrieve the 'programme_id' from the request

    $programmeId = $request->input('programme_id');

    

    // Query the subtopics by programme_id

    $subtopics = Subtopic::where('programme_id', $programmeId)

        ->orderBy('title') // Order by title

        ->get(['id', 'title']); // Only select id and title



    // Return the subtopics as a JSON response

    return response()->json([

        'success' => true,

        'subtopics' => $subtopics,

    ]);

}

public function getProgrammeSubtopics(Request $request)

{

    $programmeId = $request->input('programme_id');

    $date = $request->input('date');  // Optionally filter by date



    // Query the subtopics based on programme_id and optionally by date

    $subtopicsQuery = Subtopic::where('programme_id', $programmeId)

        ->orderBy('title'); // Order by title



    if ($date) {

        $subtopicsQuery->where('date', $date); // Filter by date if provided

    }



    $subtopics = $subtopicsQuery->get(['id', 'title']);  // Only fetch id and title



    // Optionally, fetch available dates for the selected programme

    $dates = Subtopic::where('programme_id', $programmeId)

        ->distinct()

        ->pluck('date');



    return response()->json([

        'success' => true,

        'subtopics' => $subtopics,

        'dates' => $dates,

    ]);

}



//function for the session report



public function session_report($id)

{

    $programme = ProgrammeManagement::findOrFail($id);

    $faculty1 = User::find($programme->prog_dir_1);

    $faculty2 = User::find($programme->prog_dir_2);



    $subtopics = Subtopic::where('programme_id', $id)

        ->orderBy('date')

        ->get()

        ->map(function ($subtopic) {

            if ($subtopic->faculty_type === 'guest') {

                $subtopic->faculty = Guest::find($subtopic->faculty_id);

            } elseif ($subtopic->faculty_type === 'faculty') {

                $subtopic->faculty = User::find($subtopic->faculty_id);

            } else {

                $subtopic->faculty = null;

            }

            return $subtopic;

        })

        ->groupBy('date');



    return view('admin.programme.session_report', compact('programme', 'faculty1', 'faculty2', 'subtopics'));

}





//system and mannual annoucement letter

public function generate($id)

{

$id=$id;

 

$programme = ProgrammeManagement::find($id);



if ($programme) {

    $agency = $programme->agency_type_id; 

     $agencies = AgencyType::whereIn('id',$agency)->get();



  return view('admin.programme.annoucement_send',compact('id','agencies','programme'));



}





    // Logic for generating announcement letter

    return redirect()->back();

}







// public function store(Request $request, $id)

// {

//     // Validate request

//     $request->validate([

//         'announcement_type' => 'required|in:manual,system',

//         'manual_file' => 'required_if:announcement_type,manual|file|mimes:pdf|max:5120', // max 5MB

//     ]);



//     // Fetch the existing programme

//     $programme = ProgrammeManagement::findOrFail($id);



//     // Assign unique_id if not already assigned

//     if (is_null($programme->unique_id)) {

//         $lastUniqueId = ProgrammeManagement::max('unique_id');

//         $programme->unique_id = $lastUniqueId ? $lastUniqueId + 1 : 1;

//         $programme->save();

//     }



//     // Handle manual announcement file upload

//     if ($request->announcement_type === 'manual') {

//         if ($request->hasFile('manual_file')) {

//             $file = $request->file('manual_file');

//             $filename = 'announcement_' . $id . '_' . time() . '.' . $file->getClientOriginalExtension();



//             $destination = public_path('announcements');

//             if (!file_exists($destination)) {

//                 mkdir($destination, 0755, true);

//             }



//             $file->move($destination, $filename);



//             // Prepare announced_on array

//             $existingAnnouncedDates = is_array($programme->announced_on)

//                 ? $programme->announced_on

//                 : (is_string($programme->announced_on) ? json_decode($programme->announced_on, true) : []);



//             $today = now()->toDateString();



//             // Always append today's date (even if already exists)

//             $existingAnnouncedDates[] = $today;



//             // Update the programme record

//             $programme->update([

//                 'announcement_type' => $request->announcement_type,

//                 'manual_file' => $filename, // store only latest file

//                 'announced_on' => json_encode($existingAnnouncedDates), // append today's date

//                 'status' => 'announced',

//             ]);



//             // Send announcement to related agencies

//             $agencyIds = $programme->agency_type_id; // casted as array in model



//             if (is_array($agencyIds)) {

//                 $agencies = Agency::whereIn('agency_type_id', $agencyIds)->get();



//                 foreach ($agencies as $agency) {

//                     if (!empty($agency->emailid)) {

//                         Mail::to($agency->emailid)->send(new AnnouncementLetter($programme, $filename));

//                     }

//                 }

//             }



//             return redirect()->back()->with('success', 'Manual announcement uploaded and sent to agencies successfully.');

//         } else {

//             return redirect()->back()->with('error', 'Please upload a valid PDF.');

//         }

//     }



//     // Redirect to system announcement flow

//     return redirect()->route('admin.programme.announcement', $id);

// }

public function store(Request $request, $id)
{
      if (!auth('admin')->user()->hasAccess('programme', 'announce')) {
        return redirect()->back()->with('error', 'You do not have permission to announce programmes.');
    }   
    // Validate request
    $request->validate([
        'announcement_type' => 'required|in:manual,system',
        // 'manual_file' => 'required_if:announcement_type,manual|file|mimes:pdf|max:5120', // max 5MB

        'manual_file' => 'required_if:announcement_type,manual|array',
        'manual_file.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
		
    ]);

    // Fetch the existing programme
    $programme = ProgrammeManagement::findOrFail($id);

    // Assign unique_id if not already assigned
    if (is_null($programme->unique_id)) {
        $lastUniqueId = ProgrammeManagement::max('unique_id');
        $programme->unique_id = $lastUniqueId ? $lastUniqueId + 1 : 1;
        $programme->save();
    }

    // Handle manual announcement file upload
    if ($request->announcement_type === 'manual') {
      $filenames = [];
        if ($request->hasFile('manual_file')) {
            // $file = $request->file('manual_file');
            // $filename = 'announcement_' . $id . '_' . time() . '.' . $file->getClientOriginalExtension();

            // $destination = public_path('announcements');
            // if (!file_exists($destination)) {
            //     mkdir($destination, 0755, true);
            // }

            // $file->move($destination, $filename);


            foreach ($request->file('manual_file') as $file) {

                $filename = 'announcement_' . $id . '_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                $destination = public_path('announcements');

                if (!file_exists($destination)) {
                   mkdir($destination, 0755, true);
                }

                $file->move($destination, $filename);

                $filenames[] = $filename;
             }

            // Prepare announced_on array
            $existingAnnouncedDates = is_array($programme->announced_on)
                ? $programme->announced_on
                : (is_string($programme->announced_on) ? json_decode($programme->announced_on, true) : []);

            $today = now()->toDateString();

            // Always append today's date (even if already exists)
            $existingAnnouncedDates[] = $today;

            // Update the programme record
            $programme->update([
                'announcement_type' => $request->announcement_type,
                // 'manual_file' => $filename, 
                'manual_file' => json_encode($filenames),
                'announced_on' => json_encode($existingAnnouncedDates), // append today's date
                'status' => $request->status ?? 'Announced',
            ]);

            // Send announcement to related agencies
            $agencyIds = $programme->agency_type_id; // casted as array in model

            if (is_array($agencyIds)) {
                $agencies = Agency::whereIn('agency_type_id', $agencyIds)->get();

                foreach ($agencies as $agency) {
                    // if (!empty($agency->emailid)) {
                    //     Mail::to($agency->emailid)->send(new AnnouncementLetter($programme, $filenames));
                    // }

                   if (!empty($agency->emailid)) {

        
                      $ccEmails = [];

                      if (!empty($agency->cc_email)) {
                         $ccEmails = array_map('trim', explode(',', $agency->cc_email));
                      }
					   $subject = !empty($request->subject)
    ? $request->subject
    : 'Programme Announcement Letter';

                      Mail::to($agency->emailid)
                            ->cc($ccEmails)
                            ->send(new AnnouncementLetter($programme, $filenames ,$subject));
                    }
                  }
               }

            return redirect()->back()->with('success', 'Manual announcement uploaded and sent to agencies successfully.');
        } else {
            return redirect()->back()->with('error', 'Please upload a valid PDF.');
        }
    }

    // Redirect to system announcement flow
    return redirect()->route('admin.programme.announcement', $id);
}














public function updateInvitation(Request $request, $id)

{

    $programme = ProgrammeManagement::findOrFail($id);

    $programme->content = $request->input('content');

    $programme->save();



    return redirect()->back()->with('success', 'Invitation content updated successfully.');

}





public function store_edit_annoucement(Request $request, $id){



   

  $programme = ProgrammeManagement::findOrFail($id);

    

    // Validate the request

    $request->validate([

        'content' => 'required',

        'hindi_content' => 'required'

    ]);

    

    // Update the programme

    $programme->content = $request->input('content');

    $programme->hindi_content = $request->input('hindi_content');

    $programme->save();

    

  return redirect()->back()->with('success','Edit successfully');

}



//here is the approve function

public function approve($id)

{
     if (!auth('admin')->user()->hasAccess('programme', 'approve')) {
        abort(403, 'You do not have permission to approve programmes.');
    }

    $programme = ProgrammeManagement::findOrFail($id);



    // Use quotes for string comparison

    if ($programme->status == 'Announced') {

        return redirect()->back()->with('success', 'Announcement letter is already sent');

    }



    $programme->approved = 'approved';

    $programme->status= 'Announced';

    $programme->save();



    return redirect()->back()->with('success', 'Approved successfully');

}







//controller for the api

public function api_send_data()

{

    $programmes = ProgrammeManagement::with(['group', 'department'])->get();



    $response = $programmes->map(function ($item) {

      return [

        'id' => $item->id,

        'title' => $item->title,

        'from_date' => $item->from_date,

        'to_date' => $item->to_date,

        'location' => $item->location,

        'status' => $item->status,

        'announcement_letter' => $item->announcement_letter,

        'group_name' => $item->group->name ?? null,

        'department_name' => $item->department->name ?? null,

      ];

    });

    

    $department_getdata = Department::where('is_active', 1)->get();

    $sponsor_getdata = Sponsor::where('is_active', 1)->get();

    $group_getdata = Group::where('is_active', 1)->get();

    

    return response()->json([

      'status' => 'success',

      'data' => $response,

      'department_getdata' => $department_getdata,

      'sponsor_getdata' => $sponsor_getdata,

      'group_getdata' => $group_getdata

    ], 200);

}





public function api_archieve_data()

{

    $programmes = ProgrammeManagementArchive::with(['group', 'department', 'sponsor'])->get();



    $response = $programmes->map(function ($item) {



        return [

            'id'                 => $item->id,

            'title'              => $item->title,

            'from_date'          => $item->from_date,

            'to_date'            => $item->to_date,

            'location'           => $item->location,

            'status'             => $item->status,

            'announcement_letter'=> $item->announcement_letter,

            'group_name'         => $item->agency_type_id ?? null,

            'department_name'    => $item->group_id ?? null,

            'sponsor_name'       => $item->sponsor->name ?? null,

        ];

    });



    $department_getdata = Department::where('is_active', 1)->get();

    $sponsor_getdata    = Sponsor::where('is_active', 1)->get();

    $group_getdata      = Group::where('is_active', 1)->get();



    return response()->json([

        'status'              => 'success',

        'data'                => $response,

        'department_getdata'  => $department_getdata,

        'sponsor_getdata'     => $sponsor_getdata,

        'group_getdata'       => $group_getdata

    ], 200);

}





  

public function search_api_send_data($year = null, $month = null, $department = null, $programType = null)

{

  

    $query = ProgrammeManagement::with(['group', 'department']);



    if ($year && $year != '0') {

        $yearParts = explode('-', $year);

        $query->whereYear('from_date', '>=', $yearParts[0])

              ->whereYear('to_date', '<=', $yearParts[1]);

    }



    if ($month && $month != '0') {

        $query->whereMonth('from_date', '=', date('m', strtotime($month)));

    }



    if ($department && $department != '0') {

        $query->where('department_id', $department);

    }



    if ($programType && $programType != '0') {

        $query->where('group_id', $programType);

    }



    $programmes = $query->get();



    $response = $programmes->map(function ($item) {

        return [

            'id' => $item->id,

            'title' => $item->title,

            'from_date' => $item->from_date,

            'to_date' => $item->to_date,

            'location' => $item->location,

            'status' => $item->status,

            'group_name' => $item->group->name ?? '',

            'department_name' => $item->department->name ?? '',

        ];

    });



    return response()->json([

        'status' => 'success',

        'data' => $response,

    ]);

}











//get the programme by the code

public function getByCode($code)

{

    $programme = ProgrammeManagement::where('unique_id', $code)->first();



    if ($programme) {

        return response()->json([

            'id' => $programme->id,

            'title' => $programme->title,

        ]);

    }



    return response()->json(null);

}





public function getProgrammeByCode(Request $request)

{

    $code = $request->input('code');

    $year = $request->input('year');



    $programme = ProgrammeManagement::where('unique_id', $code)

                          ->where('financial_year', $year)

                          ->first();



    if ($programme) {

        return response()->json([

            'id' => $programme->id,

            'title' => $programme->title,

        ]);

    } else {

        return response()->json(null, 404);

    }

}

public function getUniqueId(Request $request)

{

    $programme = ProgrammeManagement::find($request->id);



    if ($programme) {

        return response()->json(['code' => $programme->unique_id]); // or unique_id

    } else {

        return response()->json(['code' => null], 404);

    }

}



public function poster($id){

  

$programme = ProgrammeManagement::with(['classRoom', 'progDir1', 'progDir2'])->find($id);



    $subtopic=Subtopic::where('programme_id',$id)->first();

    return view('admin.programme.poster',compact('programme','subtopic'));

}



public function DayToDaySession()
{
    
$distinctYears = ProgrammeManagement::select('financial_year')

                            ->distinct()

                            ->orderBy('financial_year','desc')

                            ->pluck('financial_year');

    

        // 2. All programmes

        $programmes = ProgrammeManagement::select(['id','financial_year','title'])

                        ->get();

        

        $groups=Group::select(['id','name'])->where('is_active' ,1)->get();



        $users=User::select(['id','name'])->get();

       $locations = ProgrammeManagement::select('location')

    ->whereNotNull('location')

    ->distinct()

    ->orderBy('location')

    ->pluck('location');



        $sponsors=Sponsor::select(['id','name'])->get();

        return view('admin.programme.day-to-day',compact(['groups','sponsors','programmes','distinctYears','locations','users']));


}

public function viewDayToDaySession(Request $request){

$request->validate([
    'program' => 'required|exists:subtopics,programme_id',
]);


$programme = ProgrammeManagement::findOrFail($request->program);

$subtopics = Subtopic::with(['faculty:id,name','SessionBreaks'])->where('programme_id', $programme->id)

->orderBy('date')

->get();

//dd($subtopics);



return view('admin.programme.view-day-to-day-session', compact('programme', 'subtopics'));

}



}

