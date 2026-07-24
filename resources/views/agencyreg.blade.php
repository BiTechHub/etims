<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Agencies</title>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fontawesome/css/all.min.css') }}">

    <style>
        body {

            background: url("{{ asset('logo2.jpg') }}") no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', sans-serif;
        }

        .container {
            margin-top: 30px;
        }

        .card {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        .section-title {
            font-weight: bold;
            color: #2c3e50;
        }
    </style>
</head>

<body>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
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



    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif


    <div class="container">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Add / Edit Agency</h5>
            </div>
            <div class="card-body">
                <form id="agency-group-form" action="{{ route('out.agency.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="agency_type" class="form-label">Agency Type <span
                                    class="text-danger">*</span></label>
                            <select class="form-control" id="agency_type" name="agency_type_id" required>
                                <option value="">Select Agency Type</option>
                                @foreach ($agencyTypes as $agencyType)
                                    <option value="{{ $agencyType->id }}"
                                        {{ old('agency_type_id') == $agencyType->id ? 'selected' : '' }}>
                                        {{ $agencyType->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="name" class="form-label">Agency Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name"
                                value="{{ old('name') }}" required>
                        </div>
                        <div class="col-md-3">
                            <label for="user_name" class="form-label">User Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="user_name" name="user_name"
                                value="{{ old('user_name') }}" required>
                        </div>
                        <div class="col-md-3">
                            <label for="sponsor_bank" class="form-label">Sponsor Bank</label>
                            <input type="text" class="form-control" id="sponsor_bank" name="sponsor_bank"
                                value="{{ old('sponsor_bank') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="chairman" class="form-label">Chairman</label>
                            <input type="text" class="form-control" id="chairman" name="chairman"
                                value="{{ old('chairman') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control" id="address" name="address"
                                value="{{ old('address') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="state" class="form-label">State <span class="text-danger">*</span></label>
                            <select class="form-control" id="state" name="state" required></select>
                        </div>
                        <div class="col-md-3">
                            <label for="city" class="form-label">City <span class="text-danger">*</span></label>
                            <select class="form-control" id="city" name="city" required></select>
                        </div>
                        <div class="col-md-3">
                            <label for="pincode" class="form-label">Pincode</label>
                            <input type="text" class="form-control" id="pincode" name="pincode"
                                value="{{ old('pincode') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="phone" class="form-label">Phone <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="phone" name="phone"
                                value="{{ old('phone') }}" required>
                        </div>
                        <div class="col-md-3">
                            <label for="emailid" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="emailid" name="emailid"
                                value="{{ old('emailid') }}" required>
                        </div>
                        <div class="col-md-3">
                            <label for="cc_email" class="form-label">CC Emails</label>
                            <input type="text" class="form-control" id="cc_email" name="cc_email"
                                value="{{ old('cc_email') }}">
                            <small class="text-muted">Separate multiple emails with commas</small>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Save Agency</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script>
        $(function() {
            $.get('/states', function(states) {
                states.forEach(function(state) {
                    $('#state').append(new Option(state.name, state.id));
                });
                const oldState = "{{ old('state') }}";
                if (oldState) $('#state').val(oldState).trigger('change');
            });

            $('#state').on('change', function() {
                var stateId = $(this).val();
                $('#city').empty().append(new Option('Select City', ''));
                if (stateId) {
                    $.get(`/states/${stateId}/districts`, function(cities) {
                        cities.forEach(function(city) {
                            $('#city').append(new Option(city.name, city.name));
                        });
                        const oldCity = "{{ old('city') }}";
                        if (oldCity) $('#city').val(oldCity);
                    });
                }
            });
        });
    </script>
</body>

</html>
