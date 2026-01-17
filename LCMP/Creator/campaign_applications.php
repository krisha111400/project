<?php
session_start();
include("../Common Files/config.php");

if (!isset($_SESSION['UserId']) || $_SESSION['UserType'] !== "creator") {
    echo "<p>You must login as Creator!</p>";
    exit;
}

// Handle Apply action
if (isset($_POST['apply_campaign'])) {
    $campaignId = intval($_POST['campaign_id']);
    $creatorId = $_SESSION['UserId'];

    // Check if already applied
    $check = $conn->prepare("SELECT * FROM campaign_applications WHERE CampaignId=? AND CreatorId=?");
    $check->bind_param("ii", $campaignId, $creatorId);
    $check->execute();
    $resultCheck = $check->get_result();

    if ($resultCheck->num_rows > 0) {
        $message = "⚠ You have already applied to this campaign.";
    } else {
        $stmt = $conn->prepare("INSERT INTO campaign_applications (CampaignId, CreatorId) VALUES (?, ?)");
        $stmt->bind_param("ii", $campaignId, $creatorId);
        if ($stmt->execute()) {
            $message = "✅ Successfully applied!";
        } else {
            $message = "❌ Something went wrong. Try again.";
        }
    }
}

// Fetch all campaigns
$sql = "SELECT c.CampaignId, c.Title, c.Category, c.Budget, u.UserName 
        FROM campaigns c 
        JOIN users_login u ON c.OwnerId = u.UserId 
        ORDER BY c.CampaignId DESC";







$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Campaigns</title>
    <link rel="stylesheet" href="creator_dashboard.css">
</head>
<body>
    <h2>Available Campaigns</h2>

    <?php if (!empty($message)): ?>
        <p class="message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <table class="campaign-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Category</th>
                <th>Budget</th>
                <th>Owner</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['CampaignId']; ?></td>
                    <td><?= htmlspecialchars($row['Title']); ?></td>
                    <td><?= htmlspecialchars($row['Category']); ?></td>
                    <td><?= number_format($row['Budget'], 2); ?></td>
                    <td><?= htmlspecialchars($row['UserName']); ?></td>
                    <td>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="campaign_id" value="<?= $row['CampaignId']; ?>">
                            <button type="submit" name="apply_campaign" class="btn apply-btn">Apply</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="6" style="text-align:center;">No campaigns available</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
<?php $conn->close(); ?>