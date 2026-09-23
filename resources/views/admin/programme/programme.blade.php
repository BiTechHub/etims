@extends('admin.layouts.master')
@section('title', 'User Management')

@push('styles')
   <link rel="stylesheet" href="{{ asset('assets/flatpickr/flatpickr.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/flatpickr/plugins/monthSelect/style.css') }}">
    <style>
            .container-wrapper {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        width: 100%;
        box-sizing: border-box;
        font-size: 18px !important;
    }

    /* Sidebar Styling */
    .sidebar {
        flex: 0 2 250px;
        background:#349e4c;
        padding: 20px;
        border-radius: 16px;
        color: white;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        box-sizing: border-box;
    }

    .sidebar-title {
        font-size: 22px;
        font-weight: bold;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .sidebar-title i {
        font-size: 24px;
        color: #25ba43;
    }

    /* Form Styling */
    .form-container {
        flex: 1 1 auto;
        background: #fff;
        padding: 20px;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        box-sizing: border-box;
        margin-left: 20px;
    }

    .form-container:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
    }

    .form-title {
    display: flex;
    align-items: center;
    justify-content: flex-start; /* aligns content to the left */
    font-size: 24px;
    font-weight: 700;
    color: #fff;
    padding: 20px;
    margin-bottom: 30px;
    background: linear-gradient(135deg, #1d976c, #027630);
    border-radius: 12px;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.15);
    transition: background 0.3s ease;
}

.form-title::before {
    content: "📝";
    font-size: 26px;
    margin-right: 10px;
}

.form-title:hover {
    background: linear-gradient(135deg, #1488cc, #2b32b2);
}



    /* Table Styling (table style starts here) */
    /* Table Styling */
.table-container {
    margin: 40px auto;
    padding: 20px;
    border-radius: 16px;
   
    font-size:16px;
    background: #ffffff;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    text-align: center; /* Ensures the table content is centered */
}

#agency-group-table {
    width: 100%;
    border-radius: 12px;
    overflow: hidden;
    margin: 0 auto; /* Ensures table is centered within the container */
}

/* Table Header */
#agency-group-table thead {
    background: linear-gradient(135deg, #2c3e50, #4a6491);
    color: white;
    font-weight:bold;
}

#agency-group-table thead th {
    padding: 15px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border: none;
    position: relative;
    text-align: center; /* Center the header text */
}

/* Table Body */
#agency-group-table tbody tr {
    transition: all 0.2s ease;
    background: white;
}

#agency-group-table tbody tr:nth-child(even) {
    background: #f9fafc;
}

#agency-group-table tbody tr:hover {
    background: #f1f7fe;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

#agency-group-table tbody td {
    padding: 12px 15px;
    border-bottom: 1px solid #eef2f7;
    vertical-align: middle;
    text-align: center; /* Center the content in the table cells */
}

    #agency-group-table thead {
        background: linear-gradient(135deg, #2c3e50, #4a6491);
        color: white;
    }

    #agency-group-table thead th {
        padding: 15px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: none;
        position: relative;
    }

    #agency-group-table thead th:not(:last-child)::after {
        content: "";
        position: absolute;
        right: 0;
        top: 25%;
        height: 50%;
        width: 1px;
        background: rgba(255, 255, 255, 0.2);
    }

    #agency-group-table tbody tr {
        transition: all 0.2s ease;
        background: white;
    }

    #agency-group-table tbody tr:nth-child(even) {
        background: #f9fafc;
    }

    #agency-group-table tbody tr:hover {
        background: #f1f7fe;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    #agency-group-table tbody td {
        padding: 12px 15px;
        border-bottom: 1px solid #eef2f7;
        vertical-align: middle;
    }

    /* Badge Styling */
    .badge {
        padding: 6px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 0.3px;
    }

    .badge-success {
        background-color: #28a745;
    }

    .badge-danger {
        background-color: #dc3545;
    }

    /* Button Styling */
    .btn-action {
        padding: 6px 12px;
        font-size: 13px;
        border-radius: 6px;
        margin: 2px;
        transition: all 0.2s;
    }

    .btn-action:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .btn-primary {
        background-color: #3490dc;
        border-color: #3490dc;
    }

    .btn-danger {
        background-color: #e3342f;
        border-color: #e3342f;
    }
    .alert {
    position: relative;
    padding: 0.75rem 1.25rem;
    margin-bottom: 1rem;
    border: 1px solid transparent;
    border-radius: 0.25rem;
    transition: opacity 0.15s linear;
}

.alert-success {
    color: #155724;
    background-color: #d4edda;
    border-color: #c3e6cb;
}

.alert-danger {
    color: #721c24;
    background-color: #f8d7da;
    border-color: #f5c6cb;
}

.alert-dismissible {
    padding-right: 4rem;
}

.alert-dismissible .close {
    position: absolute;
    top: 0;
    right: 0;
    padding: 0.75rem 1.25rem;
    color: inherit;
}

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        #agency-group-table thead th {
            padding: 12px 8px;
            font-size: 14px;
        }

        #agency-group-table tbody td {
            padding: 10px 8px;
            font-size: 14px;
        }

        .btn-action {
            padding: 4px 8px;
            font-size: 12px;
        }
    }

    .sidebar {
        width: 100%;
        text-align: center;
    }

    .form-container {
        margin-left: 0;
    }
    </style>
@endpush

@section('content')

    <div class="container-wrapper">
        <div class="form-container">
            <h2 class="form-title" id="form-title">Programme Calendar</h2>

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

            <form id="agency-group-form" action="{{ route('programmeCalender.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label for="calendar_year">Calendar Year<span class="text-danger">*</span></label>
                        <select id="calendar_year" class="form-control" name="calendar_year" required>
                            <option value="">-- Select Calendar Year --</option>
                            @foreach ($calendarYears as $year)
                                <option value="{{ $year }}" {{ old('calendar_year') == $year ? 'selected' : '' }} selected>
                                    {{ $year }}
                                </option>
                            @endforeach
                            <option value="new" {{ old('calendar_year') == 'new' ? 'selected' : '' }}>+ Add New</option>
                        </select>
                    </div>
                    
                    <div id="new_calendar_year_div" class="col-md-4 form-group" style="display: {{ old('calendar_year') == 'new' ? 'block' : 'none' }};">
                        <label for="new_calendar_year">New Calendar Year</label>
                        <input type="text" id="new_calendar_year" class="form-control" name="new_calendar_year"
                            value="{{ old('new_calendar_year') }}" placeholder="Select year">
                    </div>

                    <div class="col-md-4 form-group">
                        <label for="agency_group" class="form-label">Group<span class="text-danger">*</span></label>
                        <select class="form-control" id="agency_group" name="agency_group_id" required>
                            <option value="">Select Agency Group</option>
                            @foreach ($agencyGroups as $agencyGroup)
                                <option value="{{ $agencyGroup->id }}" 
                                    {{ old('agency_group_id') == $agencyGroup->id ? 'selected' : '' }}>
                                    {{ $agencyGroup->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-4 form-group">
                        <label for="program_code" class="form-label">
                            Program Code <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="program_code" name="program_code"
                               placeholder="Enter Program Code" value="{{ old('program_code') }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 form-group">
                        <label for="program_title" class="form-label">
                            Program Title <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="program_title" name="program_title"
                               placeholder="Enter Program Title" value="{{ old('program_title') }}" required>
                    </div>
                    
                    <div class="col-md-8 form-group">
                        <label for="select_program" class="form-label">
                            Select Program (Optional - Search by Code)
                        </label>
                        <select class="form-control" id="select_program">
                            <option value="">-- Select Program --</option>
                            @foreach($programs as $program)
                                <option value="{{ $program->id }}"
                                        data-code="{{ $program->code }}"
                                        data-title="{{ $program->title }}"
                                        {{ old('select_program') == $program->id ? 'selected' : '' }}>
                                    {{ $program->code }} - {{ $program->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label for="program_date" class="form-label">
                            From:<span class="text-danger">*</span>
                        </label>
                        <input type="date" class="form-control" id="program_date" name="program_date"
                               value="{{ old('program_date') }}" required>
                    </div>
                    
                    <div class="col-md-4 form-group">
                        <label for="program_to" class="form-label">
                            To:<span class="text-danger">*</span>
                        </label>
                        <input type="date" class="form-control" id="program_to" name="program_to"
                               value="{{ old('program_to') }}" required>
                    </div>
                     <!-- Sponsor Type Dropdown -->
                     <div class="col-md-4 form-group">
                        <label for="status">Status <span class="text-danger">*</span></label>
                        <select id="status" class="form-control" name="status" required>
                            <option value="">-- Select Status --</option>
                            @foreach ($statuses as $status)
                                <option value="{{ $status }}" {{ old('status') == $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                            <option value="new" {{ old('status') == 'new' ? 'selected' : '' }}>+ Add New</option>
                        </select>
                    </div>
                    
                    <div class="col-md-4 form-group" id="new_status_div" style="display: {{ old('status') == 'new' ? 'block' : 'none' }};">
                        <label for="new_status">New Status <span class="text-danger">*</span></label>
                        <input type="text" id="new_status" class="form-control" name="new_status"
                            value="{{ old('new_status') }}" placeholder="Enter new status">
                    </div>
                    

                </div>
                
                <div class="row">
                  {{-- Location --}}
                    
                  <div class="col-md-4 form-group">
                    <label for="location">Location <span class="text-danger">*</span></label>
                    <select id="location" class="form-control" name="location" required>
                        <option value="">-- Select Location Type --</option>
                        @foreach ($locations as $location)
                            <option value="{{ $location }}">{{ $location }}</option>
                        @endforeach
                        <option value="new">+ Add New</option>
                    </select>
                </div>
                
                <div id="new_location_div" style="display: none;">
                    <label for="new_location">New Location</label>
                    <input type="text" id="new_location" class="form-control" name="new_location"
                        placeholder="Enter new location type">
                </div>
                <div class="col-md-4 form-group">
                    <label for="user_id" class="form-label">Faculty <span class="text-danger">*</span></label>
                    <select class="form-control" id="user_id" name="user_id" required>
                        <option value="">Select Faculty</option>
                        @foreach ($users as $faculty)
                            <option value="{{ $faculty->id }}" 
                                {{ old('user_id') == $faculty->id ? 'selected' : '' }}>
                                {{ $faculty->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                
                
                   
                 
                </div>

                <button type="submit" class="btn btn-submit w-100">Save Programme</button>
            </form>
        </div>
    </div>

    <div class="table-container">
        <table id="agency-group-table" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Group</th>
                    <th>Programm</th>
                    <th>Location</th>
                    <th>Status</th>
                    <th>Active</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
@endsection

@push('scripts')
  <script src="{{ asset('assets/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset('assets/flatpickr/plugins/monthSelect/index.js') }}"></script>
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
        url: '{{ route('cal.getData') }}',
        type: 'GET',
        error: function(xhr) {
            console.error('DataTables error:', xhr.responseText);
        }
    },
    columns: [
        { data: 'id', name: 'id' },
        { data: 'user_type_name', name: 'user_type_name' },
        { data: 'program_title', name: 'program_title' }, // ✅ fixed name
        { data: 'location', name: 'location' },
        // { data: 'group_type_name', name: 'group_type_name' }, // ❌ Remove or fix
        { // Instead maybe show `status` instead?
            data: 'status',
            name: 'status'
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
    data: 'id',
    name: 'action',
    orderable: false,
    searchable: false,
    render: function(data, type, row) {
    return `
    <div class="btn-group">
        <button class="btn btn-sm btn-primary edit-btn"
            data-id="${row.id}"
            data-program-date="${row.program_date || ''}"
            data-program-to="${row.program_to || ''}"
            data-status="${row.status_raw || ''}"
            data-calendar-year="${row.calendar_year || ''}"
            data-agency-group="${row.agency_group_id || ''}"
            data-program-code="${row.program_code || ''}"
            data-program-title="${row.program_title || ''}"
            data-location="${row.location || ''}">
            <i class="fas fa-edit"></i> Edit
        </button>
        <button class="btn btn-sm btn-danger delete-btn" data-id="${row.id}">
            <i class="fas fa-trash"></i> Delete
        </button>
    </div>`;
}
}

    ],
    responsive: true
});


            // Handle toggle status click
            $('#agency-group-table').on('click', '.toggle-status', function() {
                const button = $(this);
                const id = button.data('id');
                const currentStatus = parseInt(button.data('status'));
                const newStatus = currentStatus === 1 ? 0 : 1;

                $.ajax({
                    url: `{{ url('admin/user/toggle-status') }}/${id}`,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        status: newStatus
                    },
                    success: function(response) {
                        showAlert('success', response.message);
                        button
                            .toggleClass('btn-success btn-danger')
                            .text(newStatus === 1 ? 'Active' : 'Inactive')
                            .data('status', newStatus);
                        table.ajax.reload(null, false);
                    },
                    error: function(xhr) {
                        showAlert('danger', xhr.responseJSON.message ||
                            'Failed to update status');
                    }
                });
            });

            // Handle edit button click
            $('#agency-group-table').on('click', '.edit-btn', function () {
    const id = $(this).data('id');
    const calendarYear = $(this).data('calendar-year');
    const newCalendarYear = $(this).data('new-calendar-year');
    const agencyGroupId = $(this).data('agency-group-id');
    const programCode = $(this).data('program-code');
    const programTitle = $(this).data('program-title');
    const programDate = $(this).data('program-date');
    const programTo = $(this).data('program-to');
    const status = $(this).data('status');
    const newStatus = $(this).data('new-status');
    const location = $(this).data('location');
    const newLocation = $(this).data('new-location');

    // Change form title (if applicable)
    $('#form-title').text('✏️ Edit Programme');

    // Update form action URL for editing
    $('#agency-group-form').attr('action', `/admin/programme-calender/update/${id}`);

    // Fill form fields
    $('#calendar_year').val(calendarYear).trigger('change');

    // Show and set new calendar year if "new" is selected
    if (calendarYear === 'new') {
        $('#new_calendar_year_div').show();
        $('#new_calendar_year').val(newCalendarYear);
    } else {
        $('#new_calendar_year_div').hide();
        $('#new_calendar_year').val('');
    }

    $('#agency_group').val(agencyGroupId);
    $('#program_code').val(programCode);
    $('#program_title').val(programTitle);
    $('#program_date').val(programDate);
    $('#program_to').val(programTo);

    $('#status').val(status).trigger('change');

    if (status === 'new') {
        $('#new_status_div').show();
        $('#new_status').val(newStatus);
    } else {
        $('#new_status_div').hide();
        $('#new_status').val('');
    }

    $('#location').val(location).trigger('change');

    if (location === 'new') {
        $('#new_location_div').show();
        $('#new_location').val(newLocation);
    } else {
        $('#new_location_div').hide();
        $('#new_location').val('');
    }

    // Ensure method spoofing if needed
    if (!$('input[name="_method"]').length) {
        $('#agency-group-form').append('<input type="hidden" name="_method" value="POST">');
    }
});

            // Handle delete button click
            $('#agency-group-table').on('click', '.delete-btn', function() {
                const id = $(this).data('id');

                if (confirm('Are you sure you want to delete this user?')) {
                    $.ajax({
                        url: `/admin/programme-calender/delete/${id}`,
                        type: 'post',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            alert('Deleted!');
                            $('#agency-group-table').DataTable().ajax.reload(null, false);
                        },
                        error: function(xhr) {
                            alert('Error deleting agency group');
                        }
                    });
                }
            });

            // Show alert message
            function showAlert(type, message) {
                const alertHtml = `
                    <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                        ${message}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                `;
                $('.container-wrapper').prepend(alertHtml);
                setTimeout(() => {
                    $('.alert').alert('close');
                }, 5000);
            }

            // Calendar year selection handler
            const calendarSelect = document.getElementById('calendar_year');
            const newYearDiv = document.getElementById('new_calendar_year_div');
            const newYearInput = document.getElementById('new_calendar_year');

            calendarSelect.addEventListener('change', function() {
                if (this.value === 'new') {
                    newYearDiv.style.display = 'block';
                } else {
                    newYearDiv.style.display = 'none';
                }
            });

            // Initialize flatpickr for year selection
            flatpickr("#new_calendar_year", {
                dateFormat: "Y",
                plugins: [
                    new flatpickr.plugins.monthSelectPlugin({
                        shorthand: true,
                        dateFormat: "Y",
                        theme: "light"
                    })
                ]
            });

            // Program selection handler
            document.getElementById('select_program').addEventListener('change', function() {
                const selected = this.options[this.selectedIndex];
                const code = selected.getAttribute('data-code');
                const title = selected.getAttribute('data-title');

                if (code && title) {
                    document.getElementById('program_code').value = code;
                    document.getElementById('program_title').value = title;
                }
            });

            // Status dropdown change handler
            document.getElementById('status').addEventListener('change', function() {
                const newStatusWrapper = document.getElementById('new_status_wrapper');
                if (this.value === '__add_new__') {
                    newStatusWrapper.style.display = 'block';
                    document.getElementById('new_status').required = true;
                } else {
                    newStatusWrapper.style.display = 'none';
                    document.getElementById('new_status').required = false;
                }
            });
              //for the sponsor
              document.getElementById('sponsor_type').addEventListener('change', function() {
    const newSponsorWrapper = document.getElementById('new_sponsor_type_wrapper');
    const newSponsorInput = document.getElementById('new_sponsor_type');

    if (this.value === '__add_new__') {
        newSponsorWrapper.style.display = 'block';
        newSponsorInput.setAttribute('required', 'required');
    } else {
        newSponsorWrapper.style.display = 'none';
        newSponsorInput.removeAttribute('required');
        newSponsorInput.value = '';
    }
});
            // Location dropdown change handler
            document.getElementById('location').addEventListener('change', function() {
                const newLocationWrapper = document.getElementById('new_location_wrapper');
                if (this.value === '__add_new__') {
                    newLocationWrapper.style.display = 'block';
                    document.getElementById('new_location').required = true;
                } else {
                    newLocationWrapper.style.display = 'none';
                    document.getElementById('new_location').required = false;
                }
            });

            // Initialize fields based on old input (for form validation errors)
            @if(old('status') == '__add_new__')
                document.getElementById('new_status_wrapper').style.display = 'block';
            @endif
            
            @if(old('location') == '__add_new__')
                document.getElementById('new_location_wrapper').style.display = 'block';
            @endif
        });

         // Handle new status type selection
      
    document.getElementById('status').addEventListener('change', function() {
        document.getElementById('new_status_div').style.display =
            this.value === 'new' ? 'block' : 'none';
    });


    document.getElementById('location').addEventListener('change', function () {
        document.getElementById('new_location_div').style.display =
            this.value === 'new' ? 'block' : 'none';
    });

    </script>
@endpush