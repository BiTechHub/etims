@extends('admin.layouts.master')

@section('main-section')
<div class="container">
  <div class="page-inner">
    <div class="page-header">
      <h3 class="fw-bold mb-3">Slider</h3>
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
          <a href="#">Master</a>
        </li>
        <li class="separator">
          <i class="icon-arrow-right"></i>
        </li>
        <li class="nav-item">
          <a href="#">Edit Slider</a>
        </li>
      </ul>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            @if(session('status'))
            <div class="alert alert-success">
              {{ session('status') }}
            </div>
            @endif
            @if(session('success'))
            <div class="alert alert-success">
              {{ session('success') }}
            </div>
            @endif
            @if(session('fail'))
            <div class="alert alert-success">
              {{ session('fail') }}
            </div>
            @endif
            <form action="{{ route('admin.slider_update') }}" method="post" enctype="multipart/form-data">
              @csrf

              <input type="hidden" name="id" value="{{$data->id}}">

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Title <span class="text-danger">*</span></label>
                    <div class="controls">
                      <input type="text" name="title" class="form-control" value="{{$data->title}}">
                      <span class="text-danger"> @error("title") {{$message}} @enderror </span>
                    </div>
                  </div>
                </div>

                {{-- <div class="col-md-6">
                  <div class="form-group">
                    <label>Title (Hindi) <span class="text-danger">*</span></label>
                    <div class="controls">
                      <input type="text" name="title_hi" class="form-control" value="{{$data->title_hi}}">
                      <span class="text-danger"> @error("title_hi") {{$message}} @enderror </span>
                    </div>
                  </div>
                </div> --}}


                <div class="col-md-6">
                  <div class="form-group">
                    <label>Description</label>
                    <div class="controls">
                      <textarea class="form-control" id="description" name="description" placeholder="">{{$data->description}}</textarea>
                      <span class="text-danger"> @error("description") {{$message}} @enderror </span>
                    </div>
                  </div>
                </div>

                {{-- <div class="col-md-6">
                  <div class="form-group">
                    <label>Description (Hindi)</label>
                    <div class="controls">
                      <textarea class="form-control" id="description_hi" name="description_hi" placeholder="">{{$data->description_hi}}</textarea>
                      <span class="text-danger"> @error("description_hi") {{$message}} @enderror </span>
                    </div>
                  </div>
                </div> --}}

                <div class="col-md-6">
                  <div class="form-group">
                    <label>Image <span class="text-danger">*</span></label>
                    <div class="controls">
                      <input type="file" name="attachment" class="form-control">
                      <span class="text-danger"> @error("attachment") {{$message}} @enderror </span>
                    </div>
                  </div> 
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Attach URL (Know More)</label>
                    <div class="controls">
                      <input type="text" name="attach_url" class="form-control" value="{{$data->attach_url}}">
                      <span class="text-danger"> @error("attach_url") {{$message}} @enderror </span>
                    </div>
                  </div> 
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <br>
                  <div class="text-center">
                    <button type="submit" class="btn btn-info">Update</button>
                  </div>
                </div>
              </div>
            </form>
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
      pageLength: 15,
    });

    var action =
        '<td><div class="form-button-action"><button type="button" data-bs-toggle="tooltip" title="Edit" class="btn btn-link btn-primary btn-lg"><i class="fa fa-edit"></i></button><button type="button" data-bs-toggle="tooltip" title="Remove" class="btn btn-link btn-danger"><i class="fa fa-times"></i></button></div></td>';


    // Fetch submenus when parent menu is selected

</script>
@endsection
