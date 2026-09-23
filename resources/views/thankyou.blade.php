<!DOCTYPE html>

<html lang="en">

<head>

  <meta charset="UTF-8">

  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Submission Successful — BIRD Etims Portal</title>

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
        --success: #059669;
        --success-bg: #ecfdf5;
        --success-border: #a7f3d0;
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
        display: flex;
        flex-direction: column;
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

    /* ========================
       MAIN CENTERING
    ======================== */
    .success-page {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 24px;
    }

    .success-card {
        width: 100%;
        max-width: 520px;
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-2);
        overflow: hidden;
        animation: fadeUp 0.4s ease-out;
    }

    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(16px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* ========================
       SUCCESS ICON
    ======================== */
    .success-icon-wrap {
        padding: 40px 24px 0;
        text-align: center;
    }

    .success-icon {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: var(--success-bg);
        border: 2px solid var(--success-border);
        display: inline-grid;
        place-items: center;
        position: relative;
    }

    .success-icon i {
        font-size: 28px;
        color: var(--success);
        animation: checkPop 0.4s ease-out 0.2s both;
    }

    @keyframes checkPop {
        from { opacity: 0; transform: scale(0.5); }
        to { opacity: 1; transform: scale(1); }
    }

    .success-icon::after {
        content: '';
        position: absolute;
        inset: -8px;
        border-radius: 50%;
        border: 2px solid var(--success-border);
        opacity: 0;
        animation: ringExpand 0.6s ease-out 0.3s both;
    }

    @keyframes ringExpand {
        from { opacity: 0.6; transform: scale(0.8); }
        to { opacity: 0; transform: scale(1.2); }
    }

    /* ========================
       SUCCESS BODY
    ======================== */
    .success-body {
        padding: 24px 32px 32px;
        text-align: center;
    }

    .success-body h1 {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--fg);
        margin-bottom: 8px;
        letter-spacing: -0.3px;
    }

    .success-body .subtitle {
        font-size: 0.88rem;
        color: var(--muted);
        margin-bottom: 28px;
    }

    /* Score Display */
    .score-display {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 14px;
        padding: 18px 20px;
        background: var(--success-bg);
        border: 1px solid var(--success-border);
        border-radius: var(--radius);
        margin-bottom: 28px;
    }

    .score-display .score-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--success);
        color: #fff;
        display: grid;
        place-items: center;
        font-size: 0.85rem;
        flex-shrink: 0;
    }

    .score-display .score-info {
        text-align: left;
    }

    .score-display .score-label {
        font-size: 0.72rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        color: var(--success);
        display: block;
    }

    .score-display .score-value {
        font-size: 1.2rem;
        font-weight: 800;
        color: #065f46;
        letter-spacing: -0.5px;
        display: block;
        margin-top: 1px;
    }

    /* Button */
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

    /* Timestamp */
    .success-meta {
        margin-top: 20px;
        font-size: 0.78rem;
        color: var(--muted);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .success-meta i {
        font-size: 0.7rem;
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
    @media (max-width: 600px) {
        .success-page {
            padding: 24px 16px 40px;
        }

        .success-body {
            padding: 20px 24px 28px;
        }

        .success-body h1 {
            font-size: 1.15rem;
        }

        .score-display {
            flex-direction: column;
            text-align: center;
            gap: 10px;
            padding: 16px;
        }

        .score-display .score-info {
            text-align: center;
        }

        .btn-primary {
            width: 100%;
            justify-content: center;
        }

        .navbar-inner {
            padding: 0 16px;
        }

        .navbar-text span {
            display: none;
        }

        .page-footer {
            flex-direction: column;
            gap: 8px;
            text-align: center;
        }
    }

    @media (max-width: 400px) {
        .success-icon-wrap {
            padding: 32px 16px 0;
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
        <a href="{{ url('/') }}" class="navbar-brand">
            <div class="navbar-logo">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Logo">
            </div>
            <div class="navbar-text">BIRD Etims Portal<span>/ Assessment</span></div>
        </a>
    </div>
</header>

<!-- Success Content -->
<main class="success-page">
    <div class="success-card">

        <div class="success-icon-wrap">
            <div class="success-icon">
                <i class="fas fa-check"></i>
            </div>
        </div>

        <div class="success-body">
            <h1>Submission Received</h1>
            <p class="subtitle">Your exam has been submitted successfully.</p>

            <div class="score-display">
                <div class="score-icon">
                    <i class="fas fa-star"></i>
                </div>
                <div class="score-info">
                    <span class="score-label">Your Score</span>
                    <span class="score-value">{{ session('marks') }}</span>
                </div>
            </div>
            @if($type=='exit')
  <a href="{{ route('participant.feedback') }}" class="btn-primary">
               Go to  Feedback
                <i class="fas fa-arrow-right"></i>
            </a>

           
            @else 
             <a href="{{ url('/') }}" class="btn-primary">
                Go to Homepage
                <i class="fas fa-arrow-right"></i>
            </a>


            @endif

            <div class="success-meta">
                <i class="far fa-clock"></i>
                Submitted on {{ date('d M Y, h:i A') }}
            </div>
        </div>

    </div>
</main>

<!-- Footer -->
<footer class="page-footer">
    <span>BIRD Etims Portal &copy; 2025 All Rights Reserved</span>
    <div class="footer-links">
        <a href="#">Terms and Conditions</a>
        <a href="#">Privacy Policy</a>
        <a href="#">FAQ</a>
        <a href="#">Contact Us</a>
    </div>
</footer>

</body>

</html>