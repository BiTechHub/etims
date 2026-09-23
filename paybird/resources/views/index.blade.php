@extends('admin.layouts.master')

@section('main-section')

<div class="container">
  <div class="page-inner">

    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
      <div>
        <h3 class="fw-bold mb-3">Dashboard</h3>
      </div>
    </div>

    <div class="row">

      @if(Auth::guard('admin')->user()->role === 'admin')

      <!-- Total Programme -->
     

      <!-- Active Programmes -->


      <!-- Total Faculty -->


      <!-- View Nomination -->


    <!--  Today Sales -->
<div class="col-sm-6 col-md-3 d-flex">
  <div class="card text-center h-100 w-100" 
       style="border-radius:12px; border:none; box-shadow:0 4px 15px rgba(0,0,0,0.08);">
    
    <div class="card-body" style="background:#fff; color:#000;">
      
      <div class="mb-3 fs-1" style="color:#333;">
        <i class="fas fa-clock"></i>
      </div>

      <h5 class="card-title fw-bold" style="font-size:20px;">
        ₹ {{ number_format($todaySales, 2) }}
      </h5>

      <h6 class="card-subtitle" style="color:#777;">Today's Amount</h6>

    </div>
  </div>
</div>
 <!--  Monthly Sales -->
<div class="col-sm-6 col-md-3 d-flex">
  <div class="card text-center h-100 w-100" 
       style="border-radius:12px; border:none; box-shadow:0 4px 15px rgba(0,0,0,0.08); transition:0.3s;">
    
    <div class="card-body" style="background:#fff; color:#000;">
      
      <div class="mb-3 fs-1" style="color:#333;">
        <i class="fas fa-calendar-alt"></i>
      </div>

      <h5 class="card-title fw-bold" style="font-size:20px;">
        ₹ {{ number_format($monthlySales, 2) }}
      </h5>

      <h6 class="card-subtitle" style="color:#777;">Monthly Amount</h6>

    </div>
  </div>
</div>

<!--  Total Sales -->
<div class="col-sm-6 col-md-3 d-flex">
  <div class="card text-center h-100 w-100" 
       style="border-radius:12px; border:none; box-shadow:0 4px 15px rgba(0,0,0,0.08); transition:0.3s;">
    
    <div class="card-body" style="background:#fff; color:#000;">
      
      <div class="mb-3 fs-1" style="color:#333;">
        <i class="fas fa-rupee-sign"></i>
      </div>

      <h5 class="card-title fw-bold" style="font-size:20px;">
        ₹ {{ number_format($totalSales, 2) }}
      </h5>

      <h6 class="card-subtitle" style="color:#777;">Total Amount</h6>

    </div>
  </div>
</div>

      @endif

    </div>
  </div>
</div>
@endsection