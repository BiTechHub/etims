<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session as FacadesSession;
use Yajra\DataTables\Facades\DataTables;

class SessionController extends Controller
{
   public function index()
   {
      return view('admin.masters.session');
   }

   public function getData(Request $request)
    {
        $agencies = Session::select(['id', 'title', 'start_time', 'end_time']);

        return DataTables::of($agencies)
            ->addColumn('action', function($agency) {
                return '';
            })
            ->make(true);
    }

    public function store(Request $request)
    {
        // Validate input data
        $request->validate([
            'title' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);
    
        try {
            // Create new session
            $session = Session::create([
                'title' => $request->title,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
            ]);
    
            // Return JSON response for AJAX
            
        return redirect()->back()->with('success', 'Agency group created successfully!');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add session. Please try again!',
            ], 500);
        }
    }
    

 

   public function update(Request $request, $id)
   {
    $validated = $request->validate([
        
        'title'=>'required',
        'start_time'=>'required',
        'end_time'=>'required',
        
    ]); 

    // Find the agency group or fail if not found
    $agency = Session::findOrFail($request->id);
    
    // Update agency group details
    $agency->update($validated);
     
    session()->save();
return redirect()->back()->with('success', 'Session updated successfully!');

    // Redirect back with success message
}  

   public function destroy($id)
   {
       Session::destroy($id);
       return response()->json(['message' => 'Session deleted']);
   }
}
