@extends('Agency.layouts.master')
@section('main-section')
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote-lite.css"> --}}
<link rel="stylesheet" href="{{ asset('assets/summernote/summernote-lite.css') }}">

<div class="container">
  <div class="page-inner">
    <div class="page-header">
      <h3 class="fw-bold mb-3">Edit Nomination</h3>
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
          <a href="#">Edit Nomination</a>
        </li>
      </ul>
    </div>
    <div class="row">
         @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
             <form action="{{ route('agency.nominations.update', $nomination->id) }}" method="POST">

            @csrf
         
    
            <div class="card mb-4">
                <div class="card-header">Nomination Details</div>
                <div class="row ">
                    <!-- Agency Type Field -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="agency_type_id" class="form-label">
                                Agency Type <span class="text-danger">*</span>
                            </label>
                            <select name="agency_type_id" id="agency_type_id" class="form-control" required>
                                <option value="">Select Agency Type</option>
                                @foreach($agencyTypes as $type)
                                    <option value="{{ $type->id }}" 
                                        {{ old('agency_type_id', $nomination->agency_type_id) == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('agency_type_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                
                    <!-- Agency Field -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="agency_id" class="form-label">
                                Agency <span class="text-danger">*</span>
                            </label>
                            <select name="agency_id" id="agency_id" class="form-control" required>
                                <option value="">Select Agency</option>
                                @foreach($agencies as $agency)
                                    <option value="{{ $agency->id }}" 
                                        {{ old('agency_id', $nomination->agency_id) == $agency->id ? 'selected' : '' }}>
                                        {{ $agency->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('agency_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                
                    <!-- Nomination Date Field -->
                    <div class="col-md-4">
                        <label for="nomination_date" class="form-label">Nomination Date <span class="text-danger">*</span></label>
                        <input type="date" name="nomination_date" id="nomination_date" class="form-control"
                               value="{{ old('nomination_date', $nomination->nomination_date) }}" required>
                    </div>
                
                    <!-- Rate Per Person Field -->
                  
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="rate_per_person" class="form-label">
                                Rate Per Person (₹) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"></span>
                                <input type="number" name="rate_per_person" id="rate_per_person" 
                                       class="form-control @error('rate_per_person') is-invalid @enderror"
                                       value="{{ old('rate_per_person', $nomination->rate_per_person) }}" 
                                       step="0.01" min="0" required>
                                @error('rate_per_person')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            
            </div>
    
            <!-- Participants -->
            <div class="card mb-4">
                <div class="card-header">Participants</div>
                <div class="card-body">
                    <div id="participants-wrapper">
                        @foreach($nomination->participants as $index => $participant)
                            <div class="border rounded p-3 mb-4 participant-row">
                                <input type="hidden" name="participants[{{ $index }}][id]" value="{{ $participant->id }}">
            
                                <div class="row g-3 mb-2">
                                    <!-- Title -->
                                    <div class="col-md-3">
                                        <label class="form-label">Title</label>
                                        <select name="participants[{{ $index }}][title]" class="form-control">
                                            <option value="">-- Select --</option>
                                            <option value="Mr." {{ $participant->title == 'Mr.' ? 'selected' : '' }}>Mr.</option>
                                            <option value="Mrs." {{ $participant->title == 'Mrs.' ? 'selected' : '' }}>Mrs.</option>
                                            <option value="Ms." {{ $participant->title == 'Ms.' ? 'selected' : '' }}>Ms.</option>
                                            <option value="Dr." {{ $participant->title == 'Dr.' ? 'selected' : '' }}>Dr.</option>
                                            <option value="Prof." {{ $participant->title == 'Prof.' ? 'selected' : '' }}>Prof.</option>
                                        </select>
                                    </div>
            
                                    <!-- Name -->
                                    <div class="col-md-3">
                                        <label class="form-label">Name <span class="text-danger">*</span></label>
                                        <input type="text" name="participants[{{ $index }}][name]" class="form-control"
                                               placeholder="Full Name" value="{{ old("participants.$index.name", $participant->name) }}" required>
                                    </div>

                                    <!--Gender-->
                                    <div class="col-md-3">
                                        <label class="form-label">Gender</label>
                                        @php
                                            $genderOld = old("participants.$index.gender", $participant->gender);
                                        @endphp
                                        <select name="participants[{{ $index }}][gender]" class="form-control">
                                            <option value="">-- Select --</option>
                                            <option value="Male" {{ $genderOld == 'Male' ? 'selected' : '' }}>Male</option>
                                            <option value="Female" {{ $genderOld == 'Female' ? 'selected' : '' }}>Female</option>
                                            <option value="Other" {{ $genderOld == 'Other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                    </div>
            
                                    <!-- Mobile -->
                                    <div class="col-md-3">
                                        <label class="form-label">Mobile</label>
                                        <input type="text" name="participants[{{ $index }}][phone]" class="form-control"
                                               placeholder="Mobile" value="{{ old("participants.$index.phone", $participant->phone) }}">
                                    </div>
            
                                    <!-- Email -->
                                    <div class="col-md-3">
                                        <label class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" name="participants[{{ $index }}][email]" class="form-control"
                                               placeholder="Email" value="{{ old("participants.$index.email", $participant->email) }}" required>
                                    </div>
                                      <div class="col-md-3">
                                        <label class="form-label">Designation</label>
                                        <input type="text" name="participants[{{ $index }}][designation]" class="form-control"
                                               value="{{ old("participants.$index.designation", $participant->designation) }}">
                                    </div>
  <div class="col-md-3">
                                        <label class="form-label">State</label>
                                        <input type="text" name="participants[{{ $index }}][state]" class="form-control"
                                               value="{{ old("participants.$index.state", $participant->state) }}">
                                    </div>
                                      <div class="col-md-3">
                                        <label class="form-label">City</label>
                                        <input type="text" name="participants[{{ $index }}][city]" class="form-control"
                                               value="{{ old("participants.$index.city", $participant->city) }}">
                                    </div>
                                </div>
            
                              
                                    <!-- Designation -->
                                  
            
                                    <!-- State -->
                                  
            
                                    <!-- City -->
                                  
            
                                    <!-- Remove Button -->
                                    <div class="col-md-3 d-flex align-items-end">
                                        {{-- <button type="button" class="btn btn-danger remove-participant w-100">
                                            <i class="fas fa-trash"></i> Remove
                                        </button> --}}
                                    </div>
                                
                            </div>
                        @endforeach
                    </div>
            
                    {{-- <button type="button" class="btn btn-secondary mt-3" id="add-participant">
                        <i class="fas fa-plus"></i> Add Participant
                    </button> --}}
                </div>
            </div>
            
            
    
            <!-- Submit and Cancel Buttons -->
            <div class="d-flex justify-content-between">
                <a href="" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Nomination
                </button>
            </div>
        </form>
            </div>
          
        </div>
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
    $(document).ready(function () {
        let participantIndex = {{ count($nomination->participants) }};
    
        // Add new participant
        $('#add-participant').click(function () {
            const template = $('#participant-template').html();
            const newRow = template.replace(/__INDEX__/g, participantIndex);
            $('#participants-wrapper').append(newRow);
            participantIndex++;
        });
    
        // Remove participant
        $(document).on('click', '.remove-participant', function () {
            const row = $(this).closest('.participant-row');
            if (confirm('Are you sure you want to remove this participant?')) {
                row.remove();
            }
        });
    
        // Load Agencies dynamically when agency type changes
        $('#agency_type_id').change(function () {
            const typeId = $(this).val();
            $('#agency_id').html('<option value="">Loading...</option>');
    
            if (typeId) {
                $.ajax({
                    url: '{{ route("admin.getAgenciesByType") }}',
                    type: 'GET',
                    data: { agency_type_id: typeId },
                    success: function (data) {
                        $('#agency_id').html('<option value="">Select Agency</option>');
                        if(data.success && data.data.length > 0) {
                            data.data.forEach(function (agency) {
                                $('#agency_id').append(`<option value="${agency.id}">${agency.name}</option>`);
                            });
                            // Preselect the agency if it matches the current nomination's agency type
                            $('#agency_id').val('{{ $nomination->agency_id }}');
                        }
                    },
                    error: function() {
                        $('#agency_id').html('<option value="">Error loading agencies</option>');
                    }
                });
            } else {
                $('#agency_id').html('<option value="">Select Agency Type First</option>');
            }
        });
    
        // Initialize form validation
        $('#editNominationForm').validate({
            rules: {
                'agency_type_id': { required: true },
                'agency_id': { required: true },
                'programme_id': { required: true },
                'nomination_date': { required: true, date: true },
                'rate_per_person': { required: true, number: true, min: 0 }
            },
            messages: {
                'agency_type_id': { required: "Please select an agency type" },
                'agency_id': { required: "Please select an agency" },
                'programme_id': { required: "Please select a programme" },
                'nomination_date': { 
                    required: "Please select a nomination date",
                    date: "Please enter a valid date"
                },
                'rate_per_person': { 
                    required: "Please enter the rate per person",
                    number: "Please enter a valid number",
                    min: "Rate cannot be negative"
                }
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
    </script>
    
@endsection
