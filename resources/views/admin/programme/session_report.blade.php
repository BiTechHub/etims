<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Training Programme Schedule</title>
  <style>
    * {
      font-family: 'Arial', sans-serif;
      font-weight: bold;
      box-sizing: border-box;
    }

    body {
      margin: 20px;
      padding: 20px;
      background: #fff;
      color: #000;
      border: 2px solid #000; /* Outer border */
    }

    h2, h3 {
      text-align: center;
      margin: 5px 0;
    }

    h2 {
      font-size: 20px;
      color: #003366;
    }

    h3 {
      font-size: 16px;
      color: #005580;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
      font-size: 14px;
    }

    th, td {
      border: 1px solid #000;
      padding: 6px;
      text-align: center;
    }

    th {
      background-color: #e8f4fa;
    }

    .day {
      writing-mode: vertical-lr;
      background-color: #d0e4f7;
    }

    .highlight {
      font-size: 15px;
      text-align: center;
      margin-top: 20px;
      color: #004466;
    }

    .note {
      font-size: 13px;
      margin-top: 20px;
      border-top: 1px dashed #999;
      padding-top: 10px;
    }

    .note ul {
      padding-left: 20px;
    }

    .note strong {
      color: red;
    }

    .print-btn {
      display: block;
      width: fit-content;
      margin: 30px auto 10px;
      padding: 8px 20px;
      font-size: 14px;
      cursor: pointer;
      background-color: #007acc;
      color: white;
      border: none;
      border-radius: 4px;
    }

    .print-btn:hover {
      background-color: #005b99;
    }

    @media print {
      @page {
        size: A4;
        margin: 15mm;
      }

      .print-btn {
        display: none;
      }

      body {
        margin: 0;
        border: none;
      }
    }
  </style>
</head>
<body>

<h2>Training Programme on {{ $programme->title }}: 
  {{ \Carbon\Carbon::parse($programme->from_date)->format('d-m-Y') }}
</h2>
<h3>
  Programme Directors (PDs): 
  {{ $faculty1->name ??null}}, {{ $faculty1->designation ??null}} & 
  {{ $faculty2->name ??null}}, {{ $faculty2->designation ??null}}
</h3>

<table>
  <tr>
    <th>Day</th>
    <th>Session</th>
    <th>Topic</th>
    <th>Resource Person</th>
  </tr>

  @php $dayCount = 1; @endphp
  @foreach($subtopics as $date => $sessions)
      @php
          $validSessions = collect($sessions)->filter(function ($s) {
              $title = strtolower(trim($s->title));
              return !in_array($title, ['tea break', 'lunch break']);
          })->sortBy('start_time')->values();
      @endphp

      @if($validSessions->isEmpty()) @continue @endif

      <tr>
          <td rowspan="{{ $validSessions->count() }}" class="day">
              Day {{ $dayCount }}<br>{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
          </td>

          @php $first = true; $sessionCount = 1; @endphp
          @foreach($validSessions as $session)
              @if(!$first) <tr> @endif
              <td>Session {{ $sessionCount }}</td>
              <td>{{ $session->title }}</td>
              <td>{{ ucfirst($session->faculty_type) }} - {{ $session->faculty->name ?? 'N/A' }}</td>
              </tr>
              @php $first = false; $sessionCount++; @endphp
          @endforeach

      @php $dayCount++; @endphp
  @endforeach
</table>

<div class="highlight">Session Timings:</div>
@if($subtopics->isNotEmpty())
  @php
    $dates = $subtopics->keys()->sort()->values();
    $firstDate = $dates->first();
    $firstDaySessions = $subtopics[$firstDate]->sortBy('start_time');
  @endphp

  <h4 style="margin-bottom: 4px;">
    Date: {{ \Carbon\Carbon::parse($firstDate)->format('d/m/Y') }}
  </h4>
  <table class="session-times" border="1" cellspacing="0" cellpadding="5" width="100%">
    <thead>
      <tr>
        @php $sessionNumber = 1; @endphp
        @foreach($firstDaySessions as $session)
          @php
            $lowerTitle = strtolower(trim($session->title));
            $isBreak = in_array($lowerTitle, ['tea break', 'lunch break']);
          @endphp
          <th>
            @if($isBreak)
              {{ ucwords($lowerTitle) }}
            @else
              Session {{ $sessionNumber++ }}
            @endif
          </th>
        @endforeach
      </tr>
    </thead>
    <tbody>
      <tr>
        @foreach($firstDaySessions as $session)
          <td>
            {{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }}
            to
            {{ \Carbon\Carbon::parse($session->end_time)->format('H:i') }}
            hrs
          </td>
        @endforeach
      </tr>
    </tbody>
  </table>
@else
  <p>No session timings available.</p>
@endif


<div class="note">
  <p><strong>Note:</strong></p>
  <ul>
    <li><strong>PLEASE KEEP YOUR MOBILE PHONE IN SWITCH OFF MODE IN CLASS ROOM</strong></li>
    <li><strong>PLEASE OBSERVE TIME SCHEDULE ON DAILY BASIS</strong></li>
  </ul>
</div>

<button class="print-btn" onclick="window.print()">Print</button>

</body>
</html>
