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
            @if (Session::has('success'))
                <div class="alert alert-success">
                    {{ Session::get('success') }}
                </div>
            @endif
            @if (Session::has('error'))
                <div class="alert alert-danger">
                    {{ Session::get('error') }}
                </div>
            @endif

            <div class="row mb-3 ">
              <!-- COLLAPSE BUTTON -->

<div class="mb-3">

    <button 
        class="btn btn-success w-100 text-start d-flex justify-content-between align-items-center"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#filterCollapse"
        aria-expanded="false"
        aria-controls="filterCollapse"
    >

        <span>
            <i class="fas fa-pen me-2"></i>
            Filter
        </span>

        <span id="toggleIcon">
            <i class="fas fa-plus"></i>
        </span>

    </button>

</div>



<!-- COLLAPSE FORM -->

<div class="collapse" id="filterCollapse">

    <div class="card">

        <div class="card-body">

            <div class="row">

                <!-- CALENDAR YEAR -->

                <div class="col-md-3 mb-3">

                    <label for="cal_year" class="form-label">

                        <i class="fas fa-calendar-alt me-2"></i>

                        Calendar Year

                    </label>

                    <select 
                        class="form-control"
                        id="cal_year"
                        name="cal_year"
                        required
                    >

                        <option value="">
                            Select Calendar Year
                        </option>

                        @foreach ($distinctYears as $year)

                            <option value="{{ $year }}">
                                {{ $year }}
                            </option>

                        @endforeach

                    </select>

                </div>



                <!-- PROGRAMME CODE -->

                <div class="col-md-3 mb-3">

                    <label for="programme_code" class="form-label">
                        Code
                    </label>

                    <input 
                        type="text"
                        id="programme_code"
                        name="programme_code"
                        class="form-control"
                        placeholder="Enter unique code"
                    >

                </div>



                <!-- PROGRAMME -->

                <div class="col-md-3 mb-3">

                    <label for="programme_id" class="form-label">

                        <i class="fas fa-graduation-cap me-2"></i>

                        Programme

                    </label>

                    <select 
                        id="programme_id"
                        name="programme"
                        class="form-control"
                    >

                        <option value="">
                            Select Programme...
                        </option>

                        @foreach ($programmes as $prog)

                            <option 
                                value="{{ $prog->id }}"
                                data-year="{{ $prog->financial_year }}"
                            >

                                {{ $prog->code }} –
                                {{ Str::limit($prog->title, 50) }}

                            </option>

                        @endforeach

                    </select>

                </div>



                <!-- STATUS -->

                <div class="col-md-3 mb-3">

                    <label for="status-filter" class="form-label">
                        Status
                    </label>

                    <select id="status-filter" class="form-control">

                        <option value="">
                            All Statuses
                        </option>

                        <option value="NotAnnounce">
                            NotAnnounce
                        </option>

                        <option value="Announced">
                            Announced
                        </option>

                        <option value="Canceled">
                            Canceled
                        </option>

                        <option value="Postpond">
                            Postpond
                        </option>

                    </select>

                </div>



                <!-- GROUP -->

                <div class="col-md-3 mb-3">

                    <label for="group-filter" class="form-label">
                        Vertical
                    </label>

                    <select id="group-filter" class="form-control">

                        <option value="">
                            All Groups
                        </option>

                        @foreach ($groups as $group)

                            <option value="{{ $group->id }}">
                                {{ $group->name }}
                            </option>

                        @endforeach

                    </select>

                </div>



                <!-- SPONSOR -->

                <div class="col-md-3 mb-3">

                    <label for="sponsor-filter" class="form-label">
                        Sponsor Type
                    </label>

                    <select id="sponsor-filter" class="form-control">

                        <option value="">
                            All Sponsors
                        </option>

                        @foreach ($sponsors as $sponsor)

                            <option value="{{ $sponsor->id }}">
                                {{ $sponsor->name }}
                            </option>

                        @endforeach

                    </select>

                </div>



                <!-- FROM DATE -->

                <div class="col-md-3 mb-3">

                    <label for="from_date" class="form-label">
                        From Date
                    </label>

                    <input 
                        type="date"
                        id="from_date"
                        class="form-control"
                    >

                </div>



                <!-- TO DATE -->

                <div class="col-md-3 mb-3">

                    <label for="to_date" class="form-label">
                        To Date
                    </label>

                    <input 
                        type="date"
                        id="to_date"
                        class="form-control"
                    >

                </div>



                <!-- LOCATION -->

                <div class="col-md-3 mb-3">

                    <label for="location-filter" class="form-label">
                        Location
                    </label>

                    <select id="location-filter" class="form-control">

                        <option value="">
                            All Locations
                        </option>

                        @foreach ($locations as $location)

                            <option value="{{ $location }}">
                                {{ $location }}
                            </option>

                        @endforeach

                    </select>

                </div>



                <!-- FACULTY -->

                <div class="col-md-3 mb-3">

                    <label for="faculty-filter" class="form-label">
                        Programme Director
                    </label>

                    <select id="faculty-filter" class="form-control">

                        <option value="">
                            All Faculties
                        </option>

                        @foreach ($users as $user)

                            <option value="{{ $user->id }}">
                                {{ $user->name }}
                            </option>

                        @endforeach

                    </select>

                </div>



                <!-- BUTTONS -->

                <div class="col-md-12 mt-3 text-end">

                    <button class="btn btn-primary">

                        <i class="fas fa-search me-1"></i>

                        Search

                    </button>


                    <button 
                        type="reset"
                        class="btn btn-secondary"
                    >

                        <i class="fas fa-undo me-1"></i>

                        Reset

                    </button>

                </div>

            </div>

        </div>

    </div>

</div>



<!-- JS -->

<script id="q7uj2v">

document.addEventListener('DOMContentLoaded', function () {

    let filterCollapse = document.getElementById('filterCollapse');

    let toggleIcon = document.getElementById('toggleIcon');



    filterCollapse.addEventListener('show.bs.collapse', function () {

        toggleIcon.innerHTML = `
        
            <i class="fas fa-minus"></i>
        
        `;

    });



    filterCollapse.addEventListener('hide.bs.collapse', function () {

        toggleIcon.innerHTML = `
        
            <i class="fas fa-plus"></i>
        
        `;

    });

});

</script>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-container table-responsive">
                        <table id="programme-table" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                   
                                    <th>Code</th>
                                    <th>Programme Title</th>
                                    <th>Sponsor Type</th>
                                    <th>Starting date </th>
                                    <th> End date </th>
                                    <th> Session Feedback</th>
                                    


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
    
    <script src="{{ asset('assets/summernote/summernote-lite.js') }}"></script>

    <script>
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
                url: "{{ url('faculty/announcement/store') }}/" + $('#programme_id').val(),
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

            var downloadqrurl = "{{ route('session-feedback-report') }}";
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
                        data: 'id',
                        name: 'entry_qr',
                        orderable: false,
                        searchable: false,

                        render: function(data, type, full, meta) {

                            return `
        
            <a  
                href="${downloadqrurl}?type=entry&program=${data}"
                target="_blank"
                class="btn btn-primary btn-sm"
            >
                Report
            </a>
        
        `;

                        }

                    },
                    


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
                                url: '/faculty/programme-management/delete/' + id,
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
                url: `/faculty/programme/toggle-status/${id}`,
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
