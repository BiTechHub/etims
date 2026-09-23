@extends('faculty.layouts.master')
@section('main-section')

<style>
.table.dataTable td { white-space: nowrap; }
</style>

<div class="container">
  <div class="page-inner">
    <h3 class="fw-bold mb-3">My Programmes</h3>


    <div class="row mb-3 card">
      <div class="card-body">
        <div class="row">
          <div class="col-md-3">
            <label for="cal_year" class="form-label">Calendar Year</label>
            <select class="form-control" id="cal_year" name="cal_year">
              <option value="">Select Calendar Year</option>
              @foreach($distinctYears as $year)
                <option value="{{ $year }}">{{ $year }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-md-3">
            <label for="programme_code" class="form-label">Code</label>
            <input type="text" id="programme_code" class="form-control" placeholder="Enter unique code">
          </div>

          <div class="col-md-3">
            <label for="programme_id" class="form-label">Programme</label>
            <select id="programme_id" class="form-control">
              <option value="">Select Programme...</option>
              @foreach($programmes as $prog)
                <option value="{{ $prog->id }}" data-year="{{ $prog->financial_year }}">
                  {{ Str::limit($prog->title, 50) }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="col-md-3">
            <label for="status-filter" class="form-label">Status</label>
            <select id="status-filter" class="form-control">
              <option value="">All Statuses</option>
              <option value="NotAnnounce">NotAnnounce</option>
              <option value="Announced">Announced</option>
              <option value="Canceled">Canceled</option>
              <option value="Postponed">Postponed</option>
            </select>
          </div>

          <div class="col-md-3">
            <label for="group-filter" class="form-label">Vertical</label>
            <select id="group-filter" class="form-control">
              <option value="">All Groups</option>
              @foreach($groups as $group)
                <option value="{{ $group->id }}">{{ $group->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-md-3">
            <label for="sponsor-filter" class="form-label">Sponsor Type</label>
            <select id="sponsor-filter" class="form-control">
              <option value="">All Sponsors</option>
              @foreach($sponsors as $sponsor)
                <option value="{{ $sponsor->id }}">{{ $sponsor->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-md-3">
            <label for="from_date" class="form-label">From Date</label>
            <input type="date" id="from_date" class="form-control">
          </div>

          <div class="col-md-3">
            <label for="to_date" class="form-label">To Date</label>
            <input type="date" id="to_date" class="form-control">
          </div>

          <div class="col-md-3">
            <label for="location-filter" class="form-label">Location</label>
            <select id="location-filter" class="form-control">
              <option value="">All Locations</option>
              @foreach($locations as $location)
                <option value="{{ $location }}">{{ $location }}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table id="programme-table" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>ID</th>
                <th>Status</th>
                <th>Group Name</th>
                <th>Related Agency Type</th>
                <th>Code</th>
                <th>Programme Title</th>
                <th>Sponsor Type</th>
                <th>Starting date</th>
                <th>End date</th>
                <th>Location</th>
                <th>Programme Directors</th>
              </tr>
            </thead>
            <tbody></tbody>
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
$(document).ready(function () {
    var table = $('#programme-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('ff.programmeManagement.getData') }}",
            data: function (d) {
                d.status = $('#status-filter').val();
                d.group = $('#group-filter').val();
                d.sponsor = $('#sponsor-filter').val();
                d.programme = $('#programme_id').val();
                d.from_date = $('#from_date').val();
                d.to_date = $('#to_date').val();
                d.location = $('#location-filter').val();
            }
        },
        columns: [
            { data: 'id', name: 'id' },
            {
                data: 'status', name: 'status',
                render: function (data) {
                    let badgeClass = 'badge-secondary';
                    if (data === 'Announced') badgeClass = 'badge-success';
                    if (data === 'Canceled') badgeClass = 'badge-danger';
                    if (data === 'Postponed') badgeClass = 'badge-warning';
                    return `<span class="badge ${badgeClass}">${data}</span>`;
                }
            },
            { data: 'group_name', name: 'group_name' },
            { data: 'agency_type', name: 'agency_type' },
            { data: 'unique_id', name: 'unique_id' },
            { data: 'title', name: 'title' },
            { data: 'sponsor_name', name: 'sponsor_name' },
            {
                data: null, name: 'from_date',
                render: function (data, type, row) {
                    const d = new Date(row.from_date);
                    return ('0' + d.getDate()).slice(-2) + '-' + ('0' + (d.getMonth() + 1)).slice(-2) + '-' + d.getFullYear();
                }
            },
            {
                data: null, name: 'to_date',
                render: function (data, type, row) {
                    const d = new Date(row.to_date);
                    return ('0' + d.getDate()).slice(-2) + '-' + ('0' + (d.getMonth() + 1)).slice(-2) + '-' + d.getFullYear();
                }
            },
            { data: 'location', name: 'location' },
            {
                data: null, name: 'dir_name',
                render: function (data, type, row) {
                    const n1 = row.dir_name1 || '', n2 = row.dir_name2 || '';
                    return (n1 && n2) ? `${n1} - ${n2}` : (n1 || n2);
                }
            }
        ],
        responsive: true
    });

    $('#status-filter, #group-filter, #sponsor-filter, #programme_id, #from_date, #to_date, #location-filter')
        .on('change', function () { table.ajax.reload(); });
    $('#programme_code').on('keyup', function () { table.ajax.reload(); });

    const calYearSelect = document.getElementById('cal_year');
    const programmeSelect = document.getElementById('programme_id');
    const allOptions = Array.from(programmeSelect.options).slice(1);
    programmeSelect.disabled = true;

    calYearSelect.addEventListener('change', function () {
        programmeSelect.innerHTML = '<option value="">Select Programme...</option>';
        if (this.value) {
            allOptions.filter(o => o.getAttribute('data-year') === this.value)
                .forEach(o => programmeSelect.appendChild(o));
            programmeSelect.disabled = false;
        } else {
            programmeSelect.disabled = true;
        }
    });
});
</script>
@endsection