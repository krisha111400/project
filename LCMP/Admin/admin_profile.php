<?php
session_start();
require_once __DIR__ . '/../Common Files/config.php';

/* =========================
   ADMIN AUTH
========================= */
if (!isset($_SESSION['UserId']) || $_SESSION['UserType'] !== 'admin') {
    header("Location: ../Authentication/Login-Page.php");
    exit;
}

$adminId = $_SESSION['UserId'];
$msg = "";

/* =========================
   UPDATE PROFILE
========================= */
if (isset($_POST['update_profile'])) {
    $name  = trim($_POST['name']);
    $email = trim($_POST['email']);

    $stmt = $conn->prepare(
        "UPDATE users_login SET UserName=?, Email=? WHERE UserId=?"
    );
    $stmt->bind_param("ssi", $name, $email, $adminId);
    $stmt->execute();
    $stmt->close();

    $msg = "Profile updated successfully ✅";
}

/* =========================
   CHANGE PASSWORD
========================= */
if (isset($_POST['change_password'])) {
    $old = $_POST['old_password'];
    $new = $_POST['new_password'];

    $q = $conn->query(
        "SELECT Password FROM users_login WHERE UserId=$adminId"
    );
    $row = $q->fetch_assoc();

    if (password_verify($old, $row['Password'])) {
        $hash = password_hash($new, PASSWORD_DEFAULT);
        $conn->query(
            "UPDATE users_login SET Password='$hash' WHERE UserId=$adminId"
        );
        $msg = "Password changed successfully 🔐";
    } else {
        $msg = "Old password incorrect ❌";
    }
}

/* =========================
   FETCH ADMIN
========================= */
$admin = $conn->query(
    "SELECT UserName, Email, created_at 
     FROM users_login WHERE UserId=$adminId"
)->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
<title>Admin Profile</title>
<link rel="stylesheet" href="admin_profile.css">
</head>
<body>

<?php include(__DIR__ . '/../Common Files/navbar.php'); ?>

<div class="container">

<h1>👤 Admin Profile</h1>

<?php if ($msg): ?>
<div class="alert"><?= $msg ?></div>
<?php endif; ?>

<div class="profile-grid">

<!-- PROFILE INFO -->
<div class="card">
<h3>📄 Profile Details</h3>

<form method="post">
<label>Name</label>
<input type="text" name="name" required
value="<?= htmlspecialchars($admin['UserName']) ?>">

<label>Email</label>
<input type="email" name="email" required
value="<?= htmlspecialchars($admin['Email']) ?>">

<label>Account Created</label>
<input type="text" disabled
value="<?= date('d M Y', strtotime($admin['created_at'])) ?>">

<button name="update_profile" class="btn primary">
💾 Update Profile
</button>
</form>
</div>

<!-- PASSWORD -->
<div class="card">
<h3>🔐 Change Password</h3>

<form method="post">
<label>Old Password</label>
<input type="password" name="old_password" required>

<label>New Password</label>
<input type="password" name="new_password" required>

<button name="change_password" class="btn warning">
🔁 Change Password
</button>
</form>
</div>

</div>

</div>

</body>
</html>

<?php $conn->close(); ?>
