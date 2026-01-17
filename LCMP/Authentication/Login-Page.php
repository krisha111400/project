<?php
session_start();

/* =========================
   ALREADY LOGGED IN REDIRECT
========================= */
if (!empty($_SESSION['UserType'])) {

    switch (strtolower($_SESSION['UserType'])) {

        case 'admin':
            header("Location: ../Admin/admin_dashboard.php");
            exit;

        case 'promoter':
            header("Location: ../BusinessOwner/owner_dashboard.php");
            exit;

        case 'creator':
            header("Location: ../Creator/creator_dashboard.php");
            exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Local Creator Marketing Platform</title>

    <link rel="stylesheet" href="../Authentication/Login-Page.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"/>
</head>
<body>

<div class="Login-Box">

    <div class="Login">
        <h2>Welcome Back</h2>
    </div>

    <div class="Form-Section">
        <form action="Login_Process.php" method="post" autocomplete="off">

            <label>Email or Username</label>
            <input type="text" name="login_id" required>

            <label>Password</label>
            <input type="password" name="Password" required>

            <label>User Type</label>
            <select name="UserType" required>
                <option value="">-- Select User Type --</option>
                <option value="admin">Admin</option>
                <option value="creator">Creator</option>
                <option value="promoter">Business Owner</option>
            </select>

            <button type="submit">
                <i class="fa-solid fa-right-to-bracket"></i> Login
            </button>


            <p class="forgot-password">
    <a href="Forgot-Password.php">Forgot Password?</a>
</p>

<p class="register-link">
    New user?
    <a href="Register-page.php">Create an Account</a>
</p>


            <?php if(isset($_SESSION['error'])): ?>
                <p style="color:red;text-align:center;">
                    <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                </p>
            <?php endif; ?>

        </form>
    </div>

</div>
</body>
</html>
