@extends('admin.layouts.master')
@section('main-section')
<div class="container">
  <div class="page-inner">
    <div class="page-header">
      <h3 class="fw-bold mb-3">Photo Category</h3>
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
          <a href="#">Photo Category</a>
        </li>
      </ul>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header d-flex align-items-center">
            <h4 class="card-title">Gallery</h4>
            <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal" data-bs-target="#addRowModal">
              <i class="fa fa-plus"></i> Add Category
            </button>
          </div>
          <div class="card-body">

            <!-- Modal -->
            <div class="modal fade" id="addRowModal" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-header border-0">
                    <h5 class="modal-title"> New Category </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <form id="categoryForm">
                      @csrf
                      <div class="form-group">
                        <label>Category</label>
                        <input id="category" name="category" type="text" class="form-control" placeholder="English" required />
                      </div>

                      {{-- <div class="form-group">
                        <label>Category (Hindi)</label>
                        <input id="category_hi" name="category_hi" type="text" class="form-control" placeholder="Hindi" required />
                      </div> --}}

                      <div class="form-group">
                        <label>Category Image</label>
                        <input id="cate_image" name="cate_image" type="file" class="form-control" required />
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
                    <h5 class="modal-title">Edit Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                    <form id="editCategoryForm" enctype="multipart/form-data">
                      @csrf
                      <input type="hidden" id="edit_id">
                      <div class="form-group">
                        <label>Category</label>
                        <input id="edit_category" name="category" type="text" class="form-control" required />
                      </div>

                      {{-- <div class="form-group">
                        <label>Category (Hindi)</label>
                        <input id="edit_category_hi" name="category_hi" type="text" class="form-control" required />
                      </div> --}}
                      <div class="form-group">
                        <label>Category Image</label>
                        <input id="edit_cate_image" name="cate_image" type="file" class="form-control" />
                        <img id="editPreviewImage" src="" class="mt-2 rounded" width="50">
                      </div>
                    </form>
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
                    <th>S.No.</th>
                    <th>Category</th>
                    {{-- <th>Category (Hindi)</th> --}}
                    <th>Image</th>
                    <th style="width: 10%">Action</th>
                  </tr>
                </thead>
                <tbody>
                  @php $i = 1; @endphp
                  @foreach ($data as $items)
                  <tr data-id="{{ $items->id }}">
                    <td>{{ $i++ }}</td>
                    <td>{{ $items->category }}</td>
                    {{-- <td>{{ $items->category_hi }}</td> --}}
                    <td><img src="{{ url('/') }}/gallery/{{ $items->cate_image }}" alt="..." class="avatar-img rounded" width="50"></td>
                    <td>
                      <div class="form-button-action">
                        <button type="button" class="btn btn-link1 btn-primary btn_updated btn-sm"><i class="fa fa-edit"></i></button>
                        <button type="button" class="btn btn-link1 btn-danger btn_deleted btn-sm"><i class="fa fa-times"></i></button>
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

<!-- Datatables -->
@section('script')
<script src="{{ url('/admin') }}/assets/js/plugin/datatables/datatables.min.js"></script>
<script src="{{ url('/admin') }}/assets/js/kaiadmin.min.js"></script>
<script src="{{ url('/admin') }}/assets/js/setting-demo2.js"></script>

<script>
$(document).ready(function () {
    var table = $("#add-row").DataTable({
      pageLength: 15,
    });

    function showLoader() {
        $('#loader').show();
    }

    function hideLoader() {
        $('#loader').hide();
    }

    // ADD Category
    $("#addRowButton").click(function () {
      var formData = new FormData();
      formData.append('_token', "{{ csrf_token() }}");
      formData.append('category', $("#category").val());
      formData.append('cate_image', $('#cate_image')[0].files[0]);

      showLoader();
      $.ajax({
        url: "{{ route('admin.category_store') }}",
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
          var newRow = "<tr data-id='" + response.id + "'>" +
              "<td>New</td>" +
              "<td>" + response.category + "</td>" +
              "<td><img src='{{ url('/') }}/gallery/" + response.cate_image + "' class='avatar-img rounded' width='50'/></td>" +
              "<td><div class='form-button-action'>" +
              "<button class='btn btn-link btn-primary btn-lg btn_updated'><i class='fa fa-edit'></i></button>" +
              "<button class='btn btn-link btn-danger btn_deleted'><i class='fa fa-times'></i></button>" +
              "</div></td></tr>";

          table.row.add($(newRow)).draw();

          $("#categoryForm")[0].reset();
          $("#addRowModal").modal('hide');
        },
        error: function () {
          alert("Error while adding category.");
        },
        complete: function () {
          hideLoader();
        }
      });
    });

    // EDIT Category
    $('#add-row').on('click', '.btn_updated', function () {
      var row = $(this).closest('tr');
      var id = row.attr('data-id');
      var category = row.find('td:eq(1)').text();
      //var category_hi = row.find('td:eq(2)').text();
      var imageSrc = row.find('img').attr('src');

      $('#edit_id').val(id);
      $('#edit_category').val(category);
      //$('#edit_category_hi').val(category_hi);
      $('#editPreviewImage').attr('src', imageSrc);

      $('#editRowModal').modal('show');
    });

    // UPDATE Category
    $('#updateRowButton').click(function () {
      var id = $('#edit_id').val();
      var formData = new FormData();
      formData.append('_token', "{{ csrf_token() }}");
      formData.append('category', $('#edit_category').val());
      //formData.append('category_hi', $('#edit_category_hi').val());
      if ($('#edit_cate_image')[0].files[0]) {
        formData.append('cate_image', $('#edit_cate_image')[0].files[0]);
      }

      showLoader();
      $.ajax({
        url: '/admin-panel/category_update/' + id,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function () {
          location.reload();
        },
        error: function () {
          alert('Error while updating category');
        },
        complete: function () {
          hideLoader();
        }
      });
    });

    // DELETE Category
    $('#add-row').on('click', '.btn_deleted', function () {
      if (!confirm('Are you sure you want to delete this category?')) return;

      var row = $(this).closest('tr');
      var id = row.attr('data-id');

      showLoader();
      $.ajax({
        url: '/admin-panel/category_delete/' + id,
        type: 'POST',
        data: { _token: "{{ csrf_token() }}" },
        success: function () {
          table.row(row).remove().draw();
        },
        error: function () {
          alert('Error while deleting category');
        },
        complete: function () {
          hideLoader();
        }
      });
    });

});
</script>
@endsection

