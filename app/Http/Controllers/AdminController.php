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
use Illuminate\Support\Str;

class AdminController extends Controller
{
    // Indian state/UT name => amCharts map code
    protected $stateCodeMap = [
        'andhra pradesh' => 'IN-AP',
        'arunachal pradesh' => 'IN-AR',
        'assam' => 'IN-AS',
        'bihar' => 'IN-BR',
        'chhattisgarh' => 'IN-CT',
        'goa' => 'IN-GA',
        'gujarat' => 'IN-GJ',
        'haryana' => 'IN-HR',
        'himachal pradesh' => 'IN-HP',
        'jharkhand' => 'IN-JH',
        'karnataka' => 'IN-KA',
        'kerala' => 'IN-KL',
        'madhya pradesh' => 'IN-MP',
        'maharashtra' => 'IN-MH',
        'manipur' => 'IN-MN',
        'meghalaya' => 'IN-ML',
        'mizoram' => 'IN-MZ',
        'nagaland' => 'IN-NL',
        'odisha' => 'IN-OR',
        'orissa' => 'IN-OR',
        'punjab' => 'IN-PB',
        'rajasthan' => 'IN-RJ',
        'sikkim' => 'IN-SK',
        'tamil nadu' => 'IN-TN',
        'telangana' => 'IN-TG',
        'tripura' => 'IN-TR',
        'uttar pradesh' => 'IN-UP',
        'uttarakhand' => 'IN-UT',
        'west bengal' => 'IN-WB',
        // Union Territories
        'andaman and nicobar islands' => 'IN-AN',
        'chandigarh' => 'IN-CH',
        'dadra and nagar haveli and daman and diu' => 'IN-DN',
        'delhi' => 'IN-DL',
        'new delhi' => 'IN-DL',
        'jammu and kashmir' => 'IN-JK',
        'ladakh' => 'IN-LA',
        'lakshadweep' => 'IN-LD',
        'puducherry' => 'IN-PY',
        'pondicherry' => 'IN-PY',
    ];

    public function index()
{
    $today = Carbon::today();

    $activeProgrammes = ProgrammeManagement::whereDate('to_date', '>=', $today)
        ->where('status', 'Announced')
        ->get();

    $programme = ProgrammeManagement::all();

    $user = User::where('user_type', 'Faculty')->get();

   $financialYears = ProgrammeManagement::query()
    ->whereNotNull('financial_year')
    ->where('financial_year', '!=', '')
    ->select('financial_year')
    ->distinct()
    ->orderBy('financial_year', 'desc')
    ->pluck('financial_year');

    return view('index', compact(
        'programme',
        'activeProgrammes',
        'user',
        'financialYears'
    ));
}
    public function getAdminDashboardInfo(Request $request)
{
    if ($request->type == 'program_dates') {

        $month = (int) $request->month;
        $financialYear = $request->financial_year;

        /*
        |--------------------------------------------------------------------------
        | Validate Financial Year
        |--------------------------------------------------------------------------
        | Example: 2026-27
        |--------------------------------------------------------------------------
        */

        if (!$financialYear) {
            return response()->json([
                'success' => false,
                'message' => 'Financial year is required.'
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Extract Financial Year
        |--------------------------------------------------------------------------
        */

        $financialYearParts = explode('-', $financialYear);

        if (count($financialYearParts) !== 2) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid financial year format.'
            ], 422);
        }

        $startYear = (int) $financialYearParts[0];

        /*
        |--------------------------------------------------------------------------
        | Financial Year Mapping
        |--------------------------------------------------------------------------
        |
        | 2026-27:
        |
        | April 2026  -> March 2027
        |
        */

        $actualYear = $month >= 4
            ? $startYear
            : $startYear + 1;

        /*
        |--------------------------------------------------------------------------
        | Start & End Date
        |--------------------------------------------------------------------------
        */

        $startDate = Carbon::createFromDate(
            $actualYear,
            $month,
            1
        )->startOfMonth();

        $endDate = Carbon::createFromDate(
            $actualYear,
            $month,
            1
        )->endOfMonth();

        /*
        |--------------------------------------------------------------------------
        | Cache Key
        |--------------------------------------------------------------------------
        */

        $cacheKey = 'programmeDates_' . $month . '_' . $financialYear;

        if (!Cache::has($cacheKey)) {

            /*
            |--------------------------------------------------------------------------
            | Fetch Programmes
            |--------------------------------------------------------------------------
            */

            $programmes = ProgrammeManagement::with([
                'progDir1:id,name',
                'progDir2:id,name',
            ])
                ->where('financial_year', $financialYear)

                /*
                |--------------------------------------------------------------------------
                | Programme Date Filter
                |--------------------------------------------------------------------------
                */

                ->where(function ($query) use ($startDate, $endDate) {

                    $query->whereBetween('from_date', [
                        $startDate,
                        $endDate
                    ])

                    ->orWhereBetween('to_date', [
                        $startDate,
                        $endDate
                    ])

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
                    'prog_dir_2',
                    'financial_year'
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

                /*
                |--------------------------------------------------------------------------
                | Week End
                |--------------------------------------------------------------------------
                |
                | Monday -> Saturday
                |--------------------------------------------------------------------------
                */

                $weekEnd = $current->copy()->addDays(5);

                /*
                |--------------------------------------------------------------------------
                | Filter Programmes For Current Week
                |--------------------------------------------------------------------------
                */

                $weekProgrammes = $programmes->filter(function ($programme) use (
                    $weekStart,
                    $weekEnd
                ) {

                    return
                        Carbon::parse($programme->from_date)->lte($weekEnd)
                        &&
                        Carbon::parse($programme->to_date)->gte($weekStart);

                })->values();

                if ($weekProgrammes->count() > 0) {

                    $weeks[] = [

                        'week_name' =>
                            $weekStart->format('d/m/Y')
                            . ' - '
                            . $weekEnd->format('d/m/Y'),

                        'from_date' =>
                            $weekStart->format('d/m/Y'),

                        'to_date' =>
                            $weekEnd->format('d/m/Y'),

                        'programmes' => $weekProgrammes->map(function ($item) {

                            return [

                                'id' => $item->id,

                                'programme_name' => $item->title,

                                'from_date' =>
                                    Carbon::parse($item->from_date)
                                        ->format('d/m/Y'),

                                'to_date' =>
                                    Carbon::parse($item->to_date)
                                        ->format('d/m/Y'),

                                'director_1' =>
                                    $item->progDir1->name ?? '-',

                                'director_2' =>
                                    $item->progDir2->name ?? '-',

                            ];

                        })->values(),

                    ];
                }

                $current->addWeek();
            }

            /*
            |--------------------------------------------------------------------------
            | Cache Data
            |--------------------------------------------------------------------------
            */

            Cache::put(
                $cacheKey,
                $weeks,
                now()->addMinutes(5)
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Get Cached Data
        |--------------------------------------------------------------------------
        */

        $weeks = Cache::get($cacheKey);

        return response()->json([

            'success' => true,

            'data' => $weeks,

        ]);
    }  
      
      
    if ($request->type == 'program_participants') {

    $programmeId = $request->programme_id;

    if (!$programmeId) {
        return response()->json([
            'success' => false,
            'message' => 'Programme id is required.'
        ], 422);
    }

    $programme = ProgrammeManagement::select('id', 'location', 'venue')
        ->find($programmeId);

    $officialAddress = $programme->location ?? '-';
    $nominatingAddress = $programme->venue ?? '-';

    $participants = Participant::where('programme_id', $programmeId)
        ->select('id', 'name', 'status')
        ->get();

    $confirmed = $participants->filter(function ($p) {
        return strtolower(trim($p->status ?? '')) === 'confirm';
    })->values();

    $unconfirmed = $participants->filter(function ($p) {
        return strtolower(trim($p->status ?? '')) !== 'confirm';
    })->values();

    return response()->json([
        'success'            => true,
        'official_address'   => $officialAddress,
        'nominating_address' => $nominatingAddress,
        'confirmed'          => $confirmed,
        'unconfirmed'        => $unconfirmed,
    ]);
}

    return response()->json([
        'success' => false,
        'message' => 'Invalid request type.'
    ], 400);
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

        $agencyNominations = $this->getAgencyNominations();
        [$stateStats, $stateNominations, $totalNominations, $maxStateTotal] = $this->getStateStats();
        $classStudents = $this->getClassStudents();

        $announcedCount = ProgrammeManagement::where('status', 'Announced')->count();
        $postponedCount = ProgrammeManagement::where('status', 'Postponed')->count();
        $cancelledCount = ProgrammeManagement::where('status', 'Cancelled')->count();

        return view('admin.static_dashboard.static_dashboard', compact(
            'facultiesCount', 'programmesCount', 'nominationsCount',
            'programmeNominations', 'classStudents', 'agencyNominations',
            'stateStats', 'stateNominations', 'totalNominations', 'maxStateTotal',
            'announcedCount', 'postponedCount', 'cancelledCount'
        ));
    }

    private function getAgencyNominations()
    {
        $rawAgencyCounts = DB::table('nomination_participants')
            ->select('agency_id', DB::raw('count(*) as total'))
            ->whereNotNull('agency_id')
            ->where('agency_id', '!=', '')
            ->groupBy('agency_id')
            ->orderByDesc('total')
            ->get();

        $agencyNamesById = DB::table('agencies')->pluck('name', 'id');

        return $rawAgencyCounts->map(function ($row) use ($agencyNamesById) {
            return [
                'agency' => $agencyNamesById[$row->agency_id] ?? 'Unknown',
                'total' => (int) $row->total,
            ];
        })->values();
    }

    private function getStateStats()
    {
        $rawStateCounts = DB::table('nomination_participants')
            ->select('state', DB::raw('count(*) as total'))
            ->whereNotNull('state')
            ->where('state', '!=', '')
            ->groupBy('state')
            ->get();

        $stateNamesById = DB::table('states')->pluck('name', 'id');

        $merged = [];

        foreach ($rawStateCounts as $row) {
            $stateName = $stateNamesById[$row->state] ?? null;

            if (! $stateName) {
                continue;
            }

            $normalized = Str::lower(trim($stateName));
            $code = $this->stateCodeMap[$normalized] ?? null;

            if (! $code) {
                continue;
            }

            if (! isset($merged[$code])) {
                $merged[$code] = ['name' => $stateName, 'code' => $code, 'total' => 0];
            }

            $merged[$code]['total'] += (int) $row->total;
        }

        usort($merged, fn ($a, $b) => $b['total'] <=> $a['total']);

        $stateStats = array_values($merged);
        $stateNominations = collect($stateStats)->pluck('total', 'code')->toArray();
        $totalNominations = array_sum(array_column($stateStats, 'total'));
        $maxStateTotal = count($stateStats) ? max(array_column($stateStats, 'total')) : 0;

        return [$stateStats, $stateNominations, $totalNominations, $maxStateTotal];
    }

    private function getClassStudents()
    {
        return Classes::with(['programmes.participants'])
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
    }
}
