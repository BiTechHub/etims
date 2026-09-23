@extends('admin.layouts.master')
@section('main-section')
<link href="{{ asset('assets/select2/css/select2.min.css') }}" rel="stylesheet" />
<link rel="stylesheet" href="{{ asset('assets/summernote/summernote-lite.css') }}"><div class="container">
  <div class="page-inner">
    <div class="page-header">
      <h3 class="fw-bold mb-3">Edit Programme</h3>
      <ul class="breadcrumbs mb-3">
        <li class="nav-home">
          <a href="#"><i class="icon-home"></i></a>
        </li>
        <li class="separator"><i class="icon-arrow-right"></i></li>
        <li class="nav-item"><a href="#">Manage Programmes</a></li>
        <li class="separator"><i class="icon-arrow-right"></i></li>
        <li class="nav-item"><a href="#">Edit Programme</a></li>
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
            <form id="agency-group-form" action="{{ route('programmeManagement.update', $programme->id) }}" method="POST" enctype="multipart/form-data">
              @csrf
              @method('post')
              <div class="row">
                <div class="col-md-4 form-group">
                  <label for="group" class="form-label">Group <span style="color: red;">*</span></label>
                  <select name="group_id" id="group" class="form-control" required>
                    <option value="">Select Group</option>
                    @foreach($groups as $group)
                    <option value="{{ $group->id }}" {{ $programme->group_id == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="col-md-4 form-group">
                  <label for="agency_type_id" class="form-label">
                    Agency Type <span style="color: red;">*</span>
                  </label>
                  <select name="agency_type_id[]" id="agency_type_id" class="form-control js-example-basic-multiple" multiple="multiple" required>
                    @foreach($AgencyTypes as $agency)
                      <option value="{{ $agency->id }}" 
                        {{ in_array($agency->id, $programme->agency_type_id ?? []) ? 'selected' : '' }}>
                        {{ $agency->name }}
                      </option>
                    @endforeach
                  </select>
                </div>

                <div class="col-md-4 form-group">
                  <label for="program_type" class="form-label">Program Type <span class="text-danger">*</span></label>
                  <select name="program_type" id="program_type" class="form-control" required>
                    <option value="">Select Program Type</option>
                    <option value="Regular" {{ $programme->program_type == 'Regular' ? 'selected' : '' }}>1. Regular</option>
                    <option value="Seminar/Webinar/Conference" {{ $programme->program_type == 'Seminar/Webinar/Conference' ? 'selected' : '' }}>2. Seminar/Webinar/Conference</option>
                    <option value="Workshop" {{ $programme->program_type == 'Workshop' ? 'selected' : '' }}>3. Workshop</option>
                    <option value="International" {{ $programme->program_type == 'International' ? 'selected' : '' }}>4. International</option>
                    <option value="Exposure Visit" {{ $programme->program_type == 'Exposure Visit' ? 'selected' : '' }}>5. Exposure Visit</option>
                  </select>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6 form-group">
                  <label for="title" class="form-label">Programme Title<span style="color: red;">*</span></label>
                  <input type="text" name="title" id="title" class="form-control" placeholder="Enter programme title" value="{{ $programme->title }}" required>
                </div>
                <div class="col-md-6 form-group">
                  <label for="hindi_title" class="form-label">शीर्षक <span style="color: red;">*</span></label>
                  <input type="text" name="hindi_title" id="hindi_title" class="form-control" placeholder="कार्यक्रम का शीर्षक दर्ज करें" value="{{ $programme->hindi_title }}" required>
                </div>
              </div>

              <div class="row">
                <div class="col-md-4 form-group">
                  <label for="location" class="form-label">Location <span style="color: red;">*</span></label>
                  <select name="location" id="location" class="form-control" required>
                    <option value="">Select Location</option>
                    <option value="In-House" {{ $programme->location == 'In-House' ? 'selected' : '' }}>In-House</option>
                    <option value="On-Location" {{ $programme->location == 'On-Location' ? 'selected' : '' }}>On-Location</option>
                    <option value="On-line" {{ $programme->location == 'On-line' ? 'selected' : '' }}>On-line</option>
                  </select>
                </div>

                <div class="col-md-4 form-group">
                  <label for="venue" class="form-label">Venue <span style="color: red;">*</span></label>
                  <input type="text" name="venue" id="venue" class="form-control" value="{{ $programme->venue }}" required>
                </div>

                <div class="col-md-4 form-group">
                  <label for="sponsor_id" class="form-label">Sponsor <span style="color: red;">*</span></label>
                  <select name="sponsor_id" id="sponsor_id" class="form-control" required>
                    <option value="">Select Sponsor</option>
                    @foreach($sponsors as $sponsor)
                    <option value="{{ $sponsor->id }}" {{ $programme->sponsor_id == $sponsor->id ? 'selected' : '' }}>{{ $sponsor->name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6 form-group" id="department_container" style="{{ $programme->sponsor->name == 'NABARD' ? '' : 'display:none;' }}">
                  <label for="department_id" class="form-label">Department Name</label>
                  <select name="department_id" id="department_id" class="form-control">
                    <option value="">Select Department</option>
                    @foreach($departments as $department)
                    <option value="{{ $department->id }}" {{ $programme->department_id == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="col-md-6 form-group" id="department_input_container" style="{{ $programme->sponsor->name == 'NABARD' ? 'display:none;' : '' }}">
                  <label for="department_input" class="form-label">Enter Department Name</label>
                  <input type="text" name="department_input" id="department_input" class="form-control" placeholder="Enter Department Name" value="{{ $programme->department_input }}">
                </div>
              </div>

                
              @if($programme->sponsor->name == 'Paid')
              <div class="card">
                <div class="card-body">
                  <div id="paid_agency_table_container" class="mt-4">
                    <h5>Selected Agency Types with Fees</h5>
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th>Agency Type</th>
                          <th>Fee</th>
                        </tr>
                      </thead>
                      <tbody id="paid_agency_table_body">
                        @foreach($programme->agencyFees as $agencyFee)
                        <tr>
                          <td>{{ $agencyFee->agencyType->name }}</td>
                          <td>
                            <input type="hidden" name="agency_fee_ids[]" value="{{ $agencyFee->id }}">
                            <input type="number" name="agency_fees[{{ $agencyFee->agency_type_id }}]" 
                                   class="form-control" placeholder="Enter Fee" 
                                   value="{{ $agencyFee->fee }}" required>
                          </td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              @endif

              @if($programme->sponsor->name == 'Customised')
              <div class="row" id="customised_fee_section">
                <div class="col-md-6 form-group">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="1" id="participant_fee_check" {{ $programme->participant_fee ? 'checked' : '' }}>
                    <label class="form-check-label" for="participant_fee_check">Parti. Wise Fee</label>
                  </div>
                  <input type="text" name="participant_fee" id="participant_fee" class="form-control mt-2" placeholder="Enter Participant Wise Fee" value="{{ $programme->participant_fee }}" style="{{ $programme->participant_fee ? '' : 'display:none;' }}" />
                </div>

                <div class="col-md-6 form-group">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="1" id="program_fee_check" {{ $programme->program_fee ? 'checked' : '' }}>
                    <label class="form-check-label" for="program_fee_check">Prog. Wise Fee</label>
                  </div>
                  <input type="text" name="program_fee" id="program_fee" class="form-control mt-2" placeholder="Enter Programme Wise Fee" value="{{ $programme->program_fee }}" style="{{ $programme->program_fee ? '' : 'display:none;' }}" />
                </div>
              </div>
              @endif

              <div class="row">
                <div class="col-md-3 form-group">
                  <label for="from_date" class="form-label">From <span style="color: red;">*</span></label>
                @php
  $fromDate = \Carbon\Carbon::parse($programme->from_date)->format('y-m-d');
@endphp

<input type="text" name="from_date" id="from_date" class="form-control" value="{{ $fromDate }}" required>
                </div>

                <div class="col-md-3 form-group">
                  <label for="to_date" class="form-label">To <span style="color: red;">*</span></label>
@php
  $toDate = \Carbon\Carbon::parse($programme->to_date)->format('Y-m-d');
@endphp
                  <input type="date" name="to_date" id="to_date" class="form-control" value="{{ $toDate }}" required>
                </div>

                <div class="col-md-3 form-group">
                  <label for="duration" class="form-label">Duration</label>
                  <input type="text" name="duration" id="duration" class="form-control" placeholder="e.g. 5 Days" value="{{ $programme->duration }}">
                </div>

                <div class="col-md-3 form-group">
                  <label for="strength" class="form-label">Strength</label>
                  <input type="text" name="strength" id="strength" class="form-control" placeholder="Enter Strength" value="{{ $programme->strength }}">
                </div>
              </div>

              <div class="row">
                <div class="col-md-4 form-group">
                  <label for="fee_structure" class="form-label">Fee Structure <span style="color: red;">*</span></label>
                  <input type="number" name="fee_structure" id="fee_structure" class="form-control" placeholder="Enter fee amount" value="{{ $programme->fee_structure }}" required min="0">
                </div>
              </div>

              <div class="row mt-4">
                <div class="col-12">
                  <h4 class="form-title">Programme Announcement</h4>
                </div>
              </div>

              <div class="row">
                <div class="col-md-4 form-group">
                  <label for="last_nomination_date" class="form-label">Last Nomination Date. <span style="color: red;">*</span></label>
                  <input type="date" name="last_nomination_date" id="last_nomination_date" class="form-control" value="{{ $programme->last_nomination_date }}" required>
                </div>

                <div class="col-md-4 form-group">
                  <label for="class_room" class="form-label">Class Room</label>
                  <select name="class_room" id="class_room" class="form-control">
                    <option value="">Select Class Room...</option>
                    @foreach($classes as $class)
                    <option value="{{ $class->id }}" {{ $programme->class_room == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="col-md-4 form-group">
                  <label for="boarding_plan" class="form-label">Boarding Plan</label>
                  <input type="text" name="boarding_plan" id="boarding_plan" class="form-control" placeholder="Enter Boarding Plan" value="{{ $programme->boarding_plan }}">
                </div>
              </div>

              <div class="row">
                <div class="col-md-4 form-group">
                  <label for="prog_dir_1" class="form-label">Prog Dir. 1<span style="color: red;">*</span></label>
                  <select name="prog_dir_1" id="prog_dir_1" class="form-control" required>
                    <option value="">Select...</option>
                    @foreach($faculties as $faculty)
                    <option value="{{ $faculty->id }}" {{ $programme->prog_dir_1 == $faculty->id ? 'selected' : '' }}>{{ $faculty->name }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="col-md-4 form-group">
                  <label for="prog_dir_2" class="form-label">Prog Dir. 2</label>
                  <select name="prog_dir_2" id="prog_dir_2" class="form-control">
                    <option value="">Select...</option>
                    @foreach($faculties as $faculty)
                    <option value="{{ $faculty->id }}" {{ $programme->prog_dir_2 == $faculty->id ? 'selected' : '' }}>{{ $faculty->name }}</option>
                    @endforeach
                  </select>
                </div>

<div class="col-md-4 form-group">
    <label for="status">Status</label>
    <select name="status" id="status" class="form-control" required>
        <option value="">Select Status</option>
        <option value="NotAnnounce" {{ $programme->status == 'NotAnnounce' ? 'selected' : '' }}>Not Announced</option>
        <option value="Announced" {{ $programme->status == 'Announced' ? 'selected' : '' }}>Announced</option>
        <option value="Canceled" {{ $programme->status == 'Canceled' ? 'selected' : '' }}>Canceled</option>
        <option value="Postponed" {{ $programme->status == 'Postponed' ? 'selected' : '' }}>Postponed</option>
    </select>
</div>
              </div>

              <div class="row">
                <div class="col-md-12 form-group">
                  <label for="remarks" class="form-label">Remarks</label>
                  <textarea name="remarks" id="remarks" class="form-control" rows="3" placeholder="Enter Remarks">{{ $programme->remarks }}</textarea>
                </div>
              </div>

              <button type="submit" class="btn btn-primary">Update Programme</button>
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
<script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('assets/select2/js/select2.min.js') }}"></script>

<script>
    
     const canEditCalendar   = @json(Auth::guard('admin')->user()->hasAccess('programme_calendar', 'edit'));
    const canDeleteCalendar = @json(Auth::guard('admin')->user()->hasAccess('programme_calendar', 'delete'));
{
    data: 'id',
    name: 'action',
    orderable: false,
    searchable: false,
    render: function(data, type, row) {
        let btns = '<div class="btn-group">';

        if (canEditCalendar) {
            btns += `
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
        </button>`;
        }

        if (canDeleteCalendar) {
            btns += `
        <button class="btn btn-sm btn-danger delete-btn" data-id="${row.id}">
            <i class="fas fa-trash"></i> Delete
        </button>`;
        }

        btns += '</div>';

        if (!canEditCalendar && !canDeleteCalendar) {
            btns = '<span class="text-muted">No actions</span>';
        }

        return btns;
    }
}

{
    data: 'is_active',
    name: 'is_active',
    render: function(data, type, row) {
        if (!canEditCalendar) {
            return data
                ? `<span class="badge badge-success">Active</span>`
                : `<span class="badge badge-danger">Inactive</span>`;
        }
        return data ?
            `<button class="btn btn-sm btn-success toggle-status" data-id="${row.id}" data-status="1">Active</button>` :
            `<button class="btn btn-sm btn-danger toggle-status" data-id="${row.id}" data-status="0">Inactive</button>`;
    }
},

  document.addEventListener('DOMContentLoaded', function () {
    const venue = document.getElementById('venue');
    const location = document.getElementById('location');

    location.addEventListener('change', function () {
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

    // Initialize based on current sponsor
    const currentSponsor = $('#sponsor_id option:selected').text().trim();
    if (currentSponsor === 'NABARD') {
      $('#department_container').show();
      $('#department_input_container').hide();
    } else if (currentSponsor === 'Customised') {
      $('#customised_fee_section').show();
      $('#department_input_container').show();
    } else if (currentSponsor === 'Paid') {
      $('#department_input_container').show();
    } else {
      $('#department_input_container').show();
    }

    $('#sponsor_id').change(function () {
      const selectedText = $('#sponsor_id option:selected').text().trim();
      const selectedAgencies = $('#agency_type_id option:selected').map(function () {
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
        $('#agency_type_id option:selected').each(function () {
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

    $('#participant_fee_check').change(function () {
      if (this.checked) {
        $('#program_fee_check').prop('checked', false);
        $('#program_fee').hide().val('');
        $('#participant_fee').show();
      } else {
        $('#participant_fee').hide().val('');
      }
    });

    $('#program_fee_check').change(function () {
      if (this.checked) {
        $('#participant_fee_check').prop('checked', false);
        $('#participant_fee').hide().val('');
        $('#program_fee').show();
      } else {
        $('#program_fee').hide().val('');
      }
    });

    $('#from_date, #to_date').change(function () {
      const fromDate = new Date($('#from_date').val());
      const toDate = new Date($('#to_date').val());
      if ($('#from_date').val() && $('#to_date').val() && fromDate <= toDate) {
        const diffTime = Math.abs(toDate - fromDate);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
        $('#duration').val(diffDays + ' Days');
      }
    });

    document.getElementById('agency-group-form').addEventListener('submit', function (e) {
      const sponsorText = $('#sponsor_id option:selected').text().trim();
      let formIsValid = true;

      if (sponsorText === 'Paid') {
        $('#paid_agency_table_body input[type="number"]').each(function () {
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
          alert('Please select either "Parti. Wise Fee" or "Prog. Wise Fee" for Customised sponsor.');
          return;
        }

        if (participantChecked && (participantFee === '' || isNaN(participantFee) || parseFloat(participantFee) <= 0)) {
          $('#participant_fee').addClass('is-invalid');
          e.preventDefault();
          alert('Please enter a valid Participant Wise Fee.');
          return;
        } else {
          $('#participant_fee').removeClass('is-invalid');
        }

        if (programChecked && (programFee === '' || isNaN(programFee) || parseFloat(programFee) <= 0)) {
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