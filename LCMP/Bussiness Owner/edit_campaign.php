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

/* 🔎 Campaign ID check */
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid Campaign ID");
}

$campaignId = (int) $_GET['id'];
$promoterId = $_SESSION['UserId'];

/* 📥 Fetch campaign (ownership check included) */
$sql = "SELECT * FROM campaigns 
        WHERE CampaignId = ? AND OwnerId = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $campaignId, $promoterId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("Campaign not found or access denied");
}

$campaign = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Campaign | Business Overview</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">

    <style>
        .form-box{
            max-width:600px;
            margin:40px auto;
            background:#fff;
            padding:30px;
            border-radius:12px;
            box-shadow:0 6px 20px rgba(0,0,0,.08);
        }
        .form-box h2{color:#20204a;margin-bottom:20px;}
        .form-box input, .form-box textarea, .form-box select{
            width:100%;
            padding:12px;
            margin-bottom:15px;
            border-radius:8px;
            border:1px solid #ccc;
        }
        .form-box button{
            background:#2d7ff9;
            color:#fff;
            border:none;
            padding:12px;
            border-radius:8px;
            font-weight:bold;
            cursor:pointer;
        }
    </style>
</head>
<body>

<?php include(__DIR__ . "/../Common Files/navbar.php"); ?>

<div class="form-box">
    <h2>✏️ Edit Campaign</h2>

    <form action="update_campaign_process.php" method="POST">

        <input type="hidden" name="campaign_id" value="<?= $campaign['CampaignId']; ?>">

        <input type="text" name="title"
               value="<?= htmlspecialchars($campaign['Title']); ?>"
               required>

        <select name="category" required>
            <option value="">Select Category</option>
            <?php
            $categories = ['Food','Fashion','Tech','Local Business'];
            foreach ($categories as $cat):
            ?>
                <option value="<?= $cat ?>"
                    <?= ($campaign['Category'] === $cat) ? 'selected' : '' ?>>
                    <?= $cat ?>
                </option>
            <?php endforeach; ?>
        </select>

        <textarea name="description" rows="4" required><?= 
            htmlspecialchars($campaign['Description']); 
        ?></textarea>

        <input type="number" name="budget"
               min="1000" max="10000" step="500"
               value="<?= $campaign['Budget']; ?>" required>

        <select name="city" required>
            <?php
            $cities = ['Ahmedabad','Surat','Vadodara','Rajkot','Gandhinagar'];
            foreach ($cities as $city):
            ?>
                <option value="<?= $city ?>"
                    <?= ($campaign['City'] === $city) ? 'selected' : '' ?>>
                    <?= $city ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Update Campaign</button>
    </form>
</div>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
