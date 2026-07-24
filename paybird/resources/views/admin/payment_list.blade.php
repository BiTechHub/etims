@extends('admin.layouts.master')
@section('main-section')
<div class="container">
  <div class="page-inner">
    <div class="page-header">
      <h3 class="fw-bold mb-3">Payment List</h3>
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
          <a href="#">Payment List</a>
        </li>
        <li class="separator">
          <i class="icon-arrow-right"></i>
        </li>
        <li class="nav-item">
          <a href="#">View Payments</a>
        </li>
      </ul>
    </div>
    <div class="row">
      
     <div class="card">
    <div class="card-header">
        <h4 class="card-title">Payment List</h4>
    </div>

    <div class="card-body">

        <div class="table-responsive">

            <table id="add-row" class="table table-striped table-hover">

                <thead class="table-dark">

                    <tr>

                        <th>ID</th>
                        <th>Order ID</th>
                        <th>Amount</th>
                        <th>Name</th>
                        <th>City</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Date</th>

                    </tr>

                </thead>

                <tbody>

                @forelse($pays as $pay)

                    <tr>

                        <td>{{ $pay->id }}</td>

                        <td>{{ $pay->order_id }}</td>

                        <td>
                            ₹ {{ number_format($pay->amount,2) }}
                        </td>

                        <td>{{ $pay->billing_name }}</td>

                        <td>{{ $pay->billing_city }}</td>

                        <td>{{ $pay->billing_email }}</td>

                        <td>

                            @if($pay->status=='Success')

                                <span class="badge bg-success">
                                    Success
                                </span>

                            @elseif($pay->status=='Pending')

                                <span class="badge bg-warning text-dark">
                                    Pending
                                </span>

                            @else

                                <span class="badge bg-danger">
                                    Failed
                                </span>

                            @endif

                        </td>

                        <td>{{ date('d-m-Y H:i',strtotime($pay->created_at)) }}</td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="8" class="text-center">
                            No Payment Found
                        </td>

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
@endsection
