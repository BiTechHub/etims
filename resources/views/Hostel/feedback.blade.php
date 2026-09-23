<!DOCTYPE html>
<html>
<head>
    <title>Guest Checkout Feedback Form</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #333;
            text-align: center;
            padding: 8px;
        }
        .form-section {
            margin-bottom: 20px;
        }
        .comments {
            width: 100%;
            height: 80px;
        }
    </style>
</head>
<body>
      <!-- Flash Messages -->
  @if (session('success'))
  <div class="alert alert-success flash-message" style="background-color:green">
    {{ session('success') }}
  </div>
@endif

@if (session('error'))
  <div class="alert alert-danger flash-message">
    {{ session('error') }}
  </div>
@endif
    <h2>Guest Checkout Feedback Form</h2>
    <p><em>...Because your expectations matter!</em></p>

    <form action="{{ route('hostel.feedback.store') }}" method="POST">

        @csrf

        <div class="form-section">
            <label>
                Name:
                <input type="text" name="name" value="{{ $participant->name }}" required>
            </label>
            &nbsp;&nbsp;&nbsp;
            <label>
                Email:
                <input type="email" name="email" value="{{ $participant->email }}" required>
            </label>
            &nbsp;&nbsp;&nbsp;
            <label>
                Phone:
                <input type="text" name="phone" value="{{ $participant->phone }}" required>
            </label>
            &nbsp;&nbsp;&nbsp;
            <label>
                Programme:
                <input type="text" name="programme" value="{{ $programme->title }}" readonly>
            </label>
            <!-- Hidden field to store programme_id -->
            <input type="hidden" name="programme_id" value="{{ $programme->id }}">
        </div>
        

        <table>
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Excellent</th>
                    <th>Good</th>
                    <th>Fair</th>
                    <th>Poor</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $categories = [
                        'check_out_experience' => 'Check-out Experience',
                        'cleanliness' => 'Cleanliness',
                        'housekeeping' => 'Housekeeping',
                        'staff_service' => 'Staff Service',
                        'restaurant_food' => 'Restaurant Food',
                        'amenities' => 'Amenities',
                        'overall_rating' => 'Overall Hotel Rating',
                    ];
                    $ratings = ['Excellent', 'Good', 'Fair', 'Poor'];
                @endphp

                @foreach($categories as $key => $label)
                    <tr>
                        <td>{{ $label }}</td>
                        @foreach($ratings as $rating)
                            <td>
                                <input type="radio" name="{{ $key }}" value="{{ $rating }}" required>
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="form-section">
            <label for="comments"><strong>Other Comments:</strong></label><br>
            <textarea name="comments" class="comments"></textarea>
        </div>

        <button type="submit">Submit Feedback</button>
    </form>
</body>
<script>
    $(document).ready(function() {
        setTimeout(function() {
            $('.flash-message').fadeOut('slow');
        }, 3000); // Hide flash messages after 3 seconds
    });
  </script>
</html>
