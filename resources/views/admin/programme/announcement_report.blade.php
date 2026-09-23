@extends('admin.layouts.master')
@section('main-section')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Announcement Mail Report</h3>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Financial Year</label>
                            <select id="filter_financial_year" class="form-control">
                                <option value="">All</option>
                                @foreach ($financialYears as $fy)
                                    <option value="{{ $fy }}">{{ $fy }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Agency</label>
                            <select id="agency-filter" name="agency_type_id" class="form-control">
                                <option value="">All Agency Types</option>
                                @foreach ($agencyTypes as $agencyType)
                                    <option value="{{ $agencyType->id }}">{{ $agencyType->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 d-flex align-items-end">
                            <button id="btnFilter" class="btn btn-primary">Filter</button>
                            <button id="btnReset" class="btn btn-secondary ms-2">Reset</button>
                        </div>
                    </div>

                    <table id="announcementLogsTable" class="table table-bordered table-striped w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Programme</th>
                                <th>Unique ID</th>
                                <th>Financial Year</th>
                                <th>Agency</th>
                                <th>Email</th>
                                <th>Subject</th>
                                <th>Sent At</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ url('/admin') }}/assets/js/plugin/datatables/datatables.min.js"></script>

    <script>
        $(function() {
            var table = $('#announcementLogsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.announcement.logs.data') }}",
                    data: function(d) {
                        d.financial_year = $('#filter_financial_year').val();
                        d.agency_type_id = $('#agency-filter').val();
                    }
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'programme_title',
                        name: 'programme_title'
                    },
                    {
                        data: 'unique_id',
                        name: 'unique_id'
                    },
                    {
                        data: 'financial_year',
                        name: 'financial_year'
                    },
                    {
                        data: 'agency_name',
                        name: 'agency_name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'subject',
                        name: 'subject'
                    },
                    {
                        data: 'sent_at_formatted',
                        name: 'sent_at_formatted'
                    },
                ]
            });

            $('#btnFilter').on('click', function() {
                table.ajax.reload();
            });

            $('#btnReset').on('click', function() {
                $('#filter_financial_year').val('');
                $('#filter_agency_id').val('');
                table.ajax.reload();
            });
        });
    </script>
@endsection
