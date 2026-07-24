@extends('admin.layouts.master')

@section('main-section')
<div class="container">
  <div class="page-inner">
    <div class="page-header">
      <h3 class="fw-bold mb-3">Content List</h3>
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
          <a href="#">Manage SiteContent</a>
        </li>
        <li class="separator">
          <i class="icon-arrow-right"></i>
        </li>
        <li class="nav-item">
          <a href="#">Content List</a>
        </li>
      </ul>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <div class="d-flex align-items-center">
              <h4 class="card-title">Content List</h4>
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
                    <th>Heading</th>
                    <th>Menu/URL</th>
                    <th>SubMenu</th>
                    <th>Childmenu Name</th>

                    <th style="width: 10%">Action</th>
                  </tr>
                </thead>
                <tbody>
                  @php
                  $i = 1;
                  @endphp
                  @foreach ($content as $items)
                  <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $items->heading ?? 'N/A'}}</td>
                    <td>{{ $items->menu_name ?? ($items->page_url ?? 'N/A') }}</td>
                    <td>{{ $items->submenu_name ?? 'N/A'}}</td>
                    <td>{{ $items->childmenu_name ?? 'N/A'}}</td>

                    <td>
                      <div class="form-button-action">
                        <a href="{{url('/admin-panel/contentlist/'. $items->id)}}" data-bs-toggle="tooltip" title="Edit" class="btn btn-link1 btn-primary btn-sm">
                          <i class="fa fa-edit"></i>
                        </a>&nbsp;&nbsp;

                        <a href="{{url('/admin-panel/content_delete/'. $items->id)}}" data-bs-toggle="tooltip" title="Remove" class="btn btn-lin1k btn-danger btn-sm">
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
      pageLength: 20,
    });

    var action =
        '<td><div class="form-button-action"><button type="button" data-bs-toggle="tooltip" title="Edit" class="btn btn-link btn-primary btn-lg"><i class="fa fa-edit"></i></button><button type="button" data-bs-toggle="tooltip" title="Remove" class="btn btn-link btn-danger"><i class="fa fa-times"></i></button></div></td>';


  });
</script>
@endsection
