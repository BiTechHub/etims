@extends('admin.layouts.master')

@section('main-section')
<div class="container">
  <div class="page-inner">
    <div class="page-header">
      <h3 class="fw-bold mb-3">Enquiry List</h3>
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
          <a href="#">Home</a>
        </li>
        <li class="separator">
          <i class="icon-arrow-right"></i>
        </li>
        <li class="nav-item">
          <a href="#">Enquiry List</a>
        </li>
      </ul>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <!-- Modal for Adding Childmenu -->
            <!-- Table for Displaying Childmenus -->
            <div class="table-responsive">
              <table id="add-row" class="display table table-striped table-hover">
                <thead>
                  <tr>
                    <th>S.No</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone No.</th>
                    <th>Organization</th>
                    <th>Subject</th>
                    <th>Message</th>
                  </tr>
                </thead>
                <tbody>
                  @php
                    $i = 1;
                  @endphp
                  @foreach ($data as $items)
                  <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $items->name ?? 'N/A'}}</td>
                    <td>{{ $items->email ?? 'N/A'}}</td>
                    <td>{{ $items->mobile ?? 'N/A'}}</td>
                    <td>{{ $items->organization ?? 'N/A'}}</td>
                    <td>{{ $items->subject ?? 'N/A'}}</td>
                    <td>{{ $items->message ?? 'N/A'}}</td>
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
    $("#add-row").DataTable({
      pageLength: 5,
    });

    var action =
      '<td><div class="form-button-action"><button type="button" data-bs-toggle="tooltip" title="Edit" class="btn btn-link btn-primary btn-lg"><i class="fa fa-edit"></i></button><button type="button" data-bs-toggle="tooltip" title="Remove" class="btn btn-link btn-danger"><i class="fa fa-times"></i></button></div></td>';

    // Handle Add Childmenu Button Click
    $("#addChildmenuButton").click(function () {
      var parentMenuUrl = $("#parentMenuUrl").val();
      var submenuUrl = $("#submenu_url").val();
      var childmenuName = $("#childmenu").val();
      var childmenuNameHi = $("#childmenu_hi").val();

      console.log("Sending data:", { parentMenuUrl: parentMenuUrl, submenuUrl: submenuUrl, childmenuName: childmenuName, childmenuNameHi: childmenuNameHi });

      $.ajax({
        url: "{{ route('admin.childmenu_store') }}",
        method: 'POST',
        data: {
          _token: "{{ csrf_token() }}",
          parentMenuUrl: parentMenuUrl,
          submenuUrl: submenuUrl,
          childmenuName: childmenuName,
          childmenuNameHi: childmenuNameHi
        },
        success: function (response) {
          console.log("Response:", response);
          var newRow = "<tr>" +
                         "<td>" + response.parent_menu_name + "</td>" +
                         "<td>" + response.childmenu_name + "</td>" +
                         "<td>" + response.childmenu_name_hi + "</td>" +
                         action +
                       "</tr>";
          $("#add-row tbody").append(newRow);

          $("#childmenu").val('');
          $("#childmenu_hi").val('');
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
</script>
@endsection
