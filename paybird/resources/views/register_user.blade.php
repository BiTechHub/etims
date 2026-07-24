<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pay Bird</title>

<!-- Bootstrap 5 -->
<link href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

<!-- Font Awesome -->
<link rel="stylesheet" href="{{ asset('assets/fontawesome/css/all.min.css') }}">

<style>
body {
    background: linear-gradient(135deg, #e3f2fd, #f5f7fa);
    font-family: 'Segoe UI', sans-serif;
}

/* Container Fix (header/footer ke baad spacing) */
.register-wrapper {
    min-height: calc(100vh - 120px);
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Card */
.register-card {
    background: #fff;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

/* Left Image Section */
.left-section {
    background: linear-gradient(rgba(0,0,0,0.2), rgba(0,0,0,0.2)),
                url('https://etims.upulb.in/logo2.jpg') center/cover;
    min-height: 400px;
}

/* Form Styling */
.form-control {
    border-radius: 8px;
    border: 1px solid #ddd;
    padding: 10px;
    font-size: 17px;
    transition: 0.3s;
}

.form-control:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 2px rgba(78,115,223,0.1);
}

/* Labels */
.form-label {
    font-size: 20px;
    color: #555;
    font-weight: 500;
}

/* Button */
.btn-custom {
    background: #4e73df;
    color: #fff;
    border-radius: 30px;
    padding: 10px;
    font-weight: 500;
    transition: 0.3s;
}

.btn-custom:hover {
    background: #2e59d9;
}

/* Title */
.title {
    font-weight: 600;
    margin-bottom: 20px;
    color: #333;
}

/* Responsive */
@media(max-width: 768px){
    .left-section{
        display:none;
    }

    .register-card {
        margin: 10px;
    }
}
</style>

</head>
<body>
    <!--  HEADER -->
<nav style="background:#fff; padding:12px 25px; box-shadow:0 2px 10px rgba(0,0,0,0.08); position:sticky; top:0; z-index:999;">
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        
        <div class="d-flex align-items-center">
            <img src="{{url('/')}}/public/logo.png" style="height:42px; margin-right:10px;">
            <h5 style="margin:0; font-weight:600; color:#222;">
                PayBIRD
            </h5>
        </div>

        <div>
            <a href="{{ url('/admin-login') }}" 
               style="text-decoration:none; color:#4e73df; font-weight:500;">
               Login
            </a>
        </div>

    </div>
</nav>
<div class="container register-wrapper mt-3">
    <div class="row register-card w-100">

        <!-- Left Image -->
        <div class="col-md-5 left-section"></div>

        <!-- Right Form -->
        <div class="col-md-7 p-4">
            @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif
            <h3 class="title">Payment Details</h3>
          @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

            <form method="POST" action="{{ url('pay-bird-post') }}">
    @csrf

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">First Name <span style="color:red;">*</span></label>
        <input type="text"
       name="first_name"
       class="form-control @error('first_name') is-invalid @enderror"
       value="{{ old('first_name') }}"
       required>

@error('first_name')
<span class="text-danger">{{ $message }}</span>
@enderror
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Last Name <span style="color:red;">*</span></label>
        <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}" required>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Phone Number <span style="color:red;">*</span></label>
        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}"  required>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Email <span style="color:red;">*</span></label>
        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
    </div>

    

<!-- OPTIONAL FIELDS -->

       <div class="col-md-6 mb-3">
    <label class="form-label">Organization/Institute Type <span class="text-danger">*</span></label>

    <select name="organization" class="form-control" required>
        <option value="">--Select--</option>

        @foreach($organization as $org)
            <option value="{{ $org->name }}"
                {{ old('organization') == $org->name ? 'selected' : '' }}>
                {{ $org->name }}
            </option>
        @endforeach
    </select>

    @error('organization')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>


    

        <div class="col-md-6 mb-3">
    <label class="form-label">Payment Type <span class="text-danger">*</span></label>

    <select name="payment" class="form-control" required>
        <option value="">--Select--</option>

        @foreach($payment_type as $pay)
            <option value="{{ $pay->id }}"
                {{ old('payment') == $pay->id ? 'selected' : '' }}>
                {{ $pay->name }} - ₹{{ $pay->amount }}
            </option>
        @endforeach
    </select>

    @error('payment')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>

        <div class="col-md-6 mb-3">
    <label class="form-label">Select Branch <span class="text-danger">*</span></label>

    <select name="branch" class="form-control" required>
        <option value="">--Select--</option>

        @foreach($branch as $br)
            <option value="{{ $br->name }}"
                {{ old('branch') == $br->name ? 'selected' : '' }}>
                {{ $br->name }}
            </option>
        @endforeach
    </select>

    @error('branch')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>

       <div class="col-md-6 mb-3">
    <label class="form-label">State <span class="text-danger">*</span></label>

    <select name="state" class="form-control" required>
        <option value="">--Select--</option>

        @foreach($states as $state)
            <option value="{{ $state->name }}"
                {{ old('state') == $state->name ? 'selected' : '' }}>
                {{ $state->name }}
            </option>
        @endforeach
    </select>

    @error('state')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>
            <div class="col-md-6 mb-3">
    <label class="form-label">Country <span class="text-danger">*</span></label>

    <select name="Country" class="form-control" required>
        <option value="">--Select--</option>

        @foreach($countries as $country)
            <option value="{{ $country->name }}"
                {{ old('Country') == $country->name ? 'selected' : '' }}>
                {{ $country->name }}
            </option>
        @endforeach
    </select>

    @error('Country')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>



    </div>

    <button type="submit" class="btn btn-custom w-100">Proceed to Payment</button>
    {{-- <div style="text-align:center; margin-top:12px;">
    <span style="font-size:14px;">Already have an account?</span>
    <a href="{{ url('/admin-login') }}" 
       style="color:#1A237E; font-weight:bold; text-decoration:none; margin-left:5px;">
        Login Now
    </a>
</div> --}}
</form>
        </div>

    </div>
</div>
<!--  FOOTER -->
<footer style="background:#fff; padding:15px; text-align:center; margin-top:20px; border-top:1px solid #eee;">
    <p style="margin:0; font-size:13px; color:#777;">
       © 2026 <b>Bird</b> |
Developed By
<a href="https://www.businessinnovations.in" target="_blank">
<b>Business Innovations</b>
</a>
    </p>
</footer>
</body>
</html>