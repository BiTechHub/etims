@extends('admin.layouts.master')

@section('main-section')

<div class="container">
    <div class="page-inner">

```
    <div class="page-header">
        <h3 class="fw-bold mb-3">Feedback Management</h3>
    </div>

    <div class="card">

        <div class="card-header">
            <button class="btn btn-primary" id="addNewFeedback">
                Add Feedback
            </button>
                  <a href="{{ route('admin.feedback.view') }}"
               class="btn btn-primary">
                <i class="fas fa-arrow-left"></i>
                View Full Feedback Page
            </a>

        </div>

        <div class="card-body">

            <table id="feedbackTable" class="table table-bordered table-striped">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Type</th>
                        <th>Question</th>
                        <th>Answer Type</th>
                        <th>Action</th>
                    </tr>
                </thead>

            </table>

        </div>

    </div>

</div>
```

</div>

<!-- Modal -->

<div class="modal fade" id="feedbackModal">

```
<div class="modal-dialog modal-xl">

    <div class="modal-content">

        <div class="modal-header">

            <h5 class="modal-title">
                Add / Update Feedback
            </h5>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
            </button>

      
        </div>

        <div class="modal-body">

            <input type="hidden" id="feedback_id">

            <div class="row">

                <div class="col-md-4">

                    <label>Feedback Type</label>

                    <select id="feedback_type_id"
                            class="form-control">

                        <option value="">
                            Select Type
                        </option>

                        @foreach($types as $type)

                            <option value="{{ $type->id }}">
                                {{ $type->type_name }}
                            </option>

                        @endforeach

                    </select>

                </div>

            </div>

            <hr>

            <div id="questionContainer"></div>

            <button type="button"
                    class="btn btn-success mt-3"
                    id="addQuestion">

                Add Question

            </button>

        </div>

        <div class="modal-footer">

            <button type="button"
                    class="btn btn-primary"
                    id="saveFeedback">

                Save

            </button>

        </div>

    </div>

</div>
```

</div>

@endsection

@section('script')

<script src="{{ url('/admin') }}/assets/js/plugin/datatables/datatables.min.js"></script>

<script>

$(document).ready(function(){

    loadTable();

});


function loadTable()
{
    $('#feedbackTable').DataTable({

        destroy:true,

        ajax:{
            url:"{{ route('admin.feedback.getData') }}",
            dataSrc:'data'
        },

        columns:[

            {data:'id'},

            {
                data:'feedback_question_type.type_name',
                defaultContent:''
            },

            {
                data:'question'
            },

            {
                data:'answer_type'
            },

            {
                data:null,
                render:function(data){

                    return `
                        <button
                            class="btn btn-warning btn-sm editBtn"
                            data-id="${data.id}"
                            data-question="${data.question}"
                            data-answer="${data.answer_type}"
                            data-type="${data.feedback_type_id}">
                            Edit
                        </button>
                    `;
                }
            }

        ]

    });
}


function questionRow()
{
    return `

    <div class="question-row border p-3 mb-3">

        <div class="row">

            <div class="col-md-5">

                <label>Question</label>

                <input type="text"
                       class="form-control question">

            </div>

            <div class="col-md-3">

                <label>Answer Type</label>

                <select class="form-control answer_type">

                    <option value="text">
                        Text
                    </option>

                    <option value="rating">
                        Rating
                    </option>

                    <option value="mcq">
                        MCQ
                    </option>

                </select>

            </div>

            <div class="col-md-2">

                <button type="button"
                        class="btn btn-danger removeQuestion mt-4">

                    Remove

                </button>

            </div>

        </div>

        <div class="mcq-section mt-3"
             style="display:none;">

            <input type="text"
                   class="form-control option_1 mb-2"
                   placeholder="Option 1">

            <input type="text"
                   class="form-control option_2 mb-2"
                   placeholder="Option 2">

            <input type="text"
                   class="form-control option_3 mb-2"
                   placeholder="Option 3">

            <input type="text"
                   class="form-control option_4 mb-2"
                   placeholder="Option 4">

            <input type="text"
                   class="form-control correct_answer"
                   placeholder="Correct Answer">

        </div>

    </div>

    `;
}


$('#addNewFeedback').click(function(){

    $('#feedback_id').val('');

    $('#feedback_type_id').val('');

    $('#questionContainer').html('');

    $('#questionContainer').append(questionRow());

    $('#feedbackModal').modal('show');

});


$('#addQuestion').click(function(){

    $('#questionContainer').append(questionRow());

});


$(document).on('click','.removeQuestion',function(){

    $(this)
        .closest('.question-row')
        .remove();

});


$(document).on('change','.answer_type',function(){

    let row = $(this)
        .closest('.question-row');

    if($(this).val() == 'mcq'){

        row.find('.mcq-section').show();

    }else{

        row.find('.mcq-section').hide();

    }

});


$('#saveFeedback').click(function(){

    let questions = [];

    $('.question-row').each(function(){

        let row = $(this);

        let item = {

            question:
                row.find('.question').val(),

            answer_type:
                row.find('.answer_type').val()

        };

        if(item.answer_type == 'mcq')
        {

            item.options = [

                {

                    option_1:
                        row.find('.option_1').val(),

                    option_2:
                        row.find('.option_2').val(),

                    option_3:
                        row.find('.option_3').val(),

                    option_4:
                        row.find('.option_4').val(),

                    correct_answer:
                        row.find('.correct_answer').val()

                }

            ];

        }

        questions.push(item);

    });

    let url =
        $('#feedback_id').val()
        ? "{{ route('admin.feedback.update') }}"
        : "{{ route('admin.feedback.add') }}";

    $.ajax({

        url:url,

        type:'POST',

        data:{

            _token:'{{ csrf_token() }}',

            id:$('#feedback_id').val(),

            feedback_type_id:
                $('#feedback_type_id').val(),

            questions:questions

        },

        success:function(response){

            alert(response.message);

            $('#feedbackModal')
                .modal('hide');

            $('#feedbackTable')
                .DataTable()
                .ajax
                .reload();

        },

        error:function(xhr){

            console.log(xhr.responseText);

        }

    });

});


$(document).on('click','.editBtn',function(){

    $('#feedback_id').val(
        $(this).data('id')
    );

    $('#feedback_type_id').val(
        $(this).data('type')
    );

    $('#questionContainer').html('');

    let html = questionRow();

    $('#questionContainer').append(html);

    $('.question').last().val(
        $(this).data('question')
    );

    $('.answer_type').last().val(
        $(this).data('answer')
    );

    $('#feedbackModal').modal('show');

});

</script>

@endsection
