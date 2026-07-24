@extends('admin.layouts.master')
@section('main-section')
<link rel="stylesheet" href="{{ asset('assets/summernote/summernote-lite.css') }}">
    <style>
        .table.dataTable td {
            white-space: nowrap;
        }
    </style>
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Add Programme</h3>
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
                        <a href="#">Programme</a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Add Programme</a>
                    </li>
                </ul>
            </div>
            <a href="{{ route('programmeManagement.index') }}" class="btn btn-primary mb-3">Add Programme</a>

            <div class="row mb-3 card">
                <div class="card-body">
                    <div class="row">

                        <div class="col-md-3">
                            <label for="cal_year" class="form-label">
                                <i class="fas fa-calendar-alt me-2"></i>Calendar Year
                            </label>
                            <select class="form-control" id="cal_year" name="cal_year" required>
                                <option value="">Select Calendar Year</option>
                                @foreach ($distinctYears as $year)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Programme Code -->
                        <div class="col-md-3">
                            <label for="programme_code" class="form-label">Code</label>
                            <input type="text" id="programme_code" name="programme_code" class="form-control"
                                placeholder="Enter unique code">
                        </div>

                        <!-- Programme Dropdown -->
                        <div class="col-md-3">
                            <label for="programme_id" class="form-label">
                                <i class="fas fa-graduation-cap me-2"></i>Programme
                            </label>
                            <select id="programme_id" name="programme" class="form-control">
                                <option value="">Select Programme...</option>
                                @foreach ($programmes as $prog)
                                    <option value="{{ $prog->id }}" data-year="{{ $prog->financial_year }}">
                                        {{ $prog->code }} – {{ Str::limit($prog->title, 50) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Status Filter -->
                        <div class="col-md-3">
                            <label for="status-filter" class="form-label">Status</label>
                            <select id="status-filter" class="form-control">
                                <option value="">All Statuses</option>
                                <option value="NotAnnounce">NotAnnounce</option>
                                <option value="Announced">Announced</option>
                                <option value="Canceled">Canceled</option>
                                <option value="Postpond">Postpond</option>
                            </select>
                        </div>

                        <!-- Group Filter -->
                        <div class="col-md-3">
                            <label for="group-filter" class="form-label">Vertical</label>
                            <select id="group-filter" class="form-control">
                                <option value="">All Groups</option>
                                @foreach ($groups as $group)
                                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                                @endforeach
                            </select>
                        </div>



                        <div class="col-md-3">
                            <label for="sponsor-filter" class="form-label">Sponsor Type</label>
                            <select id="sponsor-filter" class="form-control">
                                <option value="">All Sponsors</option>
                                @foreach ($sponsors as $sponsor)
                                    <option value="{{ $sponsor->id }}">{{ $sponsor->name }}</option>
                                @endforeach
                            </select>
                        </div>



                        <div class="col-md-3">
                            <label for="from_date" class="form-label">From Date</label>
                            <input type="date" id="from_date" class="form-control">
                        </div>

                        <div class="col-md-3">
                            <label for="to_date" class="form-label">To Date</label>
                            <input type="date" id="to_date" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label for="location-filter" class="form-label">Location</label>
                            <select id="location-filter" class="form-control">
                                <option value="">All Locations</option>
                                @foreach ($locations as $location)
                                    <option value="{{ $location }}">{{ $location }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="faculty-filter" class="form-label">Programme Director</label>
                            <select id="faculty-filter" class="form-control">
                                <option value="">All Faculties</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>



                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-container table-responsive">
                        <table id="programme-table" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Status</th>
                                    <th>Group Name</th>
                                    <th>Related Agency Type</th>
                                    <th>Code</th>
                                    <th>Programme Title</th>
                                    <th>Sponsor Type</th>
                                    <th>Starting date </th>
                                    <th> End date </th>

                                    <th>Location</th>
                                    <th>Programme Directors</th>
                                    <th>Change Status </th>
                                    <th>Active Status</th>
                                    <th>Actions</th>
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

    <!-- Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Approve Programme</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <form id="statusForm">

                        <!-- Hidden Inputs -->
                        <input type="hidden" id="programme_id" name="programme_id">
                        <input type="hidden" id="announcement_type" value="manual" name="announcement_type">

                        <div class="mb-3">
                            <label class="form-label">Status</label>

                            <select class="form-select" id="status" name="status">
                                <option value="Announced">Announced</option>
                                <option value="Postponed">Postponed</option>
                                <option value="Canceled">Canceled</option>
                            </select>
                        </div>



                        <div class="mb-3">
                            <label class="form-label">Remark</label>
                            <textarea class="form-control" name="remark"></textarea>
                        </div>
                        <div class="mb-3">
                            < <label for="manual_file" class="form-label">Upload Announcement Letter (PDF):</label>
                                <!-- <input type="file" class="form-control" name="manual_file" id="manual_file" accept="application/pdf"> -->
                                <input type="file" class="form-control" name="manual_file[]" id="manual_file"
                                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" multiple>

                        </div>






                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>

                    <button type="submit" form="statusForm" id="saveBtn" class="btn btn-primary">
                        Save
                    </button>
                </div>
                </form>

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

         const canEditProgramme      = @json(Auth::guard('admin')->user()->hasAccess('programme', 'edit'));
    const canDeleteProgramme    = @json(Auth::guard('admin')->user()->hasAccess('programme', 'delete'));
    const canApproveProgramme   = @json(Auth::guard('admin')->user()->hasAccess('programme', 'approve'));
    const canAnnounceProgramme  = @json(Auth::guard('admin')->user()->hasAccess('programme', 'announce'));

        // Open Modal & Set Data
        $(document).on('click', '.change-status', function() {

            var id = $(this).data('id');
            var status = $(this).data('status');


            // Set hidden input values
            $('#programme_id').val(id);
            $('#status').val(status);

            // Show modal
            $('#statusModal').modal('show');
        });

        {
    data: 'id',
    name: 'actions',
    orderable: false,
    searchable: false,
    className: 'text-nowrap',
    render: function(data, type, full, meta) {

        let items = '';

        if (canEditProgramme) {
            items += `
                <li>
                    <a class="dropdown-item"
                       href="/admin/programme-management/edit/${data}/">
                        <i class="fas fa-edit me-2"></i> Edit
                    </a>
                </li>`;
        }

        if (canDeleteProgramme) {
            items += `
                <li>
                    <button class="dropdown-item delete-btn"
                            data-id="${data}">
                        <i class="fas fa-trash me-2 text-danger"></i> Delete
                    </button>
                </li>`;
        }

        // Session Report - view-only, keep visible to everyone with list access
        items += `
            <li>
                <a class="dropdown-item"
                   href="/admin/session-report/${data}/">
                    <i class="fas fa-file-pdf me-2 text-danger"></i>
                    Session Report
                </a>
            </li>`;

        if (canAnnounceProgramme) {
            items += `
                <li>
                    <a class="dropdown-item"
                       href="/admin/announcement/generate/${data}/">
                        <i class="fas fa-bullhorn me-2 text-success"></i>
                        Announcement
                    </a>
                </li>`;
        }

        if (canApproveProgramme) {
            items += `
                <li>
                    <a class="dropdown-item"
                       href="/admin/programme/approve/${data}/">
                        <i class="fas fa-check me-2 text-primary"></i>
                        Approve
                    </a>
                </li>`;
        }

        // Poster - view-only, keep visible
        items += `
            <li>
                <a class="dropdown-item"
                   href="/admin/poster/${data}/">
                    <i class="fas fa-image me-2 text-success"></i>
                    Poster
                </a>
            </li>`;

        if (!items) {
            return '<span class="text-muted">No actions</span>';
        }

        return `
            <div class="dropdown text-nowrap">
                <button class="btn btn-secondary btn-sm dropdown-toggle"
                        type="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu">
                    ${items}
                </ul>
            </div>
        `;
    }
}

{
    data: null,
    name: 'Change Status',
    render: function(data, type, row) {
        if (!canApproveProgramme) {
            return '<span class="text-muted">—</span>';
        }
        return `<button class="btn btn-sm btn-success change-status" data-id="${row.id}" data-status="${row.status}" data-status="1">Change Status</button>`;
    }
},

{
    data: 'is_active',
    name: 'is_active',
    render: function(data, type, row) {
        if (!canEditProgramme) {
            return data
                ? `<span class="badge badge-success">Active</span>`
                : `<span class="badge badge-danger">Inactive</span>`;
        }
        return data ?
            `<button class="btn btn-sm btn-success toggle-status" data-id="${row.id}" data-status="1">Active</button>` :
            `<button class="btn btn-sm btn-danger toggle-status" data-id="${row.id}" data-status="0">Inactive</button>`;
    }
},
    </script>
    <script>
        $(document).on('submit', '#statusForm', function(e) {

            e.preventDefault();

            let formData = new FormData(this);

            // Button loader
            $('#saveBtn').html(`
        <span class="spinner-border spinner-border-sm"></span>
        Saving...
    `);

            $('#saveBtn').prop('disabled', true);

            $.ajax({
                url: "{{ url('admin/announcement/store') }}/" + $('#programme_id').val(),
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },


                data: formData,
                processData: false,
                contentType: false,

                success: function(response) {

                    // Success message
                    alert('Saved Successfully');

                    // Reset form
                    $('#statusForm')[0].reset();

                    // Close modal
                    $('#statusModal').modal('hide');

                    window.location.reload();

                },

                error: function(xhr) {

                    alert('Something went wrong');

                },

                complete: function() {

                    // Reset button
                    $('#saveBtn').html('Save');
                    $('#saveBtn').prop('disabled', false);

                }

            });

        });
    </script>


    <script>
        $(document).ready(function() {
            var table = $('#programme-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('programmeManagement.getData') }}",
                    data: function(d) {
                        d.status = $('#status-filter').val();
                        d.group = $('#group-filter').val();
                        d.sponsor = $('#sponsor-filter').val();
                        d.year = $('#year-filter').val();
                        d.programme = $('#programme_id').val();
                        d.fanicial = $('#cal_year').val();
                        d.from_date = $('#from_date').val();
                        d.to_date = $('#to_date').val();
                        d.location = $('#location-filter').val();
                        d.faculty = $('#faculty-filter').val();


                    }
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: function(data, type, row) {
                            let badgeClass = 'badge-secondary';
                            if (data === 'Announced') badgeClass = 'badge-success';
                            if (data === 'Canceled') badgeClass = 'badge-danger';
                            if (data === 'Postpond') badgeClass = 'badge-warning';
                            return `<span class="badge ${badgeClass}">${data}</span>`;
                        }
                    },
                    {
                        data: 'group_name',
                        name: 'group_name'
                    },
                    {
                        data: 'agency_type',
                        name: 'agency_type'
                    },


                    {
                        data: 'unique_id',
                        name: 'unique_id'
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'sponsor_name',
                        name: 'sponsor_name'
                    },
                    {
                        data: null,
                        name: 'duration',
                        render: function(data, type, row) {
                            const from = new Date(row.from_date);
                            const to = new Date(row.to_date);

                            const formatDate = (date) => {
                                return ('0' + date.getDate()).slice(-2) + '-' +
                                    ('0' + (date.getMonth() + 1)).slice(-2) + '-' +
                                    date.getFullYear();
                            };

                            return formatDate(from);
                        }
                    }, {
                        data: null,
                        name: 'duration',
                        render: function(data, type, row) {
                            const from = new Date(row.from_date);
                            const to = new Date(row.to_date);

                            const formatDate = (date) => {
                                return ('0' + date.getDate()).slice(-2) + '-' +
                                    ('0' + (date.getMonth() + 1)).slice(-2) + '-' +
                                    date.getFullYear();
                            };

                            return formatDate(to);
                        }

                    },


                    {
                        data: 'location',
                        name: 'location'
                    },
                    {
                        data: null,
                        name: 'dir_name',
                        render: function(data, type, row) {
                            const name1 = row.dir_name1 || '';
                            const name2 = row.dir_name2 || '';

                            if (name1 && name2) {
                                return `${name1} - ${name2}`;
                            } else {
                                return name1 || name2;
                            }
                        }
                    },
                    {
                        data: null,
                        name: 'Change Status',
                        render: function(data, type, row) {
                            return `<button class="btn btn-sm btn-success change-status" data-id="${row.id}" data-status="${row.status}"  data-status="1">Change Status</button>`;
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
                        data: 'id',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        className: 'text-nowrap',
                        render: function(data, type, full, meta) {

                            return `
            <div class="dropdown text-nowrap">
                <button class="btn btn-secondary btn-sm dropdown-toggle"
                        type="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">
                    <i class="fas fa-ellipsis-v"></i>
                </button>

                <ul class="dropdown-menu">

                    <li>
                        <a class="dropdown-item"
                           href="/admin/programme-management/edit/${data}/">
                            <i class="fas fa-edit me-2"></i> Edit
                        </a>
                    </li>

                    <li>
                        <button class="dropdown-item delete-btn"
                                data-id="${data}">
                            <i class="fas fa-trash me-2 text-danger"></i> Delete
                        </button>
                    </li>

                    <li>
                        <a class="dropdown-item"
                           href="/admin/session-report/${data}/">
                            <i class="fas fa-file-pdf me-2 text-danger"></i>
                            Session Report
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item"
                           href="/admin/announcement/generate/${data}/">
                            <i class="fas fa-bullhorn me-2 text-success"></i>
                            Announcement
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item"
                           href="/admin/programme/approve/${data}/">
                            <i class="fas fa-check me-2 text-primary"></i>
                            Approve
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item"
                           href="/admin/poster/${data}/">
                            <i class="fas fa-image me-2 text-success"></i>
                            Poster
                        </a>
                    </li>

                </ul>
            </div>
        `;
                        }
                    }
                ],
                responsive: true,
                language: {
                    processing: '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>'
                },
                initComplete: function() {
                    $('#programme-table').on('click', '.delete-btn', function() {
                        var id = $(this).data('id');
                        if (confirm('Are you sure you want to delete this programme?')) {
                            $.ajax({
                                url: '/admin/programme-management/delete/' + id,
                                type: 'POST',
                                data: {
                                    _token: "{{ csrf_token() }}"
                                },
                                success: function(response) {
                                    if (response.success) {
                                        table.ajax.reload(null, false);
                                        alert('Programme deleted successfully');
                                    } else {
                                        alert('Error deleting programme');
                                    }
                                },
                                error: function() {
                                    alert('Error deleting programme');
                                }
                            });
                        }
                    });
                }
            });

            // Toggle Active/Inactive status


            // Filters
            $('#status-filter, #group-filter, #sponsor-filter, #year-filter').on('change', function() {
                table.ajax.reload();
            });
            $('#programme_code').on('keyup', function() {
                table.ajax.reload();
            });

            $('#cal_year').on('change', function() {
                table.ajax.reload();
            });



            $('#programme_id').on('change', function() {
                table.ajax.reload();
            });

            $('#from_date, #to_date').on('change', function() {
                table.ajax.reload();
            });
            $('#location-filter').on('change', function() {
                table.ajax.reload();
            });


            $('#faculty-filter').on('change', function() {
                table.ajax.reload();
            });



        });
    </script>

    <script>
        $(document).on('click', '.toggle-status', function() {
            const button = $(this);
            const id = button.data('id');
            const currentStatus = parseInt(button.data('status'));
            const newStatus = currentStatus === 1 ? 0 : 1;

            $.ajax({
                url: `/admin/programme/toggle-status/${id}`,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    status: newStatus
                },
                success: function(response) {
                    // Optional: Show alert
                    alert(response.message);

                    // Refresh DataTable
                    $('#programme-table').DataTable().ajax.reload(null,
                    false); // reload without resetting pagination
                },
                error: function(xhr) {
                    alert(xhr.responseJSON.message || 'Failed to update status');
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {


            const calYearSelect = document.getElementById('cal_year');
            const programmeSelect = document.getElementById('programme_id');
            const allProgrammeOptions = Array.from(programmeSelect.options).slice(1); // Skip placeholder

            // Initially disable programme dropdown
            programmeSelect.disabled = true;

            calYearSelect.addEventListener('change', function() {
                const selectedYear = this.value;

                // Reset options
                programmeSelect.innerHTML = '<option value="">Select Programme...</option>';

                if (selectedYear) {
                    const matchingOptions = allProgrammeOptions.filter(option =>
                        option.getAttribute('data-year') === selectedYear
                    );

                    matchingOptions.forEach(option => {
                        programmeSelect.appendChild(option);
                    });

                    programmeSelect.disabled = false;
                } else {
                    programmeSelect.disabled = true;
                }
            });
        });
    </script>
    <script>
        document.getElementById('programme_code').addEventListener('keyup', function() {
            let code = this.value.trim();
            let year = document.getElementById('cal_year').value;
            let select = document.getElementById('programme_id');

            if (code !== '' && year !== '') {
                fetch('/get-programme-by-code', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            code: code,
                            year: year
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.id) {
                            select.innerHTML =
                                `<option value="${data.id}" data-code="${data.unique_id}" selected>${data.title}</option>`;
                            select.disabled = false;
                            select.dispatchEvent(new Event('change'));
                        } else {
                            select.innerHTML = '<option value="">-- Select Programme --</option>';
                            select.disabled = true;

                            Swal.fire({
                                icon: 'warning',
                                title: 'Not Found',
                                text: 'No programme found for this code and financial year.'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        select.innerHTML = '<option value="">-- Select Programme --</option>';
                        select.disabled = true;

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to fetch programme.'
                        });
                    });
            } else {
                select.innerHTML = '<option value="">-- Select Programme --</option>';
                select.disabled = true;
            }
        });

        // 👉 Auto-fill the programme code when selected from dropdown
    </script>
@endsection
