@extends('admin.layouts.master')
@section('main-section')
   <link rel="stylesheet" href="{{ asset('assets/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/summernote/summernote-lite.css') }}">
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Add Programme</h3>
                <ul class="breadcrumbs mb-3">
                    <li class="nav-home">
                        <a href="#"><i class="icon-home"></i></a>
                    </li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item"><a href="#">Programme</a></li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item"><a href="#">Add Programme</a></li>
                </ul>
            </div>

            <div class="row">
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
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                @endif

                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form id="agency-group-form" action="{{ route('programmeManagement.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4 form-group">
                                        <label for="group" class="form-label">Group <span
                                                style="color: red;">*</span></label>
                                        <select name="group" id="group" class="form-control" required>
                                            <option value="">Select Group</option>
                                            @foreach ($groups as $group)
                                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="agency_type_id" class="form-label">
                                            Agency Type <span style="color: red;">*</span>
                                        </label>
                                        <select name="agency_type_id[]" id="agency_type_id"
                                            class="form-control js-example-basic-multiple" multiple="multiple" required>
                                            @foreach ($agencies as $a)
                                                <option value="{{ $a->id }}"
                                                    {{ collect(old('agency_type_id'))->contains($a->id) ? 'selected' : '' }}>
                                                    {{ $a->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>


                                    <div class="col-md-4 form-group">
                                        <label for="program_type" class="form-label">Program Type <span
                                                class="text-danger">*</span></label>
                                        <select name="program_type" id="program_type" class="form-control" required>
                                            <option value="">Select Program Type</option>
                                            <option value="Regular"
                                                {{ old('program_type') == 'Regular' ? 'selected' : '' }}>1. Regular</option>
                                            <option value="Seminar/Webinar/Conference"
                                                {{ old('program_type') == 'Seminar/Webinar/Conference' ? 'selected' : '' }}>
                                                2. Seminar/Webinar/Conference</option>
                                            <option value="Workshop"
                                                {{ old('program_type') == 'Workshop' ? 'selected' : '' }}>3. Workshop
                                            </option>
                                            <option value="International"
                                                {{ old('program_type') == 'International' ? 'selected' : '' }}>4.
                                                International</option>
                                            <option value="Exposure Visit"
                                                {{ old('program_type') == 'Exposure Visit' ? 'selected' : '' }}>5. Exposure
                                                Visit</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label for="title" class="form-label">Programme <Title></Title><span
                                                style="color: red;">*</span></label>
                                        <input type="text" name="title" id="title" class="form-control"
                                            placeholder="Enter programme title" required>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="hindi_title" class="form-label">शीर्षक <span
                                                style="color: red;">*</span></label>
                                        <input type="text" name="hindi_title" id="hindi_title" class="form-control"
                                            placeholder="कार्यक्रम का शीर्षक दर्ज करें" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 form-group">
                                        <label for="location" class="form-label">Location <span
                                                style="color: red;">*</span></label>
                                        <select name="location" id="location" class="form-control" required>
                                            <option value="">Select Location</option>
                                            <option value="In-House">In-House</option>
                                            <option value="On-Location">On-Location</option>
                                            <option value="On-line">On-line</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="venue" class="form-label">Venue <span
                                                style="color: red;">*</span></label>
                                        <input type="text" name="venue" id="venue" class="form-control"
                                            placeholder="Venue will auto-fill if In-House" required>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="sponsor_id" class="form-label">Sponsor <span
                                                style="color: red;">*</span></label>
                                        <select name="sponsor_id" id="sponsor_id" class="form-control" required>
                                            <option value="">Select Sponsor</option>
                                            @foreach ($sponsors as $sponsor)
                                                <option value="{{ $sponsor->id }}">{{ $sponsor->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 form-group" id="department_container" style="display:none;">
                                        <label for="department_id" class="form-label">Department Name</label>
                                        <select name="department_id" id="department_id" class="form-control">
                                            <option value="">Select Department</option>
                                            @foreach ($departments as $department)
                                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6 form-group" id="department_input_container"
                                        style="display:none;">
                                        <label for="department_input" class="form-label">Enter Department Name</label>
                                        <input type="text" name="department_input" id="department_input"
                                            class="form-control" placeholder="Enter Department Name">
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-body">
                                        <div id="paid_agency_table_container" style="display: none;" class="mt-4">
                                            <h5>Selected Agency Types</h5>
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Agency Type</th>
                                                        <th>Fee</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="paid_agency_table_body">
                                                    <!-- Dynamically filled rows -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" id="customised_fee_section" style="display: none;">
                                    <div class="col-md-6 form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="1"
                                                id="participant_fee_check">
                                            <label class="form-check-label" for="participant_fee_check">Parti. Wise
                                                Fee</label>
                                        </div>
                                        <input type="text" name="participant_fee" id="participant_fee"
                                            class="form-control mt-2" placeholder="Enter Participant Wise Fee"
                                            style="display:none;" />
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="1"
                                                id="program_fee_check">
                                            <label class="form-check-label" for="program_fee_check">Prog. Wise Fee</label>
                                        </div>
                                        <input type="text" name="program_fee" id="program_fee"
                                            class="form-control mt-2" placeholder="Enter Programme Wise Fee"
                                            style="display:none;" />
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3 form-group">
                                        <label for="from_date" class="form-label">From <span
                                                style="color: red;">*</span></label>
                                        <input type="date" name="from_date" id="from_date" class="form-control"
                                            required>
                                    </div>

                                    <div class="col-md-3 form-group">
                                        <label for="to_date" class="form-label">To <span
                                                style="color: red;">*</span></label>
                                        <input type="date" name="to_date" id="to_date" class="form-control"
                                            required>
                                    </div>

                                    <div class="col-md-3 form-group">
                                        <label for="duration" class="form-label">Duration</label>
                                        <input type="text" name="duration" id="duration" class="form-control"
                                            placeholder="e.g. 5 Days">
                                    </div>

                                    <div class="col-md-3 form-group">
                                        <label for="strength" class="form-label">Strength</label>
                                        <input type="text" name="strength" id="strength" class="form-control"
                                            placeholder="Enter Strength">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 form-group">
                                        <label for="fee_structure" class="form-label">Fee Structure <span
                                                style="color: red;">*</span></label>
                                        <input type="number" name="fee_structure" id="fee_structure"
                                            class="form-control" placeholder="Enter fee amount" required min="0">
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-12">
                                        <h4 class="form-title">Programme Announcement</h4>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 form-group">
                                        <label for="last_nomination_date" class="form-label">Last Nomination Date. <span
                                                style="color: red;">*</span></label>
                                        <input type="date" name="last_nomination_date" id="last_nomination_date"
                                            class="form-control" required>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="class_room" class="form-label">Class Room</label>
                                        <select name="class_room" id="class_room" class="form-control">
                                            <option value="">Select Class Room...</option>
                                            @foreach ($classes as $class)
                                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="boarding_plan" class="form-label">Boarding Plan</label>
                                        <input type="text" name="boarding_plan" id="boarding_plan"
                                            class="form-control" placeholder="Enter Boarding Plan">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 form-group">
                                        <label for="prog_dir_1" class="form-label">Prog Dir. 1<span
                                                style="color: red;">*</span></label>
                                        <select name="prog_dir_1" id="prog_dir_1" class="form-control" required>
                                            <option value="">Select...</option>
                                            @foreach ($faculties as $faculty)
                                                <option value="{{ $faculty->id }}">{{ $faculty->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="prog_dir_2" class="form-label">Prog Dir. 2</label>
                                        <select name="prog_dir_2" id="prog_dir_2" class="form-control">
                                            <option value="">Select...</option>
                                            @foreach ($faculties as $faculty)
                                                <option value="{{ $faculty->id }}">{{ $faculty->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="guest_faculty" class="form-label">Guest Faculty</label>
                                        <select name="guest_faculty" id="guest_faculty" class="form-control">
                                            <option value="">Select...</option>
                                            @foreach ($guest_faculty as $guest_facult)
                                                <option value="{{ $guest_facult->id }}">{{ $guest_facult->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        <label for="remarks" class="form-label">Remarks</label>
                                        <textarea name="remarks" id="remarks" class="form-control" rows="3" placeholder="Enter Remarks"></textarea>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary">Save Programme</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div> <!-- row -->
        </div> <!-- page-inner -->
    </div> <!-- container -->
@endsection

@section('script')
    <script src="{{ url('/admin') }}/assets/js/plugin/datatables/datatables.min.js"></script>
    <script src="{{ url('/admin') }}/assets/js/kaiadmin.min.js"></script>
    <script src="{{ url('/admin') }}/assets/js/setting-demo2.js"></script>
    <script src="{{ asset('assets/summernote/summernote-lite.js') }}"></script>




    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const venue = document.getElementById('venue');
            const location = document.getElementById('location');

            location.addEventListener('change', function() {
                if (this.value === 'In-House') {
                    venue.value = 'BIRD,Lucknow';
                    venue.setAttribute('readonly', true);
                } else if (this.value === 'On-line') {
                    venue.value = 'BIRD,Lucknow digital';
                    venue.removeAttribute('readonly');
                } else {
                    venue.value = '';
                    venue.removeAttribute('readonly');
                }
            });

            $('#sponsor_id').change(function() {
                const selectedText = $('#sponsor_id option:selected').text().trim();
                const selectedAgencies = $('#agency_type_id option:selected').map(function() {
                    return $(this).text();
                }).get();

                $('#department_input_container, #department_container, #customised_fee_section').hide();
                $('#participant_fee, #program_fee').hide().val('');
                $('#participant_fee_check, #program_fee_check').prop('checked', false);
                $('#paid_agency_table_container').hide();
                $('#paid_agency_table_body').empty();

                if (selectedText === 'NABARD') {
                    $('#department_container').show();
                } else if (selectedText === 'Customised') {
                    $('#customised_fee_section').show();
                    $('#department_input_container').show();
                } else if (selectedText === 'Paid') {
                    $('#department_input_container').show();

                    let tableBody = '';
                    $('#agency_type_id option:selected').each(function() {
                        const agencyName = $(this).text();
                        const agencyId = $(this).val();

                        tableBody += `
            <tr>
              <td>${agencyName}</td>
              <td><input type="number" name="agency_fees[${agencyId}]" class="form-control" placeholder="Enter Fee" required></td>
            </tr>`;
                    });

                    $('#paid_agency_table_body').html(tableBody);
                    $('#paid_agency_table_container').show();
                } else {
                    $('#department_input_container').show();
                }
            });

            $('#participant_fee_check').change(function() {
                if (this.checked) {
                    $('#program_fee_check').prop('checked', false);
                    $('#program_fee').hide().val('');
                    $('#participant_fee').show();
                } else {
                    $('#participant_fee').hide().val('');
                }
            });

            $('#program_fee_check').change(function() {
                if (this.checked) {
                    $('#participant_fee_check').prop('checked', false);
                    $('#participant_fee').hide().val('');
                    $('#program_fee').show();
                } else {
                    $('#program_fee').hide().val('');
                }
            });

            $('#from_date, #to_date').change(function() {
                const fromDate = new Date($('#from_date').val());
                const toDate = new Date($('#to_date').val());
                if ($('#from_date').val() && $('#to_date').val() && fromDate <= toDate) {
                    const diffTime = Math.abs(toDate - fromDate);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                    $('#duration').val(diffDays + ' Days');
                }
            });

            document.getElementById('agency-group-form').addEventListener('submit', function(e) {
                const sponsorText = $('#sponsor_id option:selected').text().trim();
                let formIsValid = true;

                if (sponsorText === 'Paid') {
                    $('#paid_agency_table_body input[type="number"]').each(function() {
                        const value = $(this).val();
                        if (value === '' || isNaN(value) || parseFloat(value) <= 0) {
                            $(this).addClass('is-invalid');
                            formIsValid = false;
                        } else {
                            $(this).removeClass('is-invalid');
                        }
                    });

                    if (!formIsValid) {
                        e.preventDefault();
                        alert('Please enter valid fees for all selected agency types.');
                        return;
                    }
                }

                if (sponsorText === 'Customised') {
                    const participantChecked = $('#participant_fee_check').is(':checked');
                    const programChecked = $('#program_fee_check').is(':checked');
                    const participantFee = $('#participant_fee').val().trim();
                    const programFee = $('#program_fee').val().trim();

                    if (!participantChecked && !programChecked) {
                        e.preventDefault();
                        alert(
                            'Please select either "Parti. Wise Fee" or "Prog. Wise Fee" for Customised sponsor.');
                        return;
                    }

                    if (participantChecked && (participantFee === '' || isNaN(participantFee) || parseFloat(
                            participantFee) <= 0)) {
                        $('#participant_fee').addClass('is-invalid');
                        e.preventDefault();
                        alert('Please enter a valid Participant Wise Fee.');
                        return;
                    } else {
                        $('#participant_fee').removeClass('is-invalid');
                    }

                    if (programChecked && (programFee === '' || isNaN(programFee) || parseFloat(
                            programFee) <= 0)) {
                        $('#program_fee').addClass('is-invalid');
                        e.preventDefault();
                        alert('Please enter a valid Programme Wise Fee.');
                        return;
                    } else {
                        $('#program_fee').removeClass('is-invalid');
                    }
                }
            });
        });
    </script>

   <script src="{{ asset('assets/select2/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.js-example-basic-multiple').select2();
        });
    </script>
@endsection
