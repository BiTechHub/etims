@extends('faculty.layouts.master')
@section('main-section')

<link rel="stylesheet" href="{{ asset('assets/summernote/summernote-lite.css') }}">
<div class="container">
  <div class="page-inner">
    <div class="page-header">
      <h3 class="fw-bold mb-3">Add SiteContent</h3>
      <ul class="breadcrumbs mb-3">
        <li class="nav-home"><a href="#"><i class="icon-home"></i></a></li>
        <li class="separator"><i class="icon-arrow-right"></i></li>
        <li class="nav-item"><a href="#">Manage SiteContent</a></li>
        <li class="separator"><i class="icon-arrow-right"></i></li>
        <li class="nav-item"><a href="#">Add SiteContent</a></li>
      </ul>
    </div>

    <div class="row">
      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
      @endif

      <div class="col-md-12">
        <div class="card">
          <div class="card-body">

@if(!empty($programme->manual_file))
  <!-- Reminder Button -->
  <form action="" method="POST" class="d-inline" id="reminderForm">
      @csrf
      <button type="submit" class="btn btn-info">Send Reminder</button>
  </form>

  <!-- Resend Form -->
  <form action="{{ route('announcement.store', $id) }}" method="POST" enctype="multipart/form-data" id="announcementForm">
      @csrf
      <input type="hidden" name="announcement_type" value="manual">

      <div class="mb-4 mt-3">
          <label for="manual_file" class="form-label">Upload Announcement Letter (PDF):</label>
          <!-- <input type="file" class="form-control" name="manual_file" id="manual_file" accept="application/pdf"> -->
        <input type="file" 
       class="form-control" 
       name="manual_file[]" 
       id="manual_file" 
       accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
       multiple>
      </div>

      <button type="submit" class="btn btn-warning" data-bs-toggle="modal">Resend</button>
  </form>
@else
  <!-- Submit Form -->
  <form action="{{ route('announcement.store', $id) }}" method="POST" enctype="multipart/form-data" id="announcementForm">
      @csrf
      <input type="hidden" name="announcement_type" value="manual">

      <div class="mb-4">
          <label for="manual_file" class="form-label">Upload Announcement Letter (PDF):</label>
          <!-- <input type="file" class="form-control" name="manual_file" id="manual_file" accept="application/pdf" required> -->
        <input type="file" 
       class="form-control" 
       name="manual_file[]" 
       id="manual_file" 
       accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
       multiple required>
      </div>

      <button type="submit" class="btn btn-success" data-bs-toggle="modal">Submit</button>
  </form>
@endif

<!-- Preview Last File -->
<!-- @if (!empty($programme->manual_file))
  <a href="{{ asset('announcements/' . $programme->manual_file) }}" target="_blank" class="btn btn-secondary mt-3">
    Preview Last Uploaded File
  </a>
@endif -->

            @php
    $files = is_array($programme->manual_file) 
        ? $programme->manual_file 
        : json_decode($programme->manual_file, true);
@endphp

@if (!empty($files))
    @foreach ($files as $file)
        <a href="{{ asset('announcements/' . $file) }}" 
           target="_blank" 
           class="btn btn-secondary mt-2">
            Preview {{ $file }}
        </a>
    @endforeach
@endif

          </div>
        </div>

        <!-- Announcement Dates Table -->
        <div class="card">
          <div class="card-body">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Announced Date</th>
                </tr>
              </thead>
              <tbody>
                @php
                  $dates = is_array($programme->announced_on)
                      ? $programme->announced_on
                      : (is_string($programme->announced_on) ? json_decode($programme->announced_on, true) : []);
                @endphp

                @forelse($dates as $index => $date)
                  <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($date)->format('d-m-Y') }}</td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="2" class="text-center">No announcements yet</td>
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

<!-- Modal -->
<div class="modal fade" id="announcementModal" tabindex="-1" aria-labelledby="announcementModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="announcementModalLabel">Confirm Submission</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h6 class="mb-3 text-primary">Agency types Involved:</h6>
        <ul class="list-group">
          @foreach ($agencies as $agency)
            <li class="list-group-item d-flex align-items-center">
              <i class="bi bi-building me-2 text-secondary"></i> {{ $agency->name }}
            </li>
          @endforeach
        </ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-success" id="proceedSubmit">Proceed</button>
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
  document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('announcementForm');
    const modalElement = document.getElementById('announcementModal');
    const modal = new bootstrap.Modal(modalElement);
    const proceedButton = document.getElementById('proceedSubmit');

    form.addEventListener('submit', function (e) {
      e.preventDefault(); // prevent default
      modal.show();       // show modal
    });

    proceedButton.addEventListener('click', function () {
      modal.hide();       // hide modal
      form.submit();      // submit form
    });
  });
</script>
@endsection
