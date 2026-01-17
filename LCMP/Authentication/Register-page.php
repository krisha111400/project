<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Join Creator Connect | Local Creator Marketing</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Arial',sans-serif;}
body{background:#f6f7fb;color:#222;}

/* HEADER */
.header{text-align:center;padding:50px 20px 30px;}
.header h1{font-size:2.5rem;color:#20204a;}
.header p{margin-top:10px;font-size:1rem;color:#555;max-width:600px;margin:auto;}

/* REGISTER BOX */
.register-box{background:#fff;border-radius:15px;margin:0 auto;padding:35px 30px;max-width:450px;box-shadow:0 6px 20px rgba(0,0,0,0.08);}
.toggle{display:flex;margin-bottom:20px;border-radius:8px;overflow:hidden;border:1px solid #ddd;}
.toggle button{flex:1;height:50px;border:none;cursor:pointer;font-weight:bold;background:#f0f0f0;color:#555;}
.toggle button.active{background:#20204a;color:#fff;}
.step{font-size:.95rem;color:#777;margin-bottom:10px;}

input{width:100%;padding:12px 15px;margin-bottom:15px;border-radius:8px;border:1px solid #ccc;}
input:focus{border-color:#e5533d;outline:none;}

.btn{width:100%;height:50px;background:#e5533d;color:#fff;border:none;border-radius:8px;font-size:1rem;font-weight:bold;cursor:pointer;}
.btn:disabled{opacity:.6;cursor:not-allowed;}

.password-box{position:relative;}
.password-box i{position:absolute;right:15px;top:50%;transform:translateY(-50%);cursor:pointer;color:#666;}

.alert{padding:10px 15px;margin-bottom:15px;border-radius:6px;font-weight:bold;}
.alert-success{background:#d4edda;color:#155724;}
.alert-error{background:#f8d7da;color:#721c24;}

.footer-text{text-align:center;margin-top:20px;font-size:.9rem;}
.footer-text a{color:#e5533d;text-decoration:none;font-weight:500;}

.why{max-width:1100px;margin:50px auto;padding:0 20px;}
.why h2{text-align:center;margin-bottom:30px;color:#20204a;}
.why-grid{display:grid;grid-template-columns:1fr 1fr;gap:25px;}
.why-card{background:#fff;padding:25px;border-radius:12px;box-shadow:0 6px 18px rgba(0,0,0,0.08);}
.why-card h3{color:#e5533d;margin-bottom:10px;}
.why-card ul{padding-left:18px;}
</style>
</head>

<body>

<div class="header">
    <h1>Get Started with LCMP</h1>
    <p>Connecting  Promoters And Creators To Grow Real Businesses.</p>
</div>

<div class="register-box">

<?php
if(isset($_SESSION['error'])){
    echo '<div class="alert alert-error">'.$_SESSION['error'].'</div>';
    unset($_SESSION['error']);
}
if(isset($_SESSION['success'])){
    echo '<div class="alert alert-success">'.$_SESSION['success'].'</div>';
    unset($_SESSION['success']);
}
?>

<div class="toggle">
    <button id="promoterBtn" class="active">Promoter</button>
    <button id="creatorBtn">Creator</button>
</div>

<div class="step" id="stepText">Promoter Registration</div>

<!-- PROMOTER FORM -->
<form id="promoterForm" action="Regiser_Process.php" method="POST">
    <input type="hidden" name="userType" value="promoter">

    <input type="text" name="business_name" placeholder="Business / Brand Name" required>
    <input type="text" name="owner_full_name" placeholder="Owner Full Name" required>
    <input type="tel" name="phone" placeholder="Phone Number" pattern="[0-9]{10}" required>
    <input type="email" name="email" placeholder="Email Address" required>

    <div class="password-box">
        <input type="password" name="password" placeholder="Create Password" required>
        <i class="fa-solid fa-eye" onclick="togglePassword(this)"></i>
    </div>

    <div class="password-box">
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        <i class="fa-solid fa-eye" onclick="togglePassword(this)"></i>
    </div>

    <div class="strength"></div>

    <button class="btn" type="submit" disabled>Continue</button>
</form>

<!-- CREATOR FORM -->
<form id="creatorForm" action="Regiser_Process.php" method="POST" style="display:none;">
    <input type="hidden" name="userType" value="creator">

    <input type="text" name="full_name" placeholder="Full Name" required>
    <input type="tel" name="phone" placeholder="Phone Number" pattern="[0-9]{10}" required>
    <input type="email" name="email" placeholder="Email Address" required>
    
    <input type="text" name="city" placeholder="City" required>
    

    <div class="password-box">
        <input type="password" name="password" placeholder="Create Password" required>
        <i class="fa-solid fa-eye" onclick="togglePassword(this)"></i>
    </div>

    <div class="password-box">
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        <i class="fa-solid fa-eye" onclick="togglePassword(this)"></i>
    </div>

    <div class="strength"></div>

    <button class="btn" type="submit" disabled>Continue</button>
</form>

<div class="footer-text">
    Already have an account? <a href="Login-Page.php">Login</a>
</div>
</div>

<div class="why">
<h2>Why Creator Connect?</h2>
<div class="why-grid">
    <div class="why-card">
        <h3>For Promoters</h3>
        <ul>
            <li>Launch local campaigns easily</li>
            <li>Reach trusted local audiences</li>
            <li>Work directly with creators</li>
        </ul>
    </div>
    <div class="why-card">
        <h3>For Creators</h3>
        <ul>
            <li>Find paid local collaborations</li>
            <li>Grow influence in your city</li>
            <li>Build long-term partnerships</li>
        </ul>
    </div>
</div>
</div>

<script>
const promoterBtn = document.getElementById("promoterBtn");
const creatorBtn  = document.getElementById("creatorBtn");
const promoterForm = document.getElementById("promoterForm");
const creatorForm = document.getElementById("creatorForm");
const stepText = document.getElementById("stepText");

promoterBtn.onclick = ()=>{
    promoterBtn.classList.add("active");
    creatorBtn.classList.remove("active");
    promoterForm.style.display="block";
    creatorForm.style.display="none";
    stepText.textContent="Promoter Registration";
};

creatorBtn.onclick = ()=>{
    creatorBtn.classList.add("active");
    promoterBtn.classList.remove("active");
    promoterForm.style.display="none";
    creatorForm.style.display="block";
    stepText.textContent="Creator Registration";
};

function togglePassword(icon){
    const input = icon.previousElementSibling;
    input.type = input.type === "password" ? "text" : "password";
    icon.classList.toggle("fa-eye-slash");
}

document.querySelectorAll("form").forEach(form=>{
    const btn = form.querySelector("button");
    form.addEventListener("input",()=>{
        const pwd = form.querySelector('input[name="password"]').value;
        const cp  = form.querySelector('input[name="confirm_password"]').value;
        const strong = pwd.length>=8 && /[0-9]/.test(pwd) && /[@$!%*#?&]/.test(pwd);
        btn.disabled = !(strong && pwd===cp);
    });
});
</script>

</body>
</html>
