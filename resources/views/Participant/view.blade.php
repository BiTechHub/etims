<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Participant Feedback</title>
  
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">

    <style>
        body {
            background-color: #eef2f5;
        }
        .profile-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 2px 8px rgba(0,0,0,0.1);
        }
        .profile-header {
            font-size: 1.2rem;
            font-weight: 600;
            color: #155724;
            border-bottom: 1px solid #ccc;
            margin-bottom: 1rem;
        }
        .profile-table td {
            padding: 5px 10px;
            font-size: 0.9rem;
        }
        .rating-header th {
            text-align: center;
            vertical-align: middle;
            font-size: 0.9rem;
        }
        .rating-header th:nth-child(2) { background-color: #28a745; color: white; }
        .rating-header th:nth-child(3) { background-color: #17a2b8; color: white; }
        .rating-header th:nth-child(4) { background-color: #ffc107; color: black; }
        .rating-header th:nth-child(5) { background-color: #fd7e14; color: white; }
        .rating-header th:nth-child(6) { background-color: #dc3545; color: white; }

        @media (max-width: 768px) {
            .profile-table td {
                display: block;
                width: 100%;
                padding: 5px;
            }
            .profile-table tr {
                margin-bottom: 10px;
                display: block;
            }
            .rating-header th {
                font-size: 0.8rem;
                padding: 5px 2px;
            }
            .rating-header th br {
                display: none;
            }
            .table-bordered td {
                padding: 5px 2px;
            }
        }
    </style>
</head>
<body>
<div class="container py-4">
    <div class="row g-4">
        <!-- Participant Profile -->
        <div class="col-md-4">
            <div class="profile-card">
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
                    </div>
                @endif

                <div class="profile-header">Participant Profile</div>
                <table class="table table-borderless profile-table">
                    <tbody>
                        <tr><td><strong>Programme</strong></td><td>{{ $participant->programme->title }}</td></tr>
                        <tr><td><strong>Nomination ID</strong></td><td>{{ $participant->nomination_id }}</td></tr>
                        <tr><td><strong>Title</strong></td><td>{{ $participant->title }}</td></tr>
                        <tr><td><strong>Name</strong></td><td>{{ $participant->name }}</td></tr>
                        <tr><td><strong>Email</strong></td><td>{{ $participant->email }}</td></tr>
                        <tr><td><strong>Phone</strong></td><td>{{ $participant->phone }}</td></tr>
                        <tr><td><strong>Designation</strong></td><td>{{ $participant->designation }}</td></tr>
                        <tr><td><strong>City</strong></td><td>{{ $participant->city }}</td></tr>
                        <tr><td><strong>State</strong></td><td>{{ $participant->state }}</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Feedback Form -->
        <div class="col-md-8">
            <form method="POST" action="{{ route('feedback.response.store') }}">
                @csrf
                <input type="hidden" name="participant_id" value="{{ $participant->id }}">
                <input type="hidden" name="programme_id" value="{{ $participant->programme->id }}">

                <div class="card">
                    <div class="card-header bg-primary text-white text-center">
                        <h5 class="mb-0">Feedback Form</h5>
                    </div>
                    <div class="card-body">

                        @foreach($subtopics as $menu)
                            {{-- <h6 class="mt-4 text-success">{{ $menu->title}}</h6> --}}
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="rating-header">
                                        <tr>
                                            <th style="width: 40%;">Topics</th>
                                            <th>Excellent<br><small>(5)</small></th>
                                            <th>Very Good<br><small>(4)</small></th>
                                            <th>Good<br><small>(3)</small></th>
                                            <th>Fair<br><small>(2)</small></th>
                                            <th>Poor<br><small>(1)</small></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($subtopics as $submenu)
                                            <tr>
                                                <td>{{ $submenu->title }}</td>
                                                @for($i = 5; $i >= 1; $i--)
                                                    <td class="text-center">
                                                        <input type="radio" 
                                                               id="feedback-{{ $submenu->id }}-{{ $i }}" 
                                                               name="feedback[{{ $submenu->id }}]" 
                                                               value="{{ $i }}" 
                                                               {{ $i == 5 ? 'required' : '' }}>
                                                        <label for="feedback-{{ $submenu->id }}" class="visually-hidden">{{ $submenu->id }}</label>
                                                    </td>
                                                @endfor
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endforeach

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-success px-5">Submit Feedback</button>
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>