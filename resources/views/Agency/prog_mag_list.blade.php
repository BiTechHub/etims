@extends('Agency.layouts.master')
@section('main-section')
  <style>
    .table td,
    .table thead th {
      font-size: 11px;
      white-space: nowrap;
      vertical-align: middle;

    }

    .table>tbody>tr>td,
    .table>tbody>tr>th {
      padding: 6px 4px !important;

    }
  </style>
  <div class="container">
    <div class="page-inner">
      <!-- Page Header -->
      <!-- Table & Modal -->
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <div class="d-flex align-items-center">
                <h4 class="card-title">Programmes List</h4>
                {{-- <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal"
                  data-bs-target="#addRowModal">
                  <i class="fa fa-plus"></i> Add Menu
                </button> --}}
              </div>
            </div>
            <div class="card-body">

              <!-- Add Modal -->
              <div class="modal fade" id="addRowModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header border-0">
                      <h5 class="modal-title"><span class="fw-mediumbold"> New</span> <span class="fw-light"> Menu </span>
                      </h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                      <p class="small">Create a new menu using this form.</p>
                      <form>
                        <div class="row">
                          <div class="col-sm-12">
                            <div class="form-group form-group-default1">
                              <label>Menu Name</label>
                              <input id="menu" name="menu" type="text" class="form-control" placeholder="English" />
                            </div>
                          </div>
                          {{-- <div class="col-md-12">
                            <div class="form-group form-group-default">
                              <label>Menu Name (Hindi)</label>
                              <input id="menu_hi" name="menu_hi" type="text" class="form-control" placeholder="Hindi" />
                            </div>
                          </div> --}}
                        </div>
                      </form>
                    </div>
                    <div class="modal-footer border-0">
                      <button type="button" id="addRowButton" class="btn btn-primary">Add</button>
                      <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Edit Modal -->
              <div class="modal fade" id="editRowModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header border-0">
                      <h5 class="modal-title">Edit Menu</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                      <input type="hidden" id="edit_id">
                      <div class="form-group">
                        <label>Menu Name</label>
                        <input id="edit_menu" name="menu" type="text" class="form-control" />
                      </div>
                      {{-- <div class="form-group">
                        <label>Menu Name (Hindi)</label>
                        <input id="edit_menu_hi" name="menu_hi" type="text" class="form-control" />
                      </div> --}}
                    </div>
                    <div class="modal-footer border-0">
                      <button type="button" id="updateRowButton" class="btn btn-primary">Update</button>
                      <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Table -->
              <div class="table-responsive">
                <table id="programme-table" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Title</th>
                      <th>Program Code</th>
                      <th>From Date</th>
                      <th>To Date</th>
                      <th>Sponsor Type</th>
                      <th>Duration</th>
                      <th>Location</th>
                      <th>Faculty Name</th>
                      <th>Faculty Emails</th>
                      <th>Faculty Mobile</th>
                      <th>Announcement Letter</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <!-- Data will be loaded via AJAX -->
                  </tbody>
                </table>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
  <script src="{{url('/admin')}}/assets/js/plugin/datatables/datatables.min.js"></script>
  <script>
    $(document).ready(function () {
      const nominationBaseUrl = "{{ url('/agency-panel/add-nomination') }}";
      const nominationListBaseUrl = "{{ url('/agency-panel/nomination-list') }}";
      var table = $('#programme-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: "{{url('/agency-panel/programmes-data')}}",
          data: function (d) {
            // Append the selected status to the data being sent to the server
            d.status = $('#status-filter').val(); // Get the selected status
            d.group = $('#group-filter').val();
            d.sponsor = $('#sponsor-filter').val();
            d.year = $('#year-filter').val();
          }
        },
        columns: [
          {
            data: null,
            name: 'index',
            orderable: false,
            searchable: false,
            render: function (data, type, row, meta) {
              return meta.row + 1;
            }
          },
          { data: 'title', name: 'title' },
          { data: 'unique_id', name: 'unique_id' },

          // { data: 'group_name', name: 'group_name' },
          { data: 'from_date', name: 'from_date' },
          { data: 'to_date', name: 'to_date' },
          {
            data: 'sponsor_name',
            name: 'sponsor_name',
          },
          { data: 'duration', name: 'duration' },
          { data: 'venue', name: 'location' },
          { data: 'faculty_name', name: 'faculty_name', orderable: false, searchable: false },

          {
            data: 'faculty_emails',
            name: 'faculty_emails',
            orderable: false,
            searchable: false
          },
          {
            data: 'faculty_mobiles',
            name: 'faculty_mobiles',
            orderable: false,
            searchable: false
          },

          {
            data: 'announcement_letter',
            name: 'announcement_letter',
            orderable: false,
            searchable: false,
            render: function (data, type, row) {

              if (!data) {
                return '<span class="text-muted">No File</span>';
              }

              let files = data.split(','); // 🔥 split multiple files

              let buttons = '';

              files.forEach(function (file, index) {

                let fileUrl = '/announcements/' + file.trim();

                buttons += `
                  <div class="mb-1">
                      <a href="${fileUrl}" target="_blank" class="btn btn-info btn-sm">
                          View ${index + 1}
                      </a>
                      <a href="${fileUrl}" download class="btn btn-success btn-sm">
                          Download ${index + 1}
                      </a>
                  </div>
              `;
              });

              return buttons;
            }
          }, {
            data: 'id',
            name: 'actions',
            orderable: false,
            searchable: false,
            render: function (data, type, full, meta) {
              return `
                              <div class="btn-group">
                                  <a href="${nominationListBaseUrl}/?program=${btoa(data)}" class="btn btn-primary btn-action">
                                      Nomination list
                                  </a>
                                  <a href="${nominationBaseUrl}/${data}" class="btn btn-primary btn-action">
                                    Apply for Nomination
                                  </a>
                              </div>
                          `;
            }
          }
        ],
        responsive: true,
        language: {
          processing: '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>'
        },
        initComplete: function () {
          // Add delete button functionality
          $('#programme-table').on('click', '.delete-btn', function () {
            var id = $(this).data('id');
            if (confirm('Are you sure you want to delete this programme?')) {
              $.ajax({
                url: '/admin/programme-management/delete/' + id,
                type: 'post',
                data: {
                  _token: "{{ csrf_token() }}"
                },
                success: function (response) {
                  if (response.success) {
                    $('#programme-table').DataTable().ajax.reload();
                    alert('Programme deleted successfully');
                  } else {
                    alert('Error deleting programme');
                  }
                },
                error: function () {
                  alert('Error deleting programme');
                }
              });
            }
          });
        }
      });

      // When the status filter changes, reload the table data
      $('#status-filter').change(function () {
        table.ajax.reload();
      });
      $('#group-filter, #status-filter').on('change', function () {
        $('#programme-table').DataTable().ajax.reload();
      });
      $('#status-filter, #group-filter, #sponsor-filter').on('change', function () {
        $('#programme-table').DataTable().ajax.reload();
      });

      $('#status-filter, #group-filter, #sponsor-filter, #year-filter').on('change', function () {
        $('#programme-table').DataTable().ajax.reload();
      });

    });
  </script>
@endsection