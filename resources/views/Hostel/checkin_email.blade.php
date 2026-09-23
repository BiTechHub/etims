<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Checkedin Confirmation</title>
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
        <h2>Welcome, {{ $participant->name }}!</h2>

        <p>Thank you for checking in. We’re excited to have you with us and wish you a pleasant stay.</p>

        <p>If you have any queries or need assistance, please feel free to contact us.</p>

        <p>
            <a class="button" href="http://127.0.0.1:8000/participants/queries">Submit a Query</a>
        </p>

        <p>Thank you!</p>

        <div class="footer">
            © {{ date('Y') }} Bird. All rights reserved.
        </div>
    </div>
</body>
</html>
