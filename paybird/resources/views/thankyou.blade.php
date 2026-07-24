<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Exam Submission Success</title>
 <link href="{{ asset('assets/fontawesome-4.7/css/font-awesome.min.css') }}" rel="stylesheet">
</head>

<body>
  <!-- thank-you-wrapper -->
  <section class="thank-you-wrapper">
    <div class="container">
      <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
          <div class="thank-you-page-logo">
            <img src="{{url('/')}}/assets/images/timslogo.png" alt="Logo">
          </div>
          <div class="thank-you-page-content">
            <h1>Your Exam submission is received!</h1>
            <p class="answer-text">✅ Your Right Answer: <strong>{{ session('marks') }}</strong></p>
            <a href="{{url('/')}}" class="btn btn-primary arrow-icon">Go back to Homepage</a>
          </div>

          <ul class="footer-nav">
            <li><a href="#">Terms and Conditions</a></li>
            <li><a href="#">Privacy Policy</a></li>
            <li><a href="#">FAQ</a></li>
            <li><a href="#">Contact Us</a></li>
          </ul>

          <div class="thank-you-copy">
            <p>Tims &copy; 2025 All Rights Reserved</p>
          </div>

        </div>
      </div>
    </div>
  </section>
  <!-- thank-you-wrapper -->

  <style>
    html, body {height: 100%; margin: 0; font-family: Arial, sans-serif;}
    .thank-you-wrapper {position: relative; height: 100%; background-color: #f2f2f2; text-align: center;}
    .thank-you-wrapper .container {display: table; height: 100%; width: 100%; max-width: 800px; margin: 0 auto;}
    .thank-you-wrapper .row {display: table-cell; vertical-align: middle;}
    .thank-you-page-logo a {font-size: 24px; color: #0a568a; text-decoration: none; font-weight: bold;}
    .thank-you-page-content {background: #fff; padding: 50px; margin: 30px 0; box-shadow: 0 5px 15px rgba(0,0,0,0.1); border-radius: 10px;}
    .thank-you-page-content h1 {font-size: 28px; margin-bottom: 20px; color: green;}
    .thank-you-page-content .answer-text {font-size: 20px; margin-bottom: 30px; color: #333;}
    .btn-primary {background-color: #0a568a; color: #fff; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;}
    .btn-primary:hover {background-color: #084472;}
    ul.footer-nav {list-style: none; padding: 0; margin: 20px 0 10px;}
    ul.footer-nav li {display: inline; margin: 0 10px;}
    ul.footer-nav li a {color: #0a568a; text-decoration: none; font-size: 14px;}
    ul.footer-nav li a:hover {text-decoration: underline;}
    .thank-you-copy p {font-size: 12px; color: #666;}
  </style>

</body>
</html>
