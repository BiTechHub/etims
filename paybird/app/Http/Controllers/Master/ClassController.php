<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ClassController extends Controller
{
    public function index()
    {
        return view('admin.masters.room');
    }

    // Fetch agency groups data for DataTable
    public function getData(Request $request)
    {
        $agencies = Classes::select(['id', 'name', 'is_active']);

        return DataTables::of($agencies)
            ->addColumn('action', function($agency) {
                return '';
            })
            ->make(true);
    }

    // Store new agency group (API version)
    public function store(Request $request)
    {
        $validated = $request->validate([
          
            'name' => 'required|max:100',
            'is_active' => 'nullable|boolean'
        ]);
    
        $validated['is_active'] = $request->has('is_active') ? $request->is_active : 1;
    
        Classes::create($validated);
    
        return redirect()->back()->with('success', 'Classes created successfully!');
    }
    

    // Edit agency group
  
    

    // Update agency group
    public function update(Request $request)
{
    // Validate incoming request
    $validated = $request->validate([
        
        
        'name' => 'required|max:100',
    ]); 

    // Find the agency group or fail if not found
    $agency = Classes::findOrFail($request->id);
    
    // Update agency group details
    $agency->update($validated);

    // Redirect back with success message
    return redirect()->back()->with('updated', 'Class updated successfully.');
}

    // Delete agency group
    public function destroy($id)
    {
        $agencyGroup = Classes::find($id);
    
        if (!$agencyGroup) {
            return response()->json(['error' => 'Class not found.'], 404);
        }
    
        $agencyGroup->delete();
    
        return response()->json(['success' => 'Class deleted successfully.']);
    }
    
    
    // app/Http/Controllers/AgencyGroupController.php

public function toggleStatus($id, Request $request)
{
    $agencyGroup = Classes::find($id);

    if (!$agencyGroup) {
        return response()->json(['message' => 'class not found'], 404);
    }

    // Toggle the status
    $agencyGroup->is_active = $request->status;
    $agencyGroup->save();

    return response()->json(['message' => 'Status updated successfully']);
}

}
