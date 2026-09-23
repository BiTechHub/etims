@extends('admin.layouts.master')
@section('main-section')

<style>
	.table thead th {
		 font-size: 11px;
	}
	.table td, .table th {
    font-size: 11px;
}
</style>
	
<link rel="stylesheet" href="{{ asset('assets/summernote/summernote-lite.css') }}">

<div class="container">
  <div class="page-inner">
    <div class="page-header">
      <h3 class="fw-bold mb-3">Add Guest Faculty</h3>
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
          <a href="#">Master</a>
        </li>
        <li class="separator">
          <i class="icon-arrow-right"></i>
        </li>
        <li class="nav-item">
          <a href="#">Add Guest Faculty</a>
        </li>
      </ul>
    </div>
    <div class="row">
 @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <h1 id="form-title"></h1>
              <form id="agency-group-form" action="{{route('guest.store')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-4 form-group">
                    <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="{{ old('name') }}" required>
                </div>

                
        
                <div class="col-md-4 form-group">
                    <label for="designation" class="form-label">Designation </label>
                    <input type="text" class="form-control" id="designation" name="designation" placeholder="Enter Designation" value="{{ old('designation') }}" >
                </div>
        
                <div class="col-md-4 form-group">
                    <label for="dob" class="form-label">Date of Birth</label>
                    <input type="date" class="form-control" id="dob" name="dob" value="{{ old('dob') }}">
                </div>
            </div>
        
            <div class="row">
                <div class="col-md-4 form-group">
                    <label for="phone" class="form-label">Phone No <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter Phone No" value="{{ old('phone') }}" required>
                </div>
        
                <div class="col-md-4 form-group">
                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" value="{{ old('email') }}" required>
                </div>
        
                <div class="col-md-4 form-group">
                    <label for="address" class="form-label">Address </label>
                    <textarea class="form-control" id="address" name="address" placeholder="Enter Address" rows="2" >{{ old('address') }}</textarea>
                </div>
            </div>
        
            <!-- New Address Info Fields -->
            <div class="row">
          <div class="col-md-4 form-group">
    <label for="state" class="form-label">State <span class="text-danger">*</span></label>
    <select class="form-control" id="state" name="state" required>
        <option value="">Select State</option>
        {{-- States will be loaded via AJAX --}}
    </select>
</div>

<div class="col-md-4 form-group">
    <label for="city" class="form-label">City <span class="text-danger">*</span></label>
    <select class="form-control" id="city" name="city" required>
        <option value="">Select City</option>
        {{-- Cities will be loaded based on selected state --}}
    </select>
</div>

{{--         
                <div class="col-md-4 form-group">
                    <label for="pincode" class="form-label">Pincode No <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="pincode" name="pincode" placeholder="Enter Pincode" value="{{ old('pincode') }}" required>
                </div>
            </div> --}}

            <div class="col-md-4 form-group">
                    <label for="bankname" class="form-label">Name as per the bank<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="acount_holder_name" name="acount_holder_name" placeholder="Enter Name" value="{{ old('acount_holder_name') }}" required>
                </div>
        
            <!-- Bank Info Section -->
            <div class="row">
				 <div class="col-md-4 form-group">
                    <label for="bank_name" class="form-label">Bank name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="bank_name" name="bank_name" placeholder="Enter Bank Name" value="{{ old('bank_name') }}" required>
                </div>
                <div class="col-md-4 form-group">
                    <label for="account_no" class="form-label">Bank Account No <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="account_no" name="account_no" placeholder="Enter Account No" value="{{ old('account_no') }}" required>
                </div>
        
                <div class="col-md-4 form-group">
                    <label for="ifsc" class="form-label">IFSC Code <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="ifsc" name="ifsc" placeholder="Enter IFSC Code" value="{{ old('ifsc') }}" required>
                </div>
        
                <div class="col-md-4 form-group">
                    <label for="branch_name" class="form-label">Branch Name </label>
                    <input type="text" class="form-control" id="branch_name" name="branch_name" placeholder="Enter Branch Name" value="{{ old('branch_name') }}" required>
                </div>
          
                <div class="col-md-4 form-group">
                    <label for="bank_address" class="form-label">Bank Address </label>
                    <textarea class="form-control" id="bank_address" name="bank_address" placeholder="Enter Bank Address" rows="2" required>{{ old('bank_address') }}</textarea>
                </div>
        
                <div class="col-md-4 form-group">
                    <label for="kyc" class="form-label">KYC Document(s)</label>
                    <input type="file" class="form-control" id="kyc" name="kyc[]" multiple>
                </div>
                    <div class="col-md-4 form-group">
                    <label for="specialization" class="form-label">Specialization</label>
                    <input type="text" class="form-control" id="specialization" name="specialization" placeholder="Enter Your specialization" rows="2" >{{ old('specialization') }}</input>
                </div>
                
            </div>
            <div class="row">
                  <div class="col-md-4 form-group">
                    <label for="cv" class="form-label">CV/Resume</label>
                    <input type="file" class="form-control" id="cv" name="cv" multiple>
                </div>
            </div>
        
            <div class="row mt-4">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
            </div>
          
        </div>
      </div>
      <div class="card">
     <div class="card-body">
<div class="table-container table-responsive">
 <table id="agency-group-table" class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Designation</th>
               
                <th>Phone</th>
                <th>Email</th>
             
                <th>Account No</th>
                <th>Branch Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

     </div>

      </div>
    </div>
  </div>
</div>

<!-- Bank Detail Modal -->
<div class="modal fade" id="bankDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Bank Details</h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body">

                <table class="table table-bordered">

                    <tr>
                        <th>Branch Name</th>
                        <td id="modal_branch_name"></td>
                    </tr>

                    <tr>
                        <th>Bank Name</th>
                        <td id="modal_bank_name"></td>
                    </tr>

                    <tr>
                        <th>Account Holder</th>
                        <td id="modal_account_holder"></td>
                    </tr>

                    <tr>
                        <th>IFSC</th>
                        <td id="modal_ifsc"></td>
                    </tr>

                    <tr>
                        <th>Bank Address</th>
                        <td id="modal_bank_address"></td>
                    </tr>

                </table>

            </div>

        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{ url('/admin') }}/assets/js/plugin/datatables/datatables.min.js"></script>
<script src="{{ url('/admin') }}/assets/js/kaiadmin.min.js"></script>
<script src="{{ url('/admin') }}/assets/js/setting-demo2.js"></script>
<script src="{{ asset('assets/summernote/summernote-lite.js') }}"></script>

<script>
	$(document).on('click', '.view-bank-detail', function () {

    // Get Data Attributes
    let branch_name = $(this).data('branch_name');
    let bank_name = $(this).data('bank_name');
    let account_holder = $(this).data('acount_holder_name');
    let ifsc = $(this).data('ifsc');
    let bank_address = $(this).data('bank_address');

    // Set Modal Data
    $('#modal_branch_name').text(branch_name);
    $('#modal_bank_name').text(bank_name);
    $('#modal_account_holder').text(account_holder);
    $('#modal_ifsc').text(ifsc);
    $('#modal_bank_address').text(bank_address);

    // Open Modal
    $('#bankDetailModal').modal('show');
});
</script>
<script>
    $(document).ready(function () {
        // CSRF Token setup for AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Initialize DataTable
        const table = $('#agency-group-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("guest.getData") }}',
                type: 'GET'
            },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name' },
                { data: 'designation', name: 'designation' },
                { data: 'phone', name: 'phone' },
                { data: 'email', name: 'email' },
                { data: 'account_no', name: 'account_no' },
                { data: 'branch_name', name: 'branch_name' },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {
                        return `
                            <div class="btn-group">
                                <button 
                                    class="btn btn-sm btn-primary btn-action edit-btn" 
                                    data-id="${row.id}"
                                    data-name="${row.name ?? ''}"
                                    data-designation="${row.designation ?? ''}"
                                    data-dob="${row.dob ?? ''}"
                                    data-phone="${row.phone ?? ''}"
                                    data-email="${row.email ?? ''}"
                                    data-address="${row.address ?? ''}"
                                    data-state="${row.state ?? ''}"
                                    data-city="${row.city ?? ''}"
                                    data-bankname="${row.bank_name ?? ''}"
                                    data-pincode="${row.pincode ?? ''}"
                                    data-account_no="${row.account_no ?? ''}"
                                    data-ifsc="${row.ifsc ?? ''}"
                                    data-branch_name="${row.branch_name ?? ''}"
                                    data-bank_address="${row.bank_address ?? ''}"
                                    data-kyc="${row.kyc ?? ''}"
                                    data-specialization="${row.specialization}"
                                >
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="btn btn-sm btn-danger delete-btn btn-action" data-id="${row.id}">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
								 <button class="btn btn-sm btn-success view-bank-detail" data-id="${row.id}"  data-branch_name="${row.branch_name ?? ''}"  data-bank_name="${row.bank_name ?? ''}"  data-acount_holder_name="${row.acount_holder_name ?? ''}"  data-ifsc="${row.ifsc ?? ''}" data-bank_address="${row.bank_address ?? ''}" >
                                    <i class="fas fa-eye"></i> Bank
                                </button>
								
                            </div>
                        `;
                    }
                }
            ]
        });

        // Edit button handler
        $('#agency-group-table').on('click', '.edit-btn', function () {
            const id = $(this).data('id');
            const name = $(this).data('name');
            const designation = $(this).data('designation');
            const dob = $(this).data('dob');
            const phone = $(this).data('phone');
            const email = $(this).data('email');
            const address = $(this).data('address');
            const state = $(this).data('state');
            const city = $(this).data('city');
            const  bank= $(this).data('bankname');
            const pincode = $(this).data('pincode');
            const account_no = $(this).data('account_no');
            const ifsc = $(this).data('ifsc');
            const branch_name = $(this).data('branch_name');
            const bank_address = $(this).data('bank_address');
            const kyc = $(this).data('kyc');
            const specialization = $(this).data('specialization');

            // Change form title
            $('#form-title').text('✏️ Edit Guest Faculty');

            // Update form action
            $('#agency-group-form').attr('action', '{{ url("admin/guest/edit") }}/' + id);

            // Populate form fields
            $('#name').val(name);
            $('#designation').val(designation);
            $('#dob').val(dob);
            $('#phone').val(phone); 
            $('#email').val(email);
            $('#address').val(address);
            $('#state').val(state);
            $('#city').val(city);
            $('#bankname').val(bank);
            $('#pincode').val(pincode);
            $('#account_no').val(account_no);
            $('#ifsc').val(ifsc);
            $('#branch_name').val(branch_name);
            $('#bank_address').val(bank_address);
            $('#specialization').val(specialization);
            // KYC preview
            if (kyc) {
                $('#kyc-preview').html(`<a href="${kyc}" target="_blank">View KYC</a>`);
            }

            // Add _method input for Laravel if needed
            $('#agency-group-form').append('<input type="hidden" name="_method" value="POST">');
        });


  // Handle delete button click
$('#agency-group-table').on('click', '.delete-btn', function () {
    var id = $(this).data('id');

    if (confirm('Are you sure you want to delete this Guest Faculty?')) {
        $.ajax({
            url: '{{ url("admin/guest/delete") }}/' + id,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                _method: 'post' // simulate DELETE method if your route uses Route::delete()
            },
            success: function (response) {
                alert('Deleted successfully!');
                $('#agency-group-table').DataTable().ajax.reload(null, false); // false = stay on same page
            },
            error: function (xhr, status, error) {
                alert('Error deleting guest faculty.');
                console.error('Error status: ' + status);
                console.error('Error response: ' + xhr.responseText);
            }
        });
    }
});





        // IFSC Auto-Fill
        document.getElementById('ifsc').addEventListener('blur', function () {
        
            const ifsc = this.value.trim();
            if (ifsc.length > 4) {
                fetch(`https://ifsc.razorpay.com/${ifsc}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.BANK && data.ADDRESS && data.BRANCH) {
                            document.getElementById('bank_address').value = data.ADDRESS;
                            document.getElementById('branch_name').value = data.BRANCH;
                        }
                    })
                    .catch(error => {
                        console.error('Invalid IFSC or API Error', error);
                    });
            }
        });
    });
</script>
 

<script>
$(document).ready(function () {
    // Load states on page load
    $.get('/states', function(states) {
        states.forEach(function(state) {
            $('#state').append(new Option(state.name, state.id));
        });

        // Pre-select old value if available
        const oldState = "{{ old('state') }}";
        if (oldState) {
            $('#state').val(oldState).trigger('change');
        }
    });   

    // On state change, load corresponding cities
    $('#state').on('change', function () {
        var stateId = $(this).val();
        $('#city').empty().append(new Option('Select City', ''));

        if (stateId) {
            $.get(`/states/${stateId}/districts`, function(cities) {
                cities.forEach(function(city) {
                    $('#city').append(new Option(city.name, city.name));
                });

                // Pre-select old value if available
                const oldCity = "{{ old('city') }}";
                if (oldCity) {
                    $('#city').val(oldCity);
                }
            });
        }
    });
});
</script>
@endsection
