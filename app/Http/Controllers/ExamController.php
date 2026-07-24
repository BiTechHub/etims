<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\ExamPaper;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\ProgrammeManagement;
use App\Models\Option;
use App\Models\{Question ,Group ,User,Sponsor};
use App\Models\Guest;
use Illuminate\Support\Facades\DB;
use App\Models\Subtopic;
use App\Models\{Participant , QuestionPaperBasicDetail};
use Carbon\Carbon;
use Illuminate\Support\Carbon as SupportCarbon;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;



class ExamController extends Controller
{
    public function addQuestion()
{

    $programmes = ProgrammeManagement::select('id','title')->get();
    $departments =  Group::select(['id','name'])->where('is_active' ,1)->get();

    
    return view('admin.Question.addQuestion',compact(['departments','programmes']));
}


 public function destroy($id)
 {
     try {
         $agencyGroup = Question::findOrFail($id);
         $agencyGroup->delete();
         
         return response()->json([
             'success' => true,
             'message' => 'Question deleted successfully'
         ]);
     } catch (\Exception $e) {
         return response()->json([
             'success' => false,
             'message' => 'Error deleting Question: ' . $e->getMessage()
         ], 500);
     }
 }

public function Question_get_data(Request $request)
{
    $Question = Question::select([
        'department_id',
        'question_title',
        'right_option',
        'id'
    ]);

    return DataTables::of($Question)
        ->make(true);
}


public function questionAdd(Request $request)
    {

        

        
        $request->validate([
            'programme_id'=>'required',
            'department_id' => 'required',
            'question' => 'required',
            'right_option' => 'required',
            'option_A' => 'required',
            'option_B' => 'required',
            'option_C' => 'required',
            'option_D' => 'required'
        ]);
        //echo "<pre>";
        //print_r($request->toArray()); die(" dddddddddd");
        $questions = New Question;
        $questions->programme_id = $request['programme_id'];
        $questions->department_id = $request['department_id'];
        $questions->question_title = $request['question'];
        $questions->right_option = $request['right_option'];
        $questions->option_A = $request['option_A'];
        $questions->option_B = $request['option_B'];
        $questions->option_C = $request['option_C'];
        $questions->option_D = $request['option_D'];
        $questions->save();
        // $lastInsertedId = $questions->id;
        // $i = 1;
        // for ($i = 1; $i <= 4; $i++) {
        //     $item = $request->input('option_' . $i);
        //     //dd($request->input('option_' . $i));
        //     $options = new Option;
        //     $options->programme_id = $request['programme_id'];
        //     $options->question_id = $lastInsertedId;
        //     $options->option_title = $item;
        //     $options->save();
        // }
        return redirect('admin/addQuestion')->with('success',"Question Added Successfully.");
    }



   public function AttempTestexist($id , Request $request)
{

    // Get the participant by token
    $Participant = Participant::where('Token_id', $id)->firstOrFail();

    // Get all exam papers for the participant's programme
    $exams = ExamPaper::where('programme_id', $Participant->programme_id)->get();

    // Extract all question_ids from exams (assuming 'question_id' is a field in ExamPaper)
    $questionIds = $exams->pluck('question_id')->unique()->toArray();

    // Fetch all matching questions
    $questions = Question::whereIn('id', $questionIds)->get();

    // Pass data to view
    return view('attempExistTest', compact('questions', 'Participant'));
}

   public function AttempTest($id ,Request $request)
{


 
    // Get the participant by token
    $Participant = Participant::with('programme')->where('Token_id', $id)->firstOrFail();
    $basic_details = QuestionPaperBasicDetail::where('programme_id', $Participant->programme->id)->first();
    $programmes = $Participant->programme;


    if(!$basic_details || !$programmes)
    {
        return redirect()->back()->with('error', 'Basic details and programme not found.');
    }
  
    // Get all exam papers for the participant's programme
    $exams = ExamPaper::where('programme_id', $Participant->programme_id)->get();

    // Extract all question_ids from exams (assuming 'question_id' is a field in ExamPaper)
    $questionIds = $exams->pluck('question_id')->unique()->toArray();

    // Fetch all matching questions
    $questiones = Question::whereIn('id', $questionIds)->get();
    $type = $request->input('type');

    
  
    // Pass data to view
    return view('AttempTest', compact('questiones', 'Participant' ,'basic_details' ,'type'));
}
    

public function onlinetest(Request $request)
{
    $request->validate([
        'type' => 'required|in:entry,exit,both',
    ]);
    $programmeId = $request->input('programme_id');
    $participantId = $request->input('participants_id');

    // Check if the participant has already attempted the test
    $alreadyAttempted = DB::table('marks')
        ->where('programme_id', $programmeId)
        ->where('participants_id', $participantId)
        ->where('type_of_test', $request->input('type'))
        ->exists();

    if ($alreadyAttempted) {
        return redirect()->back()->with('error', 'You have already attempted this test.');
    }

    $marks = 0;
    $count = count($request->input('cauntt'));

    for ($i = 1; $i <= $count; $i++) {
        $questionId = $request->input('question_' . $i);
        $selectedOption = $request->input('option_' . $i);

        $question = DB::table('questions')->find($questionId);

        if ($question && $question->right_option == $selectedOption) {
            $marks += 1;
        }

        DB::table('answers')->insert([
            'programme_id' => $programmeId,
            'participants_id' => $participantId,
            'options' => $selectedOption,
            'question_id' => $questionId,
            'type_of_test' => $request->input('type'),
        ]);
    }

    DB::table('marks')->insert([
        'marks' => $marks,
        'programme_id' => $programmeId,
        'participants_id' => $participantId,
        'type_of_test' => $request->input('type'),
    ]);

    session(['marks' => $marks]);
    $type = $request->input('type');
    
return redirect('thankyou?type=' . $type);
}


public function feedback(Request $request)
{
    $participant = Session::get('participant');
    $program = ProgrammeManagement::where('id', $participant->programme_id)->first();

    $programmes = $participant->programme;
}



    public function thankyou(Request $request)
    {

        $type = $request->type;


        return view('thankyou',compact('type'));
    }



    public function addquestionview(){

        $departments=Department::select('id','name')->get();
        $programmes=ProgrammeManagement::select('id','title')->get();
        return view('admin.Question.set_paper',compact('departments','programmes'));

    }


    public function getquestion(Request $request)
{
    $departmentId = $request->input('department_id');

    $data = Question::where('department_id', $departmentId)->get(['id', 'question_title', 'option_A','option_B','option_C','option_D']); // adjust fields

    return response()->json($data);
}


public function processQuestions(Request $request)
{

    
    if (is_string($request->selected_questions)) {
        $decoded = json_decode($request->selected_questions, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            $request->merge(['selected_questions' => $decoded]);
        }
    }

    $validated = $request->validate([
        'department_id' => 'required|exists:departments,id',
        'programmes_id' => 'required|exists:programmes,id',
        'selected_questions' => 'required|array',
        'selected_questions.*' => 'exists:questions,id',
    ]);

    foreach ($validated['selected_questions'] as $question_id) {
        // Check if the combination already exists
        $exists = ExamPaper::where('programme_id', $validated['programmes_id'])
            ->where('question_id', $question_id)
            ->exists();

        if (!$exists) {
            ExamPaper::create([
                'department_id' => $validated['department_id'],
                'programme_id' => $validated['programmes_id'],
                'question_id' => $question_id,
            ]);
        }     
    }

    return redirect()->back()->with('success', 'Questions assigned successfully!');
}




public function view_selected_question()
{
    $financialYears = ProgrammeManagement::pluck('financial_year')->unique();
    return view('admin.Question.view_selected', compact('financialYears'));
}



public function getselectedquestion(Request $request)
{
    $exams = ExamPaper::where('programme_id', $request->programme_id)->get();

    // Collect all question_ids from the exam papers
    $questionIds = $exams->pluck('question_id');

    // Fetch all questions at once
    $questions = Question::whereIn('id', $questionIds)->get()->keyBy('id');

    // Map over exams and attach the question title and exam ID
    $data = $exams->map(function ($exam) use ($questions) {
        return [
            'id' => $exam->id, // Include the ExamPaper ID for delete action
            'question' => $questions[$exam->question_id]->question_title ?? 'N/A',
        ];
    });

    return response()->json($data);
}

 
//function for the deleted question
public function destroy_selected_question($id)
{
    try {
        $question = ExamPaper::findOrFail($id);
        $question->delete();

        return response()->json(['success' => true, 'message' => 'Question deleted successfully.']);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Error deleting question.']);
    }
}

public function getProgrammesByDepartment(Request $request)
{
    $departmentId = $request->input('department_id');

    // Get all questions for the department
    $questions = Question::where('department_id', $departmentId)->get();

    // Get unique programme_ids from those questions
    $programmeIds = $questions->pluck('programme_id')->unique();

    // Fetch programmes matching those IDs
    $programmes = ProgrammeManagement::whereIn('id', $programmeIds)->get();

    return response()->json($programmes);
}



	public function exam_login($proId , Request $request)
	{
        $request->validate([
            'type' => 'required |in:entry,exit,both',
        ]);
        
        $type = $request->type;
	    $programmes = ProgrammeManagement::where('id', $proId)->first();
	
	    $participant = Participant::where('programme_id', $programmes->id)
	                    ->latest()
                        ->where('status','paid')
                        ->select('name','id')
	                    ->get();
	
	    return view('exam_login', compact('programmes', 'proId', 'participant','type'));
	}


	public function exam_check(Request $request)
{
   
    $request->validate([
        'phone' => 'required|digits:10',
        'name' => 'required',
        'programme_id' => 'required',
        'type' => 'required |in:entry,exit,both',
    ]);

    // Try to find participant with related programme
    $participant = Participant::where('phone', $request->phone)

                    ->with('programme')
                    ->latest()
                    ->where('programme_id', $request->programme_id)
                    ->find($request->name);

    // Check if participant exists before continuing
    if (!$participant) {
        return redirect()->back()->with('error', 'Phone number not found.');
    }

    // Now safe to access $participant->programme
    $subtopics = Subtopic::where('programme_id', $participant->programme->id)
                ->where('date', Carbon::today())
                ->get();
    $type = $request->type;
    $programmes = ProgrammeManagement::where('id', $request->programme_id)->first();

	//dd($participant);
    if ($participant->response_id == 0) {
        Session::put('participant', $participant);
        return view('participant_info', compact('participant', 'subtopics' ,'type' ,'programmes'));
    } else {
        return redirect()->back()->with('success', 'Feedback is already given.');
    }
}


public function PreviewQuestionPaper(Request $request)
{
   $currentYear = date('Y');

$years = [];

for ($i = 2020; $i <= $currentYear; $i++) {
    $years[] = $i . '-' . ($i + 1);
}

return view('admin.Exam.preview_question_paper', compact('years'));
    
}


public function getQuestionPaperByProgramme($programmeId)
{
    $programme = ProgrammeManagement::where('id', $programmeId)->first();
    if (!$programme)
        {
            return response()->json(['message'=>'program not found ','status'=>false],500);
        }

        $questions = Question::where('programme_id', $programmeId)->get();

        $basic_details = QuestionPaperBasicDetail::where('programme_id', $programmeId)->first();

        return response()->json(['questions'=>$questions,'status'=>true ,'basic_details'=>$basic_details],200);

}


public function getProgrammesByYears(Request $request)
{
    $years = $request->year;



    $programmes = ProgrammeManagement::where('financial_year', $years)->select('id', 'title','unique_id','financial_year')->get();
     

    return response()->json(['programmes'=>$programmes,'status'=>true],200);

}


public function updateQuestionPaperBasicDetails(Request $request)
{
    $validated = Validator::make($request->all(), [

    'programme_id' => 'required|exists:programmes,id',

    'total_marks' => 'required|numeric',

    'passing_marks' => 'required|numeric',

    'duration' => 'required|numeric',

    'exit_exam_date' => 'nullable|date',

    'entry_exam_date' => 'nullable|date',

    'entry_exam_time' => 'nullable|date_format:H:i',

    'exit_exam_time' => 'nullable|date_format:H:i',

    'exam_type' => 'required|in:entry,exit,both',

]);

    if ($validated->fails()) {
        return response()->json(['message'=>$validated->errors(),'status'=>false],500);
    }

    $basic_details = QuestionPaperBasicDetail::where('programme_id', $request->programme_id)->first();

    if (!$basic_details) {
    QuestionPaperBasicDetail::create([
        'programme_id' => $request->programme_id,
        'total_marks' => $request->total_marks,
        'passing_marks' => $request->passing_marks,
        'duration' => $request->duration,
        'exit_exam_date' => $request->exit_exam_date,
        'entry_exam_date' => $request->entry_exam_date,
        'entry_exam_time' => $request->entry_exam_time,
        'exit_exam_time' => $request->exit_exam_time,
        'exam_type' => $request->exam_type,
    ]);

    }else{
    $basic_details->update([
        'total_marks' => $request->total_marks,
        'passing_marks' => $request->passing_marks,
        'duration' => $request->duration,
        'exit_exam_date' => $request->exit_exam_date,
        'entry_exam_date' => $request->entry_exam_date,
        'entry_exam_time' => $request->entry_exam_time,
        'exit_exam_time' => $request->exit_exam_time,
        'exam_type' => $request->exam_type,
    ]);
    }
    return response()->json(['message'=>'updated successfully','status'=>true],200);

}

public function ProgrammeExamQrCodes(Request $request)
{
 

$distinctYears = ProgrammeManagement::select('financial_year')

                            ->distinct()

                            ->orderBy('financial_year','desc')

                            ->pluck('financial_year');

    

        // 2. All programmes

        $programmes = ProgrammeManagement::select(['id','financial_year','title'])

                        ->get();

        

        $groups=Group::select(['id','name'])->where('is_active' ,1)->get();



        $users=User::select(['id','name'])->get();

       $locations = ProgrammeManagement::select('location')

    ->whereNotNull('location')

    ->distinct()

    ->orderBy('location')

    ->pluck('location');



        $sponsors=Sponsor::select(['id','name'])->get();

        return view('admin.Exam.program-qr-codes',compact(['groups','sponsors','programmes','distinctYears','locations','users']));

}




// public function generateQrCode(Request $request)
// {

//     $request->validate([

//         'type' => 'required',

//         'program' => 'required',

//     ]);


//     // PROGRAMME

//     $program = ProgrammeManagement::find($request->program);

//     if (!$program) {

//         return back()->with('error', 'Programme not found');

//     }



//     // BASIC DETAILS

//     $basic_details = QuestionPaperBasicDetail::where(
//         'programme_id',
//         $request->program
//     )->first();


//     if (!$basic_details) {

//         return back()->with('error', 'Basic details not found');

//     }



//     // CHECK EXAM TYPE

//     if (
//         $request->type == $basic_details->exam_type
//         ||
//         $basic_details->exam_type == 'both'
//     ) {



//         // LOGIN URL

//         $url = route('exam_login', [

//             'id' => $program->id,

//             'type' => $request->type

//         ]);



//         // QR DATA

//         $qrData = $url;
//        // dd($qrData);


//         // GENERATE QR PNG

//         $qr = QrCode::format('png')
//             ->size(400)
//             ->margin(2)
//             ->generate($qrData);



//         // DOWNLOAD FILE NAME

//         $fileName = strtolower(
//             $program->title . '-' . $request->type . '-qr.png'
//         );

       


//         // RETURN DOWNLOAD

//         return Response::make($qr, 200, [

//             'Content-Type' => 'image/png',

//             'Content-Disposition' => 'attachment; filename="'.$fileName.'"'

//         ]);

//     }



//     return back()->with(
//         'error',
//         'This exam type is not allowed for this programme'
//     );

// }



public function generateQrCode(Request $request)
{
    $request->validate([
        'type' => 'required',
        'program' => 'required',
    ]);

    // PROGRAMME
    $program = ProgrammeManagement::find($request->program);

    if (!$program) {
        return back()->with('error', 'Programme not found');
    }

    // BASIC DETAILS
    $basic_details = QuestionPaperBasicDetail::where(
        'programme_id',
        $request->program
    )->first();

    if (!$basic_details) {
        return back()->with('error', 'Basic details not found');
    }

    // CHECK EXAM TYPE
    if (
        $request->type == $basic_details->exam_type
        || $basic_details->exam_type == 'both'
    ) {

        // LOGIN URL
        $url = route('exam_login', [
            'id' => $program->id,
            'type' => $request->type
        ]);

        // TEXT BELOW QR
        $bottomText = $program->title . ' - ' . strtoupper($request->type);

        // GENERATE QR
        $qr = QrCode::format('png')
            ->size(400)
            ->margin(2)
            ->errorCorrection('H')
            ->generate($url);

        // CREATE IMAGE FROM QR
        $image = imagecreatefromstring($qr);

        $width = imagesx($image);
        $height = imagesy($image);

        // Extra space for text
        $newHeight = $height + 40;

        $newImage = imagecreatetruecolor($width, $newHeight);

        // White background
        $white = imagecolorallocate($newImage, 255, 255, 255);
        $black = imagecolorallocate($newImage, 0, 0, 0);

        imagefill($newImage, 0, 0, $white);

        // Copy QR
        imagecopy($newImage, $image, 0, 0, 0, 0, $width, $height);

        // FONT SIZE
        $fontSize = 3;

        // Center text
        $textWidth = imagefontwidth($fontSize) * strlen($bottomText);
        $x = ($width - $textWidth) / 2;
        $y = $height + 10;

        // Add text
        imagestring($newImage, $fontSize, $x, $y, $bottomText, $black);

        // Output image
        ob_start();
        imagepng($newImage);
        $finalQr = ob_get_clean();

        imagedestroy($image);
        imagedestroy($newImage);

        // FILE NAME
        $fileName = strtolower(
            $program->title . '-' . $request->type . '-qr.png'
        );

        return Response::make($finalQr, 200, [
            'Content-Type' => 'image/png',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"'
        ]);
    }

    return back()->with(
        'error',
        'This exam type is not allowed for this programme'
    );
}



}