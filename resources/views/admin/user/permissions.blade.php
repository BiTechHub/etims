@extends('admin.layouts.master')

@section('main-section')
    <div class="container">
        <div class="page-inner">

            <div class="page-header">
                <h3 class="fw-bold mb-3">Manage Access</h3>
                <ul class="breadcrumbs mb-3">
                    <li class="nav-home"><a href="#"><i class="icon-home"></i></a></li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item"><a href="{{ route('user.adduser') }}">Users</a></li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item"><a href="#">Manage Access</a></li>
                </ul>
            </div>

            <div class="card">
                <div class="card-body">

                    <!-- ============ USER INFO — TOP ============ -->
                    <div class="alert alert-info">
                        <strong>Setting access for:</strong>
                        <span class="badge bg-primary" style="font-size: 1rem; padding: 6px 14px;">
                            {{ $user->role }}
                        </span>
                    </div>

                    <form id="permissionsForm">
                        <input type="hidden" id="perm_user_id" value="{{ $user->id }}">

                        <div class="table-responsive">
                            <table class="table table-bordered" id="permTable">
                                <thead>
                                    <tr>
                                        <th>Module</th>
                                        <th class="text-center">View</th>
                                        <th class="text-center">Add</th>
                                        <th class="text-center">Edit</th>
                                        <th class="text-center">Delete</th>
                                    </tr>
                                </thead>
                                <tbody id="permTableBody">
                                 
                                </tbody>
                            </table>
                        </div>

                        <button type="submit" class="btn btn-primary" id="savePermBtn">Save Access</button>
                        <a href="{{ route('user.adduser') }}" class="btn btn-secondary">Back to Users</a>
                    </form>

                </div>
            </div>

        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {

            var userId = $('#perm_user_id').val();

      
            $.ajax({
                url: "{{ url('admin/user/adduser/permissions') }}/" + userId,
                type: 'GET',
                success: function(response) {
                    buildPermTable(response.modules, response.actions, response.permissions);
                },
                error: function() {
                    alert('Failed to load permissions');
                }
            });

            function buildPermTable(modules, actions, currentPermissions) {
                var tbody = $('#permTableBody');
                tbody.empty();

                $.each(modules, function(key, label) {
                    var row = '<tr><td>' + label + '</td>';

                    $.each(actions, function(i, action) {
                        var checked = (currentPermissions[key] && currentPermissions[key][action] ==
                            1) ? 'checked' : '';
                        row += '<td class="text-center">' +
                            '<input type="checkbox" name="permissions[' + key + '][' + action +
                            ']" value="1" ' + checked + '>' +
                            '</td>';
                    });

                    row += '</tr>';
                    tbody.append(row);
                });
            }

            // Submit permissions form
            $('#permissionsForm').on('submit', function(e) {
                e.preventDefault();

                $('#savePermBtn').prop('disabled', true).text('Saving...');

                $.ajax({
                    url: "{{ url('admin/user/adduser/permissions') }}/" + userId,
                    type: 'POST',
                    data: $(this).serialize() + '&_token={{ csrf_token() }}',
                    success: function(response) {
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
