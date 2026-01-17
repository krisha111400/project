<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
<link rel="stylesheet" href="../Authentication/Forgot-Password.css">
</head>
<body>

<div class="Login-Box">
    <h2>Forgot Password</h2>

    <form method="post" action="Forgot-Password-Process.php">
        <label>Email</label>
        <input type="email" name="email" required>

        <button type="submit">Send Reset Link</button>

        <?php if(isset($_SESSION['msg'])): ?>
            <p style="color:green"><?= $_SESSION['msg']; unset($_SESSION['msg']); ?></p>
        <?php endif; ?>

        <?php if(isset($_SESSION['error'])): ?>
            <p style="color:red"><?= $_SESSION['error']; unset($_SESSION['error']); ?></p>
        <?php endif; ?>
    </form>
</div>

</body>
</html>
