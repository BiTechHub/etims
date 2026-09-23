<?php

namespace App\Http\Controllers\admin;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\BedAllocation;
use App\Models\Block;
use App\Models\Feedbackmenu;
use App\Models\FeedbackResponse;
use App\Models\Feedbacksubmenu;
use App\Models\Mark;
use App\Models\Nomination;
use App\Models\Participant;
use App\Models\ProgrammeManagement;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ReportController extends Controller    
{
     public function participant_report(Request $request)
    {

     
        // 1. Distinct calendar years
        $distinctYears = ProgrammeManagement::select('financial_year')
                            ->distinct()
                            ->orderBy('financial_year','desc')
                            ->pluck('financial_year');
    
        // 2. All programmes  
        $programmes = ProgrammeManagement::select(['id','financial_year','title'])
                        ->get();
    
        // 3. Base query for nominations
        $query = Nomination::with(['agencyType','agency','participants']);
    
        // 4. Apply filters
        if ($request->filled('cal_year')) {
            // join through programme if needed, assuming nominations track programme_id
            $query->whereHas('programme', function($q) use ($request) {
                $q->where('financial_year', $request->cal_year);
            });
        }
        if ($request->filled('programme')) {
            $query->where('programme_id', $request->programme);
        }
    
        // 5. Fetch results
        $nominations = $query
            ->orderBy('nomination_date','desc')
            ->get();
    
        // 6. Return view
        return view('admin.reports.participant', [
            'distinctYears'      => $distinctYears,
            'programmes'         => $programmes,
            'nominations'        => $nominations,
            'selectedYear'       => $request->cal_year,
            'selectedProgramme'  => $request->programme,
        ]);
    }

//code for the download
public function download($programmeId)
{
    // Fetch participants for the given programme
    $participants = Participant::with('nomination.agency')
        ->whereHas('nomination', function ($query) use ($programmeId) {
            $query->where('programme_id', $programmeId);
        })
        ->get();

    // Prepare CSV content
    $headers = ['Name', 'Designation', 'Agency', 'Phone', 'City', 'State'];

    $callback = function() use ($participants, $headers) {
        $file = fopen('php://output', 'w');
        fputcsv($file, $headers);

        foreach ($participants as $p) {
            fputcsv($file, [
                $p->name,
                $p->designation,
                optional($p->nomination->agency)->name,
                $p->phone,
                $p->city,
                $p->state,
            ]);
        }
        fclose($file);
    };

    $filename = 'participants_programme_' . $programmeId . '.csv';

    // Return streamed response as CSV
    return Response::stream($callback, 200, [
        "Content-Type" => "text/csv",
        "Content-Disposition" => "attachment; filename={$filename}",
    ]);
}

//function for the rating







public function getFeedbackByProgramme(Request $request)
{
    $request->validate([
        'programme_id' => 'required|exists:programmes,id',
    ]);

    $programmeId = $request->programme_id;

    // Get submenus that have feedback responses for this programme
    $submenus = FeedbackSubmenu::whereHas('feedbackResponses', function ($query) use ($programmeId) {
        $query->where('programme_id', $programmeId);
    })->get();

    $results = [];
    $totalRatings = 0;
    $totalResponses = 0;
    $fiveStarCount = 0;

    foreach ($submenus as $submenu) {
        $stats = FeedbackResponse::where('programme_id', $programmeId)
            ->where('submenu_id', $submenu->id)
            ->selectRaw('
                COUNT(*) as total_responses,
                SUM(rating) as total_ratings,
                AVG(rating) as average_rating,
                SUM(CASE WHEN rating = 1 THEN 1 ELSE 0 END) as rating_1,
                SUM(CASE WHEN rating = 2 THEN 1 ELSE 0 END) as rating_2,
                SUM(CASE WHEN rating = 3 THEN 1 ELSE 0 END) as rating_3,
                SUM(CASE WHEN rating = 4 THEN 1 ELSE 0 END) as rating_4,
                SUM(CASE WHEN rating = 5 THEN 1 ELSE 0 END) as rating_5
            ')
            ->first();

        if (!$stats || $stats->total_responses == 0) {
            continue;
        }

        // If you want to get menu_name without relation, use this:
        $menuName = Feedbackmenu::where('id', $submenu->menu_id)->value('name');

        $results[] = [
            'submenu_id' => $submenu->id,
            'submenu_name' => $submenu->name,
            'menu_name' => $menuName ?? 'N/A',  
            'rating_distribution' => [
                1 => (int)($stats->rating_1 ?? 0),
                2 => (int)($stats->rating_2 ?? 0),
                3 => (int)($stats->rating_3 ?? 0),
                4 => (int)($stats->rating_4 ?? 0),
                5 => (int)($stats->rating_5 ?? 0),
            ],
            'average_rating' => round($stats->average_rating ?? 0, 2),
            'total_responses' => (int)($stats->total_responses ?? 0),
        ];

        $totalRatings += $stats->total_ratings ?? 0;
        $totalResponses += $stats->total_responses ?? 0;
        $fiveStarCount += $stats->rating_5 ?? 0;
    }

    $overallAverage = $totalResponses > 0 ? round($totalRatings / $totalResponses, 2) : 0;

    $participantCount = FeedbackResponse::where('programme_id', $programmeId)
        ->distinct('participant_id')
        ->count('participant_id');

    // Optional Excel download
    if ($request->has('download') && $request->download == 'excel') {
        return Excel::download(new FeedbackSummaryExport($results), 'Feedback_Summary.xlsx');
    }

    return response()->json([
        'data' => $results,
        'summary' => [
            'overall_average' => $overallAverage,
            'five_star_count' => $fiveStarCount,
            'total_participants' => $participantCount,
            'total_responses' => $totalResponses,
        ],
    ]);
}






    //view for the rating

    public function rating_report(Request $request)
    {

     
        // 1. Distinct calendar years
        $distinctYears = ProgrammeManagement::select('financial_year')
                            ->distinct()
                            ->orderBy('financial_year','desc')
                            ->pluck('financial_year');
    
        // 2. All programmes
        $programmes = ProgrammeManagement::select(['id','financial_year','title'])
                        ->get();
    
        // 3. Base query for nominations
        $query = Nomination::with(['agencyType','agency','participants']);
    
        // 4. Apply filters
        if ($request->filled('cal_year')) {
            // join through programme if needed, assuming nominations track programme_id
            $query->whereHas('programme', function($q) use ($request) {
                $q->where('financial_year', $request->cal_year);
            });
        }
        if ($request->filled('programme')) {
            $query->where('programme_id', $request->programme);
        }
    
        // 5. Fetch results
        $nominations = $query
            ->orderBy('nomination_date','desc')
            ->get();
    
        // 6. Return view
        return view('admin.reports.rating', [
            'distinctYears'      => $distinctYears,
            'programmes'         => $programmes,
            'nominations'        => $nominations,
            'selectedYear'       => $request->cal_year,
            'selectedProgramme'  => $request->programme,
        ]);
    }


public function downloadFeedback($programmeId)
{
    $programme = ProgrammeManagement::findOrFail($programmeId);
    
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="feedback_report_' . $programme->code . '.csv"',
    ];

    $callback = function() use ($programmeId) {
        $file = fopen('php://output', 'w');
        fwrite($file, "\xEF\xBB\xBF");
        
        // Headers
        fputcsv($file, [
            'Menu',
            'Submenu', 
            'Average Rating',
            'Total Responses',
            '5 Stars',
            '4 Stars',
            '3 Stars',
            '2 Stars', 
            '1 Star',
            '5 Star Percentage'
        ]);

        // Get feedback data
        $feedbackData = FeedbackResponse::with(['submenu.feedbackMenu'])
            ->where('programme_id', $programmeId)
            ->selectRaw('submenu_id, 
                COUNT(*) as total_responses,
                AVG(rating) as average_rating,
                SUM(CASE WHEN rating = 5 THEN 1 ELSE 0 END) as rating_5,
                SUM(CASE WHEN rating = 4 THEN 1 ELSE 0 END) as rating_4,
                SUM(CASE WHEN rating = 3 THEN 1 ELSE 0 END) as rating_3,
                SUM(CASE WHEN rating = 2 THEN 1 ELSE 0 END) as rating_2,
                SUM(CASE WHEN rating = 1 THEN 1 ELSE 0 END) as rating_1')
            ->groupBy('submenu_id')
            ->cursor(); // Uses cursor for memory efficiency

        foreach ($feedbackData as $item) {
            $total = $item->total_responses;
            $percent5 = $total > 0 ? round(($item->rating_5 / $total) * 100) : 0;
             $menuId = $item->submenu->menu_id ?? null;
            fputcsv($file, [
               
$menuName = $menuId ? Feedbackmenu::findOrFail($menuId)->name : 'N/A',
                $item->submenu->name ?? 'N/A',
                round($item->average_rating, 1),
                $total,
                $item->rating_5,
                $item->rating_4,
                $item->rating_3,
                $item->rating_2,
                $item->rating_1,
                $percent5 . '%'
            ]);
        }
        
        fclose($file);
    };

    return Response::stream($callback, 200, $headers);
}

public function marksReportView()
{
    $financialYears = ProgrammeManagement::pluck('financial_year')->unique();
    return view('admin.reports.marks', compact('financialYears'));
}

public function marksReportViewExist()
{
    $financialYears = ProgrammeManagement::pluck('financial_year')->unique();
    return view('admin.reports.exists', compact('financialYears'));
}

public function attendenceView()
{
    $financialYears = ProgrammeManagement::pluck('financial_year')->unique();
    return view('admin.reports.attendence', compact('financialYears'));
}

public function getProgrammesByYear(Request $request)
{
    $programmes = ProgrammeManagement::where('financial_year', $request->year)->get(['id', 'title']);
    return response()->json($programmes);
}




public function getParticipantsAndMarks(Request $request)
{
 $marks = Mark::where('programme_id', $request->programme_id)
             ->where('entry_test', 1)
             ->get();


    $data = $marks->map(function ($mark) {
        $participant = Participant::find($mark->participants_id);
        return [
            'participant_name' => $participant ? $participant->name : 'N/A',
            'marks' => $mark->marks
        ];
    });

    return response()->json($data);
}


public function getParticipantsAndMarksexist(Request $request)
{
 $marks = Mark::where('programme_id', $request->programme_id)
             ->where('exist_test', 1)
             ->get();


    $data = $marks->map(function ($mark) {
        $participant = Participant::find($mark->participants_id);
        return [
            'participant_name' => $participant ? $participant->name : 'N/A',
            'marks' => $mark->marks
        ];
    });

    return response()->json($data);
}   

public function download_entry_excel($programmeId)
{
    // Validate programme exists
    $programme = ProgrammeManagement::findOrFail($programmeId);

    // Get all marks for this programme where entry_test = 1
    $marks = Mark::with('participant') // If you have a participant relationship
                ->where('programme_id', $programmeId)
                ->where('entry_test', 1)
                ->get();

    // Filename for download
    $filename = 'entry_marks_' . str_replace(' ', '_', $programme->title) . '.csv';

    // Set headers for CSV download
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=\"$filename\"",
    ];

    // Callback to output CSV
    $callback = function() use ($marks) {
        $file = fopen('php://output', 'w');

        // CSV Header row
        fputcsv($file, ['Participant Name', 'Mark', 'Remarks']);

        // Data rows
        foreach ($marks as $mark) {
            fputcsv($file, [
                $mark->participant->name ?? 'N/A',
                $mark->marks,
                $mark->remarks,
            ]);
        }

        fclose($file);
    };

    // Return stream download response
    return response()->stream($callback, 200, $headers);
}

public function download_exist_excel($programmeId)
{
    // Validate programme exists
    $programme = ProgrammeManagement::findOrFail($programmeId);

    // Get all marks for this programme where entry_test = 1
    $marks = Mark::with('participant') // If you have a participant relationship
                ->where('programme_id', $programmeId)
                ->where('exist_test', 1)
                ->get();

    // Filename for download
    $filename = 'exist_marks_' . str_replace(' ', '_', $programme->title) . '.csv';

    // Set headers for CSV download
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=\"$filename\"",
    ];

    // Callback to output CSV
    $callback = function() use ($marks) {
        $file = fopen('php://output', 'w');

        // CSV Header row
        fputcsv($file, ['Participant Name', 'Mark', 'Remarks']);

        // Data rows
        foreach ($marks as $mark) {
            fputcsv($file, [
                $mark->participant->name ?? 'N/A',
                $mark->marks,
                $mark->remarks,
            ]);
        }

        fclose($file);
    };

    // Return stream download response
    return response()->stream($callback, 200, $headers);
}




//report for the attendence
public function attendenceReport(Request $request)
{
    $participants = Participant::where('programme_id', $request->programme_id)->get();

    $data = $participants->map(function ($participant) {
        $agency = optional($participant->nomination->agency);

        return [
            'participant_name' => $participant->name ?? 'N/A',
            'participant_state' => $participant->state ?? 'N/A',
            'participant_designation' => $participant->designation ?? 'N/A',
            'participant_phone' => $participant->phone ?? 'N/A',
            'participant_email' => $participant->email ?? 'N/A',
            'agency_address' => $agency->address ?? 'N/A',
        ];
    });

    return response()->json($data);
}

public function download_attendence_excel($programmeId)
{
    // Validate programme
    $programme = ProgrammeManagement::findOrFail($programmeId);

    // Get all participants in the programme with their nomination and agency
    $participants = Participant::with('nomination.agency')
        ->where('programme_id', $programmeId)
        ->get();

    // Filename
    $filename = 'attendence_report_' . str_replace(' ', '_', $programme->title) . '.csv';

    // CSV headers
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=\"$filename\"",
    ];

    // Callback to generate CSV
    $callback = function () use ($participants) {
        $file = fopen('php://output', 'w');

        // Header row
        fputcsv($file, [
            'Participant Name',
            'Email',
            'Phone',
            'State',
            'Designation',
            
            'Agency Address'
        ]);

        // Participant rows
        foreach ($participants as $participant) {
            $agency = optional($participant->nomination)->agency;

            fputcsv($file, [
                $participant->name ?? 'N/A',
                $participant->email ?? 'N/A',
                $participant->phone ?? 'N/A',
                $participant->state ?? 'N/A',
                $participant->designation ?? 'N/A',
                
                $agency->address ?? 'N/A',
            ]);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}





//route related to the feed back
public function marksReportViewfeedback()
{
    $financialYears = ProgrammeManagement::pluck('financial_year')->unique();
    return view('admin.reports.feedback', compact('financialYears'));
}


public function getfeedback(Request $request)
{
    // Eager load participant and submenu relations
    $feedbacks = FeedbackResponse::where('programme_id', $request->programme_id)
        ->with(['participant', 'submenu'])
        ->get();

    $data = $feedbacks->map(function ($feedback) {
        // Get submenu related to feedback
        $submenu = $feedback->submenu;

        // Get menu from submenu if submenu exists
        $menu = null;
        if ($submenu) {
            $menu = Feedbackmenu::find($submenu->menu_id);
        }

        $participant = $feedback->participant;

        return [
            'menu' => $menu ? $menu->name : 'N/A',          // menu name
            'submenu' => $submenu ? $submenu->name : 'N/A', // submenu name
            'participant_name' => $participant ? $participant->name : 'N/A',
            'rating' => $feedback->rating ?? 'N/A',         // rating from FeedbackResponse table
        ];
    });

    return response()->json($data);
}


 
public function getByProgramme(Request $request)
{
    $programmeId = $request->programme_id;

    // Get current and last financial year ranges
    $now = Carbon::now();
    $startOfCurrentFY = $now->month >= 4 ? Carbon::create($now->year, 4, 1) : Carbon::create($now->year - 1, 4, 1);
    $endOfCurrentFY = $startOfCurrentFY->copy()->addYear()->subDay();

    $startOfLastFY = $startOfCurrentFY->copy()->subYear();
    $endOfLastFY = $startOfCurrentFY->copy()->subDay();

    $participants = Participant::where('status', 'confirm') // ✅ Apply 'confirm' status filter here
        ->whereHas('nomination', function ($q) use ($programmeId) {
            $q->where('programme_id', $programmeId);
        })
        ->with([
            'nomination.agencyType:id,name',
            'nomination.agency:id,name',
        ])
        ->get()
        ->map(function ($participant) use ($startOfCurrentFY, $endOfCurrentFY, $startOfLastFY, $endOfLastFY) {
            $participant->current_fy_count = Participant::where('phone', $participant->phone)
                ->whereBetween('created_at', [$startOfCurrentFY, $endOfCurrentFY])
                ->count();

            $participant->last_fy_count = Participant::where('phone', $participant->phone)
                ->whereBetween('created_at', [$startOfLastFY, $endOfLastFY])
                ->count();

            return $participant;
        });

    return response()->json(['data' => $participants]);
}




//controller related to the rooms

public function view_room(){


    $blocks=Block::select('id','name')->get();
    return view('admin.reports.room',compact('blocks'));
}

public function getRoomOccupancyData(Request $request)
{
    // First validate that block_id exists
    if (!$request->has('block_id')) {
        return response()->json(['error' => 'Block ID is required'], 400);
    }

    // Get all rooms for the specified block where is_available is null
   $rooms = Room::with(['type','block'])
    ->where('block_id', $request->block_id)
    ->whereNull('is_available')
    ->get();


    // Transform each room to include its bed allocations
    $rooms->transform(function($room) {
        $room->bed_allocations = BedAllocation::where('room_number', $room->id)->whereNull('is_available')->get();
        return $room;
    });

    return DataTables::of($rooms)
        ->addColumn('bed_count', function($room) {
            return $room->bed_allocations->count();
        })
        ->make(true);
}




}
  