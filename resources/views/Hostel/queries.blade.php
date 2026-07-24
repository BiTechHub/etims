<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Guest Support | Your Hotel</title>
  <style>
    body {
      font-family: "Segoe UI", sans-serif;
      background: linear-gradient(135deg, #dff6ff, #e0eafc);
      margin: 0;
      padding: 40px 20px;
    }

    .contact-card {
      background: #ffffff;
      padding: 35px 30px;
      border-radius: 16px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
      max-width: 540px;
      margin: 0 auto;
      animation: fadeIn 0.5s ease-in-out;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .contact-card h2 {
      margin-bottom: 25px;
      text-align: center;
      color: #2c3e50;
      font-size: 24px;
    }

    label {
      display: block;
      margin-bottom: 6px;
      color: #2c3e50;
      font-weight: 600;
      font-size: 15px;
    }

    input, textarea {
      width: 100%;
      padding: 12px;
      margin-bottom: 20px;
      border-radius: 10px;
      border: 1px solid #ccc;
      font-size: 15px;
      transition: border-color 0.3s;
    }

    input:focus, textarea:focus {
      border-color: #3498db;
      outline: none;
    }

    textarea {
      resize: vertical;
      min-height: 120px;
    }

    button {
      background-color: #3498db;
      color: white;
      border: none;
      padding: 14px;
      font-size: 16px;
      border-radius: 10px;
      cursor: pointer;
      width: 100%;
      transition: background-color 0.3s ease;
    }

    button:hover {
      background-color: #2980b9;
    }

    .note {
      font-size: 12px;
      color: #888;
      margin-top: -15px;
      margin-bottom: 20px;
    }

    .help-desk {
      margin-top: 30px;
      font-size: 14px;
      color: #2c3e50;
      border-top: 1px solid #eee;
      padding-top: 20px;
      text-align: center;
    }

    .help-desk strong {
      display: block;
      margin-bottom: 10px;
      font-size: 16px;
    }

    .help-desk a {
      color: #3498db;
      text-decoration: none;
    }

    .help-desk a:hover {
      text-decoration: underline;
    }

    /* Flash message styling */
    .alert {
      padding: 15px;
      margin-bottom: 20px;
      border-radius: 8px;
      color: white;
      font-weight: bold;
    }

    .alert-success {
      background-color: #28a745;
    }

    .alert-danger {
      background-color: #dc3545;
    }
  </style>
  
  <!-- Include jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>
<body>

  <!-- Flash Messages -->
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

  <!-- Contact Form -->
  <div class="contact-card">
    <h2>Need Help? We're Here for You</h2>
    <form action="{{route('participant.hostel.queries')}}" method="POST">

      @csrf
      <label for="name">Your Name</label>
      <input type="text" id="name" name="name" required />

      <label for="phone">Phone Number</label>
      <input type="tel" id="phone" name="phone" required />

      <label for="email">Email ID</label>
      <input type="email" id="email" name="email" required />

      <label for="room">Room Number <span class="note">(Optional)</span></label>
      <input type="text" id="room" name="room" />

      <label for="message">Your Message</label>
      <textarea id="message" name="message" required></textarea>

      <button type="submit">Submit Query</button>
    </form>

    <!-- Help Desk Info -->
    <div class="help-desk">
      <strong>Help Desk Contact</strong>
      📞 <a href="tel:+911234567890">+91-123-456-7890</a><br>
      📧 <a href="mailto:helpdesk@yourhotel.com">helpdesk@yourhotel.com</a>
    </div>
  </div>

  <script>
    $(document).ready(function() {
        setTimeout(function() {
            $('.flash-message').fadeOut('slow');
        }, 3000); // Hide flash messages after 3 seconds
    });
  </script>
  
</body>
</html>
