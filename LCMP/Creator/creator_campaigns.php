<?php
session_start();
require_once __DIR__ . '/../Common Files/config.php';

/* 🔐 Creator login check */
if (
    !isset($_SESSION['UserId']) ||
    strtolower($_SESSION['UserType']) !== 'creator'
) {
    header("Location: ../Authentication/Login-Page.php");
    exit;
}

$creatorId = (int) $_SESSION['UserId'];

/* iframe check */
$embed = isset($_GET['embed']) && $_GET['embed'] == 1;

/* 📄 Fetch campaigns */
$sql = "SELECT CampaignId, Title, Category, Description, Budget, City
        FROM campaigns
        ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Available Campaigns</title>

<style>

/* ===== Base ===== */
body {
  margin: 0;
  font-family: Arial, sans-serif;
  background: #f4f6f9;
}

/* Page wrapper */
.campaign-page {
  padding: 25px;
}

/* Heading */
.campaign-page h2 {
  margin-bottom: 20px;
  color: #2c3e50;
  font-size: 22px;
}

/* ===== Campaign Card ===== */
.card {
  background: #ffffff;
  border-radius: 10px;
  padding: 20px 22px;
  margin-bottom: 18px;
  box-shadow: 0 3px 8px rgba(0,0,0,0.08);
  transition: transform 0.2s, box-shadow 0.2s;
}

.card:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 14px rgba(0,0,0,0.12);
}

.card h3 {
  margin: 0 0 10px;
  color: #2c3e50;
  font-size: 18px;
}

/* Info text */
.card p {
  margin: 5px 0;
  color: #555;
  line-height: 1.5;
}

.card p b {
  color: #2c3e50;
}

/* ===== Status ===== */
.status {
  margin: 10px 0;
  font-weight: bold;
  font-size: 14px;
}

/* ===== Buttons ===== */
.apply-btn,
.cancel-btn {
  padding: 7px 14px;
  border-radius: 5px;
  text-decoration: none;
  font-size: 14px;
  display: inline-block;
  transition: 0.3s;
}

/* Apply */
.apply-btn {
  background: #f39c12;
  color: #fff;
}

.apply-btn:hover {
  background: #d35400;
}

/* Cancel */
.cancel-btn {
  background: #7f8c8d;
  color: #fff;
  margin-left: 10px;
}

.cancel-btn:hover {
  background: #636e72;
}

/* Approved / Rejected colors */
.status.approved {
  color: #27ae60;
}

.status.rejected {
  color: #e74c3c;
}

/* ===== iframe compatibility ===== */
iframe body {
  background: #f4f6f9;
}

/* Responsive */
@media (max-width: 768px) {
  .campaign-page {
    padding: 15px;
  }

  .card {
    padding: 16px;
  }
}
</style>

<!-- Navbar sirf normal page pe -->
<?php if(!$embed): ?>
    <?php include(__DIR__ . "/../Common Files/navbar.php"); ?>
<?php endif; ?>

<h2>🔥 Available Campaigns</h2>

<?php while($row = $result->fetch_assoc()): ?>

<?php
$check = $conn->prepare(
    "SELECT status FROM campaign_applications
     WHERE campaign_id = ? AND creator_id = ?"
);
$check->bind_param("ii", $row['CampaignId'], $creatorId);
$check->execute();
$app = $check->get_result()->fetch_assoc();
$check->close();
?>

<div class="card">
    <h3><?= htmlspecialchars($row['Title']); ?></h3>

    <p><b>Category:</b> <?= htmlspecialchars($row['Category']); ?></p>
    <p><b>City:</b> <?= htmlspecialchars($row['City']); ?></p>
    <p><b>Budget:</b> ₹<?= number_format($row['Budget'],2); ?></p>
    <p><?= nl2br(htmlspecialchars($row['Description'])); ?></p>

    <!-- ACTION -->
    <?php if(!$app): ?>

        <a class="apply-btn"
           href="/project/LCMP/Creator/apply_campaign.php?id=<?= $row['CampaignId']; ?>"
           onclick="return confirm('Apply for this campaign?');">
           Apply
        </a>

    <?php elseif($app['status'] === 'pending'): ?>

        <p class="status">⏳ Applied (Pending)</p>

        <a class="cancel-btn"
           href="/project/LCMP/Creator/cancel_application.php?id=<?= $row['CampaignId']; ?>"
           onclick="return confirm('Cancel application?');">
           ❌ Cancel
        </a>

    <?php elseif($app['status'] === 'approved'): ?>

        <p class="status" style="color:green;">✅ Approved</p>

    <?php elseif($app['status'] === 'rejected'): ?>

        <p class="status" style="color:red;">❌ Rejected</p>

    <?php endif; ?>
</div>

<?php endwhile; ?>

</body>
</html>

<?php $conn->close(); ?>
