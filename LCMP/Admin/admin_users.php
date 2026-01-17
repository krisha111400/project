<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include("config.php");

// Only Admin access
if (!isset($_SESSION['UserId']) || $_SESSION['UserType'] !== "Admin") {
    header("Location: admin_dashboard.php");
    exit;
}

// Fetch all users
$sql = "SELECT UserId, UserName, Email, UserType FROM users_login ORDER BY UserId DESC";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="manage_campaigns.css"> 
    <script>
        function deleteAlert() {
            return confirm("Are you sure you want to delete this user?");
        }
    </script>
</head>
<body>

<!-- Navbar -->
<header>
    <div class="navbar">
        <div class="nav-logo">
            <div class="logo">
                <h4>Creator</h4>
                <h3>Connect</h3>
            </div>
        </div>

        <div class="nav-Home">
            <i class="fa-solid fa-house-chimney"></i>
            <a href="Home Page.php">Home</a>
        </div>

        <div class="nav-Campaigns">
            <i class="fa-solid fa-bullhorn"></i>
            <a href="campaigns.php">Campaigns</a>
        </div>

        <div class="nav-About">
            <i class="fa-solid fa-list"></i>
            <a href="about.php">About Us</a>
        </div>

        <div class="nav-Contact">
            <i class="fa-solid fa-address-card"></i>
            <a href="contact.php">Contact Us</a>
        </div>

        <div class="nav-Login">
            <i class="fa-solid fa-user-lock"></i>
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['UserName']); ?></span> |
            <a href="logout.php">Logout</a>
        </div>
    </div>
</header>

<!-- Sidebar -->
<div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="admin_dashboard.php"><i class="fa-solid fa-grip"></i> Dashboard</a>
    <a href="admin_users.php" class="active"><i class="fa-solid fa-users"></i> Manage Users</a>
    <a href="manage_campaigns.php"><i class="fa-solid fa-bullhorn"></i> Manage Campaigns</a>
      <!--  <a href="admin_contact_messages.php"><i class="fa-solid fa-envelope"></i> Contact Messages</a>-->

    <a href="logout.php" class="logout">🚪 Logout</a>
</div>

<!-- Main Content -->
<div class="main-content">
    <h1>Manage Users</h1>
    <p>Here you can edit or delete users.</p>

    <table class="campaign-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>UserName</th>
                <th>Email</th>
                <th>User Type</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['UserId']; ?></td>
                        <td><?php echo htmlspecialchars($row['UserName']); ?></td>
                        <td><?php echo htmlspecialchars($row['Email']); ?></td>
                        <td><?php echo htmlspecialchars($row['UserType']); ?></td>
                        <td>
                            <a href="edit_user.php?id=<?php echo $row['UserId']; ?>" class="btn edit-btn">
                                <i class="fa-solid fa-pen-to-square"></i> Edit
                            </a>
                            <a href="delete_user.php?id=<?php echo $row['UserId']; ?>" class="btn delete-btn" onclick="return deleteAlert();">
                                <i class="fa-solid fa-trash"></i> Delete
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align:center;">No users found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
