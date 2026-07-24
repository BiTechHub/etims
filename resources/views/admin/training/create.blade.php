@extends('admin.layouts.master')
@section('main-section')
<link rel="stylesheet" href="{{ asset('assets/summernote/summernote-lite.css') }}"><div class="container">
  <div class="page-inner">
    <div class="page-header">
      <h3 class="fw-bold mb-3">Add Training</h3>
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
          <a href="#">Add Training</a>
        </li>
        <li class="separator">
          <i class="icon-arrow-right"></i>
        </li>
        {{-- <li class="nav-item">
          <a href="#">Add SiteContent</a>
        </li> --}}
      </ul>
    </div>
    <h1 id="form-title"></h1>
    <div class="row">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
      <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.training.store') }}"
                        method="POST"
                        enctype="multipart/form-data">
                    @csrf

                    <div class="row">

                        <!-- Program Title -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Program Title</label>
                                <input type="text" name="program_title" class="form-control" required>
                            </div>
                        </div>

                        <!-- Area -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Area</label>
                                <input type="text" name="area" class="form-control" required>
                            </div>
                        </div>

                    </div>


                    <div class="row">

                        <!-- From Date -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>From Date</label>
                                <input type="date" name="from_date" class="form-control" required>
                            </div>
                        </div>

                        <!-- To Date -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>To Date</label>
                                <input type="date" name="to_date" class="form-control" required>
                            </div>
                        </div>

                    </div>


                    <div class="row">

                        <!-- Group -->

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Group</label>
                                <input type="text" name="group" class="form-control" required>
                            </div>
                        </div>

                        <!-- Location -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Location</label>
                                <select name="location" class="form-control" required>
                                    <option value="">Select Location</option>
                                    <option value="In House">In House</option>
                                    <option value="On-Location">On-Location</option>
                                    <option value="On-line">On-line</option>
                                    <option value="In Campus">In Campus</option>
                                </select>
                            </div>
                        </div>

                    </div>


                    <div class="row">

                        <!-- Announcement Letter -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Announcement Letter (PDF)</label>
                                <input type="file"
                                    name="announcement_letter"
                                    class="form-control"
                                    accept="application/pdf">
                            </div>
                        </div>

                        <!-- Announcement Status -->
                        {{-- <div class="col-md-6">
                            <div class="form-group">
                                <label>Announcement Status</label>
                                <select name="announcement_status" class="form-control" required>
                                    <option value="">Select Status</option>
                                    <option value="published">Published</option>
                                    <option value="draft">Draft</option>
                                    <option value="closed">Closed</option>
                                </select>
                            </div>
                        </div> --}}

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Announcement Status</label>
                                <input type="text" name="announcement_status" class="form-control" required>
                            </div>
                        </div>

                    </div>


                    <button class="btn btn-primary">Save</button>

                    </form>

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
        $.get('/states', function(states) {
        states.forEach(function(state) {
            $('#state').append(new Option(state.name, state.id));
        });

        // Pre-select old value if available
        const oldState = "{{ old('state') }}";
        if (oldState) {
            $('#state').val(oldState).trigger('change');
        }
    });   

    // On state change, load corresponding cities
    $('#state').on('change', function () {
        var stateId = $(this).val();
        $('#city').empty().append(new Option('Select City', ''));

        if (stateId) {
            $.get(`/states/${stateId}/districts`, function(cities) {
                cities.forEach(function(city) {
                    $('#city').append(new Option(city.name, city.name));
                });

                // Pre-select old value if available
                const oldCity = "{{ old('city') }}";
                if (oldCity) {
                    $('#city').val(oldCity);
                }
            });
        }
    });
    });
</script>

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
                url: '{{ route("user.getData") }}',
                type: 'GET'
            },
            columns: [
                { data: 'id', name: 'id' },
                
              
                       {
            data: null,
            name: 'name',
            render: function (data, type, row) {
                return `
                    <div>
                        <strong>${row.name}</strong><br>
                        <span style="color: #555;">${row.hindi_name}</span>
                    </div>
                `;
            }
        },
                { data: 'user_type', name: 'user_type' },
                { data: 'username', name: 'username' },
                { data: 'email', name: 'email' },

                {
                    data: 'is_active',
                    name: 'is_active',
                    visible: true,
                    render: function(data, type, row) {
                        return data ? 
                            `<button class="btn btn-sm btn-success toggle-status" data-id="${row.id}" data-status="1">Active</button>` : 
                            `<button class="btn btn-sm btn-danger toggle-status" data-id="${row.id}" data-status="0">Inactive</button>`;
                    }
                },
                { 
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return ` 
                           <div class="btn-group">
    <button class="btn btn-sm btn-primary btn-action edit-btn"
        data-id="${row.id}"
        data-user_type="${row.user_type}"
        data-username="${row.username}"
        data-name="${row.name}"
         data-hindi_name="${row.hindi_name}"
        data-email="${row.email}"
        data-dob="${row.dob}"
        data-designation="${row.designation}"
        data-address="${row.address}"
        data-state="${row.state}"
        data-city="${row.city}"
        data-pincode="${row.pincode}"
        data-phone="${row.phone}"
        data-image="${row.image}"
        data-password="${row.password}">
        <i class="fas fa-edit"></i> Edit
    </button>
    <button class="btn btn-sm btn-danger delete-btn btn-action" data-id="${row.id}">
        <i class="fas fa-trash"></i> Delete
    </button>
</div>

                        `;
                    }
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
                    url: `{{ url('admin/user/toggle-status') }}/${id}`,
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
    const user_type = $(this).data('user_type');
    const username = $(this).data('username');
    const name = $(this).data('name');
    const hindi_name = $(this).data('hindi_name');
    const email = $(this).data('email');
    const dob = $(this).data('dob');
    const designation = $(this).data('designation');
    const address = $(this).data('address');
    const state = $(this).data('state');
    const city = $(this).data('city');
    const pincode = $(this).data('pincode');
    const phone = $(this).data('phone');
    const image = $(this).data('image');
    const password = $(this).data('password');

    // Change form title to "Edit"
    $('#form-title').text('✏️ Edit User');

    // Update the form action to point to the update route
    $('#agency-group-form').attr('action', '{{ url("admin/user/edit") }}/' + id);

    // Pre-fill the form with the existing values
    $('#user_type').val(user_type);
    $('#username').val(username);
    $('#name').val(name);
    $('#hindi_name').val(hindi_name);
    $('#email').val(email);
    $('#dob').val(dob);
    $('#designation').val(designation);
    $('#address').val(address);
    $('#state').val(state);
    $('#city').val(city);
    $('#pincode').val(pincode);
    $('#phone').val(phone);
    $('#image').val(image); // This might need additional handling if you're uploading an image
    $('#password').val(password);

    // Change the form method to PUT for editing
    $('#agency-group-form').append('<input type="hidden" name="_method" value="POST">');
});


        // Handle edit button click
        $('#agency-group-table').on('click', '.edit-btn', function() {
    const id = $(this).data('id');
    const user_type = $(this).data('user_type');
    const username = $(this).data('username');
    const name = $(this).data('name');
    const hindi_name = $(this).data('hindi_name');
    const email = $(this).data('email');
    const dob = $(this).data('dob');
    const designation = $(this).data('designation');
    const address = $(this).data('address');
    const state = $(this).data('state');
    const city = $(this).data('city');
    const pincode = $(this).data('pincode');
    const phone = $(this).data('phone');
    const image = $(this).data('image');
    const password = $(this).data('password');

    // Change form title to "Edit"
    $('#form-title').text('✏️ Edit User');

    // Update the form action to point to the update route
    $('#agency-group-form').attr('action', '{{ url("admin/user/edit") }}/' + id);

    // Pre-fill the form with the existing values
    $('#user_type').val(user_type);
    $('#username').val(username);
    $('#name').val(name);
    $('#hindi_name').val(hindi_name);
    $('#email').val(email);
    $('#dob').val(dob);
    $('#designation').val(designation);
    $('#address').val(address);
    $('#state').val(state);
    $('#city').val(city);
    $('#pincode').val(pincode);
    $('#phone').val(phone);
    $('#image').val(image); // This might need additional handling if you're uploading an image
    $('#password').val(password);

    // Change the form method to PUT for editing
    $('#agency-group-form').append('<input type="hidden" name="_method" value="POST">');
});

        // Handle delete button click
        $('#agency-group-table').on('click', '.delete-btn', function() {
            var id = $(this).data('id');
            
            if (confirm('Are you sure you want to delete this user?')) {
                $.ajax({
                    url: '{{ url("admin/user/delete") }}/' + id,
                    type: 'post',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                alert('Deleted!');
                $('#agency-group-table').DataTable().ajax.reload(null, false);
            },
            error: function(xhr) {
                alert('Error deleting deparment');
            }
                });
            }
        });

        // Helper function to show alerts
        function showAlert(type, message) {
            $('#alert-container').html(`
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            `);
            
            // Auto-dismiss alert after 5 seconds
            setTimeout(function() {
                $('.alert').alert('close');
            }, 5000);
        }
    });
    
    $(document).ready(function () {
    // Show/hide new user type input
    $('#user_type').change(function () {
        if ($(this).val() === 'new') {
            $('#new_user_type_div').show();
        } else {
            $('#new_user_type_div').hide();
        }
    });  

    // On form submit, if "new" is selected, override user_type with new_user_type value
    $('#agency-group-form').submit(function (e) {
        const selectedType = $('#user_type').val();
        const newType = $('#new_user_type').val();

        if (selectedType === 'new') {
            if (!newType.trim()) {
                alert('Please enter a new user type.');
                e.preventDefault();
                return false;
            }

            // Create a hidden input to submit the new type as the actual user_type
            $('<input>').attr({
                type: 'hidden',
                name: 'user_type',
                value: newType.trim()
            }).appendTo('#agency-group-form');
        }
    });
      
});
</script>
<script>
$(document).ready(function () {
 $('#name').on('input', function () {
    const name = $(this).val();

    if (name.length > 0) {
        $.ajax({
            url: '/translate-to-hindi',
            type: 'POST',
            data: {
                text: name,
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                $('#hindi_name').val(response.hindi);
            },
            error: function () {
                console.log("Translation failed.");
            }
        });
    } else {
        $('#hindi_name').val('');
    }
});

});
</script>


@endsection
