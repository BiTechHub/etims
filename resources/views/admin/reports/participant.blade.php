@extends('admin.layouts.master')
@section('main-section')
<link rel="stylesheet" href="{{ asset('assets/summernote/summernote-lite.css') }}">
<div class="container">
  <div class="page-inner">
    <div class="page-header">
      <h3 class="fw-bold mb-3">Participant</h3>
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
          <a href="#">Reports</a>
        </li>
        <li class="separator">
          <i class="icon-arrow-right"></i>
        </li>
        <li class="nav-item">
          <a href="#">Participant</a>
        </li>
      </ul>
    </div>
    <div class="row">
      <div class="row">
        <div class="card">
    <div class="card-body">  
        <div class="row">
            <!-- Calendar Year -->
            <div class="col-md-4 mb-3">
                <label for="cal_year" class="form-label">
                    <i class="fas fa-calendar-alt me-2"></i>Calendar Year
                </label>
                <select class="form-control" id="cal_year" name="cal_year" required>
                    <option value="">Select Calendar Year</option>
                    @foreach($distinctYears as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Programme Code -->
            <div class="col-md-4 mb-3">
                <label for="programme_code" class="form-label">Code</label>
                <input type="text" id="programme_code" class="form-control" placeholder="Enter unique code">
            </div>

            <!-- Programme Dropdown -->
            <div class="col-md-4 mb-3">
                <label for="programme_id" class="form-label">
                    <i class="fas fa-graduation-cap me-2"></i>Programme
                </label>
                <select id="programme_id" class="form-control">
                    <option value="">Select Programme...</option>
                    @foreach($programmes as $prog)
                        <option value="{{ $prog->id }}" data-year="{{ $prog->financial_year }}">
                            {{ $prog->code }} – {{ Str::limit($prog->title, 50) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Download Button -->
        <div class="row">
            <div class="col-md-12">
                <button class="btn btn-success mt-2" id="btn-download">
                    <i class="fas fa-download me-1"></i> Download Participants
                </button>
            </div>
        </div>
    </div>
</div>

      </div>
      <div class="col-md-12">
     
      </div>
      <div class="card">
     <div class="card-body">
<div class="table-container table-responsive">
     <table id="participants-table" class="table">
        <thead>
          <tr>
            <th>Participant</th>
            <th>Designation</th>
            <th>Agency</th>
            <th>Phone</th>
            <th>City</th>
            <th>State</th>
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


<script src="{{ asset('assets/summernote/summernote-lite.js') }}"></script>

<script>
$(function () {
  const yearSelect = $('#cal_year');
  const programmeSelect = $('#programme_id');
  const loading = $('#loading-indicator');
  const tableElem = $('#participants-table');
  const downloadBtn = $('#btn-download');

  const table = tableElem.DataTable({
    ajax: {
      url: '{{ route("admin.nominations.byProgramme") }}',
      dataSrc: 'data',
      data: d => {
        d.programme_id = programmeSelect.val();
      },
      beforeSend: () => loading.show(),
      complete: () => loading.hide(),
      error: () => {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Failed to load participant data. Please try again.'
        });
      }
    },
    columns: [
      {
        data: 'name',
        render: (data, type, row) => `
          <div class="d-flex align-items-center">
            <i class="fas fa-user-circle fa-lg text-muted me-2"></i>
            <div>
              <strong>${data}</strong><br>
              <small class="text-muted">ID: ${row.id}</small>
            </div>
          </div>
        `
      },
      { data: 'designation' },
      {
        data: 'nomination.agency.name',
        render: data => data ? `<span class="text-primary">${data}</span>` : '<span class="text-muted">N/A</span>'
      },
      {
        data: 'phone',
        defaultContent: '<span class="text-muted">N/A</span>'
      },
      {
        data: 'city',
        defaultContent: '<span class="text-muted">N/A</span>'
      },
      {
        data: 'state',
        defaultContent: '<span class="text-muted">N/A</span>'
      }
    ],
    language: {
      emptyTable: '<div class="empty-table-message"><i class="fas fa-users-slash fa-2x mb-3"></i><p>Select year & programme to load participants</p></div>'
    }
  });

  yearSelect.on('change', () => {
    const year = yearSelect.val();
    programmeSelect.find('option[data-year]').each(function () {
      $(this).toggle($(this).data('year') == year);
    });
    programmeSelect.val('').trigger('change');
    table.clear().draw();
  });

  programmeSelect.on('change', () => {
    if (programmeSelect.val()) {
      table.ajax.reload();
    } else {
      table.clear().draw();
    }
  });

  downloadBtn.on('click', function () {
    const programmeId = programmeSelect.val();
    if (!programmeId) {
      Swal.fire({
        icon: 'warning',
        title: 'No Programme Selected',
        text: 'Please select a programme first.'
      });
      return;
    }

    const url = `{{ url('admin/nominations/download') }}/${programmeId}`;
    window.open(url, '_blank');
  });
});
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


@endsection
