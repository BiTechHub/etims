@extends('Agency.layouts.master')

@section('main-section')

{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote-lite.css"> --}}
<link rel="stylesheet" href="{{ asset('assets/summernote/summernote-lite.css') }}">

<div class="container">

  <div class="page-inner">

    <div class="page-header">

      <h3 class="fw-bold mb-3">Nomination Details</h3>

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

          <a href="#">Nomination</a>

        </li>

        <li class="separator">

          <i class="icon-arrow-right"></i>

        </li>

        <li class="nav-item">

          <a href="#">Nomination Details</a>

        </li>

      </ul>

    </div>

    <div class="row">

      <div class="col-md-12">

        <div class="card">

          <div class="card-body">

            <div class="card-header">

              <h1>Nomination Details</h1>

            </div>

            <form action="{{ route('ccavenue.initiate') }}" method="post" style="margin:10px;">

         <p><strong>Agency:</strong> {{$nomination->agency->name}}</p>

                    <p><strong>Programme:</strong> {{ $nomination->programme->title }}</p>

                    <p><strong>Nomination Date:</strong> {{ $nomination->nomination_date }}</p>

                    <p><strong>Rate per Person:</strong> ₹{{ $feeStructure }}</p>

                    <p><strong>Total Person</strong> {{   $participantCount}}</p>

                    <p><strong>Discount</strong> {{$discount}}</p>

                    <p><strong>Total Amount:</strong> ₹{{ $total }}</p>

            </div>

            <input type="hidden" name="company_name" value="$nomination->agency->name">

            <input type="hidden" name="amount" value="{{$total}}">

            <input type="hidden" name="firm_name" value="{{$nomination->agency->name}}">

            <input type="hidden" name="nomination_id" value="{{$nominationId}}">

            <button type="submit" class="btn btn-success btn-lg">Make Payment</button>

              {{-- <a href="{{ route('agency.pay', $nominationId) }}" class="btn btn-primary">

            Make Payment

        </a> --}}



         </form>

          

        </div>

      </div>

 

    </div>

  </div>

</div>

@endsection