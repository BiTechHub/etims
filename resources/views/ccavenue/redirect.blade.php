
@php
    $actionUrl = env('CCAVENUE_ENV') === 'sandbox'
        ? 'https://test.ccavenue.com/transaction/transaction.do?command=initiateTransaction'
        : 'https://secure.ccavenue.com/transaction/transaction.do?command=initiateTransaction';
@endphp


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Redirecting to CC Avenue</title>
</head>
<body>

<form id="ccForm"
      method="post"
      action="{{ $actionUrl }}">

    <input type="hidden" name="encRequest" value="{{ $encrypted_data }}">
    <input type="hidden" name="access_code" value="{{ $access_code }}">
</form>

<script>
    document.getElementById('ccForm').submit();
</script>

</body>
</html>
