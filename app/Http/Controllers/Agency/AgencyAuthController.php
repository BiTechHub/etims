<?php

namespace App\Http\Controllers\Agency;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Agency;
use App\Models\Group;
use App\Models\Sponsor;
use App\Models\ProgrammeManagement;
use Yajra\DataTables\Facades\DataTables;
use App\Models\AgencyType;
use App\Models\Nomination;
use App\Models\Participant;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;

class AgencyAuthController extends Controller
{
    public function showLogin()
    {
     
        if (Auth::guard('agency')->check()) {
            return redirect()->route('dashboard'); // Redirect if already logged in
        }
    
        return view('Agency.login');
    }

    public function dashboard(){
        return view('Agency.dashboard');
    }

  public function login(Request $request)
  {
    $request->validate([
        'user_name' => 'required',
        'password' => 'required|min:6',
    ]);

    $user = \App\Models\Agency::where('user_name', $request->user_name)->first();

    if (!$user) {
        return back()->withErrors([
            'user_name' => 'User not found.'
        ]);
    }

	//  dd( $user );
    if ($user->approved != 1) {
        return back()->withErrors([
            'user_name' => 'Your account is not approved yet.'
        ]);
    }

    if (Auth::guard('agency')->attempt([
        'user_name' => $request->user_name,
        'password' => $request->password
    ])) {
        return redirect()->route('dashboard');
    }

    return back()->withErrors([
        'user_name' => 'Invalid password.'
    ]);
  }

    // public function logout()
    // {
    //     Auth::guard('agency')->logout();
    //     return redirect()->route('agency.login.form');
    // }

    public function logout(Request $request)
{
    Auth::guard('agency')->logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('welcome');
}


    public function programmes(){
           $distinctYears = ProgrammeManagement::select('financial_year')
                            ->distinct()
                            ->orderBy('financial_year','desc')
                            ->pluck('financial_year');
    
        // 2. All programmes
        $programmes = ProgrammeManagement::select(['id','financial_year','title'])
                        ->get();
        $groups=Group::select(['id','name'])->get();
        
        $sponsors=Sponsor::select(['id','name'])->get();
        return view('Agency.prog_mag_list',compact(['groups','sponsors',]));
    }
      
    public function add_nomination($id) {
        try {
            $userInfo = Agency::where('id', '=', Auth::guard('agency')->user()->id)->first();
            $programmes = ProgrammeManagement::where('id', '=', $id)->first();
            $agencyTypes = AgencyType::select(['id', 'name'])->get();
            return view('Agency.Nomination.add_nomination', [
                'agencyTypes' => $agencyTypes,
                'userInfo' => $userInfo,
                'programmes' => $programmes
            ]);
        } catch (\Exception $e) {
            Log::error('NominationController@manage_nomination error: ' . $e->getMessage());
            return back()->with('error', 'Failed to load nomination form. Please try again.');
        }
    }
    
    
    


public function getData(Request $request)
{
    
    $programmes = ProgrammeManagement::with([
        'group:id,name',
        'sponsor:id,name',
        'progDir1:id,email,phone,name',
        'progDir2:id,email,phone,name'
    ])->select([
        'id',
        'group_id',
        'unique_id',
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
        'agency_type_id',
        'manual_file',
        'prog_dir_1',
        'prog_dir_2',
    ]);

   
   

    // Apply filters
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

    // Filter by agency_type_id (handles array format [8,9])
    $userAgencyTypeId = Auth::guard('agency')->user()->agency_type_id;
    $programmes->where(function($query) use ($userAgencyTypeId) {
        $query->whereJsonContains('agency_type_id', $userAgencyTypeId)
              ->orWhere('agency_type_id', 'like', '%'.$userAgencyTypeId.'%');
    });

    // Additional status filter
    $programmes->where('status', 'Announced');

    // dd($programmes->get());

    return DataTables::of($programmes)
        ->addColumn('group_name', function ($row) {
            return $row->group->name ?? '-';
        })
        ->addColumn('sponsor_name', function ($row) {
            return $row->sponsor->name ?? '-';
        })

        ->addColumn('announcement_letter', function ($row) {
            return $row->manual_file ?? null; 
        })


         ->addColumn('faculty_emails', function ($row) {

           $emails = [];

         if ($row->progDir1 && $row->progDir1->email) {
            $emails[] = '1 - ' . $row->progDir1->email;
         }

         if ($row->progDir2 && $row->progDir2->email) {
            $emails[] = (count($emails) + 1) . ' - ' . $row->progDir2->email;
         } 

         return implode('<br>', $emails ?: ['-']);
        })


         ->addColumn('faculty_mobiles', function ($row) {

    $mobiles = [];

    if ($row->progDir1 && $row->progDir1->phone) {
        $mobiles[] = '1 - ' . $row->progDir1->phone;
    }

    if ($row->progDir2 && $row->progDir2->phone) {
        $mobiles[] = (count($mobiles) + 1) . ' - ' . $row->progDir2->phone;
    }

    return implode('<br>', $mobiles ?: ['-']);
})

 ->addColumn('faculty_name', function ($row) {

    $names = [];

    if ($row->progDir1 && $row->progDir1->name) {
        $names[] = '1 - ' . $row->progDir1->name;
    }

    if ($row->progDir2 && $row->progDir2->name) {
        $names[] = (count($names) + 1) . ' - ' . $row->progDir2->name;
    }

    return implode('<br>', $names ?: ['-']);
})
         ->editColumn('from_date', function ($row) {
            return \Carbon\Carbon::parse($row->from_date)->format('d-m-Y');
         })

         ->editColumn('to_date', function ($row) {
            return \Carbon\Carbon::parse($row->to_date)->format('d-m-Y');
         })

         ->rawColumns(['faculty_emails', 'faculty_mobiles'])
        ->make(true);
}


public function store(Request $request)
{
    $request->validate([
        'agency_type_id'     => 'required|exists:agency_types,id',
        'agency_id'          => 'required|exists:agencies,id',
        'programme_id'       => 'required|exists:programmes,id',
        'nomination_date'    => 'required|date',
        'rate_per_person'    => 'nullable',
        'participants'       => 'required|array|min:1',
        'participants.*.name'        => 'required|string|max:255',
        'participants.*.email'       => 'required|email|max:255',
        'participants.*.title'       => 'nullable|string|max:10',
        'participants.*.state'       => 'required|string|max:255',
        'participants.*.phone'       => ['required', 'digits:10'],
        'participants.*.gender'      => 'required|in:Male,Female,Other',
        'participants.*.designation' => ['required', 'regex:/^[a-zA-Z\s\.]+$/', 'max:100'],
    ]);

    // Check unique phone within same programme
    foreach ($request->participants as $index => $participant) {
        $exists = \App\Models\Participant::where('programme_id', $request->programme_id)
            ->where('phone', $participant['phone'])
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors(["participants.$index.phone" => "Phone number already exists for this programme."]);
        }
    }

    try {
        // Get programme and sponsor
        $programme = ProgrammeManagement::with('sponsor')->find($request->programme_id);
        $ratePerPerson = $request->rate_per_person; // Default

        if ($programme && $programme->sponsor) {
            $sponsorName = $programme->sponsor->name;

            if ($sponsorName === "Paid") {
                $agencyFee = DB::table('programme_agency_fees')
                    ->where('programme_id', $request->programme_id)
                    ->where('agency_type_id', $request->agency_type_id)
                    ->value('fee');

                $ratePerPerson = $agencyFee ?? 0;
            }
            elseif ($sponsorName === "Customised") {
                if (!empty($programme->participant_fee)) {
                    $ratePerPerson = $programme->participant_fee;
                } elseif (!empty($programme->program_fee)) {
                    $ratePerPerson = $programme->program_fee;
                } else {
                    $ratePerPerson = 0;
                }
            }
        }

        // Create nomination
        $nomination = Nomination::create([
            'agency_type_id'   => $request->agency_type_id,
            'agency_id'        => $request->agency_id,
            'programme_id'     => $request->programme_id,
            'nomination_date'  => $request->nomination_date,
            'rate_per_person'  => $ratePerPerson,
        ]);

        // Insert participants
        foreach ($request->participants as $participant) {
            $token = \Str::random(10);
            Participant::create([
                'nomination_id'   => $nomination->id,
                'programme_id'    => $request->programme_id,
                'agency_type_id'  => $request->agency_type_id,
                'agency_id'       => $request->agency_id,
                'date'            => $request->nomination_date,
                'rate'            => $ratePerPerson,
                'status'          => 'Pending',
                'name'            => $participant['name'],
                'email'           => $participant['email'],
                'title'           => $participant['title'] ?? null,
                'city'            => $participant['city'],
                'state'           => $participant['state'],
                'designation'     => $participant['designation'],
                'phone'           => $participant['phone'],
                'gender'          => $participant['gender'],
                'token_id'        => $token,
                'rooms_id'        => 0,
            ]);
        }

        // Redirect conditionally
        if ($programme && $programme->sponsor && $programme->sponsor->name === "NABARD") {
            return redirect()->back()->with('success', 'Uploaded successfully');
        } else {
            return redirect()->route('Agency.Nomination.payment', $nomination->id);
        }

    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
    }
}

 public function nomination_list(Request $request)
        {

        $request->validate([
            'program' => 'required|string',
        ]);

     

        



           $programme = ProgrammeManagement::with([
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
    'agency_type_id',
])
->whereJsonContains(
    'agency_type_id',
    Auth::guard('agency')->user()->agency_type_id
)
->find(base64_decode($request->program));

if(!$programme){
    return redirect()->back()->with('error', 'Invalid programme');
}



            return view('Agency.Nomination.list' , compact('programme'));

        }

public function getNominationsData(Request $request)
{
    $search = $request->search['value'] ?? '';

    $programme = ProgrammeManagement::whereJsonContains(
        'agency_type_id',
        Auth::guard('agency')->user()->agency_type_id
    )->find($request->program);

    if(!$programme){
        return response()->json([
            'error' => 'Programme not found'
        ]);
    }

    $nominations = Participant::where('programme_id', $programme->id)
        ->where('agency_id', Auth::guard('agency')->user()->id)
        ->latest();

    if($search){

        $nominations->where(function($query) use ($search){

            $query->where('name', 'like', '%'.$search.'%')
                  ->orWhere('email', 'like', '%'.$search.'%')
                  ->orWhere('phone', 'like', '%'.$search.'%');

        });
    }

    return DataTables::of($nominations)

        ->addColumn('name', function($row){
            return $row->name;
        })

        ->addColumn('email', function($row){
            return $row->email;
        })

        ->addColumn('phone', function($row){
            return $row->phone;
        })

        ->addColumn('date', function($row){
            return $row->date;
        })

        ->addColumn('designation', function($row){
            return $row->designation;
        })

        ->addColumn('status', function($row){

            if($row->status == 'pending'){
                return '<span class="badge bg-warning">Pending</span>';
            }
            elseif($row->status == 'paid'){
                return '<span class="badge bg-success">Paid</span>';
            }
            elseif($row->status == 'confirmed'){
                return '<span class="badge bg-primary">Confirmed</span>';
            }

            return '<span class="badge bg-secondary">'.$row->status.'</span>';
        })

        ->rawColumns(['status'])

        ->make(true);
}





public function pay($nominationId){


   $nomination=Nomination::findOrFail($nominationId);

   $participantCount = Participant::where('nomination_id', $nominationId)->Where('status','Confirm')->count();

    return view('Agency.Nomination.payment',compact('nomination','participantCount'));
}



public function processPayment($nominationId)
{

$nomination=Nomination::findOrFail($nominationId);
    $nomination->status = 'paid';
    $nomination->save();


    $participants = Participant::where('nomination_id', $nominationId)->where('status','Confirm')->get();

    foreach ($participants as $participant) {
        $participant->status = 'paid';
        $participant->save();
    }

    return response()->json([
        'success' => true,
        'message' => 'Payment completed successfully.',
        'redirect_url' => route('nomination.list.show') 
    ]);

}


public function edit($id)
{
    // return $id;
    $nomination = Nomination::with('participants')->findOrFail($id);
    $agencyTypes = AgencyType::all();
    $agencies = Agency::where('agency_type_id', $nomination->agency_type_id)->get();
    $programmes = ProgrammeManagement::where('status', 1)->get();

    return view('Agency.Nomination.edit', compact(
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
        'participants.*.id' => 'nullable|exists:nomination_participants,id',
        'participants.*.gender' => 'required|in:Male,Female,Other',
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
                        'gender'       => $participantData['gender'],
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
                    'gender'       => $participantData['gender'],
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
        $agencyGroup = Nomination::find($id);
    
        if (!$agencyGroup) {
            return response()->json(['error' => 'Nomination  not found.'], 404);
        }
    
        $agencyGroup->delete();
    
        return response()->json(['success' => ' deleted successfully.']);
    }


    
public function showPaymentPage(Request $request)
{

$programme = ProgrammeManagement::whereJsonContains(
    'agency_type_id',
    Auth::guard('agency')->user()->agency_type_id
)->where('sponsor_id','!=',3)->find(base64_decode($request->programme));

if(!$programme){
    return redirect()->back()->with('error', 'Programme not found');
}

$participant = Participant::where('programme_id',$programme->id)->where('status','confirm')->first();


if(!$participant){
    return redirect()->back()->with('error', 'Participant not found Please Wait for  Confirmation');
}


    
    $nomination = Nomination::with(['programme', 'agencyType'])->findOrFail($participant->nomination_id);
    if(!$nomination){
        return redirect()->back()->with('error', 'Nomination not found');
    }

    $nominationId = $nomination->id;

   // dd($nomination);

    $participantCount = Participant::where('programme_id',$programme->id)->where('status','confirm')->count();

   // dd($participantCount);
    $feeStructure = 0;
    $discount = 0;
    $total = 0;

    $programme = $nomination->programme;
    $sponsor = $programme->sponsor;
    $maxDiscount = $programme->max_disc_amt ?? 0;

    if ($sponsor) {
        $sponsorName = $sponsor->name;

        if ($sponsorName === 'Paid') {
            // Use agency-wise fee
            $agencyFee = DB::table('programme_agency_fees')
                ->where('programme_id', $programme->id)
                ->where('agency_type_id', $nomination->agency_type_id)
                ->value('fee');
            $feeStructure = $agencyFee ?? 0;
        } elseif ($sponsorName === 'Customised') {
            // Use participant_fee or program_fee
            if (!empty($programme->participant_fee)) {
                $feeStructure = $programme->participant_fee;
            } elseif (!empty($programme->program_fee)) {
                $feeStructure = $programme->program_fee;
            } else {
                $feeStructure = 0;
            }
        } else {
            // Default fee
            $feeStructure = $programme->fee_structure ?? 0;
        }
    }

    // Calculate total and discount
    $discount = $participantCount * $maxDiscount;
    $total = ($participantCount * $feeStructure) - $discount;

    // Save total
    $nomination->total = $total;
    $nomination->save();

    return view('Agency.Nomination.show_payment', compact(
        'total', 'nominationId', 'nomination',
        'participantCount', 'feeStructure', 'discount'
    ));
}




















}
