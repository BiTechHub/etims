@extends('admin.layouts.master')

@section('main-section')
<link rel="stylesheet" href="{{ asset('assets/summernote/summernote-lite.css') }}">
    <div class="container">

        <div class="page-inner">

            <div class="page-header">

                <h3 class="fw-bold mb-3">Add Programme Session</h3>

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

                        <a href="#">Add Programme Session</a>

                    </li>

                </ul>

            </div>

            <div class="row">

                <div class="col-md-12">

                    <div class="card">

                        <div class="card-body">

                            <form id="programme-form" action="{{ route('faculty.session.store') }}" method="POST">

                                @csrf

                                <div class="row">



                                    <div class="col-md-2 form-group">

                                        <label for="programme_code" class="form-label">Code</label>

                                        <input type="text" id="programme_code" class="form-control"
                                            placeholder="Enter unique code">

                                    </div>

                                    <div class="col-md-6 form-group">

                                        <label for="programme_id">Programme Title <span class="text-danger">*</span></label>

                                        <select class="form-control" name="programme_id" id="programme_id" required>

                                            <option value="">Select Programme</option>

                                            @foreach ($programmes as $programme)
                                                <option value="{{ $programme->id }}"
                                                    data-from="{{ \Carbon\Carbon::parse($programme->from_date)->format('Y-m-d') }}"
                                                    data-to="{{ \Carbon\Carbon::parse($programme->to_date)->format('Y-m-d') }}">

                                                    {{ $programme->title }}

                                                </option>
                                            @endforeach

                                        </select>

                                    </div>



                                    <div class="col-md-2 form-group">

                                        <label for="from_date">Start Date</label>

                                        <input type="date" class="form-control" name="from_date" id="from_date" readonly>

                                    </div>



                                    <div class="col-md-2 form-group">

                                        <label for="to_date">End Date</label>

                                        <input type="date" class="form-control" name="to_date" id="to_date" readonly>

                                    </div>

                                </div>



                                <!-- BREAK FIELDS -->

                                <div class="row mt-3">

                                    <div class="col-md-4">

                                        <div class="form-check">

                                            <input class="form-check-input" type="checkbox" name="include_tea_break"
                                                id="include-tea-break">

                                            <label class="form-check-label" for="include-tea-break">Include Tea
                                                Break</label>

                                        </div>

                                    </div>

                                    <div class="col-md-4">

                                        <div class="form-check">

                                            <input class="form-check-input" type="checkbox" name="include_lunch_break"
                                                id="include-lunch-break">

                                            <label class="form-check-label" for="include-lunch-break">Include Lunch
                                                Break</label>

                                        </div>

                                    </div>

                                </div>



                                <!-- Tea Break Fields (initially hidden) -->

                                <div id="tea-break-fields" style="display: none;">

                                    <div class="row mt-3">

                                        <div class="col-md-4">

                                            <label>Tea Break Time</label>

                                            <input type="time" name="tea_break_time" class="form-control">

                                        </div>

                                        <div class="col-md-4">

                                            <label>Tea Break Duration (minutes)</label>

                                            <input type="number" name="tea_break_duration" class="form-control"
                                                min="5" max="60" value="15">

                                        </div>

                                    </div>

                                </div>



                                <!-- Lunch Break Fields (initially hidden) -->

                                <div id="lunch-break-fields" style="display: none; margin-top:10px">

                                    <div class="row mt-3">

                                        <div class="col-md-4">

                                            <label>Lunch Break Time</label>

                                            <input type="time" name="lunch_break_time" class="form-control">

                                        </div>

                                        <div class="col-md-4">

                                            <label>Lunch Break Duration (minutes)</label>

                                            <input type="number" name="lunch_break_duration" class="form-control"
                                                min="30" max="120" value="60">

                                        </div>

                                    </div>

                                </div>



                                <div id="breaks-container"></div>

                                <div class="row mt-3">

                                    <div class="col-md-12">

                                        <button type="button" id="add-tea-break" class="btn btn-sm btn-info">

                                            <i class="fas fa-coffee"></i> Add Tea Break

                                        </button>

                                        <button type="button" id="add-lunch-break" class="btn btn-sm btn-warning">

                                            <i class="fas fa-utensils"></i> Add Lunch Break

                                        </button>

                                        <span class="text-muted ms-3" id="no-session-warning" style="display: none;">
                                            <i class="fas fa-exclamation-triangle text-warning"></i> 
                                            Please add sessions first before adding breaks.
                                        </span>

                                    </div>

                                </div>



                                <hr>

                                <hr>



                                <h5 class="mt-4">Subtopics and Faculties</h5>

                                <div id="subtopics-wrapper">

                                    <div class="row subtopic-row mb-2">

                                        <!-- SESSION NAME INPUT -->
                                        <div class="col-md-2">
                                            <input type="text" name="subtopics[0][session_name]" class="form-control session-name-input" placeholder="Session Name (e.g. I, II)">
                                        </div>

                                        <div class="col-md-2">

                                            <input type="text" name="subtopics[0][title]" class="form-control"
                                                placeholder="Topic Title" required>

                                        </div>

                                        <div class="col-md-2">

                                            <input type="date" name="subtopics[0][date]"
                                                class="form-control subtopic-date" required>

                                        </div>

                                        <div class="col-md-2">

                                            <input type="time" name="subtopics[0][start_time]" class="form-control"
                                                required>

                                        </div>

                                        <div class="col-md-2">

                                            <input type="time" name="subtopics[0][end_time]" class="form-control"
                                                required>

                                        </div>

                                        <div class="col-md-2">

                                            <select name="subtopics[0][type]" class="form-control type-selector" required>

                                                <option value="">Select Type</option>

                                                <option value="faculty">Faculty</option>

                                                <option value="guest">Guest Faculty</option>

                                                <option value="Both">Faculty and Guest</option>

                                            </select>

                                        </div>



                                        <!-- Faculty Dropdown -->

                                        <div class="col-md-3 faculty-dropdown" style="display: none;">

                                            <label>Select Faculty</label>

                                            <select name="subtopics[0][faculty_id][]"
                                                class="form-control custom-multiselect" multiple size="5">

                                                @foreach ($facultyUsers as $faculty)
                                                    <option value="{{ $faculty->id }}">{{ $faculty->name }}</option>
                                                @endforeach

                                            </select>

                                        </div>



                                        <!-- Guest Faculty Dropdown -->

                                        <div class="col-md-3 guest-dropdown" style="display: none;">

                                            <label>Select Guest Faculty</label>

                                            <select name="subtopics[0][guest_faculty_id][]"
                                                class="form-control custom-multiselect" multiple size="5">

                                                @foreach ($guestFaculties as $guest)
                                                    <option value="{{ $guest->id }}">{{ $guest->name }}</option>
                                                @endforeach

                                            </select>

                                        </div>



                                        <!-- Action Buttons Column -->
                                        <div class="col-md-3 d-flex align-items-center gap-1 mt-2">

                                            <button type="button" class="btn btn-danger btn-sm remove-subtopic" title="Remove Session">
                                                <i class="fas fa-times"></i>
                                            </button>

                                            <button type="button" class="btn btn-info btn-sm add-tea-from-row" title="Add Tea Break for this Session">
                                                <i class="fas fa-coffee"></i> Tea
                                            </button>

                                            <button type="button" class="btn btn-warning btn-sm add-lunch-from-row" title="Add Lunch Break for this Session">
                                                <i class="fas fa-utensils"></i> Lunch
                                            </button>

                                        </div>

                                    </div>

                                </div>



                                <button type="button" id="add-subtopic" class="btn btn-sm btn-secondary mt-2">+ Add
                                    Subtopic</button>



                                <div class="mt-4">

                                    <button type="submit" class="btn btn-success ">Save Programme</button>

                                </div>

                            </form>

                        </div>



                    </div>

                </div>

                <div class="card">

                    <div class="card-body">

                        <div class="row mb-3">

                            <div class="col-md-3">

                                <label for="programme-filter" class="form-label">Programme</label>

                                <select id="programme-filter" class="form-control">

                                    <option value="">All Programmes</option>

                                    @foreach ($programmes as $programme)
                                        <option value="{{ $programme->id }}">{{ $programme->title }}</option>
                                    @endforeach

                                </select>

                            </div>



                            <div class="col-md-3">

                                <label for="date-filter" class="form-label">Date</label>

                                <select id="date-filter" class="form-control" disabled>

                                    <option value="">Select a programme first</option>

                                </select>

                            </div>

                            <div class="col-md-3">

                                <label for="title-filter" class="form-label">Title</label>

                                <select id="title-filter" class="form-control">

                                    <option value="">select a programme first</option>

                                </select>

                            </div>

                        </div>

                    </div>



                </div>

                <div class="card">

                    <div class="card-body">

                        <div class="table-container table-responsive">

                            <table id="subtopic-table" class="table table-bordered table-hover">

                                <thead>

                                    <tr>

                                        <th>ID</th>
                                        <th>Session</th>
                                        <th>Title</th>

                                        <th>Date</th>

                                        <th>Start Time</th>

                                        <th>End Time</th>

                                        <th>Faculty</th>

                                        <th>Programme</th>

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
        let subtopicIndex = 1;



        const facultyOptions = `@foreach ($facultyUsers as $faculty)

        <option value="{{ $faculty->id }}">{{ $faculty->name }}</option>

    @endforeach`;



        const guestOptions = `@foreach ($guestFaculties as $guest)

        <option value="{{ $guest->id }}">{{ $guest->name }}</option>

    @endforeach`;



        // Function to get all session names from subtopics
        function getSessionNames() {
            const sessionNames = [];
            $('.subtopic-row .session-name-input').each(function() {
                const name = $(this).val().trim();
                if (name && !sessionNames.includes(name)) {
                    sessionNames.push(name);
                }
            });
            return sessionNames;
        }

        // Function to generate session name options HTML
        function getSessionNameOptions(selectedValue = '') {
            const sessionNames = getSessionNames();
            let optionsHtml = '<option value="">Select Session</option>';
            
            if (sessionNames.length === 0) {
                optionsHtml += '<option value="" disabled>No sessions added yet</option>';
            } else {
                sessionNames.forEach(function(name) {
                    const selected = (name === selectedValue) ? 'selected' : '';
                    optionsHtml += `<option value="${name}" ${selected}>${name}</option>`;
                });
            }
            
            return optionsHtml;
        }

        // Function to update all break session name dropdowns
        function updateBreakSessionDropdowns() {
            const sessionNames = getSessionNames();
            
            // Update warning message visibility
            if (sessionNames.length > 0) {
                $('#no-session-warning').hide();
            } else {
                $('#no-session-warning').show();
            }
            
            // Update all existing break session name selects
            $('.break-session-select').each(function() {
                const currentValue = $(this).val();
                $(this).html(getSessionNameOptions(currentValue));
            });
        }

        function createSubtopicRow(index) {

            const row = document.createElement('div');

            row.className = 'row subtopic-row mb-2';

            row.innerHTML = `

            <!-- SESSION NAME INPUT -->
            <div class="col-md-2">
                <input type="text" name="subtopics[${index}][session_name]" class="form-control session-name-input" placeholder="Session Name (e.g. I, II)">
            </div>

            <div class="col-md-2">

                <input type="text" name="subtopics[${index}][title]" class="form-control" placeholder="Topic Title" required>

            </div>

            <div class="col-md-2">

                <input type="date" name="subtopics[${index}][date]" class="form-control subtopic-date" required>

            </div>

            <div class="col-md-2">

                <input type="time" name="subtopics[${index}][start_time]" class="form-control" required>

            </div>

            <div class="col-md-2">

                <input type="time" name="subtopics[${index}][end_time]" class="form-control" required>

            </div>

            <div class="col-md-2">

                <select name="subtopics[${index}][type]" class="form-control type-selector" required>

                    <option value="">Select Type</option>

                    <option value="faculty">Faculty</option>

                    <option value="guest">Guest Faculty</option>

                    <option value="Both">Faculty and Guest</option>

                </select>

            </div>

            <div class="col-md-3 faculty-dropdown" style="display: none;">

                <label>Select Faculty</label>

                <select name="subtopics[${index}][faculty_id][]" class="form-control custom-multiselect" multiple size="5">

                    ${facultyOptions}

                </select>

            </div>

            <div class="col-md-3 guest-dropdown" style="display: none;">

                <label>Select Guest Faculty</label>

                <select name="subtopics[${index}][guest_faculty_id][]" class="form-control custom-multiselect" multiple size="5">

                    ${guestOptions}

                </select>

            </div>

            <!-- Action Buttons Column -->
            <div class="col-md-3 d-flex align-items-center gap-1 mt-2">

                <button type="button" class="btn btn-danger btn-sm remove-subtopic" title="Remove Session">
                    <i class="fas fa-times"></i>
                </button>

                <button type="button" class="btn btn-info btn-sm add-tea-from-row" title="Add Tea Break for this Session">
                    <i class="fas fa-coffee"></i> Tea
                </button>

                <button type="button" class="btn btn-warning btn-sm add-lunch-from-row" title="Add Lunch Break for this Session">
                    <i class="fas fa-utensils"></i> Lunch
                </button>

            </div>

        `;

            return row;

        }



        // Handle type selector change to show/hide appropriate dropdowns

        $(document).on('change', '.type-selector', function() {

            const type = $(this).val();

            const container = $(this).closest('.subtopic-row');



            // Hide both dropdowns initially

            container.find('.faculty-dropdown').hide();

            container.find('.guest-dropdown').hide();



            // Show appropriate dropdowns based on selection

            if (type === 'faculty') {

                container.find('.faculty-dropdown').show();

            } else if (type === 'guest') {

                container.find('.guest-dropdown').show();

            } else if (type === 'Both') {

                container.find('.faculty-dropdown').show();

                container.find('.guest-dropdown').show();

            }

        });



        document.getElementById('add-subtopic').addEventListener('click', function() {

            const wrapper = document.getElementById('subtopics-wrapper');

            const newRow = createSubtopicRow(subtopicIndex);

            wrapper.appendChild(newRow);



            // Initialize date restrictions for the new row

            const fromDate = document.getElementById('from_date').value;

            const toDate = document.getElementById('to_date').value;

            newRow.querySelector('.subtopic-date').min = fromDate;

            newRow.querySelector('.subtopic-date').max = toDate;



            subtopicIndex++;

        });

        // Listen for session name input changes to update break dropdowns
        $(document).on('input', '.session-name-input', function() {
            updateBreakSessionDropdowns();
        });

        // Delegate remove event for subtopics
        document.addEventListener('click', function(e) {

            if (e.target.classList.contains('remove-subtopic')) {

                const row = e.target.closest('.subtopic-row');

                if (row) {
                    row.remove();
                    // Update break dropdowns after removing a subtopic
                    setTimeout(updateBreakSessionDropdowns, 100);
                }
            }

        });



        // Initialize DataTable

        $(document).ready(function() {

            $.ajaxSetup({

                headers: {

                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                }

            });



            const table = $('#subtopic-table').DataTable({

                processing: true,

                serverSide: true,

                ajax: {

                    url: '{{ route('programmeManagement.getData.subtopic') }}',

                    type: 'GET',

                    data: function(d) {

                        d.title = $('#title-filter').val();

                        d.programme = $('#programme-filter').val();

                        d.date = $('#date-filter').val();

                    }

                },

                columns: [

                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'session_name',
                        name: 'session_name'
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },

                    {
                        data: 'date',
                        name: 'date'
                    },

                    {
                        data: 'start_time',
                        name: 'start_time'
                    },

                    {
                        data: 'end_time',
                        name: 'end_time'
                    },

                    {
                        data: 'faculty_name',
                        name: 'faculty_name'
                    },

                    {
                        data: 'programme_title',
                        name: 'programme_title'
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
                                    data-session_name="${row.session_name ?? ''}"
                                    data-title="${row.title ?? ''}"

                                    data-date="${row.date ?? ''}"

                                    data-start_time="${row.start_time ?? ''}"

                                    data-end_time="${row.end_time ?? ''}"

                                    data-faculty_id="${row.faculty_id ?? ''}"

                                    data-programme_id="${row.programme_id ?? ''}">

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



            // Handle delete button click

            $('#subtopic-table').on('click', '.delete-btn', function() {

                var id = $(this).data('id');



                if (confirm('Are you sure you want to delete this session?')) {

                    $.ajax({

                        url: '{{ route('programme.session.delete', '') }}/' + id,

                        type: 'POST',

                        data: {

                            _method: 'post',

                            _token: '{{ csrf_token() }}'

                        },

                        success: function(response) {

                            if (response.success) {

                                alert(response.message || 'Deleted successfully!');

                                $('#subtopic-table').DataTable().ajax.reload(null, false);

                            } else {

                                alert(response.message || 'Error deleting session');

                            }

                        },

                        error: function(xhr) {

                            alert('Error deleting session: ' + xhr.responseJSON.message);

                        }

                    });

                }

            });



            // Filter event handlers

            $('#title-filter, #programme-filter, #date-filter').on('change', function() {

                table.ajax.reload();

            });



            // Programme filter change - update date and title filters

            $('#programme-filter').on('change', function() {

                let programmeId = $(this).val();

                let $dateFilter = $('#date-filter');

                let $titleFilter = $('#title-filter');



                $dateFilter.html('<option value="">Loading...</option>').prop('disabled', true);

                $titleFilter.html('<option value="">Loading...</option>').prop('disabled', true);



                if (programmeId) {

                    $.ajax({

                        url: '{{ route('get.programme.subtopics') }}',

                        type: 'GET',

                        data: {
                            programme_id: programmeId
                        },

                        success: function(response) {

                            // Update title filter

                            if (response.success && response.subtopics.length > 0) {

                                let titleOptions = '<option value="">All Subtopics</option>';

                                response.subtopics.forEach(function(subtopic) {

                                    titleOptions +=
                                        `<option value="${subtopic.id}">${subtopic.title}</option>`;

                                });

                                $titleFilter.html(titleOptions).prop('disabled', false);

                            } else {

                                $titleFilter.html(
                                    '<option value="">No Subtopics Found</option>');

                            }



                            // Update date filter

                            if (response.dates && response.dates.length > 0) {

                                let dateOptions = '<option value="">All Dates</option>';

                                response.dates.forEach(function(date) {

                                    dateOptions +=
                                        `<option value="${date}">${date}</option>`;

                                });

                                $dateFilter.html(dateOptions).prop('disabled', false);

                            } else {

                                $dateFilter.html('<option value="">No Dates Found</option>');

                            }

                        },

                        error: function() {

                            $titleFilter.html(
                                '<option value="">Error fetching subtopics</option>');

                            $dateFilter.html('<option value="">Error fetching dates</option>');

                        }

                    });

                } else {

                    $titleFilter.html('<option value="">Select a programme first</option>').prop('disabled',
                        true);

                    $dateFilter.html('<option value="">Select a programme first</option>').prop('disabled',
                        true);

                }

            });



            // Handle edit button click

            $('#subtopic-table').on('click', '.edit-btn', function() {

                const id = $(this).data('id');
                const session_name = $(this).data('session_name');
                const title = $(this).data('title');

                const date = $(this).data('date');

                const start_time = $(this).data('start_time');

                const end_time = $(this).data('end_time');

                const faculty_id = $(this).data('faculty_id');

                const programme_id = $(this).data('programme_id');



                $('#form-title').text('✏️ Edit Subtopic');

                $('#programme-form').attr('action', '{{ route('programme.sessions.update', '') }}/' + id);

                $('#subtopics-wrapper').empty();



                // Add a single row with the subtopic data

                const rowHtml = `

                <div class="row subtopic-row mb-2">

                    <div class="col-md-2">
                        <input type="text" name="subtopics[0][session_name]" class="form-control session-name-input" 
                            value="${session_name}" placeholder="Session Name">
                    </div>

                    <div class="col-md-2">

                        <input type="text" name="subtopics[0][title]" class="form-control" 

                            value="${title}" placeholder="Topic Title" required>

                    </div>

                    <div class="col-md-2">

                        <input type="date" name="subtopics[0][date]" class="form-control subtopic-date" 

                            value="${date}" required>

                    </div>

                    <div class="col-md-2">

                        <input type="time" name="subtopics[0][start_time]" class="form-control" 

                            value="${start_time}" required>

                    </div>

                    <div class="col-md-2">

                        <input type="time" name="subtopics[0][end_time]" class="form-control" 

                            value="${end_time}" required>

                    </div>

                    <div class="col-md-2">

                        <select name="subtopics[0][faculty_id]" class="form-control" required>

                            <option value="">Select Faculty</option>

                            @foreach ($faculties as $faculty)

                                <option value="{{ $faculty->id }}" ${faculty_id == {{ $faculty->id }} ? 'selected' : ''}>

                                    {{ $faculty->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <!-- Action Buttons Column -->
                    <div class="col-md-3 d-flex align-items-center gap-1 mt-2">

                        <button type="button" class="btn btn-danger btn-sm remove-subtopic" title="Remove Session">
                            <i class="fas fa-times"></i>
                        </button>

                        <button type="button" class="btn btn-info btn-sm add-tea-from-row" title="Add Tea Break for this Session">
                            <i class="fas fa-coffee"></i> Tea
                        </button>

                        <button type="button" class="btn btn-warning btn-sm add-lunch-from-row" title="Add Lunch Break for this Session">
                            <i class="fas fa-utensils"></i> Lunch
                        </button>

                    </div>

                    <input type="hidden" name="subtopic_id" value="${id}">

                </div>

            `;



                $('#subtopics-wrapper').html(rowHtml);

                $('#programme_id').val(programme_id).trigger('change');

                // Update break dropdowns after editing
                setTimeout(updateBreakSessionDropdowns, 100);

                // Scroll to form

                $('html, body').animate({

                    scrollTop: $(".form-container").offset().top

                }, 500);

            });

        });



        // Break management functionality

        $(document).ready(function() {

            let breakIndex = 0;



            // Handle tea break checkbox

            $('#include-tea-break').change(function() {

                if ($(this).is(':checked')) {

                    $('#tea-break-fields').show();

                    $('[name="tea_break_time"]').prop('required', true);

                    $('[name="tea_break_duration"]').prop('required', true);

                } else {

                    $('#tea-break-fields').hide();

                    $('[name="tea_break_time"]').prop('required', false);

                    $('[name="tea_break_duration"]').prop('required', false);

                }

            });



            // Handle lunch break checkbox

            $('#include-lunch-break').change(function() {

                if ($(this).is(':checked')) {

                    $('#lunch-break-fields').show();

                    $('[name="lunch_break_time"]').prop('required', true);

                    $('[name="lunch_break_duration"]').prop('required', true);

                } else {

                    $('#lunch-break-fields').hide();

                    $('[name="lunch_break_time"]').prop('required', false);

                    $('[name="lunch_break_duration"]').prop('required', false);

                }

            });



            // Function to add a break - updated to accept preSelectedDate
            function addBreak(type, preSelectedSessionName = '', preSelectedDate = '') {

                const container = $('#breaks-container');

                const minDate = $('#from_date').val();

                const maxDate = $('#to_date').val();

                const sessionOptions = getSessionNameOptions(preSelectedSessionName);

                const newBreak = $(`

                <div class="row break-row mb-3 align-items-center ${type}-break" style="margin-top:10px; background-color: ${type === 'tea' ? '#e3f2fd' : '#fff8e1'}; padding: 15px; border-radius: 8px; border-left: 4px solid ${type === 'tea' ? '#0288d1' : '#f9a825'};">
                    <div class="col-md-3">

                        <label class="form-label fw-bold">Session Name <span class="text-danger">*</span></label>
                        <select name="breaks[${breakIndex}][session_name]" class="form-control break-session-select" required>
                            ${sessionOptions}
                        </select>

                    </div>


                    <div class="col-md-2">

                        <label class="form-label fw-bold">Date <span class="text-danger">*</span></label>
                        <input type="date" name="breaks[${breakIndex}][date]" class="form-control break-date" value="${preSelectedDate}" required>

                    </div>

                    <div class="col-md-2">

                        <label class="form-label fw-bold">Time <span class="text-danger">*</span></label>
                        <input type="time" name="breaks[${breakIndex}][time]" class="form-control" required>

                    </div>

                    <div class="col-md-2">

                        <label class="form-label fw-bold">Duration (min)</label>
                        <input type="number" name="breaks[${breakIndex}][duration]" 

                               class="form-control" min="${type === 'tea' ? 5 : 30}" 

                               max="${type === 'tea' ? 60 : 120}" 

                               value="${type === 'tea' ? 15 : 60}" required>

                    </div>

                    <div class="col-md-3 d-flex align-items-end">

                        <span class="badge ${type === 'tea' ? 'bg-info' : 'bg-warning'} text-dark me-2" style="font-size: 14px; padding: 8px 15px; border-radius: 5px;">

                            <i class="fas ${type === 'tea' ? 'fa-coffee' : 'fa-utensils'}"></i> 
                            ${type === 'tea' ? 'Tea Break' : 'Lunch Break'}

                        </span>

                        <input type="hidden" name="breaks[${breakIndex}][type]" value="${type}">

                        <button type="button" class="btn btn-sm btn-danger remove-break" title="Remove Break">

                            <i class="fas fa-trash-alt"></i>

                        </button>

                    </div>

                </div>

            `);



                // Set date restrictions

                newBreak.find('.break-date').attr('min', minDate).attr('max', maxDate);

                container.append(newBreak);

                // Scroll to the newly added break
                $('html, body').animate({
                    scrollTop: newBreak.offset().top - 100
                }, 300);

                breakIndex++;

            }



            // Add break buttons (main buttons)
            $('#add-tea-break').click(() => addBreak('tea'));
            $('#add-lunch-break').click(() => addBreak('lunch'));



            // Add break from subtopic row buttons
            $(document).on('click', '.add-tea-from-row', function() {
                const row = $(this).closest('.subtopic-row');
                const sessionName = row.find('.session-name-input').val().trim();
                const sessionDate = row.find('.subtopic-date').val(); // Get the date from the subtopic row
                
                if (!sessionName) {
                    alert('Please enter a session name first before adding a break.');
                    row.find('.session-name-input').focus();
                    return;
                }
                
                if (!sessionDate) {
                    alert('Please select a date for this session before adding a break.');
                    row.find('.subtopic-date').focus();
                    return;
                }
                
                // Pass both sessionName and sessionDate to the addBreak function
                addBreak('tea', sessionName, sessionDate);
            });

            $(document).on('click', '.add-lunch-from-row', function() {
                const row = $(this).closest('.subtopic-row');
                const sessionName = row.find('.session-name-input').val().trim();
                const sessionDate = row.find('.subtopic-date').val(); // Get the date from the subtopic row
                
                if (!sessionName) {
                    alert('Please enter a session name first before adding a break.');
                    row.find('.session-name-input').focus();
                    return;
                }
                
                if (!sessionDate) {
                    alert('Please select a date for this session before adding a break.');
                    row.find('.subtopic-date').focus();
                    return;
                }
                
                // Pass both sessionName and sessionDate to the addBreak function
                addBreak('lunch', sessionName, sessionDate);
            });



            // Remove break

            $(document).on('click', '.remove-break', function() {

                $(this).closest('.break-row').fadeOut(300, function() {
                    $(this).remove();
                });

            });

            // Initialize warning state
            updateBreakSessionDropdowns();

        });
    </script>



    <script>
        document.getElementById('programme_code').addEventListener('keyup', function() {

            let code = this.value.trim();

            if (code !== '') {

                fetch(`/get-programme-by-code/${code}`)

                    .then(response => response.json())

                    .then(data => {

                        if (data && data.id) {

                            console.log(data);
                            $('#programme_id')
                                .val(data.id)
                                .trigger('change');


                        } else {

                            alert('No programme found with this code.');

                        }

                    })

                    .catch(error => {

                        console.error('Error fetching programme:', error);

                    });

            }

        });
    </script>

    <script>
        $(document).on('change', '#programme_id', function() {

            const selected = $(this).find(':selected');

            const fromDate = selected.data('from') || '';
            const toDate = selected.data('to') || '';

            $('#from_date').val(fromDate);
            $('#to_date').val(toDate);

            $('.subtopic-date').attr({
                min: fromDate,
                max: toDate
            });

            // Also update break date fields
            $('.break-date').attr({
                min: fromDate,
                max: toDate
            });

        });
    </script>
@endsection