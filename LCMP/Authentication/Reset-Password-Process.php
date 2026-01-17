<?php
session_start();
require_once __DIR__ . '/../Common Files/config.php';

$error = "";

$token   = $_POST['token'] ?? '';
$pass    = $_POST['password'] ?? '';
$confirm = $_POST['confirm_password'] ?? '';

/* =========================
   VALIDATION
========================= */
if ($token === '' || $pass === '' || $confirm === '') {
    $error = "Invalid request!";
} elseif ($pass !== $confirm) {
    $error = "Passwords do not match!";
}

/* =========================
   IF ERROR → SHOW MESSAGE
========================= */
if ($error !== ''):
?>
<!DOCTYPE html>
<html>
<head>
    <title>Error</title>
    <style>
        body{
            background:#f6f7fb;
            font-family:Arial, sans-serif;
            display:flex;
            justify-content:center;
            align-items:center;
            height:100vh;
        }
        .msg-box{
            background:#fff;
            padding:40px;
            border-radius:12px;
            box-shadow:0 10px 25px rgba(0,0,0,0.1);
            text-align:center;
        }
        .msg-box h2{
            color:#dc2626;
            margin-bottom:15px;
        }
        .msg-box a{
            display:inline-block;
            margin-top:15px;
            color:#2563eb;
            text-decoration:none;
            font-weight:600;
        }
    </style>
</head>
<body>

<div class="msg-box">
    <h2><?= htmlspecialchars($error) ?></h2>
    <a href="javascript:history.back()">⬅ Go Back</a>
</div>

</body>
</html>
<?php
exit;
endif;

/* =========================
   UPDATE PASSWORD
========================= */
$hash = password_hash($pass, PASSWORD_DEFAULT);

$stmt = $conn->prepare("
    UPDATE users_login 
    SET Password = ?, reset_token = NULL, reset_token_expiry = NULL 
    WHERE reset_token = ?
");
$stmt->bind_param("ss", $hash, $token);
$stmt->execute();

if ($stmt->affected_rows !== 1) {
    $error = "Invalid or expired reset token!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Password Updated</title>

    <!-- AUTO REDIRECT AFTER 5 SECONDS -->
    <meta http-equiv="refresh" content="5;url=Login-Page.php">

    <style>
        body{
            background:#f6f7fb;
            font-family:Arial, sans-serif;
            display:flex;
            justify-content:center;
            align-items:center;
            height:100vh;
        }
        .msg-box{
            background:#fff;
            padding:40px;
            border-radius:12px;
            box-shadow:0 10px 25px rgba(0,0,0,0.1);
            text-align:center;
        }
        .msg-box h2{
            color:#16a34a;
            margin-bottom:15px;
        }
        .msg-box p{
            color:#555;
            font-size:14px;
        }
    </style>
</head>
<body>

<div class="msg-box">
    <h2>Password updated successfully!</h2>
    
</div>

</body>
</html>
