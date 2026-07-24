@extends('faculty.layouts.master')

@section('main-section')

{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote-lite.css"> --}}

<link rel="stylesheet" href="{{ asset('assets/summernote/summernote-lite.css') }}">

<div class="container">

  <div class="page-inner">

    <div class="page-header">

      <h3 class="fw-bold mb-3">Add Question</h3>

      <ul class="breadcrumbs mb-3">

        <li class="nav-home">

          <a href="#">

            <i class="icon-home"></i>

          </a>

        </li>

        <li class="separator">

          <i class="icon-arrow-right"></i>

        </li>

        <li class="nav-item">

          <a href="#">Question Management</a>

        </li>

        <li class="separator">

          <i class="icon-arrow-right"></i>

        </li>

        <li class="nav-item">

          <a href="#">Add Question</a>

        </li>

      </ul>

    </div>

    <div class="row">

      <div class="col-md-12">

        <div class="card">

          <div class="card-body">

           <form action="{{route('ff.questionAdd')}}" method="POST" enctype="multipart/form-data">

          @csrf

          <div class="row mb-3">

            <div class="col-md-4 form-group">

              <label for="department_id" class="form-label">Vertical </label>

              <select name="department_id" id="department_id" class="form-control">

                  <option value="">Select Vertical</option>

                  @foreach($departments as $department)

                      <option value="{{ $department->id }}">{{ $department->name}}</option>

                  @endforeach

              </select>

          </div>       

   

          <div class="col-md-2 form-group">

    <label for="programme_code" class="form-label">Code</label>

    <input type="text" id="programme_code" class="form-control" placeholder="Enter unique code">

</div>



<div class="col-md-6 form-group">

    <label for="programme_id" class="form-label">Select Programme</label>

    <select name="programme_id" id="programme_id" class="form-control">

        <option value="">Select Programme</option>

        @foreach($programmes as $programme)

            <option value="{{ $programme->id }}" data-code="{{ $programme->code }}">{{ $programme->title }}</option>

        @endforeach

    </select>

</div>



            <div class="col-md-12 mb-3 form-group">

              <label class="form-label">Question:</label>

              <span class="text-danger"> @error('question'){{$message}}@enderror</span>

              <input class="form-control form-control-lg" type="text" name="question" value="{{ old('question') }}"  />



            </div>

            <div class="col-md-6 mb-3 form-group">

              <label class="form-label">Option (A):</label>

              <span class="text-danger">@error('option_A'){{$message}}@enderror</span>

              <input class="form-control form-control-lg" type="text" name="option_A" value="{{old('option_A')}}" />



            </div>

            <div class="col-md-6 mb-3 form-group">

              <label class="form-label">Option (B):</label>

              <span class="text-danger">@error('option_B'){{$message}}@enderror</span>

              <input class="form-control form-control-lg" type="text" name="option_B" value="{{old('option_B')}}" />



            </div>

            <div class="col-md-6 mb-3 form-group">

              <label class="form-label">Options (C):</label>

              <span class="text-danger">@error('option_C'){{$message}}@enderror</span>

              <input class="form-control form-control-lg" type="text" name="option_C" value="{{old('option_C')}}" />



            </div>

            <div class="col-md-6 mb-3 form-group">

              <label class="form-label">Option (D):</label>

              <span class="text-danger">@error('option_D'){{$message}}@enderror</span>

              <input class="form-control form-control-lg" type="text" name="option_D" value="{{old('option_D')}}" />



            </div>



            <div class="col-md-6 mb-3 form-group">

              <label class="form-label"><b> Answer:</b></label>



              &nbsp;&nbsp;&nbsp;  <input class="form-check-input" type="radio" name="right_option" id="optionA" value="A">

              &nbsp;<label class="form-check-label" for="optionA">A</label>



              &nbsp;&nbsp;&nbsp; <input class="form-check-input" type="radio" name="right_option" id="optionB" value="B">

              &nbsp;<label class="form-check-label" for="optionB">B</label>



              &nbsp;&nbsp;&nbsp; <input class="form-check-input" type="radio" name="right_option" id="optionC" value="C">

              &nbsp;<label class="form-check-label" for="optionC">C</label>



              &nbsp;&nbsp;&nbsp; <input class="form-check-input" type="radio" name="right_option" id="optionD" value="D">

              &nbsp;<label class="form-check-label" for="optionD">D</label><br>

              <span class="text-danger">@error('right_option'){{$message}}@enderror</span>

            </div>

          </div>

          <div class="text-center mt-3">

            <button type="submit" class="btn btn-lg btn-primary"><i class="align-middle" data-feather="plus"></i><span class="align-middle"> Add</span></button>

          </div>

        </form>

            </div>

          

        </div>

      </div>

      <div class="card">

     <div class="card-body">

<div class="table-container table-responsive">

   <table id="question-table" class="table table-bordered table-hover">

    <thead>

      <tr>

        <th>ID</th>

        <th>Programme</th>

        <th>Question</th>

        <th>Right Option</th>

        <th>Action</th>

      </tr>

    </thead>

    <tbody></tbody>

  </table>

</div>



     </div>



      </div>

    </div>

  </div>

</div>

@endsection

@section('script')

<script src="{{ url('/admin') }}/assets/js/plugin/datatables/datatables.min.js"></script>

<script src="{{ url('/admin') }}/assets/js/kaiadmin.min.js"></script>

<script src="{{ url('/admin') }}/assets/js/setting-demo2.js"></script>
<script src="{{ asset('assets/summernote/summernote-lite.js') }}"></script>


<script>

  $(document).ready(function () {

    // CSRF Token setup for AJAX

    $.ajaxSetup({

      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }

    });



    // Initialize DataTable

    $('#question-table').DataTable({

      processing: true,

      serverSide: true,

      ajax: {

        url: '{{ route("ff.Question_get_data") }}',

        type: 'GET'

      },

      columns: [

        { data: 'id', name: 'id' },

        { data: 'department_id', name: 'department_id' },

        { data: 'question_title', name: 'question_title' },

        { data: 'right_option', name: 'right_option' },

        {

          data: 'id',

          name: 'action',

          orderable: false,

          searchable: false,

          render: function (data, type, row) {

            return `

              <button class="btn btn-sm btn-danger delete-btn btn-action" data-id="${data}">

                <i class="fas fa-trash"></i> Delete

              </button>

            `;

          }

        }

      ]

    });



    // Delete button handler

    $('#question-table').on('click', '.delete-btn', function () {

      var id = $(this).data('id');



      if (confirm('Are you sure you want to delete this question?')) {

        $.ajax({

          url: '{{ url("admin/question/delete") }}/' + id,

          type: 'POST',

          data: {

            _token: '{{ csrf_token() }}',

            _method: 'post'

          },

          success: function (response) {

            alert('Deleted successfully!');

            $('#question-table').DataTable().ajax.reload(null, false);

          },

          error: function (xhr, status, error) {

            alert('Error deleting question.');

            console.error('Error status: ' + status);

            console.error('Error response: ' + xhr.responseText);

          }

        });

      }

    });

  });

</script>



<script>

document.getElementById('programme_code').addEventListener('keyup', function () {

    let code = this.value.trim();



    if (code !== '') {

        fetch(`/get-programme-by-code/${code}`)

            .then(response => response.json())

            .then(data => {

                if (data && data.id) {

                    let select = document.getElementById('programme_id');

                    select.value = data.id;

                } else {

                    alert('No programme found with this code.');

                }

            })

            .catch(error => {

                console.error('Error fetching programme:', error);

            });

    }

});

</script>





@endsection

