<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BIRD (e-TIMS)</title>
     <link rel="icon" type="image/png" href="{{ asset('assets/images/logo_white.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fontawesome/css/all.min.css') }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif
        }

        body {
            min-height: 100vh;
            display: flex;
            background: #f4f6fb;
        }

        /* ===== LEFT PANEL ===== */
        .left-panel {
            flex: 0 0 380px;
            background: linear-gradient(160deg, #0d2c6e 0%, #1a3a8f 60%, #2451b8 100%);
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 50px 35px;
            position: relative;
            overflow: hidden;
        }

        .left-panel::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: radial-gradient(circle, rgba(255, 255, 255, .08) 1.5px, transparent 1.5px);
            background-size: 40px 40px;
            opacity: .4;
        }

        .icon-badge {
            width: 90px;
            height: 90px;
            background: rgba(255, 255, 255, .12);
            border-radius: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 30px;
            position: relative;
            z-index: 1;
        }

        .icon-badge .inner {
            width: 70px;
            height: 70px;
            background: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #1a3a8f;
            font-size: 24px;
        }

        .left-panel h1 {
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 6px;
            position: relative;
            z-index: 1;
        }

        .left-panel h1 span {
            color: #7ecbff
        }

        .left-panel p.sub {
            letter-spacing: 2px;
            font-size: 13px;
            color: #c7d6f5;
            margin-bottom: 30px;
            position: relative;
            z-index: 1;
        }

        .left-panel p.desc {
            font-size: 14px;
            line-height: 1.7;
            color: #dbe4fa;
            position: relative;
            z-index: 1;
        }

        .back-link {
            margin-top: 40px;
            position: relative;
            z-index: 1;
        }

        .back-link a {
            color: #fff;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            background: rgba(255, 255, 255, .12);
            padding: 10px 22px;
            border-radius: 30px;
            border: 1px solid rgba(255, 255, 255, .25);
            transition: .25s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .back-link a:hover {
            background: #fff;
            color: #0d2c6e;
        }

        /* ===== RIGHT PANEL ===== */
        .right-panel {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 30px;
            overflow-y: auto;
        }

        .form-wrap {
            width: 100%;
            max-width: 900px;
        }

        .badge-secure {
            display: inline-block;
            background: #e8f1ff;
            color: #1a3a8f;
            font-weight: 700;
            font-size: 12px;
            letter-spacing: 1px;
            padding: 6px 14px;
            border-radius: 20px;
            margin-bottom: 18px;
        }

        .form-wrap h2 {
            font-size: 28px;
            font-weight: 800;
            color: #151a2d;
            margin-bottom: 6px;
        }

        .form-wrap p.lead {
            color: #7a8199;
            font-size: 14.5px;
            margin-bottom: 26px;
        }

        .group label {
            display: block;
            font-weight: 600;
            font-size: 13.5px;
            margin-bottom: 6px;
            color: #151a2d;
        }

        .group .form-control,
        .group .form-select {
            padding: 11px 14px;
            border-radius: 10px;
            border: 1px solid #e1e4ec;
            font-size: 14.5px;
            background: #fafbfd;
            transition: .2s;
        }

        .group .form-control:focus,
        .group .form-select:focus {
            outline: none;
            border-color: #1a3a8f;
            box-shadow: 0 0 0 3px rgba(26, 58, 143, .12);
            background: #fff;
        }

        .group small {
            color: #9aa1b5;
            font-size: 12px;
        }

        button.reg-btn {
            padding: 13px 34px;
            background: linear-gradient(135deg, #1a3a8f, #2451b8);
            border: none;
            color: #fff;
            font-weight: 700;
            border-radius: 10px;
            font-size: 15.5px;
            cursor: pointer;
            transition: .2s;
        }

        button.reg-btn:hover {
            background: #111c3f
        }

        button.reg-btn:disabled {
            opacity: .7;
            cursor: not-allowed
        }

        .footer-note {
            text-align: center;
            margin-top: 30px;
            font-size: 13px;
            color: #9aa1b5;
        }

        .footer-note a {
            color: #1a3a8f;
            font-weight: 600;
            text-decoration: none
        }

        @media(max-width:900px) {
            .left-panel {
                display: none
            }

            .right-panel {
                padding: 30px 16px
            }
        }
    </style>
</head>

<body>

    <!-- ===== LEFT PANEL ===== -->
    <div class="left-panel">
        <div class="icon-badge">
            <div class="inner"><img src="{{ asset('assets/images/logo_white.png') }}" alt=""
                    class="navbar-brand" height="70" /></div>
        </div>
        <h1>BIRD <span>(e-TIMS)</span></h1>
        <p class="sub">AGENCY REGISTRATION</p>
        <p class="desc">
            Register your agency to nominate participants, manage programmes,
            and access the e-TIMS agency portal.
        </p>

        <div class="back-link">
            <a href="{{ route('welcome') }}"><i class="fas fa-arrow-left"></i> Back to Login</a>
        </div>
    </div>

    <!-- ===== RIGHT PANEL ===== -->
    <div class="right-panel">
        <div class="form-wrap">

            <span class="badge-secure">NEW AGENCY</span>
            <h2>Agency Registration</h2>
            <p class="lead">Fill in your agency details to create an account</p>

            <form id="agency_registration_form" method="POST">
                @csrf
                <div class="row g-3">

                    <div class="col-md-4 group">
                        <label for="agency_type">Agency Type <span class="text-danger">*</span></label>
                        <select class="form-select" id="agency_type" name="agency_type_id" required>
                            <option value="">Select Agency Type</option>
                            @foreach ($agencyTypes as $agencyType)
                                <option value="{{ $agencyType->id }}"
                                    {{ old('agency_type_id') == $agencyType->id ? 'selected' : '' }}>
                                    {{ $agencyType->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 group">
                        <label for="name">Agency Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name"
                            value="{{ old('name') }}" required>
                    </div>

                    <div class="col-md-4 group">
                        <label for="sponsor_bank">Sponsor Bank</label>
                        <input type="text" class="form-control" id="sponsor_bank" name="sponsor_bank"
                            value="{{ old('sponsor_bank') }}">
                    </div>

                    <div class="col-md-4 group">
                        <label for="chairman">Chairman</label>
                        <input type="text" class="form-control" id="chairman" name="chairman"
                            value="{{ old('chairman') }}">
                    </div>

                    <div class="col-md-4 group">
                        <label for="address">Address</label>
                        <input type="text" class="form-control" id="address" name="address"
                            value="{{ old('address') }}">
                    </div>

                    <div class="col-md-4 group">
                        <label for="state">State <span class="text-danger">*</span></label>
                        <select class="form-select" id="state" name="state" required></select>
                    </div>

                    <div class="col-md-4 group">
                        <label for="city">City <span class="text-danger">*</span></label>
                        <select class="form-select" id="city" name="city" required></select>
                    </div>

                    <div class="col-md-4 group">
                        <label for="pincode">Pincode</label>
                        <input type="text" class="form-control" id="pincode" name="pincode"
                            value="{{ old('pincode') }}">
                    </div>

                    <div class="col-md-4 group">
                        <label for="phone">Phone <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="phone" name="phone"
                            value="{{ old('phone') }}" required>
                    </div>

                    <div class="col-md-4 group">
                        <label for="emailid">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="emailid" name="emailid"
                            value="{{ old('emailid') }}" required>
                    </div>

                    <div class="col-md-4 group">
                        <label for="cc_email">CC Emails</label>
                        <input type="text" class="form-control" id="cc_email" name="cc_email"
                            value="{{ old('cc_email') }}">
                        <small>Separate multiple emails with commas</small>
                    </div>

                </div>

                <div class="mt-4">
                    <button type="submit" class="reg-btn" id="submit-btn">Save Agency</button>
                </div>
            </form>

            <div class="footer-note">
                © 2026 <b>Bird</b> | Developed by
                <a href="https://www.businessinnovations.in" target="_blank">Business Innovations</a>
            </div>

        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script>
        $(function() {
            $.get('/states', function(states) {
                states.forEach(function(state) {
                    $('#state').append(new Option(state.name, state.id));
                });
                const oldState = "{{ old('state') }}";
                if (oldState) $('#state').val(oldState).trigger('change');
            });

            $('#state').on('change', function() {
                var stateId = $(this).val();
                $('#city').empty().append(new Option('Select City', ''));
                if (stateId) {
                    $.get(`/states/${stateId}/districts`, function(cities) {
                        cities.forEach(function(city) {
                            $('#city').append(new Option(city.name, city.name));
                        });
                        const oldCity = "{{ old('city') }}";
                        if (oldCity) $('#city').val(oldCity);
                    });
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {

            $('#agency_registration_form').submit(function(e) {
                e.preventDefault();

                $('#submit-btn')
                    .prop('disabled', true)
                    .html(`<span class="spinner-border spinner-border-sm me-1"></span> Please wait...`);

                var formData = new FormData(this);

                $.ajax({
                    url: "{{ route('create-agency') }}",
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,

                    success: function(data) {
                        $('body').prepend(`
                    <div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3"
                         role="alert" style="z-index:9999;">
                        Agency created successfully!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `);

                        setTimeout(function() {
                            window.location.href = "{{ route('welcome') }}";
                        }, 2000);
                    },

                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);

                        let errorMessage = "Something went wrong!";

                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            errorMessage = '';
                            $.each(errors, function(key, value) {
                                errorMessage += `<div>${value[0]}</div>`;
                            });
                        }

                        let alertBox = $(`
                    <div class="alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 m-3"
                         role="alert"
                         style="z-index:9999; min-width:300px;">
                        ${errorMessage}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `);

                        $('body').prepend(alertBox);

                        setTimeout(function() {
                            alertBox.alert('close');
                        }, 5000);

                        $('#submit-btn')
                            .prop('disabled', false)
                            .html('Save Agency');
                    }
                });

            });

        });
    </script>

</body>

</html>
