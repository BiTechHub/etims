<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Mail\AgencyMail;
use App\Models\Agency;
use App\Models\AgencyType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Mailer;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AgencyController extends Controller
{

    public function index()
    {
        // Fetch all agency groups to populate the dropdown
     $agencyTypes = AgencyType::where('is_deleted', 0)->where('is_active', 1)->get();

        
        // Return the view with agency groups
        return view('admin.masters.agency', compact('agencyTypes'));
    }
    public function getData(Request $request)
    {
        $agencies = Agency::with('agencyType:id,name') // Eager load agencyType with only needed fields
            ->select([
                'id',
                'name',
                'user_name',
                'agency_type_id',
                'sponsor_bank',
                'chairman',
                'address',
                'state',
                'city',
                'pincode',
                'phone',
                'emailid',
                'country',
                'fax',
                'cc_email',
                'is_active',
                'approved',
                 'designation',
            ])->where('deleted_at',0);
    
        return DataTables::of($agencies)
            ->addColumn('agency_type_name', function($agency) {
                return $agency->agencyType->name ?? 'N/A'; // Show agency type name or fallback
            })
            ->addColumn('action', function($agency) {
                return ''; // Add your buttons/edit links here if needed
            })
            ->make(true);
    }
    
    

    // Store new agency group (API version)


// public function store(Request $request)
// {
//     $validated = $request->validate([
//         'agency_type_id' => 'required|exists:agency_types,id',
//         'name'           => 'required|string|max:255',
//         'user_name'      => 'required|string|max:255|unique:agencies,user_name',
//         'sponsor_bank'   => 'nullable|string|max:255',
//         'chairman'       => 'nullable|string|max:255',
//         'address'        => 'nullable|string',
//         'state'          => 'nullable|string|max:255',
//         'city'           => 'nullable|string|max:255',
//         'pincode'        => 'nullable|string|max:6',
//         'phone'          => 'nullable|string|max:10',
//         'emailid'        => 'nullable|email|unique:agencies,emailid',
//         'country'        => 'nullable|string|max:255',
//         'fax'            => 'nullable|string|max:20',
//         'approved'       => 'nullable',
//         'designation'    => 'nullable',
//         'cc_email'       => [
//             'nullable',
//             'string',
//             function ($attribute, $value, $fail) {
//                 $emails = array_map('trim', explode(',', $value));
//                 foreach ($emails as $email) {
//                     if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
//                         $fail("The $attribute field contains an invalid email: $email");
//                     }
//                 }
//             },
//         ],
//         'is_active' => 'nullable|boolean',
//     ]);

//     $validated['is_active'] = $request->has('is_active') ? $request->is_active : 1;

//     // Set approved only if admin is logged in
//     if (Auth::guard('admin')->check()) {
//         $validated['approved'] = 1;
//     }

//     // Generate credentials
//     $password = strtolower(str_replace(' ', '', $validated['user_name'])) . date('Y');
//     $token = \Str::random(60);
//     $validated['password'] = bcrypt($password);
//     $validated['token'] = $token;

//     // Save to DB
//     $agency = Agency::create($validated);

//     // Send email only if admin and email exists
//     if (Auth::guard('admin')->check() && !empty($validated['emailid'])) {
//         $ccEmails = !empty($validated['cc_email'])
//             ? array_map('trim', explode(',', $validated['cc_email']))
//             : [];

//         Mail::to($validated['emailid'])
//             ->cc($ccEmails)
//             ->send(new AgencyMail(
//                 $validated['name'],
//                 $validated['user_name'],
//                 $password // plain password sent in email
//             ));
//     }

//     return redirect()->back()->with('success', 'Agency created successfully!');
// }


public function store(Request $request)
{
    Log::info('===== Agency Store Started =====');

    try {

        Log::info('Request Data', $request->all());

        // Validation
        $validated = $request->validate([
            'agency_type_id' => 'required|exists:agency_types,id',
            'name' => 'required|string|max:255',
            'user_name' => 'required|string|max:255|unique:agencies,user_name',
            'emailid' => 'nullable|email|unique:agencies,emailid',
            'phone' => [
                'nullable',
                'string',
                function ($attribute, $value, $fail) {

                    Log::info('Validating phone', ['value' => $value]);

                    $phones = array_map('trim', explode(',', $value));

                    foreach ($phones as $phone) {

                        $mobilePattern = '/^[6-9][0-9]{9}$/';
                        $landlinePattern = '/^(\+91[- ]?)?[0-9]{3,5}[- ]?[0-9]{6,8}$/';

                        if (
                            !preg_match($mobilePattern, $phone) &&
                            !preg_match($landlinePattern, $phone)
                        ) {

                            Log::error('Phone validation failed', ['phone' => $phone]);

                            $fail("Invalid phone number: $phone");
                        }
                    }
                },
            ],
            'cc_email' => [
                'nullable',
                'string',
                function ($attribute, $value, $fail) {

                    Log::info('Validating CC Email', ['value' => $value]);

                    $emails = array_map('trim', explode(',', $value));

                    foreach ($emails as $email) {

                        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

                            Log::error('CC Email invalid', ['email' => $email]);

                            $fail("Invalid email: $email");
                        }
                    }
                },
            ],
        ]);

        Log::info('Validation Passed', $validated);


        // Status
        $validated['is_active'] = $request->has('is_active') ? $request->is_active : 1;

        if (Auth::guard('admin')->check()) {

            Log::info('Admin Logged In');

            $validated['approved'] = 0;
        }


        // Password generate
        $password = strtolower(str_replace(' ', '', $validated['user_name'])) . date('Y');

        $validated['password'] = bcrypt($password);
        $validated['token'] = Str::random(60);

        Log::info('Password Generated');


        // Save DB
        $agency = Agency::create($validated);

        Log::info('Agency Created', ['agency_id' => $agency->id]);


        // Send Email
        if (!empty($validated['emailid'])) {

            Log::info('Sending Email');

            $ccEmails = !empty($validated['cc_email'])
                ? array_map('trim', explode(',', $validated['cc_email']))
                : [];

            // Mail::to($validated['emailid'])
            //     ->cc($ccEmails)
            //     ->send(new AgencyMail(
            //         $validated['name'],
            //         $validated['user_name'],
            //         $password
            //     ));

          Mail::to($validated['emailid'])
           ->cc($ccEmails)
           ->send(new \App\Mail\AgencyRegisterMail($validated['name']));

            Log::info('Email Sent Successfully');
        }


        Log::info('===== Agency Store Completed =====');

        return redirect()->back()->with('success', 'Agency created successfully!');

    } catch (\Throwable $e) {

        Log::error('===== Agency Store Error =====', [

            'message' => $e->getMessage(),

            'line' => $e->getLine(),

            'file' => $e->getFile(),

            'trace' => $e->getTraceAsString()

        ]);

        return redirect()->back()->with('error', $e->getMessage());
    }
}


public function store_out_agencyreg(Request $request)
{
    $validated = $request->validate([
        'agency_type_id' => 'required|exists:agency_types,id',
        'name'           => 'required|string|max:255',
        'user_name'      => 'required|string|max:255|unique:agencies,user_name',
        'sponsor_bank'   => 'nullable|string|max:255',
        'chairman'       => 'nullable|string|max:255',
        'address'        => 'nullable|string',
        'state'          => 'nullable|string|max:255',
        'city'           => 'nullable|string|max:255',
        'pincode'        => 'nullable|string|max:6',
        'phone'          => 'nullable|string|max:10',
        'emailid'        => 'nullable|email|unique:agencies,emailid',
        'country'        => 'nullable|string|max:255',
        'fax'            => 'nullable|string|max:20',
        'approved'       => 'nullable',
        'cc_email'       => [
            'nullable',
            'string',
            function ($attribute, $value, $fail) {
                $emails = array_map('trim', explode(',', $value));
                foreach ($emails as $email) {
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $fail("The $attribute field contains an invalid email: $email");
                    }
                }
            },
        ],
        'is_active' => 'nullable|boolean',
    ]);

    $validated['is_active'] = $request->has('is_active') ? $request->is_active : 1;

    // Set approved only if admin is logged in
    
        $validated['approved'] = 0;
    

    // Generate credentials
    $password = strtolower(str_replace(' ', '', $validated['user_name'])) . date('Y');
    $token = \Str::random(60);
    $validated['password'] = bcrypt($password);
    $validated['token'] = $token;

    // Save to DB
    $agency = Agency::create($validated);

    // Send email only if admin and email exists
    // if (Auth::guard('admin')->check() && !empty($validated['emailid'])) {
    //     $ccEmails = !empty($validated['cc_email'])
    //         ? array_map('trim', explode(',', $validated['cc_email']))
    //         : [];

    //     Mail::to($validated['emailid'])
    //         ->cc($ccEmails)
    //         ->send(new AgencyMail(
    //             $validated['name'],
    //             $validated['user_name'],
    //             $password // plain password sent in email
    //         ));
    // }

    return redirect()->route('agency.login.form')->with('success', 'Agency created successfully!');
}
    
    
    

    // Edit agency group


    // Update agency group
public function update(Request $request)
{
    // Validate incoming request
    $validated = $request->validate([
        'agency_type_id' => 'required|exists:agency_types,id',
        'name'           => 'required|string|max:255',
        'sponsor_bank'   => 'nullable|string|max:255',
        'chairman'       => 'nullable|string|max:255',
        'address'        => 'nullable|string',
        'state'          => 'nullable|string|max:255',
        'city'           => 'nullable|string|max:255',
        'pincode'        => 'nullable|string|max:10',
        'phone'          => 'nullable|string|max:20',
        'emailid'        => 'nullable|email|unique:agencies,emailid,' . $request->id,
        'country'        => 'nullable|string|max:255',
        'fax'            => 'nullable|string|max:20',
        'designation'    =>'nullable',
        'cc_email'       => [
            'nullable',
            'string',
            function ($attribute, $value, $fail) {
                $emails = array_map('trim', explode(',', $value));
                foreach ($emails as $email) {
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $fail("The $attribute field contains an invalid email: $email");
                    }
                }
            },
        ],
    ]);

    // Find the agency or fail if not found
    $agency = Agency::findOrFail($request->id);

    // Update agency details
    $agency->update($validated);

    // Redirect back with success message
    return redirect()->back()->with('success', 'Agency updated successfully!');
}


    // Delete agency group
    public function destroy($id)
    {
        $agencyGroup = Agency::find($id);

        $agencyGroup->update([
            'deleted_at'=>1,
        ]);
    
        if (!$agencyGroup) {
            return response()->json(['error' => 'Agency  not found.'], 404);
        }
    
       
    
        return response()->json(['success' => 'Agency deleted successfully.']);
    }
    
    
    // app/Http/Controllers/AgencyGroupController.php

    public function toggleStatus(Request $request, $id)
    {
        $agency = Agency::findOrFail($id);
        $agency->is_active = $request->status;
        $agency->save();
    
        return response()->json(['message' => 'Status updated successfully!']);
    }
    

  public function approve($id)
{
    $agency = Agency::findOrFail($id);
    $agency->approved = 1;
    $agency->save();

    if ($agency->emailid) {
        $password = strtolower(str_replace(' ', '', $agency->user_name)) . date('Y');
        Mail::to($agency->emailid)
            ->cc(explode(',', $agency->cc_email ?? ''))
            ->send(new AgencyMail($agency->name, $agency->user_name, $password));
    }

    return response()->json(['message' => 'Agency approved and mail sent.']);
}

public function reject($id)
{
    $agency = Agency::findOrFail($id);
    $agency->approved = 0;
    $agency->save();

  
    return response()->json(['message' => 'Agency rejected.']);
}
    
}
