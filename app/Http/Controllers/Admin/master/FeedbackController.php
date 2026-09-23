<?php



namespace App\Http\Controllers\admin\master;



use App\Http\Controllers\Controller;

use App\Models\Feedbackmenu;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Models\{ FeedbackQusetion, FeedbackQusetionType, FeedbackMcqQuestionOption,ParticipantFeedbackResponse ,ProgrammeManagement ,Subtopic};
use Illuminate\Support\Facades\Session;

class FeedbackController extends Controller

{

public function index(){

$types = FeedbackQusetionType::all();

  
  return view('admin.masters.feedback.index',compact('types'));
}


public function addFeedback(Request $request)
{
   $validated = $request->validate([
    'feedback_type_id' => 'required|integer',

    'questions' => 'required|array',

    'questions.*.question' => 'required|string|max:255',

    'questions.*.answer_type' => 'required|in:mcq,rating,text',

    'questions.*.options' => 'nullable|array',

    'questions.*.options.*.option_1' => 'nullable|string|max:255',
    'questions.*.options.*.option_2' => 'nullable|string|max:255',
    'questions.*.options.*.option_3' => 'nullable|string|max:255',
    'questions.*.options.*.option_4' => 'nullable|string|max:255',
    'questions.*.options.*.correct_answer' => 'nullable|string|max:255',
]);
    //dd($request->all());

    DB::transaction(function () use ($validated) {

       foreach ($validated['questions'] as $questionData) {

    $feedbackQuestion = FeedbackQusetion::create([
        'feedback_question_type_id' => $validated['feedback_type_id'],
        'question' => $questionData['question'],
        'answer_type' => $questionData['answer_type'],
    ]);

    if (
        $questionData['answer_type'] === 'mcq' &&
        !empty($questionData['options'])
    ) {

        foreach ($questionData['options'] as $option) {

            FeedbackMcqQuestionOption::create([
                'feedback_question_id' => $feedbackQuestion->id,
                'option_1' => $option['option_1'] ?? null,
                'option_2' => $option['option_2'] ?? null,
                'option_3' => $option['option_3'] ?? null,
                'option_4' => $option['option_4'] ?? null,
                'correct_answer' => $option['correct_answer'] ?? null,
            ]);
        }
    }
}
    });

    return response()->json([
        'status' => 'success',
        'message' => 'Feedback questions added successfully.',
    ]);
}


public function getFeedbackData(Request $request){
    $feedbackQuestions = FeedbackQusetion::with('feedbackQuestionType','feedbackQuestionOptions')->get();

    return response()->json([
        'status' => 'success',
        'message' => 'Feedback questions fetched successfully.',
        'data' => $feedbackQuestions,
    ]);

}


public function viewFullFeedbackPage(Request $request)
{
    $types = FeedbackQusetionType::all();
    $feedbackQuestions = FeedbackQusetion::with('feedbackQuestionType','feedbackQuestionOptions')->get();

    
    //dd($feedbackQuestions);
    return view('admin.masters.feedback.view-full-feedback-page',compact('feedbackQuestions'));
}

// public function particpate_feedback(Request $request)
// {
    
//     $feedbackQuestions = FeedbackQusetion::with('feedbackQuestionType','feedbackQuestionOptions')->get();

//  //   dd($feedbackQuestions);

//     //dd(Session::has('participant'));
//   if(Session::has('participant')){
//     $participant = Session::get('participant');

//    // dd($participant);

    
// $programme = ProgrammeManagement::find($participant->programme_id);

// $session_wise_feedback_questions = Subtopic::with(['faculty:id,name','SessionBreaks'])->where('programme_id', $programme->id)

// ->orderBy('date')

// ->get();


// dd('session_wise_feedback_questions',$session_wise_feedback_questions ,'feedbackQuestions', $feedbackQuestions);

//    // $session_wise_feedback_questions = Subtopic::


//     return view('Participant.feedback',compact('feedbackQuestions','participant','session_wise_feedback_questions'));
//   }


//   return back()->with('error','Please login to view feedback');
// }

public function particpate_feedback(Request $request)
{
    if (Session::has('participant')) {
        $participant = Session::get('participant');
        $programme = ProgrammeManagement::find($participant->programme_id);

        $feedbackQuestions = FeedbackQusetion::with('feedbackQuestionType', 'feedbackQuestionOptions')->get();

        // Only non-break sessions, sorted chronologically
        $session_wise_feedback_questions = Subtopic::with(['faculty:id,name', 'SessionBreaks'])
            ->where('programme_id', $programme->id)
            ->where('is_break', 0)
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        // ── Build unified sections array ──
        $sections = [];

        // 1) Session-wise section — RATING ONLY
        if ($session_wise_feedback_questions->isNotEmpty()) {
            $sections[] = [
                'type_name'        => 'Session-wise Feedback',
                'is_session_section' => true,
                'sessions'         => $session_wise_feedback_questions,
                'rating_questions' => collect(),
                'mcq_questions'    => collect(),
                'text_questions'   => collect(),
            ];
        }

        // 2) Regular feedback sections — ALL types (rating + mcq + text)
        $groupedQuestions = $feedbackQuestions->groupBy(
            fn($q) => $q->feedbackQuestionType->type_name
        );

        foreach ($groupedQuestions as $typeName => $questions) {
            $sections[] = [
                'type_name'        => $typeName,
                'is_session_section' => false,
                'sessions'         => collect(),
                'rating_questions' => $questions->where('answer_type', 'rating'),
                'mcq_questions'    => $questions->where('answer_type', 'mcq'),
                'text_questions'   => $questions->where('answer_type', 'text'),
            ];
        }

        // Total = general questions + one rating per session
        $totalQuestions = $feedbackQuestions->count() + $session_wise_feedback_questions->count();


        return view('Participant.feedback', compact('sections', 'participant', 'totalQuestions'));
    }



    return redirect()->route('participant.login')->with('error', 'Please login first.');
}

public function submitFeedback(Request $request)
{





    $request->validate([
        'programme_id' => 'required',
        'participants_id' => 'required',
        'questions' => 'required|array',
        'questions.*' => 'required|string',
        'session_ratings.*' => 'required|string',
        'session_ratings' => 'required|array',
    ]);

try {
    DB::beginTransaction();
    foreach ($request->questions as $questionId => $answer) {

        ParticipantFeedbackResponse::create([
            'participant_id'        => $request->participants_id,
            'program_id'            => $request->programme_id,
            'feedback_question_id'  => $questionId,
            'chosen_answer'         => $answer,
            'question_status'=> 'feedback'
        ]);
    }



    foreach ($request->session_ratings as $session_rating => $session_rating_answer) {

        ParticipantFeedbackResponse::create([
            'participant_id'        => $request->participants_id,
            'program_id'            => $request->programme_id,
            'feedback_question_id'  => $session_rating,
            'chosen_answer'         => $session_rating_answer,
            'question_status'=> 'session'
        ]);
    }

    DB::commit();

    return redirect('thankyou?type=none')->with('success', 'Feedback submitted successfully.');
} catch (\Exception $e) {
    DB::rollBack();
    return back()->with('error', 'Feedback submission failed. Please try again.');
}
 return back()->with('error', 'Feedback submission failed. Please try again.');
    
}






































//=================================== Old Feedback ======================================

    public function master(){

        return view('admin.masters.feedback');

    }





public function store(Request $request)

{

    $validated = $request->validate([

        'menus' => 'required|array',

        'menus.*.name' => 'required|string|max:255',

        'menus.*.submenus' => 'sometimes|array',

        'menus.*.submenus.*.name' => 'required_with:menus.*.submenus|string|max:255',

        'menus.*.submenus.*.response_type' => 'required_with:menus.*.submenus|in:Yes/No,Rating,Text',

    ]);



    DB::transaction(function () use ($validated) {

        foreach ($validated['menus'] as $menuData) {

            $menu = Feedbackmenu::create([

                'name' => $menuData['name']

            ]);



            foreach ($menuData['submenus'] ?? [] as $submenuData) {

                $menu->submenus()->create([

                    'name' => $submenuData['name'],

                    'response_type' => $submenuData['response_type'],

                ]);

            }

        }

    });



    return back()->with('success', 'Menus and submenus saved successfully.');

}



}

