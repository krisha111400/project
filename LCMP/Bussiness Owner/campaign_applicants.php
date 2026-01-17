<?php
session_start();
require_once __DIR__ . '/../Common Files/config.php';

/* 🔐 Only Promoter allowed */
if (
    !isset($_SESSION['UserId']) ||
    !isset($_SESSION['UserType']) ||
    strtolower($_SESSION['UserType']) !== 'promoter'
) {
    header("Location: ../Authentication/Login-Page.php");
    exit;
}

/* ✅ Campaign ID check */
$campaignId = (int) ($_GET['campaign_id'] ?? 0);
if ($campaignId <= 0) {
    die("Invalid Campaign ID");
}

/* 📥 Fetch applicants */
$sql = "
    SELECT 
        ca.id AS application_id,
        ca.status,
        ca.applied_at,
        u.UserId,
        u.Username,
        u.Email
    FROM campaign_applications ca
    JOIN users_login u ON ca.creator_id = u.UserId
    WHERE ca.campaign_id = ?
    ORDER BY ca.applied_at DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $campaignId);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Campaign Applicants | LCMP</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">

<style>
body{
    font-family: Arial, sans-serif;
    background:#f5f6fa;
}

.container{
    max-width:1200px;
    margin:40px auto;
    background:#fff;
    padding:25px;
    border-radius:10px;
    box-shadow:0 5px 15px rgba(0,0,0,.1);
}

h1{
    margin-bottom:20px;
}

table{
    width:100%;
    border-collapse:collapse;
}

th,td{
    padding:12px;
    border-bottom:1px solid #ddd;
    text-align:left;
    vertical-align:middle;
}

th{
    background:#f1f1f1;
}

.btn{
    padding:6px 12px;
    border:none;
    border-radius:5px;
    cursor:pointer;
    font-size:14px;
}

.btn.success{
    background:#28a745;
    color:#fff;
}

.btn.danger{
    background:#dc3545;
    color:#fff;
}

.btn.view{
    background:#007bff;
    color:#fff;
    text-decoration:none;
}

.badge{
    padding:5px 10px;
    border-radius:5px;
    font-weight:bold;
    font-size:13px;
}

.badge.pending{
    background:#fff3cd;
    color:#856404;
}

.badge.approved{
    background:#d4edda;
    color:#155724;
}

.badge.rejected{
    background:#f8d7da;
    color:#721c24;
}

.alert{
    padding:10px;
    margin-bottom:15px;
    background:#d4edda;
    color:#155724;
    border-radius:6px;
}
</style>
</head>
<body>

<?php include(__DIR__ . "/../Common Files/navbar.php"); ?>

<div class="container">

<h1>👥 Campaign Applicants</h1>

<?php if (isset($_GET['updated'])): ?>
    <div class="alert">Application status updated successfully.</div>
<?php endif; ?>

<table>
<thead>
<tr>
    <th>#</th>
    <th>Creator Name</th>
    <th>Email</th>
    <th>Applied On</th>
    <th>Status</th>
    <th>View Profile</th>
    <th>Action</th>
</tr>
</thead>

<tbody>
<?php if ($result->num_rows > 0): ?>
<?php $i = 1; while ($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= $i++; ?></td>
    <td><?= htmlspecialchars($row['Username']); ?></td>
    <td><?= htmlspecialchars($row['Email']); ?></td>
    <td><?= date("d M Y", strtotime($row['applied_at'])); ?></td>

    <!-- ✅ STATUS -->
    <td>
        <span class="badge <?= $row['status']; ?>">
            <?= ucfirst($row['status']); ?>
        </span>
    </td>

    <!-- 👁 VIEW PROFILE -->
    <td>
        <a href="view_creator_profile.php?creator_id=<?= $row['UserId']; ?>&campaign_id=<?= $campaignId ?>"

           class="btn view"
           target="_blank">
           👁 View
        </a>
    </td>

    <!-- ✅ ACTION -->
    <td>
    <?php if ($row['status'] === 'pending'): ?>

        <form action="update_application_status.php" method="POST" style="display:inline;">
    <input type="hidden" name="application_id" value="<?= $row['application_id']; ?>">
    <input type="hidden" name="campaign_id" value="<?= $campaignId; ?>">
    <input type="hidden" name="status" value="approved">
    <button class="btn success">Approve</button>
</form>


        <form action="update_application_status.php" method="POST" style="display:inline;">
    <input type="hidden" name="application_id" value="<?= $row['application_id']; ?>">
    <input type="hidden" name="campaign_id" value="<?= $campaignId; ?>">
    <input type="hidden" name="status" value="rejected">
    <button class="btn danger">Reject</button>
</form>


    <?php else: ?>
        —
    <?php endif; ?>
    </td>
</tr>
<?php endwhile; ?>
<?php else: ?>
<tr>
    <td colspan="7" style="text-align:center;">No applicants found.</td>
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
