@extends('admin.layouts.master')
@section('main-section')
  <link rel="stylesheet" href="{{ asset('assets/summernote/summernote-lite.css') }}">
  <div class="container">
    <div class="page-inner">
      <div class="page-header">
        <h3 class="fw-bold mb-3">Rating</h3>
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
            <a href="#">Rating</a>
          </li>
        </ul>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
              <div class="row">
                <!-- Calendar Year -->
                <div class="col-md-4 mb-3">
                  <label for="financial_year" class="form-label">
                    <i class="fas fa-calendar-alt me-2"></i>Calendar Year
                  </label>
                  <select class="form-control" id="financial_year" name="financial_year" required>
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
                  <label for="programme" class="form-label">
                    <i class="fas fa-graduation-cap me-2"></i>Programme
                  </label>
                  <select id="programme" class="form-control" disabled>
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
                  <button class="btn btn-primary mt-2" id="btn-download" disabled>
                    <i class="fas fa-download me-1"></i> Download Report
                  </button>
                </div>
              </div>
            </div>
          </div>

        </div>
        <div class="card">
          <div class="card-body">
            <div class="table-container table-responsive">
              <table id="feedback-table" class="table">
                <thead>
                  <tr>
                    <th>Submenu</th>
                    <th>Menu</th>
                    <th>Rating Distribution</th>
                    <th>Average Rating</th>
                    <th>Total Responses</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td colspan="5" class="text-center">
                      <div class="empty-table-message">
                        <i class="fas fa-chart-bar fa-2x mb-3"></i>
                        <p>Select year and programme to load feedback data</p>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

          </div>

        </div>
        <div id="summary-card" class="card mt-4" style="display: none;">
          <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Programme Feedback Summary</h5>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-4">
                <div class="d-flex align-items-center">
                  <div class="bg-primary text-white rounded p-3 me-3">
                    <i class="fas fa-star fa-2x"></i>
                  </div>
                  <div>
                    <h6 class="mb-0">Overall Average</h6>
                    <h3 id="overall-average" class="mb-0">0.0</h3>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="d-flex align-items-center">
                  <div class="bg-success text-white rounded p-3 me-3">
                    <i class="fas fa-thumbs-up fa-2x"></i>
                  </div>
                  <div>
                    <h6 class="mb-0">5-Star Ratings</h6>
                    <h3 id="five-star-count" class="mb-0">0</h3>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="d-flex align-items-center">
                  <div class="bg-info text-white rounded p-3 me-3">
                    <i class="fas fa-users fa-2x"></i>
                  </div>
                  <div>
                    <h6 class="mb-0">Total Participants</h6>
                    <h3 id="total-participants" class="mb-0">0</h3>
                  </div>
                </div>
              </div>
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
      const yearSelect = $('#financial_year');
      const programmeSelect = $('#programme');
      const loading = $('#loading-indicator');
      const tableElem = $('#feedback-table');
      const downloadBtn = $('#btn-download');
      const summaryCard = $('#summary-card');

      // Initialize empty table
      // let table = tableElem.DataTable({
      //   dom: '<"top"i>rt<"bottom"lp><"clear">',
      //   paging: false,
      //   searching: false,
      //   info: false,
      //   columns: [
      //     { data: 'submenu_name', className: 'submenu-name' },
      //       { data: 'menu_name', className: 'menu-name' },
      //     { data: 'rating_distribution' },
      //     { data: 'average_rating' },
      //     { data: 'total_responses' }
      //   ],
      //   language: {
      //     emptyTable: '<div class="empty-table-message"><i class="fas fa-chart-bar fa-2x mb-3"></i><p>Select year and programme to load feedback data</p></div>'
      //   }
      // });

      // Enable programme select when year is selected
      yearSelect.on('change', function () {
        const year = $(this).val();
        if (year) {
          programmeSelect.prop('disabled', false);
          // Filter programmes by selected year
          programmeSelect.find('option[data-year]').each(function () {
            $(this).toggle($(this).data('year') == year);
          });
          programmeSelect.val('').trigger('change');
        } else {
          programmeSelect.prop('disabled', true).val('');
          downloadBtn.prop('disabled', true);
        }

        // Clear the table
        table.clear().draw();
        summaryCard.hide();
      });

      // Handle programme selection
      programmeSelect.on('change', function () {
        const programmeId = $(this).val();

        if (!programmeId) {
          downloadBtn.prop('disabled', true);
          table.clear().draw();
          summaryCard.hide();
          return;
        }

        downloadBtn.prop('disabled', false);
        loading.show();

        // Destroy existing table if it exists
        if ($.fn.DataTable.isDataTable(tableElem)) {
          table.destroy();
        }

        // Initialize new DataTable with AJAX
        table = tableElem.DataTable({
          processing: true,
          serverSide: false,
          ajax: {
            url: '{{ route("admin.feedback.byProgramme") }}',
            type: 'GET',
            data: function (d) {
              return {
                programme_id: programmeId
              };
            },
            dataSrc: 'data',
            beforeSend: function () {
              loading.show();
            },
            complete: function () {
              loading.hide();
            },
            error: function (xhr) {
              loading.hide();
              console.error('AJAX Error:', xhr.status, xhr.responseText);
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load feedback data. Please try again.'
              });
            }
          },
          columns: [
            { data: 'submenu_name', className: 'submenu-title', render: (data) => `<strong>${data}</strong>` },
            { data: 'menu_name', className: 'menu-title', render: (data) => `<strong>${data}</strong>` },
            {
              data: 'rating_distribution', render: function (data) {
                let html = '<div class="d-flex justify-content-center align-items-center">';
                for (let i = 5; i >= 1; i--) {
                  const count = data[i] || 0;
                  html += `<span class="rating-count rating-${i}" title="${i} star: ${count}">${count}</span>`;
                }
                html += '</div>';
                const total = Object.values(data).reduce((a, b) => a + b, 0);
                const percent5 = total > 0 ? Math.round((data[5] || 0) / total * 100) : 0;
                html += `<div class="progress mt-2">
            <div class="progress-bar" role="progressbar" style="width: ${percent5}%"
              aria-valuenow="${percent5}" aria-valuemin="0" aria-valuemax="100"></div>
          </div>`;
                return html;
              }
            },
            { data: 'average_rating', render: (data) => `<span class="average-rating">${parseFloat(data).toFixed(1)}</span>` },
            { data: 'total_responses', render: (data) => `<span class="badge bg-primary rounded-pill">${data}</span>` }
          ],
          createdRow: function (row, data) {
            if (data.average_rating < 3) {
              $(row).addClass('bg-warning bg-opacity-10');
            } else if (data.average_rating >= 4) {
              $(row).addClass('bg-success bg-opacity-10');
            }
          },
          drawCallback: function (settings) {
            const json = settings.json;
            if (json && json.summary) {
              $('#overall-average').text(parseFloat(json.summary.overall_average).toFixed(1));
              $('#five-star-count').text(json.summary.five_star_count);
              $('#total-participants').text(json.summary.total_participants);
              summaryCard.show();
            } else {
              summaryCard.hide();
            }
          }
        });
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
        const url = `{{ url('admin/feedback/download') }}/${programmeId}`;
        window.open(url, '_blank');
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