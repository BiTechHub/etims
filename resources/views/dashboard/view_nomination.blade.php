@extends('admin.layouts.master')
@section('title','Programme list')
@push('styles')
<style>
/* ===============================
   GLOBAL GENERIC STYLING
================================= */
.form-container {
  margin: 20px !important;
  background: #fff;
  padding: 20px;
  border-radius: 16px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
}

/* Layout and Container */
.container-wrapper {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  width: 100%;
  box-sizing: border-box;
  font-size: 18px !important;
}

/* Sidebar Styling */
.sidebar {
  flex: 0 0 250px;
  background: #349e4c;
  padding: 20px;
  border-radius: 16px;
  color: white;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
  text-align: center;
}

.sidebar-title {
  font-size: 22px;
  font-weight: bold;
  display: flex;
  align-items: center;
  gap: 10px;
}

.sidebar-title i {
  font-size: 24px;
  color: #25ba43;
}

/* Form Styling */
.form-container {
  margin: 20px !important;
  flex: 1 1 auto;
  background: #fff;
  padding: 20px;
  border-radius: 16px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.form-container:hover {
  transform: translateY(-5px);
  box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
}

.form-title {
  font-size: 19px;
  font-weight: bold;
  margin: 5px 0 25px;
  padding: 3px 5px;
  text-transform: capitalize;
}

/* ===============================
   GENERIC TABLE STYLING
================================= */

.table-container {
  margin: 20px !important;
  padding: 20px;
  border-radius: 16px;
  font-size: 16px;
  background: #ffffff;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
  overflow-x: auto;
}

/* Add horizontal scroll on small screens */
.table-container::-webkit-scrollbar {
  height: 8px;
}

.table-container::-webkit-scrollbar-thumb {
  background-color: #ccc;
  border-radius: 8px;
}

/* Table Styles */
table {
  width: 100%;
  border-collapse: collapse;
  border-radius: 12px;
  overflow: hidden;
  min-width: 600px; /* Force horizontal scroll for small devices */
}

table thead {
  background: linear-gradient(135deg, #2c3e50, #4a6491);
  color: white;
  font-weight: bold;
}

table thead th {
  padding: 15px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  border: none;
  position: relative;
  text-align: center;
}

table thead th:not(:last-child)::after {
  content: "";
  position: absolute;
  right: 0;
  top: 25%;
  height: 50%;
  width: 1px;
  background: rgba(255, 255, 255, 0.2);
}

table tbody tr {
  background: white;
  transition: all 0.2s ease;
}

table tbody tr:nth-child(even) {
  background: #f9fafc;
}

table tbody tr:hover {
  background: #f1f7fe;
  transform: translateY(-1px);
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

table tbody td {
  padding: 12px 15px;
  border-bottom: 1px solid #eef2f7;
  vertical-align: middle;
  text-align: center;
}

/* ===============================
   BADGES
================================= */
.badge {
  padding: 6px 10px;
  border-radius: 12px;
  font-size: 12px;
  font-weight: 600;
  letter-spacing: 0.3px;
  display: inline-block;
}

.badge-success {
  background-color: #28a745;
  color: white;
}

.badge-danger {
  background-color: #dc3545;
  color: white;
}

/* ===============================
   BUTTONS
================================= */
.btn-action {
  padding: 6px 12px;
  font-size: 13px;
  border-radius: 6px;
  margin: 2px;
  transition: all 0.2s;
  color: white;
  text-decoration: none;
  display: inline-block;
}

.btn-action:hover {
  transform: translateY(-1px);
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

.btn-primary {
  background-color: #3490dc;
  border: none;
}

.btn-danger {
  background-color: #e3342f;
  border: none;
}

/* ===============================
   ALERTS
================================= */
.alert {
  position: relative;
  padding: 0.75rem 1.25rem;
  margin-bottom: 1rem;
  border: 1px solid transparent;
  border-radius: 0.25rem;
  transition: opacity 0.15s linear;
}

.alert-success {
  color: #155724;
  background-color: #d4edda;
  border-color: #c3e6cb;
}

.alert-danger {
  color: #721c24;
  background-color: #f8d7da;
  border-color: #f5c6cb;
}

.alert-dismissible {
  padding-right: 4rem;
}

.alert-dismissible .close {
  position: absolute;
  top: 0;
  right: 0;
  padding: 0.75rem 1.25rem;
  color: inherit;
}

/* ===============================
   RESPONSIVE ADJUSTMENTS
================================= */
@media (max-width: 768px) {
  .container-wrapper {
    flex-direction: column;
  }

  .sidebar {
    width: 100%;
  }

  table thead th,
  table tbody td {
    padding: 10px 8px;
    font-size: 14px;
  }

  .btn-action {
    padding: 4px 8px;
    font-size: 12px;
  }

  .table-container {
    padding: 10px;
  }

  table {
    min-width: 500px; /* Ensure scroll works */
  }
}
</style>
@endpush


@section('content')
{{-- <a href="{{route('programmeManagement.index')}}" class="btn btn-success mb-3" style="margin-left:20px">Add Programme</a> --}}
<!-- Add the status filter dropdown -->
<div class="row mb-3" style="margin-left: 20px; margin-right: 20px; margin-top:10px;">
    <!-- Status Filter -->


    <!-- Group Filter -->




    
    


    {{-- <div class="col-md-3">
        <label for="year-filter" class="form-label">Year</label>
        <select id="year-filter" class="form-control">
            <option value="">All Years</option>
            @for ($i = date('Y'); $i >= 2000; $i--)
                <option value="{{ $i }}">{{ $i }}</option>
            @endfor
        </select>
    </div> --}}
    {{-- <div class="form-group col-md-3">
    <label for="fancial"><strong>Filter by Financial Year</strong></label>
    <select name="fancial" id="fancial" class="form-control" onchange="this.form.submit()">
        <option value="">-- All --</option>
        @foreach($fancial as $year)
            <option value="{{ $year }}" {{ request('fancial') == $year ? 'selected' : '' }}>
                {{ $year }}
            </option>
        @endforeach
    </select>
</div> --}}
{{-- <div class="form-group col-md-3">
    <label for="status"><strong>Filter by Status</strong></label>
    <select name="status" id="status" class="form-control" onchange="this.form.submit()">
        <option value="">-- All --</option>
        @foreach($status as $stat)
            <option value="{{ $stat }}" {{ request('status') == $stat ? 'selected' : '' }}>
                {{ $stat }}
            </option>
        @endforeach
    </select>
</div> --}}


</div>

<div class="form-container" style="margin: 20px;" style="font-weight: bold">
    <form action="{{ route('dashboard.view.nomination', request()->route('id')) }}" method="GET" class="row g-3 align-items-end">
        @csrf
        <div class="col-md-2">
            <label for="agency_type" class="form-label">Agency Type</label>
            <select name="agency_type" id="agency_type" class="form-control">
                <option value="">All</option>
                @foreach($agencyTypes as $type)
                    <option value="{{ $type->id }}" {{ request('agency_type') == $type->id ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <label for="agency" class="form-label">Agency</label>
            <select name="agency" id="agency" class="form-control">
                <option value="">All</option>
                @foreach($agencies as $agency)
                    <option value="{{ $agency->id }}" {{ request('agency') == $agency->id ? 'selected' : '' }}>
                        {{ $agency->name }}
                    </option>
                @endforeach
            </select>
        </div>


        <div class="col-md-2">
    <label for="status" class="form-label">Status</label>
    <select name="status" id="status" class="form-control">
        <option value="">All</option>
        <option value="confirm" {{ request('status') == 'confirm' ? 'selected' : '' }}>Confirm</option>
    </select>
</div>

        <div class="col-md-3">
            <label for="search" class="form-label">Search (Name, Email or Phone)</label>
            <input type="text" name="search" id="search" class="form-control"
                value="{{ request('search') }}" placeholder="Enter name, email or phone">
        </div>

        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100" style="margin-top:20px">Filter</button>
        </div>
    </form>
</div>


<div class="table-container" style="margin: 20px;">
    <div class="table-scroll-wrapper">
    <table id="programme-table" class="table table-bordered table-hover">
        <thead>
        <tr>
            <th>ID</th>
          <th>Programme Code</th>
            <th>Programme Name</th>
            
        
            <th>Agency Type </th>
            <th>Agency </th>
           <th>Status</th>
            <th>Name</th>
            <th>Email</th>
            <th>Designation</th>
            <th>Phone</th>
            <th>Gender</th>
            <th>City</th>
            <th>State</th>
         
             <th>Checked In At</th>
            <th>Checkout Time</th>
           
        </tr>
    </thead>
        <tbody>
            <!-- Data will be loaded via AJAX -->
                @foreach ($participant as $index => $item)
        <tr>
            <td>{{ $index + 1 }}</td> <!-- Index starts at 1 -->
           <td>{{$item->programme->unique_id}}</td>
           <td>{{$item->programme->title}}</td>
           <td>{{$item->nomination->agencyType->name}}</td>
           <td>{{$item->nomination->agency->name}}</td>
           <td>{{$item->status}}</td>
           <td>{{$item->title}} {{$item->name}}</td>
           <td>{{$item->email}}</td>
           <td>{{$item->designation}}</td>
           <td>{{$item->phone}}</td>
           <td>{{$item->gender}}</td>
           <td>{{$item->city}}</td>
           <td>{{$item->state}}</td>
           <td>{{$item->checked_in_at}}</td>
           <td>{{$item->checked_out}}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
</div>




@endsection