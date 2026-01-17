<?php
require_once __DIR__ . '/../Common Files/auth_guard.php';

/* 🔐 Only Creator allowed */
if (strtolower($_SESSION['UserType']) !== 'creator') {
    header("Location: ../Authentication/Login-Page.php");
    exit;
}

require_once __DIR__ . '/../Common Files/config.php';

$userId = $_SESSION['UserId'];

/* 📄 Fetch creator profile */
$stmt = $conn->prepare("SELECT * FROM creators WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$profile = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$profile) {
    session_destroy();
    header("Location: ../Authentication/Login-Page.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Creator Dashboard | LCMP</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
<link rel="stylesheet" href="creator_dashboards.css">

<script>
function loadPage(page) {
    document.getElementById("content-frame").src = page;
}
</script>
</head>
<body>

<?php include(__DIR__ . '/../Common Files/navbar.php'); ?>

<div class="dashboard-container">

    <!-- Sidebar -->
    <aside class="sidebar">
        <h2>Creator Panel</h2>
        <ul>
            <li>
                <a href="#" onclick="loadPage('creator_campaigns.php?embed=1');return false;">
                    <i class="fa-solid fa-bullhorn"></i> Campaigns
                </a>
            </li>

            <li>
                <a href="#" onclick="loadPage('creator_profile.php?embed=1');return false;">
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
            <h1>👋 Welcome, <?= htmlspecialchars($profile['full_name']); ?></h1>
        </div>

        <iframe
            id="content-frame"
            src="creator_campaigns.php?embed=1"
            frameborder="0"
            style="width:100%; min-height:75vh;">
        </iframe>

    </main>
</div>

</body>
</html>

<?php $conn->close(); ?>
