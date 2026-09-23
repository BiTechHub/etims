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

    // =========================
    // DATATABLE
    // =========================
    const table = tableElem.DataTable({
        searching: false,
        paging: false,
        info: false,

        columns: [
            {
                data: 'index',
                orderable: false,
                searchable: false
            },
            {
                data: 'question',
                defaultContent: 'N/A'
            },
            {
                data: 'action',
                orderable: false,
                searchable: false
            }
        ],

        language: {
            emptyTable:
                '<div class="empty-table-message">' +
                '<i class="fas fa-clipboard-list fa-2x mb-3"></i>' +
                '<p>Select financial year and programme to view questions</p>' +
                '</div>'
        }
    });


    // =========================
    // FINANCIAL YEAR CHANGE
    // =========================
    financialYearSelect.on('change', function () {

        const year = $(this).val();

        console.log('Selected Financial Year:', year);

        // Reset programme
        programmeSelect
            .prop('disabled', true)
            .html('<option value="">Loading...</option>');

        // Clear table
        table.clear().draw();

        if (!year) {

            programmeSelect
                .html('<option value="">-- Select Programme --</option>')
                .prop('disabled', true);

            return;
        }

        loading.show();

        $.ajax({

            url: '{{ route("getProgrammesByYear") }}',

            type: 'POST',

            data: {
                _token: '{{ csrf_token() }}',
                year: year
            },

            success: function (response) {

                console.log('Programmes Response:', response);

                programmeSelect.html(
                    '<option value="">-- Select Programme --</option>'
                );

                if (response && response.length > 0) {

                    response.forEach(function (programme) {

                        console.log(
                            'Programme:',
                            programme.id,
                            programme.title
                        );

                        programmeSelect.append(
                            $('<option>', {
                                value: programme.id,
                                text: programme.title
                            })
                        );
                    });

                    programmeSelect.prop('disabled', false);

                } else {

                    programmeSelect
                        .html('<option value="">No Programme Found</option>')
                        .prop('disabled', true);

                    Swal.fire({
                        icon: 'info',
                        title: 'No Programme',
                        text: 'Selected financial year me koi programme nahi mila.'
                    });
                }

                loading.hide();
            },

            error: function (xhr) {

                console.log(
                    'getProgrammesByYear ERROR:',
                    xhr.responseText
                );

                loading.hide();

                programmeSelect
                    .html('<option value="">-- Select Programme --</option>')
                    .prop('disabled', true);

                Swal.fire(
                    'Error',
                    'Failed to load programmes.',
                    'error'
                );
            }
        });
    });


    // =========================
    // PROGRAMME CHANGE
    // =========================
    programmeSelect.on('change', function () {

        const programmeId = $(this).val();

        console.log('================================');
        console.log('SELECTED PROGRAMME ID:', programmeId);
        console.log('================================');

        table.clear().draw();

        if (!programmeId) {
            return;
        }

        loading.show();

        $.ajax({

            url: '{{ route("getselectedquestion") }}',

            type: 'POST',

            data: {
                _token: '{{ csrf_token() }}',
                programme_id: programmeId
            },

            success: function (response) {

                console.log('Selected Questions Response:', response);

                /*
                 * Controller response:
                 *
                 * {
                 *     success: true,
                 *     data: [...]
                 * }
                 */

                if (
                    response &&
                    response.success === true &&
                    Array.isArray(response.data)
                ) {

                    if (response.data.length === 0) {

                        console.log(
                            'No questions found for programme:',
                            programmeId
                        );

                        table.clear().draw();

                        loading.hide();

                        return;
                    }


                    const rows = response.data.map(function (item, index) {

                        console.log('Question:', item);

                        return {

                            index: index + 1,

                            question: item.question || 'Question Not Found',

                            action:
                                '<button ' +
                                'type="button" ' +
                                'class="btn btn-danger btn-sm btn-delete" ' +
                                'data-id="' + item.id + '">' +
                                '<i class="fas fa-trash-alt"></i> Delete' +
                                '</button>'
                        };
                    });


                    console.log('DataTable Rows:', rows);

                    table
                        .clear()
                        .rows
                        .add(rows)
                        .draw();

                } else {

                    console.log(
                        'Invalid response format:',
                        response
                    );

                    Swal.fire(
                        'Error',
                        'Invalid response received from server.',
                        'error'
                    );
                }

                loading.hide();
            },

            error: function (xhr) {

                console.log(
                    'getselectedquestion ERROR:',
                    xhr.responseText
                );

                console.log(
                    'HTTP Status:',
                    xhr.status
                );

                loading.hide();

                Swal.fire(
                    'Error',
                    'Failed to load questions.',
                    'error'
                );
            }
        });
    });


    // =========================
    // DELETE QUESTION
    // =========================
    $(document).on('click', '.btn-delete', function () {

        const button = $(this);

        // This is ExamPaper ID
        const examPaperId = button.data('id');

        const row = button.closest('tr');

        console.log(
            'Delete ExamPaper ID:',
            examPaperId
        );


        Swal.fire({

            title: 'Are you sure?',

            text: 'This will remove the selected question from this programme.',

            icon: 'warning',

            showCancelButton: true,

            confirmButtonText: 'Yes, delete it!',

            cancelButtonText: 'Cancel'

        }).then(function (result) {

            if (!result.isConfirmed) {
                return;
            }

            loading.show();

            $.ajax({

                // IMPORTANT:
                // examPaperId is ExamPaper ID,
                // not Question ID.
                url: '/admin/exam-papers/' + examPaperId,

                type: 'DELETE',

                data: {
                    _token: '{{ csrf_token() }}'
                },

                success: function (response) {

                    console.log(
                        'Delete Response:',
                        response
                    );

                    loading.hide();

                    if (response.success) {

                        table
                            .row(row)
                            .remove()
                            .draw();

                        Swal.fire(
                            'Deleted!',
                            'Question removed from programme.',
                            'success'
                        );

                    } else {

                        Swal.fire(
                            'Error',
                            response.message || 'Failed to delete.',
                            'error'
                        );
                    }
                },

                error: function (xhr) {

                    console.log(
                        'Delete ERROR:',
                        xhr.responseText
                    );

                    loading.hide();

                    Swal.fire(
                        'Error',
                        'An error occurred while deleting.',
                        'error'
                    );
                }
            });
        });
    });

});
</script>

@endsection
