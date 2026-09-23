@extends('faculty.layouts.master')
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
        <div class="card">
          <div class="card-body">
            <h1 id="form-title"></h1>
               <form action="{{ route('faculty.assigned.programme', request()->route('id')) }}" method="GET" class="row g-3 align-items-end">
    @csrf

    <!-- Programme Status -->
    <div class="col-md-3">
        <label for="status" class="form-label">Programme Status</label>
        <select name="status" id="status" class="form-control">
            <option value="">All</option>
            @foreach($status as $s)
                <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>
                    {{ ucfirst($s) }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Programme Code -->
    <div class="col-md-3">
        <label for="unique_id" class="form-label">Programme Code</label>
        <input type="text" name="unique_id" id="unique_id" class="form-control" value="{{ request('unique_id') }}" placeholder="Enter programme code">
    </div>

    <!-- Department -->
    <div class="col-md-3">
        <label for="department" class="form-label">Department</label>
        <select name="department" id="department" class="form-control">
            <option value="">All</option>
            @foreach($department as $id => $name)
                <option value="{{ $id }}" {{ request('department') == $id ? 'selected' : '' }}>
                    {{ $name }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Financial Year -->
    <div class="col-md-3">
        <label for="financial" class="form-label">Financial Year</label>
        <select name="financial" id="financial" class="form-control">
            <option value="">All</option>
            @foreach($financial as $fy)
                <option value="{{ $fy }}" {{ request('financial') == $fy ? 'selected' : '' }}>
                    {{ $fy }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Submit Button -->
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100" style="margin-top: 20px;">Filter</button>
    </div>
</form>
            </div>
          
        </div>
      </div>  
      <div class="card">
     <div class="card-body">
<div class="table-container table-responsive">
     <table id="programme-table" class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>index</th>
                <th>Programme code</th>
              <th>Status</th>
                <th>Group Name</th>
                <th>Programme Title</th>
                <th>Sponsor Type</th>
               
                <th>Location</th>
                <th>Total class</th>

                 
              
            </tr>
        </thead>
        <tbody>
            <!-- Data will be loaded via AJAX -->
                @foreach ($programmes as $index => $item)
        <tr>
            <td>{{ $index + 1 }}</td> <!-- Index starts at 1 -->
            <td>{{$item->unique_id}}</td>
            <td>{{ $item->status}}</td>
            <td>       {{ $group[$item->group_id] ?? 'Unknown' }}</td>
            <td>{{$item->title}}</td>
             <td>       {{ $sponsor[$item->sponsor_id] ?? 'Unknown' }}</td>
             <td>{{$item->venue}}</td>
             <td>{{ $programmeIdCounts[$item->id] ?? 0 }} Classes</td>
             
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
