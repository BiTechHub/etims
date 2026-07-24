<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProgrammeManagement;

class TrainingController extends Controller
{
    public function index()
    {
        $trainings = ProgrammeManagement::latest()->get();
        return view('admin.training.index', compact('trainings'));
    }

    public function create()
    {
        return view('admin.training.create');
    }

    public function store(Request $request)
    {
        //echo "<pre>"; print_r($request->all()); exit;
        $request->validate([
            'program_title' => 'required',
            'from_date' => 'required|date',
            'to_date' => 'required|date',
            'group' => 'required',
            'location' => 'required',
            'announcement_status' => 'required',
            'area' => 'required',
            'announcement_letter' => 'nullable|mimes:pdf|max:5120',
        ]);

        $fileName = null;

        if ($request->hasFile('announcement_letter')) {
            $fileName = time().'_'.$request->announcement_letter->getClientOriginalName();
            $request->announcement_letter->move(
                public_path('frontend/TrainingLetter'),
                $fileName
            );
        }

        ProgrammeManagement::create([
            'program_title' => $request->program_title,
            'from_date' => $request->from_date,
            'to_date' => $request->to_date,
            'group' => $request->group,
            'location' => $request->location,
            'announcement_status' => $request->announcement_status,
            'area' => $request->area,
            'announcement_letter' => $fileName,
        ]);

        return redirect()->back()
            ->with('success','Training created successfully');
    }
}

