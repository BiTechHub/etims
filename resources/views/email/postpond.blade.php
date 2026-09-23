<!DOCTYPE html>
<html>
<head>
    <title>Programme Update</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
        }
        p {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <p>Dear Sir,</p>

    <p>
        Please refer to our communication on this subject. In this connection, we advise that the 
        <strong>{{ $programme->title }}</strong>
        scheduled at <strong>{{ $programme->venue }}</strong>

        @if($status === 'Postpond')
            from <strong>{{ \Carbon\Carbon::parse($programme->from_date)->format('jS F Y') }}</strong>
            to <strong>{{ \Carbon\Carbon::parse($programme->to_date)->format('jS F Y') }}</strong>
            has been <strong>postponed</strong> due to low number of nominations.

        @elseif($status === 'Canceled')
            from <strong>{{ \Carbon\Carbon::parse($programme->from_date)->format('jS F Y') }}</strong>
            to <strong>{{ \Carbon\Carbon::parse($programme->to_date)->format('jS F Y') }}</strong>
            has been <strong>cancelled</strong>.

        @elseif($status === 'Reschedule')
            has been <strong>rescheduled</strong>. The new dates are as follows:
            from <strong>{{ \Carbon\Carbon::parse($programme->from_date)->format('jS F Y') }}</strong>
            to <strong>{{ \Carbon\Carbon::parse($programme->to_date)->format('jS F Y') }}</strong>.
        @endif
    </p>

    <p>
        We request you to kindly inform the participants nominated for the programme accordingly.
    </p>

    <p>
        The inconvenience caused is deeply regretted.
    </p>

    <p>With Regards,</p>
    <p>
        Academic Section<br>
        BIRD, Lucknow<br>
        0522-2421097
    </p>
</body>
</html>