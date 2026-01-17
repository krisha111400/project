<?php
// Database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login";

// Connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection silently
if ($conn->connect_error) {
    // Agar connection fail ho, backend me handle karein, user ko na dikhayein
    error_log("Database connection failed: " . $conn->connect_error);
    // Optional: redirect user ya show generic error
    die("Something went wrong."); // user ko simple message
}

// Agar connection successful hai, kuch bhi screen pe nahi dikhana
// echo "Connected successfully";  <-- yeh hata do
?>