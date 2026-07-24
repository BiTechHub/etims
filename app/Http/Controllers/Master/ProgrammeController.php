<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\AgencyGroup;
use App\Models\Programme;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProgrammeController extends Controller
{
    public function create()
    {
        // Fetch all agency groups to populate the dropdown
        $agencyGroups = AgencyGroup::all();
        
        // Return the view with agency groups
        return view('admin.masters.programme', compact('agencyGroups'));
    }
    public function getData(Request $request)
    {
        $agencies = Programme::select(['id', 'agency_group_id', 'name','target_group','duration','content','objective']);
    
        return DataTables::of($agencies)
            ->addColumn('action', function($agency) {
                return '';
            })
            ->make(true);
    }


    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'agency_group_id' => 'required|exists:agency_groups,id',
            'name' => 'required|string|max:255',
            'target_group' => 'required|string|max:255',
            'duration' => 'required|integer|min:1',
            'content' => 'required|string',
            'objective' => 'required|string',
        ]);

        try {
            // Create a new agency type
            Programme::create([
                'agency_group_id' => $request->agency_group_id,
                'name' => $request->name,
                'target_group' => $request->target_group,
                'duration' => $request->duration,
                'content' => $request->content,
                'objective' => $request->objective,
            ]);

            // Redirect back with success message
            return redirect()->back()->with('success', 'Programme added successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong! Please try again.');
        }
    }


    public function destroy($id)
    {
        $agencyGroup = Programme::find($id);
    
        if (!$agencyGroup) {
            return response()->json(['error' => 'Agency type not found.'], 404);
        }
    
        $agencyGroup->delete();
    
        return response()->json(['success' => 'Agency type deleted successfully.']);
    }

       // Update agency group
       public function update(Request $request)
       {
           // Validate incoming request
           $validated = $request->validate([
            'agency_group_id' => 'required|exists:agency_groups,id',
            'name' => 'required|string|max:255',
            'target_group' => 'required|string|max:255',
            'duration' => 'required|integer|min:1',
            'content' => 'required|string',
            'objective' => 'required|string',
           ]);
       
           // Find the agency group or fail if not found
           $agency = Programme::findOrFail($request->id);
           
           // Update agency group details
           $agency->update($validated);
       
           // Redirect back with success message
           return redirect()->back()->with('updated', 'Programme updated successfully.');
       }
}
