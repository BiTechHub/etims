<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Checkedout Confirmation</title>
    <style>
        body {
            font-family: "Segoe UI", sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .email-container {
            max-width: 600px;
            background: #ffffff;
            margin: 0 auto;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            padding: 30px;
        }

        h2 {
            color: #2c3e50;
        }

        p {
            font-size: 16px;
            line-height: 1.6;
        }

        a.button {
            display: inline-block;
            margin-top: 15px;
            padding: 12px 20px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
        }

        a.button:hover {
            background-color: #2980b9;
        }

        .footer {
            margin-top: 30px;
            font-size: 13px;
            color: #888;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <h2>Thank You, {{ $participant->name }}!</h2>
    
        <p>We hope you had a comfortable and memorable stay with us.</p>
    
        <p>Your feedback is important to us. It helps us improve and serve you better in the future.</p>
    
        <p>
            <a class="button" href="{{ route('hostel.feedback.form', ['id' => $participant->id]) }}">
                Give Feedback
            </a>
        </p>
    
        <p>We look forward to welcoming you again. Safe travels!</p>
    
        <div class="footer">
            © {{ date('Y') }} Bird. All rights reserved.
        </div>
    </div>
    
</body>
</html>
