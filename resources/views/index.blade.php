@extends('admin.layouts.master')



@section('main-section')

    <style>
        .form-check,
        .form-group .month_radio {
            margin-bottom: 0;
            padding: 2px;
        }
    </style>
    <div class="container">

        <div class="page-inner">

            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">

                <div>

                    <h3 class="fw-bold mb-3">Dashboard</h3>

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

                        <div class="card shadow h-100 w-100">

                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0">
                                 Program Name
                                </h5>
                            </div>

                            <div class="card-body">



                                <ul class="row list-unstyled mb-0" id="program-name-list-container">


                                </ul>
                            </div>

                        </div>

                    </div>
                @endif

            </div>

            <br>
            <br>



            <div class="row">

                @if (Auth::guard('admin')->user()->role === 'admin')
                    <!-- Assigned Programmes -->

                    <div class="col-sm-6 col-md-3 d-flex">

                        <div class="card text-center shadow h-100 w-100">

                            <div class="card-body">

                                <div class="mb-3 text-primary fs-1">

                                    <i class="fas fa-users"></i>

                                </div>

                                <h5 class="card-title">{{ $programme->count() }}</h5>

                                <h6 class="card-subtitle mb-2 text-muted">Total Programme</h6>

                                <p class="card-text small">Click here to see the total programme</p>

                            </div>

                            <div class="card-footer bg-transparent border-top-0">

                                <a href="{{ route('dashboard.programme.list') }}"
                                    class="btn btn-outline-primary btn-sm">View Details</a>

                            </div>

                        </div>

                    </div>



                    <!-- Active Programmes -->

                    <div class="col-sm-6 col-md-3 d-flex">

                        <div class="card text-center shadow h-100 w-100">

                            <div class="card-body">

                                <div class="mb-3 text-success fs-1">

                                    <i class="fas fa-play-circle"></i>

                                </div>

                                <h5 class="card-title">{{ $activeProgrammes->count() }}</h5>

                                <h6 class="card-subtitle mb-2 text-muted">Active Programmes</h6>

                                <p class="card-text small">click here to see the total active programme</p>

                            </div>


                        </div>

                    </div>



                    <!-- Total Faculty -->

                    <div class="col-sm-6 col-md-5 d-flex">

                        <div class="card text-center shadow h-100 w-100">

                            <div class="card-body">

                                <div class="mb-3 text-info fs-1">

                                    <i class="fas fa-user-tie"></i>

                                </div>

                                <h5 class="card-title">{{ $user->count() }}</h5>

                                <h6 class="card-subtitle mb-2 text-muted">Total Faculty</h6>

                                <p class="card-text small">View all faculty members.</p>

                            </div>

                            <div class="card-footer bg-transparent border-top-0">

                                <a href="{{ route('dashboard.faculty.list') }}" class="btn btn-outline-info btn-sm">View
                                    Details</a>

                            </div>

                        </div>

                    </div>





                    {{-- here is the card for the view nomination --}}

                    <div class="col-sm-6 col-md-3 d-flex">

                        <div class="card text-center shadow h-100 w-100">

                            <div class="card-body">

                                <div class="mb-3 text-info fs-1">

                                    <i class="fas fa-user-tie"></i>

                                </div>

                                {{-- <h5 class="card-title">{{$user->count()}}</h5> --}}

                                <h6 class="card-subtitle mb-2 text-muted">View Nomination </h6>

                                <p class="card-text small">View all nomination according to programme.</p>

                            </div>

                            <div class="card-footer bg-transparent border-top-0">

                                <a href="{{ route('participants.reports') }}" class="btn btn-outline-info btn-sm">View
                                    Details</a>

                            </div>

                        </div>

                    </div>
                @endif

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

            $('.month_radio').change(function() {

                var month = $(this).val();
                var year = new Date().getFullYear();

                // Show Loader
                $('#date-list-container').html(`
            <div class="text-center py-3 w-100">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>

                <p class="mt-2 mb-0">Loading programme dates...</p>
            </div>
        `);

                $.ajax({

                    url: '{{ route('admin.getAdminDashboardInfo') }}',
                    type: 'GET',

                    data: {
                        type: 'program_dates',
                        month: month,
                        year: year
                    },

                    success: function(response) {

                        $('#date-list-container').empty();

                        if (response.success && response.data.length > 0) {

                            response.data.forEach(function(item) {

                                $('#date-list-container').append(`

                            <li class="col-12 mb-2">

                                <div class="form-check">

                                    <input
                                        class="form-check-input week_radio"
                                        type="radio"
                                        name="date"
                                        id="date_${item.from_date}"
                                        value="${item.from_date}"
                                    >

                                    <label
                                        class="form-check-label"
                                        for="date_${item.from_date}"
                                    >
                                        ${item.from_date} - ${item.to_date}
                                    </label>

                                </div>

                            </li>

                        `);

                            });

                        } else {

                            $('#date-list-container').html(`
                        <div class="text-center text-danger py-3">
                            No programme dates found
                        </div>
                    `);
                        }
                    },

                    error: function() {

                        $('#date-list-container').html(`
                    <div class="text-center text-danger py-3">
                        Error fetching dates
                    </div>
                `);

                    }

                });

            });

        });
    </script>
@endsection
