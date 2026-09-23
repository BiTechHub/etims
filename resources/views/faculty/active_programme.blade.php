@extends('faculty.layouts.master')

@section('title', 'Manage Menus')

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

    <!-- Content -->
    <div class="row">
      <div class="col-md-12">
        <div class="card">

          <!-- Card Header -->
          {{-- <div class="card-header d-flex align-items-center justify-content-between">
            <h4 class="card-title">Menu List</h4>
            <button class="btn btn-primary btn-round" data-bs-toggle="modal" data-bs-target="#addRowModal">
              <i class="fa fa-plus"></i> Add Menu
            </button>
          </div> --}}

          <!-- Card Body -->
          <div class="card-body">

            <!-- Add Modal -->
            

            <!-- Edit Modal -->
            <div class="modal fade" id="editRowModal" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <form method="POST" action="" id="editForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-header border-0">
                      <h5 class="modal-title">Edit Menu</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                      <input type="hidden" id="edit_id" name="id">
                      <div class="form-group">
                        <label for="edit_menu">Menu Name</label>
                        <input type="text" id="edit_menu" name="menu" class="form-control" required>
                      </div>
                    </div>
                    <div class="modal-footer border-0">
                      <button type="submit" class="btn btn-primary">Update</button>
                      <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>

            <!-- Filter Form -->
            <form action="{{ route('faculty.assigned.programme', request()->route('id')) }}" method="GET" class="row g-3 align-items-end mb-4">
              @csrf

              <div class="col-md-3">
                <label for="status" class="form-label">Programme Status</label>
                <select name="status" id="status" class="form-control">
                  <option value="">All</option>
                  @foreach($status as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                  @endforeach
                </select>
              </div>

              <div class="col-md-3">
                <label for="unique_id" class="form-label">Programme Code</label>
                <input type="text" name="unique_id" id="unique_id" class="form-control" value="{{ request('unique_id') }}" placeholder="Enter programme code">
              </div>

              <div class="col-md-3">
                <label for="department" class="form-label">Department</label>
                <select name="department" id="department" class="form-control">
                  <option value="">All</option>
                  @foreach($department as $id => $name)
                    <option value="{{ $id }}" {{ request('department') == $id ? 'selected' : '' }}>{{ $name }}</option>
                  @endforeach
                </select>
              </div>

              <div class="col-md-3">
                <label for="financial" class="form-label">Financial Year</label>
                <select name="financial" id="financial" class="form-control">
                  <option value="">All</option>
                  @foreach($financial as $fy)
                    <option value="{{ $fy }}" {{ request('financial') == $fy ? 'selected' : '' }}>{{ $fy }}</option>
                  @endforeach
                </select>
              </div>

              <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
              </div>
            </form>

            <!-- Table -->
            <div class="table-responsive">
              <table class="table table-striped table-hover" id="add-row">
                <thead class="thead-light text-uppercase fw-semibold">
                  <tr>
                    <th>#</th>
                    <th>Programme Code</th>
                    <th>Status</th>
                    <th>Group Name</th>
                    <th>Programme Title</th>
                    <th>Sponsor Type</th>
                    <th>Location</th>
                    <th>Total Classes</th>
                    <th>View Participants</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($programmes as $index => $item)
                    <tr>
                      <td>{{ $index + 1 }}</td>
                      <td>{{ $item->unique_id }}</td>
                      <td>{{ $item->status }}</td>
                      <td>{{ $group[$item->group_id] ?? 'Unknown' }}</td>
                      <td>{{ $item->title }}</td>
                      <td>{{ $sponsor[$item->sponsor_id] ?? 'Unknown' }}</td>
                      <td>{{ $item->venue }}</td>
                      <td>{{ $programmeIdCounts[$item->id] ?? 0 }} Classes</td>
                      <td>
    <a href="{{ route('faculty.vew.nomination', $item->id) }}">View</a>
</td>

                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div> <!-- end table-responsive -->

          </div> <!-- end card-body -->

        </div> <!-- end card -->
      </div> <!-- end col -->
    </div> <!-- end row -->

  </div> <!-- end page-inner -->
</div> <!-- end container -->
@endsection
