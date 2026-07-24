<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nomination Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            padding: 20px;
        }
        .header, .footer {
            font-size: 14px;
            margin-bottom: 20px;
        }
        .letter {
            border: 1px solid #ddd;
            padding: 20px;
        }
        .bold {
            font-weight: bold;
        }
        .section {
            margin-top: 20px;
        }
        .hindi {
            font-family: 'Noto Sans Devanagari', sans-serif;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <p><strong>FROM:</strong> The Director, BIRD, Lucknow</p>
            <p><strong>TO:</strong> The General Manager, {{$agency->name ?? null}}</p>
            <p><strong>Ref:</strong> BIRD/LKO/OLC017214/26487/2025-2026</p>
        
        </div>

        <div class="letter">
            <p class="bold">ACCEPTANCE LETTER / स्वीकृति पत्र</p>

            <p>Dear Sir,</p>
            <p><strong>Programme:</strong>{{$programme->title}}</p>
            <p><strong>Dates:</strong>{{$programme->from_date}} {{$programme->to_date}}</p>

            <p class="hindi">कृपया अपने ई-मेल दिनांक 28/05/2025 का सन्दर्भ लें जिसके द्वारा आपने 
                <strong>{{ $participant->name }}</strong>, <strong>{{ $participant->designation }}</strong> को उपरोक्त कार्यक्रम हेतु नामांकित किया है।
                हम उनका नामांकन स्वीकार करते हैं।
            </p>

            <p>Please refer to your email dated 28/05/2025 through which you have nominated 
                <strong>{{ $participant->name }}</strong>, <strong>{{ $participant->designation }}</strong> for the above-mentioned training programme. We confirm the nomination.
            </p>

            <p class="hindi">आपसे अनुरोध है कि उन्हें सूचित करें कि उपयुक्त कार्यक्रम में सहभागिता के समय इस पत्र की प्रति साथ लाएं।</p>
            <p class="hindi">हम यह भी अनुरोध करते हैं कि सहभागियों को पहचान पत्र (ड्राइविंग लाइसेंस/मतदाता पहचान पत्र/आधार कार्ड) लाने के लिए भी सूचित करें।</p>

            <p>We request you to advise the participant to bring a copy of this letter when attending the programme.</p>
            <p>Also, kindly ensure they carry a valid ID proof (Driving License, Voter ID Card, or Aadhar Card – original and a copy).</p>
        </div>

        <div class="section footer">
            <p>भवदीय,</p>
            <p>प्रबंधक</p>
            <p>Bankers Institute of Rural Development (An autonomous Society promoted by NABARD)</p>
            <p>Sector H, LDA Colony, Kanpur Road, Lucknow – 226012</p>
            <p>Tel: +91 522 2421187, 2425917 | Fax: +91 522 2421006</p>
            <p>Email: bird@nabard.org | Website: <a href="http://www.birdlucknow.in">www.birdlucknow.in</a></p>
        </div>
    </div>
</body>
</html>
