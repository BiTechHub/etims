<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting to CCAvenue...</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 100px;
        }
    </style>
</head>
<body>

    <h3>Please wait while we redirect you to the secure payment page...</h3>

    <form method="post" name="redirectForm" id="redirectForm" autocomplete="off"
          action="https://secure.ccavenue.com/transaction/transaction.do?command=initiateTransaction">
        <input type="hidden" name="encRequest" value="{{ $encrypted_data }}">
        <input type="hidden" name="access_code" value="{{ $access_code }}">
        <noscript>
            <p>JavaScript is required. Please click the button below to proceed.</p>
            <button type="submit">Continue</button>
        </noscript>
    </form>

    <script defer>
        document.addEventListener("DOMContentLoaded", function () {
            document.getElementById('redirectForm').submit();
        });
    </script>

</body>
</html>
