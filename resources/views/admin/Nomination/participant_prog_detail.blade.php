@extends('admin.layouts.master')
@section('main-section')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container">
  <div class="page-inner">
    <div class="page-header">
      <h3 class="fw-bold mb-3">Add SiteContent</h3>
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
          <a href="#">Add SiteContent</a>
        </li>
      </ul>
    </div>
    <div class="row">
      <div class="col-md-12">
      
      </div>
      <div class="card">
     <div class="card-body">
<div class="table-container table-responsive">
      <div class="row">
                @foreach ($getParticipant as $participant)
                    <div class="col-lg-6 mb-3">
                        <div class="card p-2">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <tbody>
                                        <tr>
                                            <th>Participant Name:</th>
                                            <td>{{ $participant->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Designation:</th>
                                            <td>{{ $participant->designation }}</td>
                                        </tr>
                                        <tr>
                                            <th>Email:</th>
                                            <td>{{ $participant->email }}</td>
                                        </tr>
                                        <tr>
                                            <th>Phone:</th>
                                            <td>{{ $participant->phone }}</td>
                                        </tr>
                                        <tr>
                                            <th>City:</th>
                                            <td>{{ $participant->city }}</td>
                                        </tr>
                                        <tr>
                                            <th>State:</th>
                                            <td>{{ $participant->state }}</td>
                                        </tr>

                                        <!-- Programme Details -->
                                        <tr>
                                            <th>Programme Title:</th>
                                            <td>{{ $participant->programme->title }}</td>
                                        </tr>
                                        <tr>
                                            <th>Programme Location:</th>
                                            <td>{{ $participant->programme->location }}</td>
                                        </tr>
                                        <tr>
                                            <th>Programme Venue:</th>
                                            <td>{{ $participant->programme->venue }}</td>
                                        </tr>
                                        <tr>
                                            <th>Programme Duration:</th>
                                            <td>{{ $participant->programme->duration }}</td>
                                        </tr>
                                        <tr>
                                            <th>From Date:</th>
                                            <td>{{ \Carbon\Carbon::parse($participant->programme->from_date)->format('d-m-Y') }}</td>
                                        </tr>
                                        <tr>
                                            <th>To Date:</th>
                                            <td>{{ \Carbon\Carbon::parse($participant->programme->to_date)->format('d-m-Y') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
</div>

     </div>

      </div>
    </div>
  </div>
</div>
@endsection
