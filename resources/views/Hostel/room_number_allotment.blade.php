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
            @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <h1 id="form-title"></h1>
                 <form method="GET" action="" class="mb-4">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="block" class="form-label">Select Block</label>
                <select name="block_id" id="block" class="form-control" required>
                    <option value="">-- Select Block --</option>
                    @foreach($blocks as $block)
                        <option value="{{ $block->id }}" {{ request('block_id') == $block->id ? 'selected' : '' }}>
                            {{ $block->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label for="room_type" class="form-label">Select Room Type</label>
                <select name="room_type_id" id="room_type" class="form-control" required>
                    <option value="">-- Select Room Type --</option>
                    @foreach($types as $type)
                        <option value="{{ $type->id }}" {{ request('room_type_id') == $type->id ? 'selected' : '' }}>
                            {{ $type->types_of_rooms }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary w-100" style="margin:10px">Show Rooms</button>
    </form>
            </div>
          
        </div>
      </div>  
      <div class="card">
        
     <div class="card-body">
<div class="table-container table-responsive">
    @if(isset($allottedRooms) && count($allottedRooms))
        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th>Existing Room Number</th>
                    <th>New Room Number</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($allottedRooms as $room)
                    <tr data-room-id="{{ $room->id }}">
                        <td>{{ $room->room_number ?? 'Not Allotted' }}</td>
                        <td>
                            <input type="text" class="form-control room-number" value="{{ $room->room_number }}">
                        </td>
                        <td>
                            <button type="button" class="btn btn-success update-room-number">Update</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @elseif(request('block_id') && request('room_type_id'))
        <div class="alert alert-warning mt-4">No rooms found for the selected block and room type.</div>
    @endif
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
    $(document).on('click', '.update-room-number', function () {
        const row = $(this).closest('tr');
        const roomId = row.data('room-id');
        const roomNumber = row.find('.room-number').val();

        $.ajax({
            url: "{{ route('rooms.update_room_number_ajax') }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                room_id: roomId,
                room_number: roomNumber
            },
            success: function (response) {
                if (response.success) {
                    alert('Room number updated successfully!');
                    row.find('td:first').text(roomNumber);
                } else {
                    alert(response.message || 'Failed to update room number.');
                }
            },
            error: function (xhr) {
                if (xhr.status === 422 && xhr.responseJSON.errors) {
                    const messages = Object.values(xhr.responseJSON.errors).flat().join('\n');
                    alert(messages);
                } else {
                    alert('Something went wrong. Please try again.');
                }
            }
        });
    });
</script>

@endsection
