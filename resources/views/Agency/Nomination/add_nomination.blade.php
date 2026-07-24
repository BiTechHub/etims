@extends('Agency.layouts.master')
@section('main-section')
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote-lite.css"> --}}
<link rel="stylesheet" href="{{ asset('assets/summernote/summernote-lite.css') }}">
<div class="container">
  <div class="page-inner">
    <div class="page-header">
      <h3 class="fw-bold mb-3">Add Nomination</h3>
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
          <a href="#">{{$programmes->title}}</a>
        </li>
        <li class="separator">
          <i class="icon-arrow-right"></i>
        </li>
        <li class="nav-item">
          <a href="#">Add Nomination</a>
        </li>
      </ul>
    </div>
    <div class="row">
          @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

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
            <div class="card-header">
                   <h3>Nomination for {{$programmes->title}}</h3>
                   <br>
                   <span class="text text-danger">last nomination date : {{$programmes->last_nomination_date}}</span> 

            </div>
         
          <div class="card-body">
               <form id="nomination-form" action="{{url('/agency-panel/nomination-store')}}" method="POST">
                @csrf
                <input type="hidden" name="agency_type_id" value="{{$userInfo->agency_type_id}}">
                <input type="hidden" name="agency_id" value="{{$userInfo->id}}">
                <input type="hidden" name="rate_per_person" value="{{$programmes->fee_structure}}">
                <input type="hidden" name="programme_id" value="{{$programmes->id}}">
                <input type="hidden" name="nomination_date" value="{{ date('Y-m-d') }}">
            
               
            <h5 class="mb-3">Participants</h5>
            <div id="participants-wrapper">
                <div class="participant-section" id="participant-0">
                    <div class="participant-header">
                        <h6 class="participant-title">Participant #1</h6>
                        <button type="button" class="remove-participant" onclick="removeParticipant(0)" title="Remove participant">
                            ×
                        </button>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Title</label>
                            <select name="participants[0][title]" class="form-control">
                                <option value="">-- Select --</option>
                                <option value="Mr.">Mr.</option>
                                <option value="Mrs.">Mrs.</option>
                                <option value="Ms.">Ms.</option>
                                <option value="Dr.">Dr.</option>
                                <option value="Prof.">Prof.</option>
                            </select>
                        </div>
                
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="participants[0][name]" class="form-control" placeholder="Enter name" required>
                        </div>
                
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Designation</label>
                            <input type="text" name="participants[0][designation]" class="form-control" placeholder="Enter designation">
                        </div>
                
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Participant Phone <span class="text-danger">*</span></label>
                            <input type="text" name="participants[0][phone]" class="form-control" placeholder="Enter phone" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Gender <span class="text-danger">*</span></label>
                            <select name="participants[0][gender]" class="form-control" required>
                                <option value="">Select gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Participant Email <span class="text-danger">*</span></label>
                            <input type="email" name="participants[0][email]" class="form-control" placeholder="Enter email" required>
                        </div>
                
                                    <div class="col-md-4 mb-3">
    <label class="form-label">State</label>

    <select name="participants[0][state]"
            class="form-control state-dropdown select2-state"
            data-index="0">

        <option value="">Select State</option>
    </select>
</div>

<div class="col-md-4 mb-3">
    <label class="form-label">City</label>

    <select name="participants[0][city]"
            class="form-control city-dropdown select2-city"
            data-index="0">

        <option value="">Select City</option>
    </select>
</div>
                    </div>
                </div>
            </div>   
            
            <button type="button" class="btn btn-primary add-participant-btn" onclick="addParticipant()">
                <i class="fas fa-plus"></i> Add Another Participant
            </button>
            
                <button type="submit" class="btn btn-primary">Save Nomination</button>
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
    $(document).ready(function() {
        const $programmeYear = $('#programme_year');
        const $programmeSelect = $('#programme_id');
        const $agencyTypeSelect = $('#agency_type_id');
        const $agencySelect = $('#agency_id');

        // Programme year change
        $programmeYear.on('change', function() {
            const selectedYear = $(this).val();
            $programmeSelect.html('<option value="">Loading programmes...</option>').prop('disabled', true);

            if (selectedYear) {
                $.ajax({
                    url: '{{ route("admin.getProgrammesByYear") }}',
                    type: 'GET',
                    data: { year: selectedYear },
                    success: function(response) {
                        let options = '<option value="">Select Programme...</option>';

                        if (response.success && response.data.length > 0) {
                            response.data.forEach(function(programme) {
                                const year = new Date(programme.created_at).getFullYear();
                                options += `<option value="${programme.id}">${programme.title} (${year})</option>`;
                            });
                        } else {
                            options = '<option value="">No programmes available for selected year</option>';
                        }

                        $programmeSelect.html(options);
                    },
                    error: function(xhr) {
                        const message = xhr.responseJSON?.message || 'Error loading programmes';
                        $programmeSelect.html(`<option value="">${message}</option>`);
                    },
                    complete: function() {
                        $programmeSelect.prop('disabled', false);
                    }
                });
            } else {
                $programmeSelect.html('<option value="">Select Academic Year First</option>').prop('disabled', true);
            }
        });

        // Agency type change
        $agencyTypeSelect.on('change', function() {
            const selectedTypeId = $(this).val();
            $agencySelect.html('<option value="">Loading agencies...</option>').prop('disabled', true);

            if (selectedTypeId) {
                $.ajax({
                    url: '{{ route("admin.getAgenciesByType") }}',
                    type: 'GET',
                    data: { agency_type_id: selectedTypeId },
                    success: function(response) {
                        let options = '<option value="">Select Agency...</option>';

                        if (response.success && response.data.length > 0) {
                            response.data.forEach(function(agency) {
                                options += `<option value="${agency.id}">${agency.name}</option>`;
                            });
                        } else {
                            options = '<option value="">No agencies available for this type</option>';
                        }

                        $agencySelect.html(options);
                    },
                    error: function(xhr) {
                        const message = xhr.responseJSON?.message || 'Error loading agencies';
                        $agencySelect.html(`<option value="">${message}</option>`);
                    },
                    complete: function() {
                        $agencySelect.prop('disabled', false);
                    }
                });
            } else {
                $agencySelect.html('<option value="">Select Agency Type First</option>').prop('disabled', true);
            }
        });

        // Preload on page load (in case old data exists)
        if ($programmeYear.val()) $programmeYear.trigger('change');
        if ($agencyTypeSelect.val()) $agencyTypeSelect.trigger('change');

        // Form submit state
        $('#nomination-form').on('submit', function() {
            $(this).find('button[type=submit]').prop('disabled', true)
                   .html('<i class="fas fa-spinner fa-spin me-2"></i> Processing...');
        });
    });
</script>
<script>
   let participantIndex = 1;

function addParticipant() {
    const participantRow = `
        <div class="participant-section" id="participant-${participantIndex}">
            <div class="participant-header">
                <h6 class="participant-title">Participant #${participantIndex + 1}</h6>
                <button type="button" class="remove-participant" onclick="removeParticipant(${participantIndex})" title="Remove participant">
                    ×
                </button>
            </div>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">Title</label>
                    <select name="participants[${participantIndex}][title]" class="form-control">
                        <option value="">-- Select --</option>
                        <option value="Mr.">Mr.</option>
                        <option value="Mrs.">Mrs.</option>
                        <option value="Ms.">Ms.</option>
                        <option value="Dr.">Dr.</option>
                        <option value="Prof.">Prof.</option>
                    </select>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="participants[${participantIndex}][name]" class="form-control" placeholder="Enter name" required>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Designation</label>
                    <input type="text" name="participants[${participantIndex}][designation]" class="form-control" placeholder="Enter designation">
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Participant Phone <span class="text-danger">*</span></label>
                    <input type="text" name="participants[${participantIndex}][phone]" class="form-control" placeholder="Enter phone" required>
                </div>
<div class="col-md-3 mb-3">
    <label class="form-label">Gender <span class="text-danger">*</span></label>
    <select name="participants[${participantIndex}][gender]" class="form-control" required>
        <option value="">Select gender</option>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
        <option value="Other">Other</option>
    </select>
</div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Participant Email <span class="text-danger">*</span></label>
                    <input type="email" name="participants[${participantIndex}][email]" class="form-control" placeholder="Enter email" required>
                </div>

              <div class="col-md-4 mb-3">
                <label class="form-label">State</label>

                <select name="participants[${participantIndex}][state]"
                        class="form-control state-dropdown select2-state"
                        data-index="${participantIndex}">

                    <option value="">Select State</option>

                </select>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">City</label>

                <select name="participants[${participantIndex}][city]"
                        class="form-control city-dropdown select2-city"
                        data-index="${participantIndex}">

                    <option value="">Select City</option>

                </select>
            </div>
            </div>
        </div>
    `;

    $('#participants-wrapper').append(participantRow);

    // Select2 init
    $('.select2-state').select2();
    $('.select2-city').select2();

    loadStates();
    // Smooth scroll to the newly added participant
    $(`#participant-${participantIndex}`).get(0).scrollIntoView({
        behavior: 'smooth',
        block: 'nearest'
    });
    participantIndex++;
}

function removeParticipant(index) {
    $(`#participant-${index}`).fadeOut(300, function() {
        $(this).remove();
        // Renumber remaining participants
        $('.participant-section').each(function(i) {
            $(this).find('.participant-title').text(`Participant #${i + 1}`);
            $(this).attr('id', `participant-${i}`);
            $(this).find('button').attr('onclick', `removeParticipant(${i})`);
            // Update all input names to maintain sequence
            $(this).find('[name^="participants["]').each(function() {
                const name = $(this).attr('name').replace(/participants\[\d+\]/g, `participants[${i}]`);
                $(this).attr('name', name);
            });
        });
        participantIndex = $('.participant-section').length;
    });
}
</script>
<script>
  
        
	$(document).ready(function () {
  $(".select2").select2();
});
</script>

<script>

$(document).ready(function () {

    $('.select2').select2();
    $('.select2-state').select2();
    $('.select2-city').select2();

    loadStates();

});


// LOAD STATES
function loadStates() {

    $.get('/states', function(states) {

        $('.state-dropdown').each(function() {

            let stateDropdown = $(this);

            // Prevent duplicate loading
            if(stateDropdown.attr('data-loaded') == 'yes') {
                return;
            }

            states.forEach(function(state) {

                stateDropdown.append(
                    new Option(state.name, state.id)
                );

            });

            stateDropdown.attr('data-loaded', 'yes');

        });

    });

}


// STATE CHANGE
$(document).on('change', '.state-dropdown', function () {

    let stateId = $(this).val();

    let index = $(this).data('index');

    let cityDropdown = $(`.city-dropdown[data-index="${index}"]`);

    cityDropdown.empty();

    cityDropdown.append(
        new Option('Select City', '')
    );

    if(stateId) {

        $.get(`/states/${stateId}/districts`, function(cities) {

            cities.forEach(function(city) {

                cityDropdown.append(
                    new Option(city.name, city.name)
                );

            });

            cityDropdown.trigger('change');

        });

    }

});

</script>
@endsection
