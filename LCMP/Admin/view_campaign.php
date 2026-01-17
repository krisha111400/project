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
   CAMPAIGN ID
========================= */
$cid = (int)($_GET['id'] ?? 0);
if ($cid <= 0) {
    header("Location: manage_campaigns.php");
    exit;
}

/* =========================
   FETCH CAMPAIGN + OWNER
========================= */
$sql = "
SELECT 
    c.*,
    u.UserName AS promoter,
    u.Email AS promoter_email,
    u.Status AS promoter_status
FROM campaigns c
JOIN users_login u ON c.OwnerId = u.UserId
WHERE c.CampaignId = ?
LIMIT 1
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $cid);
$stmt->execute();
$campaign = $stmt->get_result()->fetch_assoc();

if (!$campaign) {
    header("Location: manage_campaigns.php");
    exit;
}

/* =========================
   FETCH APPLICANTS
========================= */
$appSql = "
SELECT 
    cr.full_name,
    cr.city,
    cr.phone,
    ca.applied_at
FROM campaign_applications ca
JOIN creators cr ON ca.creator_id = cr.user_id
WHERE ca.campaign_id = ?
ORDER BY ca.applied_at DESC
";
$appStmt = $conn->prepare($appSql);
$appStmt->bind_param("i", $cid);
$appStmt->execute();
$applicants = $appStmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
<title>View Campaign | Admin</title>
<link rel="stylesheet" href="view_campaign.css">
</head>
<body>

<?php include(__DIR__ . '/../Common Files/navbar.php'); ?>

<div class="container">

<!-- HEADER -->
<div class="header">
    <h1>📢 Campaign Details</h1>
    <a href="manage_campaigns.php" class="back-btn">← Back</a>
</div>

<!-- CAMPAIGN CARD -->
<div class="card">

<div class="row">
<label>Status</label>
<span class="badge <?= $campaign['status'] ?>">
<?= ucfirst($campaign['status']) ?>
</span>
</div>

<div class="row">
<label>Title</label>
<span><?= htmlspecialchars($campaign['Title']) ?></span>
</div>

<div class="row">
<label>Category</label>
<span><?= htmlspecialchars($campaign['Category']) ?></span>
</div>

<div class="row">
<label>City</label>
<span><?= htmlspecialchars($campaign['City']) ?></span>
</div>

<div class="row">
<label>Budget</label>
<span>₹<?= number_format($campaign['Budget'],2) ?></span>
</div>

<div class="row">
<label>Description</label>
<span><?= nl2br(htmlspecialchars($campaign['Description'])) ?></span>
</div>

<div class="row">
<label>Created On</label>
<span><?= date('d M Y', strtotime($campaign['created_at'])) ?></span>
</div>

<hr>

<h3>👤 Promoter Details</h3>

<div class="row">
<label>Name</label>
<span><?= htmlspecialchars($campaign['promoter']) ?></span>
</div>

<div class="row">
<label>Email</label>
<span><?= htmlspecialchars($campaign['promoter_email']) ?></span>
</div>

<div class="row">
<label>Account Status</label>
<span class="badge <?= $campaign['promoter_status'] ?>">
<?= ucfirst($campaign['promoter_status']) ?>
</span>
</div>

<hr>

<!-- ACTIONS -->
<div class="actions">

<?php if ($campaign['status'] === 'active'): ?>
    <a href="manage_campaigns.php?block=<?= $cid ?>"
       class="btn warning"
       onclick="return confirm('Block this campaign?')">
       🚫 Block Campaign
    </a>
<?php else: ?>
    <a href="manage_campaigns.php?unblock=<?= $cid ?>"
       class="btn primary"
       onclick="return confirm('Unblock this campaign?')">
       ✅ Unblock Campaign
    </a>
<?php endif; ?>

<a href="manage_campaigns.php?delete=<?= $cid ?>"
   class="btn danger"
   onclick="return confirm('Delete campaign permanently?')">
   🗑 Delete Campaign
</a>

</div>

</div>

<!-- APPLICANTS -->
<div class="card">
<h3>👥 Applicants (<?= $applicants->num_rows ?>)</h3>

<?php if ($applicants->num_rows > 0): ?>
<table>
<tr>
<th>Name</th>
<th>City</th>
<th>Phone</th>
<th>Applied On</th>
</tr>

<?php while($a = $applicants->fetch_assoc()): ?>
<tr>
<td><?= htmlspecialchars($a['full_name']) ?></td>
<td><?= htmlspecialchars($a['city']) ?></td>
<td><?= htmlspecialchars($a['phone']) ?></td>
<td><?= date('d M Y', strtotime($a['applied_at'])) ?></td>
</tr>
<?php endwhile; ?>
</table>
<?php else: ?>
<p>No applicants yet.</p>
<?php endif; ?>

</div>

</div>

</body>
</html>

<?php
$stmt->close();
$appStmt->close();
$conn->close();
?>
