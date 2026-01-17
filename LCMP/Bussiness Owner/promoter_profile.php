<?php
session_start();
require_once __DIR__ . '/../Common Files/config.php';

/* 🔐 Only Promoter allowed */
if (
    !isset($_SESSION['UserId']) ||
    strtolower($_SESSION['UserType']) !== 'promoter'
) {
    header("Location: ../Authentication/Login-Page.php");
    exit;
}

$userId = $_SESSION['UserId'];

/* 📥 Fetch promoter profile */
$stmt = $conn->prepare("
    SELECT * FROM business_owners 
    WHERE user_id = ?
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$profile = $stmt->get_result()->fetch_assoc();
$stmt->close();

/* 🆕 Create profile if not exists */
if (!$profile) {
    $stmt = $conn->prepare("
        INSERT INTO business_owners (user_id) VALUES (?)
    ");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->close();

    header("Location: promoter_profile.php");
    exit;
}

/* 💾 UPDATE PROFILE */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $businessName = trim($_POST['business_name'] ?? '');
    $ownerName    = trim($_POST['owner_full_name'] ?? '');
    $phone        = trim($_POST['phone'] ?? '');

    $stmt = $conn->prepare("
        UPDATE business_owners
        SET business_name = ?, owner_full_name = ?, phone = ?
        WHERE user_id = ?
    ");
    $stmt->bind_param(
        "sssi",
        $businessName,
        $ownerName,
        $phone,
        $userId
    );
    $stmt->execute();
    $stmt->close();

    /* ✅ REDIRECT TO DASHBOARD AFTER SAVE */
    header("Location: owner_dashboard.php?profile_updated=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Promoter Profile | LCMP</title>

<style>
body{
    font-family:Segoe UI, Arial;
    background:#f4f6fb;
    margin:0;
}
.profile-box{
    max-width:600px;
    margin:30px auto;
    background:#fff;
    padding:25px;
    border-radius:12px;
    box-shadow:0 6px 18px rgba(0,0,0,.08);
}
h2{
    margin-bottom:20px;
}
label{
    font-weight:600;
    display:block;
    margin:12px 0 6px;
}
input{
    width:100%;
    padding:10px;
    border:1px solid #ddd;
    border-radius:6px;
}
button{
    margin-top:18px;
    padding:10px 20px;
    background:#2563eb;
    color:#fff;
    border:none;
    border-radius:6px;
    cursor:pointer;
}
</style>
</head>
<body>

<div class="profile-box">

<h2>🏢 Promoter Profile</h2>

<form method="post">

    <label>Business Name</label>
    <input type="text"
           name="business_name"
           value="<?= htmlspecialchars($profile['business_name'] ?? '') ?>">

    <label>Owner Full Name</label>
    <input type="text"
           name="owner_full_name"
           value="<?= htmlspecialchars($profile['owner_full_name'] ?? '') ?>">

    <label>Phone</label>
    <input type="text"
           name="phone"
           value="<?= htmlspecialchars($profile['phone'] ?? '') ?>">

    <button type="submit">
        💾 Save Profile
    </button>

</form>
</div>

</body>
</html>

<?php $conn->close(); ?>
