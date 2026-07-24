@extends('admin.layouts.master')
@section('main-section')
<div class="container">
  <div class="page-inner">
    <div class="page-header">
      <h3 class="fw-bold mb-3">Upload PDF</h3>
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
          <a href="#">Manage Site Content</a>
        </li>
        <li class="separator">
          <i class="icon-arrow-right"></i>
        </li>
        <li class="nav-item">
          <a href="#">Upload PDF</a>
        </li>
      </ul>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <div class="d-flex align-items-center">
              <h4 class="card-title">PDF List</h4>
              <button
              class="btn btn-primary btn-round ms-auto"
              data-bs-toggle="modal"
              data-bs-target="#addRowModal"
            >
              <i class="fa fa-plus"></i>
              Add PDF
            </button>
            </div>
          </div>
          <div class="card-body">
            <!-- Modal -->
            <div class="modal fade" id="addRowModal" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header border-0">
                    <h5 class="modal-title">
                      <span class="fw-mediumbold"> New</span>
                      <span class="fw-light"> PDF </span>
                    </h5>
                    <!-- Correct Close Button for Bootstrap 5 -->
                    <button
                      type="button"
                      class="btn-close"
                      data-bs-dismiss="modal"
                      aria-label="Close"
                    ></button>
                  </div>
                  <div class="modal-body">
                    <p class="small">
                      Create a new PDF using this form, make sure you fill them all.
                    </p>


                    <form action="{{ route('pdf.upload') }}" method="post" enctype="multipart/form-data">
                      @csrf
                      <div class="row">
                        <div class="col-sm-12">
                          <div class="form-group form-group-default">
                            <label>Title</label>
                            <input
                              id="title"
                              name="title"
                              type="text"
                              class="form-control"
                            />
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="form-group form-group-default">
                            <label>Select PDF</label>
                            <input
                              id="pdfname"
                              name="pdfname"
                              type="file"
                              class="form-control"
                            />
                          </div>
                        </div>
                      </div>
                      <div class="modal-footer border-0">
                        <button
                          type="button"
                          id="addRowButton"
                          class="btn btn-primary"
                        >
                          Add
                        </button>
                        <!-- Correct Close Button for Bootstrap 5 -->
                        <button
                          type="submit"
                          class="btn btn-danger"
                          data-bs-dismiss="modal"
                        >
                          Close
                        </button>
                      </div>
                    </form>
                  </div>
                  

                </div>
              </div>
            </div>

            <div class="table-responsive">
              <table
                id="add-row"
                class="display table table-striped table-hover"
              >
                <thead>
                  <tr>
                    <th>S.No.</th>
                    <th>Title</th>
                    <th>PDF URL</th>
                    <th style="width: 10%">Copy URL</th>
                  </tr>
                </thead>
                <tbody>
                  @php
                    $i = 1;
                  @endphp
                  @foreach ($pdf as $items)
                  <tr>
                    <td> {{$i++}}</td>
                    <td> {{$items->title}}</td>
                    <td id="urlCell{{ $i }}">{{ url('/') }}/uploads/pdf_file/{{ $items->pdfname }}</td>
                    <td>
                        <i class="fas fa-clone" onclick="copyToClipboard('#urlCell{{ $i }}')" data-placement="left" data-toggle="popover" data-content="Copied!"></i>
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
<!-- Datatables -->
<script src="{{url('/admin')}}/assets/js/plugin/datatables/datatables.min.js"></script>
<!-- Kaiadmin JS -->
<script src="{{url('/admin')}}/assets/js/kaiadmin.min.js"></script>
<!-- Kaiadmin DEMO methods, don't include it in your project! -->
<script src="{{url('/admin')}}/assets/js/setting-demo2.js"></script>
<script>
  $(document).ready(function () {
    // Initialize DataTable
    $("#add-row").DataTable({
      pageLength: 5,
    });

    var action =
      '<td> <div class="form-button-action"> <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task"> <i class="fa fa-edit"></i> </button> <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove"> <i class="fa fa-times"></i> </button> </div> </td>';

    


  });
</script>
<script>
  function copyToClipboard(element) {
      var $temp = $("<input>");
      $("body").append($temp);
      $temp.val($(element).text()).select();
      document.execCommand("copy");
      $temp.remove();
      setTimeout(function() {
      }, 2000);
  }
  </script>
@endsection
