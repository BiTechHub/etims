<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>{{ $programme->title ?? 'Programme Poster' }}</title>
  <style>
    body {
      font-family: 'Georgia', serif;
      background: #fff;
      color: #000;
      padding: 40px;
      max-width: 900px;
      margin: auto;
    }
    h1, h2, h3 {
      text-align: center;
      margin: 10px 0;
    }
    .section {
      margin: 30px 0;
      text-align: center;
    }
    .venue-time {
      font-weight: bold;
      font-size: 18px;
      margin-top: 20px;
      text-align: center;
    }
    .directors {
      margin-top: 20px;
      font-weight: bold;
      text-align: center;
    }
    .footer {
      margin-top: 40px;
      font-size: 14px;
      text-align: center;
      border-top: 1px solid #ccc;
      padding-top: 20px;
    }
    .print-btn {
      display: block;
      margin: 20px auto;
      padding: 10px 20px;
      font-size: 16px;
      background: #007bff;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }
    .print-btn:hover {
      background: #0056b3;
    }
  </style>
</head>
<body>

  <button class="print-btn" onclick="window.print()">Print Poster</button>

  <h2>WELCOME</h2>
  <h3>PARTICIPANTS FOR</h3>

  <h2>{{ $programme->title }}</h2>
  <h3>for Faculty Members of {{ $programme->sponsor->name ?? 'N/A' }}</h3>

  <h3>
    {{ \Carbon\Carbon::parse($programme->from_date)->format('d F Y') }} 
    to 
    {{ \Carbon\Carbon::parse($programme->to_date)->format('d F Y') }}
  </h3>

  <div class="venue-time">
    PLEASE ASSEMBLE IN<br>
    <strong>{{ $programme->classRoom->name ?? 'CLASS ROOM' }}</strong><br>
  AT <strong>
  {{ $subtopic && $subtopic->start_time ? \Carbon\Carbon::parse($subtopic->start_time)->format('h:i A') : 'N/A' }}
</strong>



  </div>

  <div class="directors">
    PROGRAMME DIRECTOR{{ $programme->progDir2 ? 's' : '' }}<br>
    <strong>
      {{ $programme->progDir1->name ?? '' }}
      @if($programme->progDir2)
        &amp; {{ $programme->progDir2->name }}
      @endif
    </strong>
  </div>

  <div class="section">
    <strong>www.birdlucknow.in</strong><br>
    <strong>Bankers Institute of Rural Development</strong><br>
    (An autonomous Society promoted by NABARD)<br><br>
    <strong>बैंकर ग्रामीण विकास संस्थान</strong><br>
    (नाबार्ड द्वारा स्थापित एक स्वायत्त संस्था)
  </div>

  <div class="footer">
    सेक्टर एच, एलडीए कॉलोनी, कानपुर रोड, लखनऊ – 226012. टेली.: +91 522 2421187, 2425917 • फ़ैक्स: +91 522 2421006 • ईमेल: bird@nabard.org<br>
    Sector H, LDA Colony, Kanpur Road, Lucknow – 226012 • Tel.: +91 522 2421187, 2425917 • Fax: +91 522 2421006 • E-mail: bird@nabard.org
  </div>

</body>
</html>
