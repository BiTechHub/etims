@extends('admin.layouts.master')

@section('main-section')
<div class="container">
  <div class="page-inner">
    <div class="page-header">
      <h3 class="fw-bold mb-3">Tender List</h3>
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
          <a href="#">Tender</a>
        </li>
        <li class="separator">
          <i class="icon-arrow-right"></i>
        </li>
        <li class="nav-item">
          <a href="#">Tender List</a>
        </li>
      </ul>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <div class="d-flex align-items-center">
              <h4 class="card-title">Tender List</h4>
              {{-- <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal" data-bs-target="#addChildmenuModal">
              <i class="fa fa-plus"></i>
              Add Childmenu
              </button> --}}
            </div>
          </div>
          <div class="card-body">
            <!-- Modal for Adding Childmenu -->
            <!-- Table for Displaying Childmenus -->
            <div class="table-responsive">
              <table id="add-row" class="display table table-striped table-hover">
                <thead>
                  <tr>
                    <th>S.No</th>
                    <th>Published Date</th>
                    <th>Title/Tender Number</th>
                    <th>Description</th>
                    <th style="width: 10%">Action</th>
                  </tr>
                </thead>
                <tbody>
                  @php
                  $i = 1;
                  @endphp
                  @foreach ($data as $items)
                  <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $items->published_date ?? 'N/A'}}</td>
                    <td>{{ $items->title ?? 'N/A'}}</td>
                    <td>{{ $items->description ?? 'N/A'}}</td>
                    <td>
                      <div class="form-button-action">
                        <a href="{{url('/')}}/tender/{{$items->attachment}}" class="btn btn-info btn-sm"><i class="fa fa-eye"></i></a>
                        <a href="{{url('/admin-panel/tender_edit/'. $items->id)}}" data-bs-toggle="tooltip" title="Edit" class="btn btn-primary btn-sm">
                          <i class="fa fa-edit"></i>
                        </a>&nbsp;&nbsp;
                        <a href="{{url('/admin-panel/tender_delete/'. $items->id)}}" data-bs-toggle="tooltip" title="Remove" class="btn btn-lin1k btn-danger btn-sm">
                          <i class="fa fa-times"></i>
                        </a>
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
