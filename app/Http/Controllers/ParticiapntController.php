<?php

namespace App\Http\Controllers;

use App\Models\Feedbackmenu;
use App\Models\FeedbackResponse;
use App\Models\Participant;
use App\Models\Subtopic;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon as SupportCarbon;
use Illuminate\Support\Facades\DB;

class ParticiapntController extends Controller
{


    public function view(){
        return view('participant_login');
    }


    public function login_session(){

        return view('part_login_session.blade.php');
    }

   
   
   
   
   
   
public function submit(Request $request)
{
    $request->validate([
        'phone' => 'required|digits:10',
    ]);

    // Try to find participant with related programme
    $participant = Participant::where('phone', $request->phone)
                    ->with('programme')
                    ->latest()
                    ->first();

    // Check if participant exists before continuing
    if (!$participant) {
        return redirect()->back()->with('error', 'Phone number not found.');
    }

    // Now safe to access $participant->programme
    $subtopics = Subtopic::where('programme_id', $participant->programme->id)
                ->where('date', Carbon::today())
                ->get();

    if ($participant->response_id == 0) {
        return view('Participant.view', compact('participant', 'subtopics'));
    } else {
        return redirect()->back()->with('success', 'Feedback is already given.');
    }
}


public function storeResponse(Request $request)
{
    $request->validate([
        'participant_id' => 'required|exists:nomination_participants,id',
        'programme_id' => 'required|exists:programmes,id',
        'feedback' => 'required|array',
        'feedback.*' => 'required|integer|min:1|max:5',
    ]);

    // Check if any topic_id already has feedback from this participant
    $existing = FeedbackResponse::where('participant_id', $request->participant_id)
        ->whereIn('topic_id', array_keys($request->feedback))
        ->exists();

    if ($existing) {
        return redirect()->route('participant.login')->with('error', 'Feedback already submitted. Please login again.');
    }

    foreach ($request->feedback as $submenuId => $rating) {
        FeedbackResponse::create([
            'participant_id' => $request->participant_id,
            'topic_id' => $submenuId,
            'rating' => $rating,
            'programme_id' => $request->programme_id,
        ]);
    }

    return redirect()->route('feedback.thankyou')->with('success', 'Thank you for your feedback.');
}


public function feedbackthanku(){
    return view('feedbackthankyou');
}

    

}
