@extends('admin.layouts.master')

@section('content')
<div class="custom-container">
    @if (session('success'))
        <div class="alert success flash-message">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert error flash-message">{{ session('error') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <h4 class="page-title">Room Master Setup</h4>

    <form action="{{route('rooms.store')}}" method="POST" class="room-form">
        @csrf

        <div class="form-group">
            <label for="block">Select Block</label>
            <select name="block_id" id="block" required>
                <option value="">Select Block</option>
                @foreach($blocks as $block)
                    <option value="{{ $block->id }}">{{ $block->name }}</option>
                @endforeach
            </select>
        </div>

        <div id="room-master-container">
            <div class="room-master-row" id="room-master-row-1">
                <div class="form-row">
                    <div class="form-group">
                        <label>Room Type</label>
                        <select name="room_type[]" required>
                            <option value="">Select Type</option>
                            @foreach($types as $type)
                                <option value="{{ $type->id }}">{{ $type->types_of_rooms }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Number of Rooms</label>
                        <input type="number" name="room_number[]" placeholder="Enter number of rooms" min="1" required>
                    </div>
                    <div class="form-group remove-btn">
                        <button type="button" class="remove-room">Remove</button>
                    </div>
                </div>
            </div>
        </div>

        <button type="button" id="add-room-master" class="btn add-btn">+ Add Room</button>
        <button type="submit" class="btn submit-btn">Submit</button>
    </form>
</div>
@endsection

@push('scripts')
<script>
    let roomMasterCount = 1;
    const types = @json($types);

    document.getElementById('add-room-master').addEventListener('click', function () {
        roomMasterCount++;

        let typeOptions = '<option value="">Select Type</option>';
        types.forEach(type => {
            typeOptions += `<option value="${type.id}">${type.types_of_rooms}</option>`;
        });

        const container = document.createElement('div');
        container.className = 'room-master-row';
        container.id = `room-master-row-${roomMasterCount}`;

        container.innerHTML = `
            <div class="form-row">
                <div class="form-group">
                    <label>Room Type</label>
                    <select name="room_type[]" required>
                        ${typeOptions}
                    </select>
                </div>
                <div class="form-group">
                    <label>Number of Rooms</label>
                    <input type="number" name="room_number[]" placeholder="Enter number of rooms" min="1" required>
                </div>
                <div class="form-group remove-btn">
                    <button type="button" class="remove-room">Remove</button>
                </div>
            </div>
        `;

        document.getElementById('room-master-container').appendChild(container);
    });

    document.getElementById('room-master-container').addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-room')) {
            e.target.closest('.room-master-row').remove();
        }
    });

    setTimeout(() => {
        document.querySelectorAll('.flash-message').forEach(el => el.style.display = 'none');
    }, 3000);
</script>
@endpush

@push('styles')
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        overflow-x: hidden;
    }

    .custom-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 2rem;
    }

    .page-title {
        font-size: 26px;
        text-align: center;
        color: #007bff;
        margin-bottom: 30px;
    }

    .alert {
        padding: 12px 20px;
        margin-bottom: 15px;
        border-radius: 5px;
        font-size: 14px;
    }

    .alert.success {
        background-color: #d4edda;
        color: #155724;
    }

    .alert.error {
        background-color: #f8d7da;
        color: #721c24;
    }

    .room-form {
        background: #fff;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .form-group {
        margin-bottom: 15px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    label {
        font-weight: 600;
        margin-bottom: 5px;
        font-size: 14px;
    }

    select, input {
        padding: 10px;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 6px;
    }

    .room-master-row {
        background: #f9f9f9;
        padding: 20px;
        margin-bottom: 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
    }

    .form-row {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }

    .remove-btn {
        align-self: flex-end;
    }

    .remove-room {
        background: #dc3545;
        color: white;
        padding: 10px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        width: 100%;
    }

    .btn {
        display: block;
        width: 100%;
        padding: 12px;
        border: none;
        font-size: 16px;
        margin-top: 10px;
        border-radius: 6px;
        cursor: pointer;
    }

    .add-btn {
        background-color: #007bff;
        color: white;
    }

    .submit-btn {
        background-color: #28a745;
        color: white;
    }

    .btn:hover, .remove-room:hover {
        opacity: 0.9;
    }

    @media (max-width: 600px) {
        .form-row {
            flex-direction: column;
        }
    }
</style>
@endpush
