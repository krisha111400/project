<?php
session_start();
require_once __DIR__ . '/../Common Files/config.php';

/* =========================
   POST CHECK
========================= */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = "Invalid request!";
    header("Location: Login-Page.php");
    exit;
}

/* =========================
   INPUT
========================= */
$login_id = trim($_POST['login_id'] ?? '');
$password = $_POST['Password'] ?? '';
$userType = strtolower(trim($_POST['UserType'] ?? ''));

if ($login_id === '' || $password === '' || $userType === '') {
    $_SESSION['error'] = "All fields are required!";
    header("Location: Login-Page.php");
    exit;
}

/* =========================
   FETCH USER
========================= */
$sql = "
SELECT UserId, UserName, UserType, Password, Status
FROM users_login
WHERE (UserName = ? OR Email = ?)
AND LOWER(UserType) = ?
LIMIT 1
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $login_id, $login_id, $userType);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    $_SESSION['error'] = "Invalid login credentials!";
    header("Location: Login-Page.php");
    exit;
}

$user = $result->fetch_assoc();

/* =========================
   BLOCKED CHECK 🔒
========================= */
if ($user['Status'] === 'blocked') {
    $_SESSION['error'] = "Your account has been blocked by admin.";
    header("Location: Login-Page.php");
    exit;
}

/* =========================
   PASSWORD VERIFY
========================= */
if (!password_verify($password, $user['Password'])) {
    $_SESSION['error'] = "Invalid password!";
    header("Location: Login-Page.php");
    exit;
}

/* =========================
   LOGIN SUCCESS
========================= */
session_regenerate_id(true);

$_SESSION['UserId']   = $user['UserId'];
$_SESSION['UserName'] = $user['UserName'];
$_SESSION['UserType'] = strtolower($user['UserType']);

/* =========================
   REDIRECT
========================= */
switch ($_SESSION['UserType']) {

    case 'admin':
        header("Location: ../Admin/admin_dashboard.php");
        break;

    case 'creator':
        header("Location: ../Creator/creator_dashboard.php");
        break;

    case 'promoter':
        header("Location: ../Bussiness Owner/owner_dashboard.php");
        break;

    default:
        session_destroy();
        $_SESSION['error'] = "Unauthorized role!";
        header("Location: Login-Page.php");
}

exit;
