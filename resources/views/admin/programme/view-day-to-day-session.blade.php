@extends('admin.layouts.master')

@section('main-section')

<style>

    body{
        background:#efefef;
        font-family:"Times New Roman", Times, serif;
    }

    .schedule-wrapper{
        width:850px;
        margin:20px auto;
        background:#fff;
        padding:25px 35px;
        border:1px solid #ccc;
        color:#000;
    }

    .schedule-title{
        text-align:center;
        margin-bottom:15px;
    }

    .schedule-title h2{
        font-size:22px;
        font-weight:bold;
        text-decoration:underline;
        margin-bottom:2px;
    }

    .schedule-title h4{
        font-size:20px;
        font-weight:bold;
        text-decoration:underline;
        margin-bottom:4px;
    }

    .schedule-title h5{
        font-size:18px;
        font-weight:bold;
        margin-bottom:0;
        text-decoration:none !important;
    }

    .resource-text{
        text-align:center;
        margin:25px 0;
        font-size:16px;
    }

    .d2d-heading{
        text-align:center;
        font-weight:bold;
        font-size:24px;
        margin-bottom:20px;
    }

    .schedule-table{
        width:100%;
        border-collapse:collapse;
    }

    .schedule-table th,
    .schedule-table td{
        border:1px solid #000;
        padding:5px 7px;
        font-size:15px;
        vertical-align:top;
    }

    .schedule-table th{
        font-weight:bold;
        text-align:left;
    }

    .date-column{
        width:18%;
        text-align:left !important;
        vertical-align:middle !important;
        font-weight:bold;
    }

    .session-column{
        width:12%;
        text-align:center !important;
        vertical-align:middle !important;
        font-weight:bold;
    }

    .topic-column{
        width:48%;
        text-align:justify;
    }

    .resource-column{
        width:22%;
        text-align:left;
    }

    .break-row td{
        text-align:center !important;
        font-style:italic;
        font-weight:normal !important;
        background:#fff;
    }

    .break-row .session-column{
        font-weight:bold !important;
        font-style:normal;
        text-transform:capitalize;
    }

    .session-timing-title{
        font-size:22px;
        font-weight:bold;
        margin-top:30px;
        margin-bottom:10px;
    }

    .timing-table{
        width:100%;
        border-collapse:collapse;
    }

    .timing-table th,
    .timing-table td{
        border:1px solid #000;
        padding:6px;
        font-size:15px;
        text-align:center;
    }

    .timing-table th{
        font-weight:bold;
    }

    .timing-table td{
        vertical-align:top;
    }

    /* Break columns in timing table */
    .timing-table .break-header{
        text-transform:capitalize;
        font-weight:bold;
       
    }

    .timing-table .break-cell{
       
    }

    .no-print{
        margin-bottom:15px;
    }

    @media print {
        body *{
            visibility:hidden !important;
        }
        .schedule-wrapper,
        .schedule-wrapper *{
            visibility:visible !important;
        }
        .schedule-wrapper{
            position:absolute;
            left:0;
            top:0;
            width:100%;
            border:none;
            margin:0;
            padding:15px;
            background:#fff;
        }
        .no-print{
            display:none !important;
        }
        @page{
            margin:10mm;
        }
    }

</style>

<div class="container-fluid">

    <!-- BUTTONS -->
    <div class="text-end no-print">
        <button onclick="window.print()" class="btn btn-primary">
            <i class="fas fa-print"></i> Print
        </button>
        <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
    </div>

    <!-- MAIN AREA -->
    <div class="schedule-wrapper">

        <!-- HEADER -->
        <div class="schedule-title">
            <h2>{{ $programme->title }}</h2>
            <h4>at {{ $programme->venue ?? 'Madurai, Tamil Nadu' }}</h4>
            <h5>
                {{ \Carbon\Carbon::parse($programme->from_date)->format('d') }} –
                {{ \Carbon\Carbon::parse($programme->to_date)->format('d F Y') }}
            </h5>
        </div>

        <!-- RESOURCE -->
        <div class="resource-text">
            PDs: {{ $programme->programme_director ?? 'Shri Deependra Kumar & Smt. Akanksha, DGM/FMs' }}
        </div>

        <!-- TITLE -->
        <div class="d2d-heading">Day to Day Schedule</div>

        <!-- MAIN TABLE -->
        <table class="schedule-table">
            <thead>
                <tr>
                    <th class="date-column">Date</th>
                    <th class="session-column">Session</th>
                    <th class="topic-column">Topic</th>
                    <th class="resource-column">Resource Person/s</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $allItems = [];

                    foreach ($subtopics->sortBy([
                        ['date', 'asc'],
                        ['start_time', 'asc']
                    ]) as $st) {

                        $allItems[] = [
                            'type'  => 'session',
                            'date'  => $st->date,
                            'time'  => $st->start_time,
                            'model' => $st,
                        ];

                        if ($st->relationLoaded('SessionBreaks') && $st->SessionBreaks->isNotEmpty()) {
                            foreach ($st->SessionBreaks as $br) {
                                $allItems[] = [
                                    'type'  => 'break',
                                    'date'  => $br->date,
                                    'time'  => $br->start_time,
                                    'model' => $br,
                                ];
                            }
                        }
                    }

                    usort($allItems, function ($a, $b) {
                        $dc = strcmp($a['date'], $b['date']);
                        if ($dc !== 0) return $dc;
                        return strcmp($a['time'], $b['time']);
                    });

                    $grouped = collect($allItems)->groupBy('date');
                @endphp

                @foreach($grouped as $date => $items)
                    @foreach($items as $idx => $row)

                        @if($row['type'] === 'session')
                            <tr>
                                @if($idx === 0)
                                    <td rowspan="{{ $items->count() }}" class="date-column">
                                        {{ \Carbon\Carbon::parse($date)->format('d.m.Y') }}
                                        <br>({{ \Carbon\Carbon::parse($date)->format('l') }})
                                    </td>
                                @endif

                                <td class="session-column">{{ $row['model']->session_name }}</td>
                                <td class="topic-column">{{ $row['model']->title }}</td>
                                <td class="resource-column">
                                    @php
                                        $names = [];
                                        if ($row['model']->relationLoaded('faculty') && $row['model']->faculty) {
                                            $names[] = $row['model']->faculty->name;
                                        }
                                        if ($row['model']->relationLoaded('guestFaculty') && $row['model']->guestFaculty) {
                                            if (is_iterable($row['model']->guestFaculty)) {
                                                foreach ($row['model']->guestFaculty as $g) {
                                                    $names[] = $g->name;
                                                }
                                            }
                                        }
                                    @endphp
                                    {{ count($names) ? implode(', ', $names) : 'PDs' }}
                                </td>
                            </tr>

                        @else
                            <tr class="break-row">
                                @if($idx === 0)
                                    <td rowspan="{{ $items->count() }}" class="date-column">
                                        {{ \Carbon\Carbon::parse($date)->format('d.m.Y') }}
                                        <br>({{ \Carbon\Carbon::parse($date)->format('l') }})
                                    </td>
                                @endif

                               
                            </tr>
                        @endif

                    @endforeach
                @endforeach
            </tbody>
        </table>

        <!-- ==========================================
             SESSION TIMINGS (with breaks interleaved)
        ========================================== -->
        <div class="session-timing-title">Session Timings:</div>

        @php
            // Build interleaved columns: Session I → Tea → Session II → Lunch → Session III → Tea → Session IV
            $timingColumns = [];

            foreach ($subtopics->sortBy('start_time') as $st) {

                // Add the session column
                $timingColumns[] = [
                    'type'  => 'session',
                    'name'  => $st->session_name,
                    'start' => $st->start_time,
                    'end'   => $st->end_time,
                ];

                // Add any breaks attached to this session, sorted by start_time
                if ($st->relationLoaded('SessionBreaks') && $st->SessionBreaks->isNotEmpty()) {
                    foreach ($st->SessionBreaks->sortBy('start_time') as $br) {
                        $timingColumns[] = [
                            'type'  => 'break',
                            'name'  => ucfirst($br->break_type),
                            'start' => $br->start_time,
                            'end'   => $br->end_time,
                        ];
                    }
                }
            }
        @endphp

        <table class="timing-table">
            <thead>
                <tr>
                    @foreach($timingColumns as $col)
                        @if($col['type'] === 'session')
                            <th> Session {{ $col['name'] }}</th>
                        @else
                            <th class="break-header">{{ $col['name'] }}</th>
                        @endif
                    @endforeach
                </tr>
            </thead>
            <tbody>
                <tr>
                    @foreach($timingColumns as $col)
                        @if($col['type'] === 'session')
                            <td>
                                {{ \Carbon\Carbon::parse($col['start'])->format('H:i') }} to
                                {{ \Carbon\Carbon::parse($col['end'])->format('H:i') }} hrs.
                            </td>
                        @else
                            <td class="break-cell">
                                {{ \Carbon\Carbon::parse($col['start'])->format('H:i') }} to
                                {{ \Carbon\Carbon::parse($col['end'])->format('H:i') }} hrs.
                            </td>
                        @endif
                    @endforeach
                </tr>
            </tbody>
        </table>

    </div>
</div>

@endsection