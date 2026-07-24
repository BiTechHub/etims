@extends('admin.layouts.master')
@section('main-section')
<div class="container">
  <div class="page-inner">
    <div class="page-header">
      <h3 class="fw-bold mb-3">Add Childmenu</h3>
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
          <a href="#">Manage Childmenu</a>
        </li>
        <li class="separator">
          <i class="icon-arrow-right"></i>
        </li>
        <li class="nav-item">
          <a href="#">Add Childmenu</a>
        </li>
      </ul>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <div class="d-flex align-items-center">
              <h4 class="card-title">Childmenu List</h4>
              <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal" data-bs-target="#addChildmenuModal">
                <i class="fa fa-plus"></i>
                Add Childmenu
              </button>
            </div>
          </div>
          <div class="card-body">
            <!-- Modal for Adding Childmenu -->
            <div class="modal fade" id="addChildmenuModal" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header border-0">
                    <h5 class="modal-title">
                      <span class="fw-mediumbold"> New</span>
                      <span class="fw-light"> Childmenu </span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <form>
                      <div class="row">
                        <div class="col-sm-12">
                          <div class="form-group form-group-default1">
                            <label>Select Parent Menu</label>
                            <select class="form-select" id="parentMenuUrl" name="parentMenuUrl">
                              <option value="">Select Parent Menu</option>
                              @foreach ($menus as $items)
                              <option value="{{ $items->menu_url }}">{{ $items->menu_name }}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="col-sm-12">
                          <div class="form-group form-group-default1">
                            <label>Select Submenu</label>
                            <select class="form-select" id="submenu_url" name="submenu_url">
                            </select>
                          </div>
                        </div>

                        <div class="col-sm-12">
                          <div class="form-group form-group-default1">
                            <label>Childmenu Name</label>
                            <input id="childmenu" name="childmenu" type="text" class="form-control" placeholder="English">
                          </div>
                        </div>

                        {{-- <div class="col-md-12">
                          <div class="form-group form-group-default1">
                            <label>Childmenu Name (Hindi)</label>
                            <input id="childmenu_hi" name="childmenu_hi" type="text" class="form-control" placeholder="Hindi">
                          </div>
                        </div> --}}

                      </div>
                    </form>
                  </div>
                  <div class="modal-footer border-0">
                    <button type="button" id="addChildmenuButton" class="btn btn-primary">Add</button>
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
                    <h5 class="modal-title">Edit Childmenu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                    <input type="hidden" id="edit_id">
                    <div class="form-group">
                      <label>Childmenu Name</label>
                      <input id="edit_menu" name="submenu_name" type="text" class="form-control" />
                    </div>

                    {{-- <div class="form-group">
                      <label>Childmenu Name (Hindi)</label>
                      <input id="edit_menu_hi" name="submenu_hi" type="text" class="form-control" />
                    </div> --}}

                  </div>
                  <div class="modal-footer border-0">
                    <button type="button" id="updateRowButton" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
            
            

            <!-- Table for Displaying Childmenus -->
            <div class="table-responsive">
              <table id="add-row" class="display table table-striped table-hover">
                <thead>
                  <tr>
                    <th>Parent Menu</th>
                    <th>Childmenu Name</th>
                    {{-- <th>Childmenu (Hindi)</th> --}}
                    <th style="width: 10%">Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($childmenus as $items)
                  <tr data-id="{{ $items->id }}">
                    <td>{{ $items->menu_url }}</td>
                    <td class="menu_name">{{ $items->childmenu_name }}</td>
                    {{-- <td class="menu_name_hi">{{ $items->childmenu_name_hi }}</td> --}}
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
<script src="{{ url('/admin') }}/assets/js/plugin/datatables/datatables.min.js"></script>
<script src="{{ url('/admin') }}/assets/js/kaiadmin.min.js"></script>
<script src="{{ url('/admin') }}/assets/js/setting-demo2.js"></script>

<script>
  $(document).ready(function () {
    // Initialize DataTable
    $("#add-row").DataTable({
      pageLength: 15,
    });


    var action =
        '<td><div class="form-button-action"><button type="button" data-bs-toggle="tooltip" title="Edit" class="btn btn-link btn-primary btn-lg"><i class="fa fa-edit"></i></button><button type="button" data-bs-toggle="tooltip" title="Remove" class="btn btn-link btn-danger"><i class="fa fa-times"></i></button></div></td>';

    // Handle Add Childmenu Button Click
    $("#addChildmenuButton").click(function () {
      var parentMenuUrl = $("#parentMenuUrl").val();
      var submenuUrl = $("#submenu_url").val();
      var childmenuName = $("#childmenu").val();
      // var childmenuNameHi = $("#childmenu_hi").val();

      console.log("Sending data:", { parentMenuUrl: parentMenuUrl, submenuUrl: submenuUrl, childmenuName: childmenuName });

      $.ajax({
        url: "{{ route('admin.childmenu_store') }}",
        method: 'POST',
        data: {
          _token: "{{ csrf_token() }}",
          parentMenuUrl: parentMenuUrl,
          submenuUrl: submenuUrl,
          childmenuName: childmenuName,
          // childmenuNameHi: childmenuNameHi
        },
        success: function (response) {
          console.log("Response:", response);
          var newRow = "<tr>" +
              "<td>" + response.parent_menu_name + "</td>" +
              "<td>" + response.childmenu_name + "</td>" +
              // "<td>" + response.childmenu_name_hi + "</td>" +
              action +
              "</tr>";
          $("#add-row tbody").append(newRow);

          $("#childmenu").val('');
          // $("#childmenu_hi").val('');
          $("#submenu_url").empty();

          $("#addChildmenuModal").modal('hide');
        },
        error: function (xhr, status, error) {
          console.error("AJAX Error:", error);
          alert("There was an error while adding the Childmenu.");
        }
      });
    });

    // Fetch submenus when parent menu is selected
    $("#parentMenuUrl").change(function () {
      var parentMenuUrl = $(this).val();

      $.ajax({
        url: "{{ route('admin.fetch_submenus') }}",
        method: 'GET',
        data: {
          parentMenuUrl: parentMenuUrl
        },
        success: function (response) {
          // Populate submenu_url dropdown with new options
          var submenuOptions = '<option value="">Select Submenu</option>';
          response.submenus.forEach(function(submenu) {
            submenuOptions += '<option value="' + submenu.submenu_url + '">' + submenu.submenu_name + '</option>';
          });
          $('#submenu_url').html(submenuOptions);
        },
        error: function (xhr, status, error) {
          console.error("AJAX Error:", error);
          alert("There was an error fetching submenus.");
        }
      });
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
    $('#edit_menu_hi').val(menuNameHi);
    $('#editRowModal').modal('show');
  });
  
   
   // Update Menu
  $('#updateRowButton').click(function () {
    var id = $('#edit_id').val();
    var menuName = $('#edit_menu').val();
    var menuNameHi = $('#edit_menu_hi').val();

    $.ajax({
      url: "/admin-panel/childmenu_update/" + id,
      method: 'POST',
      data: {
        _token: "{{ csrf_token() }}",
        submenuName: menuName,
        submenuNameHi: menuNameHi
      },
      success: function (response) {
        var row = $('tr[data-id="' + id + '"]');
        row.find('.menu_name').text(response.submenu_name);
        row.find('.menu_name_hi').text(response.submenu_name_hi);
        $('#editRowModal').modal('hide');
      },
      error: function () {
        alert("Error while updating menu.");
      }
    });
  });
  
  
  
    // Delete Button Click
$('#add-row').on('click', '.deleteBtn', function () {
  if (!confirm("Are you sure you want to delete this menu?")) return;

  var row = $(this).closest('tr');
  var id = row.data('id'); // Use `.data('id')` instead of `.attr('data-id')` (cleaner)

  $.ajax({
    url: "/admin-panel/childmenu_delete/" + id,
    method: 'POST',
    data: {
      _token: "{{ csrf_token() }}"
    },
    success: function () {
      $('#add-row').DataTable().row(row).remove().draw(); // Ensure DataTable instance is used
    },
    error: function () {
      alert("Error while deleting menu.");
    }
  });
});

</script>
@endsection
