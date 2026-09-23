<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>BIRD (e-TIMS)</title>

<style>

*{box-sizing:border-box;margin:0;padding:0;}

body{
min-height:100vh;
background:url("{{asset('logo2.jpg')}}") no-repeat center center fixed;
background-size:cover;
font-family:'Segoe UI',sans-serif;
display:flex;
flex-direction:column;
}

/* ===== HEADER ===== */

.header{
background:linear-gradient(90deg, #ffffff, #ffffff);
color:white;
padding:15px 30px;
display:flex;
align-items:center;
justify-content:space-between;
box-shadow:0 4px 12px rgba(0,0,0,.3);
}

.header-left{
display:flex;
align-items:center;
gap:15px;
}

.header img{height:60px;}

.header-title{
font-size:22px;
font-weight:bold;
letter-spacing:.5px;
color: black;
}

/* ===== MAIN ===== */

main{
flex:1;
display:flex;
justify-content:center;
align-items:center;
gap:50px;
flex-wrap:wrap;
padding:40px 20px;
}

/* ===== LOGIN CARD ===== */

.container{
position:relative;
width:280px;
height:280px;
border-radius:25px;
overflow:hidden;
cursor:pointer;
background:rgba(255,255,255,.85);
box-shadow:0 12px 35px rgba(0,0,0,.3);
transition:.35s;
display:flex;
align-items:flex-end;
}

.container:hover{
transform:translateY(-8px) scale(1.04);
box-shadow:0 18px 50px rgba(0,0,0,.45);
}

/* moving light */
.circle{
position:absolute;
width:110px;
height:110px;
background:rgba(26,35,126,.25);
border-radius:50%;
pointer-events:none;
transform:translate(-9999px,-9999px);
transition:transform .1s;
}

/* label */
.label{
position:absolute;
top:18px;
left:18px;
background:#1A237E;
color:white;
padding:6px 14px;
border-radius:20px;
font-size:13px;
}

/* text */
.text{
padding:25px;
font-size:22px;
font-weight:600;
color:#111;
}

/* ===== FOOTER ===== */

footer{
background:#1A237E;
color:white;
text-align:center;
padding:18px;
font-size:15px;
box-shadow:0 -4px 12px rgba(0,0,0,.3);
}

footer a{
color:#ffd54f;
text-decoration:none;
font-weight:600;
}

/* ===== MOBILE ===== */

@media(max-width:700px){
.header-title{font-size:18px}
.container{width:90%;max-width:340px;height:240px;}
}

</style>
</head>

<body>

<!-- ===== HEADER ===== -->

<div class="header">

<div class="header-left">
<img src="{{ asset('assets/images/logo.png') }}">
<div class="header-title">
BIRD (E-Timse) Login
</div>
</div>

<div style="color: #1A237E;font-weight: 600;">
Welcome
</div>

</div>


<!-- ===== MAIN LOGIN OPTIONS ===== -->

<main>

<div class="container" data-link="{{ route('agency.login.form') }}">
<div class="circle"></div>
<div class="label">Access</div>
<div class="text">Agency Login</div>
</div>

<div class="container" data-link="{{route('admin.login')}}">
<div class="circle"></div>
<div class="label">Access</div>
<div class="text">Admin / Hostel Login</div>
</div>

<div class="container" data-link="{{route('faculty.login.form')}}">
<div class="circle"></div>
<div class="label">Access</div>
<div class="text">Faculty Login</div>
</div>

</main>


<!-- ===== FOOTER ===== -->

<footer>
© 2026 <strong>BIRD (E-Timse)</strong> |
Developed By
<a href="https://www.businessinnovations.in" target="_blank">
Business Innovations
</a>
</footer>


<script>

document.querySelectorAll(".container").forEach(container=>{

const circle=container.querySelector(".circle");

container.addEventListener("mousemove",e=>{

const r=container.getBoundingClientRect();
const radius=55;

let x=e.clientX-r.left;
let y=e.clientY-r.top;

x=Math.max(radius,Math.min(x,r.width-radius));
y=Math.max(radius,Math.min(y,r.height-radius));

circle.style.transform=`translate(${x-radius}px,${y-radius}px)`;

});

container.addEventListener("mouseleave",()=>{
circle.style.transform="translate(-9999px,-9999px)";
});

container.addEventListener("click",()=>{
window.location.href=container.dataset.link;
});

});

</script>

</body>
</html>