<?php
include("../Common Files/config.php");
error_reporting(E_ALL);
ini_set('display_errors', 1);

$adminUserName = "krisha";
$adminEmail    = "krishapatel73596@gmail.com";
$adminPassword = "@krisha123";
$adminType     = "admin"; // ✅ lowercase

$sql = "SELECT * FROM users_login WHERE LOWER(UserType) = 'admin' LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "⚠ Admin already exists!";
} else {

    $hashedPassword = password_hash($adminPassword, PASSWORD_DEFAULT);

    $stmt = $conn->prepare(
        "INSERT INTO users_login (UserName, Email, Password, UserType)
         VALUES (?, ?, ?, ?)"
    );

    $stmt->bind_param("ssss",
        $adminUserName,
        $adminEmail,
        $hashedPassword,
        $adminType
    );

    if ($stmt->execute()) {
        echo "✅ Admin account created successfully!<br><br>";
        echo "👉 Username: {$adminUserName}<br>";
        echo "👉 Email: {$adminEmail}<br>";
        echo "👉 Password: {$adminPassword}<br>";
        echo "👉 UserType: {$adminType}<br>";
    } else {
        echo "❌ Error: " . $stmt->error;
    }
}

$conn->close();
?>
