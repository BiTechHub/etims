@extends('admin.layouts.master')
@section('main-section')

<link rel="stylesheet" href="{{ asset('assets/summernote/summernote-lite.css') }}">

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container">
  <div class="page-inner">
    <div class="page-header">
      <h3 class="fw-bold mb-3">Add Class Room</h3>
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
          <a href="#">Master</a>
        </li>
        <li class="separator">
          <i class="icon-arrow-right"></i>
        </li>
        <li class="nav-item">
          <a href="#">Add Class Room</a>
        </li>
      </ul>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <h1 id="form-title"></h1>
       <form id="agency-group-form" action="{{ route('class.store') }}" method="POST">
            @csrf
            <div class="row">
               
                <div class="col-md-8 form-group">
                    <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="{{ old('name') }}" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Save class room</button>
        </form>
            </div>
          
        </div>
      </div>
      <div class="card">
     <div class="card-body">
<div class="table-container table-responsive">
     <table id="agency-group-table" class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>ID</th>
              
                <th>Name</th>
                <th>Active Status</th>
                <th>Action</th>
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
                url: '{{ route("class.getData") }}',
                type: 'GET',
                dataSrc: function(json) {
                    console.log("Received data:", json);
                    return json.data;
                },
                error: function(xhr, error, thrown) {
                    console.error("AJAX Error:", xhr.responseText);
                    showAlert('danger', 'Failed to load data');
                }
            },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name' },
                {
                    data: 'is_active',
                    name: 'is_active',
                    render: function(data, type, row) {
                        return data ? 
                            '<button class="btn btn-sm btn-success toggle-status" data-id="'+row.id+'" data-status="1">Active</button>' : 
                            '<button class="btn btn-sm btn-danger toggle-status" data-id="'+row.id+'" data-status="0">Inactive</button>';
                    }
                },
                { 
                    data: 'id',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return '<div class="btn-group">'+
                            '<button class="btn btn-sm btn-primary edit-btn" data-id="'+data+'" data-name="'+(row.name || '')+'">'+
                                '<i class="fas fa-edit"></i> Edit'+
                            '</button>'+
                            '<button class="btn btn-sm btn-danger delete-btn" data-id="'+data+'">'+
                                '<i class="fas fa-trash"></i> Delete'+
                            '</button>'+
                        '</div>';
                    }
                }
            ],
            responsive: true
        });

        // Prevent default form submission and handle via AJAX
       

        // Handle toggle status click
        $('#agency-group-table').on('click', '.toggle-status', function(e) {
            e.preventDefault();
            const button = $(this);
            const id = button.data('id');
            const currentStatus = parseInt(button.data('status'));
            const newStatus = currentStatus === 1 ? 0 : 1;

            $.ajax({
                url: '/admin/class/toggle-status/' + id,
                type: 'POST',
                data: {
                    status: newStatus
                },
                success: function(response) {
                    alert('success', response.message || 'Status updated successfully');
                    table.ajax.reload(null, false);
                },
                error: function(xhr) {
                    alert('danger', xhr.responseJSON?.message || 'Failed to update status');
                }
            });
        });

        // Handle edit button click
        $('#agency-group-table').on('click', '.edit-btn', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            const name = $(this).data('name');

            $('#form-title').text('✏️ Edit Class Room');
            $('#agency-group-form').attr('action', '/admin/class/edit/' + id);
            $('#name').val(name);
            $('#agency-group-form').find('input[name="_method"]').remove();
            $('#agency-group-form').append('<input type="hidden" name="_method" value="POST">');
        });

        // Handle delete button click
        $('#agency-group-table').on('click', '.delete-btn', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            const row = $(this).closest('tr');
            const rowIndex = table.row(row).index();

            if (confirm('Are you sure you want to delete this class room?')) {
                $.ajax({
                    url: '/admin/class/delete/' + id,
                    type: 'POST',
                    data: {
                        _method: 'post'
                    },
                    success: function(response) {
                        // Remove the row visually immediately
                        table.row(row).remove().draw(false);
                        
                        // Show success message
                        showAlert('success', response.message || 'Deleted successfully');
                    },
                    error: function(xhr) {
                        showAlert('danger', xhr.responseJSON?.message || 'Error deleting class room');
                    }
                });
            }
        });


        // Alert helper function
        function showAlert(type, message) {
            const alertId = 'alert-' + Date.now();
            const alertHtml = `
                <div id="${alertId}" class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            `;
            
            $('.alert-dismissible').alert('close');
            $('.container-wrapper').prepend(alertHtml);
            
            setTimeout(() => {
                $('#' + alertId).alert('close');
            }, 5000);
        }
    });
</script>


@endsection
