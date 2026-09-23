<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class GropController extends Controller
{  
     public function index()
    {
        return view('admin.masters.group');
    }

    // Fetch agency groups data for DataTable
    public function getData(Request $request)
    {
        $agencies = Group::select(['id',  'name','code', 'is_active'])->where('is_deleted',0);

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
            'code' =>'required',
            'is_active' => 'nullable|boolean'
        ]);
    
        $validated['is_active'] = $request->has('is_active') ? $request->is_active : 1;
    
       Group::create($validated);
    
        return redirect()->back()->with('success', ' group created successfully!');
    }
    

    // Edit agency group


    // Update agency group
    public function update(Request $request)
{
    // Validate incoming request
    $validated = $request->validate([
        
        'code' => 'required',
        'name' => 'required|max:100',
    ]); 

    // Find the agency group or fail if not found
    $agency = Group::findOrFail($request->id);
    
    // Update agency group details
    $agency->update($validated);

    // Redirect back with success message
    return redirect()->back()->with('updated', ' Group updated successfully.');
}

    // Delete agency group
    public function destroy($id)
    {
        $agencyGroup = Group::find($id);
        $agencyGroup->update([
            'is_deleted'=>1,
        ]);
        if (!$agencyGroup) {
            return response()->json(['error' => 'Agency Group not found.'], 404);
        }
    
        // $agencyGroup->delete();
    
        return response()->json(['success' => 'Agency Group deleted successfully.']);
    }
    
    
    // app/Http/Controllers/AgencyGroupController.php

public function toggleStatus($id, Request $request)
{
    $agencyGroup = Group::find($id);

    if (!$agencyGroup) {
        return response()->json(['message' => 'Agency group not found'], 404);
    }

    // Toggle the status
    $agencyGroup->is_active = $request->status;
    $agencyGroup->save();

    return response()->json(['message' => 'Status updated successfully']);
}
}
