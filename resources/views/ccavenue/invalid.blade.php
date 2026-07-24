<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Invalid Transaction</title>
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

    .invalid-card {
      background: #ffffff;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
      padding: 40px;
      text-align: center;
      max-width: 400px;
    }

    .invalid-icon {
      font-size: 60px;
      color: #ffc107;
    }

    .invalid-title {
      font-size: 24px;
      font-weight: bold;
      margin-top: 20px;
      color: #ffc107;
    }

    .invalid-message {
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

  <div class="invalid-card">
    <div class="invalid-icon">🚫</div>
    <div class="invalid-title">Invalid Transaction</div>
    <div class="invalid-message">
      The transaction is invalid or access was not authorized.<br>
      Transaction ID: <strong>#{{$response['order_id'] ?? 'N/A'}}</strong>
    </div>
    <a href="/" class="btn btn-warning btn-home">Go to Home</a>
  </div>

</body>
</html>
