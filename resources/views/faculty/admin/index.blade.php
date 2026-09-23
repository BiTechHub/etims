<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>U.P. State Construction And Infrastructure Development Corporation LTD</title>
  <link href="{{url('/frontend')}}/img/logo.jpeg" rel="icon">
      <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">

  {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
  <style>
    body {
      background: url('{{url('/')}}/frontend/img/const1.jpg') no-repeat center center fixed;
    background-size: cover;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }
    .login-card {
      background-color: rgba(255, 255, 255, 0.92);
      max-width: 400px;
      width: 100%;
      border-radius: 15px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      padding: 2rem;
    }
    .captcha-img {
      width: 100%;
      height: auto;
      border-radius: 8px;
    }
  </style>
</head>
<body>

  <div class="login-card bg-white11">
    <div class="text-center mb-4">
      <img src="{{url('/frontend')}}/img/logo.jpeg" alt="BIRD Logo" width="180">
      <h5 class="mt-2">UPSIDKO LOGIN</h5>
    </div>

    {{-- 🔥 Show Laravel Errors --}}
    @if ($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{route('admin.check')}}" method="post">
        @csrf
      <div class="mb-3">
        <label class="form-label">UserName</label>
        <input type="text" name="username" value="{{ old('username') }}" class="form-control" placeholder="Enter your username">
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" placeholder="Enter your password">
      </div>

      {{-- Optional CAPTCHA (if needed later)
      <div class="mb-3">
        <label class="form-label">Type the text</label>
        <input type="text" class="form-control" placeholder="Enter CAPTCHA">
      </div>
      <div class="mb-3">
        <img src="https://dummyimage.com/300x80/000/fff&text=JTAU" alt="CAPTCHA" class="captcha-img">
      </div> 
      --}}

      <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="rememberMe">
        <label class="form-check-label" for="rememberMe">Remember Me</label>
      </div>
      <button type="submit" class="btn btn-success w-100">Login</button>
    </form>
  </div>

</body>
</html>
