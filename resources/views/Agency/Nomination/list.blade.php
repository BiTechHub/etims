@extends('Agency.layouts.master')
@section('main-section')

<style>
  .table td, .table thead th {
    font-size: 11px;
    white-space: nowrap;
    vertical-align: middle;
    
}
.table>tbody>tr>td, .table>tbody>tr>th {
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
          <div class="card-header py-3">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
    
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

        <!-- Left Side -->
        <div>
            <h5 class="mb-1 fw-bold">
                Nomination List
            </h5>

            <small class="text-muted">
                {{ $programme->title }}
            </small>
        </div>

        <!-- Right Side -->
        <div class="d-flex align-items-center gap-2 flex-wrap">

            <span class="badge bg-success px-3 py-2">
                Sponsor : {{ $programme->sponsor->name }}
            </span>

            @if($programme->sponsor_id != 3)

                <span class="badge bg-danger px-3 py-2">
                    Fee : ₹{{ $programme->fee_structure }} / Per Person
                </span>

                <a href="{{ route('Agency.Nomination.payment') }}?programme={{base64_encode($programme->id) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-credit-card me-1"></i>
                    Pay Now
                </a>

            @endif

        </div>

    </div>

</div>
          <div class="card-body">

          
            <!-- Table -->
            <div class="table-responsive">
            <table id="nomination-table" class="table table-bordered table-hover">
              
        <thead>
    <tr>
        <th>#</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Date</th>
        <th>Designation</th>
        <th>Status</th>
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

    var table = $('#nomination-table').DataTable({
        processing: true,
        serverSide: true,
       ajax: {
        url: "{{ url('agency-panel/nomination-data') }}",
        data: {
            program: "{{ $programme->id }}"
        }
    },

    columns: [
    {
        data: null,
        name: 'serial_no',
        orderable: false,
        searchable: false,
        render: function (data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
        }
    },

    {
        data: 'name',
        name: 'name'
    },

    {
        data: 'email',
        name: 'email'
    },

    {
        data: 'phone',
        name: 'phone'
    },

    {
        data: 'date',
        name: 'date'
    },

    {
        data: 'designation',
        name: 'designation'
    },

    {
        data: 'status',
        name: 'status',
        orderable: false,
        searchable: false
    }
]
        
        
    });

    // ✅ ADD THIS BLOCK HERE (IMPORTANT)
    table.on('xhr', function () {
        var json = table.ajax.json();

        // if (json.data.length > 0) {
        //     let sponsorId = json.data[0].sponsor_id;

        //     if (sponsorId == 3) {
        //         table.column(5).visible(false); // Rate
        //         table.column(8).visible(false); // Status
        //     } else {
        //         table.column(5).visible(true);
        //         table.column(8).visible(true);
        //     }
        // }
    });

});

    
</script>
@endsection
