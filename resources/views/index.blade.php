@extends('admin.layouts.master')



@section('main-section')

    <style>
        .form-check,
        .form-group .month_radio {
            margin-bottom: 0;
            padding: 2px;
        }

        #program-name-list-container {
            height: auto !important;
            max-height: none !important;
            overflow: visible !important;
        }

        #program-name-list-container li {
            height: auto !important;
            overflow: visible !important;
        }

        .program-item {
            display: flex;
            align-items: flex-start;
            width: 100%;
        }

        .program-item .program_radio {
            flex: 0 0 auto;
            margin-top: 5px;
        }

        .program-name-label {
            display: block;
            flex: 1 1 auto;
            min-width: 0;
            margin-left: 10px;

            /* Full text show */
            white-space: normal !important;
            overflow: visible !important;
            text-overflow: unset !important;

            /* Long words/text ko next line mein bhejega */
            overflow-wrap: anywhere;
            word-break: break-word;

            /* Text ki height automatically increase hogi */
            height: auto !important;
            max-height: none !important;

            line-height: 1.5;
        }

        .nomination-badge {
            color: #fff;
            border-radius: 5px;
            padding: 7px 12px;
            text-align: center;
            font-size: 13px;
            white-space: nowrap;
        }

        .nomination-badge strong {
            margin-left: 3px;
        }

        .bg-purple {
            background-color: #8e24aa !important;
        }

       

        #nomination-summary {
    margin-top: 15px;
}

#nomination-summary .col {
    padding-left: 4px;
    padding-right: 4px;
}

.nomination-badge {
    color: #fff;
    border-radius: 5px;
    padding: 7px 12px;
    text-align: center;
    font-size: 13px;
    white-space: nowrap;
    width: 100%;
}

.nomination-badge strong {
    margin-left: 3px;
}

.bg-purple {
    background-color: #8e24aa !important;
}
    </style>
    <div class="container">

        <div class="page-inner">

            <div
                class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4 justify-content-between">

                <div>
                    <h3 class="fw-bold mb-3">Dashboard</h3>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <label for="financial-year-select" class="form-label mb-0 fw-bold">
                        Financial Year
                    </label>

                    <select id="financial-year-select" class="form-control" style="width: auto; min-width: 150px;">

                        @foreach ($financialYears as $fy)
                            <option value="{{ $fy }}" {{ $loop->first ? 'selected' : '' }}>
                                {{ $fy }}
                            </option>
                        @endforeach

                    </select>
                </div>

            </div>

            <div class="row">

                @if (Auth::guard('admin')->user()->role === 'admin')
                    <!-- Assigned Programmes -->
                    <div class="col-sm-6 col-md-3 d-flex">

                        <div class="card shadow h-100 w-100">

                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">
                                    Months of Calendar
                                </h5>
                            </div>

                            <div class="card-body">

                                @php
                                    $months = [
                                        'Jan',
                                        'Feb',
                                        'Mar',
                                        'Apr',
                                        'May',
                                        'June',
                                        'July',
                                        'Aug',
                                        'Sep',
                                        'Oct',
                                        'Nov',
                                        'Dec',
                                    ];

                                    $currentMonth = \Carbon\Carbon::now()->format('F');
                                @endphp

                                <ul class="row list-unstyled mb-0">

                                    @foreach ($months as $month)
                                        <li class="col-6 mb-2">

                                            <div class="form-check">

                                                <input class="form-check-input month_radio" type="radio" name="month"
                                                    id="month_{{ $month }}" value="{{ $loop->iteration }}"
                                                    {{ $currentMonth == $month ? 'checked' : '' }}>

                                                <label class="form-check-label" for="month_{{ $month }}">
                                                    {{ $month }}
                                                </label>

                                            </div>

                                        </li>
                                    @endforeach

                                </ul>
                            </div>

                        </div>

                    </div>



                    <!-- Assigned Programmes -->
                    <div class="col-sm-6 col-md-3 d-flex">

                        <div class="card shadow h-100 w-100">

                            <div class="card-header bg-danger text-white">
                                <h5 class="mb-0">
                                    Week of Programme
                                </h5>
                            </div>

                            <div class="card-body">



                                <ul class="row list-unstyled mb-0" id="date-list-container">


                                </ul>
                            </div>

                        </div>

                    </div>

                    <div class="col-sm-6 col-md-3 d-flex">

                        <div class="card shadow h-100 w-100">

                            <div class="card-header bg-warning text-white">
                                <h5 class="mb-0">
                                    Project Director
                                </h5>
                            </div>

                            <div class="card-body">



                                <ul class="row list-unstyled mb-0" id="project-director-list-container">


                                </ul>
                            </div>

                        </div>

                    </div>

                    <div class="col-sm-6 col-md-3 d-flex">

                        <div class="card shadow h-100 w-100 overflow-hidden">

                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0 text-truncate">
                                    Program Name
                                </h5>
                            </div>

                            <div class="card-body">
                                <ul class="row list-unstyled mb-0 g-2" id="program-name-list-container">
                                </ul>
                            </div>

                        </div>

                    </div>
                @endif

            </div>


            <div class="row mt-4">

                <!-- Nomination Confirmed -->
                <div class="col-md-6">
                    <div class="card shadow">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">Nomination Confirmed</h5>
                        </div>
                        <div class="card-body p-0" style="max-height: 400px; overflow-y: auto;">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Name</th>
                                        <th>Official Address</th>
                                        <th>Nominating Address</th>
                                    </tr>
                                </thead>
                                <tbody id="confirmed-participants-list">
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">Select a programme to view data
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Nomination UnConfirmed -->
                <div class="col-md-6">
                    <div class="card shadow">
                        <div class="card-header bg-warning text-white">
                            <h5 class="mb-0">Nomination UnConfirmed</h5>
                        </div>
                        <div class="card-body p-0" style="max-height: 400px; overflow-y: auto;">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Name</th>
                                        <th>Official Address</th>
                                        <th>Nominating Address</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="unconfirmed-participants-list">
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">Select a programme to view data
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Nomination Summary -->
                {{-- <div class="row mb-3" id="nomination-summary">

                    <div class="col">
                        <div class="nomination-badge bg-danger">
                            <span>Confirm :</span>
                            <strong id="confirm-count">0</strong>
                        </div>
                    </div>

                    <div class="col">
                        <div class="nomination-badge bg-warning">
                            <span>C.F.A :</span>
                            <strong id="cfa-count">0</strong>
                        </div>
                    </div>

                    <div class="col">
                        <div class="nomination-badge bg-primary">
                            <span>Regret :</span>
                            <strong id="regret-count">0</strong>
                        </div>
                    </div>

                    <div class="col">
                        <div class="nomination-badge bg-purple">
                            <span>UnConfirm :</span>
                            <strong id="unconfirm-count">0</strong>
                        </div>
                    </div>

                    <div class="col">
                        <div class="nomination-badge bg-success">
                            <span>Total Nomination :</span>
                            <strong id="total-nomination-count">0</strong>
                        </div>
                    </div>

                    <div class="col">
                        <div class="nomination-badge bg-info">
                            <span>Total Attended :</span>
                            <strong id="total-attended-count">0</strong>
                        </div>
                    </div>

                </div> --}}


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

    let currentWeeksData = [];

    // ---------------------------------------------------------
    // Escape HTML
    // ---------------------------------------------------------
    function escapeHtml(str) {
        return $('<div>').text(str ?? '').html();
    }


    // ---------------------------------------------------------
    // Selected Financial Year
    // ---------------------------------------------------------
    function getSelectedFinancialYear() {
        return $('#financial-year-select').val();
    }


    // ---------------------------------------------------------
    // Load Programme Dates
    // ---------------------------------------------------------
    function loadProgrammeDates() {

        const month = $('.month_radio:checked').val();

        if (!month) {
            return;
        }

        const financialYear = getSelectedFinancialYear();

        $('#project-director-list-container').empty();
        $('#program-name-list-container').empty();

        // Hide summary when programme changes
       // Summary visible rahega
$('#nomination-summary').show();

        $('#date-list-container').html(`
            <div class="text-center py-3 w-100">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>

                <p class="mt-2 mb-0">
                    Loading programme dates...
                </p>
            </div>
        `);


        $.ajax({

            url: '{{ route('admin.getAdminDashboardInfo') }}',

            type: 'GET',

            data: {
                type: 'program_dates',
                month: month,
                financial_year: financialYear
            },

            success: function(response) {

                $('#date-list-container').empty();

                if (response.success && response.data.length > 0) {

                    currentWeeksData = response.data;

                    response.data.forEach(function(item) {

                        $('#date-list-container').append(`
                            <li class="col-12 mb-2">

                                <div class="form-check">

                                    <input
                                        class="form-check-input week_radio"
                                        type="radio"
                                        name="date"
                                        id="date_${escapeHtml(item.from_date)}"
                                        value="${escapeHtml(item.from_date)}"
                                    >

                                    <label
                                        class="form-check-label"
                                        for="date_${escapeHtml(item.from_date)}"
                                    >
                                        ${escapeHtml(item.from_date)}
                                        -
                                        ${escapeHtml(item.to_date)}
                                    </label>

                                </div>

                            </li>
                        `);

                    });

                } else {

                    currentWeeksData = [];

                    $('#date-list-container').html(`
                        <div class="text-center text-danger py-3">
                            No programme dates found
                        </div>
                    `);
                }

            },

            error: function(xhr) {

                console.log(xhr.responseText);

                currentWeeksData = [];

                $('#date-list-container').html(`
                    <div class="text-center text-danger py-3">
                        Error fetching dates
                    </div>
                `);
            }
        });
    }


    // ---------------------------------------------------------
    // Month Change
    // ---------------------------------------------------------
    $('.month_radio').change(function() {

        loadProgrammeDates();

    });


    // ---------------------------------------------------------
    // Financial Year Change
    // ---------------------------------------------------------
    $('#financial-year-select').change(function() {

        loadProgrammeDates();

    });


    // ---------------------------------------------------------
    // Initial Load
    // ---------------------------------------------------------
    if ($('.month_radio:checked').length > 0) {

        loadProgrammeDates();

    }


    // ---------------------------------------------------------
    // WEEK SELECTED -> Project Director
    // ---------------------------------------------------------
    $(document).on('change', '.week_radio', function() {

        const selectedFromDate = $(this).val();

        $('#program-name-list-container').empty();

        // Hide nomination summary
        $('#nomination-summary').hide();

        // Clear tables
        $('#confirmed-participants-list').html(`
            <tr>
                <td colspan="4" class="text-center text-muted">
                    Select a programme to view data
                </td>
            </tr>
        `);

        $('#unconfirmed-participants-list').html(`
            <tr>
                <td colspan="5" class="text-center text-muted">
                    Select a programme to view data
                </td>
            </tr>
        `);


        const week = currentWeeksData.find(function(w) {

            return w.from_date === selectedFromDate;

        });


        const $directorContainer =
            $('#project-director-list-container');

        $directorContainer.empty();


        if (
            !week ||
            !week.programmes ||
            week.programmes.length === 0
        ) {

            $directorContainer.html(`
                <div class="text-center text-danger py-3">
                    No directors found
                </div>
            `);

            return;
        }


        const directorSet = new Set();


        week.programmes.forEach(function(prog) {

            if (
                prog.director_1 &&
                prog.director_1 !== '-'
            ) {

                directorSet.add(prog.director_1);

            }


            if (
                prog.director_2 &&
                prog.director_2 !== '-'
            ) {

                directorSet.add(prog.director_2);

            }

        });


        if (directorSet.size === 0) {

            $directorContainer.html(`
                <div class="text-center text-danger py-3">
                    No directors found
                </div>
            `);

            return;
        }


        directorSet.forEach(function(directorName, index) {

            const safeId =
                'director_' +
                index +
                '_' +
                directorName.replace(/\s+/g, '_');


            $directorContainer.append(`
                <li class="col-12 mb-2">

                    <div class="form-check">

                        <input
                            class="form-check-input director_radio"
                            type="radio"
                            name="director"
                            id="${escapeHtml(safeId)}"
                            value="${escapeHtml(directorName)}"
                        >

                        <label
                            class="form-check-label"
                            for="${escapeHtml(safeId)}"
                        >
                            ${escapeHtml(directorName)}
                        </label>

                    </div>

                </li>
            `);

        });

    });


    // ---------------------------------------------------------
    // DIRECTOR SELECTED -> Programme Name
    // ---------------------------------------------------------
    $(document).on('change', '.director_radio', function() {

        const selectedDirector = $(this).val();

        const selectedFromDate =
            $('.week_radio:checked').val();

        const $programContainer =
            $('#program-name-list-container');

        $programContainer.empty();

        // Hide summary
        $('#nomination-summary').hide();


        const week = currentWeeksData.find(function(w) {

            return w.from_date === selectedFromDate;

        });


        if (
            !week ||
            !week.programmes ||
            week.programmes.length === 0
        ) {

            $programContainer.html(`
                <div class="text-center text-danger py-3">
                    No programmes found
                </div>
            `);

            return;
        }


        const matchingProgrammes =
            week.programmes.filter(function(prog) {

                return (
                    prog.director_1 === selectedDirector ||
                    prog.director_2 === selectedDirector
                );

            });


        if (matchingProgrammes.length === 0) {

            $programContainer.html(`
                <div class="text-center text-danger py-3">
                    No programmes found
                </div>
            `);

            return;
        }


        matchingProgrammes.forEach(function(prog) {

            $programContainer.append(`
                <li class="col-12 mb-2">

                    <div class="form-check d-flex align-items-start">

                        <input
                            class="form-check-input program_radio flex-shrink-0 mt-1"
                            type="radio"
                            name="program_name"
                            id="program_${escapeHtml(prog.id)}"
                            value="${escapeHtml(prog.id)}"
                        >

                        <label
                            class="form-check-label ms-2 program-name-label"
                            for="program_${escapeHtml(prog.id)}"
                        >
                            ${escapeHtml(prog.programme_name)}
                        </label>

                    </div>

                </li>
            `);

        });

    });


    // ---------------------------------------------------------
    // PROGRAM SELECTED -> Participants
    // ---------------------------------------------------------
    $(document).on('change', '.program_radio', function() {

        const programmeId = $(this).val();

        loadNominationStatus(programmeId);

    });


    // ---------------------------------------------------------
    // Load Nomination Status
    // ---------------------------------------------------------
    function loadNominationStatus(programmeId) {

       // Summary hamesha visible rahe
$('#nomination-summary').show();

// Initial counts
$('#confirm-count').text('0');
$('#cfa-count').text('0');
$('#regret-count').text('0');
$('#unconfirm-count').text('0');
$('#total-nomination-count').text('0');
$('#total-attended-count').text('0');

$.ajax({
    url: '{{ route('admin.getAdminDashboardInfo') }}',
    type: 'GET',
    data: {
        type: 'nomination_summary'
    },

    success: function(response) {

        console.log('Nomination Summary:', response);

        if (!response.success) {
            return;
        }

        $('#confirm-count').text(response.confirm_count ?? 0);
        $('#cfa-count').text(response.cfa_count ?? 0);
        $('#regret-count').text(response.regret_count ?? 0);
        $('#unconfirm-count').text(response.unconfirm_count ?? 0);
        $('#total-nomination-count').text(response.total_nomination ?? 0);
        $('#total-attended-count').text(response.total_attended ?? 0);

        // Visible rahe
        $('#nomination-summary').show();
    },

    error: function(xhr) {
        console.log('Summary Error:', xhr.responseText);
    }
});

        // Confirmed loading
        $('#confirmed-participants-list').html(`
            <tr>
                <td colspan="4" class="text-center py-3">

                    <div
                        class="spinner-border spinner-border-sm text-success"
                        role="status"
                    ></div>

                    Loading...

                </td>
            </tr>
        `);


        // Unconfirmed loading
        $('#unconfirmed-participants-list').html(`
            <tr>
                <td colspan="5" class="text-center py-3">

                    <div
                        class="spinner-border spinner-border-sm text-warning"
                        role="status"
                    ></div>

                    Loading...

                </td>
            </tr>
        `);


        $.ajax({

            url: '{{ route('admin.getAdminDashboardInfo') }}',

            type: 'GET',

            data: {

                type: 'program_participants',

                programme_id: programmeId

            },


            success: function(response) {

                console.log('Programme Response:', response);


                // -------------------------------------------------
                // Error
                // -------------------------------------------------
                if (!response.success) {

                    $('#nomination-summary').hide();

                    $('#confirmed-participants-list').html(`
                        <tr>
                            <td
                                colspan="4"
                                class="text-center text-danger"
                            >
                                Error loading data
                            </td>
                        </tr>
                    `);


                    $('#unconfirmed-participants-list').html(`
                        <tr>
                            <td
                                colspan="5"
                                class="text-center text-danger"
                            >
                                Error loading data
                            </td>
                        </tr>
                    `);

                    return;
                }


                // -------------------------------------------------
                // NOMINATION SUMMARY
                // -------------------------------------------------

                const confirmedCount =
                    response.confirm_count ?? 0;

                const cfaCount =
                    response.cfa_count ?? 0;

                const regretCount =
                    response.regret_count ?? 0;

                const unconfirmCount =
                    response.unconfirm_count ?? 0;

                const totalNomination =
                    response.total_nomination ?? 0;

                const totalAttended =
                    response.total_attended ?? 0;


                // Set values
                $('#confirm-count').text(
                    confirmedCount
                );

                $('#cfa-count').text(
                    cfaCount
                );

                $('#regret-count').text(
                    regretCount
                );

                $('#unconfirm-count').text(
                    unconfirmCount
                );

                $('#total-nomination-count').text(
                    totalNomination
                );

                $('#total-attended-count').text(
                    totalAttended
                );


                // Show summary
                $('#nomination-summary').show();


                // -------------------------------------------------
                // ADDRESS
                // -------------------------------------------------

                const officialAddress =
                    response.official_address ?? '-';

                const nominatingAddress =
                    response.nominating_address ?? '-';


                // -------------------------------------------------
                // CONFIRMED PARTICIPANTS
                // -------------------------------------------------

                $('#confirmed-participants-list').empty();


                if (
                    response.confirmed &&
                    response.confirmed.length > 0
                ) {

                    response.confirmed.forEach(
                        function(p, index) {

                            $('#confirmed-participants-list')
                                .append(`
                                    <tr>

                                        <td>
                                            ${index + 1}
                                        </td>

                                        <td>
                                            ${escapeHtml(p.name)}
                                        </td>

                                        <td>
                                            ${escapeHtml(
                                                officialAddress
                                            )}
                                        </td>

                                        <td>
                                            ${escapeHtml(
                                                nominatingAddress
                                            )}
                                        </td>

                                    </tr>
                                `);

                        }
                    );

                } else {

                    $('#confirmed-participants-list').html(`
                        <tr>

                            <td
                                colspan="4"
                                class="text-center text-muted"
                            >
                                No confirmed nominations
                            </td>

                        </tr>
                    `);

                }


                // -------------------------------------------------
                // UNCONFIRMED PARTICIPANTS
                // -------------------------------------------------

                $('#unconfirmed-participants-list').empty();


                if (
                    response.unconfirmed &&
                    response.unconfirmed.length > 0
                ) {

                    response.unconfirmed.forEach(
                        function(p, index) {

                            $('#unconfirmed-participants-list')
                                .append(`
                                    <tr>

                                        <td>
                                            ${index + 1}
                                        </td>

                                        <td>
                                            ${escapeHtml(p.name)}
                                        </td>

                                        <td>
                                            ${escapeHtml(
                                                officialAddress
                                            )}
                                        </td>

                                        <td>
                                            ${escapeHtml(
                                                nominatingAddress
                                            )}
                                        </td>

                                        <td>
                                            ${escapeHtml(
                                                p.status ?? 'Pending'
                                            )}
                                        </td>

                                    </tr>
                                `);

                        }
                    );

                } else {

                    $('#unconfirmed-participants-list').html(`
                        <tr>

                            <td
                                colspan="5"
                                class="text-center text-muted"
                            >
                                No unconfirmed nominations
                            </td>

                        </tr>
                    `);

                }

            },


            error: function(xhr) {

                console.log(
                    'Dashboard Error:',
                    xhr.responseText
                );


                $('#nomination-summary').hide();


                $('#confirmed-participants-list').html(`
                    <tr>

                        <td
                            colspan="4"
                            class="text-center text-danger"
                        >
                            Error fetching data
                        </td>

                    </tr>
                `);


                $('#unconfirmed-participants-list').html(`
                    <tr>

                        <td
                            colspan="5"
                            class="text-center text-danger"
                        >
                            Error fetching data
                        </td>

                    </tr>
                `);

            }

        });

    }

});


</script>

@endsection