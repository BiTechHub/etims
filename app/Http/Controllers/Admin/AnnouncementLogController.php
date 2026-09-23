<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\AnnouncementLog;
use App\Models\ProgrammeManagement;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AnnouncementLogController extends Controller
{
    // Show the report page with filters
    public function index()
    {
        $financialYears = ProgrammeManagement::query()
            ->whereNotNull('financial_year')
            ->where('financial_year', '!=', '')
            ->select('financial_year')
            ->distinct()
            ->orderByRaw("CAST(SUBSTRING_INDEX(financial_year, '-', 1) AS UNSIGNED) DESC")
            ->pluck('financial_year');

        $agencies = Agency::select('id', 'name')->orderBy('name')->get();

        return view('admin.programme.announcement_report', compact('financialYears', 'agencies'));
    }

    // DataTable server-side data source
    public function getData(Request $request)
{
    $query = AnnouncementLog::query()
        ->with(['programme:id,title,unique_id,financial_year', 'agencyType:id,name']);

    // financial_year 'programmes' table me hai, relation ke through filter
    if ($request->filled('financial_year')) {
        $query->whereHas('programme', function ($q) use ($request) {
            $q->where('financial_year', $request->financial_year);
        });
    }

    // agency_type_id ab seedha announcement_logs table me hi hai
    if ($request->filled('agency_type_id')) {
        $query->where('agency_type_id', $request->agency_type_id);
    }

    $query->orderByDesc('sent_at');

    return DataTables::of($query)
        ->addColumn('programme_title', function ($log) {
            return optional($log->programme)->title ?? '-';
        })
        ->addColumn('unique_id', function ($log) {
            return optional($log->programme)->unique_id ?? '-';
        })
        ->addColumn('agency_name', function ($log) {
            return optional($log->agencyType)->name ?? '-';
        })
        ->addColumn('sent_at_formatted', function ($log) {
            return $log->sent_at ? \Carbon\Carbon::parse($log->sent_at)->format('d-m-Y h:i A') : '-';
        })
        ->make(true);
}
}