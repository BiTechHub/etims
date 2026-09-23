<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Payment Successful</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
<link href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <style>
    body {
      background-color: #f5f7fa;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }

    .success-card {
      background: #ffffff;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
      padding: 40px;
      text-align: center;
      max-width: 400px;
    }

    .success-icon {
      font-size: 60px;
      color: #28a745;
    }

    .success-title {
      font-size: 24px;
      font-weight: bold;
      margin-top: 20px;
    }

    .success-message {
      font-size: 16px;
      color: #555;
      margin-top: 10px;
    }

    .btn-home {
      margin-top: 20px;
    }
  </style>
</head>
<body>

  <div class="success-card">
    <div class="success-icon">✅</div>
    <div class="success-title">Payment Successful!</div>
    <div class="success-message">
      Thank you! Your payment has been processed successfully.<br>
      Transaction ID: <strong>#{{$response['order_id']}}</strong>
    </div>
    <a href="/" class="btn btn-success btn-home">Go to Home</a>
  </div>

</body>
</html>
