@extends('admin.layouts.master')
@section('main-section')
    <link rel="stylesheet" href="{{ asset('assets/summernote/summernote-lite.css') }}">

   
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
            {{-- <a href="{{route('programmeManagement.index')}}" class="btn btn-primary mb-3">Add Programme</a> --}}

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
                            <label for="group-filter" class="form-label">Group</label>
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
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="active-date-filter">
                                <label class="form-check-label" for="active-date-filter">
                                    Show Only Active Programs
                                </label>
                            </div>
                        </div>



                        {{-- <div class="col-md-3">
        <label for="year-filter" class="form-label">Year</label>
        <select id="year-filter" class="form-control">
            <option value="">All Years</option>
            @for ($i = date('Y'); $i >= 2000; $i--)
                <option value="{{ $i }}">{{ $i }}</option>
            @endfor
        </select>
    </div> --}}
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
                                    <th>Title</th>
                                    <th>Sponsor Type</th>
                                    <th>Duration</th>
                                    <th>Location</th>
                                    <th>Active Status</th>
                                    {{-- <th>Actions</th> --}}
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

    <script>
        $(document).ready(function() {
            var table = $('#programme-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('programmeManagement.getData.active') }}",
                    data: function(d) {
                        d.status = $('#status-filter').val();
                        d.group = $('#group-filter').val();
                        d.sponsor = $('#sponsor-filter').val();
                        d.year = $('#year-filter').val();
                        d.programme = $('#programme_id').val();
                        d.fanicial = $('#cal_year').val();
                        d.active_only = $('#active-date-filter').is(':checked');
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
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'sponsor_name',
                        name: 'sponsor_name'
                    },
                    {
                        data: 'duration',
                        name: 'duration'
                    },
                    {
                        data: 'location',
                        name: 'location'
                    },
                    {
                        data: 'is_active',
                        name: 'is_active',
                        // render: function(data, type, row) {
                        //     return data ?
                        //         `<button class="btn btn-sm btn-success toggle-status" data-id="${row.id}" data-status="1">Active</button>` :
                        //         `<button class="btn btn-sm btn-danger toggle-status" data-id="${row.id}" data-status="0">Inactive</button>`;
                        // }
                    },
                    // {
                    //     data: 'id',
                    //     name: 'actions',
                    //     orderable: false,
                    //     searchable: false,
                    //     render: function(data, type, full, meta) {
                    //         return `
                //             <div class="btn-group">
                //                 <a href="/admin/programme-management/edit/${data}/" class="btn btn-primary btn-action">
                //                     <i class="fas fa-edit"></i>
                //                 </a>
                //                 <button class="btn btn-danger btn-action delete-btn" data-id="${data}">
                //                     <i class="fas fa-trash"></i>
                //                 </button>


                //                 <a href="/admin/session-report/${data}/" class="btn btn-primary btn-action">
                //                     <i class="fas fa-file-pdf icon-pdf"></i> Session Report
                //                 </a>
                //                  <a href="/admin/announcement/generate/${data}/" class="btn btn-success btn-action">
                //                     <i class="fas fa-file-pdf icon-pdf"></i>Send Annoucement
                //                 </a>
                //             </div>
                //         `;
                    //     }
                    // }
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

            $('#active-date-filter').on('change', function() {
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
