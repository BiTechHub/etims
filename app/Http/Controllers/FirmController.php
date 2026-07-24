<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\FirmRegistration;
use App\Models\FirmWorkDistrict;
use App\Models\FirmWorkExperience;
use App\Models\FirmUpsidcoProject;
use App\Models\FirmWorkInHand;

class FirmController extends Controller
{
    
public function store(Request $request)
{
    // Validate the request data
    $validatedData = $this->validateRequest($request);

    // Begin database transaction
    DB::beginTransaction();

    try {
        // Handle file uploads
        $filePaths = $this->handleFileUploads($request);

        // Create the firm registration
        $firm = $this->createFirmRegistration($request, $filePaths);

        // Attach work districts
        $this->attachWorkDistricts($request, $firm);

        // Process all project tables
        $this->processProjectTables($request, $firm);

        // Commit transaction
        DB::commit();

        return redirect()->back()
               ->with('message', 'Registration successful! Your ID: ' . $firm->unique_id);

    } catch (\Exception $e) {
        // Rollback transaction on error
        DB::rollBack();
        
        // Delete any uploaded files
        $this->cleanupUploadedFiles($filePaths);

        return redirect()->back()
               ->withInput()
               ->withErrors(['error' => 'Registration failed: ' . $e->getMessage()]);
    }
}

/**
 * Validate the request data
 */
protected function validateRequest(Request $request)
{
    return $request->validate([
        'grade' => 'nullable|exists:grades,id',
        'firm_name' => 'nullable|string|max:255',
        'name' => 'nullable|string|max:255',
        'social_cat' => 'nullable|in:General,OBC,SC,ST,Other',
        'social_cat_input' => 'required_if:social_cat,Other|nullable|string|max:255',
        'office_dist' => 'nullable|exists:district,id',
        'aadhar' => 'nullable',
        'mobile' => 'nullable|string|size:10|regex:/^[0-9]{10}$/',
        'email' => 'nullable|email|max:255|unique:firm_registrations,email',
        'address' => 'nullable|string|max:500',
        'pan' => 'nullable',
        'gst' => 'nullable',
        'msme_reg' => 'nullable|in:Yes,No',
        'msme' => 'nullable_if:msme_reg,Yes|nullable|string|max:50',
        'exp' => 'nullable|numeric|min:0|max:100',
        'reg_fees' => 'nullable|numeric|min:0',
        'worka' => 'nullable|array|min:1',
        'worka.*' => 'exists:district,id',
        
        // File validations
        'staff_stamp_file' => 'nullable|file|mimes:pdf|max:2048',
        'machine_stamp_file' => 'nullable|file|mimes:pdf|max:2048',
        'msme_file' => 'required_if:msme_reg,Yes|nullable|file|mimes:pdf|max:2048',
        'status_file' => 'nullable|file|mimes:pdf|max:2048',
        'char_file' => 'nullable|file|mimes:pdf|max:2048',
        'gst_file' => 'nullable|file|mimes:pdf|max:2048',
        'pan_file' => 'nullable|file|mimes:pdf|max:2048',
        'aadhar_file' => 'nullable|file|mimes:pdf|max:2048',
        'proof_file' => 'nullable|file|mimes:pdf|max:2048',
        'regarding_file' => 'nullable|file|mimes:pdf|max:2048',
        'criminal_file' => 'nullable|file|mimes:pdf|max:2048',
        'notice_file' => 'nullable|file|mimes:pdf|max:2048',
        
        // Work experience validations
        'project_name_we.*' => 'sometimes|nullable|string|max:255',
        'project_started_we.*' => 'sometimes|nullable|date',
        'project_ended_we.*' => 'sometimes|nullable|date|after_or_equal:project_started_we.*',
        'project_status_we.*' => 'sometimes|nullable|in:OnTime,Delayed',
        'project_cost_we.*' => 'sometimes|nullable|numeric|min:0',
        'payment_rec_we.*' => 'sometimes|nullable|numeric|min:0|lte:project_cost_we.*',
        'work_order_we.*' => 'sometimes|nullable|file|mimes:pdf|max:2048',
        'completion_we.*' => 'sometimes|nullable|file|mimes:pdf|max:2048',
        
        // UPSIDCO projects validations
        'project_name_up.*' => 'sometimes|nullable|string|max:255',
        'project_started_up.*' => 'sometimes|nullable|date',
        'project_ended_up.*' => 'sometimes|nullable|date|after_or_equal:project_started_up.*',
        'project_status_up.*' => 'sometimes|nullable|in:OnTime,Delayed',
        'project_cost_up.*' => 'sometimes|nullable|numeric|min:0',
        'payment_rec_up.*' => 'sometimes|nullable|numeric|min:0|lte:project_cost_up.*',
        'work_order_up.*' => 'sometimes|nullable|file|mimes:pdf|max:2048',
        'agreement_up.*' => 'sometimes|nullable|file|mimes:pdf|max:2048',
        'completion_up.*' => 'sometimes|nullable|file|mimes:pdf|max:2048',
        
        // Work in hand validations
        'project_name_wh.*' => 'sometimes|nullable|string|max:255',
        'project_started_wh.*' => 'sometimes|nullable|date',
        'project_ended_wh.*' => 'sometimes|nullable|date|after_or_equal:project_started_wh.*',
        'project_status_wh.*' => 'sometimes|nullable|in:OnTime,Delayed',
        'project_cost_wh.*' => 'sometimes|nullable|numeric|min:0',
        'payment_rec_wh.*' => 'sometimes|nullable|numeric|min:0|lte:project_cost_wh.*',
        'depart_name_wh.*' => 'sometimes|nullable|string|max:255',
        'work_order_wh.*' => 'sometimes|nullable|file|mimes:pdf|max:2048',
    ]);
}

/**
 * Handle all file uploads
 */
protected function handleFileUploads(Request $request): array
{
    $filePaths = [];
    
    $fileFields = [
        'staff_stamp_file' => 'stamp-papers',
        'machine_stamp_file' => 'stamp-papers',
        'msme_file' => 'msme',
        'status_file' => 'solvency',
        'char_file' => 'character',
        'gst_file' => 'gst',
        'pan_file' => 'pan',
        'aadhar_file' => 'aadhar',
        'proof_file' => 'affidavits/proof',
        'regarding_file' => 'affidavits/regarding',
        'criminal_file' => 'affidavits/criminal',
        'notice_file' => 'notices'
    ];

    foreach ($fileFields as $field => $folder) {
        if ($request->hasFile($field)) {
            $filePaths[$field] = $request->file($field)->store("firm-documents/{$folder}");
        }
    }

    return $filePaths;
}

/**
 * Create the firm registration record
 */
protected function createFirmRegistration(Request $request, array $filePaths): FirmRegistration
{
    return FirmRegistration::create([
        'grade_id' => $request->grade,
        'firm_name' => $request->firm_name,
        'contact_person' => $request->name,
        'social_category' => $request->social_cat,
        'social_category_other' => $request->social_cat === 'Other' ? $request->social_cat_input : null,
        'gst_district_id' => $request->office_dist,
        'aadhar_number' => $request->aadhar,
        'mobile' => $request->mobile,
        'email' => $request->email,
        'gst_address' => $request->address,
        'pan_number' => $request->pan,
        'gst_number' => $request->gst,
        'msme_registered' => $request->msme_reg === 'Yes',
        'msme_number' => $request->msme_reg === 'Yes' ? $request->msme : null,
        'work_experience_years' => $request->exp,
        'registration_fees' => $request->reg_fees,
        
        // File paths
        'staff_stamp_file_path' => $filePaths['staff_stamp_file'] ?? null,
        'machine_stamp_file_path' => $filePaths['machine_stamp_file'] ?? null,
        'msme_file_path' => $filePaths['msme_file'] ?? null,
        'status_file_path' => $filePaths['status_file'],
        'character_file_path' => $filePaths['char_file'],
        'gst_file_path' => $filePaths['gst_file'],
        'pan_file_path' => $filePaths['pan_file'],
        'aadhar_file_path' => $filePaths['aadhar_file'],
        'proof_file_path' => $filePaths['proof_file'],
        'regarding_file_path' => $filePaths['regarding_file'],
        'criminal_file_path' => $filePaths['criminal_file'],
        'notice_file_path' => $filePaths['notice_file'] ?? null,
    ]);
}

/**
 * Attach work districts to the firm
 */
protected function attachWorkDistricts(Request $request, FirmRegistration $firm): void
{
    $firm->workDistricts()->attach($request->worka);
}

/**
 * Process all project tables
 */
protected function processProjectTables(Request $request, FirmRegistration $firm): void
{
    // Process Work Experience
    $this->processWorkExperiences($request, $firm);
    
    // Process UPSIDCO Projects
    $this->processUpsidcoProjects($request, $firm);
    
    // Process Work in Hand
    $this->processWorkInHand($request, $firm);
}

/**
 * Process Work Experiences
 */
protected function processWorkExperiences(Request $request, FirmRegistration $firm): void
{
    if (!$request->has('project_name_we')) {
        return;
    }

    foreach ($request->project_name_we as $index => $projectName) {
        $workOrderPath = $request->file('work_order_we')[$index]->store('Contractor/Work-Experience/Work-Order-Agreement');
        $completionPath = isset($request->file('completion_we')[$index]) 
            ? $request->file('completion_we')[$index]->store('Contractor/Work-Experience/Completion_Reports') 
            : null;

        FirmWorkExperience::create([
            'firm_id' => $firm->id,
            'project_name' => $projectName,
            'project_started' => $request->project_started_we[$index],
            'project_ended' => $request->project_ended_we[$index],
            'project_status' => $request->project_status_we[$index],
            'project_cost' => $request->project_cost_we[$index],
            'payment_received' => $request->payment_rec_we[$index],
            'work_order_path' => $workOrderPath,
            'completion_report_path' => $completionPath,
        ]);
    }
}

/**
 * Process UPSIDCO Projects
 */
protected function processUpsidcoProjects(Request $request, FirmRegistration $firm): void
{
    if (!$request->has('project_name_up')) {
        return;
    }

    foreach ($request->project_name_up as $index => $projectName) {
        $workOrderPath = $request->file('work_order_up')[$index]->store('Contractor/Completed-Projects/Work-Order');
        $agreementPath = $request->file('agreement_up')[$index]->store('Contractor/Completed-Projects/Agreement');
        $completionPath = $request->file('completion_up')[$index]->store('Contractor/Completed-Projects/Completed-Projects');

        FirmUpsidcoProject::create([
            'firm_id' => $firm->id,
            'project_name' => $projectName,
            'project_started' => $request->project_started_up[$index],
            'project_ended' => $request->project_ended_up[$index],
            'project_status' => $request->project_status_up[$index],
            'project_cost' => $request->project_cost_up[$index],
            'payment_received' => $request->payment_rec_up[$index],
            'work_order_path' => $workOrderPath,
            'agreement_path' => $agreementPath,
            'completion_report_path' => $completionPath,
        ]);
    }
}

/**
 * Process Work in Hand
 */
protected function processWorkInHand(Request $request, FirmRegistration $firm): void
{
    if (!$request->has('project_name_wh')) {
        return;
    }

    foreach ($request->project_name_wh as $index => $projectName) {
        $workOrderPath = $request->file('work_order_wh')[$index]->store('Contractor/Work-In-Hand/Work-Order');

        FirmWorkInHand::create([
            'firm_id' => $firm->id,
            'project_name' => $projectName,
            'project_started' => $request->project_started_wh[$index],
            'estimated_end_date' => $request->project_ended_wh[$index],
            'project_status' => $request->project_status_wh[$index],
            'project_cost' => $request->project_cost_wh[$index],
            'payment_received' => $request->payment_rec_wh[$index],
            'department_name' => $request->depart_name_wh[$index],
            'work_order_path' => $workOrderPath,
        ]);
    }
}

/**
 * Cleanup uploaded files if transaction fails
 */
protected function cleanupUploadedFiles(array $filePaths): void
{
    foreach ($filePaths as $path) {
        if ($path) {
            Storage::delete($path);
        }
    }
}

}
