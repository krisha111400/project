<?php
session_start();
include("config.php");

// Admin-only access
if (!isset($_SESSION['UserId']) || $_SESSION['UserType'] !== "Admin") {
    header("Location: Login-Page.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM contact_messages WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

header("Location: admin_contact_messages.php");
exit;
