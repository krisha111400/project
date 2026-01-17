<?php
session_start();
require_once __DIR__ . '/../Common Files/config.php';

if (
    !isset($_SESSION['UserId']) ||
    strtolower($_SESSION['UserType']) !== 'creator'
) {
    header("Location: ../Authentication/Login-Page.php");
    exit;
}

$creatorId  = (int) $_SESSION['UserId'];
$campaignId = (int) ($_GET['id'] ?? 0);

if ($campaignId <= 0) {
    header("Location: creator_campaigns.php");
    exit;
}

$sql = "DELETE FROM campaign_applications
        WHERE campaign_id = ?
        AND creator_id = ?
        AND status = 'pending'";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $campaignId, $creatorId);
$stmt->execute();

$stmt->close();
$conn->close();

header("Location: creator_campaigns.php");
exit;
