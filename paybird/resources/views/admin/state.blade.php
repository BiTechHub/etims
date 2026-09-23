@extends('admin.layouts.master')
@section('main-section')
<div class="container">
    <div class="page-inner">

        <div class="page-header">
            <h3 class="fw-bold mb-3">State & District Management</h3>

            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="#"><i class="icon-home"></i></a>
                </li>

                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>

                <li class="nav-item">
                    <a href="#">District</a>
                </li>
            </ul>
        </div>
 <div class="card">

    <div class="card-header">

        <div class="d-flex justify-content-between align-items-center flex-wrap">

            <h4 class="card-title mb-2">
                District List
            </h4>

            <div class="d-flex gap-2">

                <form method="GET">

                    <select
                        name="state_id"
                        class="form-select"
                        onchange="this.form.submit()">

                        <option value="">All States</option>

                        @foreach($states as $state)

                            <option value="{{ $state->id }}"
                                {{ request('state_id')==$state->id?'selected':'' }}>

                                {{ $state->name }}

                            </option>

                        @endforeach

                    </select>

                </form>

                <button
                    class="btn btn-primary"
                    onclick="toggleForm()">

                    <i class="fa fa-plus"></i>

                    Add District

                </button>

            </div>

        </div>

    </div>

    <div class="card-body border-bottom" id="formBox" style="display:none;">

<form id="districtForm"
      action="{{ route('district.store') }}"
      method="POST">

@csrf

<div class="row">

<div class="col-md-5">

<label class="form-label">
State
</label>

<select
name="state_id"
class="form-select">

@foreach($states as $state)

<option value="{{ $state->id }}">

{{ $state->name }}

</option>

@endforeach

</select>

</div>

<div class="col-md-5">

<label class="form-label">

District Name

</label>

<input
type="text"
name="name"
class="form-control"
required>

</div>

<div class="col-md-2 d-flex align-items-end">

<button class="btn btn-success w-100">

<i class="fa fa-save"></i>

Save

</button>

</div>

</div>

</form>

</div>

<div class="card-body">

<div class="table-responsive">

<table
id="add-row"
class="table table-striped table-hover">

<thead>

<tr>

<th>#</th>

<th>State</th>

<th>District</th>

<th width="120">Action</th>

</tr>

</thead>

<tbody>

@php $count=1; @endphp

@foreach($states as $state)

@if($state->districts->count())

<tr class="table-secondary">

<td colspan="4">

<strong>

{{ $state->name }}

</strong>

</td>

</tr>

@foreach($state->districts as $district)

<tr>

<td>{{ $count++ }}</td>

<td>{{ $state->name }}</td>

<td>{{ $district->name }}</td>

<td>

<button
class="btn btn-warning btn-sm"
onclick="editDistrict({{ $district->id }},'{{ $district->name }}',{{ $state->id }})">

<i class="fa fa-edit"></i>

Edit

</button>

</td>

</tr>

@endforeach

@endif

@endforeach

</tbody>

</table>

</div>

</div>


function toggleForm(){

    let form=document.getElementById("formBox");

    form.style.display=
    form.style.display==="none" || form.style.display==""
    ? "block"
    : "none";

}

function editDistrict(id,name,state){

    document.getElementById("formBox").style.display="block";

    document.querySelector("#districtForm input[name='name']").value=name;

    document.querySelector("#districtForm select[name='state_id']").value=state;

    document.getElementById("districtForm").action="/admin/district/update/"+id;

}

@endsection