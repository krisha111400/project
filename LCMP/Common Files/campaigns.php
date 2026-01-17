<?php
session_start();
require_once __DIR__ . '/config.php';

$userId   = $_SESSION['UserId']   ?? null;
$userType = strtolower($_SESSION['UserType'] ?? 'guest');

/* ================= FETCH CAMPAIGNS ================= */
if ($userType === 'creator') {

    $sql = "
        SELECT 
            c.*,
            ca.status AS apply_status
        FROM campaigns c
        LEFT JOIN campaign_applications ca
            ON c.CampaignId = ca.campaign_id
            AND ca.creator_id = ?
        ORDER BY c.created_at DESC
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);

} else {
    $sql = "SELECT * FROM campaigns ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Available Campaigns</title>
<link rel="stylesheet" href="campaign1.css">
</head>
<body>

<?php include("navbar.php"); ?>

<div class="campaigns-page">
<h1>📢 Available Campaigns</h1>

<table class="campaign-table">
<thead>
<tr>
    <th>Title</th>
    <th>Category</th>
    <th>City</th>
    <th>Budget</th>
    <th>Action</th>
</tr>
</thead>
<tbody>

<?php if ($result->num_rows > 0): ?>
<?php while ($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= htmlspecialchars($row['Title']); ?></td>
    <td><?= htmlspecialchars($row['Category']); ?></td>
    <td><?= htmlspecialchars($row['City']); ?></td>
    <td>₹<?= number_format($row['Budget'],2); ?></td>

    <td class="actions">

    <?php if ($userType === 'creator'): ?>

        <?php if ($row['apply_status'] === null): ?>
            <a href="Creator/apply_campaign.php?id=<?= $row['CampaignId']; ?>"
               class="btn apply-btn">Apply</a>

        <?php elseif ($row['apply_status'] === 'pending'): ?>
            <span class="status pending">Applied (Pending)</span>
            <a href="Creator/cancel_application.php?id=<?= $row['CampaignId']; ?>"
               class="btn cancel-btn"
               onclick="return confirm('Cancel application?');">
               ❌ Cancel
            </a>

        <?php elseif ($row['apply_status'] === 'approved'): ?>
            <span class="status approved">✔ Approved</span>

        <?php elseif ($row['apply_status'] === 'rejected'): ?>
            <span class="status rejected">✖ Rejected</span>

        <?php endif; ?>

    <?php else: ?>
        <span class="login-note">Login as Creator</span>
    <?php endif; ?>

    </td>
</tr>
<?php endwhile; ?>
<?php else: ?>
<tr>
<td colspan="5" style="text-align:center;">No campaigns found</td>
</tr>
<?php endif; ?>

</tbody>
</table>
</div>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
