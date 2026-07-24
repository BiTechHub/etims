@extends('admin.layouts.master')
@section('main-section')
<div class="container">
  <div class="page-inner">
    <div class="page-header">
      <h3 class="fw-bold mb-3">Add Nomination</h3>
      <ul class="breadcrumbs mb-3">
        <li class="nav-home"><a href="#"><i class="icon-home"></i></a></li>
        <li class="separator"><i class="icon-arrow-right"></i></li>
        <li class="nav-item"><a href="#">Nomination</a></li>
        <li class="separator"><i class="icon-arrow-right"></i></li>
        <li class="nav-item"><a href="#">Add Nomination</a></li>
      </ul>
    </div>

    <a href="{{ route('nomination.add') }}" class="btn btn-primary mb-3">Add Nomination</a>

    <div class="card mb-4">
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-3">
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

          <div class="col-md-3">
            <label for="programme_code" class="form-label">Programme Code</label>
            <input type="text" id="programme_code" name="programme_code" class="form-control" placeholder="Enter unique code">
          </div>

          <div class="col-md-3">
            <label for="programme_id" class="form-label">
              <i class="fas fa-graduation-cap me-2"></i>Programme
            </label>
            <select id="programme_id" name="programme" class="form-control">
              <option value="">Select Programme...</option>
              @foreach($programmes as $prog)
                <option value="{{ $prog->id }}" data-year="{{ $prog->financial_year }}">
                  {{ $prog->code }} – {{ Str::limit($prog->title, 50) }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="col-md-3">
    <label for="status-filter" class="form-label">Status</label>
    <select id="status-filter" class="form-control">
        <option value="">All</option>
        <option value="pending">Pending</option>
        <option value="confirm">Confirm</option>
        <option value="regret">Regret</option>
        <option value="cancel">Cancel</option>
    </select>
</div>

        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-body">
        <div class="table-container table-responsive">
          <table id="nomination-table" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>ID</th>
                <th>Agency Type</th>
                <th>Agency</th>
                <th>Nomination Date</th>
                <th>Rate/Person</th>
                <th>Participant Name</th>
                <th>Email</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <!-- Loaded via DataTables -->
            </tbody>
          </table>
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

   const canEditNomination   = @json(Auth::guard('admin')->user()->hasAccess('nomination', 'edit'));
  const canDeleteNomination = @json(Auth::guard('admin')->user()->hasAccess('nomination', 'delete'));

  $(document).ready(function () {
    var table = $('#nomination-table').DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url: "{{ route('nomination.getData') }}",
        data: function(d) {
          d.programme = $('#programme_id').val();
          d.financial = $('#cal_year').val();
          d.programme_code = $('#programme_code').val();
                 d.status = $('#status-filter').val();
        }
      },
      columns: [
        { data: 'id', name: 'id' },
        { data: 'agency_type_name', name: 'agency_type.name' },
        { data: 'agency_name', name: 'agency.name' },
        { data: 'nomination_date', name: 'nomination_date' },
        { data: 'rate_per_person', name: 'rate_per_person' },
        {
          data: 'participants_display',
          name: 'participants.name',
          orderable: false,
          searchable: true
        },
        {
          data: 'emails_display',
          name: 'participants.email',
          orderable: false,
          searchable: true
        },
        {
          data: 'id',
          name: 'actions',
          orderable: false,
          searchable: false,
          render: function(data, type, row) {
            return `
              <div class="btn-group">
                <button class="btn btn-danger btn-action delete-btn" data-id="${data}">
                  <i class="fas fa-trash"></i>
                </button>
              </div>
            `;
          }
        }
      ]
    });

    // Trigger reload on filter change
    $('#cal_year, #programme_id, #programme_code').on('change keyup', function () {
      table.ajax.reload();
    });
    $('#status-filter').on('change', function () {
    table.ajax.reload();
});


    // Handle delete
    $('#nomination-table').on('click', '.delete-btn', function() {
      var id = $(this).data('id');
      if (confirm('Are you sure you want to delete this nomination?')) {
        $.ajax({
          url: '/admin/nominations/delete/' + id,
          type: 'POST',
          data: {
            _token: "{{ csrf_token() }}"
          },
          success: function(response) {
            if (response.success) {
              table.ajax.reload();
              alert('Nomination deleted successfully');
            } else {
              alert('Error deleting nomination');
            }
          },
          error: function() {
            alert('Error deleting nomination');
          }
        });
      }
    });
  });
</script>
@endsection
