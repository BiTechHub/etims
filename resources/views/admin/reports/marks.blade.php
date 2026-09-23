@extends('admin.layouts.master')
@section('main-section')
<link rel="stylesheet" href="{{ asset('assets/summernote/summernote-lite.css') }}">
<div class="container">
  <div class="page-inner">
    <div class="page-header">
      <h3 class="fw-bold mb-3">Entry Test Marks</h3>
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
          <a href="#">Entry Test Marks</a>
        </li>
      </ul>
    </div>
    <div class="row">
      <div class="row">
        <div class="card">
    <div class="card-body">
        <div class="row">
            <!-- Financial Year -->
            <div class="col-md-4 mb-3">
                <label for="financial_year" class="form-label">
                    <i class="fas fa-calendar-alt me-2"></i>Financial Year
                </label>
                <select id="financial_year" class="form-control">
                    <option value="">-- Select Year --</option>
                    @foreach ($financialYears as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
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
                <label for="programme" class="form-label">
                    <i class="fas fa-graduation-cap me-2"></i>Programme
                </label>
                <select id="programme" class="form-control" disabled>
                    <option value="">-- Select Programme --</option>
                </select>
            </div>
        </div>

        <!-- Export Button -->
        <div class="row">
            <div class="col-12">
                <button id="btn-download" class="btn btn-primary mt-2" disabled>
                    <i class="fas fa-file-export me-1"></i> Export Report
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
    <table id="marks-table" class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>Participant Name</th>
            <th>Marks</th>
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
<script src="{{ asset('assets/summernote/summernote-lite.js') }}"></script>

<script>
$(function () {
  const financialYearSelect = $('#financial_year');
  const programmeSelect = $('#programme');
  const loading = $('#loading-indicator');
  const tableElem = $('#marks-table');
  const downloadBtn = $('#btn-download');
  let currentProgrammeId = null;

  // Initialize DataTable
  const table = tableElem.DataTable({
    searching: false,
    paging: false,
    info: false,
    columns: [
      { data: 'index' },
      { data: 'participant_name' },
      { 
        data: 'marks',
        render: function(data) {
          if (data >= 80) {
            return `<span class="marks-high">${data}</span>`;
          } else if (data >= 50) {
            return `<span class="marks-medium">${data}</span>`;
          } else {
            return `<span class="marks-low">${data}</span>`;
          }
        }
      }
    ],
    language: {
      emptyTable: '<div class="empty-table-message"><i class="fas fa-clipboard-list fa-2x mb-3"></i><p>Select financial year and programme to view marks</p></div>'
    }
  });

  // Load Programmes on Financial Year change
  financialYearSelect.change(function () {
    const year = $(this).val();
    programmeSelect.prop('disabled', true).html('<option value="">Loading...</option>');
    table.clear().draw();
    downloadBtn.prop('disabled', true);
    currentProgrammeId = null;

    if (year) {
      loading.show();
      $.ajax({
        url: '{{ route("getProgrammesByYear") }}',
        type: 'POST',
        data: {
          _token: '{{ csrf_token() }}',
          year: year
        },
        success: function (response) {
          programmeSelect.html('<option value="">-- Select Programme --</option>');
          response.forEach(programme => {
            programmeSelect.append(`<option value="${programme.id}">${programme.title}</option>`);
          });
          programmeSelect.prop('disabled', false);
          loading.hide();
        },
        error: function() {
          loading.hide();
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Failed to load programmes. Please try again.'
          });
        }
      });
    }
  });

  // Load Participants and Marks on Programme change
  programmeSelect.change(function () {
    const programmeId = $(this).val();
    currentProgrammeId = programmeId;
    
    if (programmeId) {
      loading.show();
      downloadBtn.prop('disabled', false);
      $.ajax({
        url: '{{ route("getParticipantsAndMarks") }}',
        type: 'POST',
        data: {
          _token: '{{ csrf_token() }}',
          programme_id: programmeId
        },
        success: function (response) {
          const rows = response.map((item, index) => ({
            index: index + 1,
            participant_name: item.participant_name,
            marks: item.marks
          }));
          
          table.clear().rows.add(rows).draw();
          loading.hide();
        },
        error: function() {
          loading.hide();
          downloadBtn.prop('disabled', true);
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Failed to load marks data. Please try again.'
          });
        }
      });
    } else {
      table.clear().draw();
      downloadBtn.prop('disabled', true);
    }
  });

  // Download button handler
  downloadBtn.on('click', function() {
    if (!currentProgrammeId) {
      Swal.fire({
        icon: 'warning',
        title: 'No Programme Selected',
        text: 'Please select a programme first.'
      });
      return;
    }

    loading.show();
    
    // You can implement either direct download or via AJAX
    // Option 1: Direct download (recommended for large files)
 window.location.href = `{{ route('entry.test.downlode', ':id') }}`.replace(':id', currentProgrammeId);

    
    
  });
});
</script>
<script>
document.getElementById('programme_code').addEventListener('keyup', function () {
    let code = this.value.trim();
    let year = document.getElementById('financial_year').value;
    let select = document.getElementById('programme');

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


@endsection
