<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="utf-8">

<meta http-equiv="X-UA-Compatible" content="IE=edge">

<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<meta name="description" content="Online Test">

<meta name="author" content="Online Test">

<meta name="keywords" content="Online Test">

<link rel="preconnect" href="https://fonts.gstatic.com">

<link href="https://demo-lms.ulbup.in/images/favicon.png" rel="shortcut icon" type="image/png">

<title>Online Test — BIRD Etims Portal</title>

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
        --accent-light: #e8eaf6;
        --accent-border: #c5cae9;
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
       NAVBAR
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
        overflow: hidden;
    }

    .navbar-logo img {
        height: 36px;
        width: auto;
        display: block;
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
       TIMER BAR (sticky below navbar)
    ======================== */
    .timer-bar {
        background: var(--card);
        border-bottom: 1px solid var(--border);
        position: sticky;
        top: 60px;
        z-index: 99;
        box-shadow: var(--shadow-1);
    }

    .timer-bar-inner {
        max-width: 1180px;
        margin: 0 auto;
        padding: 12px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
    }

    .timer-bar-left {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .timer-meta {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.82rem;
        color: var(--fg-secondary);
        font-weight: 500;
    }

    .timer-meta i {
        font-size: 0.75rem;
        color: var(--muted);
    }

    .timer-display {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 18px;
        border-radius: var(--radius);
        font-size: 0.92rem;
        font-weight: 700;
        font-variant-numeric: tabular-nums;
        letter-spacing: 0.5px;
        transition: background 0.3s ease, color 0.3s ease;
    }

    .timer-display--normal {
        background: var(--success-bg);
        color: #065f46;
        border: 1px solid var(--success-border);
    }

    .timer-display--warning {
        background: var(--warning-bg);
        color: #78350f;
        border: 1px solid var(--warning-border);
    }

    .timer-display--danger {
        background: var(--danger-bg);
        color: #7f1d1d;
        border: 1px solid var(--danger-border);
        animation: timerPulse 1s ease-in-out infinite;
    }

    @keyframes timerPulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }

    .timer-display i {
        font-size: 0.85rem;
    }

    .timer-progress {
        flex: 1;
        max-width: 200px;
        height: 6px;
        background: var(--border);
        border-radius: 3px;
        overflow: hidden;
    }

    .timer-progress-fill {
        height: 100%;
        border-radius: 3px;
        transition: width 1s linear, background 0.3s ease;
        background: var(--success);
    }

    .timer-progress-fill--warning {
        background: var(--warning);
    }

    .timer-progress-fill--danger {
        background: var(--danger);
    }

    /* ========================
       PAGE WRAP
    ======================== */
    .page-wrap {
        max-width: 900px;
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

    /* Page Title */
    .page-title-row {
        margin-bottom: 24px;
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

    /* ========================
       QUESTION NAV (side dots)
    ======================== */
    .exam-layout {
        display: grid;
        grid-template-columns: 1fr;
        gap: 24px;
    }

    /* ========================
       CARD
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
        display: grid;
        place-items: center;
        font-size: 0.82rem;
        flex-shrink: 0;
    }

    .card-head-icon--success {
        background: var(--success-bg);
        color: var(--success);
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
       ALERTS
    ======================== */
    .alert {
        padding: 14px 18px;
        border-radius: var(--radius);
        font-size: 0.85rem;
        margin-bottom: 20px;
        border-left: 4px solid;
        animation: slideDown 0.25s ease-out;
    }

    .alert-danger {
        background: var(--danger-bg);
        border-color: var(--danger);
        color: var(--danger);
    }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-8px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* ========================
       QUESTIONS
    ======================== */
    .questions-list {
        display: flex;
        flex-direction: column;
        gap: 0;
    }

    .question-item {
        padding: 28px 0;
        border-bottom: 1px solid var(--border);
    }

    .question-item:first-child {
        padding-top: 0;
    }

    .question-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .question-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: var(--accent);
        color: #fff;
        font-size: 0.78rem;
        font-weight: 700;
        flex-shrink: 0;
        margin-right: 12px;
        vertical-align: middle;
    }

    .question-text {
        font-size: 0.94rem;
        font-weight: 600;
        color: var(--fg);
        line-height: 1.55;
        display: inline;
        vertical-align: middle;
    }

    /* Options */
    .options-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin-top: 18px;
    }

    .option-label {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 14px 16px;
        border: 1px solid var(--border);
        border-radius: var(--radius);
        cursor: pointer;
        transition: all 0.15s ease;
        font-size: 0.88rem;
        color: var(--fg-secondary);
        line-height: 1.45;
        position: relative;
    }

    .option-label:hover {
        border-color: var(--accent-border);
        background: var(--accent-light);
    }

    .option-label:has(input:checked) {
        border-color: var(--accent);
        background: var(--accent-light);
        color: var(--fg);
        box-shadow: 0 0 0 1px var(--accent);
    }

    .option-label input[type="radio"] {
        appearance: none;
        -webkit-appearance: none;
        width: 20px;
        height: 20px;
        border: 2px solid var(--border-strong);
        border-radius: 50%;
        flex-shrink: 0;
        margin-top: 1px;
        position: relative;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .option-label input[type="radio"]:checked {
        border-color: var(--accent);
        background: var(--accent);
    }

    .option-label input[type="radio"]:checked::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #fff;
    }

    .option-label input[type="radio"]:focus-visible {
        outline: 2px solid var(--accent);
        outline-offset: 2px;
    }

    .option-letter {
        font-weight: 700;
        color: var(--muted);
        margin-right: 2px;
        flex-shrink: 0;
    }

    .option-label:has(input:checked) .option-letter {
        color: var(--accent);
    }

    /* ========================
       QUESTION NAV PANEL
    ======================== */
    .question-nav-card {
        position: sticky;
        top: 130px;
    }

    .question-nav-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-bottom: 16px;
    }

    .question-nav-dot {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        border: 1px solid var(--border-strong);
        background: var(--card);
        display: grid;
        place-items: center;
        font-size: 0.78rem;
        font-weight: 600;
        color: var(--muted);
        text-decoration: none;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .question-nav-dot:hover {
        border-color: var(--accent-border);
        color: var(--accent);
    }

    .question-nav-dot--answered {
        background: var(--accent);
        border-color: var(--accent);
        color: #fff;
    }

    .question-nav-dot--current {
        border-color: var(--accent);
        color: var(--accent);
        box-shadow: 0 0 0 2px var(--accent-light);
    }

    .nav-legend {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
    }

    .nav-legend-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.75rem;
        color: var(--muted);
    }

    .nav-legend-swatch {
        width: 14px;
        height: 14px;
        border-radius: 4px;
        border: 1px solid var(--border-strong);
    }

    .nav-legend-swatch--answered {
        background: var(--accent);
        border-color: var(--accent);
    }

    .nav-legend-swatch--unanswered {
        background: var(--card);
    }

    .nav-legend-swatch--current {
        border-color: var(--accent);
        box-shadow: 0 0 0 2px var(--accent-light);
    }

    /* ========================
       SUBMIT AREA
    ======================== */
    .submit-area {
        margin-top: 32px;
        padding-top: 24px;
        border-top: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
    }

    .submit-info {
        font-size: 0.84rem;
        color: var(--muted);
    }

    .submit-info strong {
        color: var(--fg-secondary);
    }

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
    }

    /* ========================
       WARNING CALLOUT
    ======================== */
    .callout {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 14px 16px;
        border-radius: var(--radius);
        margin-bottom: 24px;
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
    @media (min-width: 920px) {
        .exam-layout {
            grid-template-columns: 1fr 220px;
            align-items: start;
        }
    }

    @media (max-width: 919px) {
        .question-nav-card {
            position: static;
            order: -1;
        }

        .timer-progress {
            display: none;
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

        .timer-bar-inner {
            padding: 10px 16px;
            flex-wrap: wrap;
        }

        .timer-bar-left {
            gap: 12px;
        }

        .timer-meta {
            font-size: 0.76rem;
        }

        .timer-display {
            font-size: 0.84rem;
            padding: 6px 14px;
        }

        .page-title-row h1 {
            font-size: 1.25rem;
        }

        .card-body {
            padding: 20px 16px;
        }

        .card-head {
            padding: 14px 16px;
        }

        .options-grid {
            grid-template-columns: 1fr;
        }

        .option-label {
            padding: 12px 14px;
            font-size: 0.85rem;
        }

        .submit-area {
            flex-direction: column;
            align-items: stretch;
        }

        .btn-primary {
            width: 100%;
            justify-content: center;
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

        .timer-bar-left {
            flex-wrap: wrap;
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
                <img src="{{ asset('assets/images/logo.png') }}" alt="Logo">
            </div>
            <div class="navbar-text">BIRD Etims Portal<span>/ Online Test</span></div>
        </a>
        <div class="navbar-status">
            <div class="status-dot"></div>
            Exam In Progress
        </div>
    </div>
</header>

<!-- Timer Bar -->
<div class="timer-bar">
    <div class="timer-bar-inner">
        <div class="timer-bar-left">
            <div class="timer-meta">
                <i class="fas fa-clock"></i>
                Duration: {{$basic_details->duration}} minutes
            </div>
            <div class="timer-meta">
                <i class="fas fa-list-ol"></i>
                <span id="answeredCount">0</span>/{{ $questiones->count() }} answered
            </div>
            <div class="timer-progress">
                <div class="timer-progress-fill" id="timerProgress" style="width: 100%;"></div>
            </div>
        </div>
        <div class="timer-display timer-display--normal" id="timer">
            <i class="fas fa-hourglass-half"></i>
            <span id="timerText">--:--</span>
        </div>
    </div>
</div>

@if(session('error'))
    <div style="max-width:900px;margin:20px auto 0;padding:0 24px;">
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    </div>
@endif

@php $action = 2; @endphp

<main class="page-wrap">

    <!-- Breadcrumb -->
    <nav class="breadcrumb" aria-label="Breadcrumb">
        <a href="#">Dashboard</a>
        <i class="fas fa-chevron-right"></i>
        <a href="#">Programmes</a>
        <i class="fas fa-chevron-right"></i>
        <span class="current">Online Test</span>
    </nav>

    <!-- Title -->
    <div class="page-title-row">
        <div>
            <h1>Online Assessment</h1>
            <p class="subtitle">Read each question carefully and select the correct option.</p>
        </div>
    </div>

    <!-- Warning -->
    <div class="callout callout-warning" id="warningCallout">
        <i class="fas fa-triangle-exclamation"></i>
        <p><strong>Do not refresh or close this page.</strong> Your answers are tracked in real-time. If the timer expires, the form will be submitted automatically with your current responses.</p>
        <button class="callout-close" onclick="toggleWarning()" aria-label="Close warning">
            <i class="fas fa-xmark"></i>
        </button>
    </div>
   

    <div class="exam-layout">

        <!-- Questions Column -->
        <div class="card">
            <div class="card-head">
                <div class="card-head-left">
                    <div class="card-head-icon card-head-icon--success">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <span class="card-head-title">Questions — {{ $questiones->count() }} Items</span>
                </div>
            </div>

            <div class="card-body">
                <form action="{{url('/')}}/onlinetest" id="yourFormId" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="programme_id" value="{{$Participant->programme_id}}">
                    <input type="hidden" name="participants_id" value="{{$Participant->id}}">
                    <input type="hidden" name="type" value="{{$type}}">

                    <div class="questions-list">
                        @php $i = 1; @endphp
                        @foreach ($questiones as $questions)
                        <div class="question-item" id="question-{{ $i }}">
                            <div class="question-header" style="margin-bottom:4px;">
                                <span class="question-number">{{ $i }}</span>
                                <span class="question-text">{{ $questions->question_title }}</span>
                            </div>
                            <input type="hidden" name="question_{{$i}}" value="{{$questions->id}}">
                            <input type="hidden" name="cauntt[]" value="{{$i}}">

                            <div class="options-grid" id="options-{{ $i }}">
                                <label class="option-label">
                                    <input type="radio" name="option_{{$i}}" value="A" onchange="markAnswered({{ $i }})">
                                    <span><span class="option-letter">A)</span> {{ $questions->option_A }}</span>
                                </label>
                                <label class="option-label">
                                    <input type="radio" name="option_{{$i}}" value="B" onchange="markAnswered({{ $i }})">
                                    <span><span class="option-letter">B)</span> {{ $questions->option_B }}</span>
                                </label>
                                <label class="option-label">
                                    <input type="radio" name="option_{{$i}}" value="C" onchange="markAnswered({{ $i }})">
                                    <span><span class="option-letter">C)</span> {{ $questions->option_C }}</span>
                                </label>
                                <label class="option-label">
                                    <input type="radio" name="option_{{$i}}" value="D" onchange="markAnswered({{ $i }})">
                                    <span><span class="option-letter">D)</span> {{ $questions->option_D }}</span>
                                </label>
                            </div>
                        </div>
                        @php $i++; @endphp
                        @endforeach
                    </div>

                    <!-- Submit -->
                    <div class="submit-area">
                        <div class="submit-info">
                            <strong>{{ $questions->count() }}</strong> questions &middot; All responses are saved automatically
                        </div>
                        <button type="submit" class="btn-primary">
                            Submit Assessment
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Question Nav Sidebar -->
        <aside class="card question-nav-card">
            <div class="card-head">
                <div class="card-head-left">
                    <div class="card-head-icon" style="background:var(--accent-light);color:var(--accent);">
                        <i class="fas fa-th"></i>
                    </div>
                    <span class="card-head-title">Navigator</span>
                </div>
            </div>
            <div class="card-body" style="padding:16px;">
                <div class="question-nav-grid" id="questionNav">
                    @php $j = 1; @endphp
                    @foreach ($questions as $questions)
                    <a href="#question-{{ $j }}" class="question-nav-dot" id="navDot-{{ $j }}">{{ $j }}</a>
                    @php $j++; @endphp
                    @endforeach
                </div>
                <div class="nav-legend">
                    <div class="nav-legend-item">
                        <div class="nav-legend-swatch nav-legend-swatch--answered"></div>
                        Answered
                    </div>
                    <div class="nav-legend-item">
                        <div class="nav-legend-swatch nav-legend-swatch--unanswered"></div>
                        Unanswered
                    </div>
                    <div class="nav-legend-item">
                        <div class="nav-legend-swatch nav-legend-swatch--current"></div>
                        Current
                    </div>
                </div>
            </div>
        </aside>

    </div>
</main>

<!-- Footer -->
<footer class="page-footer">
    <span>BIRD Etims Portal &mdash; Responses are encrypted</span>
    <div class="footer-links">
        <a href="#">Help Center</a>
        <a href="#">Privacy Policy</a>
    </div>
</footer>

<script>


 function toggleWarning() {
        const callout = document.getElementById('warningCallout');
        const toggle = document.getElementById('warningToggle');

        if (callout.classList.contains('callout--collapsed')) {
            callout.classList.remove('callout--collapsed');
            toggle.classList.remove('callout-toggle--visible');
            toggle.classList.remove('callout-toggle--open');
        } else {
            callout.classList.add('callout--collapsed');
            toggle.classList.add('callout-toggle--visible');
            toggle.classList.add('callout-toggle--open');
        }
    }
    // ========================
    // TIMER
    // ========================
    const totalQuestions = {{ $questiones->count() }};
    const examDuration = parseInt('{{ $basic_details->duration }}') || 30;

    function startExamTimer() {
        const timerDisplay = document.getElementById('timer');
        const timerText = document.getElementById('timerText');
        const timerProgress = document.getElementById('timerProgress');
        const startTime = new Date().getTime();
        const endTime = startTime + examDuration * 60 * 1000;
        const totalMs = examDuration * 60 * 1000;

        const timerInterval = setInterval(function () {
            const now = new Date().getTime();
            const remaining = endTime - now;
            const minutes = Math.floor((remaining % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((remaining % (1000 * 60)) / 1000);

            const mStr = String(minutes).padStart(2, '0');
            const sStr = String(seconds).padStart(2, '0');
            timerText.textContent = mStr + ':' + sStr;

            // Progress bar
            const pct = Math.max(0, (remaining / totalMs) * 100);
            timerProgress.style.width = pct + '%';

            // Color states
            timerDisplay.classList.remove('timer-display--normal', 'timer-display--warning', 'timer-display--danger');
            timerProgress.classList.remove('timer-progress-fill--warning', 'timer-progress-fill--danger');

            if (remaining <= 60000) {
                // Last minute
                timerDisplay.classList.add('timer-display--danger');
                timerProgress.classList.add('timer-progress-fill--danger');
            } else if (remaining <= 300000) {
                // Last 5 minutes
                timerDisplay.classList.add('timer-display--warning');
                timerProgress.classList.add('timer-progress-fill--warning');
            } else {
                timerDisplay.classList.add('timer-display--normal');
            }

            if (remaining <= 0) {
                clearInterval(timerInterval);
                timerText.textContent = "00:00";
                timerDisplay.classList.remove('timer-display--normal', 'timer-display--warning');
                timerDisplay.classList.add('timer-display--danger');
                timerProgress.style.width = '0%';
                document.getElementById('yourFormId').submit();
            }
        }, 1000);
    }

    // ========================
    // ANSWER TRACKING + NAV
    // ========================
    const answeredSet = new Set();

    function markAnswered(qNum) {
        answeredSet.add(qNum);
        updateNav();
        document.getElementById('answeredCount').textContent = answeredSet.size;
    }

    function updateNav() {
        for (let i = 1; i <= totalQuestions; i++) {
            const dot = document.getElementById('navDot-' + i);
            if (!dot) continue;
            dot.classList.remove('question-nav-dot--answered', 'question-nav-dot--current');
            if (answeredSet.has(i)) {
                dot.classList.add('question-nav-dot--answered');
            }
        }
    }

    // Highlight current question on scroll
    function highlightCurrentQuestion() {
        const items = document.querySelectorAll('.question-item');
        let currentIndex = 1;
        const scrollY = window.scrollY + 180;

        items.forEach(function(item, idx) {
            if (item.offsetTop <= scrollY) {
                currentIndex = idx + 1;
            }
        });

        for (let i = 1; i <= totalQuestions; i++) {
            const dot = document.getElementById('navDot-' + i);
            if (!dot) continue;
            if (i === currentIndex && !answeredSet.has(i)) {
                dot.classList.add('question-nav-dot--current');
            } else if (!answeredSet.has(i)) {
                dot.classList.remove('question-nav-dot--current');
            }
        }
    }

    window.addEventListener('scroll', highlightCurrentQuestion, { passive: true });

    // Smooth scroll for nav dots
    document.querySelectorAll('.question-nav-dot').forEach(function(dot) {
        dot.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const target = document.querySelector(targetId);
            if (target) {
                const y = target.offsetTop - 140;
                window.scrollTo({ top: y, behavior: 'smooth' });
            }
        });
    });

    // ========================
    // INIT
    // ========================
    window.onload = function() {
        startExamTimer();
        highlightCurrentQuestion();
    };

        // ========================
    // WARNING TOGGLE
    // ========================
   
</script>

</body>

</html>