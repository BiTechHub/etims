@extends('admin.layouts.master')
@section('main-section')
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote-lite.css"> --}}
<link rel="stylesheet" href="{{ asset('assets/summernote/summernote-lite.css') }}">

<div class="container">
  <div class="page-inner">
    <div class="page-header">
      <h3 class="fw-bold mb-3">Edit SiteContent</h3>
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
          <a href="#">Edit SiteContent</a>
        </li>
      </ul>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
              <form action="{{ route('admin.upload_content') }}" method="post" enctype="multipart/form-data">
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
                            <input type="hidden" name="cid" value="{{$data->id}}">
                            <div class="row">
                              <div class="col-md-4">
                      <div class="form-group">
                        <label>Language <span class="text-danger">* </span></label>
                        <div class="controls">
                          <select name="language" class="form-control">
                            {{-- <option selected value="">--Please Select--</option> --}}
                            <option  value="English" @selected(isset($data) && $data->language == 'English')>English</option>
                            {{-- <option  value="Hindi" @selected(isset($data) && $data->language == 'Hindi')>Hindi</option> --}}
                          </select> 
                          <span class="text-danger"> @error('language') {{$message}} @enderror </span>
                        </div>
                        @error('language') <div class="alert alert-danger mt-1 mb-1"> {{ $message }}</div> @enderror
                      </div>
                    </div>
                      </div>
                      {{-- <div class="form-group">
                          <label>Content Type <span class="text-danger">*</span></label>
                          <div class="controls">
                            <select name="con_type" id="con_type" class="form-control">
                              <option selected value="">--Please Select Content Type--</option>
                              <option value="Content">Upload Content</option>
                              <option value="Page">Create Page</option>
                            </select>
                            <span class="text-danger"> @error('con_type') {{$message}} @enderror </span>
                          </div>
                      </div> --}}
                      @if($data->content_type == 'Content')
                      <div class="row">
                      <div class="col-md-4">
                      <div class="form-group">
                          <label>Main Menu Name <span class="text-danger">*</span></label>
                          <div class="controls">
                            <select name="menu_url" id="main_cat" class="form-control">
                              <option selected value="">-Select Main Menu-</option>
                              @foreach($menus as $ddt)
                              <option value="{{$ddt->menu_url}}" @selected(isset($data) && $data->menu_url == $ddt->menu_url)>{{$ddt->menu_name}}</option>
                              @endforeach
                            </select> 
                            <span class="text-danger"> @error('menu_url') {{$message}} @enderror </span>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group" id="mee">
                          <label>SubMenu Name (If Any) <span class="text-danger">*</span></label>
                          <div class="controls">
                              <select name="submenu_url" id="sub" class="form-control sub">
                            @foreach($submenu as $ddt)
                              <option value="{{$ddt->submenu_url}}" @selected(isset($data) && $data->submenu_url == $ddt->submenu_url)>{{$ddt->submenu_name}}</option>
                              @endforeach

                            </select> 
                            <span class="text-danger"> @error('submenu_url') {{$message}} @enderror </span>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group" id="chdl">
                          <label>ChildMenu Name (If Any) <span class="text-danger">*</span></label>
                          <div class="controls">
                              <select name="childmenu_url" id="child" class="form-control child">
                                @foreach($childmenus as $ddt)
                              <option value="{{$ddt->childmenu_url}}" @selected(isset($data) && $data->childmenu_url == $ddt->childmenu_url)>{{$ddt->childmenu_name}}</option>
                              @endforeach
                            </select> 
                        <span class="text-danger"> @error('childmenu_url') {{$message}} @enderror </span>
                        </div>
                      </div>
                    </div>
                    </div>
                    @endif


                    <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Content Heading <span class="text-danger">*</span></label>
                        <div class="controls">
                          <input type="text" name="heading" class="form-control" value="{{$data->heading}}">
                          <span class="text-danger"> @error('heading') {{$message}} @enderror </span>
                        </div>
                      </div>
                    </div>
                  @if($data->content_type == 'Page')
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Page Url <span class="text-danger">*</span></label>
                        <div class="controls">
                          <input type="text" name="page_url" class="form-control" value="{{$data->page_url}}" readonly>
                          <span class="text-danger"> @error('page_url') {{$message}} @enderror </span>
                        </div>
                      </div>
                    </div>
                  @endif
                      {{-- <div class="form-group" id="edtrpdf">
                        <label>Upload Related PDF <span class="text-danger">*</span></label>
                        <div class="controls">
                           <input type="file" name="image" class="form-control" accept="application/pdf">
                          <span class="text-danger"> @error('image') {{$message}} @enderror </span>
                        </div>
                      </div> --}}
                      <div class="col-md-12">
                      <div class="form-group" id="edtr">
                        
              <textarea class="textarea" id="summernote"  name="content" placeholder="Place some text here" style="width: 100%; height: 400px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{!! $data->content !!}</textarea>
                        <span class="text-danger"> @error('content') {{$message}} @enderror </span>
    
                      </div>
                    </div>
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
