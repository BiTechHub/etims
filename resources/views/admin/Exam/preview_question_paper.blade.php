@extends('admin.layouts.master')

@section('main-section')

<!-- SELECT2 CSS -->

<link rel="stylesheet" href="{{ asset('assets/select2/css/select2.min.css') }}">

<style>

.question-paper{
    background: #fff;
    border: 1px solid #ddd;
    padding: 30px;
    border-radius: 10px;
}

.question-paper h2,
.question-paper h4{
    text-align: center;
}

.question{
    margin-bottom: 25px;
}

.question-title{
    font-weight: 600;
    margin-bottom: 10px;
}

.option-list{
    margin-left: 20px;
}

.option-list div{
    margin-bottom: 5px;
}

.paper-header{
    margin-bottom: 20px;
}

.paper-header div{
    margin-bottom: 5px;
}

.select2-container{
    width: 100% !important;
}

.select2-container .select2-selection--single{
    height: 38px !important;
    border: 1px solid #ced4da !important;
    padding-top: 4px;
}

.select2-container--default .select2-selection--single .select2-selection__arrow{
    height: 38px !important;
}

</style>


<div class="container">

    <div class="page-inner">

        <div class="page-header">
            <h3 class="fw-bold mb-3">
                Question Paper Preview
            </h3>
        </div>


        <!-- FILTER CARD -->

        <div class="card mb-4">

            <div class="card-body">

                <div class="row">

                    <!-- YEAR -->

                    <div class="col-md-3 mb-3">

                        <label>
                            Calendar Year
                        </label>

                        <select class="form-control" id="cal_year">

                            <option value="">
                                Select Year
                            </option>

                            @foreach($years as $year)

                                <option value="{{ $year }}">
                                    {{ $year }}
                                </option>

                            @endforeach

                        </select>

                    </div>



                    <!-- PROGRAMME -->

                    <div class="col-md-3 mb-3">

                        <label>
                            Programme
                        </label>

                        <select class="form-control" id="programme_id">

                            <option value="">
                                Select Programme
                            </option>

                        </select>

                    </div>



                    <!-- EXAM TYPE -->

                    <div class="col-md-2 mb-3">

                        <label>
                            Exam Type
                        </label>

                        <select class="form-control" id="exam_type">

                            <option value="entry">
                                Entry Exam
                            </option>

                            <option value="exit">
                                Exit Exam
                            </option>
                                <option value="both">
                                Both
                            </option>

                        </select>

                    </div>



                    <!-- TOTAL MARKS -->

                    <div class="col-md-2 mb-3">

                        <label>
                            Total Marks
                        </label>

                        <input 
                            type="number"
                            class="form-control"
                            id="total_marks"
                            placeholder="100"
                        >

                    </div>



                    <!-- PASSING MARKS -->

                    <div class="col-md-2 mb-3">

                        <label>
                            Passing Marks
                        </label>

                        <input 
                            type="number"
                            class="form-control"
                            id="passing_marks"
                            placeholder="40"
                        >

                    </div>



                    <!-- DURATION -->

                    <div class="col-md-3 mb-3">

                        <label>
                            Duration (Minutes)
                        </label>

                        <input 
                            type="number"
                            class="form-control"
                            id="duration"
                            placeholder="60"
                        >

                    </div>



                    <!-- ENTRY DATE -->

                    <div class="col-md-3 mb-3">

                        <label>
                            Entry Exam Date
                        </label>

                        <input 
                            type="date"
                            class="form-control"
                            id="entry_exam_date"
                        >

                    </div>



                    <!-- ENTRY TIME -->

                    <div class="col-md-3 mb-3">

                        <label>
                            Entry Exam Time
                        </label>

                        <input 
                            type="time"
                            class="form-control"
                            id="entry_exam_time"
                        >

                    </div>



                    <!-- EXIT DATE -->

                    <div class="col-md-3 mb-3">

                        <label>
                            Exit Exam Date
                        </label>

                        <input 
                            type="date"
                            class="form-control"
                            id="exit_exam_date"
                        >

                    </div>



                    <!-- EXIT TIME -->

                    <div class="col-md-3 mb-3">

                        <label>
                            Exit Exam Time
                        </label>

                        <input 
                            type="time"
                            class="form-control"
                            id="exit_exam_time"
                        >

                    </div>



                    <!-- UPDATE BUTTON -->

                    <div class="col-md-3 mb-3">

                        <label>&nbsp;</label>

                        <button 
                            type="button"
                            class="btn btn-primary w-100"
                            id="update_btn"
                        >
                            Update Question Paper
                        </button>

                    </div>

                </div>

            </div>

        </div>



        <!-- QUESTION PAPER -->

        <div class="card">

            <div class="card-body">

                <div class="question-paper">

                    <h2>
                        QUESTION PAPER
                    </h2>

                    <h4 id="preview_programme">
                        Programme Name
                    </h4>

                    <hr>


                    <!-- HEADER -->

                    <div class="row paper-header">

                        <div class="col-md-3">
                            <strong>Date :</strong>
                            <span id="preview_date">--</span>
                        </div>

                        <div class="col-md-3">
                            <strong>Time :</strong>
                            <span id="preview_time">--</span>
                        </div>

                        <div class="col-md-3">
                            <strong>Total Marks :</strong>
                            <span id="preview_marks">--</span>
                        </div>

                        <div class="col-md-3">
                            <strong>Passing Marks :</strong>
                            <span id="preview_pass_marks">--</span>
                        </div>

                    </div>

                    <hr>


                    <!-- QUESTIONS -->

                    <div id="question_container">

                        <div class="text-center text-muted">

                            Please Select Programme

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection



@section('script')

<script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>

<script src="{{ asset('assets/js/select2.min.js') }}"></script>


<script>

$(document).ready(function () {

    // =========================
    // SELECT 2
    // =========================

    $('#cal_year').select2();

    $('#programme_id').select2();

    $('#exam_type').select2();



    // =========================
    // YEAR CHANGE
    // =========================

    $('#cal_year').change(function () {

        let selectedYear = $(this).val();

        $('#programme_id').html(`
        
            <option value="">
                Loading...
            </option>
        
        `);


        $.ajax({

            url: "{{ route('admin.getProgrammesByYears') }}",

            type: "GET",

            data: {
                year: selectedYear
            },

            success: function (response) {

                let html = `
                
                    <option value="">
                        Select Programme
                    </option>
                
                `;

                if (response.status == true) {

                    $.each(response.programmes, function (key, programme) {

                        html += `
                        
                            <option value="${programme.id}">
                                ${programme.title}
                            </option>
                        
                        `;

                    });

                } else {

                    html += `
                    
                        <option value="">
                            No Programme Found
                        </option>
                    
                    `;

                }

                $('#programme_id').html(html);

            }

        });

    });




    // =========================
    // PROGRAMME CHANGE
    // =========================

    $('#programme_id').change(function () {

        let programmeId = $(this).val();

        let programmeName = $("#programme_id option:selected").text();

        $('#preview_programme').text(programmeName);


        if (programmeId != '') {

            $.ajax({

                url: "{{ url('admin/get-question-paper-by-programme') }}/" + programmeId,

                type: "GET",

                success: function (response) {

                    let html = '';



                    // =========================
                    // BASIC DETAILS
                    // =========================

                    if (response.basic_details != null) {

                        $('#total_marks').val(
                            response.basic_details.total_marks
                        );

                        $('#passing_marks').val(
                            response.basic_details.passing_marks
                        );

                        $('#duration').val(
                            response.basic_details.duration
                        );

                        $('#entry_exam_date').val(
                            response.basic_details.entry_exam_date
                        );

                        $('#entry_exam_time').val(
                            response.basic_details.entry_exam_time
                        );

                        $('#exit_exam_date').val(
                            response.basic_details.exit_exam_date
                        );

                        $('#exit_exam_time').val(
                            response.basic_details.exit_exam_time
                        );

                        $('#exam_type').val(
                            response.basic_details.exam_type
                        ).trigger('change');



                        // PREVIEW

                        $('#preview_marks').text(
                            response.basic_details.total_marks
                        );

                        $('#preview_pass_marks').text(
                            response.basic_details.passing_marks
                        );

                        if (
                            response.basic_details.exam_type == 'entry'
                        ) {

                            $('#preview_date').text(
                                response.basic_details.entry_exam_date
                            );

                            $('#preview_time').text(
                                response.basic_details.entry_exam_time
                            );

                        } else {

                            $('#preview_date').text(
                                response.basic_details.exit_exam_date
                            );

                            $('#preview_time').text(
                                response.basic_details.exit_exam_time
                            );

                        }

                    }




                    // =========================
                    // QUESTIONS
                    // =========================

                    if (response.questions.length > 0) {

                        $.each(response.questions, function (index, question) {

                            html += `
                            
                                <div class="question">

                                    <div class="question-title">

                                        Q${index + 1}. 
                                        ${question.question_title}

                                    </div>


                                    <div class="option-list">

                                        <div>
                                            A. ${question.option_A}
                                        </div>

                                        <div>
                                            B. ${question.option_B}
                                        </div>

                                        <div>
                                            C. ${question.option_C}
                                        </div>

                                        <div>
                                            D. ${question.option_D}
                                        </div>

                                    </div>

                                </div>
                            
                            `;

                        });

                    } else {

                        html = `
                        
                            <div class="text-center text-danger">
                                No Questions Found
                            </div>
                        
                        `;

                    }

                    $('#question_container').html(html);

                }

            });

        }

    });





    // =========================
    // LIVE PREVIEW
    // =========================

    $('#entry_exam_date').change(function () {

        if ($('#exam_type').val() == 'entry') {

            $('#preview_date').text($(this).val());

        }

    });



    $('#entry_exam_time').change(function () {

        if ($('#exam_type').val() == 'entry') {

            $('#preview_time').text($(this).val());

        }

    });



    $('#exit_exam_date').change(function () {

        if ($('#exam_type').val() == 'exit') {

            $('#preview_date').text($(this).val());

        }

    });



    $('#exit_exam_time').change(function () {

        if ($('#exam_type').val() == 'exit') {

            $('#preview_time').text($(this).val());

        }

    });



    $('#total_marks').keyup(function () {

        $('#preview_marks').text($(this).val());

    });



    $('#passing_marks').keyup(function () {

        $('#preview_pass_marks').text($(this).val());

    });




    // =========================
    // EXAM TYPE CHANGE
    // =========================

    $('#exam_type').change(function () {

        let type = $(this).val();

        if (type == 'entry') {

            $('#preview_date').text(
                $('#entry_exam_date').val()
            );

            $('#preview_time').text(
                $('#entry_exam_time').val()
            );

        } else {

            $('#preview_date').text(
                $('#exit_exam_date').val()
            );

            $('#preview_time').text(
                $('#exit_exam_time').val()
            );

        }

    });





    // =========================
    // UPDATE BUTTON
    // =========================

    $('#update_btn').click(function () {

        $.ajax({

            url: "{{ route('admin.update-question-paper-basic-details') }}",

            type: "POST",

            data: {

                _token: "{{ csrf_token() }}",

                programme_id: $('#programme_id').val(),

                total_marks: $('#total_marks').val(),

                passing_marks: $('#passing_marks').val(),

                duration: $('#duration').val(),

                entry_exam_date: $('#entry_exam_date').val(),

                entry_exam_time: $('#entry_exam_time').val(),

                exit_exam_date: $('#exit_exam_date').val(),

                exit_exam_time: $('#exit_exam_time').val(),

                exam_type: $('#exam_type').val()

            },

            success: function (response) {

                if (response.status == true) {

                    alert(response.message);

                } else {

                    alert(response.message);

                }

            },

            error: function () {

                alert('Something Went Wrong');

            }

        });

    });

});

</script>

@endsection