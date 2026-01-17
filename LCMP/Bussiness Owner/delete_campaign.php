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

$promoterId = $_SESSION['UserId'];

/* 🧾 Campaign ID validate */
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: owner_dashboard.php");
    exit;
}

$campaignId = (int) $_GET['id'];

/* 🔍 Ownership check (campaign isi promoter ki hai?) */
$stmt = $conn->prepare(
    "SELECT CampaignId 
     FROM campaigns 
     WHERE CampaignId = ? AND OwnerId = ?"
);
$stmt->bind_param("ii", $campaignId, $promoterId);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Campaign exist nahi karti ya user ki nahi hai
    header("Location: owner_dashboard.php");
    exit;
}

$stmt->close();

/* ❌ Delete campaign */
$stmt = $conn->prepare(
    "DELETE FROM campaigns 
     WHERE CampaignId = ? AND OwnerId = ?"
);
$stmt->bind_param("ii", $campaignId, $promoterId);
$stmt->execute();

$stmt->close();
$conn->close();

/* ✅ Redirect back */
header("Location: owner_dashboard.php?deleted=1");
exit;
