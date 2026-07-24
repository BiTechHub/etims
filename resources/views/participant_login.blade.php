<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title>Login Page</title>

  <!-- Bootstrap CDN (Fixed) -->
      <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">

  <!-- Font Awesome -->
<link rel="stylesheet" href="{{ asset('assets/fontawesome/css/all.min.css') }}">
  <style>   
    body {
      background: url("{{ asset('logo2.jpg') }}") no-repeat center center fixed;
      background-size: cover;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      margin: 0;
      padding: 0;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .card {
      border: none;
      border-radius: 1rem;
      background-color: white;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
      width: 100%;
      max-width: 500px;
      padding: 30px;
    }

    .logo-container {
      text-align: center;
      margin-bottom: 20px;
    }

    .logo-container img {
      max-width: 150px;
      height: auto;
    }

    .btn-blue {
      background-color: #1A237E;
      color: white;
      font-weight: 600;
      transition: background-color 0.3s ease;
    }

    .btn-blue:hover {
      background-color: #000;
    }

    .footer {
      background-color: #1A237E;
      color: white;
      text-align: center;
      padding: 12px;
      font-size: 14px;
      margin-top: 20px;
      border-radius: 0 0 1rem 1rem;
    }

    .footer a {
      color: #ffd54f;
      text-decoration: none;
    }

    .footer a:hover {
      text-decoration: underline;
    }

    @media (max-width: 576px) {
      .card {
        margin: 20px;
        padding: 20px;
      }

      .logo-container img {
        max-width: 120px;
      }
    }
  </style>
</head>

<body>
  <div class="card">
    <div class="logo-container">
      <img src="{{ asset('assets/images/logo.png') }}" alt="Logo">
    </div>

    <h4 class="text-center mb-4">Participant Login</h4>

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
      <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('participant.login.submit') }}" method="POST">
      @csrf
      <div class="mb-3">
        <label for="phone" class="form-label">Mobile Number</label>
        <input type="text" name="phone" id="phone" class="form-control form-control-lg" placeholder="Enter your mobile number" required pattern="[0-9]{10}">
      </div>

      <div class="d-grid mt-2">
        <button type="submit" class="btn btn-blue btn-block">Login</button>
      </div>
    </form>

    <div class="text-center mt-3">
      <a href="{{ asset('assets/images/qr.png') }}" target="_blank" class="btn btn-light btn-sm rounded-pill">
        Click here for QR Code <i class="fas fa-external-link-alt ms-1"></i>
      </a>
    </div>

    <div class="footer mt-4">
      <p class="mb-0">
        © 2025 <strong>Uppcl</strong>. All Rights Reserved. |
        Developed By:
        <a href="https://www.businessinnovations.in" target="_blank"><strong>Business Innovations</strong></a>
      </p>
    </div>
  </div>
</body>
</html>
