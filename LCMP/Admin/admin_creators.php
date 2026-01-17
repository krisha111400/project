<?php
session_start();
require_once __DIR__ . '/../Common Files/config.php';

/* =========================
   ADMIN AUTH CHECK
========================= */
if (!isset($_SESSION['UserId']) || $_SESSION['UserType'] !== 'admin') {
    header("Location: ../Authentication/Login-Page.php");
    exit;
}

/* =========================
   BLOCK / UNBLOCK ACTION
========================= */
if (isset($_GET['toggle'])) {
    $uid = (int)$_GET['toggle'];

    $conn->query("
        UPDATE users_login 
        SET Status = IF(Status='active','blocked','active')
        WHERE UserId = $uid AND UserType='creator'
    ");

    header("Location: admin_creators.php");
    exit;
}

/* =========================
   SEARCH
========================= */
$search = trim($_GET['search'] ?? '');

$sql = "
SELECT 
    u.UserId, u.Email, u.UserName, u.Status,
    c.full_name, c.phone, c.city
FROM users_login u
JOIN creators c ON u.UserId = c.user_id
WHERE u.UserType='creator'
";

if ($search !== '') {
    $safe = $conn->real_escape_string($search);
    $sql .= " AND (
        c.full_name LIKE '%$safe%' OR
        u.Email LIKE '%$safe%' OR
        c.city LIKE '%$safe%'
    )";
}

$result = $conn->query($sql);

/* =========================
   EXPORT CSV
========================= */
if (isset($_GET['export'])) {
    header("Content-Type: text/csv");
    header("Content-Disposition: attachment; filename=creators.csv");

    $out = fopen("php://output", "w");
    fputcsv($out, ['ID','Name','Email','Phone','City','Status']);

    $exp = $conn->query($sql);
    while ($row = $exp->fetch_assoc()) {
        fputcsv($out, [
            $row['UserId'],
            $row['full_name'],
            $row['Email'],
            $row['phone'],
            $row['city'],
            $row['Status']
        ]);
    }
    fclose($out);
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin | Creators</title>
    <link rel="stylesheet" href="admin_creators.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">
</head>
<body>

<?php include(__DIR__ . '/../Common Files/navbar.php'); ?>

<div class="main-content">
<h1>Creators Management</h1>

<form method="get" style="margin-bottom:15px;">
    <input type="text" name="search" placeholder="Search name, email, city"
           value="<?= htmlspecialchars($search) ?>">
    <button type="submit">Search</button>
    <a href="?export=1" class="btn">Export CSV</a>
</form>

<table border="1" width="100%" cellpadding="10">
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>City</th>
    <th>Status</th>
    <th>Actions</th>
</tr>

<?php while($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= $row['UserId'] ?></td>
    <td><?= htmlspecialchars($row['full_name']) ?></td>
    <td><?= htmlspecialchars($row['Email']) ?></td>
    <td><?= htmlspecialchars($row['city']) ?></td>
    <td><?= $row['Status'] ?></td>
    <td>
        <a href="admin_view_creator.php?id=<?= $row['UserId'] ?>">
            👁 View
        </a> |
        <a href="?toggle=<?= $row['UserId'] ?>"
           onclick="return confirm('Are you sure?')">
            <?= $row['Status']==='active' ? '🚫 Block' : '✅ Unblock' ?>
        </a>
    </td>
</tr>
<?php endwhile; ?>

</table>
</div>

</body>
</html>

<?php $conn->close(); ?>
