@extends('admin.layouts.master')

@section('main-section')

<div class="container">

  <div class="page-inner">

    <div class="page-header">

      <h3 class="fw-bold mb-3">Add SiteContent</h3>

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

          <a href="#">Manage SiteContent</a>

        </li>

        <li class="separator">

          <i class="icon-arrow-right"></i>

        </li>

        <li class="nav-item">

          <a href="#">Add SiteContent</a>

        </li>

      </ul>

    </div>

    <div class="row">

      <div class="col-md-12">

        <div class="card">

          <div class="card-body">

            <h1 id="form-title"></h1>

                  <form id="agency-group-form" action="{{ route('type.store') }}" method="POST">

                @csrf

                <div class="row">



                    <div class="col-md-6 form-group">

                        <label for="name" class="form-label">Room type<span class="text-danger">*</span></label>

                        <input type="text" class="form-control" id="types_of_rooms" name="types_of_rooms" placeholder="Enter Room Type"

                            value="{{ old('types_of_rooms') }}" required>

                    </div>
<div class="col-md-6 form-group">

                        <label for="avaible_beds" class="form-label">Available Beds <span class="text-danger">*</span></label>

                        <input type="number" class="form-control" id="avaible_beds" name="avaible_beds" placeholder="Enter Available Beds"

                            value="{{ old('avaible_beds')??'1' }}" required>

                    </div>
                

                </div>

                <button type="submit" class="btn btn-primary">Save type of room</button>

            </form>

            </div>

          

        </div>

      </div>  

      <div class="card">

     <div class="card-body">

<div class="table-container table-responsive">

     <table id="agency-group-table" class="table table-bordered table-hover">

        <thead>

            <tr>

                <th>ID</th>

              

                <th>Name</th>
                <th>Available Beds</th>

                {{-- <th>Action</th> --}}

             

            </tr>

        </thead>

        <tbody>

        </tbody>

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





    <script type="text/javascript">

        $(document).ready(function() {

            // CSRF Token setup for AJAX

            $.ajaxSetup({

                headers: {

                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                }

            });



            // Initialize DataTable

            const table = $('#agency-group-table').DataTable({

                processing: true,

                serverSide: true,

                ajax: {

                    url: '{{ route('type.getData') }}',

                    type: 'GET'

                },

                columns: [{

                        data: 'id',

                        name: 'id'

                    },

                    // { data: 'agency_group_name', name: 'agencyGroup.name' },



                    {

                        data: 'types_of_rooms',

                        name: 'name'

                    },
                    {
                        data: 'avaible_beds',
                        name: 'avaible_beds'
                    }



                      

               





                

                  

                ],

                responsive: true

            });

            // Handle toggle status click

            $('#agency-group-table').on('click', '.toggle-status', function() {

                const button = $(this);

                const id = button.data('id');

                const currentStatus = parseInt(button.data('status')); // Ensure integer value

                const newStatus = currentStatus === 1 ? 0 : 1; // Toggle status



                $.ajax({

                    url: `{{ url('admin/agency-type/toggle-status') }}/${id}`,

                    type: 'POST',

                    data: {

                        _token: '{{ csrf_token() }}',

                        status: newStatus

                    },

                    success: function(response) {

                        showAlert('success', response.message);



                        // Toggle button appearance and text

                        button

                            .toggleClass('btn-success btn-danger')

                            .text(newStatus === 1 ? 'Active' : 'Inactive')

                            .data('status', newStatus);



                        table.ajax.reload(); // Ensure table is refreshed

                    },

                    error: function(xhr) {

                        showAlert('danger', xhr.responseJSON.message ||

                            'Failed to update status');

                    }

                });

            });



            // Handle edit button click

            $('#agency-group-table').on('click', '.edit-btn', function() {

                const id = $(this).data('id');



                const name = $(this).data('name');

                const hindi_name=$(this).data('hindi_name');



                // Change form title to "Edit"

                $('#form-title').text('✏️ Edit Agency Type');



                // Update the form action to point to the update route

                $('#agency-group-form').attr('action', '{{ url('admin/agency-type/edit') }}/' + id);



                // Pre-fill the form with the existing values



                $('#name').val(name);

                $('#hindi_name').val(hindi_name);



                // Change the form method to POST (if editing is done via POST instead of PUT)

                $('#agency-group-form').append('<input type="hidden" name="_method" value="POST">');

            });





            // Handle delete button click

            // Handle delete button click

            $('#agency-group-table').on('click', '.delete-btn', function() {

                var id = $(this).data('id');



                if (confirm('Are you sure you want to delete this agency type?')) {

                    $.ajax({

                        url: '{{ url('admin/agency-type/delete') }}/' + id,

                        type: 'POST',

                        data: {

                            _token: '{{ csrf_token() }}'

                        },

                        success: function(response) {

                            alert('Deleted!');

                            $('#agency-group-table').DataTable().ajax.reload(null, false);

                        },

                        error: function(xhr) {

                            alert('Error deleting agency ');

                        }

                    });

                }

            });



            // Add this helper function if you don't already have it

            function showAlert(type, message) {

                const alertHtml = `

        <div class="alert alert-${type} alert-dismissible fade show" role="alert">

            ${message}

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">

                <span aria-hidden="true">&times;</span>

            </button>

        </div>

    `;



                // Append to your alert container

                $('#alert-container').html(alertHtml);



                // Auto-dismiss after 5 seconds

                setTimeout(() => {

                    $('.alert').alert('close');

                }, 5000);

            }



        });

    </script>





    





@endsection

