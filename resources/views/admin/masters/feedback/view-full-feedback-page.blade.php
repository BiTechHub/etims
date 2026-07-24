@extends('admin.layouts.master')

@section('main-section')

<style>

.feedback-paper{
    background:#fff;
    padding:30px;
    border:1px solid #000;
    color:#000;
}

.header-title{
    text-align:center;
}

.header-title h1,
.header-title h2,
.header-title h3,
.header-title h4{
    margin:0;
    font-weight:bold;
}

.program-table td{
    border:1px solid #000 !important;
    height:70px;
    vertical-align:middle !important;
}

.section-title{
    text-align:center;
    margin-top:40px;
    margin-bottom:20px;
}

.section-title h2{
    font-weight:bold;
    text-decoration:underline;
}

.feedback-table{
    width:100%;
    border-collapse:collapse;
}

.feedback-table th,
.feedback-table td{
    border:1px solid #000;
}

.feedback-table th{
    text-align:center;
    font-size:15px;
    font-weight:bold;
}

.question-row{
    font-weight:bold;
    font-size:15px;
    padding:10px;
}

.option-row td{
    height:30px;
    text-align:center;
    vertical-align:middle;
}

.option-row input[type=radio]{
    transform:scale(1.4);
}

.text-question{
    margin-bottom:25px;
}

.text-question textarea{
    min-height:120px;
}

.logo{
    position:absolute;
    right:30px;
    top:20px;
    height:100px;
}

</style>

<div class="container">

<div class="page-inner">

<div class="feedback-paper">

```
{{-- Logo --}}
<img src="{{ asset('bird-logo.png') }}" class="logo">

{{-- Header --}}
<div class="header-title">

    <h4>
        बैंकर ग्रामीण विकास संस्थान, लखनऊ
    </h4>

    <h1>
        BANKERS INSTITUTE OF RURAL DEVELOPMENT
    </h1>

    <h1>
        LUCKNOW
    </h1>

    <br>

    <h3>
        Evaluation Sheet [In House]
    </h3>

</div>

<br><br>

{{-- Programme Details --}}

<table class="table program-table">

    <tr>

        <td width="35%">

            <strong>
                कार्यक्रम का नाम
            </strong>

            <br>

            Name of the Programme

        </td>

        <td></td>

    </tr>

    <tr>

        <td>

            <strong>
                अवधि
            </strong>

            <br>

            Duration

        </td>

        <td>

            From _____________________

            To _____________________

        </td>

    </tr>

</table>

@php
    $groupedQuestions = $feedbackQuestions->groupBy(
        fn($item) => $item->feedbackQuestionType->type_name
    );

    $srNo = 1;
@endphp

{{-- Group by Type --}}
@foreach($groupedQuestions as $typeName => $questions)

    <div class="section-title">

        <h2>
            {{ $typeName }}
        </h2>

        <h4>
            Please tick (✓) one against each item which most closely represents your view
        </h4>

    </div>

    {{-- Rating Questions --}}

    @php
        $ratingQuestions = $questions->where('answer_type','rating');
    @endphp

    @if($ratingQuestions->count())

    <table class="feedback-table mb-5">

        <thead>

        <tr>

            <th width="8%">
                Sr. No.
            </th>

            <th>
                Excellent
            </th>

            <th>
                Very Good
            </th>

            <th>
                Good
            </th>

            <th>
                Fair
            </th>

            <th>
                Not Availed
            </th>

            <th>
                Remarks
            </th>

        </tr>

        </thead>

        <tbody>

        @foreach($ratingQuestions as $question)

            <tr>

                <td rowspan="2"
                    class="text-center fw-bold">

                    {{ $srNo++ }}

                </td>

                <td colspan="6"
                    class="question-row">

                    {{ $question->question }}

                </td>

            </tr>

            <tr class="option-row">

                <td>
                    <input type="radio"
                           name="question_{{$question->id}}"
                           value="Excellent">
                </td>

                <td>
                    <input type="radio"
                           name="question_{{$question->id}}"
                           value="Very Good">
                </td>

                <td>
                    <input type="radio"
                           name="question_{{$question->id}}"
                           value="Good">
                </td>

                <td>
                    <input type="radio"
                           name="question_{{$question->id}}"
                           value="Fair">
                </td>

                <td>
                    <input type="radio"
                           name="question_{{$question->id}}"
                           value="Not Availed">
                </td>

                <td>

                    <input type="text"
                           class="form-control border-0">

                </td>

            </tr>

        @endforeach

        </tbody>

    </table>

    @endif


    {{-- MCQ Questions --}}

    @foreach($questions->where('answer_type','mcq') as $question)

        <div class="mb-4">

            <h5>
                {{ $srNo++ }}.
                {{ $question->question }}
            </h5>

            @foreach($question->feedbackQuestionOptions as $option)

                @if($option->option_1)
                <div>
                    <input type="radio"
                           name="question_{{$question->id}}">
                    {{ $option->option_1 }}
                </div>
                @endif

                @if($option->option_2)
                <div>
                    <input type="radio"
                           name="question_{{$question->id}}">
                    {{ $option->option_2 }}
                </div>
                @endif

                @if($option->option_3)
                <div>
                    <input type="radio"
                           name="question_{{$question->id}}">
                    {{ $option->option_3 }}
                </div>
                @endif

                @if($option->option_4)
                <div>
                    <input type="radio"
                           name="question_{{$question->id}}">
                    {{ $option->option_4 }}
                </div>
                @endif

            @endforeach

        </div>

    @endforeach


    {{-- Text Questions --}}

    @foreach($questions->where('answer_type','text') as $question)

        <div class="text-question">

            <label class="fw-bold">

                {{ $srNo++ }}.
                {{ $question->question }}

            </label>

            <textarea class="form-control" rows="1"></textarea>

        </div>

    @endforeach

@endforeach
```

</div>

</div>

</div>

@endsection
