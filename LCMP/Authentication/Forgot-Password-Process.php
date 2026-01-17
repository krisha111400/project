<?php
session_start();
require_once __DIR__ . '/../Common Files/config.php';

$email = trim($_POST['email'] ?? '');

if ($email === '') {
    $_SESSION['error'] = "Email required!";
    header("Location: Forgot-Password.php");
    exit;
}

$stmt = $conn->prepare("SELECT UserId FROM users_login WHERE Email=? LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    $_SESSION['error'] = "Email not found!";
    header("Location: Forgot-Password.php");
    exit;
}

$token  = bin2hex(random_bytes(32));
$expiry = date("Y-m-d H:i:s", strtotime("+30 minutes"));

$update = $conn->prepare("
    UPDATE users_login 
    SET reset_token=?, reset_token_expiry=? 
    WHERE Email=?
");
$update->bind_param("sss", $token, $expiry, $email);
$update->execute();

/* ⚠️ For now we show link (later email bhejna) */
$resetLink = "http://localhost/project/LCMP/Authentication/Reset-Password.php?token=$token";

$_SESSION['msg'] = "Reset link generated:<br><a href='$resetLink'>Click here</a>";
header("Location: Forgot-Password.php");
exit;
