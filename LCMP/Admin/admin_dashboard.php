<?php
session_start();
require_once __DIR__ . '/../Common Files/config.php';

/* ADMIN AUTH */
if (!isset($_SESSION['UserId']) || $_SESSION['UserType'] !== 'admin') {
    header("Location: ../Authentication/Login-Page.php");
    exit;
}

/* CAMPAIGNS */
$campTotal   = $conn->query("SELECT COUNT(*) t FROM campaigns")->fetch_assoc()['t'];
$campActive  = $conn->query("SELECT COUNT(*) t FROM campaigns WHERE status='active'")->fetch_assoc()['t'];
$campBlocked = $conn->query("SELECT COUNT(*) t FROM campaigns WHERE status='blocked'")->fetch_assoc()['t'];

/* CREATORS */
$creatorTotal   = $conn->query("SELECT COUNT(*) t FROM users_login WHERE UserType='creator'")->fetch_assoc()['t'];
$creatorActive  = $conn->query("SELECT COUNT(*) t FROM users_login WHERE UserType='creator' AND Status='active'")->fetch_assoc()['t'];
$creatorBlocked = $conn->query("SELECT COUNT(*) t FROM users_login WHERE UserType='creator' AND Status='blocked'")->fetch_assoc()['t'];

/* PROMOTERS */
$promoTotal   = $conn->query("SELECT COUNT(*) t FROM users_login WHERE UserType='promoter'")->fetch_assoc()['t'];
$promoActive  = $conn->query("SELECT COUNT(*) t FROM users_login WHERE UserType='promoter' AND Status='active'")->fetch_assoc()['t'];
$promoBlocked = $conn->query("SELECT COUNT(*) t FROM users_login WHERE UserType='promoter' AND Status='blocked'")->fetch_assoc()['t'];

/* APPLICATIONS */
$totalApplications = $conn->query("SELECT COUNT(*) t FROM campaign_applications")->fetch_assoc()['t'];
?>
<!DOCTYPE html>
<html>
<head>
<title>Admin Reports</title>
<link rel="stylesheet" href="admin_reports.css">
</head>
<body>

<?php include(__DIR__ . '/../Common Files/navbar.php'); ?>

<div class="container">

<h1 class="page-title">📊 Admin Reports</h1>

<div class="report-grid">

    <!-- CAMPAIGNS -->
    <div class="report-card">
        <h3>📢 Campaigns</h3>
        <div class="mini total">Total <span><?= $campTotal ?></span></div>
        <div class="mini active">Active <span><?= $campActive ?></span></div>
        <div class="mini blocked">Blocked <span><?= $campBlocked ?></span></div>
    </div>

    <!-- CREATORS -->
    <div class="report-card">
        <h3>🎨 Creators</h3>
        <div class="mini total">Total <span><?= $creatorTotal ?></span></div>
        <div class="mini active">Active <span><?= $creatorActive ?></span></div>
        <div class="mini blocked">Blocked <span><?= $creatorBlocked ?></span></div>
    </div>

    <!-- PROMOTERS -->
    <div class="report-card">
        <h3>🏢 Promoters</h3>
        <div class="mini total">Total <span><?= $promoTotal ?></span></div>
        <div class="mini active">Active <span><?= $promoActive ?></span></div>
        <div class="mini blocked">Blocked <span><?= $promoBlocked ?></span></div>
    </div>

    <!-- APPLICATIONS -->
    <div class="report-card applications">
        <h3>📥 Applications</h3>
        <div class="big-number"><?= $totalApplications ?></div>
    </div>

</div>

</div>

</body>
</html>

<?php $conn->close(); ?>
