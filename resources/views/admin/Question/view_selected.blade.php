@extends('admin.layouts.master')
@section('main-section')
<link rel="stylesheet" href="{{ asset('assets/summernote/summernote-lite.css') }}">
<link rel="stylesheet" href="{{ asset('assets/sweetalert2/sweetalert2.min.css') }}">


<div class="container">
  <div class="page-inner">
    <div class="page-header">
      <h3 class="fw-bold mb-3">Selected Questions</h3>
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
          <a href="#">Question Management</a>
        </li>
        <li class="separator">
          <i class="icon-arrow-right"></i>
        </li>
        <li class="nav-item">
          <a href="#">Selected Questions</a>
        </li>
      </ul>
    </div>
    <div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0"><i class="fas fa-search me-2"></i>Search Filters</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="financial_year" class="form-label">
                        <i class="fas fa-calendar-alt me-2"></i>Financial Year
                    </label>
                    <select id="financial_year" class="form-select">
                        <option value="">-- Select Year --</option>
                        @foreach ($financialYears as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="programme" class="form-label">
                        <i class="fas fa-graduation-cap me-2"></i>Programme
                    </label>
                    <select id="programme" class="form-select" disabled>
                        <option value="">-- Select Programme --</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

    <div class="row">
    
      <div class="card">
     <div class="card-body">
<div class="table-container table-responsive">
    <table id="marks-table" class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Questions</th>
                        <th>Action</th>
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

<script src="{{ asset('assets/sweetalert2/sweetalert2.all.min.js') }}"></script>
<script>
    $(function () {
        const financialYearSelect = $('#financial_year');
        const programmeSelect = $('#programme');
        const loading = $('#loading-indicator');
        const tableElem = $('#marks-table');

        const table = tableElem.DataTable({
            searching: false,
            paging: false,
            info: false,
            columns: [
                { data: 'index' },
                { data: 'question' },
                { data: 'action', orderable: false, searchable: false }
            ],
            language: {
                emptyTable: '<div class="empty-table-message"><i class="fas fa-clipboard-list fa-2x mb-3"></i><p>Select financial year and programme to view marks</p></div>'
            }
        });

        financialYearSelect.change(function () {
            const year = $(this).val();
            programmeSelect.prop('disabled', true).html('<option value="">Loading...</option>');
            table.clear().draw();

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
                    error: function () {
                        loading.hide();
                        Swal.fire('Error', 'Failed to load programmes.', 'error');
                    }
                });
            }
        });

        programmeSelect.change(function () {
            const programmeId = $(this).val();

            if (programmeId) {
                loading.show();
                $.ajax({
                    url: '{{ route("getselectedquestion") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        programme_id: programmeId
                    },
                    success: function (response) {
                        const rows = response.map((item, index) => ({
                            index: index + 1,
                            question: item.question,
                            action: `<button class="btn-delete" data-id="${item.id}"><i class="fas fa-trash-alt"></i> Delete</button>`
                        }));
                        table.clear().rows.add(rows).draw();
                        loading.hide();
                    },
                    error: function () {
                        loading.hide();
                        Swal.fire('Error', 'Failed to load questions.', 'error');
                    }
                });
            } else {
                table.clear().draw();
            }
        });

        $(document).on('click', '.btn-delete', function () {
            const button = $(this);
            const questionId = button.data('id');
            const row = button.closest('tr');

            Swal.fire({
                title: 'Are you sure?',
                text: "This will delete the question.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    loading.show();
                    $.ajax({
                        url: `/admin/questions/${questionId}`,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            loading.hide();
                            if (response.success) {
                                table.row(row).remove().draw();
                                Swal.fire('Deleted!', 'Question removed.', 'success');
                            } else {
                                Swal.fire('Error', response.message || 'Failed to delete.', 'error');
                            }
                        },
                        error: function () {
                            loading.hide();
                            Swal.fire('Error', 'An error occurred while deleting.', 'error');
                        }
                    });
                }
            });
        });
    });
</script>

@endsection
