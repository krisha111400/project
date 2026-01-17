<?php
session_start();
include("config.php");

// Sirf Admin ko access allow
if (!isset($_SESSION['UserId']) || $_SESSION['UserType'] !== "Admin") {
    header("Location: Login-Page.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: admin_users.php");
    exit;
}

$id = intval($_GET['id']);

// Admin khud ko delete na kar sake
if ($id == $_SESSION['UserId']) {
    header("Location: admin_users.php");
    exit;
}

$conn->query("DELETE FROM users_login WHERE UserId=$id");
header("Location: admin_users.php");
exit;
?>
