<link rel="stylesheet" href="{{ asset('assets/summernote/summernote-lite.css') }}">

<style>
/* Background */
body {
    background: linear-gradient(135deg, #7fb3c8, #b8d7e5);
    font-family: 'Segoe UI', sans-serif;
}

/* Card Design */
.payment-card {
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    overflow: hidden;
}
  .payment-card {
    max-width: 50%;
    margin: auto;
}

/* Header */
.payment-header {
    background: linear-gradient(45deg, #28a745, #218838);
    color: #fff;
    padding: 20px;
    text-align: center;
}

/* Details */
.payment-details p {
    font-size: 18px;
    margin-bottom: 10px;
}

/* Amount Highlight */
.amount {
    font-size: 28px;
    font-weight: bold;
    color: #28a745;
}

/* Button */
.pay-btn {
    width: 100%;
    padding: 12px;
    font-size: 18px;
    border-radius: 10px;
    transition: 0.3s;
}

.pay-btn:hover {
    transform: scale(1.05);
}

/* Responsive */
@media(max-width: 768px){
    .payment-details p {
        font-size: 16px;
    }
}
</style>

<div class="container py-5">
  <div class="row justify-content-center align-items-center" style="min-height: 80vh;">
    
    <div class="col-lg-5 col-md-7 col-sm-10">
      <div class="card payment-card">
        
        <!-- Header -->
        <div class="payment-header">
          <h3>Confirm Payment</h3>
        </div>

        <!-- Body -->
        <div class="card-body text-center" style="padding: 20px;background: white;">
          
          <form action="{{ route('ccavenue.initiate') }}" method="post">
            @csrf

            <div class="payment-details mb-4">
              <p><strong>Name:</strong> {{ $house->first_name }} {{ $house->last_name }}</p>
              <p><strong>Mobile:</strong> {{ $house->phone }}</p>
              <p><strong>Mail:</strong> {{ $house->email }}</p>
              <p><strong>Branch:</strong> {{ $house->branch }}</p>
              <p><strong>State:</strong> {{ $house->state }}</p>
              <p><strong>Organization:</strong> {{ $house->organization }}</p>
              <p><strong>Total Amount:</strong> <span class="amount">₹{{ $house->payment }}</span></p>
              
            </div>

            <!-- Hidden Fields -->
            <input type="hidden" name="company_name" value="{{ $house->first_name }}">
            <input type="hidden" name="email" value="{{ $house->email }}">
            <input type="hidden" name="mobile" value="{{ $house->phone }}">
            <input type="hidden" name="amount" value="{{ $house->payment }}">
            <input type="hidden" name="firm_name" value="{{ $house->first_name }}">
             <input type="hidden" name="address" value="{{ $house->state }}, {{ $house->Country }}">
             <input type="hidden" name="state" value="{{ $house->state }}">
            <input type="hidden" name="contr_id" value="{{ $house->id }}">

            <!-- Button -->
            <button type="submit" class="btn btn-success pay-btn">
              💳 Proceed to Payment
            </button>

          </form>

        </div>

      </div>
    </div>

  </div>
</div>