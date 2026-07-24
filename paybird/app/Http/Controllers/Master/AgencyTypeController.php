<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\AgencyGroup;
use App\Models\AgencyType;
use Illuminate\Http\Request;
use Yajra\DataTables\Contracts\DataTable;
use Yajra\DataTables\Facades\DataTables;

class AgencyTypeController extends Controller
{
    public function index()
{
    
    return view('admin.masters.agency_type');
}

public function getData(Request $request)
{
    $agencyTypes = AgencyType::select(['id', 'name','hindi_name', 'is_active'])->where('is_deleted',0);

    return DataTables::of($agencyTypes)
        ->make(true);
}


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required','string','not_regex:/<script\b[^>]*>/i'],
            'hindi_name' => ['required','string','not_regex:/<script\b[^>]*>/i'],
            'is_active' => 'nullable|boolean'
        ]);
        // Remove HTML tags safely
        $validated['name'] = strip_tags($validated['name']);
        $validated['hindi_name'] = strip_tags($validated['hindi_name']);
        $validated['is_active'] = $request->has('is_active') ? $request->is_active : 1;
        AgencyType::create($validated);
    
        return redirect()->back()->with('success', 'Agency type created successfully!');
    }


 // Update agency group
 public function update(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'name' => ['required','string','max:100','not_regex:/<script\b[^>]*>/i'],
            'hindi_name' => ['required','string','max:100','not_regex:/<script\b[^>]*>/i'],
        ]);
    
        // Remove HTML tags (XSS protection)
        $validated['name'] = htmlspecialchars(strip_tags($validated['name']), ENT_QUOTES, 'UTF-8');
        $validated['hindi_name'] = htmlspecialchars(strip_tags($validated['hindi_name']), ENT_QUOTES, 'UTF-8');
    
        // Find record
        $agency = AgencyType::findOrFail($request->id);
    
        // Update record
        $agency->update($validated);
    
        return redirect()->back()->with('success', 'Agency type updated successfully!');
    }

 public function destroy($id)
 {
     try {
         $agencyGroup = AgencyType::findOrFail($id);
        $agencyGroup->update([
          'is_deleted'=>1,
        ]);
         
         return response()->json([
             'success' => true,
             'message' => 'Agency type deleted successfully'
         ]);
     } catch (\Exception $e) {
         return response()->json([
             'success' => false,
             'message' => 'Error deleting agency type: ' . $e->getMessage()
         ], 500);
     }
 }
 public function toggleStatus($id, Request $request)
{
    $agencyGroup = AgencyType::find($id);

    if (!$agencyGroup) {
        return response()->json(['message' => 'Agency group not found'], 404);
    }

    // Toggle the status
    $agencyGroup->is_active = $request->status;
    $agencyGroup->save();

    return response()->json(['success' => 'Status updated successfully']);
}
   

    }

