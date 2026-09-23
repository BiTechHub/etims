<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Agency Approval</title>
</head>
<body>

    <p>Dear {{ $agencyName }},</p>

    <p>
        Your agency has been successfully approved.
    </p>

    <p>
        Your login details are given below:
    </p>

    <p>
        <strong>Username:</strong> {{ $userName }}
    </p>

    <p>
        <strong>Password:</strong> {{ $password }}
    </p>

    <p>
        You can now login to the portal.
    </p>

    <p>
        Regards,<br>
        Admin Team
    </p>

</body>
</html>