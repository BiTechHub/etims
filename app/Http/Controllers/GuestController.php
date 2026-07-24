<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class GuestController extends Controller
{
    public function index()
    {
        return view('admin.masters.guest');
    }
public function store(Request $request)
{
    $validated = $request->validate([
        'name'           => 'required|string|max:255',
        'designation'    => 'nullable|string|max:255',
        'dob'            => 'nullable|date',
        'phone'          => 'required|string|max:15',
        'email'          => 'required|email|unique:guests,email',
        'address'        => 'nullable|string',
        'state'          => 'required|string|max:255',
        'city'           => 'required|string|max:255',
        'account_no'     => 'required|string|max:30',
        'ifsc'           => 'required|string|max:20',
        'branch_name'    => 'required|string|max:255',
        'bank_address'   => 'required|string',
        'acount_holder_name'       => 'required|string',
		'bank_name'=> 'required|string',
        'specialization' => 'nullable|string|max:255',
        'kyc.*'          => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'cv'             => 'nullable|file|mimes:pdf,doc,docx|max:2048',
    ]);

    // Handle KYC uploads
    if ($request->hasFile('kyc')) {
        $kycPaths = [];

        foreach ($request->file('kyc') as $file) {
            $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('assets/kyc');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $file->move($destinationPath, $filename);
            $kycPaths[] = 'assets/kyc/' . $filename;
        }

        $validated['kyc'] = json_encode($kycPaths);
    }

    // Handle CV upload
    if ($request->hasFile('cv')) {
        $cvFile = $request->file('cv');
        $cvFilename = time() . '_' . uniqid() . '_' . $cvFile->getClientOriginalName();
        $cvPath = public_path('assets/cv');

        if (!file_exists($cvPath)) {
            mkdir($cvPath, 0755, true);
        }

        $cvFile->move($cvPath, $cvFilename);
        $validated['cv'] = 'assets/cv/' . $cvFilename;
    }

    // Store to database
    Guest::create($validated);

    return redirect()->back()->with('success', 'Guest Faculty added successfully!');
}


public function getData(Request $request)
{
    $agencyTypes = Guest::select(['name',
        'designation',
        'dob',
        'phone',
        'email',
        'address',
        'state',
        'city',
        'pincode',
        'account_no',
        'ifsc',
        'branch_name',
		'acount_holder_name',
								 
        'bank_address',
        'kyc',
        'bank_name',
        'specialization',
       
    'id'])->where('is_delete',0);

    return DataTables::of($agencyTypes)
        ->make(true);
}


//update 
public function update(Request $request, $id)
{
    // Find the existing record
    $guest = Guest::findOrFail($id);

    // Validate request
    $validated = $request->validate([
        'name'            => 'required|string|max:255',
        'designation'     => 'nullable|string|max:255',
        'dob'             => 'nullable|date',
        'phone'           => 'nullable|string|max:20',
        'email'           => 'nullable|email|max:255',
        'address'         => 'nullable|string',
        'state'           => 'nullable|string|max:255',
        'city'            => 'nullable|string|max:255',
        'pincode'         => 'nullable|string|max:10',
        'account_no'      => 'nullable|string|max:30',
        'ifsc'            => 'nullable|string|max:15',
        'bankname'        => 'nullable|string|max:255',
        'branch_name'     => 'nullable|string|max:255',
        'bank_address'    => 'nullable|string|max:255',
        'specialization'  => 'nullable|string|max:255',
        'kyc'             => 'nullable|array',
        'kyc.*'           => 'file|mimes:pdf,jpg,jpeg,png|max:2048',
        'cv'              => 'nullable|file|mimes:pdf,doc,docx|max:2048',
    ]);

    // Handle KYC file uploads
    if ($request->hasFile('kyc')) {
        $kycPaths = [];

        foreach ($request->file('kyc') as $file) {
            $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('assets/kyc');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $file->move($destinationPath, $filename);
            $kycPaths[] = 'assets/kyc/' . $filename;
        }

        $validated['kyc'] = json_encode($kycPaths);
    }

    // Handle CV upload
    if ($request->hasFile('cv')) {
        $cvFile = $request->file('cv');
        $cvFilename = time() . '_' . uniqid() . '_' . $cvFile->getClientOriginalName();
        $cvPath = public_path('assets/cv');

        if (!file_exists($cvPath)) {
            mkdir($cvPath, 0755, true);
        }

        $cvFile->move($cvPath, $cvFilename);
        $validated['cv'] = 'assets/cv/' . $cvFilename;
    }

    // Update guest record
    $guest->update($validated);

    return redirect()->back()->with('success', 'Guest details updated successfully!');
}

//delete

public function destroy($id)
{
    $guest = Guest::find($id);
    $guest->update([
        'is_delete'=>1,
    ]);
    if (!$guest) {
        return response()->json(['error' => 'Guest not found.'], 404);
    }

    // Handle KYC file deletion if exists
    if ($guest->kyc) {
        // Decode the KYC paths stored in the database
        $kycFiles = json_decode($guest->kyc, true); // Assuming KYC files are stored as a JSON array

        foreach ($kycFiles as $file) {
            $filePath = public_path($file); // Get the full path to the file

            // Check if file exists before deleting
            if (file_exists($filePath)) {
                unlink($filePath); // Delete the file
            }
        }
    }

    // Delete the guest record
    // $guest->delete();

    return response()->json(['success' => 'Guest deleted successfully.']);
}


}
