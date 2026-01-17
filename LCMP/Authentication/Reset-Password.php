<?php
session_start();
require_once __DIR__ . '/../Common Files/config.php';

/* =========================
   TOKEN CHECK
========================= */
if (!isset($_GET['token']) || empty($_GET['token'])) {
    die("Invalid password reset link!");
}

$token = $_GET['token'];

/* =========================
   VERIFY TOKEN
========================= */
$stmt = $conn->prepare("
    SELECT UserId 
    FROM users_login 
    WHERE reset_token = ?
    AND reset_token_expiry > NOW()
    LIMIT 1
");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("This reset link is invalid or expired!");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="Reset-Password.css">
</head>
<body>

<div class="reset-container">

    <h2>Set New Password</h2>

    <form method="post" action="Reset-Password-Process.php">

        <!-- TOKEN (SAFE) -->
        <input type="hidden" name="token" value="<?= htmlspecialchars($token, ENT_QUOTES, 'UTF-8') ?>">

        <label>New Password</label>
        <input type="password" name="password" required>

        <label>Confirm Password</label>
        <input type="password" name="confirm_password" required>

        <button type="submit">Update Password</button>

    </form>

</div>

</body>
</html>
