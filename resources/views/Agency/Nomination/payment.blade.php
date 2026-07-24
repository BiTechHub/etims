<!DOCTYPE html>

<html>

<head>

    <title>Payment</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>

        body { font-family: Arial, sans-serif; padding: 30px; }

        .container { max-width: 400px; margin: auto; }

        input, button { width: 100%; padding: 10px; margin-top: 10px; font-size: 16px; }

        .btn-primary { background-color: #007bff; color: white; border: none; cursor: pointer; border-radius: 4px; }

        .btn-primary:hover { background-color: #0056b3; }

    </style>

</head>

<body>

    <div class="container">

        <h2>Make Payment</h2>



        <label>Total Payment (₹):</label>

        <input type="text" id="totalAmount" value="{{ $nomination->total*$participantCount }}" readonly>



        <button onclick="makePayment({{ $nomination->id }})" class="btn-primary">Pay</button>

    </div>



    <script>

        function makePayment(nominationId) {

            fetch(`/agency/payment/${nominationId}`, {

                method: 'POST',

                headers: {

                    'Content-Type': 'application/json',

                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content

                },

            })

            .then(response => response.json())

            .then(data => {

                if (data.success) {

                    alert(data.message);

                    window.location.href = data.redirect_url;

                } else {

                    alert('Payment failed.');

                }

            })

            .catch(error => {

                console.error('Error:', error);

                alert('Something went wrong.');

            });

        }

    </script>

</body>

</html>

