<?php
session_start();
require_once __DIR__ . '/../Common Files/config.php';

if (!isset($_SESSION['UserId']) || strtolower($_SESSION['UserType']) !== 'promoter') {
    header("Location: ../Authentication/Login-Page.php");
    exit;
}

$appId      = (int) ($_POST['application_id'] ?? 0);
$campaignId = (int) ($_POST['campaign_id'] ?? 0);
$status     = $_POST['status'] ?? '';

if ($appId <= 0 || $campaignId <= 0 || !in_array($status, ['approved','rejected'])) {
    die("Invalid request");
}

$stmt = $conn->prepare(
    "UPDATE campaign_applications SET status=? WHERE id=? AND campaign_id=?"
);
$stmt->bind_param("sii", $status, $appId, $campaignId);
$stmt->execute();

header("Location: campaign_applicants.php?campaign_id=$campaignId&updated=1");
exit;
