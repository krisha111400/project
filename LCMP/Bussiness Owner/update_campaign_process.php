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

/* 🧾 Validate POST */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request");
}

$promoterId = $_SESSION['UserId'];

/* 📥 Get & sanitize inputs */
$campaignId  = (int) ($_POST['campaign_id'] ?? 0);
$title       = trim($_POST['title'] ?? '');
$category    = trim($_POST['category'] ?? '');
$description = trim($_POST['description'] ?? '');
$budget      = (int) ($_POST['budget'] ?? 0);
$city        = trim($_POST['city'] ?? '');

/* ❌ Basic validation */
if (
    $campaignId <= 0 ||
    $title === '' ||
    $category === '' ||
    $description === '' ||
    $city === ''
) {
    die("All fields are required");
}

/* 💰 Budget validation */
if ($budget < 1000 || $budget > 10000 || $budget % 500 !== 0) {
    die("Budget must be between ₹1,000 and ₹10,000 in steps of ₹500");
}

/* 🔎 Ownership check */
$checkSql = "SELECT CampaignId FROM campaigns 
            WHERE CampaignId = ? AND OwnerId = ?";
$checkStmt = $conn->prepare($checkSql);
$checkStmt->bind_param("ii", $campaignId, $promoterId);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows !== 1) {
    die("Unauthorized action");
}
$checkStmt->close();

/* ✏️ Update campaign */
$updateSql = "UPDATE campaigns SET 
                Title = ?, 
                Category = ?, 
                Description = ?, 
                Budget = ?, 
                City = ?
              WHERE CampaignId = ? AND OwnerId = ?";

$stmt = $conn->prepare($updateSql);
$stmt->bind_param(
    "sssissi",
    $title,
    $category,
    $description,
    $budget,
    $city,
    $campaignId,
    $promoterId
);

if ($stmt->execute()) {
    header("Location: owner_dashboard.php?updated=1");
    exit;
} else {
    die("Failed to update campaign");
}

$stmt->close();
$conn->close();
