<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config.php';

if (!isset($_SESSION['UserId'])) {
    header("Location: ../Authentication/Login-Page.php");
    exit;
}

$stmt = $conn->prepare("
    SELECT Status FROM users_login 
    WHERE UserId = ?
");
$stmt->bind_param("i", $_SESSION['UserId']);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 1) {
    if ($res->fetch_assoc()['Status'] === 'blocked') {
        session_destroy();
        header("Location: ../Authentication/Login-Page.php?blocked=1");
        exit;
    }
}
