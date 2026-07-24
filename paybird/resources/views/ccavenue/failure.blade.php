<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Payment Failed</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f5f7fa;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }

    .failure-card {
      background: #ffffff;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
      padding: 40px;
      text-align: center;
      max-width: 400px;
    }

    .failure-icon {
      font-size: 60px;
      color: #dc3545;
    }

    .failure-title {
      font-size: 24px;
      font-weight: bold;
      margin-top: 20px;
      color: #dc3545;
    }

    .failure-message {
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

  <div class="failure-card">
    <div class="failure-icon">❌</div>
    <div class="failure-title">Payment Failed!</div>
    <div class="failure-message">
      Sorry, your payment could not be completed.<br>
      Transaction ID: <strong>#{{$response['order_id'] ?? 'N/A'}}</strong>
    </div>
    <a href="/pay-bird" class="btn btn-danger btn-home">Go to Home</a>
  </div>

</body>
</html>
