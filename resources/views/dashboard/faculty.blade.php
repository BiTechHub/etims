@extends('admin.layouts.master')
@section('main-section')
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote-lite.css"> --}}
<link rel="stylesheet" href="{{ asset('assets/summernote/summernote-lite.css') }}">

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
     <table id="programme-table" class="table table-bordered table-hover">
        <thead>
        <tr>
            <th>ID</th>
          <th>Name</th>
            <th>Email</th>
            
        
            <th>Phone</th>
            <th>DOB</th>
           <th>Designation</th>
            <th>State</th>
            
           
        </tr>
    </thead>
        <tbody>
            <!-- Data will be loaded via AJAX -->
                @foreach ($user as $index => $item)
        <tr>
            <td>{{ $index + 1 }}</td> <!-- Index starts at 1 -->
           <td>{{$item->name}}</td>
           <td>{{$item->email}}</td>
           <td>{{$item->phone}}</td>
           <td>{{$item->dob}}</td>
           <td>{{$item->designation}}</td>
           
           <td>{{$item->state}}</td>
           
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
@endsection



