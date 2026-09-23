@extends('admin.layouts.master')
@section('main-section')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">View Nomination</h3>
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
                        <a href="#">Nomination</a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">View Nomination</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <!-- Calendar Year -->
                                        <div class="col-md-4 mb-3">
                                            <label for="cal_year" class="form-label">Calendar Year</label>

                                            <select class="form-control" id="cal_year" name="cal_year">
                                                <option value="">Select Calendar Year</option>

                                                @foreach ($distinctYears as $year)
                                                    <option value="{{ $year }}">
                                                        {{ $year }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>


                                        <!-- Programme Code -->
                                        <div class="col-md-4 mb-3">
                                            <label for="programme_code" class="form-label">Code</label>

                                            <input type="text" id="programme_code" class="form-control"
                                                placeholder="Enter unique code">
                                        </div>
                                        <!-- Programme -->
                                        <!-- Programme -->
                                            <div class="col-md-4 mb-3">
                                                <label for="programme_id" class="form-label">Programme</label>

                                                <select id="programme_id" class="form-control">
                                                    <option value="">Select Programme</option>

                                                    @foreach ($programmes as $prog)
                                                        <option value="{{ $prog->id }}"
                                                            data-year="{{ $prog->financial_year }}"
                                                            data-code="{{ $prog->unique_id }}">
                                                            {{ $prog->unique_id }} - {{ Str::limit($prog->title, 50) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                    </div>

                                    <!-- Status Bar -->
                                    <!-- Status Bar -->
                                    <div class="mt-4 border-top pt-3">
                                        <span class="fw-bold me-3">Nomination Status:</span>

                                        @if (Auth::guard('admin')->user()->hasAccess('nomination', 'edit'))
                                            <button class="btn btn-success me-2" id="btn-confirm">
                                                <i class="fas fa-check-circle me-1"></i>Confirm
                                            </button>
                                            <button class="btn btn-danger me-2" id="btn-regret">
                                                <i class="fas fa-times-circle me-1"></i>Regret
                                            </button>
                                            <button class="btn btn-warning me-2" id="btn-cancel">
                                                <i class="fas fa-ban me-1"></i>Cancel
                                            </button>
                                            <button class="btn btn-secondary" id="btn-pending">
                                                <i class="fas fa-clock me-1"></i>Set To Pending
                                            </button>
                                        @else
                                            <span class="text-muted">You don't have permission to change nomination
                                                status.</span>
                                        @endif
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="table-container table-responsive">
                                <table id="participants-table" class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th width="40px"><input type="checkbox" id="select-all"></th>
                                            <th>Participant Name</th>
                                            <th>Designation</th>
                                            <th>Nominating Agency</th>
                                            <th>City</th>
                                            <th>Contact Info</th>
                                            <th>Status</th>


                                            <th>Check-in</th>
                                            <th>Check-out</th>
                                            <th>Current FY</th>
                                            <th>Last FY</th>
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

        <script>
            $(document).ready(function() {

                const yearSelect = $('#cal_year');
                const programmeSelect = $('#programme_id');
                const programmeCode = $('#programme_code');
                const tableElem = $('#participants-table');

                let selectedParticipants = [];
                let codeTimer;


                /*
                |--------------------------------------------------------------------------
                | DataTable
                |--------------------------------------------------------------------------
                */

                const table = tableElem.DataTable({

                    processing: true,
                    serverSide: true,

                    ajax: {
                        url: '{{ route('admin.nominations.byProgramme.confirm') }}',

                        data: function(d) {

                            // Calendar Year
                            d.cal_year = yearSelect.val();

                            // Programme ID
                            d.programme_id = programmeSelect.val();

                            // Programme Code
                            d.programme_code = programmeCode.val();

                        }
                    },

                    columns: [

                        {
                            data: null,
                            orderable: false,
                            searchable: false,

                            render: function(data) {

                                return `
                        <input type="checkbox"
                               class="participant-checkbox"
                               data-id="${data.id}">
                    `;
                            }
                        },

                        {
                            data: 'name',

                            render: function(data, type, row) {

                                return `
                        <a href="{{ route('participant.detail.prog', ':id') }}"
                           class="text-primary">
                            ${data}
                        </a>
                    `.replace(':id', row.id);
                            }
                        },

                        {
                            data: 'designation',
                            defaultContent: '-'
                        },

                        {
                            data: 'nomination.agency_type.name',

                            render: function(data) {

                                return data ||
                                    '<span class="text-muted">N/A</span>';
                            }
                        },

                        {
                            data: 'city',
                            defaultContent: '-'
                        },

                        {
                            data: null,

                            render: function(data) {

                                const email = data.email ?
                                    `<div>
                            <i class="fas fa-envelope mr-1"></i>
                            ${data.email}
                           </div>` :
                                    '';

                                const phone = data.phone ?
                                    `<div>
                            <i class="fas fa-phone mr-1"></i>
                            ${data.phone}
                           </div>` :
                                    '';

                                return email + phone ||
                                    '<span class="text-muted">N/A</span>';
                            }
                        },

                        {
                            data: 'status',

                            render: function(data) {

                                if (!data) {
                                    return '<span class="badge badge-secondary">Pending</span>';
                                }

                                const statusClass = {
                                    'confirm': 'badge-success',
                                    'regret': 'badge-danger',
                                    'cancel': 'badge-warning',
                                    'pending': 'badge-secondary'
                                } [data] || 'badge-info';

                                return `
                        <span class="badge ${statusClass}">
                            ${data.charAt(0).toUpperCase() + data.slice(1)}
                        </span>
                    `;
                            }
                        },

                        {
                            data: 'hostel_attendence',

                            render: function(data) {

                                return data ?
                                    `<span class="badge badge-info">${data}</span>` :
                                    '<span class="text-muted">N/A</span>';
                            }
                        },

                        {
                            data: null,

                            render: function(data) {

                                if (!data.checkout_time && !data.date) {
                                    return '<span class="text-muted">N/A</span>';
                                }

                                return `
                        <div>
                            <strong>Time:</strong>
                            ${data.checkout_time || 'N/A'}
                        </div>

                        <div>
                            <strong>Date:</strong>
                            ${data.date || 'N/A'}
                        </div>
                    `;
                            }
                        },

                        {
                            data: 'current_fy_count',

                            render: function(data, type, row) {

                                return `
                        <a href="{{ route('participant.current.prog', ':id') }}"
                           class="badge badge-primary">
                            ${data || 0}
                        </a>
                    `.replace(':id', row.id);
                            }
                        },

                        {
                            data: 'last_fy_count',

                            render: function(data, type, row) {

                                return `
                        <a href="{{ route('participant.last.prog', ':id') }}"
                           class="badge badge-secondary">
                            ${data || 0}
                        </a>
                    `.replace(':id', row.id);
                            }
                        }

                    ],

                    language: {

                        emptyTable: 'No participants found for the selected criteria',

                        processing: '<i class="fas fa-spinner fa-spin"></i> Loading participants...'
                    },

                    createdRow: function(row, data) {

                        $(row).attr('data-id', data.id);
                    }
                });


                /*
                |--------------------------------------------------------------------------
                | Calendar Year
                |--------------------------------------------------------------------------
                */

                yearSelect.on('change', function() {

                    const selectedYear = $(this).val();

                    /*
                    |--------------------------------------------------------------------------
                    | Programme dropdown ko selected year ke according filter karo
                    |--------------------------------------------------------------------------
                    */

                    programmeSelect
                        .find('option[data-year]')
                        .each(function() {

                            const optionYear = $(this).attr('data-year');

                            if (!selectedYear) {

                                $(this).show();

                            } else {

                                $(this).toggle(
                                    optionYear === selectedYear
                                );
                            }
                        });


                    /*
                    |--------------------------------------------------------------------------
                    | Programme reset
                    |--------------------------------------------------------------------------
                    */

                    programmeSelect.val('');


                    /*
                    |--------------------------------------------------------------------------
                    | Programme Code reset
                    |--------------------------------------------------------------------------
                    */

                    programmeCode.val('');


                    /*
                    |--------------------------------------------------------------------------
                    | DataTable reload
                    |--------------------------------------------------------------------------
                    */

                    table.ajax.reload();
                });


                /*
                |--------------------------------------------------------------------------
                | Programme Change
                |--------------------------------------------------------------------------
                */

                programmeSelect.on('change', function() {

                    const selectedOption =
                        $(this).find('option:selected');

                    /*
                    |--------------------------------------------------------------------------
                    | Automatically programme code fill karo
                    |--------------------------------------------------------------------------
                    */

                    if ($(this).val()) {

                        const code =
                            selectedOption.attr('data-code');

                        if (code) {
                            programmeCode.val(code);
                        }

                    } else {

                        programmeCode.val('');
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Reload table
                    |--------------------------------------------------------------------------
                    */

                    table.ajax.reload();
                });


                /*
                |--------------------------------------------------------------------------
                | Programme Code Search
                |--------------------------------------------------------------------------
                */

                programmeCode.on('keyup', function() {

                    clearTimeout(codeTimer);

                    codeTimer = setTimeout(function() {

                        const code =
                            programmeCode.val().trim();

                        /*
                        |--------------------------------------------------------------------------
                        | Agar code empty hai
                        |--------------------------------------------------------------------------
                        */

                        if (code === '') {

                            table.ajax.reload();

                            return;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | DataTable server-side filter
                        |--------------------------------------------------------------------------
                        */

                        table.ajax.reload();

                    }, 500);
                });


                /*
                |--------------------------------------------------------------------------
                | Select All
                |--------------------------------------------------------------------------
                */

                $('#select-all').on('change', function() {

                    $('.participant-checkbox')
                        .prop('checked', this.checked);

                    updateSelectedParticipants();
                });


                /*
                |--------------------------------------------------------------------------
                | Individual Checkbox
                |--------------------------------------------------------------------------
                */

                tableElem.on(
                    'change',
                    '.participant-checkbox',
                    function() {

                        updateSelectedParticipants();

                        $('#select-all').prop(
                            'checked',
                            $('.participant-checkbox:checked').length ===
                            $('.participant-checkbox').length
                        );
                    }
                );


                /*
                |--------------------------------------------------------------------------
                | Selected Participants
                |--------------------------------------------------------------------------
                */

                function updateSelectedParticipants() {

                    selectedParticipants =
                        $('.participant-checkbox:checked')
                        .map(function() {

                            return $(this).data('id');

                        })
                        .get();
                }


                /*
                |--------------------------------------------------------------------------
                | Alert
                |--------------------------------------------------------------------------
                */

                function showAlert(type, message) {

                    const alert = $(`
            <div class="alert alert-${type}
                        alert-dismissible fade show"
                 role="alert">

                ${message}

                <button type="button"
                        class="close"
                        data-dismiss="alert">

                    <span>&times;</span>

                </button>

            </div>
        `);

                    $('.container-fluid').prepend(alert);

                    setTimeout(function() {

                        alert.alert('close');

                    }, 5000);
                }


                /*
                |--------------------------------------------------------------------------
                | Common Action
                |--------------------------------------------------------------------------
                */

              function performAction(url, data) {

    $.ajax({
        url: url,
        type: 'POST',
        data: data,

        success: function(response) {

            console.log('Response:', response);

            if (response.success) {

                let message = response.message;

                // Mail failed hone par details console me
                if (response.mail_errors && response.mail_errors.length > 0) {
                    console.log('Mail Errors:', response.mail_errors);
                }

                Swal.fire({
                    icon: response.mail_errors?.length ? 'warning' : 'success',
                    title: response.mail_errors?.length
                        ? 'Status Updated'
                        : 'Success',
                    text: message
                });

            } else {

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message || 'Something went wrong.'
                });
            }

            $('#participants-table').DataTable().ajax.reload(null, false);
        },

        error: function(xhr) {

            console.log('AJAX Error:', xhr.responseText);

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: xhr.responseJSON?.message || 'Something went wrong.'
            });
        }
    });
}

                /*
                |--------------------------------------------------------------------------
                | Confirm
                |--------------------------------------------------------------------------
                */

                $('#btn-confirm').on('click', function() {

                    performAction(

                        '{{ route('admin.nominations.updateStatus') }}',

                        {
                            _token: '{{ csrf_token() }}',

                            participant_ids: selectedParticipants,

                            status: 'confirm'
                        },

                        'Nomination status updated to Confirm.'
                    );
                });


                /*
                |--------------------------------------------------------------------------
                | Regret
                |--------------------------------------------------------------------------
                */

                $('#btn-regret').on('click', function() {

                    performAction(

                        '{{ route('admin.nominations.updateStatus') }}',

                        {
                            _token: '{{ csrf_token() }}',

                            participant_ids: selectedParticipants,

                            status: 'regret'
                        },

                        'Nomination status updated to Regret.'
                    );
                });


                /*
                |--------------------------------------------------------------------------
                | Cancel
                |--------------------------------------------------------------------------
                */

                $('#btn-cancel').on('click', function() {

                    performAction(

                        '{{ route('admin.nominations.updateStatus') }}',

                        {
                            _token: '{{ csrf_token() }}',

                            participant_ids: selectedParticipants,

                            status: 'cancel'
                        },

                        'Nomination status updated to Cancel.'
                    );
                });


                /*
                |--------------------------------------------------------------------------
                | Pending
                |--------------------------------------------------------------------------
                */

                $('#btn-pending').on('click', function() {

                    performAction(

                        '{{ route('admin.nominations.updateStatus') }}',

                        {
                            _token: '{{ csrf_token() }}',

                            participant_ids: selectedParticipants,

                            status: 'pending'
                        },

                        'Nomination status set to Pending.'
                    );
                });

            });
        </script>
    @endsection
