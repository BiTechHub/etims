@extends('admin.layouts.master')

@section('main-section')

<div class="container py-4">

    <div class="page-inner">

        <div class="page-header mb-4">

            <h3 class="fw-bold mb-3 text-primary">
                Room Allocation
            </h3>

            <ul class="breadcrumb bg-light p-3 rounded">

                <li class="breadcrumb-item">
                    <a href="#">
                        <i class="fas fa-home"></i>
                    </a>
                </li>

                <li class="breadcrumb-item">
                    Manage Hostel
                </li>

                <li class="breadcrumb-item active">
                    Add Rooms
                </li>

            </ul>

        </div>

        <div class="row">

            <div class="col-md-12">

                {{-- Success --}}
                @if(session('success'))

                    <div class="alert alert-success alert-dismissible fade show">

                        {{ session('success') }}

                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="alert">
                        </button>

                    </div>

                @endif


                {{-- Error --}}
                @if(session('error'))

                    <div class="alert alert-danger alert-dismissible fade show">

                        {{ session('error') }}

                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="alert">
                        </button>

                    </div>

                @endif


                {{-- Validation Errors --}}
                @if ($errors->any())

                    <div class="alert alert-warning">

                        <ul class="mb-0">

                            @foreach ($errors->all() as $error)

                                <li>{{ $error }}</li>

                            @endforeach

                        </ul>

                    </div>

                @endif


                <div class="card shadow">

                    <div class="card-header bg-primary text-white">

                        <h5 class="mb-0">
                            Create Hostel Rooms
                        </h5>

                    </div>

                    <div class="card-body">

                        <form id="form_hostel"
                              action="{{ route('hostel.room.store') }}"
                              method="POST">

                            @csrf

                            {{-- Block --}}
                            <div class="mb-4">

                                <label class="form-label fw-bold">
                                    Select Block
                                </label>

                                <select name="block_id"
                                        id="block"
                                        class="form-select"
                                        required>

                                    <option value="">
                                        -- Select Block --
                                    </option>

                                    @foreach($blocks as $block)

                                        <option value="{{ $block->id }}">
                                            {{ $block->name }}
                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- Room Container --}}
                            <div id="room-container">

                                <div class="room-row border rounded p-3 mb-4">

                                    <div class="row g-3 align-items-end">

                                        {{-- Room Type --}}
                                        <div class="col-md-4">

                                            <label class="form-label fw-semibold">
                                                Room Type
                                            </label>

                                            <select name="room_type"
                                                    class="form-select room-type"
                                                    required>

                                                <option value="">
                                                    -- Select Room Type --
                                                </option>

                                                @foreach($roomTypes as $type)

                                                    <option
                                                        value="{{ $type->id }}"
                                                        data-available_beds="{{ $type->avaible_beds }}"
                                                    >
                                                        {{ $type->types_of_rooms }}
                                                    </option>

                                                @endforeach

                                            </select>

                                        </div>


                                        {{-- Start Room Number --}}
                                        <div class="col-md-3">

                                            <label class="form-label fw-semibold">
                                                Start Room Number
                                            </label>

                                            <input type="number"
                                                   name="start_room_no"
                                                   class="form-control start-room-no"
                                                   placeholder="Ex: 5"
                                                   min="1"
                                                   required>

                                        </div>


                                        {{-- Total Rooms --}}
                                        <div class="col-md-3">

                                            <label class="form-label fw-semibold">
                                                Create Number of Rooms
                                            </label>

                                            <input type="number"
                                                   name="room_count"
                                                   class="form-control room-count"
                                                   placeholder="Ex: 10"
                                                   min="1"
                                                   required>

                                        </div>


                                        {{-- Remove --}}
                                        <div class="col-md-2">

                                            <button type="button"
                                                    class="btn btn-outline-danger remove-room w-100">

                                                <i class="fas fa-trash"></i>
                                                Remove

                                            </button>

                                        </div>

                                    </div>


                                    {{-- Generated Rooms --}}
                                    <div class="generated-preview mt-4"></div>

                                </div>

                            </div>


                            {{-- Buttons --}}
                            <div class="d-flex justify-content-between mt-4">

                                

                                <button type="submit"
                                        class="btn btn-success">

                                    <i class="fas fa-save me-1"></i>
                                    Submit

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

<script>
  
// Ajax Submit
$(document).ready(function(){

    $('#form_hostel').submit(function(e){

        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({

            url: '{{ route('hostel.room.store') }}',

            type: 'POST',

            data: formData,

            contentType: false,

            processData: false,

            success: function(data){

                alert('Rooms Created Successfully');

               window.location.reload();

            },

            error: function(xhr){

                 if(xhr.status == 422){

        alert(xhr.responseJSON.message);

    }else{

        alert('Something went wrong');

    }
            }

        });

    });

});


// Generate Rooms
function generateRooms(row) {

    const blockSelect =
        document.getElementById('block');

    const blockName =
        blockSelect.options[blockSelect.selectedIndex]?.text || '';

    const roomType =
        row.querySelector('.room-type');

    const selectedOption =
        roomType.options[roomType.selectedIndex];

    const availableBeds =
        parseInt(selectedOption.dataset.available_beds || 0);

    const roomCount =
        parseInt(
            row.querySelector('.room-count').value || 0
        );

    const startRoomNo =
        parseInt(
            row.querySelector('.start-room-no').value || 1
        );

    const preview =
        row.querySelector('.generated-preview');

    preview.innerHTML = '';

    if (
        !blockName ||
        roomCount < 1
    ) {
        return;
    }

    let html = `
        <h5 class="text-primary mb-3">
            Generated Rooms
        </h5>
    `;


    // Create Rooms
    for (let i = 0; i < roomCount; i++) {

        const roomNumber =
            startRoomNo + i;

        const defaultRoomName =
            `${blockName}${roomNumber}`;

        html += `
            <div class="border rounded p-3 mb-3 bg-light room-box">

                <div class="mb-3">

                    <label class="fw-bold mb-1">
                        Room Name
                    </label>

                    <input type="text"
                           name="generated_room_name[]"
                           value="${defaultRoomName}"
                           class="form-control generated-room-name">

                </div>

                <div class="bed-preview"></div>

            </div>
        `;
    }

    preview.innerHTML = html;

    updateBeds(preview, availableBeds);
}



// Generate Beds
function updateBeds(preview, availableBeds) {

    preview.querySelectorAll('.room-box').forEach(roomBox => {

        const roomInput =
            roomBox.querySelector('.generated-room-name');

        const bedPreview =
            roomBox.querySelector('.bed-preview');

        const roomName =
            roomInput.value;

        let bedsHtml = '';

        if (availableBeds > 1) {

            bedsHtml += `
                <label class="fw-bold mb-2">
                    Beds
                </label>
                <br>
            `;

            for (let b = 0; b < availableBeds; b++) {

                const letter =
                    String.fromCharCode(65 + b);

                const bedName =
                    `Bed${roomName}${letter}`;

                bedsHtml += `
                    <div class="mb-2">

                        <input type="text"
                               name="generated_bed_name[]"
                               value="${bedName}"
                               class="form-control">

                    </div>
                `;
            }
        }

        bedPreview.innerHTML = bedsHtml;


        // Auto update beds when room changes
        roomInput.addEventListener('input', function () {

            updateBeds(preview, availableBeds);

        });

    });

}



// Input Change
document.addEventListener('input', function(e) {

    if (
        e.target.classList.contains('room-count') ||
        e.target.classList.contains('start-room-no') ||
        e.target.classList.contains('room-type')
    ) {

        const row =
            e.target.closest('.room-row');

        generateRooms(row);

    }

});



// Block Change
document.getElementById('block')
.addEventListener('change', function() {

    document.querySelectorAll('.room-row')
    .forEach(row => {

        generateRooms(row);

    });

});




// Add More
document.getElementById('add-room')
.addEventListener('click', function () {

    const firstRow =
        document.querySelector('.room-row');

    const clone =
        firstRow.cloneNode(true);

    clone.querySelector('.room-count').value = '';

    clone.querySelector('.start-room-no').value = '';

    clone.querySelector('.room-type').selectedIndex = 0;

    clone.querySelector('.generated-preview').innerHTML = '';

    document.getElementById('room-container')
        .appendChild(clone);

});




// Remove Row
document.getElementById('room-container')
.addEventListener('click', function (e) {

    if (e.target.closest('.remove-room')) {

        const rows =
            document.querySelectorAll('.room-row');

        if (rows.length > 1) {

            e.target.closest('.room-row').remove();

        }

    }

});





</script>

@endsection