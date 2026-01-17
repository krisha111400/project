<?php
session_start();
require_once __DIR__ . '/../Common Files/config.php';

/* 🔐 Only Creator allowed */
if (
    !isset($_SESSION['UserId']) ||
    strtolower($_SESSION['UserType']) !== 'creator'
) {
    header("Location: ../Authentication/Login-Page.php");
    exit;
}

$userId = $_SESSION['UserId'];

/* ================= FETCH PROFILE ================= */
$stmt = $conn->prepare("
    SELECT full_name, phone, city, social_handle 
    FROM creators 
    WHERE user_id = ?
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$profile = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$profile) {
    die("Creator profile not found.");
}

/* ================= UPDATE PROFILE ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $full_name     = trim($_POST['full_name'] ?? '');
    $phone         = trim($_POST['phone'] ?? '');
    $city          = trim($_POST['city'] ?? '');
    $social_handle = trim($_POST['social_handle'] ?? '');

    $stmt = $conn->prepare("
        UPDATE creators 
        SET full_name = ?, phone = ?, city = ?, social_handle = ?
        WHERE user_id = ?
    ");
    $stmt->bind_param(
        "ssssi",
        $full_name,
        $phone,
        $city,
        $social_handle,
        $userId
    );
    $stmt->execute();
    $stmt->close();

    header("Location: creator_profile.php?updated=1");
    exit;
}

$embed = isset($_GET['embed']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Creator Profile | LCMP</title>

<link rel="stylesheet" href="creator_dashboard.css">

<style>
.profile-box{
    background:#ffffff;
    border-radius:16px;
    padding:30px;
    max-width:850px;
    margin:auto;
    box-shadow:0 8px 25px rgba(0,0,0,0.08);
}
.profile-box h2{
    margin-bottom:25px;
}
.form-group{
    margin-bottom:18px;
}
.form-group label{
    font-weight:600;
    display:block;
    margin-bottom:6px;
}
.form-group input{
    width:100%;
    padding:12px;
    border-radius:8px;
    border:1px solid #d1d5db;
}
.save-btn{
    background:#4f46e5;
    color:white;
    padding:12px 22px;
    border:none;
    border-radius:8px;
    font-weight:600;
    cursor:pointer;
}
.save-btn:hover{
    background:#4338ca;
}
.alert{
    background:#dcfce7;
    color:#166534;
    padding:12px;
    border-radius:8px;
    margin-bottom:20px;
}
</style>
</head>

<body>

<?php if (!$embed): ?>
<?php endif; ?>

<div class="profile-box">

<h2>👤 Creator Profile</h2>

<?php if (isset($_GET['updated'])): ?>
    <div class="alert">Profile updated successfully.</div>
<?php endif; ?>

<form method="POST">

    <div class="form-group">
        <label>Full Name</label>
        <input type="text" name="full_name"
               value="<?= htmlspecialchars($profile['full_name']); ?>" required>
    </div>

    <div class="form-group">
        <label>Phone</label>
        <input type="text" name="phone"
               value="<?= htmlspecialchars($profile['phone']); ?>">
    </div>

    <div class="form-group">
        <label>City</label>
        <input type="text" name="city"
               value="<?= htmlspecialchars($profile['city']); ?>">
    </div>

    <div class="form-group">
        <label>Instagram / Social Handle</label>
        <input type="text" name="social_handle"
               value="<?= htmlspecialchars($profile['social_handle']); ?>">
    </div>

    <button class="save-btn">Save Changes</button>

</form>

</div>
</body>
</html>

<?php $conn->close(); ?>
