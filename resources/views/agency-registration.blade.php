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

        html,
        body {
            min-height: 100vh;
            overflow-x: hidden;
        }

        body {
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
            min-width: 0;
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

        /* ===== FORM ROWS & FIELD GROUPS =====
           Custom styling targets the classes the markup actually uses
           (.form-group / .form-label / .form-control), not a stray
           .group class that never matched anything. */

        #agency_registration_form .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -12px;
        }

        #agency_registration_form .row+.row {
            margin-top: 4px;
        }

        #agency_registration_form [class*="col-"] {
            padding: 0 12px;
        }

        .form-group {
            width: 100%;
            margin-bottom: 22px;
        }

        .form-label {
            display: block;
            font-weight: 600;
            font-size: 13.5px;
            margin-bottom: 7px;
            color: #151a2d;
        }

        .form-control,
        .form-select,
        select.form-control {
            width: 100%;
            display: block;
            box-sizing: border-box;
            height: 44px;
            padding: 11px 14px;
            border-radius: 10px;
            border: 1px solid #e1e4ec;
            font-size: 14.5px;
            font-family: inherit;
            line-height: 1.4;
            color: #151a2d;
            background: #fafbfd;
            transition: border-color .2s, box-shadow .2s, background .2s;
        }

        select.form-control {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'><path d='M1 1l5 5 5-5' stroke='%23555' stroke-width='1.5' fill='none' fill-rule='evenodd'/></svg>");
            background-repeat: no-repeat;
            background-position: right 14px center;
            background-size: 12px 8px;
            padding-right: 34px;
            cursor: pointer;
        }

        .form-control::placeholder {
            color: #aab0c2;
        }

        .form-control:hover,
        .form-select:hover {
            border-color: #c7cee2;
        }

        .form-control:focus,
        .form-select:focus {
            outline: none;
            border-color: #1a3a8f;
            box-shadow: 0 0 0 3px rgba(26, 58, 143, .12);
            background: #fff;
        }

        .form-group small,
        .form-text {
            display: block;
            margin-top: 6px;
            color: #9aa1b5;
            font-size: 12px;
            line-height: 1.5;
        }

        /* Required-field marker: quieter than raw red text */
        .text-danger {
            color: #d64545;
            font-weight: 600;
        }

        button.btn-primary,
        button[type="submit"] {
            padding: 13px 34px;
            background: linear-gradient(135deg, #1a3a8f, #2451b8);
            border: none;
            color: #fff;
            font-weight: 700;
            border-radius: 10px;
            font-size: 15.5px;
            cursor: pointer;
            transition: background .2s, transform .15s;
            margin-top: 6px;
        }

        button.btn-primary:hover,
        button[type="submit"]:hover {
            background: #111c3f;
        }

        button.btn-primary:active,
        button[type="submit"]:active {
            transform: translateY(1px);
        }

        button.btn-primary:disabled,
        button[type="submit"]:disabled {
            opacity: .7;
            cursor: not-allowed;
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

        /* ===== RESPONSIVE BREAKPOINTS ===== */

        /* Tablets: shrink left panel instead of hiding it too early */
        @media (max-width: 1024px) {
            .left-panel {
                flex-basis: 300px;
                padding: 40px 26px;
            }
        }

        /* Below this, stack layout: left panel becomes a compact top banner */
        @media (max-width: 900px) {
            body {
                flex-direction: column;
            }

            .left-panel {
                flex: 0 0 auto;
                width: 100%;
                padding: 28px 20px;
                min-height: 0;
            }

            .icon-badge {
                width: 56px;
                height: 56px;
                border-radius: 16px;
                margin-bottom: 14px;
            }

            .icon-badge .inner {
                width: 42px;
                height: 42px;
                font-size: 16px;
            }

            .left-panel h1 {
                font-size: 20px;
            }

            .left-panel p.sub {
                margin-bottom: 10px;
                font-size: 11px;
            }

            .left-panel p.desc {
                display: none;
            }

            .back-link {
                margin-top: 16px;
            }

            .back-link a {
                padding: 8px 18px;
                font-size: 13px;
            }

            .right-panel {
                padding: 26px 18px 40px;
            }
        }

        /* Phones */
        @media (max-width: 576px) {
            .left-panel {
                padding: 20px 16px;
            }

            .icon-badge {
                width: 46px;
                height: 46px;
                margin-bottom: 10px;
            }

            .icon-badge .inner {
                width: 34px;
                height: 34px;
                font-size: 14px;
            }

            .left-panel h1 {
                font-size: 18px;
            }

            .left-panel p.sub {
                letter-spacing: 1px;
            }

            .right-panel {
                padding: 20px 14px 32px;
            }

            .badge-secure {
                font-size: 11px;
                padding: 5px 12px;
            }

            .form-wrap h2 {
                font-size: 21px;
            }

            .form-wrap p.lead {
                font-size: 13px;
                margin-bottom: 18px;
            }

            .form-group {
                margin-bottom: 16px;
            }

            .form-control,
            .form-select {
                height: 42px;
                font-size: 14px;
            }

            button.btn-primary,
            button[type="submit"] {
                width: 100%;
                padding: 13px 20px;
                font-size: 15px;
            }

            .footer-note {
                font-size: 11.5px;
                margin-top: 22px;
            }
        }

        /* Very small phones */
        @media (max-width: 360px) {
            .left-panel h1 {
                font-size: 16px;
            }

            .form-wrap h2 {
                font-size: 19px;
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

            {{-- id changed to match the AJAX handler below --}}
            <form id="agency_registration_form" action="{{ route('agency.store.public') }}" method="POST">

                @csrf

                <div class="row">

                    <div class="col-md-4 form-group">
                        <label for="agency_type" class="form-label">Agency Type <span
                                class="text-danger">*</span></label>
                        <select class="form-control" id="agency_type" name="agency_type_id" required>
                            <option value="">Select Agency Type</option>
                            @foreach ($agencyTypes as $agencyType)
                                <option value="{{ $agencyType->id }}"
                                    {{ old('agency_type_id') == $agencyType->id ? 'selected' : '' }}>
                                    {{ $agencyType->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 form-group">
                        <label for="name" class="form-label">Agency Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name"
                            placeholder="Enter Agency Name" value="{{ old('name') }}" required>
                    </div>

                    <div class="col-md-4 form-group">
                        <label for="sponsor_bank" class="form-label">Sponsor Bank</label>
                        <input type="text" class="form-control" id="sponsor_bank" name="sponsor_bank"
                            placeholder="Enter Sponsor Bank" value="{{ old('sponsor_bank') }}">
                    </div>

                </div>

                <div class="row">

                    <div class="col-md-4 form-group">
                        <label for="chairman" class="form-label">Head of Origination</label>
                        <input type="text" class="form-control" id="chairman" name="chairman"
                            placeholder="Enter Chairman" value="{{ old('chairman') }}">
                    </div>

                    <div class="col-md-4 form-group">
                        {{-- fixed: was pointing at #chairman --}}
                        <label for="designation" class="form-label">Designation</label>
                        <input type="text" class="form-control" id="designation" name="designation"
                            placeholder="Enter Chairman" value="{{ old('designation') }}">
                    </div>

                    <div class="col-md-4 form-group">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control" id="address" name="address"
                            placeholder="Enter Address" value="{{ old('address') }}">
                    </div>


                </div>

                <div class="row">

                    <div class="col-md-4 form-group">
                        <label for="state" class="form-label">State <span class="text-danger">*</span></label>
                        <select class="form-control" id="state" name="state" required>
                            <option value="">Select State</option>
                            {{-- States will be loaded via AJAX --}}
                        </select>
                    </div>


                    <div class="col-md-4 form-group">
                        <label for="city" class="form-label">City <span class="text-danger">*</span></label>
                        <select class="form-control" id="city" name="city" required>
                            <option value="">Select City</option>
                            {{-- Cities will be loaded based on selected state --}}
                        </select>
                    </div>

                    <div class="col-md-4 form-group">
                        <label for="pincode" class="form-label">Pincode</label>
                        <input type="text" class="form-control" id="pincode" name="pincode"
                            placeholder="Enter Pincode" value="{{ old('pincode') }}">
                    </div>

                    <div class="col-md-4 form-group">
                        <label class="form-label">
                            HR Department Phone <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" name="phone" id="phone"
                            placeholder="Enter Mobile or Landline, separated by commas" value="{{ old('phone') }}"
                            required>
                        <small class="text-muted">
                            Example: 9876543210, 0522-2234567, +91-522-2234567
                        </small>
                    </div>

                    <div class="col-md-4 form-group">
                        <label for="emailid" class="form-label">Nominating Authority Email-Id <span
                                class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="emailid" name="emailid"
                            placeholder="Enter Email" value="{{ old('emailid') }}" required>
                    </div>

                    {{-- Country field intentionally omitted --}}

                    <div class="col-md-4 form-group">
                        <label for="cc_email" class="form-label">CC Emails</label>
                        <input type="text" class="form-control" id="cc_email" name="cc_email"
                            placeholder="Enter CC Emails, separated by commas" value="{{ old('cc_email') }}">
                        <small class="form-text text-muted">Enter multiple email addresses separated by
                            commas (e.g., user1@example.com, user2@example.com)</small>
                    </div>

                </div>

                {{-- Fax field intentionally omitted --}}

                {{-- id changed to match the AJAX handler below --}}
                <button type="submit" id="submit-btn" class="btn btn-primary">Save Agency</button>

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

            // matches the form's own id + action now, so this actually runs
            $('#agency_registration_form').submit(function(e) {
                e.preventDefault();

                $('#submit-btn')
                    .prop('disabled', true)
                    .html(`<span class="spinner-border spinner-border-sm me-1"></span> Please wait...`);

                var formData = new FormData(this);

                $.ajax({
                    url: "{{ route('agency.store.public') }}",
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

                        console.log('Response:', xhr.responseJSON);

                        let errorMessage = "Something went wrong!";

                        if (xhr.status === 422) {

                            // Controller se direct message aa raha hai
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }

                            // Laravel validation errors ke liye
                            else if (xhr.responseJSON && xhr.responseJSON.errors) {
                                errorMessage = '';

                                $.each(xhr.responseJSON.errors, function(key, value) {
                                    errorMessage += `<div>${value[0]}</div>`;
                                });
                            }
                        }

                        let alertBox = $(`
        <div class="alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 m-3"
             role="alert"
             style="z-index:9999; min-width:300px; max-width:500px;">
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
