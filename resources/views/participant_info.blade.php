<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Participant Exam Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/fontawesome/css/all.min.css') }}">
    <style>
        :root {
            --bg: #f5f6fa;
            --fg: #1e293b;
            --fg-secondary: #475569;
            --muted: #94a3b8;
            --accent: #1A237E;
            --accent-hover: #1f277e;
            --accent-light: #e0f2fe;
            --accent-border: #bae6fd;
            --card: #ffffff;
            --border: #e2e8f0;
            --border-strong: #cbd5e1;
            --danger: #dc2626;
            --danger-bg: #fef2f2;
            --danger-border: #fecaca;
            --success: #059669;
            --success-bg: #ecfdf5;
            --success-border: #a7f3d0;
            --warning: #d97706;
            --warning-bg: #fffbeb;
            --warning-border: #fde68a;
            --radius: 10px;
            --radius-lg: 14px;
            --shadow-1: 0 1px 2px rgba(0,0,0,0.04);
            --shadow-2: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
            --shadow-3: 0 4px 6px -1px rgba(0,0,0,0.06), 0 2px 4px -2px rgba(0,0,0,0.04);
        }

        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg);
            color: var(--fg);
            min-height: 100vh;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        /* ========================
           HEADER / NAVBAR
        ======================== */
        .navbar {
            background: var(--card);
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar-inner {
            max-width: 1180px;
            margin: 0 auto;
            padding: 0 24px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .navbar-logo {
            width: 36px;
            height: 36px;
           
            border-radius: 8px;
            display: grid;
            place-items: center;
            color: #fff;
            font-size: 15px;
        }

         .navbar-logo img{
            height: 36px;
        }

        .navbar-text {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--fg);
            letter-spacing: -0.3px;
        }

        .navbar-text span {
            color: var(--muted);
            font-weight: 400;
            margin-left: 4px;
        }

        .navbar-status {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.8rem;
            color: var(--fg-secondary);
            font-weight: 500;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            background: var(--success);
            border-radius: 50%;
            position: relative;
        }

        .status-dot::after {
            content: '';
            position: absolute;
            inset: -3px;
            border-radius: 50%;
            border: 1.5px solid var(--success);
            opacity: 0.3;
        }

        /* ========================
           MAIN LAYOUT
        ======================== */
        .page-wrap {
            max-width: 1180px;
            margin: 0 auto;
            padding: 28px 24px 56px;
        }

        /* Breadcrumb */
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.8rem;
            color: var(--muted);
            margin-bottom: 24px;
        }

        .breadcrumb a {
            color: var(--accent);
            text-decoration: none;
            font-weight: 500;
        }

        .breadcrumb a:hover {
            text-decoration: underline;
        }

        .breadcrumb i {
            font-size: 0.6rem;
        }

        .breadcrumb .current {
            color: var(--fg-secondary);
            font-weight: 500;
        }

        /* Page Title Row */
        .page-title-row {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            margin-bottom: 24px;
            gap: 16px;
        }

        .page-title-row h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--fg);
            letter-spacing: -0.5px;
            line-height: 1.3;
        }

        .page-title-row .subtitle {
            font-size: 0.88rem;
            color: var(--fg-secondary);
            margin-top: 4px;
            font-weight: 400;
        }

        .timestamp {
            font-size: 0.78rem;
            color: var(--muted);
            white-space: nowrap;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .timestamp i {
            font-size: 0.72rem;
        }

        /* Content Grid */
        .content-grid {
            display: grid;
            grid-template-columns: 360px 1fr;
            gap: 24px;
            align-items: start;
        }

        /* ========================
           CARD BASE
        ======================== */
        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-1);
            overflow: hidden;
        }

        .card-head {
            padding: 18px 24px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .card-head-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-head-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: var(--accent-light);
            color: var(--accent);
            display: grid;
            place-items: center;
            font-size: 0.82rem;
            flex-shrink: 0;
        }

        .card-head-title {
            font-size: 0.88rem;
            font-weight: 700;
            color: var(--fg);
        }

        .card-body {
            padding: 24px;
        }

        /* ========================
           PROFILE CARD
        ======================== */
        .profile-top {
            padding: 24px 24px 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            border-bottom: 1px solid var(--border);
        }

        .profile-avatar {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: var(--accent-light);
            color: var(--accent);
            display: grid;
            place-items: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .profile-identity {
            min-width: 0;
        }

        .profile-identity h3 {
            font-size: 1rem;
            font-weight: 700;
            color: var(--fg);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .profile-identity .role {
            font-size: 0.82rem;
            color: var(--muted);
            font-weight: 400;
            margin-top: 1px;
        }

        /* Programme Badge */
        .programme-badge {
            margin: 16px 24px 0;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 14px;
            background: var(--accent-light);
            border: 1px solid var(--accent-border);
            border-radius: var(--radius);
        }

        .programme-badge i {
            color: var(--accent);
            font-size: 0.85rem;
            flex-shrink: 0;
        }

        .programme-badge .label {
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--accent);
            display: block;
        }

        .programme-badge .value {
            font-size: 0.88rem;
            font-weight: 600;
            color: var(--fg);
            display: block;
            margin-top: 1px;
            line-height: 1.3;
        }

        /* Detail List */
        .detail-list {
            padding: 20px 24px;
        }

        .detail-row {
            display: flex;
            align-items: center;
            padding: 11px 0;
            border-bottom: 1px solid var(--border);
            gap: 12px;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-row .icon {
            width: 30px;
            height: 30px;
            border-radius: 6px;
            background: var(--bg);
            display: grid;
            place-items: center;
            font-size: 0.72rem;
            color: var(--muted);
            flex-shrink: 0;
        }

        .detail-row .info {
            flex: 1;
            min-width: 0;
        }

        .detail-row .info .label {
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            color: var(--muted);
        }

        .detail-row .info .value {
            font-size: 0.88rem;
            font-weight: 500;
            color: var(--fg);
            margin-top: 1px;
            word-break: break-word;
        }

        /* ========================
           EXAM CARD
        ======================== */
        .exam-card .card-head-icon {
            background: var(--success-bg);
            color: var(--success);
        }

        /* Info Banner */
        .info-banner {
            padding: 16px 20px;
            background: var(--bg);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .info-banner i {
            color: var(--accent);
            font-size: 0.9rem;
            margin-top: 2px;
            flex-shrink: 0;
        }

        .info-banner p {
            font-size: 0.84rem;
            color: var(--fg-secondary);
            line-height: 1.55;
        }

        .info-banner strong {
            color: var(--fg);
        }

        /* Instructions */
        .section-label {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: var(--muted);
            margin-bottom: 14px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--border);
        }

        .rules-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 28px;
        }

        .rules-list li {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            font-size: 0.86rem;
            color: var(--fg-secondary);
            line-height: 1.5;
        }

        .rules-list li .rule-icon {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: var(--accent-light);
            color: var(--accent);
            font-size: 0.6rem;
            display: grid;
            place-items: center;
            flex-shrink: 0;
            margin-top: 2px;
            font-weight: 700;
        }

        /* Warning Callout */
        .callout {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 14px 16px;
            border-radius: var(--radius);
            margin-bottom: 28px;
            font-size: 0.84rem;
            line-height: 1.5;
        }

        .callout-warning {
            background: var(--warning-bg);
            border: 1px solid var(--warning-border);
            color: #92400e;
        }

        .callout-warning i {
            color: var(--warning);
            margin-top: 2px;
            flex-shrink: 0;
        }

        .callout-warning strong {
            color: #78350f;
        }

        /* Divider */
        .divider {
            border: none;
            border-top: 1px solid var(--border);
            margin: 0 0 24px;
        }

        /* Start Section */
        .start-section {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
        }

        .start-text h4 {
            font-size: 1rem;
            font-weight: 700;
            color: var(--fg);
            margin-bottom: 4px;
        }

        .start-text p {
            font-size: 0.84rem;
            color: var(--muted);
        }

        .start-text p strong {
            color: var(--fg-secondary);
        }

        /* Primary Button */
        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 32px;
            background: var(--accent);
            color: #fff;
            font-family: inherit;
            font-size: 0.9rem;
            font-weight: 600;
            border: 1px solid transparent;
            border-radius: var(--radius);
            text-decoration: none;
            cursor: pointer;
            transition: background 0.15s ease, box-shadow 0.15s ease, transform 0.1s ease;
            box-shadow: var(--shadow-2);
            white-space: nowrap;
            flex-shrink: 0;
        }

        .btn-primary:hover {
            background: var(--accent-hover);
            box-shadow: var(--shadow-3);
        }

        .btn-primary:active {
            transform: scale(0.98);
        }

        .btn-primary:focus-visible {
            outline: 2px solid var(--accent);
            outline-offset: 2px;
        }

        .btn-primary i {
            font-size: 0.78rem;
            transition: transform 0.15s ease;
        }

        .btn-primary:hover i {
            transform: translateX(2px);
        }

        /* ========================
           ALERTS
        ======================== */
        .alert {
            padding: 14px 18px;
            border-radius: var(--radius);
            font-size: 0.85rem;
            margin: 20px 24px 0;
            border-left: 4px solid;
            animation: slideDown 0.25s ease-out;
        }

        .alert-danger {
            background: var(--danger-bg);
            border-color: var(--danger);
            color: var(--danger);
        }

        .alert-danger ul {
            margin: 0;
            padding-left: 16px;
        }

        .alert-danger li {
            margin-bottom: 2px;
        }

        .alert-danger li:last-child {
            margin-bottom: 0;
        }

        .alert-success {
            background: var(--success-bg);
            border-color: var(--success);
            color: var(--success);
            font-weight: 500;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ========================
           FOOTER
        ======================== */
        .page-footer {
            max-width: 1180px;
            margin: 0 auto;
            padding: 20px 24px;
            border-top: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.76rem;
            color: var(--muted);
        }

        .page-footer a {
            color: var(--accent);
            text-decoration: none;
            font-weight: 500;
        }

        .page-footer a:hover {
            text-decoration: underline;
        }

        .footer-links {
            display: flex;
            gap: 20px;
        }

        /* ========================
           RESPONSIVE
        ======================== */
        @media (max-width: 920px) {
            .content-grid {
                grid-template-columns: 1fr;
            }

            .profile-card {
                order: 1;
            }

            .exam-card {
                order: 2;
            }

            .page-title-row {
                flex-direction: column;
                align-items: flex-start;
            }

            .start-section {
                flex-direction: column;
                align-items: flex-start;
            }

            .btn-primary {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 600px) {
            .page-wrap {
                padding: 20px 16px 48px;
            }

            .navbar-inner {
                padding: 0 16px;
            }

            .navbar-text span {
                display: none;
            }

            .page-title-row h1 {
                font-size: 1.25rem;
            }

            .profile-top {
                padding: 20px;
            }

            .programme-badge {
                margin: 14px 20px 0;
            }

            .detail-list {
                padding: 16px 20px;
            }

            .card-body {
                padding: 20px;
            }

            .card-head {
                padding: 14px 20px;
            }

            .info-banner {
                padding: 14px 16px;
            }

            .page-footer {
                flex-direction: column;
                gap: 8px;
                text-align: center;
            }
        }

        @media (max-width: 400px) {
            .navbar-status {
                display: none;
            }

            .breadcrumb {
                font-size: 0.75rem;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<header class="navbar">
    <div class="navbar-inner">
        <a href="#" class="navbar-brand">
            <div class="navbar-logo">
               
<img src="{{ asset('assets/images/logo.png') }}">
            </div>
            <div class="navbar-text">BIRD Etims Portal<span>/ Assessment</span></div>
        </a>
        <div class="navbar-status">
            <div class="status-dot"></div>
            Session Active
        </div>
    </div>
</header>

<!-- Main -->
<main class="page-wrap">

    <!-- Breadcrumb -->
    <nav class="breadcrumb" aria-label="Breadcrumb">
        <a href="#">Dashboard</a>
        <i class="fas fa-chevron-right"></i>
        <a href="#">Programmes</a>
        <i class="fas fa-chevron-right"></i>
        <span class="current">Exam Test</span>
    </nav>

    <!-- Title Row -->
    <div class="page-title-row">
        <div>
            <h1>Participant Assessment</h1>
            <p class="subtitle">Verify your details and begin the examination.</p>
        </div>
        <div class="timestamp">
            <i class="far fa-clock"></i>
            {{ date('d M Y, h:i A') }}
        </div>
    </div>

    <!-- Content Grid -->
    <div class="content-grid">

        <!-- Profile Card -->
        <aside class="card profile-card">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="profile-top">
                <div class="profile-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div class="profile-identity">
                    <h3>{{ $participant->title }} {{ $participant->name }}</h3>
                    <div class="role">{{ $participant->designation }}</div>
                </div>
            </div>

            <div class="programme-badge">
                <i class="fas fa-book-open"></i>
                <div>
                    <span class="label">Programme</span>
                    <span class="value">{{ $participant->programme->title }}</span>
                </div>
            </div>

            <div class="detail-list">
                <div class="detail-row">
                    <div class="icon"><i class="fas fa-envelope"></i></div>
                    <div class="info">
                        <div class="label">Email Address</div>
                        <div class="value">{{ $participant->email }}</div>
                    </div>
                </div>
                <div class="detail-row">
                    <div class="icon"><i class="fas fa-phone"></i></div>
                    <div class="info">
                        <div class="label">Phone Number</div>
                        <div class="value">{{ $participant->phone }}</div>
                    </div>
                </div>
                <div class="detail-row">
                    <div class="icon"><i class="fas fa-building"></i></div>
                    <div class="info">
                        <div class="label">City</div>
                        <div class="value">{{ $participant->city }}</div>
                    </div>
                </div>
                <div class="detail-row">
                    <div class="icon"><i class="fas fa-map-marker-alt"></i></div>
                    <div class="info">
                        <div class="label">State</div>
                        <div class="value">{{ $participant->state }}</div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Exam Card -->
        <section class="card exam-card">
            <div class="card-head">
                <div class="card-head-left">
                    <div class="card-head-icon">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                    <span class="card-head-title">Examination Details</span>
                </div>
            </div>

            <div class="info-banner">
                <i class="fas fa-circle-info"></i>
                <p>You are about to start the assessment for <strong>{{ $programmes->title }}</strong>. Please review the following guidelines before proceeding.</p>
            </div>

            <div class="card-body">
                <div class="section-label">General Instructions</div>
                <ul class="rules-list">
                    <li>
                        <span class="rule-icon"><i class="fas fa-check"></i></span>
                        Ensure a stable internet connection is available for the entire duration of the test.
                    </li>
                    <li>
                        <span class="rule-icon"><i class="fas fa-check"></i></span>
                        Do not refresh, close, or navigate away from the browser window during the assessment.
                    </li>
                    <li>
                        <span class="rule-icon"><i class="fas fa-check"></i></span>
                        Answer all questions within the allocated time. Unanswered questions will not be scored.
                    </li>
                    <li>
                        <span class="rule-icon"><i class="fas fa-check"></i></span>
                        Responses are saved automatically. No manual submission is required upon completion.
                    </li>
                    <li>
                        <span class="rule-icon"><i class="fas fa-check"></i></span>
                        Any form of unfair practice will result in immediate disqualification.
                    </li>
                </ul>

                <div class="callout callout-warning">
                    <i class="fas fa-triangle-exclamation"></i>
                    <p><strong>Note:</strong> The timer starts immediately after you click the button below. Ensure you are fully prepared before proceeding.</p>
                </div>

                <hr class="divider">

                <div class="start-section">
                    <div class="start-text">
                        <h4>Ready to begin?</h4>
                        <p>Assessment: <strong>{{ $programmes->title }}</strong></p>
                    </div>
                    <form method="POST" action="{{ route('feedback.response.store') }}" style="display:inline;">
                        @csrf
                        <input type="hidden" name="participant_id" value="{{ $participant->id }}">
                        <input type="hidden" name="programme_id" value="{{ $participant->programme->id }}">
                        <a href="{{ url('/') }}/exam/{{ $participant->Token_id }}?type={{ $type }}" class="btn-primary" role="button">
                            Start Assessment
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </form>
                </div>
            </div>
        </section>

    </div>
</main>

<!-- Footer -->
<footer class="page-footer">
    <span>Etims Portal &mdash; Responses are encrypted </span>
    <div class="footer-links">
        <a href="#">Help Center</a>
        <a href="#">Privacy Policy</a>
    </div>
</footer>

</body>
</html>