@extends('faculty.layouts.master')

@section('main-section')
<div class="container">
  <div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
      <div>
        <h3 class="fw-bold mb-3">Dashboard</h3>
      </div>
    </div>

    <div class="row">

      <!-- Assigned Programmes -->
      <div class="col-sm-6 col-md-3 d-flex">
        <div class="card text-center shadow h-100 w-100">
          <div class="card-body">
            <div class="mb-3 text-primary fs-1">
              <i class="fas fa-users"></i>
            </div>
            <h5 class="card-title">{{ $programmes->count() }}</h5>
            <h6 class="card-subtitle mb-2 text-muted">Assigned Programmes</h6>
            <p class="card-text small">Check total and active assigned programmes.</p>
          </div>
          <div class="card-footer bg-transparent border-top-0">
            <a href="{{ route('faculty.assigned.programme') }}" class="btn btn-outline-primary btn-sm">View Details</a>
          </div>
        </div>
      </div>

      <!-- Active Programmes -->
      <div class="col-sm-6 col-md-3 d-flex">
        <div class="card text-center shadow h-100 w-100">
          <div class="card-body">
            <div class="mb-3 text-success fs-1">
              <i class="fas fa-play-circle"></i>
            </div>
            <h5 class="card-title">{{ $activeProgrammes->count() }}</h5>
            <h6 class="card-subtitle mb-2 text-muted">Active Programmes</h6>
            <p class="card-text small">Currently ongoing training programmes.</p>
          </div>
          <div class="card-footer bg-transparent border-top-0">
            <a href="{{ route('faculty.active.programme') }}" class="btn btn-outline-success btn-sm">View Details</a>
          </div>
        </div>
      </div>

      <!-- Total Faculty -->
      {{-- <div class="col-sm-6 col-md-3 d-flex">
        <div class="card text-center shadow h-100 w-100">
          <div class="card-body">
            <div class="mb-3 text-info fs-1">
              <i class="fas fa-user-tie"></i>
            </div>
            <h5 class="card-title">--</h5>
            <h6 class="card-subtitle mb-2 text-muted">Total Faculty</h6>
            <p class="card-text small">View all faculty members.</p>
          </div>
          <div class="card-footer bg-transparent border-top-0">
            <a href="#" class="btn btn-outline-info btn-sm">View Details</a>
          </div>
        </div>
      </div> --}}

    </div>
  </div>
</div>
@endsection
@section('script')
<script src="{{ url('/admin') }}/assets/js/plugin/datatables/datatables.min.js"></script>
<script src="{{ url('/admin') }}/assets/js/kaiadmin.min.js"></script>
<script src="{{ url('/admin') }}/assets/js/setting-demo2.js"></script>
@endsection