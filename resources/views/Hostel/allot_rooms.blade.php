@extends('admin.layouts.master')

@section('main-section')
    <div class="container">

        <div class="page-inner">

            <div class="page-header">

                <h3 class="fw-bold mb-3">Room Allocation</h3>

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

                        <a href="#">Block : {{ $block->name }}</a>

                    </li>

                    <li class="separator">

                        <i class="icon-arrow-right"></i>

                    </li>

                    <li class="nav-item">

                        <a href="#">Program :{{ $programme->title }}</a>

                    </li>
                    <li class="separator">

                        <i class="icon-arrow-right"></i>

                    </li>
                    <li class="nav-item">
                        <a href="#">Participant : {{ $participant->name }}</a>
                    </li>

                </ul>

            </div>

            <div class="row">


                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-bed me-2"></i>Room Allotment for {{ $programme->title }}</h5>
                    </div>

                    <style>
                        .room-card {
                            border-radius: 15px;
                            overflow: hidden;
                            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
                            transition: 0.3s ease;
                            background: #fff;
                        }

                        .room-card:hover {
                            transform: translateY(-5px);
                        }

                        .room-header {
                            background: #234996;
                            color: #fff;
                            text-align: center;
                            padding: 10px;
                            font-size: 20px;
                            font-weight: 600;
                        }

                        .room-image-wrapper {
                            position: relative;
                            width: 100%;
                            height: 150px;
                            overflow: hidden;
                            background: #f8f9fa;
                        }

                        .room-bg {
                            width: 100%;
                            height: 100%;
                            object-fit: cover;
                        }

                        .bed {
                            position: absolute;
                            bottom: 20px;
                            width: 70px;
                            text-align: center;
                        }

                        .bed img {
                            width: 100%;
                            height: auto;
                        }

                        .bed-label {
                            background: rgba(0, 0, 0, 0.7);
                            color: #fff;
                            padding: 3px 8px;
                            border-radius: 5px;
                            margin-top: 5px;
                            font-size: 13px;
                            cursor: pointer;
                            display: inline-block;
                        }

                        .single-bed {
                            left: 50%;
                            transform: translateX(-50%);
                        }

                        .double-bed-1 {
                            left: 10%;
                        }

                        .double-bed-2 {
                            right: 10%;
                        }

                        .triple-bed-1 {
                            left: 2%;
                        }

                        .triple-bed-2 {
                            left: 50%;
                            transform: translateX(-50%);
                        }

                        .triple-bed-3 {
                            right: 2%;
                        }
                    </style>
                    <br>


                    <div class="row">

@foreach ($rooms as $room)

    @php

        /*
        |--------------------------------------------------------------------------
        | Allocated Beds Array
        |--------------------------------------------------------------------------
        */
        $allocatedBeds = $room->bed_allocation
            ->pluck('bed_number')
            ->toArray();

        $totalBeds = $room->type->avaible_beds;

    @endphp

    <div class="col-md-3 mb-4">

        <div class="room-card">

            {{-- Room Header --}}
            <div class="room-header">
                Room No: {{ $room->room_number }}
            </div>

            {{-- Room Background --}}
            <div class="room-image-wrapper">

                {{-- Room BG --}}
                <img
                    src="{{ asset('frontend/img/realistic-room.jpg') }}"
                    class="room-bg"
                    alt="Room"
                >

                {{-- ========================================================= --}}
                {{-- SINGLE BED --}}
                {{-- ========================================================= --}}
                @if ($totalBeds == 1)

                    @php
                        $bedNo = "Bed{$room->room_number}A";
                        $isAllocated = in_array($bedNo, $allocatedBeds);
                    @endphp

                    <div class="bed single-bed">

                        <img
                            src="{{ asset('frontend/img/bed.png') }}"
                            alt="Bed"
                        >

                        <div class="bed-label {{ $isAllocated ? 'bg-success text-white' : '' }}"
                             data-bed_no="{{ $bedNo }}"
                             data-room_no="{{ $room->room_number }}"
                             data-room_id="{{ $room->id }}">

                            {{ $bedNo }}

                        </div>

                    </div>

                @endif


                {{-- ========================================================= --}}
                {{-- DOUBLE BED --}}
                {{-- ========================================================= --}}
                @if ($totalBeds == 2)

                    @foreach (['A', 'B'] as $index => $letter)

                        @php
                            $bedNo = "Bed{$room->room_number}{$letter}";
                            $isAllocated = in_array($bedNo, $allocatedBeds);
                        @endphp

                        <div class="bed {{ $index == 0 ? 'double-bed-1' : 'double-bed-2' }}">

                            <img
                                src="{{ asset('frontend/img/bed.png') }}"
                                alt="Bed"
                            >

                            <div class="bed-label {{ $isAllocated ? 'bg-success text-white' : '' }}"
                                 data-bed_no="{{ $bedNo }}"
                                 data-room_no="{{ $room->room_number }}"
                                 data-room_id="{{ $room->id }}">

                                {{ $bedNo }}

                            </div>

                        </div>

                    @endforeach

                @endif


                {{-- ========================================================= --}}
                {{-- TRIPLE BED --}}
                {{-- ========================================================= --}}
                @if ($totalBeds == 3)

                    @php
                        $positions = [
                            'A' => 'triple-bed-1',
                            'B' => 'triple-bed-2',
                            'C' => 'triple-bed-3'
                        ];
                    @endphp

                    @foreach ($positions as $letter => $position)

                        @php
                            $bedNo = "Bed{$room->room_number}{$letter}";
                            $isAllocated = in_array($bedNo, $allocatedBeds);
                        @endphp

                        <div class="bed {{ $position }}">

                            <img
                                src="{{ asset('frontend/img/bed.png') }}"
                                alt="Bed"
                            >

                            <div class="bed-label {{ $isAllocated ? 'bg-success text-white' : '' }}"
                                 data-bed_no="{{ $bedNo }}"
                                 data-room_no="{{ $room->room_number }}"
                                 data-room_id="{{ $room->id }}">

                                {{ $bedNo }}

                            </div>

                        </div>

                    @endforeach

                @endif

            </div>

        </div>

    </div>

@endforeach

                    </div>



                </div>

            </div>

        </div>

    </div>




    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Are you sure? Allot this room</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="allot_room_form" action="#" method="post">
                    <div class="modal-body">


                        <div class="row">

                            <input type="hidden" name="room_id" id="allot_room_id">
                            <input type="hidden" name="participant_id" id="allot_participant_id"
                                value="{{ $participant->id }}">
                            <input type="hidden" name="programme_id" id="allot_programme_id"
                                value="{{ $programme->id }}">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name">For Program</label>
                                    <input type="text" class="form-control" id="name"
                                        placeholder="Enter participant name" value="{{ $programme->title }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Participant Name</label>
                                    <input type="text" class="form-control" id="name"
                                        placeholder="Enter participant name" value="{{ $participant->name }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone_no">Participant phone no</label>
                                    <input type="text" class="form-control" id="phone_no"
                                        placeholder="Enter participant Phone" value="{{ $participant->phone }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="allot_room_no">Allot Room No</label>
                                    <input type="text" class="form-control" id="allot_room_no" name="room_no">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="allot_bed_no">Allot bed no</label>
                                    <input type="text" class="form-control" id="allot_bed_no" name="bed_no">
                                </div>
                            </div>










                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ url('/admin') }}/assets/js/plugin/datatables/datatables.min.js"></script>

    <script src="{{ url('/admin') }}/assets/js/kaiadmin.min.js"></script>

    <script src="{{ url('/admin') }}/assets/js/setting-demo2.js"></script>
    <script>
        $(document).ready(function() {
            $('.bed-label').click(function() {
                var bed_no = $(this).data('bed_no');
                var room_no = $(this).data('room_no');
                var room_id = $(this).data('room_id');
                $('#allot_room_no').val(room_no);
                $('#allot_bed_no').val(bed_no);
                $('#allot_room_id').val(room_id);
                $('#exampleModal').modal('show');
            });

        });
    </script>

    <script>
        $(document).ready(function() {

            $('#allot_room_form').submit(function(e) {
                e.preventDefault();


                $.ajax({
                    url: "{{ route('admin.save-allot-rooms') }}",
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    data: $(this).serialize(),
                    success: function(data) {
                        alert("Allot Successfully");
                        location.reload();
                    },
                    error: function(error) {
                        alert(error);
                    }
                });
            });


        });
    </script>
@endsection
