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

$creatorUserId = (int) ($_GET['creator_id'] ?? 0);
$campaignId    = (int) ($_GET['campaign_id'] ?? 0);

if ($creatorUserId <= 0) {
    die("Invalid Creator");
}

/* 📌 Fetch creator profile */
$sql = "
SELECT 
    c.full_name,
    c.phone,
    c.city,
    c.social_handle,
    u.Email
FROM creators c
JOIN users_login u ON c.user_id = u.UserId
WHERE c.user_id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $creatorUserId);
$stmt->execute();
$result = $stmt->get_result();
$creator = $result->fetch_assoc();

if (!$creator) {
    die("Creator profile not found");
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Creator Profile | LCMP</title>
<style>
body{font-family:Arial;background:#f4f6f9}
.card{
    max-width:600px;
    margin:40px auto;
    background:#fff;
    padding:25px;
    border-radius:10px;
    box-shadow:0 5px 15px rgba(0,0,0,.1)
}
h2{margin-bottom:15px}
.info{margin:10px 0}
.label{font-weight:bold}
.btn{
    display:inline-block;
    margin-top:20px;
    padding:10px 18px;
    background:#007bff;
    color:#fff;
    text-decoration:none;
    border-radius:6px
}
</style>
</head>
<body>

<div class="card">
    <h2>👤 Creator Profile</h2>

    <div class="info"><span class="label">Name:</span> <?= htmlspecialchars($creator['full_name']) ?></div>
    <div class="info"><span class="label">Email:</span> <?= htmlspecialchars($creator['Email']) ?></div>
    <div class="info"><span class="label">Phone:</span> <?= htmlspecialchars($creator['phone']) ?></div>
    <div class="info"><span class="label">City:</span> <?= htmlspecialchars($creator['city']) ?></div>
    <div class="info"><span class="label">Instagram:</span> <?= htmlspecialchars($creator['social_handle']) ?></div>

    <?php if ($campaignId > 0): ?>
    <a href="../Bussiness Owner/campaign_applicants.php?campaign_id=<?= $campaignId ?>" class="btn">
        ⬅ Back
    </a>
<?php else: ?>
    <a href="../Bussiness Owner/creators_list.php" class="btn">
        ⬅ Back
    </a>
<?php endif; ?>

</div>

</body>
</html>
