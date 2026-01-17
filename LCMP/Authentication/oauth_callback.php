<?php
session_start();
require_once(__DIR__ . '/../Common Files/config.php'); // DB connection

$provider = $_POST['oauth_provider'] ?? '';
$oauth_id  = $_POST['oauth_id'] ?? '';
$email     = $_POST['email'] ?? '';
$name      = $_POST['name'] ?? '';

if(empty($provider) || empty($oauth_id) || empty($email)){
    $_SESSION['error'] = "OAuth login failed!";
    header("Location: register.php");
    exit;
}

// Check if user exists
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND oauth_provider = ? AND oauth_id = ?");
$stmt->bind_param("sss", $email, $provider, $oauth_id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows > 0){
    // Already exists, log them in
    $_SESSION['success'] = "Welcome back! You are signed in.";
    header("Location: dashboard.php");
    exit;
}

// If not exists, insert user (default type: creator)
$userType = 'creator';
$stmt = $conn->prepare("INSERT INTO users (user_type, name, email, oauth_provider, oauth_id) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $userType, $name, $email, $provider, $oauth_id);
$stmt->execute();

$_SESSION['success'] = "Registration successful via $provider! You can now use your account.";
header("Location: dashboard.php");
exit;
?>
