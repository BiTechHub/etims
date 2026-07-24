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
                <select class="form-control" id="cal_year" name="cal_year" required>
                    <option value="">Select Calendar Year</option>
                    @foreach($distinctYears as $year)
                        <option value="{{ $year }}" {{ request('cal_year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Programme Code -->
            <div class="col-md-4 mb-3">
                <label for="programme_code" class="form-label">Code</label>
                <input type="text" id="programme_code" class="form-control" placeholder="Enter unique code">
            </div>

            <!-- Programme -->
            <div class="col-md-4 mb-3">
                <label for="programme_id" class="form-label">Programme</label>
                <select id="programme_id" class="form-control">
                    <option value="">Select Programme</option>
                    @foreach($programmes as $prog)
                        <option value="{{ $prog->id }}" data-year="{{ $prog->financial_year }}"
                            {{ request('programme_id') == $prog->id ? 'selected' : '' }}>
                            {{ $prog->code }} – {{ Str::limit($prog->title, 50) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Status Bar -->
        <!-- Status Bar -->
<div class="mt-4 border-top pt-3">
    <span class="fw-bold me-3">Nomination Status:</span>

    @if(Auth::guard('admin')->user()->hasAccess('nomination', 'edit'))
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
        <span class="text-muted">You don't have permission to change nomination status.</span>
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
$(function () {
  const yearSelect = $('#cal_year');
  const programmeSelect = $('#programme_id');
  const loading = $('#loading-indicator');
  const tableElem = $('#participants-table');
  let selectedParticipants = [];

  const table = tableElem.DataTable({
    processing: true,
    serverSide: true,
    ajax: {
      url: '{{ route("admin.nominations.byProgramme.confirm") }}',
      data: function (d) {
        d.programme_id = programmeSelect.val();
        d.cal_year = yearSelect.val();
      }
    },
    columns: [
      { 
        data: null, 
        orderable: false,
        render: function(data) {
          return `<input type="checkbox" class="participant-checkbox" data-id="${data.id}">`;
        }
      },
      { 
        data: 'name',
        render: function(data, type, row) {
          return `<a href="{{ route('participant.detail.prog', ':id') }}`
                  .replace(':id', row.id) + '" class="text-primary">' + data + '</a>';
        }
      },
      { data: 'designation' },
      { 
        data: 'nomination.agency_type.name',
        render: function (data) {
          return data || '<span class="text-muted">N/A</span>';
        }
      },
      { data: 'city' },
      { 
        data: null,
        render: function(data) {
          const email = data.email ? `<div><i class="fas fa-envelope mr-1"></i>${data.email}</div>` : '';
          const phone = data.phone ? `<div><i class="fas fa-phone mr-1"></i>${data.phone}</div>` : '';
          return email + phone || '<span class="text-muted">N/A</span>';
        }
      },
      { 
        data: 'status',
        render: function(data) {
          if (!data) return '<span class="badge badge-secondary">Pending</span>';
          const statusClass = {
            'confirm': 'badge-success',
            'regret': 'badge-danger',
            'cancel': 'badge-warning',
            'pending': 'badge-secondary'
          }[data] || 'badge-info';
          return `<span class="badge ${statusClass}">${data.charAt(0).toUpperCase() + data.slice(1)}</span>`;
        }
      },

      { 
        data: 'hostel_attendence',
        render: function(data) {
          return data ? `<span class="badge badge-info">${data}</span>` : '<span class="text-muted">N/A</span>';
        }
      },
      { 
        data: null,
        render: function(data) {
          if (!data.checkout_time && !data.date) return '<span class="text-muted">N/A</span>';
          return `<div><strong>Time:</strong> ${data.checkout_time || 'N/A'}</div>
                  <div><strong>Date:</strong> ${data.date || 'N/A'}</div>`;
        }
      },
      {
        data: 'current_fy_count',
        render: function(data, type, row) {
          return `<a href="{{ route('participant.current.prog', ':id') }}`
                  .replace(':id', row.id) + '" class="badge badge-primary">' + (data || 0) + '</a>';
        }
      },
      { 
        data: 'last_fy_count',
        render: function(data, type, row) {
          return `<a href="{{ route('participant.last.prog', ':id') }}`
                  .replace(':id', row.id) + '" class="badge badge-secondary">' + (data || 0) + '</a>';
        }
      }
    ],
    language: { 
      emptyTable: 'No participants found for the selected criteria',
      processing: '<i class="fas fa-spinner fa-spin"></i> Loading participants...'
    },
    createdRow: function(row, data, dataIndex) {
      $(row).attr('data-id', data.id);
    }
  });

  yearSelect.on('change', function() {
    const year = $(this).val();
    programmeSelect.find('option[data-year]').each(function() {
      $(this).toggle($(this).data('year') == year);
    });
    programmeSelect.val('').trigger('change');
    table.clear().draw();
  });

  programmeSelect.on('change', function() {
    if ($(this).val()) {
      loading.show();
      table.ajax.reload(function() {
        loading.hide();
      });
    } else {
      table.clear().draw();
    }
  });

  $('#select-all').on('change', function() {
    $('.participant-checkbox').prop('checked', this.checked);
    updateSelectedParticipants();
  });

  tableElem.on('change', '.participant-checkbox', function() {
    updateSelectedParticipants();
    $('#select-all').prop('checked', 
      $('.participant-checkbox:checked').length === $('.participant-checkbox').length
    );
  });

  function updateSelectedParticipants() {
    selectedParticipants = $('.participant-checkbox:checked').map(function() {
      return $(this).data('id');
    }).get();
  }

  function showAlert(type, message) {
    const alert = $(`<div class="alert alert-${type} alert-dismissible fade show" role="alert">
      ${message}
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>`);
    $('.container-fluid').prepend(alert);
    setTimeout(() => alert.alert('close'), 5000);
  }

  function performAction(url, data, successMessage) {
    if (selectedParticipants.length === 0) {
      showAlert('warning', 'Please select at least one participant.');
      return;
    }

    loading.show();
    $.ajax({
      url: url,
      type: 'POST',
      data: data,
      success: function(response) {
        showAlert('success', successMessage);
        table.ajax.reload(null, false);
      },
      error: function(xhr) {
        showAlert('danger', 'Error: ' + (xhr.responseJSON?.message || 'Something went wrong'));
      },
      complete: function() {
        loading.hide();
      }
    });
  }

  // Attendance actions
  $('#btn-mark').on('click', function() {
    performAction(
      '{{ route("admin.nominations.updateAttendance") }}',
      {
        _token: '{{ csrf_token() }}',
        participant_ids: selectedParticipants,
        status: 'Present'
      },
      'Attendance marked successfully for selected participants.'
    );
  });

  $('#btn-clear').on('click', function() {
    performAction(
      '{{ route("admin.nominations.updateAttendance") }}',
      {
        _token: '{{ csrf_token() }}',
        participant_ids: selectedParticipants,
        status: 'Absent'
      },
      'Attendance cleared successfully for selected participants.'
    );
  });

  $('#btn-email').on('click', function() {
    performAction(
      '{{ route("admin.nominations.updateAttendance") }}',
      {
        _token: '{{ csrf_token() }}',
        participant_ids: selectedParticipants,
        status: 'mark by email'
      },
      'Confirmation emails will be sent to selected participants.'
    );
  });

  $('#btn-email-r').on('click', function() {
    performAction(
      '{{ route("admin.nominations.updateAttendance") }}',
      {
        _token: '{{ csrf_token() }}',
        participant_ids: selectedParticipants,
        status: 'regret by email'
      },
      'Regret emails will be sent to selected participants.'
    );
  });

  // Nomination status actions
  $('#btn-confirm').on('click', function() {
    performAction(
      '{{ route("admin.nominations.updateStatus") }}',
      {
        _token: '{{ csrf_token() }}',
        participant_ids: selectedParticipants,
        status: 'confirm'
      },
      'Nomination status updated to Confirm for selected participants.'
    );
  });

  $('#btn-regret').on('click', function() {
    performAction(
      '{{ route("admin.nominations.updateStatus") }}',
      {
        _token: '{{ csrf_token() }}',
        participant_ids: selectedParticipants,
        status: 'regret'
      },
      'Nomination status updated to Regret for selected participants.'
    );
  });

  $('#btn-cancel').on('click', function() {
    performAction(
      '{{ route("admin.nominations.updateStatus") }}',
      {
        _token: '{{ csrf_token() }}',
        participant_ids: selectedParticipants,
        status: 'cancel'
      },
      'Nomination status updated to Cancel for selected participants.'
    );
  });

  $('#btn-pending').on('click', function() {
    performAction(
      '{{ route("admin.nominations.updateStatus") }}',
      {
        _token: '{{ csrf_token() }}',
        participant_ids: selectedParticipants,
        status: 'pending'
      },
      'Nomination status set to Pending for selected participants.'
    );
  });

  // Initialize with current year if available
  @if(request('cal_year'))
    yearSelect.trigger('change');
    programmeSelect.val('{{ request('programme_id') }}').trigger('change');
  @endif
});
</script>



<script>
document.getElementById('programme_code').addEventListener('keyup', function () {
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
            body: JSON.stringify({ code: code, year: year })
        })
        .then(response => response.json())
        .then(data => {
            if (data && data.id) {
                select.innerHTML = `<option value="${data.id}" data-code="${data.unique_id}" selected>${data.title}</option>`;
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
<script>
document.getElementById('programme').addEventListener('change', function () {
    let programmeId = this.value;

    if (programmeId) {
        fetch('/get-programme-unique-id', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ id: programmeId })
        })
        .then(response => response.json())
        .then(data => {
            if (data && data.code) {
                document.getElementById('programme_code').value = data.code;
            } else {
                document.getElementById('programme_code').value = '';
                Swal.fire({
                    icon: 'warning',
                    title: 'Not Found',
                    text: 'Unique ID not found for the selected programme.'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to fetch unique ID.'
            });
        });
    }
});
</script>
<script>
document.getElementById('programme_id').addEventListener('change', function () {
    let programmeId = this.value;

    if (programmeId) {
        fetch('/get-programme-unique-id', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ id: programmeId })
        })
        .then(response => response.json())
        .then(data => {
            if (data && data.code) {
                document.getElementById('programme_code').value = data.code;
            } else {
                document.getElementById('programme_code').value = '';
                Swal.fire({
                    icon: 'warning',
                    title: 'Not Found',
                    text: 'Unique ID not found for the selected programme.'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to fetch unique ID.'
            });
        });
    }
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const calYearSelect = document.getElementById('cal_year');
  const programmeSelect = document.getElementById('programme_id');
  const allProgrammeOptions = Array.from(programmeSelect.options).slice(1); // Skip placeholder

  // Initially disable programme dropdown
  programmeSelect.disabled = true;

  calYearSelect.addEventListener('change', function () {
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


@endsection
