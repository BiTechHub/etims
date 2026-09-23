@extends('admin.layouts.master')
@section('main-section')
<div class="container">
  <div class="page-inner">
    <div class="page-header">
      <h3 class="fw-bold mb-3">Event & Announcements</h3>
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
          <a href="#">Event & Announcements</a>
        </li>
      </ul>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <form action="{{ route('admin.news_create') }}" method="post" enctype="multipart/form-data">
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
                <div class="col-md-6">
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


                <div class="col-md-6">
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
                    <label>Published Date <span class="text-danger">*</span></label>
                    <div class="controls">
                      <input type="date" name="published_date" class="form-control">
                      <span class="text-danger"> @error('published_date') {{$message}} @enderror </span>
                    </div>
                  </div>
                </div>


                <div class="col-md-3">
                  <div class="form-group">
                    <label>Expiry Date</label>
                    <div class="controls">
                      <input type="date" name="expiry_date" class="form-control">
                      <span class="text-danger"> @error('expiry_date') {{$message}} @enderror </span>
                    </div>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group form-group-default1">
                    <label>News Type</label>
                    <select class="form-select" id="news_type" name="news_type">
                      <option value="">Select News Type</option>
                      <option value="Latest News">Latest News</option>
                      <option value="Event & Announcements">Event & Announcements</option>
                      <option value="Both">Both</option>
                      <option value="Our Projects">Our Projects</option>
                    </select>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group form-group-default1">
                    <label>Reference Type</label>
                    <select class="form-select" id="reference_type" name="reference_type">
                      <option value="">Select Reference Type</option>
                      <option value="PDF">Upload File</option>
                      <option value="URL">Reference URL</option>
                    </select>
                  </div>
                </div>

                <div class="row" id="pdf_tab" style="display: none;">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Attach File <span class="text-danger">*</span></label>
                      <div class="controls">
                        <input type="file" name="attachment" class="form-control">
                        <span class="text-danger"> @error('attachment') {{$message}} @enderror </span>
                      </div>
                    </div> 
                  </div>

                  {{-- <div class="col-md-6">
                    <div class="form-group">
                      <label>Attach File in Hindi <span class="text-danger">*</span></label>
                      <div class="controls">
                        <input type="file" name="attachment_hi" class="form-control" accept="application/pdf">
                        <span class="text-danger"> @error('attachment_hi') {{$message}} @enderror </span>
                      </div>
                    </div> 
                  </div>   --}}
                </div>

                <div class="row" id="url_tab" style="display: none;">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Attach URL <span class="text-danger">*</span></label>
                      <div class="controls">
                        <input type="text" name="attach_url" class="form-control" >
                        <span class="text-danger"> @error('attach_url') {{$message}} @enderror </span>
                      </div>
                    </div> 
                  </div>

                  {{-- <div class="col-md-6">
                    <div class="form-group">
                      <label>Attach URL (Hindi) <span class="text-danger">*</span></label>
                      <div class="controls">
                        <input type="text" name="attach_url_hi" class="form-control">
                        <span class="text-danger"> @error('attach_url_hi') {{$message}} @enderror </span>
                      </div>
                    </div> 
                  </div>   --}}
                </div>

                <div class="row">

                </div>
                <div class="row">
                  <div class="col-md-12">
                    <br>
                    <div class="text-center">
                      <button type="submit" class="btn btn-info">Submit</button>
                    </div>
                  </div>
                </div>
                </form>
              </div>

          </div>
        </div>



        <div class="col-md-12">
          <div class="card shadow">
            <div class="card-body">
              @if(session('success'))
              <div class="alert alert-success">{{ session('success') }}</div>
              @endif

              <div class="table-responsive">
                <table class="display table table-striped table-hover dataTable no-footer" id="add-row">
                  <thead class="thead-dark">
                    <tr>
                      <th>#</th>
                      <th>Title</th>
                      {{-- <th>Title (HI)</th> --}}
                      <th>News Type</th>
                      <th>Reference Type</th>
                      <th>Published</th>
                      <th>Expires</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($data as $key => $news)
                    <tr>
                      <td>{{ $key + 1 }}</td>
                      <td>{{ $news->title }}</td>
                      {{-- <td>{{ $news->title_hi }}</td> --}}
                      <td>{{ $news->news_type ?? '-' }}</td>
                      <td>{{ $news->reference_type }}</td>
                      <td>{{ \Carbon\Carbon::parse($news->published_date)->format('d-m-Y') ?? '' }}</td>
                      <td>{{ \Carbon\Carbon::parse($news->expiry_date)->format('d-m-Y') ?? '' }}</td>
                      <td>
                        <div class="form-button-action">
                          <!-- Edit Button -->
                          <a href="{{ url('/admin-panel/news_edit/' . $news->id) }}" data-bs-toggle="tooltip" title="Edit" class="btn btn-primary btn-sm">
                            <i class="fa fa-edit"></i>
                          </a>

                          &nbsp;&nbsp;

                          <!-- Delete Button -->
                          <form action="{{ url('/admin-panel/news_delete/' . $news->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this news item?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" data-bs-toggle="tooltip" title="Remove" class="btn btn-danger btn-sm">
                              <i class="fa fa-times"></i>
                            </button>
                          </form>
                        </div>  
                      </td>
                    </tr>
                    @endforeach
                    @if(count($data) == 0)
                    <tr><td colspan="8" class="text-center">No news found.</td></tr>
                    @endif
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
     

      var action =
          '<td><div class="form-button-action"><button type="button" data-bs-toggle="tooltip" title="Edit" class="btn btn-link btn-primary btn-lg"><i class="fa fa-edit"></i></button><button type="button" data-bs-toggle="tooltip" title="Remove" class="btn btn-link btn-danger"><i class="fa fa-times"></i></button></div></td>';


      // Fetch submenus when parent menu is selected
      $("#reference_type").change(function () {
        var parentMenuUrl = $(this).val();

        if (parentMenuUrl === 'URL') {
          $('#url_tab').show();
          $('#pdf_tab').hide();
        } else {
          $('#pdf_tab').show();
          $('#url_tab').hide();
        }
      });


    });
  </script>
  @endsection
