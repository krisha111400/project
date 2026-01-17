<?php
session_start();
include("config.php");

// Check login
if (!isset($_SESSION['UserId'])) {
    header("Location: Login-Page.php");
    exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$userId = $_SESSION['UserId'];
$userType = $_SESSION['UserType'];

// Fetch campaign
if ($userType === "Admin") {
    $sql = "SELECT * FROM campaigns WHERE CampaignId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
} else {
    $sql = "SELECT * FROM campaigns WHERE CampaignId = ? AND OwnerId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id, $userId);
}

$stmt->execute();
$result = $stmt->get_result();
$campaign = $result->fetch_assoc();

if (!$campaign) {
    die("⚠ Campaign not found or access denied!");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title    = trim($_POST['title']);
    $category = trim($_POST['category']);
    $budget   = floatval($_POST['budget']);

    if ($userType === "Admin") {
        $update = $conn->prepare("UPDATE campaigns SET Title=?, Category=?, Budget=? WHERE CampaignId=?");
        $update->bind_param("ssdi", $title, $category, $budget, $id);
    } else {
        $update = $conn->prepare("UPDATE campaigns SET Title=?, Category=?, Budget=? WHERE CampaignId=? AND OwnerId=?");
        $update->bind_param("ssdii", $title, $category, $budget, $id, $userId);
    }

    if ($update->execute()) {
        if ($userType === "Admin") {
            header("Location: manage_campaigns.php?msg=updated");
        } else {
            header("Location: owner_dashboard.php?msg=updated");
        }
        exit;
    } else {
        $error = "Error: " . $update->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Campaign</title>
    <link rel="stylesheet" href="edit_campaign.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" />
</head>
<body>

<div class="form-container">
    <h2>Edit Campaign</h2>

    <?php if (!empty($error)): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="POST" class="edit-form">
        <label>Title:</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($campaign['Title']); ?>" required>

        <label>Category:</label>
        <select name="category" required>
            <?php 
            $categories = ["Fashion", "Food", "Technology", "Fitness"];
            foreach ($categories as $cat) {
                $safeCat = htmlspecialchars($cat);
                $selected = ($campaign['Category'] === $cat) ? "selected" : "";
                echo "<option value='$safeCat' $selected>$safeCat</option>";
            }
            ?>
        </select>

        <label>Budget:</label>
        <input type="number" step="0.01" name="budget" 
               value="<?php echo htmlspecialchars((string)$campaign['Budget']); ?>" required>

        <div class="form-buttons">
            <button type="submit" class="btn edit-btn"><i class="fa-solid fa-pen-to-square"></i> Update</button>
            <a href="<?php echo ($userType === 'Admin') ? 'manage_campaigns.php' : 'owner_dashboard.php'; ?>" class="btn back-btn"><i class="fa-solid fa-arrow-left"></i> Back</a>
        </div>
    </form>
</div>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
