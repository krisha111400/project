<?php
session_start();
require_once __DIR__ . '/../Common Files/config.php';

/* =========================
   GENERATE UNIQUE USERNAME
========================= */
function generateUniqueUsername($email, $conn) {
    $base = strtolower(preg_replace('/[^a-z0-9]/i', '', explode('@', $email)[0]));
    $i = 0;

    $stmt = $conn->prepare("SELECT UserId FROM users_login WHERE UserName=?");

    while (true) {
        $username = $i === 0 ? $base : $base . $i;
        $stmt->bind_param("s", $username);
        $stmt->execute();

        if ($stmt->get_result()->num_rows === 0) {
            return $username;
        }
        $i++;
    }
}

/* =========================
   BASIC REQUEST CHECK
========================= */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = "Invalid request!";
    header("Location: Register-page.php");
    exit;
}

$userType = strtolower(trim($_POST['userType'] ?? ''));
$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm  = $_POST['confirm_password'] ?? '';

if (!$email || !$password || !$confirm || !$userType) {
    $_SESSION['error'] = "All fields are required!";
    header("Location: Register-page.php");
    exit;
}

/* =========================
   PASSWORD VALIDATION
========================= */
if ($password !== $confirm) {
    $_SESSION['error'] = "Passwords do not match!";
    header("Location: Register-page.php");
    exit;
}

if (
    strlen($password) < 8 ||
    !preg_match('/[0-9]/', $password) ||
    !preg_match('/[@$!%*#?&]/', $password)
) {
    $_SESSION['error'] = "Password must be at least 8 chars with number & special character!";
    header("Location: Register-page.php");
    exit;
}

/* =========================
   DUPLICATE EMAIL CHECK
========================= */
$stmt = $conn->prepare(
    "SELECT UserId FROM users_login WHERE Email=? AND UserType=?"
);
$stmt->bind_param("ss", $email, $userType);
$stmt->execute();

if ($stmt->get_result()->num_rows > 0) {
    $_SESSION['error'] = "Email already registered for this role!";
    header("Location: Register-page.php");
    exit;
}


/* =========================
   INSERT INTO users_login
========================= */
$username = generateUniqueUsername($email, $conn);
$hashed   = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare(
    "INSERT INTO users_login (UserType, Email, UserName, Password)
     VALUES (?, ?, ?, ?)"
);
$stmt->bind_param("ssss", $userType, $email, $username, $hashed);
$stmt->execute();

$userId = $stmt->insert_id;

/* =========================
   INSERT PROFILE DATA
========================= */
if ($userType === 'promoter') {

    $stmt = $conn->prepare(
        "INSERT INTO business_owners (user_id, business_name, owner_full_name, phone)
         VALUES (?, ?, ?, ?)"
    );
    $stmt->bind_param(
        "isss",
        $userId,
        $_POST['business_name'],
        $_POST['owner_full_name'],
        $_POST['phone']
    );

} elseif ($userType === 'creator') {

    $stmt = $conn->prepare(
        "INSERT INTO creators (user_id, full_name, phone, city)
         VALUES (?, ?, ?, ?)"
    );
    $stmt->bind_param(
        "isss",
        $userId,
        $_POST['full_name'],
        $_POST['phone'],
        $_POST['city']
    );
}

$stmt->execute();

/* =========================
   AUTO LOGIN
========================= */
$_SESSION['UserId']   = $userId;
$_SESSION['UserName'] = $username;
$_SESSION['UserType'] = $userType;

/* =========================
   REDIRECT
========================= */
if ($userType === 'creator') {
    header("Location: ../Creator/creator_dashboard.php");
} else {
    header("Location: ../Bussiness Owner/owner_dashboard.php");
}
exit;
