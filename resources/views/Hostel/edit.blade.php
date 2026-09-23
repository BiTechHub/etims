@extends('admin.layouts.master')
@section('title', 'Edit Room')

@push('styles')
<style>
    .container-wrapper {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        width: 100%;
        box-sizing: border-box;
        font-size: 18px !important;
    }

    .sidebar {
        flex: 0 2 250px;
        background: #349e4c;
        padding: 20px;
        border-radius: 16px;
        color: white;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        box-sizing: border-box;
    }

    .sidebar-title {
        font-size: 22px;
        font-weight: bold;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .form-container {
        margin: 20px !important;
        flex: 1 1 auto;
        background: #fff;
        padding: 20px;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        box-sizing: border-box;
        margin-left: 20px;
    }

    .form-container:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
    }

    .form-title {
        font-size: 19px;
        font-weight: bold;
        margin: 5px 0;
        padding: 3px 5px;
        margin-bottom: 25px;
        text-transform: capitalize;
    }

    .alert {
        position: relative;
        padding: 0.75rem 1.25rem;
        margin-bottom: 1rem;
        border: 1px solid transparent;
        border-radius: 0.25rem;
        transition: opacity 0.15s linear;
    }

    .alert-success {
        color: #155724;
        background-color: #d4edda;
        border-color: #c3e6cb;
    }

    .alert-danger {
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
    }

    .alert-dismissible {
        padding-right: 4rem;
    }

    .alert-dismissible .close {
        position: absolute;
        top: 0;
        right: 0;
        padding: 0.75rem 1.25rem;
        color: inherit;
    }

    @media (max-width: 768px) {
        .btn-action {
            padding: 4px 8px;
            font-size: 12px;
        }

        .form-container {
            margin-left: 0;
        }

        .sidebar {
            width: 100%;
            text-align: center;
        }
    }
</style>
@endpush

@section('content')

@if (session('success'))
    <div class="alert alert-success flash-message">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger flash-message">
        {{ session('error') }}
    </div>
@endif

<div class="container-wrapper">
    <div class="form-container">
        <h4 class="form-title">Edit Room</h4>
        <form action="{{ route('room.update') }}" method="POST">
            @csrf
            @method('post')
            <input type="hidden" name="id" value="{{ $Room->id }}">

            <div class="row">
                <div class="col-md-4 form-group">
                    <label for="number">Room Number</label>
                    <input type="text" class="form-control" name="number" value="{{ $Room->number }}" required>
                </div>

                <div class="col-md-4 form-group">
                    <label for="beds">Number of room</label>
                    <input type="text" class="form-control" name="beds" value="{{ $Room->beds }}">
                </div>

                  <div class="col-md-4 form-group">
                    <label for="beds">Number of room</label>
                    <input type="text" class="form-control" name="beds" value="{{ $Room->beds }}">
                </div>
            </div>

            <button type="submit" class="btn btn-success mt-3">Update Room</button>
            <a href="{{ route('room.list') }}" class="btn btn-secondary mt-3">Back</a>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        setTimeout(function() {
            $('.flash-message').fadeOut('slow');
        }, 3000); // Hide flash messages after 3 seconds
    });
</script>
@endpush
