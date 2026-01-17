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

/* =========================
   GET CREATOR ID
========================= */
$creatorId = (int)($_GET['id'] ?? 0);

if ($creatorId <= 0) {
    header("Location: admin_creators.php");
    exit;
}

/* =========================
   FETCH CREATOR DATA (FIXED)
========================= */
$sql = "
SELECT 
    u.UserId,
    u.UserName,
    u.Email,
    u.Status,
    c.full_name,
    c.phone,
    c.city,
    c.social_handle
FROM users_login u
JOIN creators c ON u.UserId = c.user_id
WHERE u.UserId = ?
AND u.UserType = 'creator'
LIMIT 1
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $creatorId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    header("Location: admin_creators.php");
    exit;
}

$creator = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Creator | Admin</title>

    <link rel="stylesheet" href="admin_view_creator.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">
</head>
<body>

<?php include(__DIR__ . '/../Common Files/navbar.php'); ?>

<div class="container">

    <div class="header">
        <h1>Creator Profile</h1>
        <a href="admin_creators.php" class="back-btn">
            ← Back to Creators
        </a>
    </div>

    <div class="card">

        <div class="row">
            <label>Status</label>
            <span class="status <?= $creator['Status'] ?>">
                <?= ucfirst($creator['Status']) ?>
            </span>
        </div>

        <div class="row">
            <label>Username</label>
            <span><?= htmlspecialchars($creator['UserName']) ?></span>
        </div>

        <div class="row">
            <label>Full Name</label>
            <span><?= htmlspecialchars($creator['full_name']) ?></span>
        </div>

        <div class="row">
            <label>Email</label>
            <span><?= htmlspecialchars($creator['Email']) ?></span>
        </div>

        <div class="row">
            <label>Phone</label>
            <span><?= htmlspecialchars($creator['phone']) ?></span>
        </div>

        <div class="row">
            <label>City</label>
            <span><?= htmlspecialchars($creator['city']) ?></span>
        </div>

        <div class="row">
            <label>Social Handle</label>
            <span>
                <?php if (!empty($creator['social_handle'])): ?>
                    <?= htmlspecialchars($creator['social_handle']) ?>
                <?php else: ?>
                    —
                <?php endif; ?>
            </span>
        </div>

    </div>
</div>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
