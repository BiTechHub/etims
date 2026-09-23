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
<title>Online Test</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
 <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">

<style>
  body {
    font-family: 'Inter', sans-serif;
    margin: 0;
    padding: 0;
  }

  .question {
    font-size: 18px;
    margin-bottom: 20px;
  }

  .options label {
    display: block;
    margin-bottom: 10px;
    font-size: 16px;
  }

  .options input {
    margin-right: 8px;
    transform: scale(1.2);
  }

  .quiz-container {
    margin-top: 20px;
  }

  .btn-primary {
    background-color: #007bff;
    border: none;
    padding: 12px 20px;
    font-size: 18px;
    border-radius: 5px;
    cursor: pointer;
    width: 100%;
  }

  .btn-primary:hover {
    background-color: #0056b3;
  }

  /* Responsive Styles */
  @media (max-width: 768px) {
    .question {
      font-size: 16px;
    }

    .options label {
      font-size: 14px;
    }

    .row {
      display: flex;
      flex-direction: column;
    }

    .col-md-4 {
      width: 100%;
      margin-bottom: 10px;
      text-align: center;
    }

    .btn-primary {
      width: 100%;
      font-size: 16px;
    }
  }
</style>

</head>
<body>
@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
@php $action = 2; @endphp
<main class="content">
  <div class="container-fluid p-0">

    <h1 class="card-title text-primary text-center" style="margin-top: 10px;">
      <i class="align-middle" data-feather="radio" style="color: green;"></i> Online Test
    </h1>

    <div class="row">
      <div class="col-12">
        <div class="card">

          <div class="card-body pt-0">

            <div class="row card-header border" style="background: wheat;">
              <div class="col-md-4">
                <h2 class="card-title text-primary"></h2>
                <p>Timing: 
                  30M
                </p>
              </div>

              <div class="col-md-4"></div>

              <div class="col-md-4">
                <div id="timer" style="font-weight: bold;"></div>
              </div>
            </div>

            <hr class="mt-0">

            <div class="m-sm-4">
              <form action="{{route('admin.exist.onlinetest')}}" id="yourFormId" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="programme_id" value="{{$Participant->programme_id}}">
                <input type="hidden" name="participants_id" value="{{$Participant->id}}">
                <div class="quiz-container">
                  @php $i = 1; @endphp
                  @foreach ($questions as $questions)
                  <div class="question mb-5">
                    <b>{{$i}}: {{$questions->question_title}}
                      <input type="hidden" name="question_{{$i}}" value="{{$questions->id}}">
                      <input type="hidden" name="cauntt[]" value="{{$i}}">
                    </b>

                    <div class="options mt-3">
                      <label><input type="radio" name="option_{{$i}}" value="A"> A) {{$questions->option_A}}</label>
                      <label><input type="radio" name="option_{{$i}}" value="B"> B) {{$questions->option_B}}</label>
                      <label><input type="radio" name="option_{{$i}}" value="C"> C) {{$questions->option_C}}</label>
                      <label><input type="radio" name="option_{{$i}}" value="D"> D) {{$questions->option_D}}</label>
                    </div>
                  </div>
                  <hr>
                  @php $i++; @endphp
                  @endforeach
                </div>

                <div class="text-center mt-3">
                  <button type="submit" class="btn btn-lg btn-primary">Submit</button>
                </div>

              </form>
            </div>

          </div>

        </div>
      </div>
    </div>

  </div>
<script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script>
    const timerE = document.getElementById("iso_time");
    const examDuration = '30';

    function startExamTimer() {
      const timerElement = document.getElementById("timer");
      const startTime = new Date().getTime();
      const endTime = startTime + examDuration * 60 * 1000;

      const timerInterval = setInterval(function () {
        const currentTime = new Date().getTime();
        const remainingTime = endTime - currentTime;
        const minutes = Math.floor((remainingTime % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((remainingTime % (1000 * 60)) / 1000);
        timerElement.innerHTML = `Time Remaining: ${minutes}m ${seconds}s`;

        if (remainingTime <= 0) {
          clearInterval(timerInterval);
          timerElement.innerHTML = "Time's Up!";
          document.getElementById("yourFormId").submit();
        }
      }, 1000);
    }

    window.onload = startExamTimer;
  </script>

</main>

<script src="{{url('/')}}/js/app.js"></script>
</body>
</html>
