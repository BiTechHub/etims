<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="Feedback Form">
<meta name="author" content="Feedback Form">
<meta name="keywords" content="Feedback Form">
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://demo-lms.ulbup.in/images/favicon.png" rel="shortcut icon" type="image/png">
<title>Feedback Form — BIRD Etims Portal</title>
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

    .progress-bar-section {
        background: var(--card);
        border-bottom: 1px solid var(--border);
        position: sticky;
        top: 60px;
        z-index: 99;
        box-shadow: var(--shadow-1);
    }

    .progress-bar-inner {
        max-width: 1180px;
        margin: 0 auto;
        padding: 12px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
    }

    .progress-bar-left {
        display: flex;
        align-items: center;
        gap: 20px;
        flex: 1;
    }

    .progress-meta {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.82rem;
        color: var(--fg-secondary);
        font-weight: 500;
        white-space: nowrap;
    }

    .progress-meta i {
        font-size: 0.75rem;
        color: var(--muted);
    }

    .progress-track {
        flex: 1;
        max-width: 320px;
        height: 8px;
        background: var(--border);
        border-radius: 4px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        border-radius: 4px;
        background: var(--accent);
        transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        width: 0%;
    }

    .progress-fill--complete {
        background: var(--success);
    }

    .progress-percent {
        font-size: 0.88rem;
        font-weight: 700;
        color: var(--accent);
        font-variant-numeric: tabular-nums;
        min-width: 40px;
        text-align: right;
    }

    .progress-percent--complete {
        color: var(--success);
    }

    .page-wrap {
        max-width: 900px;
        margin: 0 auto;
        padding: 28px 24px 56px;
    }

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

    .card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-1);
        overflow: hidden;
    }

    .card-body {
        padding: 24px;
    }

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

    .callout {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 14px 16px;
        border-radius: var(--radius);
        margin-bottom: 24px;
        font-size: 0.84rem;
        line-height: 1.5;
        position: relative;
    }

    .callout-warning {
        background: var(--warning-bg);
        border: 1px solid var(--warning-border);
        color: #92400e;
    }

    .callout-warning i:first-child {
        color: var(--warning);
        margin-top: 2px;
        flex-shrink: 0;
    }

    .callout-warning strong {
        color: #78350f;
    }

    .callout-close {
        position: absolute;
        top: 10px;
        right: 12px;
        background: none;
        border: none;
        color: #92400e;
        cursor: pointer;
        padding: 4px;
        font-size: 0.85rem;
        opacity: 0.6;
        transition: opacity 0.15s ease;
        line-height: 1;
    }

    .callout-close:hover {
        opacity: 1;
    }

    .feedback-section-header {
        padding: 20px 0 14px;
        margin-top: 4px;
        border-bottom: 2px solid var(--accent);
        margin-bottom: 20px;
    }

    .feedback-section-header:first-child {
        margin-top: 0;
    }

    .feedback-section-header h2 {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--accent);
        margin-bottom: 3px;
        letter-spacing: -0.3px;
    }

    .feedback-section-header p {
        font-size: 0.8rem;
        color: var(--muted);
        font-style: italic;
    }

    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        margin: 0 -6px 24px;
        padding: 0 6px;
        border-radius: var(--radius);
    }

    .feedback-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 0.85rem;
        min-width: 580px;
        border: 1px solid var(--border);
        border-radius: var(--radius);
        overflow: hidden;
    }

    .feedback-table thead th {
        background: var(--accent);
        color: #fff;
        padding: 12px 14px;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        text-align: center;
        white-space: nowrap;
        border: none;
    }

    .feedback-table thead th:first-child {
        width: 60px;
    }

    .feedback-table tbody {
        background: var(--card);
    }

    .feedback-table tbody tr:last-child td {
        border-bottom: none;
    }

    .feedback-table .sr-cell {
        padding: 0 12px;
        text-align: center;
        font-weight: 700;
        font-size: 0.85rem;
        color: var(--accent);
        background: var(--accent-light);
        border-bottom: 1px solid var(--border);
        border-right: 1px solid var(--border);
        vertical-align: middle;
    }

    .feedback-table .question-row {
        padding: 14px 16px;
        text-align: left;
        font-weight: 600;
        color: var(--fg);
        background: #fafbfc;
        border-bottom: 1px solid var(--border);
        line-height: 1.5;
        font-size: 0.87rem;
    }

    .feedback-table .question-row .session-meta {
        display: block;
        margin-top: 4px;
        font-size: 0.78rem;
        font-weight: 400;
        color: var(--muted);
        line-height: 1.5;
    }

    .feedback-table .question-row .session-meta strong {
        color: var(--fg-secondary);
        font-weight: 500;
    }

    .feedback-table .option-row td {
        padding: 12px 14px;
        text-align: center;
        border-bottom: 1px solid var(--border);
        vertical-align: middle;
        transition: background 0.15s ease;
    }

    .feedback-table .option-row:hover td {
        background: var(--accent-light);
    }

    .rating-radio {
        appearance: none;
        -webkit-appearance: none;
        width: 22px;
        height: 22px;
        border: 2px solid var(--border-strong);
        border-radius: 50%;
        cursor: pointer;
        transition: all 0.15s ease;
        position: relative;
        vertical-align: middle;
    }

    .rating-radio:hover {
        border-color: var(--accent-border);
    }

    .rating-radio:checked {
        border-color: var(--accent);
        background: var(--accent);
    }

    .rating-radio:checked::after {
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

    .rating-radio:focus-visible {
        outline: 2px solid var(--accent);
        outline-offset: 2px;
    }

    .mcq-question-item {
        padding: 24px 0;
        border-bottom: 1px solid var(--border);
    }

    .mcq-question-item:first-child {
        padding-top: 0;
    }

    .mcq-question-item:last-child {
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

    .options-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin-top: 16px;
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

    .text-question-item {
        padding: 24px 0;
        border-bottom: 1px solid var(--border);
    }

    .text-question-item:first-child {
        padding-top: 0;
    }

    .text-question-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .text-question-label {
        display: block;
        font-size: 0.94rem;
        font-weight: 600;
        color: var(--fg);
        margin-bottom: 10px;
        line-height: 1.55;
    }

    .text-question-textarea {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid var(--border-strong);
        border-radius: var(--radius);
        font-family: inherit;
        font-size: 0.88rem;
        color: var(--fg);
        background: var(--card);
        resize: vertical;
        min-height: 80px;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
        line-height: 1.6;
    }

    .text-question-textarea:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px var(--accent-light);
    }

    .text-question-textarea::placeholder {
        color: var(--muted);
    }

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

    .section-divider {
        border: none;
        height: 1px;
        background: var(--border);
        margin: 28px 0;
    }

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

        .progress-bar-inner {
            padding: 10px 16px;
        }

        .progress-bar-left {
            gap: 12px;
        }

        .progress-meta {
            font-size: 0.76rem;
        }

        .progress-track {
            max-width: 120px;
        }

        .progress-percent {
            font-size: 0.82rem;
        }

        .page-title-row h1 {
            font-size: 1.25rem;
        }

        .card-body {
            padding: 20px 16px;
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

        .feedback-section-header h2 {
            font-size: 1rem;
        }

        .table-responsive {
            margin: 0 -2px 20px;
            padding: 0 2px;
        }
    }

    @media (max-width: 400px) {
        .navbar-status {
            display: none;
        }

        .progress-bar-left {
            flex-wrap: wrap;
        }

        .progress-track {
            max-width: 100%;
            order: 3;
            flex-basis: 100%;
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

<header class="navbar">
    <div class="navbar-inner">
        <a href="#" class="navbar-brand">
            <div class="navbar-logo">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Logo">
            </div>
            <div class="navbar-text">BIRD Etims Portal<span>/ Feedback</span></div>
        </a>
        <div class="navbar-status">
            <div class="status-dot"></div>
            Feedback In Progress
        </div>
    </div>
</header>

<div class="progress-bar-section">
    <div class="progress-bar-inner">
        <div class="progress-bar-left">
            <div class="progress-meta">
                <i class="fas fa-list-check"></i>
                <span id="answeredCount">0</span>/{{ $totalQuestions }} completed
            </div>
            <div class="progress-track">
                <div class="progress-fill" id="progressFill"></div>
            </div>
        </div>
        <div class="progress-percent" id="progressPercent">0%</div>
    </div>
</div>

@if(session('error'))
    <div style="max-width:900px;margin:20px auto 0;padding:0 24px;">
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    </div>
@endif

@php
    $srNo = 0;
@endphp

<main class="page-wrap">

    <nav class="breadcrumb" aria-label="Breadcrumb">
        <a href="#">Dashboard</a>
        <i class="fas fa-chevron-right"></i>
        <a href="#">Programmes</a>
        <i class="fas fa-chevron-right"></i>
        <span class="current">Feedback Form</span>
    </nav>

    <div class="page-title-row">
        <div>
            <h1>Feedback Form</h1>
            <p class="subtitle">Please provide your honest feedback by selecting the appropriate options for each item.</p>
        </div>
    </div>

    <div class="callout callout-warning" id="warningCallout">
        <i class="fas fa-triangle-exclamation"></i>
        <p><strong>Please complete all items.</strong> Your responses are valuable and will be used to improve the programme quality. Ensure you have selected an option for each question before submitting.</p>
        <button class="callout-close" onclick="document.getElementById('warningCallout').style.display='none'" aria-label="Close warning">
            <i class="fas fa-xmark"></i>
        </button>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('participant.feedback.submit') }}" id="feedbackForm" method="POST">
                @csrf
                <input type="hidden" name="programme_id" value="{{ $participant->programme_id ?? '' }}">
                <input type="hidden" name="participants_id" value="{{ $participant->id ?? '' }}">

                @foreach($sections as $section)

                    <div class="feedback-section-header">
                        <h2>{{ $section['type_name'] }}</h2>
                        @if($section['is_session_section'])
                            <p>Please rate each session by selecting the most appropriate option</p>
                        @else
                            <p>Please tick (&#10003;) one against each item which most closely represents your view</p>
                        @endif
                    </div>

                    @if($section['is_session_section'])
                        {{-- ─── SESSION-WISE RATING TABLE (RATING ONLY) ─── --}}
                        <div class="table-responsive">
                            <table class="feedback-table">
                                <thead>
                                    <tr>
                                        <th>Sr. No.</th>
                                        <th>Excellent</th>
                                        <th>Very Good</th>
                                        <th>Good</th>
                                        <th>Fair</th>
                                        <th>Not Availed</th>
                                    </tr>
                                </thead>
                                @foreach($section['sessions'] as $session)
                                    @php $srNo++; @endphp
                                    <tbody id="question-{{ $srNo }}">
                                        <tr>
                                            <td rowspan="2" class="sr-cell">{{ $srNo }}</td>
                                            <td colspan="5" class="question-row">
                                                Session {{ $session->session_name }}: {{ $session->title }}
                                                <span class="session-meta">
                                                    @if($session->relationLoaded('faculty') && $session->faculty)
                                                        <strong>Faculty:</strong> {{ $session->faculty->name }} &nbsp;&middot;&nbsp;
                                                    @endif
                                                    <strong>Date:</strong> {{ \Carbon\Carbon::parse($session->date)->format('d-m-Y') }} &nbsp;&middot;&nbsp;
                                                    <strong>Time:</strong> {{ \Carbon\Carbon::parse($session->start_time)->format('h:i A') }} – {{ \Carbon\Carbon::parse($session->end_time)->format('h:i A') }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr class="option-row">
                                            <td>
                                                <input type="radio" class="rating-radio" name="session_ratings[{{ $session->id }}]" value="Excellent" onchange="markAnswered({{ $srNo }})">
                                            </td>
                                            <td>
                                                <input type="radio" class="rating-radio" name="session_ratings[{{ $session->id }}]" value="Very Good" onchange="markAnswered({{ $srNo }})">
                                            </td>
                                            <td>
                                                <input type="radio" class="rating-radio" name="session_ratings[{{ $session->id }}]" value="Good" onchange="markAnswered({{ $srNo }})">
                                            </td>
                                            <td>
                                                <input type="radio" class="rating-radio" name="session_ratings[{{ $session->id }}]" value="Fair" onchange="markAnswered({{ $srNo }})">
                                            </td>
                                            <td>
                                                <input type="radio" class="rating-radio" name="session_ratings[{{ $session->id }}]" value="Not Availed" onchange="markAnswered({{ $srNo }})">
                                            </td>
                                        </tr>
                                    </tbody>
                                @endforeach
                            </table>
                        </div>

                    @else
                        {{-- ─── REGULAR RATING QUESTIONS ─── --}}
                        @if($section['rating_questions']->count())
                            <div class="table-responsive">
                                <table class="feedback-table">
                                    <thead>
                                        <tr>
                                            <th>Sr. No.</th>
                                            <th>Excellent</th>
                                            <th>Very Good</th>
                                            <th>Good</th>
                                            <th>Fair</th>
                                            <th>Not Availed</th>
                                        </tr>
                                    </thead>
                                    @foreach($section['rating_questions'] as $question)
                                        @php $srNo++; @endphp
                                        <tbody id="question-{{ $srNo }}">
                                            <tr>
                                                <td rowspan="2" class="sr-cell">{{ $srNo }}</td>
                                                <td colspan="5" class="question-row">{{ $question->question }}</td>
                                            </tr>
                                            <tr class="option-row">
                                                <td>
                                                    <input type="radio" class="rating-radio" name="questions[{{ $question->id }}]" value="Excellent" onchange="markAnswered({{ $srNo }})">
                                                </td>
                                                <td>
                                                    <input type="radio" class="rating-radio" name="questions[{{ $question->id }}]" value="Very Good" onchange="markAnswered({{ $srNo }})">
                                                </td>
                                                <td>
                                                    <input type="radio" class="rating-radio" name="questions[{{ $question->id }}]" value="Good" onchange="markAnswered({{ $srNo }})">
                                                </td>
                                                <td>
                                                    <input type="radio" class="rating-radio" name="questions[{{ $question->id }}]" value="Fair" onchange="markAnswered({{ $srNo }})">
                                                </td>
                                                <td>
                                                    <input type="radio" class="rating-radio" name="questions[{{ $question->id }}]" value="Not Availed" onchange="markAnswered({{ $srNo }})">
                                                </td>
                                            </tr>
                                        </tbody>
                                    @endforeach
                                </table>
                            </div>
                        @endif

                        {{-- ─── MCQ QUESTIONS ─── --}}
                        @foreach($section['mcq_questions'] as $question)
                            @php
                                $srNo++;
                                $letters = ['A', 'B', 'C', 'D'];
                                $optionRecord = $question->feedbackQuestionOptions;
                                $opts = [];
                                if ($optionRecord) {
                                    $opts = [
                                        $optionRecord->option_1 ?? null,
                                        $optionRecord->option_2 ?? null,
                                        $optionRecord->option_3 ?? null,
                                        $optionRecord->option_4 ?? null,
                                    ];
                                }
                            @endphp
                            <div class="mcq-question-item" id="question-{{ $srNo }}">
                                <div style="margin-bottom:4px;">
                                    <span class="question-number">{{ $srNo }}</span>
                                    <span class="question-text">{{ $question->question }}</span>
                                </div>
                                <div class="options-grid">
                                    @foreach($opts as $key => $optVal)
                                        @if($optVal)
                                            <label class="option-label">
                                                <input type="radio" name="questions[{{ $question->id }}]" value="{{ $optVal }}" onchange="markAnswered({{ $srNo }})">
                                                <span><span class="option-letter">{{ $letters[$key] }})</span> {{ $optVal }}</span>
                                            </label>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endforeach

                        {{-- ─── TEXT QUESTIONS ─── --}}
                        @foreach($section['text_questions'] as $question)
                            @php $srNo++; @endphp
                            <div class="text-question-item" id="question-{{ $srNo }}">
                                <label class="text-question-label">
                                    <span class="question-number">{{ $srNo }}</span>
                                    <span class="question-text">{{ $question->question }}</span>
                                </label>
                                <textarea class="text-question-textarea" name="questions[{{ $question->id }}]" rows="2" placeholder="Type your response here..." oninput="markAnswered({{ $srNo }})"></textarea>
                            </div>
                        @endforeach
                    @endif

                    @if(!$loop->last)
                        <hr class="section-divider">
                    @endif

                @endforeach

                <div class="submit-area">
                    <div class="submit-info">
                        <strong>{{ $totalQuestions }}</strong> questions &middot; Please complete all items before submitting
                    </div>
                    <button type="submit" class="btn-primary">
                        Submit Feedback
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

</main>

<footer class="page-footer">
    <span>BIRD Etims Portal &mdash; Responses are encrypted</span>
    <div class="footer-links">
        <a href="#">Help Center</a>
        <a href="#">Privacy Policy</a>
    </div>
</footer>

<script>
    const totalQuestions = {{ $totalQuestions }};
    const answeredSet = new Set();

    function markAnswered(qNum) {
        answeredSet.add(qNum);
        updateProgress();
    }

    function updateProgress() {
        const count = answeredSet.size;
        const pct = Math.round((count / totalQuestions) * 100);
        document.getElementById('answeredCount').textContent = count;
        document.getElementById('progressFill').style.width = pct + '%';
        document.getElementById('progressPercent').textContent = pct + '%';

        const fill = document.getElementById('progressFill');
        const percent = document.getElementById('progressPercent');

        if (pct === 100) {
            fill.classList.add('progress-fill--complete');
            percent.classList.add('progress-percent--complete');
        } else {
            fill.classList.remove('progress-fill--complete');
            percent.classList.remove('progress-percent--complete');
        }
    }

    document.getElementById('feedbackForm').addEventListener('submit', function(e) {
        const unanswered = totalQuestions - answeredSet.size;
        if (unanswered > 0) {
            const confirmed = confirm('You have ' + unanswered + ' unanswered question(s). Do you want to submit anyway?');
            if (!confirmed) {
                e.preventDefault();
                for (let i = 1; i <= totalQuestions; i++) {
                    if (!answeredSet.has(i)) {
                        const el = document.getElementById('question-' + i);
                        if (el) {
                            const y = el.getBoundingClientRect().top + window.pageYOffset - 120;
                            window.scrollTo({ top: y, behavior: 'smooth' });
                        }
                        break;
                    }
                }
            }
        }
    });

    window.onload = function() {
        updateProgress();
    };
</script>

</body>
</html>