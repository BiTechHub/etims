@extends('admin.layouts.master')
@section('main-section')

<div class="container">
    <div class="page-inner">

        <!-- Page Header -->
        <div class="page-header">
            <h3 class="fw-bold mb-3">Branch</h3>

            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="#"><i class="icon-home"></i></a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Management Branch</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">

                <div class="card">

                    <!-- Card Header -->
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">
                                Add Branch
                            </h4>

                            <button class="btn btn-primary btn-sm" onclick="toggleForm()">
                                <i class="fa fa-plus"></i> Add New
                            </button>
                        </div>
                    </div>

                    <!-- Form -->
                    <div class="card-body border-bottom" id="formBox" style="display:none;">

                        <form action="{{ url('/admin/branch_store') }}" method="POST">
                            @csrf

                            <div class="row">

                                <div class="col-md-5 mb-3">
                                    <label class="form-label">
                                        Branch Name
                                    </label>

                                    <input
                                        type="text"
                                        name="name"
                                        class="form-control"
                                        placeholder="Enter Branch Name"
                                        required>
                                </div>

                                <div class="col-md-2 d-flex align-items-end mb-3">

                                    <button class="btn btn-success w-100">
                                        <i class="fa fa-save"></i>
                                        Save
                                    </button>

                                </div>

                            </div>

                        </form>

                    </div>

                    <!-- Table -->
                    <div class="card-body">

                        <div class="table-responsive">

                            <table id="add-row" class="display table table-striped table-hover">

                                <thead>
                                    <tr>
                                        <th width="70">#</th>
                                        <th>Branch Name</th>
                                        {{-- <th width="150">Action</th> --}}
                                    </tr>
                                </thead>

                                <tbody>

                                    @forelse($branch as $row)

                                        <tr>

                                            <td>{{ $loop->iteration }}</td>

                                            <td>{{ $row->name }}</td>

                                            {{-- <td>

                                                <button class="btn btn-warning btn-sm">
                                                    <i class="fa fa-edit"></i>
                                                    Edit
                                                </button>

                                                <button class="btn btn-danger btn-sm">
                                                    <i class="fa fa-trash"></i>
                                                    Delete
                                                </button>

                                            </td> --}}

                                        </tr>

                                    @empty

                                        <tr>
                                            <td colspan="4" class="text-center text-danger">
                                                No Record Found
                                            </td>
                                        </tr>

                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>
        </div>

    </div>
</div>

<script>
function toggleForm() {

    let form = document.getElementById('formBox');

    if (form.style.display === "none" || form.style.display === "") {
        form.style.display = "block";
    } else {
        form.style.display = "none";
    }
}
</script>

@endsection