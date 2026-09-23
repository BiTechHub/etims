@extends('admin.layouts.master')
@section('title', 'Agency Group')

@push('styles')
<style>
    /* (Your existing styles) */
    .container-wrapper {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        width: 100%;
        box-sizing: border-box;
        font-size: 18px !important;
    }

    /* Sidebar Styling */
    .sidebar {
        flex: 0 2 250px;
        background:#349e4c;
        padding: 20px;
        border-radius: 16px;
        color: white;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        box-sizing: border-box;
    }

    .sidebar-title {
        font-size: 22px;
        font-weight: bold;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .sidebar-title i {
        font-size: 24px;
        color: #25ba43;
    }

    /* Form Styling */
    .form-container {
        margin:20px !important;
        flex: 1 1 auto;
        background: #fff;
        padding: 20px;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        box-sizing: border-box;
        margin-left: 20px;
    }

    .form-container:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
    }

    .form-title {
    display: flex;
    align-items: center;
    justify-content: flex-start; /* aligns content to the left */
    font-size: 24px;
    font-weight: 700;
    color: #fff;
    padding: 20px;
    margin-bottom: 30px;
    background: linear-gradient(135deg, #1d976c, #027630);
    border-radius: 12px;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.15);
    transition: background 0.3s ease;
}

.form-title::before {
    content: "📝";
    font-size: 26px;
    margin-right: 10px;
}

.form-title:hover {
    background: linear-gradient(135deg, #1488cc, #2b32b2);
}



    /* Table Styling (table style starts here) */
    /* Table Styling */
.table-container {
    margin: 40px auto;
    padding: 20px;
    border-radius: 16px;
   
    font-size:16px;
    background: #ffffff;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    text-align: center; /* Ensures the table content is centered */
}

#agency-group-table {
    width: 100%;
    border-radius: 12px;
    overflow: hidden;
    margin: 0 auto; /* Ensures table is centered within the container */
}

/* Table Header */
#agency-group-table thead {
    background: linear-gradient(135deg, #2c3e50, #4a6491);
    color: white;
    font-weight:bold;
}

#agency-group-table thead th {
    padding: 15px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border: none;
    position: relative;
    text-align: center; /* Center the header text */
}

/* Table Body */
#agency-group-table tbody tr {
    transition: all 0.2s ease;
    background: white;
}

#agency-group-table tbody tr:nth-child(even) {
    background: #f9fafc;
}

#agency-group-table tbody tr:hover {
    background: #f1f7fe;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

#agency-group-table tbody td {
    padding: 12px 15px;
    border-bottom: 1px solid #eef2f7;
    vertical-align: middle;
    text-align: center; /* Center the content in the table cells */
}

    #agency-group-table thead {
        background: linear-gradient(135deg, #2c3e50, #4a6491);
        color: white;
    }

    #agency-group-table thead th {
        padding: 15px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: none;
        position: relative;
    }

    #agency-group-table thead th:not(:last-child)::after {
        content: "";
        position: absolute;
        right: 0;
        top: 25%;
        height: 50%;
        width: 1px;
        background: rgba(255, 255, 255, 0.2);
    }

    #agency-group-table tbody tr {
        transition: all 0.2s ease;
        background: white;
    }

    #agency-group-table tbody tr:nth-child(even) {
        background: #f9fafc;
    }

    #agency-group-table tbody tr:hover {
        background: #f1f7fe;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    #agency-group-table tbody td {
        padding: 12px 15px;
        border-bottom: 1px solid #eef2f7;
        vertical-align: middle;
    }

    /* Badge Styling */
    .badge {
        padding: 6px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 0.3px;
    }

    .badge-success {
        background-color: #28a745;
    }

    .badge-danger {
        background-color: #dc3545;
    }

    /* Button Styling */
    .btn-action {
        padding: 6px 12px;
        font-size: 13px;
        border-radius: 6px;
        margin: 2px;
        transition: all 0.2s;
    }

    .btn-action:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .btn-primary {
        background-color: #3490dc;
        border-color: #3490dc;
    }

    .btn-danger {
        background-color: #e3342f;
        border-color: #e3342f;
    }
    .alert {
    position: relative;
    padding: 0.75rem 1.25rem;
    margin-bottom: 1rem;
    border: 1px solid transparent;
    border-radius: 0.25rem;
    transition: opacity 0.15s linear;
}

.alert-success {
    color: #155724;
    background-color: #d4edda;
    border-color: #c3e6cb;
}

.alert-danger {
    color: #721c24;
    background-color: #f8d7da;
    border-color: #f5c6cb;
}

.alert-dismissible {
    padding-right: 4rem;
}

.alert-dismissible .close {
    position: absolute;
    top: 0;
    right: 0;
    padding: 0.75rem 1.25rem;
    color: inherit;
}

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        #agency-group-table thead th {
            padding: 12px 8px;
            font-size: 14px;
        }

        #agency-group-table tbody td {
            padding: 10px 8px;
            font-size: 14px;
        }

        .btn-action {
            padding: 4px 8px;
            font-size: 12px;
        }
    }

    .sidebar {
        width: 100%;
        text-align: center;
    }

    .form-container {
        margin-left: 0;
    }
</style>
@endpush

@section('content')
<div class="container-wrapper">
  

    <div class="form-container" >
        <h2 class="form-title" id="form-title">➕ Add Agency Group</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
        <div class="alert alert-success" id="alert-container" >
            {{ session('success') }}
        </div>
    @endif
    
  

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    

        <form id="agency-group-form" action="{{ route('agency.group.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-4 form-group">
                    <label for="code" class="form-label">Code <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="code" name="code" placeholder="Enter Code" value="{{ old('code') }}" required>
                </div>
                <div class="col-md-8 form-group">
                    <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="{{ old('name') }}" required>
                </div>
            </div>
            <button type="submit" class="btn btn-submit w-100">Save Agency Group</button>
        </form>
    </div>
</div>

<div class="table-container">
    <table id="agency-group-table" class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Code</th>
                <th>Name</th>
                <th>Active Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
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
                url: '{{ route("agency.group.getData") }}',
                type: 'GET'
            },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'code', name: 'code' },
                { data: 'name', name: 'name' },
                {
                    data: 'is_active',
                    name: 'is_active',
                    render: function(data, type, row) {
                        return data ? 
                            `<button class="btn btn-sm btn-success toggle-status" data-id="${row.id}" data-status="1">Active</button>` : 
                            `<button class="btn btn-sm btn-danger toggle-status" data-id="${row.id}" data-status="0">Inactive</button>`;
                    }
                },
                { 
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return ` 
                            <div class="btn-group">
                                <button class="btn btn-sm btn-primary btn-action edit-btn" data-id="${row.id}" data-code="${row.code}" data-name="${row.name}">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="btn btn-sm btn-danger delete-btn btn-action" data-id="${row.id}">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                        `;
                    }
                }
            ],
            responsive: true
        });
         // Handle toggle status click
$('#agency-group-table').on('click', '.toggle-status', function () {
    const button = $(this);
    const id = button.data('id');
    const currentStatus = parseInt(button.data('status')); // Ensure integer value
    const newStatus = currentStatus === 1 ? 0 : 1; // Toggle status

    $.ajax({
        url: `{{ url('admin/agency-group/toggle-status') }}/${id}`,
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            status: newStatus
        },
        success: function (response) {
            showAlert('success', response.message);

            // Toggle button appearance and text
            button
                .toggleClass('btn-success btn-danger')
                .text(newStatus === 1 ? 'Active' : 'Inactive')
                .data('status', newStatus);

            table.ajax.reload(); // Ensure table is refreshed
        },
        error: function (xhr) {
            showAlert('danger', xhr.responseJSON.message || 'Failed to update status');
        }
    });
});

        // Handle edit button click
        $('#agency-group-table').on('click', '.edit-btn', function() {
            const id = $(this).data('id');
            const code = $(this).data('code');
            const name = $(this).data('name');

            // Change form title to "Edit"
            $('#form-title').text('✏️ Edit Agency Group');

            // Update the form action to point to the update route
            $('#agency-group-form').attr('action', '{{ url("admin/agency-group/edit") }}/' + id);

            // Pre-fill the form with the existing values
            $('#code').val(code);
            $('#name').val(name);

            // Change the form method to PUT for editing
            $('#agency-group-form').append('<input type="hidden" name="_method" value="POST">');
        });

  // Handle delete button click
// Handle delete button click
$('#agency-group-table').on('click', '.delete-btn', function() {
    var id = $(this).data('id');

    if (confirm('Are you sure you want to delete this agency group?')) {
        $.ajax({
            url: '/admin/agency-group/delete/' + id,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                alert('Deleted!');
                $('#agency-group-table').DataTable().ajax.reload(null, false);
            },
            error: function(xhr) {
                alert('Error deleting agency group');
            }
        });
    }
});


// Add this helper function if you don't already have it
function showAlert(type, message) {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    `;
    
    // Append to your alert container
    $('#alert-container').html(alertHtml);
    
    // Auto-dismiss after 5 seconds
    setTimeout(() => {
        $('.alert').alert('close');
    }, 3000);
}

    });
</script>
@endpush
