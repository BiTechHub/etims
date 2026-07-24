@extends('admin.layouts.master')
@section('main-section')
<div class="container">
  <div class="page-inner">
    <!-- Page Header -->
    <div class="page-header">
      <h3 class="fw-bold mb-3">Add Menus</h3>
      <ul class="breadcrumbs mb-3">
        <li class="nav-home"><a href="#"><i class="icon-home"></i></a></li>
        <li class="separator"><i class="icon-arrow-right"></i></li>
        <li class="nav-item"><a href="#">Manage Menus</a></li>
        <li class="separator"><i class="icon-arrow-right"></i></li>
        <li class="nav-item"><a href="#">Add Menu</a></li>
      </ul>
    </div>

    <!-- Table & Modal -->
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <div class="d-flex align-items-center">
              <h4 class="card-title">Menu List</h4>
              <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal" data-bs-target="#addRowModal">
                <i class="fa fa-plus"></i> Add Menu
              </button>
            </div>
          </div>
          <div class="card-body">

            <!-- Add Modal -->
            <div class="modal fade" id="addRowModal" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header border-0">
                    <h5 class="modal-title"><span class="fw-mediumbold"> New</span> <span class="fw-light"> Menu </span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                    <p class="small">Create a new menu using this form.</p>
                    <form>
                      <div class="row">
                        <div class="col-sm-12">
                          <div class="form-group form-group-default1">
                            <label>Menu Name</label>
                            <input id="menu" name="menu" type="text" class="form-control" placeholder="English" />
                          </div>
                        </div>
                        {{-- <div class="col-md-12">
                          <div class="form-group form-group-default">
                            <label>Menu Name (Hindi)</label>
                            <input id="menu_hi" name="menu_hi" type="text" class="form-control" placeholder="Hindi" />
                          </div>
                        </div> --}}
                      </div>
                    </form>
                  </div>
                  <div class="modal-footer border-0">
                    <button type="button" id="addRowButton" class="btn btn-primary">Add</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Edit Modal -->
            <div class="modal fade" id="editRowModal" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header border-0">
                    <h5 class="modal-title">Edit Menu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                    <input type="hidden" id="edit_id">
                    <div class="form-group">
                      <label>Menu Name</label>
                      <input id="edit_menu" name="menu" type="text" class="form-control" />
                    </div>
                    {{-- <div class="form-group">
                      <label>Menu Name (Hindi)</label>
                      <input id="edit_menu_hi" name="menu_hi" type="text" class="form-control" />
                    </div> --}}
                  </div>
                  <div class="modal-footer border-0">
                    <button type="button" id="updateRowButton" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
              <table id="add-row" class="display table table-striped table-hover">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Menu Name</th>
                    {{-- <th>Menu (Hindi)</th> --}}
                    <th style="width: 10%">Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($menus as $items)
                  <tr data-id="{{ $items->id }}">
                    <td>{{ $items->id }}</td>
                    <td class="menu_name">{{ $items->menu_name }}</td>
                    {{-- <td class="menu_name_hi">{{ $items->menu_name_hi }}</td> --}}
                    <td>
                      <div class="form-button-action">
                        <button type="button" class="btn btn-link1 btn-primary btn-sm editBtn">
                          <i class="fa fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-link1 btn-sm btn-danger deleteBtn">
                          <i class="fa fa-times"></i>
                        </button>
                      </div>
                    </td>
                  </tr>
                  @endforeach
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
  // Initialize DataTable
  var table = $("#add-row").DataTable({ pageLength: 5 });
  
  function showLoader() {
        $('#loader').show();
    }

    function hideLoader() {
        $('#loader').hide();
    }

  // Add New Menu
  $("#addRowButton").click(function () {
    var menuName = $("#menu").val();
    // var menuNameHi = $("#menu_hi").val();

    $.ajax({
      url: "{{ route('admin.menus_store') }}",
      method: 'POST',
      data: {
        _token: "{{ csrf_token() }}",
        menu: menuName,
        // menu_hi: menuNameHi
      },
      success: function(response) {
        var newRow = table.row.add([
          response.id,
          response.menu_name,
          response.menu_name_hi,
          '<div class="form-button-action">'+
          '<button type="button" class="btn btn-link btn-primary btn-lg editBtn"><i class="fa fa-edit"></i></button>'+
          '<button type="button" class="btn btn-link btn-danger deleteBtn"><i class="fa fa-times"></i></button>'+
          '</div>'
        ]).draw().node();
        $(newRow).attr('data-id', response.id);
        $(newRow).find('td:eq(1)').addClass('menu_name');
        $(newRow).find('td:eq(2)').addClass('menu_name_hi');

        $("#menu").val('');
        // $("#menu_hi").val('');
        $("#addRowModal").modal('hide');
      },
      error: function () {
        alert("Error while adding menu.");
      },
      complete: function () {
          hideLoader();
        }
    });
  });

  // Edit Button Click
  $('#add-row').on('click', '.editBtn', function () {
    var row = $(this).closest('tr');
    var id = row.attr('data-id');
    var menuName = row.find('.menu_name').text();
    var menuNameHi = row.find('.menu_name_hi').text();

    $('#edit_id').val(id);
    $('#edit_menu').val(menuName);
    // $('#edit_menu_hi').val(menuNameHi);
    $('#editRowModal').modal('show');
  });

  // Update Menu
  $('#updateRowButton').click(function () {
    var id = $('#edit_id').val();
    var menuName = $('#edit_menu').val();
    // var menuNameHi = $('#edit_menu_hi').val();

    $.ajax({
      url: "/admin-panel/menus_update/" + id,
      method: 'POST',
      data: {
        _token: "{{ csrf_token() }}",
        menu: menuName,
        // menu_hi: menuNameHi
      },
      success: function (response) {
        var row = $('tr[data-id="' + id + '"]');
        row.find('.menu_name').text(response.menu_name);
        row.find('.menu_name_hi').text(response.menu_name_hi);
        $('#editRowModal').modal('hide');
      },
      error: function () {
        alert("Error while updating menu.");
      },
      complete: function () {
          hideLoader();
        }
    });
  });

  // Delete Button Click
  $('#add-row').on('click', '.deleteBtn', function () {
    if (!confirm("Are you sure you want to delete this menu?")) return;
    var row = $(this).closest('tr');
    var id = row.attr('data-id');

    $.ajax({
      url: "/admin-panel/menus_delete/" + id,
      method: 'POST',
      data: { _token: "{{ csrf_token() }}" },
      success: function () {
        table.row(row).remove().draw();
      },
      error: function () {
        alert("Error while deleting menu.");
      },
      complete: function () {
          hideLoader();
        }
    });
  });

});
</script>
@endsection
