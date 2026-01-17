<?php
session_start();
require_once __DIR__ . '/../Common Files/config.php';





 if (isset($_GET['profile_updated'])): ?>
<div class="alert success" id="successAlert">
    ✅ Profile updated successfully
</div>

<script>
    setTimeout(function () {
        const alert = document.getElementById("successAlert");
        if (alert) {
            alert.style.opacity = "0";
            alert.style.transition = "opacity 0.6s ease";

            setTimeout(() => alert.remove(), 600);
        }
    }, 5000); // 5 seconds
</script>
<?php endif; 



/* 🔐 Only Promoter allowed */
if (
    !isset($_SESSION['UserId']) ||
    !isset($_SESSION['UserType']) ||
    strtolower($_SESSION['UserType']) !== 'promoter'
) {
    header("Location: ../Authentication/Login-Page.php");
    exit;
}






$promoterId = $_SESSION['UserId'];

/* 📢 Fetch campaigns created by this promoter */
$sql = "SELECT CampaignId, Title, Category, City, Budget 
        FROM campaigns 
        WHERE OwnerId = ?
        ORDER BY CampaignId DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $promoterId);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Promoter Dashboard | LCMP</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
  <link rel="stylesheet" href="owner_dashboard.css">
</head>
<body>

<?php include(__DIR__ . "/../Common Files/navbar.php"); ?>

<div class="dashboard-container">

    <!-- Sidebar -->
    <aside class="sidebar">
        <h2>Promoter Panel</h2>

        <ul>
            <li>
                <a href="owner_dashboard.php" class="active">
                    <i class="fa-solid fa-bullhorn"></i> My Campaigns
                </a>
            </li>

            <li>
                <a href="create_campaign.php">
                    <i class="fa-solid fa-plus"></i> Create Campaign
                </a>
            </li>

            <li>
                <a href="../Bussiness Owner/creators_list.php">
                    <i class="fa-solid fa-users"></i> Creators
                </a>
            </li>


            <li>
    <a href="promoter_profile.php" onclick="loadPage('promoter_profile.php?embed=1'); return false;">
        <i class="fa-solid fa-user-gear"></i> Manage Profile
    </a>
</li>




            <li>
                <a href="../Authentication/Logout.php">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </a>
            </li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="main-content">

        <div class="topbar">
            <h1>📢 Your Campaigns</h1>

            <a href="create_campaign.php" class="btn create-btn">
                + Create New Campaign
            </a>
        </div>

        <?php if (isset($_GET['deleted'])): ?>
            <div class="alert success"></div>
        <?php endif; ?>

        <table class="campaign-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Budget</th>
                    <th>City</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['CampaignId']; ?></td>
                        <td><?= htmlspecialchars($row['Title']); ?></td>
                        <td><?= htmlspecialchars($row['Category']); ?></td>
                        <td>₹<?= number_format($row['Budget'], 2); ?></td>
                        <td><?= htmlspecialchars($row['City']); ?></td>

                        <td class="actions">
                            <a class="btn small" href="edit_campaign.php?id=<?= $row['CampaignId']; ?>">
                                ✏️ Edit
                            </a>

                            <a class="btn small danger"
                               href="delete_campaign.php?id=<?= $row['CampaignId']; ?>"
                               onclick="return confirm('Are you sure you want to delete this campaign?');">
                               🗑 Delete
                            </a>

                            <!-- 🔥 IMPORTANT: Applicants page -->
                            <a class="btn small primary"
                               href="campaign_applicants.php?campaign_id=<?= $row['CampaignId']; ?>">
                               👥 View Applicants
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align:center;">
                        No campaigns created yet.
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>

    </main>
</div>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
