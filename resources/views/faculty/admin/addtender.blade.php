@extends('admin.layouts.master')
@section('main-section')
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote-lite.css"> --}}
<link rel="stylesheet" href="{{ asset('assets/summernote/summernote-lite.css') }}">

<div class="container">
  <div class="page-inner">
    <div class="page-header">
      <h3 class="fw-bold mb-3">Add Tender</h3>
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
          <a href="#">Add Tender</a>
        </li>
      </ul>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
              <form action="{{ route('admin.tender_store') }}" method="post" enctype="multipart/form-data">
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
                            @csrf
                            <div class="row">
                      <div class="col-md-12">
                      <div class="form-group">
                        <label>Title <span class="text-danger">*</span></label>
                        <div class="controls">
                          <input type="text" name="title" class="form-control">
                          <span class="text-danger"> @error('title') {{$message}} @enderror </span>
                        </div>
                      </div>
                    </div>

                    {{-- <div class="col-md-6">
                      <div class="form-group">
                        <label>Title (Hindi) <span class="text-danger">*</span></label>
                        <div class="controls">
                          <input type="text" name="title_hi" class="form-control">
                          <span class="text-danger"> @error('title_hi') {{$message}} @enderror </span>
                        </div>
                      </div>
                    </div> --}}

                
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Description</label>
                        <div class="controls">
                          <textarea class="form-control" id="description" name="description" placeholder=""></textarea>
                          <span class="text-danger"> @error('description') {{$message}} @enderror </span>
                        </div>
                      </div>
                    </div>

                    {{-- <div class="col-md-6">
                      <div class="form-group">
                        <label>Description (Hindi)</label>
                        <div class="controls">
                          <textarea class="form-control" id="description_hi" name="description_hi" placeholder=""></textarea>
                          <span class="text-danger"> @error('description_hi') {{$message}} @enderror </span>
                        </div>
                      </div>
                    </div> --}}

                    <div class="col-md-3">
                      <div class="form-group">
                          <label>Tender Type <span class="text-danger">*</span></label>
                          <div class="controls">
                            <select name="tender_type" id="tender_type" class="form-control">
                              <option value="">-Select Tender Type-</option>
                              <option value="Tender Notice">Tender Notice</option>
                              <option value="Tender Document">Tender Document</option>
                            </select>
                            <span class="text-danger"> @error('tender_type') {{$message}} @enderror </span>
                          </div>
                      </div>
                      </div>

                      <div class="col-md-3">
                      <div class="form-group">
                        <label>Published Date <span class="text-danger">*</span></label>
                        <div class="controls">
                          <input type="date" name="published_date" class="form-control">
                          <span class="text-danger"> @error('published_date') {{$message}} @enderror </span>
                        </div>
                      </div>
                    </div>

                    
                    
                      
                    {{-- <div class="col-md-3">
                      <div class="form-group">
                        <label>Opening Date	 <span class="text-danger">*</span></label>
                        <div class="controls">
                          <input type="date" name="opening_date" class="form-control">
                          <span class="text-danger"> @error('opening_date') {{$message}} @enderror </span>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-3">
                      <div class="form-group">
                        <label>Last Date	 <span class="text-danger">*</span></label>
                        <div class="controls">
                          <input type="date" name="submission_date" class="form-control">
                          <span class="text-danger"> @error('submission_date') {{$message}} @enderror </span>
                        </div>
                      </div>
                    </div> --}}

                      <div class="col-md-6">
                      <div class="form-group">
                        <label>Attachment (PDF) <span class="text-danger">*</span></label>
                        <div class="controls">
                           <input type="file" name="attachment" class="form-control" accept="application/pdf">
                          <span class="text-danger"> @error('attachment') {{$message}} @enderror </span>
                        </div>
                      </div> 
                     </div> 
                    <div class="col-md-12">
                        <br>
                  <div class="text-xs-right">
                    <button type="submit" class="btn btn-info">Submit</button>
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
<script src="{{ asset('assets/summernote/summernote-lite.js') }}"></script>
<script>
  $(document).ready(function () {


    $("#main_cat").change(function () {
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
          $('#sub').html(submenuOptions);
        },
        error: function (xhr, status, error) {
          console.error("AJAX Error:", error);
          alert("There was an error fetching submenus.");
        }
      });
    });


     // On change of Submenu (sub)
  $("#sub").change(function () {
    var submenuUrl = $(this).val();  
    var parentMenuUrl = $("#main_cat").val();  

    $.ajax({
      url: "{{ route('admin.fetch_child') }}",  
      method: 'GET',
      data: {
        parentMenuUrl: parentMenuUrl,
        submenuUrl: submenuUrl  
      },
      success: function (response) {
        var childmenuOptions = '<option value="">Select Childmenu</option>';
        response.childmenus.forEach(function(childmenu) {
          childmenuOptions += '<option value="' + childmenu.childmenu_url + '">' + childmenu.childmenu_name + '</option>';
        });
        $('#child').html(childmenuOptions);  
      },
      error: function (xhr, status, error) {
        console.error("AJAX Error:", error);
        alert("There was an error fetching childmenus.");
      }
    });
  });






  });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote-lite.js"></script>
 <script>
$('#summernote').summernote({
        placeholder: '',
        tabsize: 2,
        height: 400,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link', 'picture', 'video']],
          ['view', ['fullscreen', 'codeview', 'help']]
        ]
      });
	  </script>
@endsection
