<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BIRD (e-TIMS)</title>
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
            flex: 1;
            max-width: 520px;
            background: linear-gradient(160deg, #0d2c6e 0%, #1a3a8f 60%, #2451b8 100%);
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 50px 40px;
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
            font-size: 32px;
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
            margin-bottom: 40px;
            position: relative;
            z-index: 1;
        }

        /* ===== ROLE OPTIONS (LEFT PANEL TABS) ===== */
        .role-option {
            display: flex;
            align-items: center;
            gap: 14px;
            width: 100%;
            max-width: 340px;
            margin-bottom: 14px;
            text-align: left;
            position: relative;
            z-index: 1;
            background: rgba(255, 255, 255, .06);
            border: 1px solid rgba(255, 255, 255, .12);
            border-radius: 14px;
            padding: 12px 16px;
            cursor: pointer;
            transition: .25s;
        }

        .role-option:hover {
            background: rgba(255, 255, 255, .14);
        }

        .role-option.active {
            background: #fff;
            border-color: #fff;
        }

        .role-option.active span {
            color: #0d2c6e
        }

        .role-option.active .r-icon {
            background: #0d2c6e;
            color: #fff;
        }

        .role-option .r-icon {
            width: 42px;
            height: 42px;
            background: rgba(255, 255, 255, .14);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: #fff;
            transition: .25s;
        }

        .role-option span {
            font-size: 15.5px;
            font-weight: 600;
            color: #e5ecfb;
            transition: .25s;
        }

        /* ===== RIGHT PANEL ===== */
        .right-panel {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .form-wrap {
            width: 100%;
            max-width: 420px;
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
            font-size: 30px;
            font-weight: 800;
            color: #151a2d;
            margin-bottom: 6px;
        }

        .form-wrap p.lead {
            color: #7a8199;
            font-size: 14.5px;
            margin-bottom: 26px;
        }

        .alert-success-box {
            background: #e8f7ee;
            color: #1e7e42;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 14px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .alert-error-box {
            background: #ffe5e5;
            color: #b30000;
            padding: 14px 16px;
            border-radius: 10px;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .alert-error-box ul {
            margin-left: 18px;
            margin-top: 6px
        }

        /* ===== FORM ===== */
        .login-form {
            display: none
        }

        .login-form.active {
            display: block
        }

        .group {
            margin-bottom: 18px
        }

        .group label {
            display: block;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 6px;
            color: #151a2d;
        }

        .input-icon {
            position: relative
        }

        .input-icon i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #a3aabd;
        }

        .group input {
            width: 100%;
            padding: 12px 14px 12px 40px;
            border-radius: 10px;
            border: 1px solid #e1e4ec;
            font-size: 15px;
            background: #fafbfd;
            transition: .2s;
        }

        .group input:focus {
            outline: none;
            border-color: #1a3a8f;
            box-shadow: 0 0 0 3px rgba(26, 58, 143, .12);
            background: #fff;
        }

        .captcha-wrap {
            display: flex;
            gap: 8px;
            margin-bottom: 10px
        }

        .captcha-box {
            flex: 1;
            background: #eef1ff;
            padding: 12px;
            border-radius: 10px;
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            letter-spacing: 3px;
            user-select: none;
        }

        .refresh {
            border: none;
            background: #8992a8;
            color: #fff;
            padding: 0 16px;
            border-radius: 10px;
            cursor: pointer;
        }

        .refresh:hover {
            background: #1a3a8f
        }

        .remember-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 22px;
            font-size: 14px;
            color: #555;
        }

        .error {
            color: #d32f2f;
            font-size: 12.5px;
            margin-top: 4px
        }

        button.login-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #1a3a8f, #2451b8);
            border: none;
            color: #fff;
            font-weight: 700;
            border-radius: 10px;
            font-size: 16px;
            cursor: pointer;
            transition: .2s;
        }

        button.login-btn:hover {
            background: #111c3f
        }

        .footer-note {
            text-align: center;
            margin-top: 24px;
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
            <div class="inner"><img src="{{ asset('assets/images/logo_white.png') }}" alt="" class="navbar-brand"
                    height="70" /></div>
        </div>
        <h1>BIRD (e-TIMS)</span></h1>


        <!-- ===== ROLE TABS (LEFT PANEL) ===== -->
        <div class="role-option active" data-target="admin-form">
            <div class="r-icon"><i class="fas fa-user-shield"></i></div>
            <span>Admin</span>
        </div>
        <div class="role-option" data-target="agency-form">
            <div class="r-icon"><i class="fas fa-building"></i></div>
            <span>Agency</span>
        </div>
        <div class="role-option" data-target="hostel-form">
            <div class="r-icon"><i class="fas fa-hotel"></i></div>
            <span>Hostel</span>
        </div>
        <div class="role-option" data-target="faculty-form">
            <div class="r-icon"><i class="fas fa-chalkboard-teacher"></i></div>
            <span>Faculty</span>
        </div>
    </div>

    <!-- ===== RIGHT PANEL ===== -->
    <div class="right-panel">
        <div class="form-wrap">

            <span class="badge-secure">SECURE LOGIN</span>
            <h2 id="form-title">Welcome Admin</h2>
            <p class="lead" id="form-subtitle">Enter your Admin credentials to continue</p>

            @if (session('success'))
                <div class="alert-success-box">
                    <i class="fas fa-circle-check"></i> {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert-error-box">
                    <strong>Please fix the following:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- ===== ADMIN FORM ===== -->
            <form id="admin-form" class="login-form active" action="{{ route('admin.login.submit') }}" method="POST"
                onsubmit="return validateCaptcha('admin')">
                @csrf
                <div class="group">
                    <label>Username</label>
                    <div class="input-icon">
                        <i class="fas fa-user"></i>
                        <input type="text" name="user_name" placeholder="Enter username">
                    </div>
                </div>
                <div class="group">
                    <label>Password</label>
                    <div class="input-icon">
                        <i class="fas fa-lock"></i>
                        <input type="hidden" name="password" id="admin_new_password">
                        <input type="password" id="admin_pass" placeholder="Enter your password">
                    </div>
                </div>
                <div class="group">
                    <label>Captcha</label>
                    <div class="captcha-wrap">
                        <div class="captcha-box" id="admin-captchaQuestion"></div>
                        <button type="button" class="refresh" onclick="generateCaptcha('admin')">↻</button>
                    </div>
                    <input type="text" id="admin-captchaInput" placeholder="Enter captcha">
                    <div id="admin-captchaError" class="error"></div>
                </div>
                <button class="login-btn">Login as Admin</button>
            </form>

            <!-- ===== AGENCY FORM ===== -->
            <!-- ===== AGENCY FORM ===== -->
            <form id="agency-form" class="login-form" action="{{ route('agency.check') }}" method="POST"
                onsubmit="return validateCaptcha('agency')">
                @csrf
                <div class="group">
                    <label>Username</label>
                    <div class="input-icon">
                        <i class="fas fa-user"></i>
                        <input type="text" name="user_name" placeholder="Enter username">
                    </div>
                </div>
                <div class="group">
                    <label>Password</label>
                    <div class="input-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" placeholder="Enter your password">
                    </div>
                </div>
                <div class="group">
                    <label>Captcha</label>
                    <div class="captcha-wrap">
                        <div class="captcha-box" id="agency-captchaQuestion"></div>
                        <button type="button" class="refresh" onclick="generateCaptcha('agency')">↻</button>
                    </div>
                    <input type="text" id="agency-captchaInput" placeholder="Enter captcha">
                    <div id="agency-captchaError" class="error"></div>
                </div>
                <button class="login-btn">Login as Agency</button>

                <div style="text-align:center; margin-top:14px; font-size:14px;">
                    Don't have an account?
                    <a href="{{ route('agency-registration') }}"
                        style="color:#1a3a8f; font-weight:600; text-decoration:none;">
                        Register here
                    </a>
                </div>
            </form>

            <!-- ===== HOSTEL FORM ===== -->
            <form id="hostel-form" class="login-form" action="{{ route('admin.login.submit') }}" method="POST"
                onsubmit="return validateCaptcha('hostel')">
                @csrf
                <div class="group">
                    <label>Username</label>
                    <div class="input-icon">
                        <i class="fas fa-user"></i>
                        <input type="text" name="user_name" placeholder="Enter username">
                    </div>
                </div>
                <div class="group">
                    <label>Password</label>
                    <div class="input-icon">
                        <i class="fas fa-lock"></i>
                        <input type="hidden" name="password" id="hostel_new_password">
                        <input type="password" id="hostel_pass" placeholder="Enter your password">
                    </div>
                </div>
                <div class="group">
                    <label>Captcha</label>
                    <div class="captcha-wrap">
                        <div class="captcha-box" id="hostel-captchaQuestion"></div>
                        <button type="button" class="refresh" onclick="generateCaptcha('hostel')">↻</button>
                    </div>
                    <input type="text" id="hostel-captchaInput" placeholder="Enter captcha">
                    <div id="hostel-captchaError" class="error"></div>
                </div>
                <button class="login-btn">Login as Hostel</button>
            </form>

            <!-- ===== FACULTY FORM ===== -->
            <form id="faculty-form" class="login-form" action="{{ route('faculty.submit') }}" method="POST">
                @csrf
                <div class="group">
                    <label>Username</label>
                    <div class="input-icon">
                        <i class="fas fa-user"></i>
                        <input type="text" name="username" id="faculty_username" placeholder="Enter username">
                    </div>
                </div>
                <div class="group">
                    <label>Password</label>
                    <div class="input-icon">
                        <i class="fas fa-lock"></i>
                        <input type="hidden" name="password" id="faculty_new_password">
                        <input type="password" id="faculty_pass" placeholder="Enter your password">
                    </div>
                </div>
                <div class="group">
                    <label>Captcha</label>
                    <div class="captcha-wrap">
                        <div class="captcha-box" id="faculty-captchaQuestion"></div>
                        <button type="button" class="refresh" onclick="generateCaptcha('faculty')">↻</button>
                    </div>
                    <input type="text" id="faculty-captchaInput" placeholder="Enter captcha">
                    <div id="faculty-captchaError" class="error"></div>
                </div>
                <button type="submit" class="login-btn">Login as Faculty</button>
            </form>

            <div class="footer-note">
                © 2026 <b>Bird</b> | Developed by
                <a href="https://www.businessinnovations.in" target="_blank">Business Innovations</a>
            </div>

        </div>
    </div>

    <script src="{{ url('/admin') }}/assets/js/core/jquery-3.7.1.min.js"></script>
    <script>
        // ===== ROLE (LEFT PANEL) SWITCHING =====
        const roleTitles = {
            'admin-form': {
                title: 'Welcome Admin',
                sub: 'Enter your Admin credentials to continue'
            },
            'agency-form': {
                title: 'Welcome Agency',
                sub: 'Enter your Agency credentials to continue'
            },
            'hostel-form': {
                title: 'Welcome Hostel',
                sub: 'Enter your Hostel credentials to continue'
            },
            'faculty-form': {
                title: 'Welcome Faculty',
                sub: 'Enter your Faculty credentials to continue'
            },
        };

        document.querySelectorAll('.role-option').forEach(opt => {
            opt.addEventListener('click', function() {
                document.querySelectorAll('.role-option').forEach(o => o.classList.remove('active'));
                document.querySelectorAll('.login-form').forEach(f => f.classList.remove('active'));

                this.classList.add('active');
                const target = this.dataset.target;
                document.getElementById(target).classList.add('active');

                document.getElementById('form-title').innerText = roleTitles[target].title;
                document.getElementById('form-subtitle').innerText = roleTitles[target].sub;
            });
        });

        // ===== CAPTCHA (all 4 forms) =====
        let captchaAnswers = {};

        function generateCaptcha(type) {
            let chars = "ABCDEFGHJKLMNPQRSTUVWXYZ23456789";
            let answer = "";
            for (let i = 0; i < 5; i++) {
                answer += chars[Math.floor(Math.random() * chars.length)];
            }
            captchaAnswers[type] = answer;
            document.getElementById(type + "-captchaQuestion").innerHTML = answer;
            document.getElementById(type + "-captchaInput").value = "";
            document.getElementById(type + "-captchaError").innerHTML = "";
        }
        generateCaptcha('admin');
        generateCaptcha('agency');
        generateCaptcha('hostel');
        generateCaptcha('faculty');

        function validateCaptcha(type) {
            let input = document.getElementById(type + "-captchaInput").value.trim().toUpperCase();
            if (input !== captchaAnswers[type]) {
                document.getElementById(type + "-captchaError").innerHTML = "Captcha incorrect";
                generateCaptcha(type);
                return false;
            }
            return true;
        }

        // ===== PASSWORD ENCRYPT (Admin & Hostel use AdminAuthController's encrypt route) =====
        $(document).ready(function() {
            $('#admin_pass').on('change', function() {
                let password = $(this).val();
                if (password.trim() === '') return;
                $.ajax({
                    url: "{{ route('encrypt_token') }}",
                    type: "GET",
                    data: {
                        token_id: password
                    },
                    success: function(response) {
                        $('#admin_new_password').val(response.token);
                    }
                });
            });

            $('#hostel_pass').on('change', function() {
                let password = $(this).val();
                if (password.trim() === '') return;
                $.ajax({
                    url: "{{ route('encrypt_token') }}",
                    type: "GET",
                    data: {
                        token_id: password
                    },
                    success: function(response) {
                        $('#hostel_new_password').val(response.token);
                    }
                });
            });
        });

        $('#faculty_pass').on('change', function() {
            let password = $(this).val();
            if (password.trim() === '') return;
            $.ajax({
                url: "{{ route('encrypt_token') }}",
                type: "GET",
                data: {
                    token_id: password
                },
                success: function(response) {
                    $('#faculty_new_password').val(response.token);
                }
            });
        });
    </script>

</body>

</html>
