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
    

            <!-- Table -->
            <div class="table-responsive">
              <table class="table table-striped table-hover" id="add-row">
                <thead class="thead-light text-uppercase fw-semibold">
                  <tr>
                    <th>#</th>
                    <th>Participant Name</th>
                    <th>Phone</th>
                    <th>Gender</th>
                    <th>Email</th>
                    <th>Designation</th>
                    
                   
                  </tr>
                </thead>
                <tbody>
                  @foreach ($participants as $index => $item)
                    <tr>
                      <td>{{ $index + 1 }}</td>
                      <td>{{ $item->name }}</td>
                     <td>{{ $item->phone }}</td>
                       <td>{{ $item->gender}}</td>
                       <td>{{ $item->email }}</td>
                       <td>{{ $item->designation }}</td>
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
