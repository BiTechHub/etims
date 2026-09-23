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

    <a href="{{route('beds.management')}}" class="btn btn-success mb-3" style="margin-left:20px">Add Rooms</a>

    <div class="row">
        <div class="row mb-3">

    <div class="col-md-4">
        <select id="filter_block" class="form-control">
            <option value="">All Blocks</option>

            @foreach($blocks as $block)
                <option value="{{ $block->id }}">
                    {{ $block->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <select id="filter_room_type" class="form-control">
            <option value="">All Room Types</option>

            @foreach($roomTypes as $type)
                <option value="{{ $type->id }}">
                    {{ $type->types_of_rooms }}
                </option>
            @endforeach
        </select>
    </div>

</div>

    

      <div class="card">

     <div class="card-body">

<div class="table-container table-responsive">

   <table id="programme-table" class="table table-bordered table-hover">

        <thead>

            <tr>

                <th>ID</th>

              <th>Room Number</th>

                <th>Block</th>

                <th>Type Of Room</th>

                <th>Action</th>

            </tr>

        </thead>

        <tbody>

            <!-- Data will be loaded via AJAX -->

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

            const table = $('#programme-table').DataTable({

                processing: true,

                serverSide: true,

                ajax: {

                    url: '{{ route('room.getData') }}',

                    type: 'GET',
                     data: function(d) {

        d.block_id = $('#filter_block').val();
        d.room_type = $('#filter_room_type').val();

    },

                },

                columns: [

              { data: 'id', name: 'id' },

             

                { data: 'room_number', name: 'room_number' },

               

                  { data: 'block_name', name: 'block_name' },



                    { data: 'type_of_room', name: 'type_of_room' },





                    {

                        data: 'is_active',

                        name: 'is_active',

                        render: function(data, type, row) {

                            return data ?

                                `<button class="btn btn-sm btn-success toggle-status" data-id="${row.id}" data-status="1">Active</button>` :

                                `<button class="btn btn-sm btn-danger toggle-status" data-id="${row.id}" data-status="0">Inactive</button>`;

                        }

                    },

    //                 {

    //                     data: 'action',

    //                     name: 'action',

    //                     orderable: false,

    //                     searchable: false,

    //                     render: function(data, type, row) {

    //                         return ` 

    //     <div class="btn-group">

    //         <button 

    //             class="btn btn-sm btn-primary btn-action edit-btn" 

    //             data-id="${row.id}"

    //                 data-name="${row.name ?? ''}"

    //                   data-hindi_name="${row.hindi_name ?? ''}"

                  

    //         >

    //             <i class="fas fa-edit"></i> Edit

    //         </button>

    //         <button class="btn btn-sm btn-danger delete-btn btn-action" data-id="${row.id}">

    //             <i class="fas fa-trash"></i> Delete

    //         </button>

    //     </div>

    // `;

    //                     }



    //                 }

                ],

                responsive: true

            });

            $('#filter_block, #filter_room_type').change(function () {

    table.ajax.reload();

});

            // Handle toggle status click

            $('#programme-table').on('click', '.toggle-status', function() {

                const button = $(this);

                const id = button.data('id');

                const currentStatus = parseInt(button.data('status')); // Ensure integer value

                const newStatus = currentStatus === 1 ? 0 : 1; // Toggle status



                $.ajax({

                    url: `{{ url('admin/room/toggle-status') }}/${id}`,

                    type: 'POST',

                    data: {

                        _token: '{{ csrf_token() }}',

                        status: newStatus

                    },

                    success: function(response) {

                        alert('success', response.message);



                        // Toggle button appearance and text

                        button

                            .toggleClass('btn-success btn-danger')

                            .text(newStatus === 1 ? 'Active' : 'Inactive')

                            .data('status', newStatus);



                        table.ajax.reload(); // Ensure table is refreshed

                    },

                    error: function(xhr) {

                        alert('danger', xhr.responseJSON.message ||

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

