@extends('admin.layouts.master')
@section('main-section')
<link rel="stylesheet" href="{{ asset('assets/summernote/summernote-lite.css') }}">
<div class="container">
  <div class="page-inner">
    <div class="page-header">
      <h3 class="fw-bold mb-3">Room Occupancy</h3>
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
          <a href="#">Room Occupancy</a>
        </li>
      </ul>
    </div>
    <div class="row">
     <div class="row">
      <div class="card">
    <div class="card-body">
        <div class="row">
            <!-- Block Selection -->
            <div class="col-md-6 mb-3">
                <label for="blocks" class="form-label">
                    <i class="fas fa-calendar-alt me-2"></i>Select Block
                </label>
                <select name="block_id" id="blocks" class="form-control" required>
                    <option value="">-- Select block --</option>
                    @foreach ($blocks as $block)
                        <option value="{{ $block->id }}">{{ $block->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Download Button -->
        <div class="row">
            <div class="col-md-12">
                <button class="btn btn-primary mt-2" id="btn-download" type="button">
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
     <table id="agency-group-table" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                  <th>Block</th>
                                   <th>Room</th> 
                                   <th>Type</th>
                                   <th>No of vaccant Beds</th>
                                    
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
<script src="{{asset('admin/assets/js/plugin/xlsx.full.min.js')}}"></script>
<script src="{{ asset('assets/summernote/summernote-lite.js') }}"></script>

 <script type="text/javascript">
    $(document).ready(function() {
        // CSRF Token setup for AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Initialize DataTable
        const table = $('#agency-group-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("admin.room-occupancy.data") }}',
                type: 'GET',
                data: function(d) {
                    d.block_id = $('#blocks').val();
                }
            },
            columns: [
                { data: 'block.name', name: 'block.name' },
                { data: 'room_number', name: 'room_number' },
                { data: 'type.types_of_rooms', name: 'type.name' },
                { data: 'bed_count', name: 'bed_count' }
            ],
            responsive: true,
            deferLoading: true
        });

        $('#blocks').change(function() {
            const blockId = $(this).val();
            if (blockId) {
                table.ajax.reload();
            } else {
                table.clear().draw();
            }
        });

        // Download Excel
       $('#btn-download').click(function () {
    const table = document.getElementById('agency-group-table');
    if (table) {
        const workbook = XLSX.utils.table_to_book(table, { sheet: "RoomOccupancy" });
        XLSX.writeFile(workbook, 'RoomOccupancyReport.xlsx');
    } else {
        alert('Table not found!');
    }
});

    });
</script>


@endsection
