@extends('admin.layouts.master')
@section('main-section')
<div class="container">
  <div class="page-inner">
    <div class="page-header">
      <h3 class="fw-bold mb-3">Photo Gallery</h3>
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
          <a href="#">Gallery</a>
        </li>
        <li class="separator">
          <i class="icon-arrow-right"></i>
        </li>
        <li class="nav-item">
          <a href="#">Photo Gallery</a>
        </li>
      </ul>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header d-flex align-items-center">
            <h4 class="card-title">Gallery List</h4>
            <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal" data-bs-target="#addRowModal">
              <i class="fa fa-plus"></i> Photo Gallery
            </button>
          </div>
          <div class="card-body">

            <!-- Modal -->
            <div class="modal fade" id="addRowModal" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-header border-0">
                    <h5 class="modal-title"> New Photo </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                    <form id="categoryForm">
                      @csrf
                      <div class="form-group">
                        <label>Category</label>
                        <select class="form-select form-control" id="category" name="category">
                          <option value="">Select Category</option>
                          @foreach ($cate_data as $items)
                          <option value="{{$items->id}}">{{$items->category}}</option>
                          @endforeach
                        </select>
                      </div>

                      <div class="form-group">
                        <label>Title</label>
                        <input id="title" name="title" type="text" class="form-control" required />
                      </div>
                      {{-- <div class="form-group">
                        <label>Title (Hindi)</label>
                        <input id="title_hi" name="title_hi" type="text" class="form-control" required />
                      </div> --}}
                      <div class="form-group">
                        <label>Image</label>
                        <input id="image" name="image" type="file" class="form-control" required />
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
              <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-header border-0">
                    <h5 class="modal-title">Edit Photo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                    <form id="editCategoryForm">
                      @csrf
                      <input type="hidden" id="edit_id" name="id">
                      <div class="form-group">
                        <label>Category</label>
                        <select class="form-select form-control" id="edit_category" name="category">
                          <option value="">Select Category</option>
                          @foreach ($cate_data as $items)
                          <option value="{{$items->id}}">{{$items->category}}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="form-group">
                        <label>Title</label>
                        <input id="edit_title" name="title" type="text" class="form-control" required />
                      </div>

                      {{-- <div class="form-group">
                        <label>Title (Hindi)</label>
                        <input id="edit_title_hi" name="title_hi" type="text" class="form-control" required />
                      </div> --}}

                      <div class="form-group">
                        <label>Change Image (Optional)</label>
                        <input id="edit_image" name="image" type="file" class="form-control" />
                      </div>
                    </form>
                  </div>
                  <div class="modal-footer border-0">
                    <button type="button" id="updateRowButton" class="btn btn-success">Update</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>


            <!-- Table -->
            <div class="table-responsive">
              <table id="add-row" class="display table table-striped table-hover">
                <thead>
                  <tr>
                    <th>S.No.</th>
                    <th>Category</th>
                    <th>Title</th>
                    {{-- <th>Title (English)</th> --}}
                    <th>Image</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @php $i = 1; @endphp
                  @foreach ($data as $items)
                  <tr id="row_{{ $items->id }}">
                    <td>{{ $i++ }}</td>
                    <td>{{ $items->category }}</td>
                    <td>{{ $items->title }}</td>
                    {{-- <td>{{ $items->title_hi }}</td> --}}
                    <td><img src="{{ url('/') }}/gallery/{{ $items->image }}" class="avatar-img rounded" width="50"></td>
                    <td>
                      <div class="form-button-action">

                        <button type="button" class="btn btn-link1 btn-primary btn-sm editBtn"
                                data-id="{{ $items->id }}"
                                data-category="{{ $items->category }}"
                                data-title="{{ $items->title }}"
                                {{-- data-title_hi="{{ $items->title_hi }}" --}}
                                
                                >
                          <i class="fa fa-edit"></i>
                        </button>

                        <button type="button" class="btn btn-link1 btn-danger btn-sm deleteBtn" data-id="{{ $items->id }}">
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
<script>
  $(document).ready(function () {
    var table = $("#add-row").DataTable({ pageLength: 5 });

    // Insert New
    $("#addRowButton").click(function () {
      var formData = new FormData();
      formData.append('_token', "{{ csrf_token() }}");
      formData.append('category', $("#category").val());
      formData.append('title', $("#title").val());
      // formData.append('title_hi', $("#title_hi").val());
      formData.append('image', $('#image')[0].files[0]);

      $.ajax({
        url: "{{ route('admin.gallery_store') }}",
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
          var newRow = table.row.add([
            'New',
            response.category,
            response.title,
            // response.title_hi,
            "<img src='{{ url('/') }}/gallery/" + response.image + "' class='avatar-img rounded' width='50'/>",
            "<button class='btn btn-link btn-danger btn-sm deleteBtn' data-id='" + response.id + "'><i class='fa fa-times'></i></button>"
          ]).draw().node();

          $(newRow).attr('id', 'row_' + response.id);

          $("#categoryForm")[0].reset();
          $("#addRowModal").modal('hide');
        },
        error: function (xhr) {
          alert("Error: " + xhr.responseText);
        }
      });
    });

    // Delete
    $(document).on('click', '.deleteBtn', function () {
      var id = $(this).data('id');
      if (confirm('Are you sure to delete?')) {
        $.ajax({
          url: "admin-panel/photogallery_delete/" + id,
          type: 'POST',
          data: { _token: "{{ csrf_token() }}" },
          success: function () {
            table.row($('#row_' + id)).remove().draw();
          },
          error: function (xhr) {
            alert("Error: " + xhr.responseText);
          }
        });
      }
    });

    
    
    // Fill Edit Modal
    $(document).on('click', '.editBtn', function () {
      $('#edit_id').val($(this).data('id'));
      $('#edit_category').val($(this).data('category')).trigger('change');
      $('#edit_title').val($(this).data('title'));
      // $('#edit_title_hi').val($(this).data('title_hi'));
      $('#editRowModal').modal('show');
    });

    // Update via AJAX
    $("#updateRowButton").click(function () {
      var formData = new FormData();
      formData.append('_token', "{{ csrf_token() }}");
      formData.append('id', $('#edit_id').val());
      formData.append('category', $('#edit_category').val());
      formData.append('title', $('#edit_title').val());
      // formData.append('title_hi', $('#edit_title_hi').val());
      if ($('#edit_image')[0].files.length > 0) {
        formData.append('image', $('#edit_image')[0].files[0]);
      }

      $.ajax({
        url: "{{ url('admin-panel/photogallery_update') }}",
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
          // Optionally reload the page or update the row directly
          location.reload();
        },
        error: function (xhr) {
          alert("Error: " + xhr.responseText);
        }
      });
    });

  }); // <-- Missing closing bracket added here
</script> <!-- <-- Missing script tag closed here -->




@endsection
