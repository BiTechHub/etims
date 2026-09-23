<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Sponsor;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SponsorController extends Controller
{
    public function index()
    {
        return view('admin.masters.sponsor');
    }

    // Fetch agency groups data for DataTable
    public function getData(Request $request)
    {
        $agencies = Sponsor::select(['id',  'name', 'is_active','hindi_name'])->where('is_deleted',0);

        return DataTables::of($agencies)
            ->addColumn('action', function ($agency) {
                return '';
            })
            ->make(true);
    }

    // Store new agency group (API version)
    public function store(Request $request)
    {
    
        $validated = $request->validate([
            'hindi_name'=>'required|max:100',
            'name' => 'required|max:100',
            'is_active' => 'nullable|boolean'
        ]);

        $validated['is_active'] = $request->has('is_active') ? $request->is_active : 1;

        Sponsor::create($validated);

        return redirect()->back()->with('success', 'Sponsor created successfully!');
    }


    // Edit agency group


    // Update agency group
    public function update(Request $request)
    {
        // Validate incoming request
        $validated = $request->validate([
              'hindi_name' => 'required|max:100',

            'name' => 'required|max:100',
        ]);

        // Find the agency group or fail if not found
        $agency = Sponsor::findOrFail($request->id);

        // Update agency group details
        $agency->update($validated);

        // Redirect back with success message
        session()->save();
        return redirect()->back()->with('success', ' updated successfully!');
    }

    // Delete agency group
    public function destroy($id)
    {
       
        $agencyGroup = Sponsor::find($id);
        $agencyGroup->update([
            'is_deleted'=>1,
        ]);

        if (!$agencyGroup) {
            return response()->json(['error' => 'sponsor not found.'], 404);
        }

        // $agencyGroup->delete();

        return response()->json(['success' => 'sponsor deleted successfully.']);
    }


    // app/Http/Controllers/AgencyGroupController.php

    public function toggleStatus($id, Request $request)
    {
        $agencyGroup = Sponsor::find($id);

        if (!$agencyGroup) {
            return response()->json(['message' => 'Agency group not found'], 404);
        }

        // Toggle the status
        $agencyGroup->is_active = $request->status;
        $agencyGroup->save();

        return response()->json(['message' => 'Status updated successfully']);
    }
}
