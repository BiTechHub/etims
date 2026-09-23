<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\Confirmation;
use App\Models\Agency;
use App\Models\AgencyType;
use App\Models\Nomination;
use App\Models\Participant;
use App\Models\Programme;  
use App\Models\ProgrammeManagement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

use Yajra\DataTables\Facades\DataTables;

class NominationController extends Controller
{
    /**+
     * Show the nomination form with available programmes, agency types, and year options.
     */
public function list()
{
    $distinctYears = ProgrammeManagement::select('financial_year')
        ->distinct()
        ->orderBy('financial_year', 'desc')
        ->pluck('financial_year');

    $programmes = ProgrammeManagement::select([
        'id',
        'financial_year',
        'unique_id',
        'title'
    ])
    ->orderBy('id', 'desc')
    ->get();

    return view(
        'admin.Nomination.list',
        compact('distinctYears', 'programmes')
    );
}
    public function manage_nomination()
    {
        try {
        
    
            // Get all agency types
            $agencyTypes = AgencyType::select(['id', 'name'])->get();
            $programmes=ProgrammeManagement::select(['id','title'])->get();
            return view('admin.Nomination.add_nomination',compact(['agencyTypes','programmes']));
    
        } catch (\Exception $e) {
            Log::error('NominationController@manage_nomination error: ' . $e->getMessage());
            return back()->with('error', 'Failed to load nomination form. Please try again.');
        }
    }

    /**
     * Return programmes filtered by selected year range (AJAX call).
     * Expects format "YYYY-YYYY+1" (e.g. "2023-2024")
     */
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
   public function store(Request $request)
    {
        
      $request->validate([
    'agency_type_id'     => 'required|exists:agency_types,id',
    'agency_id'          => 'required|exists:agencies,id',
    'programme_id'       => 'required|exists:programmes,id',
    'nomination_date'    => 'required|date',
    'participants'       => 'required|array|min:1',

    'participants.*.name'        => 'required|string|max:255',
    'participants.*.email'       => [
        'required',
        'email',
        'max:255',
        'distinct',
        function ($attribute, $value, $fail) use ($request) {
            $exists = \App\Models\Participant::where('programme_id', $request->programme_id)
                        ->where('email', $value)
                        ->exists();
            if ($exists) {
                $fail("The email $value has already been taken for this programme.");
            }
        },
    ],
    'participants.*.title'       => 'nullable|string|max:10',
    'participants.*.state'       => 'required|string|max:255',
    'participants.*.designation' => ['required', 'regex:/^[a-zA-Z\s\.]+$/', 'max:100'],
    'participants.*.phone'       => ['required', 'digits:10'],
						     'participants.*.gender'       => ['required'],
						 
]);

    
        try {
            $fee = ProgrammeManagement::where('id', $request->programme_id)->first();
$rate= $fee->fee_structure;

            $nomination = Nomination::create([
                'agency_type_id'   => $request->agency_type_id,
                'agency_id'        => $request->agency_id,
                'nomination_date'  => $request->nomination_date,
                'rate_per_person'  => $rate,
                'programme_id'     =>$request->programme_id,
                
            ]);
    
            // Loop through participants and insert each one
            foreach ($request->participants as $participant) {
                Participant::create([
                    'nomination_id' => $nomination->id,
                    'name'          => $participant['name'],
                    'email'         => $participant['email'],
                    'title'         => $participant['title'] ?? null,
                    'city'          => $participant['city'],
					'gender'    => $participant['gender'],
                    'state'         => $participant['state'],
                    'designation'   => $participant['designation'],
                    'phone'         => $participant['phone'],
                    'programme_id'  => $request->programme_id,
                ]);
            }
    
            return redirect()->back()->with('success', 'Nomination saved successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }


    //get agency through its agency type
/**
 * Return agencies filtered by agency type (AJAX call)
 */
public function getAgenciesByType(Request $request)
{
    try {
        $request->validate([
            'agency_type_id' => 'required|exists:agency_types,id'
        ]);

        $agencies = Agency::where('agency_type_id', $request->agency_type_id)
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $agencies,
            'message' => $agencies->isEmpty() ? 'No agencies found for this type' : ''
        ]);

    } catch (\Exception $e) {
        Log::error('Error fetching agencies: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to fetch agencies'
        ], 500);
    }
}   
public function getNominationsData(Request $request)
{
    $nominations = Nomination::with([
        'agencyType:id,name',
        'agency:id,name',
        'participants' => function ($q) {
            $q->select(
                'id',
                'nomination_id',
                'name',
                'email',
                'title',
                'programme_id',
                'status'
            );
        }
    ])->select([
        'id',
        'agency_type_id',
        'agency_id',
        'programme_id',
        'nomination_date',
        'rate_per_person',
        'created_at',
        'updated_at',
    ]);

    /*
    |--------------------------------------------------------------------------
    | Calendar Year Filter
    |--------------------------------------------------------------------------
    */
    if ($request->filled('financial')) {

        $programmeIds = ProgrammeManagement::where(
            'financial_year',
            $request->financial
        )->pluck('id');

        $nominations->whereIn('programme_id', $programmeIds);
    }

    /*
    |--------------------------------------------------------------------------
    | Programme Filter
    |--------------------------------------------------------------------------
    */
    if ($request->filled('programme')) {

        $nominations->where(
            'programme_id',
            $request->programme
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Programme Code Filter
    |--------------------------------------------------------------------------
    */
    if ($request->filled('programme_code')) {

        $programmeIds = ProgrammeManagement::where(
            'unique_id',
            'like',
            '%' . trim($request->programme_code) . '%'
        )->pluck('id');

        $nominations->whereIn('programme_id', $programmeIds);
    }

    /*
    |--------------------------------------------------------------------------
    | Status Filter
    |--------------------------------------------------------------------------
    */
    if ($request->filled('status')) {

        $nominations->whereHas('participants', function ($q) use ($request) {
            $q->where('status', $request->status);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Agency Type
    |--------------------------------------------------------------------------
    */
    if ($request->filled('agency_type')) {

        $nominations->where(
            'agency_type_id',
            $request->agency_type
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Agency
    |--------------------------------------------------------------------------
    */
    if ($request->filled('agency')) {

        $nominations->where(
            'agency_id',
            $request->agency
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Nomination Date
    |--------------------------------------------------------------------------
    */
    if ($request->filled('date')) {

        $nominations->whereDate(
            'nomination_date',
            $request->date
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DataTables
    |--------------------------------------------------------------------------
    */
    return DataTables::of($nominations)

        ->addColumn(
            'agency_type_name',
            fn($row) => optional($row->agencyType)->name ?? '-'
        )

        ->addColumn(
            'agency_name',
            fn($row) => optional($row->agency)->name ?? '-'
        )

        ->addColumn('participants_display', function ($row) {
            return $row->participants
                ->pluck('name')
                ->filter()
                ->implode('<br>');
        })

        ->addColumn('emails_display', function ($row) {
            return $row->participants
                ->pluck('email')
                ->filter()
                ->implode('<br>');
        })

        ->rawColumns([
            'participants_display',
            'emails_display'
        ])

        ->make(true);
}
public function edit($id)
{
        if (!auth('admin')->user()->hasAccess('nomination', 'edit')) {
        abort(403, 'You do not have permission to edit nominations.');
    }

    // return $id;
    $nomination = Nomination::with('participants')->findOrFail($id);
    $agencyTypes = AgencyType::all();
    $agencies = Agency::where('agency_type_id', $nomination->agency_type_id)->get();
    $programmes = ProgrammeManagement::where('status', 1)->get();

    return view('admin.Nomination.edit', compact(
        'nomination',
        'agencyTypes',
        'agencies',
        'programmes'
    ));
}
//here is the code for the update function
public function update(Request $request, $id)
{
    // First validate base input and structure
    $request->validate([
        'agency_type_id'       => 'required|exists:agency_types,id',
        'agency_id'            => 'required|exists:agencies,id',
        'nomination_date'      => 'required|date',
        'rate_per_person'      => 'required|numeric|min:0',
        'participants'         => 'required|array|min:1',
        'participants.*.name'  => 'required|string|max:255',
        'participants.*.email' => [
            'required',
            'email',
            'max:255',
            'distinct',
        ],
        'participants.*.title' => 'nullable|string|max:10',
        'participants.*.state' => 'required|string|max:255',
        'participants.*.phone' => ['required', 'digits:10'],
        'participants.*.city' => 'required|string|max:255',
        'participants.*.designation' => ['required', 'regex:/^[a-zA-Z\s\.]+$/', 'max:100'],
        'participants.*.id' => 'nullable|exists:participants,id',
    ]);

    // Validate unique email per participant (skip current ID if exists)
    foreach ($request->participants as $index => $participant) {
        $exists = \App\Models\Participant::where('email', $participant['email'])
            ->where('nomination_id', $id) // Check within the same nomination
            ->where('id', '!=', $participant['id'] ?? 0)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withErrors(["participants.$index.email" => "The email has already been taken."])
                ->withInput();
        }
    }

    DB::beginTransaction();

    try {
        // Find the nomination
        $nomination = Nomination::findOrFail($id);

        // Update the nomination
        $nomination->update([
            'agency_type_id'   => $request->agency_type_id,
            'agency_id'        => $request->agency_id,
            'nomination_date'  => $request->nomination_date,
            'rate_per_person'  => $request->rate_per_person,
        ]);

        // Track existing participant IDs
        $existingParticipantIds = $nomination->participants->pluck('id')->toArray();
        $updatedParticipantIds = [];

        foreach ($request->participants as $participantData) {
            if (isset($participantData['id'])) {
                // Update existing participant
                $participant = Participant::where('id', $participantData['id'])
                    ->where('nomination_id', $nomination->id)
                    ->first();

                if ($participant) {
                    $participant->update([
                        'name'        => $participantData['name'],
                        'email'       => $participantData['email'],
                        'title'       => $participantData['title'] ?? null,
                        'state'       => $participantData['state'],
                        'phone'       => $participantData['phone'],
                        'designation' => $participantData['designation'],
                        'city'        => $participantData['city'],
                    ]);
                    $updatedParticipantIds[] = $participantData['id'];
                }
            } else {
                // Create new participant
                $newParticipant = Participant::create([
                    'nomination_id' => $nomination->id,
                    'name'          => $participantData['name'],
                    'email'         => $participantData['email'],
                    'title'         => $participantData['title'] ?? null,
                    'state'         => $participantData['state'],
                    'phone'         => $participantData['phone'],
                    'designation'   => $participantData['designation'],
                    'city'          => $participantData['city'],
                ]);
                $updatedParticipantIds[] = $newParticipant->id;
            }
        }

        // Delete removed participants
        $participantsToDelete = array_diff($existingParticipantIds, $updatedParticipantIds);
        if (!empty($participantsToDelete)) {
            Participant::whereIn('id', $participantsToDelete)->delete();
        }

        DB::commit();

        return redirect()->back()->with('success', 'Nomination updated successfully!');
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error updating nomination: ' . $e->getMessage(), ['exception' => $e]);
        return redirect()->back()
            ->with('error', 'Error: ' . $e->getMessage())
            ->withInput();
    }
}


//here is a delete funaction
public function destroy($id)
    {
         if (!auth('admin')->user()->hasAccess('nomination', 'delete')) {
        return response()->json(['error' => 'You do not have permission to delete.'], 403);
    }
        $agencyGroup = Nomination::find($id);
    
        if (!$agencyGroup) {
            return response()->json(['error' => 'Nomination  not found.'], 404);
        }
    
        $agencyGroup->delete();
    
        return response()->json(['success' => ' deleted successfully.']);
    }



    public function view_nomination(Request $request)
    {

        $duplicatePhones = Participant::select('phone', DB::raw('COUNT(*) as total'))
        ->groupBy('phone')
        ->having('total', '>', 1)
        ->get();
    

 
        // 1. Distinct calendar years
        $distinctYears = ProgrammeManagement::select('financial_year')
                            ->distinct()
                            ->orderBy('financial_year','desc')
                            ->pluck('financial_year');
    
        // 2. All programmes
        $programmes = ProgrammeManagement::select(['id','financial_year','title'])
                        ->orderBy('id', 'desc') 
                        ->get();
    
        // 3. Base query for nominations
        $query = Nomination::with(['agencyType','agency','participants']);
    
        // 4. Apply filters
        if ($request->filled('cal_year')) {
            // join through programme if needed, assuming nominations track programme_id
            $query->whereHas('programme', function($q) use ($request) {
                $q->where('financial_year', $request->cal_year);
            });
        }
        if ($request->filled('programme')) {
            $query->where('programme_id', $request->programme);
        }
    
        // 5. Fetch results
        $nominations = $query
            ->orderBy('nomination_date','desc')
            ->get();
    
        // 6. Return view
        return view('admin.Nomination.view_nomination', [
            'distinctYears'      => $distinctYears,
            'programmes'         => $programmes,
            'nominations'        => $nominations,
            'selectedYear'       => $request->cal_year,
            'selectedProgramme'  => $request->programme,
        ]);
    }


    
    public function getByProgramme(Request $request)
    {
        $programmeId = $request->programme_id;
    
        // Get current and last financial year ranges
        $now = Carbon::now();
        $startOfCurrentFY = $now->month >= 4 ? Carbon::create($now->year, 4, 1) : Carbon::create($now->year - 1, 4, 1);
        $endOfCurrentFY = $startOfCurrentFY->copy()->addYear()->subDay();
    
        $startOfLastFY = $startOfCurrentFY->copy()->subYear();
        $endOfLastFY = $startOfCurrentFY->copy()->subDay();
    
        $participants = Participant::whereHas('nomination', function ($q) use ($programmeId) {
            $q->where('programme_id', $programmeId);
        })
        ->with([
            'nomination.agencyType:id,name',
            'nomination.agency:id,name',
        ])
        ->get()
        ->map(function ($participant) use ($startOfCurrentFY, $endOfCurrentFY, $startOfLastFY, $endOfLastFY) {
            $participant->current_fy_count = Participant::where('phone', $participant->phone)
                ->whereBetween('created_at', [$startOfCurrentFY, $endOfCurrentFY])
                ->count();
    
            $participant->last_fy_count = Participant::where('phone', $participant->phone)
                ->whereBetween('created_at', [$startOfLastFY, $endOfLastFY])
                ->count();
    
            return $participant;
        });
    
        return response()->json(['data' => $participants]);
    }
    // public function getByProgrammeconfirm(Request $request)
    // {
    //     $programmeId = $request->programme_id;
    
    //     // Get current and last financial year ranges
    //     $now = Carbon::now();
    //     $startOfCurrentFY = $now->month >= 4 ? Carbon::create($now->year, 4, 1) : Carbon::create($now->year - 1, 4, 1);
    //     $endOfCurrentFY = $startOfCurrentFY->copy()->addYear()->subDay();
    
    //     $startOfLastFY = $startOfCurrentFY->copy()->subYear();
    //     $endOfLastFY = $startOfCurrentFY->copy()->subDay();
    
    //     $participants = Participant::whereHas('nomination', function ($q) use ($programmeId) {
    //         $q->where('programme_id', $programmeId);
    //     })
    //     ->with([
    //         'nomination.agencyType:id,name',
    //         'nomination.agency:id,name',
    //     ])
    //     ->get()
    //     ->map(function ($participant) use ($startOfCurrentFY, $endOfCurrentFY, $startOfLastFY, $endOfLastFY) {
    //         $participant->current_fy_count = Participant::where('phone', $participant->phone)
    //             ->whereBetween('created_at', [$startOfCurrentFY, $endOfCurrentFY])
    //             ->count();
    
    //         $participant->last_fy_count = Participant::where('phone', $participant->phone)
    //             ->whereBetween('created_at', [$startOfLastFY, $endOfLastFY])
    //             ->count();
    
    //         return $participant;
    //     });
    
    //     return response()->json(['data' => $participants]);
    // }

  public function getByProgrammeconfirm(Request $request)
{
    $query = Participant::with([
        'nomination.agencyType:id,name',
        'nomination.agency:id,name',
    ]);

    /*
    |--------------------------------------------------------------------------
    | Calendar Year
    |--------------------------------------------------------------------------
    */

    if ($request->filled('cal_year')) {

        $programmeIds = ProgrammeManagement::where(
            'financial_year',
            $request->cal_year
        )->pluck('id');

        $query->whereIn(
            'programme_id',
            $programmeIds
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Programme
    |--------------------------------------------------------------------------
    */

    if ($request->filled('programme_id')) {

        $query->where(
            'programme_id',
            $request->programme_id
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Programme Code
    |--------------------------------------------------------------------------
    */

    if ($request->filled('programme_code')) {

        $programmeIds = ProgrammeManagement::where(
            'unique_id',
            'like',
            '%' . trim($request->programme_code) . '%'
        )->pluck('id');

        $query->whereIn(
            'programme_id',
            $programmeIds
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DataTables
    |--------------------------------------------------------------------------
    */

    return DataTables::of($query)

        ->addColumn(
            'current_fy_count',
            function ($participant) {

                return Participant::where(
                    'phone',
                    $participant->phone
                )
                ->whereBetween('created_at', [
                    now()->startOfYear(),
                    now()->endOfYear()
                ])
                ->count();
            }
        )

        ->addColumn(
            'last_fy_count',
            function ($participant) {

                return Participant::where(
                    'phone',
                    $participant->phone
                )
                ->whereBetween('created_at', [
                    now()->subYear()->startOfYear(),
                    now()->subYear()->endOfYear()
                ])
                ->count();
            }
        )

        ->make(true);
}

    //function for the nomination status update


// public function updateStatus(Request $request)
// {

//   if (! auth('admin')->user()->hasAccess('nomination', 'edit')) {
//         return response()->json(['success' => false, 'message' => 'You do not have permission to perform this action.'], 403);
//     }
//     // Validate incoming request
//     $request->validate([
//         'participant_ids' => 'required|array',
//         'participant_ids.*' => 'exists:nomination_participants,id',
//         'status' => 'required|string',
//     ]);

//     // Get all participants to be updated
//     $participants = Participant::whereIn('id', $request->participant_ids)->get();

//     foreach ($participants as $participant) {
//         // Update status
      

//         // Get related programme
//         $programme = ProgrammeManagement::with('sponsor')->find($participant->programme_id);

//        // dd($programme->sponsor);
//        if(optional($programme->sponsor)->type == 'unpaid')
// {

       

//     if($request->status == 'confirm')
//     {

   
//             $participant->status = 'paid';
//         $participant->save();

//     } else {

//         $participant->status = $request->status;
//         $participant->save();

//     }

// }
// else{
//       $participant->status = $request->status;
//         $participant->save();

//     }

//         $nomination=Nomination::find($participant->nomination_id);
//         $agency=Agency::find($nomination->agency_id);   

//         // Send email
//         Mail::to($participant->email)->send(new Confirmation($participant, $programme, $agency));
//     }

//     return response()->json(['message' => 'Nomination status updated and emails sent successfully.']);
// }

public function updateStatus(Request $request)
{
    if (!auth('admin')->user()->hasAccess('nomination', 'edit')) {
        return response()->json([
            'success' => false,
            'message' => 'You do not have permission to perform this action.'
        ], 403);
    }

    $request->validate([
        'participant_ids'   => 'required|array',
        'participant_ids.*' => 'exists:nomination_participants,id',
        'status'            => 'required|string',
    ]);

    $participants = Participant::whereIn(
        'id',
        $request->participant_ids
    )->get();

    $mailErrors = [];

    foreach ($participants as $participant) {

        /*
        |--------------------------------------------------------------------------
        | Get Programme
        |--------------------------------------------------------------------------
        */

        $programme = ProgrammeManagement::with('sponsor')
            ->withTrashed()
            ->find($participant->programme_id);

        if (!$programme) {

            Log::error('Programme not found', [
                'participant_id' => $participant->id,
                'programme_id'   => $participant->programme_id,
            ]);

            $mailErrors[] = [
                'participant_id' => $participant->id,
                'email'          => $participant->email,
                'error'          => 'Programme not found for participant.'
            ];

            continue;
        }


        /*
        |--------------------------------------------------------------------------
        | Update Participant Status
        |--------------------------------------------------------------------------
        */

        $sponsorType = optional($programme->sponsor)->type;

        if (
            $sponsorType === 'unpaid' &&
            $request->status === 'confirm'
        ) {
            $participant->status = 'paid';
        } else {
            $participant->status = $request->status;
        }

        $participant->save();


        /*
        |--------------------------------------------------------------------------
        | Get Nomination
        |--------------------------------------------------------------------------
        */

        $nomination = Nomination::find($participant->nomination_id);

        if (!$nomination) {

            Log::error('Nomination not found', [
                'participant_id' => $participant->id,
                'nomination_id'  => $participant->nomination_id,
            ]);

            $mailErrors[] = [
                'participant_id' => $participant->id,
                'email'          => $participant->email,
                'error'          => 'Nomination not found.'
            ];

            continue;
        }


        /*
        |--------------------------------------------------------------------------
        | Get Agency
        |--------------------------------------------------------------------------
        */

        $agency = Agency::find($nomination->agency_id);


        /*
        |--------------------------------------------------------------------------
        | Send Confirmation Email
        |--------------------------------------------------------------------------
        */

        try {

            Mail::to($participant->email)->send(
                new Confirmation(
                    $participant,
                    $programme,
                    $agency
                )
            );

            Log::info('Nomination email sent successfully', [
                'participant_id' => $participant->id,
                'email'          => $participant->email,
            ]);

        } catch (\Throwable $e) {

            $mailErrors[] = [
                'participant_id' => $participant->id,
                'email'          => $participant->email,
                'error'          => $e->getMessage(),
            ];

            Log::error('Nomination email failed', [
                'participant_id' => $participant->id,
                'email'          => $participant->email,
                'error'          => $e->getMessage(),
            ]);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Response
    |--------------------------------------------------------------------------
    */

    if (!empty($mailErrors)) {

        return response()->json([
            'success' => true,
            'message' => 'Status updated, but some emails could not be sent.',
            'mail_errors' => $mailErrors,
        ]);
    }

    return response()->json([
        'success' => true,
        'message' => 'Nomination status updated and emails sent successfully.'
    ]);
}
      

    public function updateAttendence(Request $request){
        // Validate incoming request
        $request->validate([
            'participant_ids' => 'required|array',
            'participant_ids.*' => 'exists:nomination_participants,id',
            'status' => 'required|string',
        ]);
    
        // Update the status for all selected participants
        Participant::whereIn('id', $request->participant_ids)
            ->update(['attendence' => $request->status]);
    
        // Return a success response
        return response()->json(['message' => 'Attendence updated successfully.']);
    }



//route for the participant programme detail
public function part_currenty_prog($id)
{
    // Find the participant by ID
    $participant = Participant::findOrFail($id);

    // Get the phone number of the participant
    $match = $participant->phone;

    // Get the current date
    $currentDate = now();

    // Determine the current financial year (April to March)
    $startYear = $currentDate->year;
    if ($currentDate->month < 4) {
        $startYear -= 1;
    }
    $endYear = $startYear + 1;

    // Find all participants with the same phone number, filter by the current financial year, and eager load the 'programme' relationship
    $getParticipant = Participant::where('phone', $match)
        ->whereBetween('created_at', [
            Carbon::create($startYear, 4, 1),  // Start of the financial year (April 1st)
            Carbon::create($endYear, 3, 31)    // End of the financial year (March 31st)
        ])
        ->with('programme')
        ->get();

    // Pass the data to the view
    return view('admin.Nomination.participant_prog_detail', compact('getParticipant'));
}

public function part_last_prog($id)
{
    // Find the participant by ID
    $participant = Participant::findOrFail($id);

    // Get the phone number of the participant
    $match = $participant->phone;

    // Get the current date
    $currentDate = now();

    // Determine the last financial year (April to March of the previous year)
    $startYear = $currentDate->year - 1;  // Last year
    $endYear = $startYear + 1;  // The next year

    // Find all participants with the same phone number, filter by the last financial year, and eager load the 'programme' relationship
    $getParticipant = Participant::where('phone', $match)
        ->whereBetween('created_at', [
            Carbon::create($startYear, 4, 1),  // Start of the last financial year (April 1st)
            Carbon::create($endYear, 3, 31)    // End of the last financial year (March 31st)
        ])
        ->with('programme')
        ->get();

    // Pass the data to the view
    return view('admin.Nomination.participant_prog_detail', compact('getParticipant'));
}

public function participants_details($id){



      // Find the participant by ID
      $participant = Participant::findOrFail($id);

      // Get the phone number of the participant
      $match = $participant->phone;
  
   
  
      // Find all participants with the same phone number, filter by the current financial year, and eager load the 'programme' relationship
      $getParticipant = Participant::where('phone', $match)->with('programme')->get();
  
      // Pass the data to the view
      return view('admin.Nomination.participant_prog_detail', compact('getParticipant'));
}



}