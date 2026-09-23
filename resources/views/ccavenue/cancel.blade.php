<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Transaction Cancelled</title>
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

    .cancel-card {
      background: #ffffff;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
      padding: 40px;
      text-align: center;
      max-width: 400px;
    }

    .cancel-icon {
      font-size: 60px;
      color: #fd7e14;
    }

    .cancel-title {
      font-size: 24px;
      font-weight: bold;
      margin-top: 20px;
      color: #fd7e14;
    }

    .cancel-message {
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

  <div class="cancel-card">
    <div class="cancel-icon">❌</div>
    <div class="cancel-title">Transaction Cancelled</div>
    <div class="cancel-message">
      Your transaction has been cancelled or aborted.<br>
      Transaction ID: <strong>#{{ $response['order_id'] ?? 'N/A' }}</strong>
    </div>
    <a href="/" class="btn btn-outline-warning btn-home">Go to Home</a>
  </div>

</body>
</html>
