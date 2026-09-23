<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Agency Login</title>

<style>

/* RESET */
*{margin:0;padding:0;box-sizing:border-box;font-family:Segoe UI, sans-serif}

/* BODY */
body{
background:url("{{asset('logo2.jpg')}}") no-repeat center center fixed;
background-size:cover;
min-height:100vh;
display:flex;
align-items:center;
justify-content:center;
}

/* CARD */
.card{
background:white;
padding: 15px 30px;
width:95%;
max-width:500px;
border-radius:18px;
box-shadow:0 15px 40px rgba(0,0,0,.25);
}

/* LOGO */
.logo{
text-align:center;
margin-bottom:0px;
}
.logo img{height:70px}

/* TITLE */
h4{
text-align:center;
margin-bottom:10px;
color:#222;
}

/* INPUT GROUP */
.group{margin-bottom:18px}

.group label{
display:block;
font-weight:600;
margin-bottom:6px;
}

.group input{
width:100%;
padding:12px;
border-radius:8px;
border:1px solid #ccc;
font-size:15px;
transition:.3s;
}

.group input:focus{
border-color:#1A237E;
outline:none;
box-shadow:0 0 0 2px rgba(26,35,126,.15);
}

/* CAPTCHA BOX */
.captcha-wrap{
display:flex;
gap:8px;
margin-bottom:10px;
}

.captcha-box{
flex:1;
background:#eef1ff;
padding:12px;
border-radius:8px;
font-size:20px;
font-weight:bold;
text-align:center;
letter-spacing:3px;
user-select:none;
}

.refresh{
border:none;
background:#888;
color:white;
padding:0 14px;
border-radius:8px;
cursor:pointer;
}

.refresh:hover{background:#333}

/* BUTTON */
button.login{
width:100%;
padding:13px;
background:#1A237E;
border:none;
color:white;
font-weight:bold;
border-radius:10px;
font-size:16px;
cursor:pointer;
transition:.3s;
}

button.login:hover{background:black}

/* ERROR */
.error{
color:red;
font-size:13px;
margin-top:4px;
}

/* RESPONSIVE */
@media(max-width:480px){
.card{padding:25px}
}


.footer{
background:#1A237E;
color:white;
text-align:center;
padding:14px;
margin-top:20px;
border-radius:10px;
}

.footer a{color:#ffd54f;text-decoration:none;}
</style>
</head>

<body>

<div class="card">
    

<div class="logo">
<img src="{{ asset('assets/images/logo.png') }}">
</div>

<h4>Agency Login</h4>

@if ($errors->any())
<div style="background:#ffe5e5;padding:15px;border-radius:10px;margin-bottom:20px;color:#b30000;">
<ul style="margin:8px 0 0 18px;">
@foreach ($errors->all() as $error)
<li>{{ $error }}</li>
@endforeach
</ul>
</div>
@endif

<form action="{{route('agency.check')}}" method="POST" onsubmit="return validateForm()" id="loginForm">
@csrf

<div class="group">
<label>Username</label>
<input type="text" id="user" name="user_name">
<div id="userError" class="error"></div>
</div>

<div class="group">
<label>Password</label>
<input type="password" name="password" id="new_password" autocomplete="off">


<div id="passError" class="error"></div>
</div>

<div class="group">

<label>Captcha</label>

<div class="captcha-wrap">
<div class="captcha-box" id="captchaQuestion"></div>
<button type="button" class="refresh" onclick="generateCaptcha()">↻</button>
</div>

<input type="text" id="captchaInput" placeholder="Enter captcha">
<div id="captchaError" class="error"></div>

</div>

<button class="login">Login</button>

<div class="footer">
© 2026 <b>Bird</b> |
Developed By
<a href="https://www.businessinnovations.in" target="_blank">
<b>Business Innovations</b>
</a>
</div>

</form>

</div>


<script>

let answer="";

function generateCaptcha(){
let chars="ABCDEFGHJKLMNPQRSTUVWXYZ23456789";
answer="";
for(let i=0;i<5;i++){
answer+=chars[Math.floor(Math.random()*chars.length)];
}
document.getElementById("captchaQuestion").innerHTML=answer;
document.getElementById("captchaInput").value="";
document.getElementById("captchaError").innerHTML="";
}
generateCaptcha();


function validateForm(){

let ok=true;

if(user.value.trim()==""){
userError.innerHTML="Enter username";
ok=false;
}else userError.innerHTML="";

if(pass.value.trim()==""){
passError.innerHTML="Enter password";
ok=false;
}else passError.innerHTML="";

if(captchaInput.value.trim().toUpperCase()!=answer){
captchaError.innerHTML="Captcha incorrect";
generateCaptcha();
ok=false;
}else captchaError.innerHTML="";

return ok;

}

</script>

<script src="{{url('/admin')}}/assets/js/core/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function () {

    

    // ✅ PASSWORD CHANGE PAR ENCRYPT
    $('#pass').on('change', function () {

        let password = $(this).val();

        if(password.trim() === ''){
            return;
        }

        $.ajax({
            url: "{{ route('encrypt_token') }}",
            type: "GET",
            data: { token_id: password },

            success: function (response) {

                // encrypted password set
                $('#new_password').val(response.token);
				//$('#pass').val(response.token);
                

            },

            error: function () {
               // alert("Encryption failed");
            }
        });

    });


    // ✅ FINAL LOGIN SUBMIT
   

});
</script>


</body>
</html>