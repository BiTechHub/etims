<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Programme Announcement</title>
</head>
<body style="font-family: Georgia, 'Times New Roman', serif;">

  <div style="display:flex; align-items:center; justify-content:space-between; border-bottom:20px solid #000; padding-bottom:10px; margin-bottom:20px;">
    <div>
      <h2 style="margin:0;font-size:16px;">बैंकर्स ग्रामिण विकास संस्थान
        <small style="font-weight:normal;">नाबार्ड द्वारा प्रवर्तित आईएसओ 9001:2015 प्रमाणित स्वायत्त संस्था</small>
      </h2>
      <h3 style="margin:0;font-size:16px;">Bankers Institute of Rural Development
        <small style="font-weight:normal;">An ISO 9001:2015 certified autonomous institute promoted by NABARD</small>
      </h3>
    </div>
    <div>
      <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" style="height:80px;">
    </div>
  </div>

  <p>Dear Sir/Madam,</p>

  <p>
    Please find attached the announcement letter for the training programme
    <strong>"{{ $programme->title }}"</strong>
    scheduled from <strong>{{ \Carbon\Carbon::parse($programme->from_date)->format('d F Y') }}</strong>
    to <strong>{{ \Carbon\Carbon::parse($programme->to_date)->format('d F Y') }}</strong>.
  </p>

  <div style="margin-top:20px; border-top:20px solid #000; padding-top:10px; font-size:14px;">
    सेटर-एच, एलडीए कॉलोनी, कानपुर रोड, लखनऊ – 226012<br>
    Sector-H, LDA Colony, Kanpur Road, Lucknow – 226012<br>
    Phone: +91-522-2425917 / 2421097 | Email: bird@nabard.org |
    Website: <a href="https://birdlucknow.nabard.org" target="_blank">birdlucknow.nabard.org</a>
  </div>

</body>
</html>