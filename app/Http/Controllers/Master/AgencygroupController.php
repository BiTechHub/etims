<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\AgencyGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Facades\DataTables as FacadesDataTables;

class AgencygroupController extends Controller
{
    public function index()
    {
        return view('admin.masters.agency group.agency_group');
    }

    // Fetch agency groups data for DataTable
    public function getData(Request $request)
    {
        $agencies = AgencyGroup::select(['id', 'code', 'name', 'is_active']);
        
        $response = DataTables::of($agencies)
            ->addColumn('action', function($agency) {
                return '';
            })
            ->make(true);
        
        Log::info('DataTables Response:', [$response->getContent()]);
        return $response;
    }

    // Store new agency group (API version)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:agency_groups|numeric',
            'name' => 'required|string',
            'is_active' => 'nullable|boolean'
        ]);
    
        $validated['is_active'] = $request->has('is_active') ? $request->is_active : 1;
    
        AgencyGroup::create($validated);
    
        return redirect()->back()->with('success', 'Agency group created successfully!');
    }
    

    // Edit agency group
    public function editForm($id) // Use "editForm" with a capital "F" for better convention
    {
        $agency = AgencyGroup::findOrFail($id);
        return view('admin.masters.agency_group.edit', compact('agency')); // Correct view path
    }
    

    // Update agency group
    public function update(Request $request)
{
    // Validate incoming request
    $validated = $request->validate([
        
        'code' => 'required|max:50|unique:agency_groups,code,'.$request->id,
        'name' => 'required|max:100',
    ]); 

    // Find the agency group or fail if not found
    $agency = AgencyGroup::findOrFail($request->id);
    
    // Update agency group details
    $agency->update($validated);
     
    session()->save();
return redirect()->back()->with('success', 'Agency Group updated successfully!');

    // Redirect back with success message
   

}

    // Delete agency group
    public function destroy($id)
    {
        try {
            $agencyGroup = AgencyGroup::findOrFail($id);
            $agencyGroup->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Agency group deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting agency group: ' . $e->getMessage()
            ], 500);
        }
    }
    

    
    
    // app/Http/Controllers/AgencyGroupController.php

public function toggleStatus($id, Request $request)
{
    $agencyGroup = AgencyGroup::find($id);

    if (!$agencyGroup) {
        return response()->json(['message' => 'Agency group not found'], 404);
    }

    // Toggle the status
    $agencyGroup->is_active = $request->status;
    $agencyGroup->save();

    return response()->json(['message' => 'Status updated successfully']);
}

}