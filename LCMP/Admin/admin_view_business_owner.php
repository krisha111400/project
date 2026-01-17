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

$ownerId = (int)($_GET['id'] ?? 0);
if ($ownerId <= 0) {
    header("Location: admin_business_owners.php");
    exit;
}

/* =========================
   FETCH OWNER
========================= */
$sql = "
SELECT 
    u.UserId,
    u.UserName,
    u.Email,
    u.Status,
    b.business_name,
    b.phone
FROM users_login u
LEFT JOIN business_owners b ON u.UserId = b.user_id
WHERE u.UserId = ?
AND u.UserType = 'promoter'
LIMIT 1
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $ownerId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    header("Location: admin_business_owners.php");
    exit;
}

$owner = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
<title>View Business Owner</title>
<link rel="stylesheet" href="admin_view_business_owner.css">
</head>
<body>

<?php include(__DIR__ . '/../Common Files/navbar.php'); ?>

<div class="card">
    <h2>🏢 Business Owner Profile</h2>

    <p>
        <b>Status:</b>
        <span class="badge <?= htmlspecialchars($owner['Status']) ?>">
            <?= ucfirst(htmlspecialchars($owner['Status'])) ?>
        </span>
    </p>

    <p><b>Username:</b> <?= htmlspecialchars($owner['UserName']) ?></p>
    <p><b>Email:</b> <?= htmlspecialchars($owner['Email']) ?></p>

    <!-- ✅ BUSINESS NAME ADDED -->
    <p>
        <b>Business Name:</b>
        <?= htmlspecialchars($owner['business_name'] ?? '—') ?>
    </p>

    <p>
        <b>Phone:</b>
        <?= htmlspecialchars($owner['phone'] ?? '—') ?>
    </p>

    <a href="admin_business_owners.php" class="btn">⬅ Back</a>
</div>

</body>
</html>
