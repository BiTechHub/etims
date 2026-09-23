<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />

    <title>Participant Login</title>

    <!-- Bootstrap -->
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

        .card-box {
            border: none;
            border-radius: 1rem;
            background: #fff;
            box-shadow: 0 8px 24px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 500px;
            padding: 30px;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo-container img {
            max-width: 140px;
        }

        .btn-blue {
            background: #1A237E;
            color: #fff;
            font-weight: 600;
        }

        .btn-blue:hover {
            background: #000;
            color: #fff;
        }

        .footer {
            background: #1A237E;
            color: #fff;
            text-align: center;
            padding: 12px;
            margin-top: 20px;
            border-radius: 0 0 1rem 1rem;
            font-size: 14px;
        }

        .footer a {
            color: #ffd54f;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        @media(max-width:576px){
            .card-box{
                margin:20px;
                padding:20px;
            }
        }
    </style>
</head>

<body>

<div class="card-box">

    <!-- Logo -->
    <div class="logo-container">
        <img src="{{ asset('assets/images/logo.png') }}" alt="Logo">
    </div>

    <!-- Heading -->
    <h4 class="text-center mb-4">Participant Login</h4>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Error Message -->
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <!-- Form Start -->
    <form action="{{ route('exam_check') }}" method="POST">
        @csrf

        <div class="row">

            <input type="hidden" name="programme_id" value="{{ $programmes->id }}">
            <input type="hidden" name="type" value="{{ $type }}">

       

            <div class="col-md-6 mb-3">

        <!-- Dropdown -->
        <div class="mb-3">
            <label class="form-label">Select Participant</label>

            <select name="name"
                    id="phone"
                    class="form-select"
                    required>

                <option value="" selected>-- Select Participant --</option>


                @foreach($participant as $participant)

                    <option value="{{ $participant->id }}" {{ old('name') == $participant->id ? 'selected' : '' }}>
                        {{ $participant->name }}
                    </option>

                @endforeach

            </select>

            @error('name')
        <div class="text-danger mt-1">
            {{ $message }}
        </div>
    @enderror


            
        </div>

            </div>
            <div class="col-md-6 mb-3">

         <div class="mb-3">
            <label class="form-label">Participant Phone Number</label>
            <input type="text" name="phone" id="" class="form-control form-control-lg" placeholder="Phone Number" required value="{{ old('phone') }}">
         </div>
         @error('phone')
        <div class="text-danger mt-1">
            {{ $message }}
        </div>
    @enderror

            </div>


        <!-- Login Button -->
        <div class="d-grid mt-3">
            <button type="submit" class="btn btn-blue btn-lg">
                Login
            </button>
        </div>

         </div>

    </form>
    <!-- Form End -->

    

   

</div>

</body>
</html>