@extends('admin.layouts.master')
@section('main-section')
    <link rel="stylesheet" href="{{ asset('assets/summernote/summernote-lite.css') }}">
    <style>
        .table.dataTable td {
            white-space: nowrap;
        }


        .programme-title-text {
            width: 250px;
            max-width: 250px;
            white-space: normal;
            word-break: break-word;
            overflow-wrap: anywhere;

            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.4;
        }

        #programme-table td.programme-title {
            width: 250px;
            max-width: 250px;
            vertical-align: middle;
        }
    </style>
    <div class="container">
        <div class="page-inner">


            <div class="page-header d-flex justify-content-between align-items-center">

                <!-- Left Side -->
                <div class="d-flex align-items-center gap-4">

                    <h3 class="fw-bold mb-0">Programme List</h3>

                    <ul class="breadcrumbs mb-0">
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

                        {{-- <li class="separator">
                            <i class="icon-arrow-right"></i>
                        </li> --}}

                        {{-- <li class="nav-item">
                            <a href="#">Add Programme</a>
                        </li> --}}
                    </ul>

                </div>

                <!-- Right Side Button -->
                <div>
                    <a href="{{ route('programmeManagement.index') }}" class="btn btn-primary">
                        Add Programme
                    </a>
                </div>

            </div>

            <div class="row mb-3 card">
                <div class="card-body">
                    <div class="row">

                        <div class="col-md-3">
                            <label for="cal_year" class="form-label">
                                <i class="fas fa-calendar-alt me-2"></i>Financial Year
                            </label>

                            <select class="form-control" id="cal_year" name="cal_year" required>
                                <option value="">Select Financial Year</option>

                                @foreach ($financialYears as $year)
                                    <option value="{{ $year }}"
                                        {{ (string) $year === (string) $defaultYear ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
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
                                        {{ $prog->unique_id }} – {{ Str::limit($prog->title, 50) }}
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
                    <!-- Financial Year Filter -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="financial-year-filter" class="form-label fw-bold">
                                Financial Year
                            </label>

                            <select id="financial-year-filter" class="form-control">
                                @foreach ($financialYears as $year)
                                    <option value="{{ $year }}"
                                        {{ (string) $year === (string) $defaultYear ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="table-container table-responsive">
                        <table id="programme-table" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>S.no</th>
                                    <th>Status</th>
                                    <th>Code</th>
                                    <th>Programme Title</th>
                                    <th>PD Name</th>
                                    <th>Sponsor Type</th>
                                    <th>Related Agency Type</th>

                                    <th>Group Name</th>



                                    <th>Starting date </th>
                                    <th> End date </th>

                                    {{-- <th>Location</th> --}}

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
                    <h5 class="modal-title">Update Programme Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <form id="statusForm">

                        <input type="hidden" id="modal_programme_id" name="programme_id">
                        <input type="hidden" id="announcement_type" value="manual" name="announcement_type">
                        <input type="hidden" id="status" name="status" value="">

                        <!-- Action buttons: only shown when current status is Announced -->
                        <div class="mb-3" id="status-btn-group">
                            <label class="form-label d-block">Change Status To</label>
                            <div class="btn-group w-100" role="group">
                                <button type="button" class="btn btn-outline-danger status-choice"
                                    data-status="Canceled">
                                    Cancelled
                                </button>
                                <button type="button" class="btn btn-outline-warning status-choice"
                                    data-status="Postpond">
                                    Postponed
                                </button>
                                <button type="button" class="btn btn-outline-info status-choice"
                                    data-status="Reschedule">
                                    Reschedule
                                </button>
                            </div>
                        </div>

                        <!-- Only for Reschedule -->
                        <div class="row d-none" id="reschedule-dates">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">New From Date</label>
                                <input type="date" class="form-control" name="new_from_date" id="new_from_date">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">New To Date</label>
                                <input type="date" class="form-control" name="new_to_date" id="new_to_date">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Remark</label>
                            <textarea class="form-control" name="remark"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="manual_file" class="form-label">Upload Announcement Letter (PDF):</label>
                            <input type="file" class="form-control" name="manual_file[]" id="manual_file"
                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" multiple>
                        </div>

                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="statusForm" id="saveBtn" class="btn btn-primary" disabled>
                        Save
                    </button>
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
        const canEditProgramme = @json(Auth::guard('admin')->user()->hasAccess('programme', 'edit'));
        const canDeleteProgramme = @json(Auth::guard('admin')->user()->hasAccess('programme', 'delete'));
        const canApproveProgramme = @json(Auth::guard('admin')->user()->hasAccess('programme', 'approve'));
        const canAnnounceProgramme = @json(Auth::guard('admin')->user()->hasAccess('programme', 'announce'));

        // Open Modal & Set Data
        // Open Modal & reset it for each row
        $(document).on('click', '.change-status', function() {
            var id = $(this).data('id');
            var currentStatus = $(this).data('status');

            $('#modal_programme_id').val(id);
            $('#status').val('');
            $('#statusForm')[0].reset();
            $('.status-choice').removeClass('active');
            $('#reschedule-dates').addClass('d-none');
            $('#saveBtn').prop('disabled', true);

            // Only Announced programmes get the Cancel/Postpone/Reschedule options
            if (currentStatus === 'Announced') {
                $('#status-btn-group').removeClass('d-none');
            } else {
                $('#status-btn-group').addClass('d-none');
                alert('Status can only be changed from Announced programmes.');
                return; // don't open the modal for non-Announced rows
            }

            $('#statusModal').modal('show');
        });

        // Pick a status button
        $(document).on('click', '.status-choice', function() {
            $('.status-choice').removeClass('active');
            $(this).addClass('active');

            var chosen = $(this).data('status');
            $('#status').val(chosen);
            $('#saveBtn').prop('disabled', false);

            if (chosen === 'Reschedule') {
                $('#reschedule-dates').removeClass('d-none');
                $('#new_from_date, #new_to_date').prop('required', true);
            } else {
                $('#reschedule-dates').addClass('d-none');
                $('#new_from_date, #new_to_date').prop('required', false);
            }
        });

        // Submit
        $(document).on('submit', '#statusForm', function(e) {
            e.preventDefault();

            if (!$('#status').val()) {
                alert('Please choose a status.');
                return;
            }

            let formData = new FormData(this);
            let programmeId = $('#modal_programme_id').val();
            let chosenStatus = $('#status').val();

            $('#saveBtn').html(`<span class="spinner-border spinner-border-sm"></span> Saving...`);
            $('#saveBtn').prop('disabled', true);

            $.ajax({
                url: "{{ url('admin/announcement/store') }}/" + programmeId,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                data: formData,
                processData: false,
                contentType: false,

                success: function(response) {
                    $('#statusModal').modal('hide');

                    if (chosenStatus === 'Reschedule') {
                        // Route helper se URL banate hain
                        window.location.href = "{{ route('announcement.generate', ':id') }}".replace(
                            ':id', programmeId);
                    } else {
                        alert('Status updated and notifications sent successfully');
                        window.location.reload();
                    }
                },

                error: function(xhr) {
                    alert(xhr.responseJSON?.message || 'Something went wrong');
                },

                complete: function() {
                    $('#saveBtn').html('Save');
                    $('#saveBtn').prop('disabled', false);
                }
            });
        });
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
                url: "{{ url('admin/announcement/store') }}/" + $('#modal_programme_id').val(),
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
                        d.financial_year = $('#financial-year-filter').val();
                        d.code = $('#programme_code').val();
                        d.from_date = $('#from_date').val();
                        d.to_date = $('#to_date').val();
                        d.location = $('#location-filter').val();
                        d.faculty = $('#faculty-filter').val();
                        d.list_type = 'all'; 
                    }
                },
                columns: [{
                        data: null,
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
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
                        data: 'unique_id',
                        name: 'unique_id'
                    },
                    {
                        data: 'title',
                        name: 'title',
                        className: 'programme-title',
                        render: function(data, type, row) {
                            if (!data) return '';

                            return `
            <div class="programme-title-text" title="${data}">
                ${data}
            </div>
        `;
                        }
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
                        data: 'sponsor_name',
                        name: 'sponsor_name'
                    },

                    {
                        data: 'agency_type',
                        name: 'agency_type'
                    },
                    {
                        data: 'group_name',
                        name: 'group_name'
                    },




                    {
                        data: null,
                        name: 'duration',
                        render: function(data, type, row) {
                            const from = new Date(row.from_date);

                            const formatDate = (date) => {
                                return ('0' + date.getDate()).slice(-2) + '-' +
                                    ('0' + (date.getMonth() + 1)).slice(-2) + '-' +
                                    date.getFullYear();
                            };

                            return formatDate(from);
                        }
                    },
                    {
                        data: null,
                        name: 'duration',
                        render: function(data, type, row) {
                            const to = new Date(row.to_date);

                            const formatDate = (date) => {
                                return ('0' + date.getDate()).slice(-2) + '-' +
                                    ('0' + (date.getMonth() + 1)).slice(-2) + '-' +
                                    date.getFullYear();
                            };

                            return formatDate(to);
                        }
                    },
                    // {
                    //     data: 'location',
                    //     name: 'location'
                    // },

                    {
                        data: null,
                        name: 'Change Status',
                        render: function(data, type, row) {
                            if (!canApproveProgramme) {
                                return '<span class="text-muted">—</span>';
                            }
                            return `<button class="btn btn-sm btn-success change-status" data-id="${row.id}" data-status="${row.status}">Change Status</button>`;
                        }
                    },
                    {
                        data: 'is_active',
                        name: 'is_active',
                        render: function(data, type, row) {
                            if (!canEditProgramme) {
                                return data ?
                                    `<span class="badge badge-success">Active</span>` :
                                    `<span class="badge badge-danger">Inactive</span>`;
                            }
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

            // Filters
            $('#status-filter, #group-filter, #sponsor-filter, #year-filter')
                .on('change', function() {
                    table.ajax.reload();
                });

            $('#financial-year-filter').on('change', function() {
                table.ajax.reload();
            });

            $('#programme_id').on('change', function() {
                table.ajax.reload();
            });

            $('#from_date, #to_date').on('change', function() {
                table.ajax.reload();
            });

            $('#programme_code').on('keyup', function() {
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
                    alert(response.message);
                    $('#programme-table').DataTable().ajax.reload(null, false);
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

            function filterProgrammesByYear(selectedYear) {
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
            }

            // Agar page load hote hi koi year pehle se selected hai, turant programme list bhar dein
            filterProgrammesByYear(calYearSelect.value);

            calYearSelect.addEventListener('change', function() {
                filterProgrammesByYear(this.value);
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
    </script>
@endsection
