<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Thank You!</title>
  <style>
    /* Reset and base */
    * {
      box-sizing: border-box;
    }
    body, html {
      margin: 0;
      padding: 0;
      height: 100%;
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #667eea, #764ba2);
      display: flex;
      justify-content: center;
      align-items: center;
      color: #fff;
      text-align: center;
      overflow: hidden;
    }

    .container {
      background: rgba(255, 255, 255, 0.1);
      padding: 3rem 2.5rem;
      border-radius: 20px;
      max-width: 420px;
      width: 90%;
      box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
      backdrop-filter: blur(15px);
      animation: fadeInScale 0.8s ease forwards;
      position: relative;
      z-index: 1;
    }

    @keyframes fadeInScale {
      0% {
        opacity: 0;
        transform: scale(0.85);
      }
      100% {
        opacity: 1;
        transform: scale(1);
      }
    }

    h1 {
      font-size: 3rem;
      margin-bottom: 0.3rem;
      font-weight: 700;
      letter-spacing: 2px;
      text-shadow: 0 0 15px rgba(255, 255, 255, 0.4);
    }

    p {
      font-size: 1.25rem;
      margin: 1rem 0 2rem;
      line-height: 1.5;
      text-shadow: 0 0 8px rgba(0,0,0,0.15);
    }

    .btn {
      background: #fff;
      color: #764ba2;
      font-weight: 700;
      padding: 0.75rem 2.5rem;
      border-radius: 50px;
      font-size: 1.1rem;
      letter-spacing: 1.2px;
      border: none;
      cursor: pointer;
      box-shadow: 0 8px 20px rgba(255, 255, 255, 0.3);
      transition: all 0.3s ease;
      text-decoration: none;
      display: inline-block;
      user-select: none;
    }

    .btn:hover {
      background: #f2f2f2;
      box-shadow: 0 10px 30px rgba(255, 255, 255, 0.5);
      transform: translateY(-3px);
    }

    /* Animated checkmark */
    .checkmark {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      background: #4caf50;
      display: flex;
      justify-content: center;
      align-items: center;
      margin: 0 auto 1.5rem;
      box-shadow: 0 0 15px #4caf50;
      animation: popIn 0.6s ease forwards;
    }

    @keyframes popIn {
      0% {
        opacity: 0;
        transform: scale(0.3);
      }
      80% {
        transform: scale(1.1);
        opacity: 1;
      }
      100% {
        transform: scale(1);
      }
    }

    .checkmark svg {
      fill: none;
      stroke: white;
      stroke-width: 6;
      stroke-linecap: round;
      stroke-linejoin: round;
      stroke-dasharray: 48;
      stroke-dashoffset: 48;
      animation: drawCheck 0.5s ease forwards 0.6s;
      width: 40px;
      height: 40px;
    }

    @keyframes drawCheck {
      to {
        stroke-dashoffset: 0;
      }
    }

    /* Responsive text */
    @media (max-width: 480px) {
      h1 {
        font-size: 2.4rem;
      }
      p {
        font-size: 1rem;
      }
      .btn {
        padding: 0.65rem 2rem;
        font-size: 1rem;
      }
      .container {
        padding: 2.5rem 2rem;
      }
    }
  </style>
</head>
<body>
  <div class="container" role="main" aria-label="Thank you message">
    <div class="checkmark" aria-hidden="true">
      <svg viewBox="0 0 24 24">
        <polyline points="20 6 9 17 4 12"></polyline>
      </svg>
    </div>
    <h1>Thank You!</h1>
    <p>Your submission has been received successfully.</p>
  
  </div>
</body>
</html>
