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

$title       = trim($_POST['title'] ?? '');
$category    = trim($_POST['category'] ?? '');
$description = trim($_POST['description'] ?? '');
$budget      = $_POST['budget'] ?? 0;
$city        = trim($_POST['city'] ?? '');

if (!$title || !$category || !$description || !$budget || !$city) {
    die("All fields are required!");
}

$ownerId = $_SESSION['UserId'];

$sql = "INSERT INTO campaigns 
        (OwnerId, Title, Category, Description, Budget, City)
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "isssds",
    $ownerId,
    $title,
    $category,
    $description,
    $budget,
    $city
);
$allowedCities = ['Ahmedabad','Surat','Vadodara','Rajkot','Gandhinagar'];

if (!in_array($city, $allowedCities)) {
    die("Invalid city selected");
}

$stmt->execute();

$stmt->close();
$conn->close();

header("Location: owner_dashboard.php");
exit;
