<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Nomination;
use App\Models\Participant;
use App\Models\ProgrammeManagement;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $activeProgrammes = ProgrammeManagement::whereDate('to_date', '>=', $today)->where('status', 'Announced')->get();
        $programme = ProgrammeManagement::all();
        $user = User::where('user_type', 'Faculty')->get();

        return view('index', compact('programme', 'activeProgrammes', 'user'));
    }

    public function getAdminDashboardInfo(Request $request)
    {
        if ($request->type == 'program_dates') {

            $month = $request->month;
            $year = $request->year;

            $startDate = Carbon::createFromDate($year, $month, 1)
                ->startOfMonth();

            $endDate = Carbon::createFromDate($year, $month, 1)
                ->endOfMonth();

            $cacheKey = 'programmeDates_'.$month.'_'.$year;

            if (! Cache::has($cacheKey)) {

                /*
                |--------------------------------------------------------------------------
                | Fetch Programmes
                |--------------------------------------------------------------------------
                */

                $programmes = ProgrammeManagement::with([
                    'progDir1:id,name',
                    'progDir2:id,name',
                ])
                    ->where(function ($query) use ($startDate, $endDate) {

                        $query->whereBetween('from_date', [$startDate, $endDate])
                            ->orWhereBetween('to_date', [$startDate, $endDate])
                            ->orWhere(function ($q) use ($startDate, $endDate) {

                                $q->where('from_date', '<=', $startDate)
                                    ->where('to_date', '>=', $endDate);

                            });

                    })
                    ->select(
                        'id',
                        'title',
                        'from_date',
                        'to_date',
                        'prog_dir_1',
                        'prog_dir_2'
                    )
                    ->orderBy('from_date')
                    ->get();

                /*
                |--------------------------------------------------------------------------
                | Generate Weeks
                |--------------------------------------------------------------------------
                */

                $weeks = [];

                $current = $startDate->copy()
                    ->startOfWeek(Carbon::MONDAY);

                while ($current->lte($endDate)) {

                    $weekStart = $current->copy();

                    $weekEnd = $current->copy()->addDays(5);

                    /*
                    |--------------------------------------------------------------------------
                    | Filter Programmes For Current Week
                    |--------------------------------------------------------------------------
                    */

                    $weekProgrammes = $programmes->filter(function ($programme) use ($weekStart, $weekEnd) {

                        return
                            Carbon::parse($programme->from_date)->lte($weekEnd)
                            &&
                            Carbon::parse($programme->to_date)->gte($weekStart);

                    })->values();

                    if ($weekProgrammes->count() > 0) {

                        $weeks[] = [

                            'week_name' => $weekStart->format('d/m/Y').
                                ' - '.
                                $weekEnd->format('d/m/Y'),

                            'from_date' => $weekStart->format('d/m/Y'),

                            'to_date' => $weekEnd->format('d/m/Y'),

                            'programmes' => $weekProgrammes->map(function ($item) {

                                return [

                                    'id' => $item->id,

                                    'programme_name' => $item->title,

                                    'from_date' => Carbon::parse($item->from_date)
                                        ->format('d/m/Y'),

                                    'to_date' => Carbon::parse($item->to_date)
                                        ->format('d/m/Y'),

                                    'director_1' => $item->progDir1->name ?? '-',

                                    'director_2' => $item->progDir2->name ?? '-',

                                ];

                            }),

                        ];
                    }

                    $current->addWeek();
                }

                /*
                |--------------------------------------------------------------------------
                | Cache Data
                |--------------------------------------------------------------------------
                */

                Cache::put($cacheKey, $weeks, now()->addMinutes(5));
            }

            $weeks = Cache::get($cacheKey);

            return response()->json([

                'success' => true,

                'data' => $weeks,

            ]);
        }
    }

    public function staticDashboard()
    {
        $facultiesCount = User::where('user_type', 'Faculty')->count();
        $programmesCount = ProgrammeManagement::count();
        $nominationsCount = Nomination::count();

        $programmeNominations = Participant::select('programme_id', DB::raw('count(*) as total'))
            ->groupBy('programme_id')
            ->with('programme:id,title')
            ->orderByDesc('total')
            ->take(8)
            ->get();

        $stateNominations = DB::table('nomination_participants')
            ->select('state', DB::raw('count(*) as total'))
            ->whereNotNull('state')
            ->where('state', '!=', '')
            ->groupBy('state')
            ->orderByDesc('total')
            ->get();

        $classStudents = Classes::with(['programmes.participants'])
            ->get()
            ->map(function ($class) {
                return [
                    'name' => $class->name,
                    'total' => $class->programmes->sum(function ($programme) {
                        return $programme->participants->count();
                    }),
                ];
            })
            ->sortByDesc('total')
            ->values();

        $announcedCount = ProgrammeManagement::where('status', 'Announced')->count();
        $postponedCount = ProgrammeManagement::where('status', 'Postponed')->count();
        $cancelledCount = ProgrammeManagement::where('status', 'Cancelled')->count();

        return view('admin.static_dashboard.static_dashboard', compact(
            'facultiesCount', 'programmesCount', 'nominationsCount',
            'programmeNominations', 'stateNominations', 'classStudents',
            'announcedCount', 'postponedCount', 'cancelledCount'
        ));
    }
}
