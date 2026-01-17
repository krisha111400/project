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
   BLOCK CAMPAIGN
========================= */
if (isset($_GET['block'])) {
    $cid = (int)$_GET['block'];
    $conn->query("UPDATE campaigns SET status='blocked' WHERE CampaignId=$cid");
    header("Location: manage_campaigns.php");
    exit;
}

/* =========================
   UNBLOCK CAMPAIGN
========================= */
if (isset($_GET['unblock'])) {
    $cid = (int)$_GET['unblock'];
    $conn->query("UPDATE campaigns SET status='active' WHERE CampaignId=$cid");
    header("Location: manage_campaigns.php");
    exit;
}

/* =========================
   DELETE CAMPAIGN (STRICT)
========================= */
if (isset($_GET['delete'])) {
    $cid = (int)$_GET['delete'];
    $conn->query("DELETE FROM campaigns WHERE CampaignId = $cid");
    header("Location: manage_campaigns.php?deleted=1");
    exit;
}

/* =========================
   EXPORT CSV
========================= */
if (isset($_GET['export'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename=campaigns.csv');

    $out = fopen("php://output", "w");
    fputcsv($out, ['ID','Title','Category','City','Budget','Status','Promoter']);

    $q = $conn->query("
        SELECT c.CampaignId, c.Title, c.Category, c.City, c.Budget, c.status, u.UserName
        FROM campaigns c
        JOIN users_login u ON c.OwnerId = u.UserId
    ");

    while ($r = $q->fetch_assoc()) {
        fputcsv($out, $r);
    }
    fclose($out);
    exit;
}

/* =========================
   SEARCH
========================= */
$search = trim($_GET['search'] ?? '');

$sql = "
SELECT 
    c.CampaignId,
    c.Title,
    c.Category,
    c.City,
    c.Budget,
    c.status,
    c.created_at,
    u.UserName AS promoter,
    u.Status AS promoter_status,
    (
        SELECT COUNT(*) 
        FROM campaign_applications ca 
        WHERE ca.campaign_id = c.CampaignId
    ) AS applicants
FROM campaigns c
JOIN users_login u ON c.OwnerId = u.UserId
WHERE u.UserType = 'promoter'
";

if ($search !== '') {
    $safe = $conn->real_escape_string($search);
    $sql .= " AND (
        c.Title LIKE '%$safe%' OR
        c.Category LIKE '%$safe%' OR
        c.City LIKE '%$safe%' OR
        u.UserName LIKE '%$safe%'
    )";
}

$sql .= " ORDER BY c.CampaignId DESC";
$result = $conn->query($sql);

/* =========================
   ANALYTICS
========================= */
$totalCampaigns = $conn->query("SELECT COUNT(*) t FROM campaigns")->fetch_assoc()['t'];
$activeCampaigns = $conn->query("SELECT COUNT(*) t FROM campaigns WHERE status='active'")->fetch_assoc()['t'];
$blockedCampaigns = $conn->query("SELECT COUNT(*) t FROM campaigns WHERE status='blocked'")->fetch_assoc()['t'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin | Manage Campaigns</title>
    <link rel="stylesheet" href="manage_campaigns.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">
</head>
<body>

<?php include(__DIR__ . '/../Common Files/navbar.php'); ?>

<div class="container">

    <div class="header">
        <h1>📢 Manage Campaigns</h1>
        <a href="?export=1" class="btn primary">⬇ Export CSV</a>
    </div>

    <!-- ANALYTICS -->
    <div class="stats">
        <span>Total: <?= $totalCampaigns ?></span>
        <span>Active: <?= $activeCampaigns ?></span>
        <span>Blocked: <?= $blockedCampaigns ?></span>
    </div>

    <?php if (isset($_GET['deleted'])): ?>
        <div class="alert success">Campaign deleted successfully</div>
    <?php endif; ?>

    <form method="get" class="search-box">
        <input type="text" name="search"
               placeholder="Search title, category, city, promoter"
               value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Search</button>
    </form>

    <div class="table-wrapper">
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Category</th>
                <th>City</th>
                <th>Budget</th>
                <th>Promoter</th>
                <th>Status</th>
                <th>Applicants</th>
                <th>Actions</th>
            </tr>
            </thead>

            <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['CampaignId'] ?></td>
                    <td><?= htmlspecialchars($row['Title']) ?></td>
                    <td><?= htmlspecialchars($row['Category']) ?></td>
                    <td><?= htmlspecialchars($row['City']) ?></td>
                    <td>₹<?= number_format($row['Budget'],2) ?></td>

                    <td>
                        <?= htmlspecialchars($row['promoter']) ?><br>
                        <small style="color:<?= $row['promoter_status']=='blocked'?'red':'green' ?>">
                            (<?= ucfirst($row['promoter_status']) ?>)
                        </small>
                    </td>

                    <td>
                        <span class="status <?= $row['status'] ?>">
                            <?= ucfirst($row['status']) ?>
                        </span>
                    </td>

                    <td><?= $row['applicants'] ?></td>

                    <td class="actions">

                        <a class="btn"
                           href="view_campaign.php?id=<?= $row['CampaignId'] ?>">
                           👁 View
                        </a>

                        <?php if ($row['status'] === 'active'): ?>
                            <a class="btn warning"
                               href="?block=<?= $row['CampaignId'] ?>"
                               onclick="return confirm('Block this campaign?')">
                               🚫 Block
                            </a>
                        <?php else: ?>
                            <a class="btn primary"
                               href="?unblock=<?= $row['CampaignId'] ?>"
                               onclick="return confirm('Unblock this campaign?')">
                               ✅ Unblock
                            </a>
                        <?php endif; ?>

                        <a class="btn danger"
                           href="?delete=<?= $row['CampaignId'] ?>"
                           onclick="return confirm('Permanent delete?')">
                           🗑 Delete
                        </a>

                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>

<?php $conn->close(); ?>
