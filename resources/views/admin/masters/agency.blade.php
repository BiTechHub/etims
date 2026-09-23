@extends('admin.layouts.master')

@section('main-section')


    <link rel="stylesheet" href="{{ asset('assets/summernote/summernote-lite.css') }}">
    <style id="0k1m1t">
        /* Smaller Table Font */
        #agency-group-table {
            font-size: 8px;
        }

        .table td,
        .table th {
            font-size: 11px;
        }

        .table thead th {
            font-size: 11px;
        }

        /* No Wrap Text */
        #agency-group-table td,
        #agency-group-table th {
            white-space: nowrap;
            vertical-align: middle;
        }

        /* Ellipsis */
        .text-ellipsis {
            max-width: 150px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* Action Dropdown */
        .action-dropdown .dropdown-menu {
            min-width: 120px;
        }

        .table>tbody>tr>td,
        .table>tbody>tr>th {
            padding: 6px 4px !important;
        }
    </style>
    <div class="container">

        <div class="page-inner">

            <div class="page-header">

                <h3 class="fw-bold mb-3">Add Agency</h3>

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

                        <a href="#">Master</a>

                    </li>

                    <li class="separator">

                        <i class="icon-arrow-right"></i>

                    </li>

                    <li class="nav-item">

                        <a href="#">Add Agency</a>

                    </li>

                </ul>

            </div>

            <div class="row">

                <h1 id="form-title"></h1>

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


                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">

                        {{ session('error') }}

                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">

                            <span aria-hidden="true">&times;</span>

                        </button>

                    </div>
                @endif
                <div class="col-md-12">

                    <div class="card">

                        <div class="card-body">

                            <form id="agency-group-form" action="{{ route('agency.store') }}" method="POST">

                                @csrf



                                <div class="row">

                                    <div class="col-md-3 form-group">

                                        <label for="agency_type" class="form-label">Agency Type <span
                                                class="text-danger">*</span></label>

                                        <select class="form-control" id="agency_type" name="agency_type_id" required>

                                            <option value="">Select Agency Type</option>

                                            @foreach ($agencyTypes as $agencyType)
                                                <option value="{{ $agencyType->id }}"
                                                    {{ old('agency_type_id') == $agencyType->id ? 'selected' : '' }}>

                                                    {{ $agencyType->name }}

                                                </option>
                                            @endforeach

                                        </select>

                                    </div>



                                    <div class="col-md-3 form-group">

                                        <label for="name" class="form-label">Agency Name <span
                                                class="text-danger">*</span></label>

                                        <input type="text" class="form-control" id="name" name="name"
                                            placeholder="Enter Agency Name" value="{{ old('name') }}" required>

                                    </div>



                                    <div class="col-md-3 form-group">

                                        <label for="user_name" class="form-label">User Name <span
                                                class="text-danger">*</span></label>

                                        <input type="text" class="form-control" id="user_name" name="user_name"
                                            placeholder="Enter User Name" value="{{ old('user_name') }}" required>

                                    </div>



                                    <div class="col-md-3 form-group">

                                        <label for="sponsor_bank" class="form-label">Sponsor Bank</label>

                                        <input type="text" class="form-control" id="sponsor_bank" name="sponsor_bank"
                                            placeholder="Enter Sponsor Bank" value="{{ old('sponsor_bank') }}">

                                    </div>

                                </div>



                                <div class="row">

                                    <div class="col-md-3 form-group">

                                        <label for="chairman" class="form-label">Head of Origination</label>

                                        <input type="text" class="form-control" id="chairman" name="chairman"
                                            placeholder="Enter Chairman" value="{{ old('chairman') }}">

                                    </div>



                                    <div class="col-md-3 form-group">

                                        <label for="chairman" class="form-label">Designation</label>

                                        <input type="text" class="form-control" id="designation" name="designation"
                                            placeholder="Enter Chairman" value="{{ old('designation') }}">

                                    </div>



                                    <div class="col-md-3 form-group">

                                        <label for="address" class="form-label">Address</label>

                                        <input type="text" class="form-control" id="address" name="address"
                                            placeholder="Enter Address" value="{{ old('address') }}">

                                    </div>



                                    <div class="col-md-3 form-group">

                                        <label for="state" class="form-label">State <span
                                                class="text-danger">*</span></label>

                                        <select class="form-control" id="state" name="state" required>

                                            <option value="">Select State</option>

                                            {{-- States will be loaded via AJAX --}}

                                        </select>

                                    </div>







                                </div>



                                <div class="row">



                                    <div class="col-md-3 form-group">

                                        <label for="city" class="form-label">City <span
                                                class="text-danger">*</span></label>

                                        <select class="form-control" id="city" name="city" required>

                                            <option value="">Select City</option>

                                            {{-- Cities will be loaded based on selected state --}}

                                        </select>

                                    </div>





                                    <div class="col-md-3 form-group">

                                        <label for="pincode" class="form-label">Pincode</label>

                                        <input type="text" class="form-control" id="pincode" name="pincode"
                                            placeholder="Enter Pincode" value="{{ old('pincode') }}">

                                    </div>



                                    <div class="col-md-3 form-group">
                                        <label class="form-label">
                                            HR Department Phone <span class="text-danger">*</span>
                                        </label>

                                        <input type="text" class="form-control" name="phone" id="phone"
                                            placeholder="Enter Mobile or Landline, separated by commas"
                                            value="{{ old('phone') }}" required>

                                        <small class="text-muted">
                                            Example:
                                            9876543210, 0522-2234567, +91-522-2234567
                                        </small>
                                    </div>

                                    <div class="col-md-3 form-group">

                                        <label for="emailid" class="form-label">Nominating Authority Email-Id <span
                                                class="text-danger">*</span></label>

                                        <input type="email" class="form-control" id="emailid" name="emailid"
                                            placeholder="Enter Email" value="{{ old('emailid') }}" required>

                                    </div>



                                    {{-- <div class="col-md-3 form-group">

                                        <label for="country" class="form-label">Country</label>

                                        <input type="text" class="form-control" id="country" name="country"
                                            placeholder="Enter Country" value="{{ old('country') }}">

                                    </div>

                                </div> --}}









                                    <div class="row">

                                        <div class="col-md-6 form-group">

                                            <label for="cc_email" class="form-label">CC Emails</label>

                                            <input type="text" class="form-control" id="cc_email" name="cc_email"
                                                placeholder="Enter CC Emails, separated by commas"
                                                value="{{ old('cc_email') }}">

                                            <small class="form-text text-muted">Enter multiple email addresses separated by
                                                commas (e.g., user1@example.com, user2@example.com)</small>

                                        </div>



                                    </div>







                                    {{-- <div class="col-md-3 form-group">

                                    <label for="fax" class="form-label">Fax</label>

                                    <input type="text" class="form-control" id="fax" name="fax" placeholder="Enter Fax"
                                        value="{{ old('fax') }}">

                                </div> --}}







                                    <div class="mt-3">
                                        <button type="submit" class="btn btn-primary px-4">
                                            Save Agency
                                        </button>
                                    </div>

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

                                        <th>S.no</th>

                                        <th>Name</th>
                                        <th>Username</th>


                                        <th>Agency Type </th>

                                        <th>Sponor Bank</th>

                                        <th>Chairman</th>

                                        <th>Address</th>

                                        <th>Email-Id</th>

                                        <th>Status</th>

                                        <th>Active</th>

                                        {{-- <th>Conformation</th> --}}

                                        <th>Action</th>

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

    <script src="{{ asset('assets/summernote/summernote-lite.js') }}"></script>

    <script>
        $(document).ready(function() {

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

            $('#state').on('change', function() {

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

                    url: '{{ route('agency.getData') }}',

                    type: 'GET'

                },

                columns: [

                   {
    data: null,
    name: 'id',
    orderable: false,
    searchable: false,
    render: function(data, type, row, meta) {
        return meta.row + meta.settings._iDisplayStart + 1;
    }
},

                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'user_name',
                        name: 'Username'
                    },



                    {
                        data: 'agency_type_name',
                        name: 'agencyType.name'
                    },

                    {
                        data: 'sponsor_bank',
                        name: 'sponsor_bank'
                    },

                    {
                        data: 'chairman',
                        name: 'chairman'
                    },

                    {
                        data: 'address',
                        name: 'address'
                    },

                    {

                        data: null,

                        name: 'emailid',

                        render: function(data, type, row) {

                            return `${row.emailid} <br><strong>CC:</strong> ${row.cc_email}`;

                        }

                    },



                    {

                        data: 'approved',

                        name: 'approved',

                        render: function(data, type, row) {

                            if (data == 1) {

                                return `<span class="badge bg-success">Approved</span>`;

                            } else {

                                return `<span class="badge bg-danger">Not Approved</span>`;

                            }

                        }

                    },



                    {

                        data: 'is_active',

                        name: 'is_active',

                        render: function(data, type, row) {

                            return data ?

                                `<button class="btn btn-sm btn-success toggle-status" data-id="${row.id}" data-status="1">Active</button>` :

                                `<button class="btn btn-sm btn-danger toggle-status" data-id="${row.id}" data-status="0">Inactive</button>`;

                        }

                    },

                    {

                        data: null,

                        name: 'actions',

                        orderable: false,

                        searchable: false,
                        render: function(data, type, row) {

                            let approvalButton = '';

                            if (row.approved == 1) {

                                approvalButton = `
                    <button class="dropdown-item text-danger reject-btn" data-id="${row.id}">
                        <i class="fas fa-times"></i> Reject
                    </button>`;
                            } else {

                                approvalButton = `
                    <button class="dropdown-item text-success approve-btn" data-id="${row.id}">
                        <i class="fas fa-check"></i> Approve
                    </button>`;
                            }

                            return `
                <div class="dropdown action-dropdown">

                    <button class="btn btn-sm btn-secondary dropdown-toggle"
                            type="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false">

                        <i class="fas fa-ellipsis-v"></i>
                    </button>

                    <ul class="dropdown-menu">

                        <li>
                            ${approvalButton}
                        </li>

                        <li>
                            <button 
                                class="dropdown-item edit-btn"
                                data-id="${row.id}"
                                data-name="${row.name ?? ''}"
                                data-user_name="${row.user_name}"
                                data-agency_type_id="${row.agency_type_id ?? ''}"
                                data-sponsor_bank="${row.sponsor_bank ?? ''}"
                                data-chairman="${row.chairman ?? ''}"
                                data-address="${row.address ?? ''}"
                                data-state="${row.state ?? ''}"
                                data-city="${row.city ?? ''}"
                                data-pincode="${row.pincode ?? ''}"
                                data-phone="${row.phone ?? ''}"
                                data-emailid="${row.emailid ?? ''}"
                                data-country="${row.country ?? ''}"
                                data-fax="${row.fax ?? ''}"
                                data-cc_email="${row.cc_email ?? ''}"
                                data-designation="${row.designation ?? ''}"
                            >
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        </li>

                        <li>
                            <button class="dropdown-item text-danger delete-btn"
                                    data-id="${row.id}">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </li>

                    </ul>

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

                    url: `{{ url('admin/agency/toggle-status') }}/${id}`,

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

                        alert('danger', xhr.responseJSON.message || 'Failed to update status');

                    }

                });

            });



            // Handle edit button click

            $('#agency-group-table').on('click', '.edit-btn', function() {

                const id = $(this).data('id');

                const agencytype = $(this).data('agency_type_id');

                const name = $(this).data('name');

                const username = $(this).data('user_name');

                const bank = $(this).data('sponsor_bank');

                const chairman = $(this).data('chairman');

                const address = $(this).data('address');

                const state = $(this).data('state');

                const city = $(this).data('city');

                const pincode = $(this).data('pincode');

                const phone = $(this).data('phone');

                const emailid = $(this).data('emailid');

                const country = $(this).data('country');

                const fax = $(this).data('fax');

                const ccmail = $(this).data('cc_email');

                const designation = $(this).data('designation');



                // Change form title to "Edit"

                $('#form-title').text('✏️ Edit Agency');



                // Update the form action to point to the update route

                $('#agency-group-form').attr('action', '{{ url('admin/agency/edit') }}/' + id);



                // Pre-fill the form with the existing values

                // $('#agency_type_id').val(agencytype);

                $('#agency_type').val(agencytype);



                $('#name').val(name);

                $('#user_name').val(username);

                $('#sponsor_bank').val(bank);

                $('#chairman').val(chairman);

                $('#address').val(address);

                $('#state').val(state);

                $('#city').val(city);

                $('#pincode').val(pincode);

                $('#phone').val(phone);

                $('#emailid').val(emailid);

                $('#country').val(country);

                $('#fax').val(fax);

                $('#cc_email').val(ccmail);

                $('#designation').val(designation);



                // Change the form method to POST (if editing is done via POST instead of PUT)

                $('#agency-group-form').append('<input type="hidden" name="_method" value="POST">');

            });





            // Handle delete button click

            // Handle delete button click

            $('#agency-group-table').on('click', '.delete-btn', function() {

                var id = $(this).data('id');



                if (confirm('Are you sure you want to delete this agency group?')) {

                    $.ajax({

                        url: '{{ url('admin/agency/delete') }}/' + id,

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

            // Approve
            $('#agency-group-table').on('click', '.approve-btn', function() {
                const id = $(this).data('id');
                if (confirm('Are you sure you want to approve this agency?')) {
                    $.post(`/admin/agency/approve/${id}`, {
                            _token: '{{ csrf_token() }}'
                        })
                        .done(function(response) {
                            alert(response.message);
                            table.ajax.reload(null, false);
                        })
                        .fail(function(xhr) {
                            console.log(xhr.responseText);
                            alert('Error: ' + (xhr.responseJSON?.message ||
                                'Approve failed. Check console/log.'));
                        });
                }
            });



            // Reject
         // Reject
$('#agency-group-table').on('click', '.reject-btn', function () {
    const id = $(this).data('id');
    if (confirm('Are you sure you want to reject this agency?')) {
        $.post(`/admin/agency/reject/${id}`, {   // ← yahan /admin/ wapas jodiye
            _token: '{{ csrf_token() }}'
        })
        .done(function (response) {
            alert(response.message);
            table.ajax.reload(null, false);
        })
        .fail(function (xhr) {
            console.log(xhr.responseText);
            alert('Error: ' + (xhr.responseJSON?.message || 'Reject failed. Check console.'));
        });
    }
});




        });
    </script>
@endsection
