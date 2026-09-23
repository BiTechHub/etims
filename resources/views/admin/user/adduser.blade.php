@extends('admin.layouts.master')

@section('main-section')

 <link rel="stylesheet" href="{{ asset('assets/summernote/summernote-lite.css') }}">

    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Add User</h3>
                <ul class="breadcrumbs mb-3">
                    <li class="nav-home"><a href="#"><i class="icon-home"></i></a></li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item"><a href="#">Add User</a></li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                </ul>
            </div>

            <h1 id="form-title"></h1>

            <div class="row">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">

                            <form id="user-form" action="{{ route('user.adduser.store') }}" method="POST">
                                @csrf
                                <input type="hidden" id="user_id" name="user_id">

                                <div class="row">
                                    <div class="col-md-4 form-group">
                                        <label for="user_type">User Type <span class="text-danger">*</span></label>
                                        <select id="user_type" class="form-control" name="user_type" required>
                                            <option value="">-- Select User Type --</option>
                                            @foreach ($userTypes as $type)
                                                <option value="{{ $type }}"
                                                    {{ old('user_type') == $type ? 'selected' : '' }}>
                                                    {{ $type }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="username">Username <span class="text-danger">*</span></label>
                                        <input type="text" id="username" name="username" class="form-control"
                                            placeholder="Enter Username" required>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="name" class="form-label">Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            placeholder="Enter Name" value="{{ old('name') }}" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 form-group">
                                        <label for="email">E-mail ID <span class="text-danger">*</span></label>
                                        <input type="email" id="email" name="email" class="form-control"
                                            placeholder="Enter Email ID" required value="{{ old('email') }}">
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="password">Password <span class="text-danger"
                                                id="password-star">*</span></label>
                                        <input type="password" id="password" name="password" class="form-control"
                                            placeholder="Enter Password">
                                        <small class="text-muted" id="passwordHint" style="display:none;">
                                            Leave blank if you don't want to change it
                                        </small>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="password_confirmation">Confirm Password <span class="text-danger"
                                                id="confirm-star">*</span></label>
                                        <input type="password" id="password_confirmation" name="password_confirmation"
                                            class="form-control" placeholder="Confirm Password">
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary" id="saveBtn">Save User</button>
                                <button type="button" class="btn btn-secondary" id="cancelEditBtn"
                                    style="display:none;">Cancel</button>
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
                                        <th>User Type</th>
                                        <th>User Name</th>
                                        <th>Email</th>
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

    <script>
        $(document).ready(function() {

            var table = $('#agency-group-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('user.adduser.getData') }}",
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'user_type', name: 'user_type' },
                    { data: 'username', name: 'username' },
                    { data: 'email', name: 'email' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ]
            });

            // Edit button click — populate the form with row data
            $(document).on('click', '.edit-btn', function() {
                let d = $(this).data();

                $('#user_id').val(d.id);
                $('#user_type').val(d.user_type);
                $('#username').val(d.username);
                $('#name').val(d.name);
                $('#email').val(d.email);

                $('#password').val('').removeAttr('required');
                $('#password_confirmation').val('').removeAttr('required');
                $('#password-star, #confirm-star').hide();
                $('#passwordHint').show();

                $('#form-title').text('Edit User');
                $('#saveBtn').text('Update User');
                $('#cancelEditBtn').show();

                $('#user-form').attr('action', "{{ url('admin/user/adduser/edit') }}/" + d.id);

                $('html, body').animate({
                    scrollTop: $('#user-form').offset().top - 100
                }, 300);
            });

            // Cancel edit — reset the form back to add mode
            $('#cancelEditBtn').on('click', function() {
                resetForm();
            });

            function resetForm() {
                $('#user-form')[0].reset();
                $('#user_id').val('');
                $('#password').attr('required', true);
                $('#password_confirmation').attr('required', true);
                $('#password-star, #confirm-star').show();
                $('#passwordHint').hide();
                $('#form-title').text('');
                $('#saveBtn').text('Save User');
                $('#cancelEditBtn').hide();
                $('#user-form').attr('action', "{{ route('user.adduser.store') }}");
            }

            // Delete
            $(document).on('click', '.delete-btn', function() {
                var id = $(this).data('id');
                if (!confirm('Are you sure you want to delete this user?')) return;

                $.ajax({
                    url: "{{ url('admin/user/adduser/delete') }}/" + id,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            table.ajax.reload(null, false);
                            alert('User deleted successfully');
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(xhr) {
                        alert(xhr.responseJSON?.message || 'Error deleting user');
                    }
                });
            });


            
            function buildPermTable(modules, actions, currentPermissions) {
                var tbody = $('#permTableBody');
                tbody.empty();

                $.each(modules, function(key, label) {
                    var row = '<tr><td>' + label + '</td>';

                    $.each(actions, function(i, action) {
                        var checked = (currentPermissions[key] && currentPermissions[key][action] == 1) ? 'checked' : '';
                        row += '<td class="text-center">' +
                            '<input type="checkbox" name="permissions[' + key + '][' + action + ']" value="1" ' + checked + '>' +
                            '</td>';
                    });

                    row += '</tr>';
                    tbody.append(row);
                });
            }

            // Submit permissions form
            $('#permissionsForm').on('submit', function(e) {
                e.preventDefault();

                var id = $('#perm_user_id').val();

                $('#savePermBtn').prop('disabled', true).text('Saving...');

                $.ajax({
                    url: "{{ url('admin/user/adduser/permissions') }}/" + id,
                    type: 'POST',
                    data: $(this).serialize() + '&_token={{ csrf_token() }}',
                    success: function(response) {
                        $('#accessModal').modal('hide');
                        alert('Access updated successfully');
                    },
                    error: function() {
                        alert('Failed to save access');
                    },
                    complete: function() {
                        $('#savePermBtn').prop('disabled', false).text('Save Access');
                    }
                });
            });

        });
    </script>
@endsection