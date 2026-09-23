@extends('admin.layouts.master')

@section('main-section')

<div class="container">

  <div class="page-inner">

    <div class="page-header">

      <h3 class="fw-bold mb-3">Add SiteContent</h3>

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

          <a href="#">Manage SiteContent</a>

        </li>

        <li class="separator">

          <i class="icon-arrow-right"></i>

        </li>

        <li class="nav-item">

          <a href="#">Add SiteContent</a>

        </li>

      </ul>

    </div>

    <div class="row">

      <div class="col-md-12">

   <div class="card shadow mb-4">

  <div class="card-header bg-primary text-white">

    <h5 class="mb-0"><i class="fas fa-bed me-2"></i>Room Allotment</h5>

  </div>

  <div class="card-body">

    <form id="room-allotment-form">

      <div class="row g-3">

        <!-- Programme Code -->

        <div class="col-md-4">

          <div class="form-group">

            <label for="programme_code" class="form-label fw-semibold">Programme Code</label>

            <input type="text" id="programme_code" class="form-control" placeholder="Enter unique code">

          </div>

        </div>



        <!-- Programme Select -->

        <div class="col-md-8">

          <div class="form-group">

            <label for="programme_id" class="form-label fw-semibold">Select Programme</label>

            <select id="programme_id" class="form-select">

              <option value="">-- Select Programme --</option>

              @foreach($programmes as $programme)

                <option value="{{ $programme->id }}">{{ $programme->title }}</option>

              @endforeach

            </select>

          </div>

        </div>



        <!-- Block Select -->

        <div class="col-md-6">

          <div class="form-group">

            <label for="block_id" class="form-label fw-semibold">Select Block</label>

            <select id="block_id" class="form-select" disabled>

              <option value="">-- Select Block --</option>

            </select>

          </div>

        </div>



        <!-- Room Type -->

        <div class="col-md-6">

          <div class="form-group">

            <label for="room_type" class="form-label fw-semibold">Select Room Type</label>

            <select id="room_type" class="form-select" disabled>

              <option value="">-- Select Room Type --</option>

            </select>

          </div>

        </div>



        <!-- Room Selection -->

       



        <!-- Bed Selection -->

        <div class="col-md-6" id="bed-container" style="display: none;">

          <div class="form-group">

            <label for="bed_id" class="form-label fw-semibold">Select Bed</label>

            <select id="bed_id" class="form-select"></select>

          </div>

        </div>

      </div>

    </form>

  </div>

</div>



      </div>  

      <div class="card">

     <div class="card-body">

<div class="table-container table-responsive">

    <table class="table table-bordered" id="participants_table">

            <thead>

                <tr>

                      <th>Select</th>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Gender</th>
            <th>City</th>
            <th>State</th>
            <th>Room No</th>
            <th>Bed No</th>
            {{-- <th>Action</th> --}}

                </tr>

            </thead>

            <tbody></tbody>

        </table>

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




<script>
$(document).ready(function () {

    const allotUrl = "{{ route('admin.get-allot-rooms') }}";

    /*
    |--------------------------------------------------------------------------
    | Load Blocks
    |--------------------------------------------------------------------------
    */
    loadBlocks();

    function loadBlocks() {

        $.get('/admin/hostel/getBlocks', function (blocks) {

            $('#block_id').html(`<option value="">-- Select Block --</option>`);

            blocks.forEach(block => {

                $('#block_id').append(`
                    <option value="${block.id}">
                        ${block.name}
                    </option>
                `);
            });

        }).fail(function () {

            alert('Failed to load blocks.');

        });
    }

    /*
    |--------------------------------------------------------------------------
    | Load Participants
    |--------------------------------------------------------------------------
    */
    function loadParticipants() {

        let programmeId = $('#programme_id').val();

        if (!programmeId) {

            $('#participants_table tbody').html('');
            return;
        }

        $.get(`/admin/hostel/getParticipants/${programmeId}`, function (data) {

            let rows = '';

            data.forEach((p, index) => {

                let roomColumn = '--';
                let bedColumn = '--';

                /*
                |--------------------------------------------------------------------------
                | If Already Allotted
                |--------------------------------------------------------------------------
                */
                if (p.bed) {

                    roomColumn = p.bed.room.room_number || '--';
                    bedColumn = p.bed.bed_number || '--';

                } else {

                    /*
                    |--------------------------------------------------------------------------
                    | Dynamic Selected Values
                    |--------------------------------------------------------------------------
                    */
                    let blockId = $('#block_id').val() || 0;
                    let roomType = $('#room_type').val() || 0;

                    let url =
                        `${allotUrl}?programme=${btoa(String(programmeId))}` +
                        `&participant=${btoa(String(p.id))}` +
                        `&block_id=${btoa(String(blockId))}` +
                        `&room_type=${btoa(String(roomType))}`;

                    roomColumn = `
                        <a href="${url}"
                           class="btn btn-sm btn-primary allot-btn">
                           Allot
                        </a>
                    `;
                }

                rows += `
                    <tr>

                        <td>
                            <input type="checkbox"
                                   class="participant"
                                   value="${p.id}">
                        </td>

                        <td>${index + 1}</td>

                        <td>${p.name || '--'}</td>

                        <td>${p.email || '--'}</td>

                        <td>${p.phone || '--'}</td>

                        <td>${p.gender || '--'}</td>

                        <td>${p.city || '--'}</td>

                        <td>${p.state || '--'}</td>

                        <td>${roomColumn}</td>

                        <td>${bedColumn}</td>

                        

                    </tr>
                `;
            });

            $('#participants_table tbody').html(rows);

        }).fail(function () {

            alert('Failed to load participants.');

        });
    }

    /*
    |--------------------------------------------------------------------------
    | Programme Change
    |--------------------------------------------------------------------------
    */
    $('#programme_id').change(function () {

        let programmeId = $(this).val();

        $('#block_id').prop('disabled', !programmeId);

        loadParticipants();
    });

    /*
    |--------------------------------------------------------------------------
    | Block Change
    |--------------------------------------------------------------------------
    */
    $('#block_id').change(function () {

        let blockId = $(this).val();

        $('#room_type')
            .html(`<option value="">-- Select Room Type --</option>`)
            .prop('disabled', true);

        if (!blockId) {

            loadParticipants();
            return;
        }

        $.get('/admin/hostel/getRoomTypesByBlock',
            {
                block_id: blockId
            },
            function (roomTypes) {

                if (Object.keys(roomTypes).length > 0) {

                    $.each(roomTypes, function (key, value) {

                        $('#room_type').append(`
                            <option value="${key}">
                                ${value}
                            </option>
                        `);
                    });

                    $('#room_type').prop('disabled', false);

                } else {

                    alert('No room types available.');

                }

                loadParticipants();
            }
        );

    });

    /*
    |--------------------------------------------------------------------------
    | Room Type Change
    |--------------------------------------------------------------------------
    */
    $('#room_type').change(function () {

        loadParticipants();

    });

    /*
    |--------------------------------------------------------------------------
    | Programme Code Search
    |--------------------------------------------------------------------------
    */
    $('#programme_code').keyup(function () {

        let code = $(this).val().trim();

        if (!code) return;

        fetch(`/get-programme-by-code/${code}`)
            .then(response => response.json())
            .then(data => {

                if (data && data.id) {

                    $('#programme_id')
                        .val(data.id)
                        .trigger('change');

                }

            })
            .catch(error => {

                console.error(error);

            });
    });

    /*
    |--------------------------------------------------------------------------
    | View Details
    |--------------------------------------------------------------------------
    */
    $(document).on('click', '.view-details', function () {

        let html = `
            <table class="table table-bordered">

                <tr>
                    <th>Name</th>
                    <td>${$(this).data('name')}</td>
                </tr>

                <tr>
                    <th>Email</th>
                    <td>${$(this).data('email')}</td>
                </tr>

                <tr>
                    <th>Phone</th>
                    <td>${$(this).data('phone')}</td>
                </tr>

                <tr>
                    <th>Designation</th>
                    <td>${$(this).data('designation')}</td>
                </tr>

                <tr>
                    <th>Gender</th>
                    <td>${$(this).data('gender')}</td>
                </tr>

                <tr>
                    <th>City</th>
                    <td>${$(this).data('city')}</td>
                </tr>

                <tr>
                    <th>State</th>
                    <td>${$(this).data('state')}</td>
                </tr>

                <tr>
                    <th>Status</th>
                    <td>${$(this).data('status')}</td>
                </tr>

            </table>
        `;

        $('#detailsBody').html(html);

        $('#detailsModal').modal('show');

    });

});
</script>



@endsection

