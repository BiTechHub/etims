<!-- resources/views/admin/programme/announcement.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Training Programme Invitation</title>
    <style>
        /* Your entire CSS here (copy the CSS from your provided HTML) */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Georgia', 'Times New Roman', serif;
            background: #f5f7fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .container {
            background: #fff;
            padding: 40px 30px;
            max-width: 800px;
            width: 90%;
            margin: 40px auto;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            border: 2px solid #ccc;
            flex-grow: 1;
            overflow: hidden;
        }

        .logo {
            text-align: center;
            margin-bottom: 15px;
        }

        .logo img {
            width: 120px;
            height: auto;
        }

        .header {
            text-align: center;
            font-weight: bold;
            font-size: 24px;
            margin-bottom: 5px;
            color: #003366;
        }

        .sub-header {
            text-align: center;
            font-size: 16px;
            color: #555;
            margin-bottom: 30px;
        }

        .details {
            font-size: 15px;
            margin-bottom: 20px;
        }

        .details span {
            font-weight: bold;
        }

        .subject {
            font-weight: bold;
            /* text-decoration: underline; */
            margin: 40px 0 20px;
            font-size: 20px;
            text-align: center;
            color: #000;
        }

        .content {
            font-size: 15px;
            text-align: justify;
        }

        .signature {
            margin-top: 10px;
            font-size: 15px;
        }

        .signature p {
            margin: 5px 0;
        }

        .footer {
            margin-top: 5px;
            border-top: 20px solid #000;
            font-size: 14px;
            padding-top: 10px;
            /* text-align: center; */
        }

        a {
            color: #003366;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Edit Form Styling */
        .edit-button {
            background-color: #003366;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-bottom: 20px;
        }

        .edit-form {
            display: none;
            margin-top: 20px;
        }

        .edit-form .container {
            border: none;
            padding: 0;
        }

        .edit-form textarea {
            width: 100%;
            height: 600px;
            padding: 10px;
            font-size: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .save-button {
            background-color: #4CAF50;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .cancel-button {
            background-color: #f44336;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .letterhead {
            border-bottom: 20px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .letterhead h2,
        .letterhead h3 {
            margin: 0;
            font-size: 16px;
            /* text-align: center; */
        }

        @media print {

            /* Ensure body and container styles are correct */
            body {
                font-family: 'Georgia', 'Times New Roman', serif;
                font-size: 14px;
                margin: 0;
                /* Remove margins from body */
                padding: 0;
                /* Remove padding from body */
            }

            /* Ensure the header and footer are displayed with the border */
            .letterhead {
                border-bottom: 20px solid #000;
                /* Make sure the border is applied */
                padding-bottom: 10px;
                margin-bottom: 20px;
            }

            .footer {
                border-top: 20px solid #000;
                /* Ensure the footer has a border */
                padding-top: 10px;
            }

            /* Ensure the container takes up the full printable area */
            .container {
                padding: 40px 30px;
                max-width: 800px;
                margin: 0 auto;
                box-shadow: none;
                /* Remove box shadow for print */
                border: none;
                /* Remove container border if any */
                background: none;
                /* Remove background for print */
            }

            /* Optional: Hide print button during print */
            button {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="container" id="contentContainer">

        <!-- Default content template -->
        {{-- <div class="logo">
            <img src="{{asset('assets/images/logo.png')}}" alt="BIRD Logo">
          </div> --}}

        <div style="display: flex; align-items: center; justify-content: space-between;" class="letterhead">
            <div>
                <h2>बैंकर्स ग्रामिण विकास संस्थान<small style="
                font-weight: normal;
            ">
                        नाबार्ड द्वारा प्रवर्तित आईएसओ 9001:2015 प्रमाणित स्वायत्त संस्था</small></h2>
                <span>
                    <h3>Bankers Institute of Rural Development <small
                            style="
              font-weight: normal;
          ">An ISO 9001:2015 certified autonomous
                            institute promoted by NABARD</small></h3>
                </span>

            </div>
            <div>
                <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" style="height: 80px; margin-left:0px">
                <!-- Adjust height as needed -->
            </div>
        </div>








        <div class="content" id="contentEditable">

            <h2>Welcome, {{ $name }}</h2>
            <p>Your agency account has been successfully created.</p>
            <p><strong>Username:</strong> {{ $username }}</p>
            <p><strong>Password:</strong> {{ $password }}</p>
            <p>You can now log in to the system using these credentials.<a href="http://127.0.0.1:8000/agency-panel">
                    Click here for login</a></p>
        </div>



        <div class="footer">
            सेटर-एच, एलडीए कॉलोनी, कानपुर रोड, लखनऊ – 226012<br>
            Sector-H, LDA Colony, Kanpur Road, Lucknow – 226012<br>
            Phone: +91-522-2425917 / 2421097 | Email: bird@nabard.org | Website: <a
                href="https://birdlucknow.nabard.org" target="_blank">birdlucknow.nabard.org</a>
        </div>


        <!-- Edit Button -->




    </div>

    <!-- Edit Form -->
