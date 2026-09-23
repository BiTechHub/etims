<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Faculity;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class FaculityController extends Controller
{
    public function index()
    {
        $department = Department::all();
        return view('admin.masters.faculity', compact('department'));
    }
    
    public function getData(Request $request)
    {
        $agencyTypes = Faculity::with('department:id,name') // Eager load the relation
            ->select(['id', 'department_id', 'name', 'is_active']); // Only select needed fields
    
        return DataTables::of($agencyTypes)
            ->addColumn('department_name', function ($agencyType) {
                return $agencyType->department->name ?? 'N/A'; // Safely get related name
            })
            ->rawColumns(['department_name']) // Optional if you're returning HTML
            ->make(true);
    }
    
    public function store(Request $request)
    {
        
        $validated = $request->validate([
             'department_id'=>'required',
            'name' => 'required|string',
            'is_active' => 'nullable|boolean'
        ]);
    
        $validated['is_active'] = $request->has('is_active') ? $request->is_active : 1;
    
        Faculity::create($validated);
    
        return redirect()->back()->with('success', 'Faculity created successfully!');
    }
     // Update agency group
     public function update(Request $request)
     {
         // Validate incoming request
         $validated = $request->validate([
             
             'department_id' => 'required',
             'name' => 'required|max:100',
         ]); 
     
         // Find the agency group or fail if not found
         $agency = Faculity::findOrFail($request->id);
         
         // Update agency group details
         $agency->update($validated);
          
         session()->save();
     return redirect()->back()->with('success', 'Faculity updated successfully!');
     
         // Redirect back with success message
        
     
     }
     public function destroy($id)
     {
         try {
             $agencyGroup = Faculity::findOrFail($id);
             $agencyGroup->delete();
             
             return response()->json([
                 'success' => true,
                 'message' => 'Faculity deleted successfully'
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
        $agencyGroup = Faculity::find($id);
    
        if (!$agencyGroup) {
            return response()->json(['message' => 'faculity not found'], 404);
        }
    
        // Toggle the status
        $agencyGroup->is_active = $request->status;
        $agencyGroup->save();
    
        return response()->json(['message' => 'Status updated successfully']);
    }
}
