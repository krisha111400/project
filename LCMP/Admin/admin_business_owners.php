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
        WHERE UserId = $uid AND UserType='promoter'
    ");

    header("Location: admin_business_owners.php");
    exit;
}

/* =========================
   SEARCH
========================= */
$search = trim($_GET['search'] ?? '');

$sql = "
SELECT 
    u.UserId,
    u.UserName,
    u.Email,
    u.Status,
    b.phone,
    b.business_name
FROM users_login u
LEFT JOIN business_owners b ON u.UserId = b.user_id
WHERE u.UserType='promoter'
";

if ($search !== '') {
    $safe = $conn->real_escape_string($search);
    $sql .= " AND (
        u.UserName LIKE '%$safe%' OR
        u.Email LIKE '%$safe%' OR
        b.phone LIKE '%$safe%' OR
        b.business_name LIKE '%$safe%'
    )";
}

$result = $conn->query($sql);

/* =========================
   EXPORT CSV
========================= */
if (isset($_GET['export'])) {

    header("Content-Type: text/csv");
    header("Content-Disposition: attachment; filename=business_owners.csv");

    $out = fopen("php://output", "w");
    fputcsv($out, ['ID','Username','Email','Business Name','Phone','Status']);

    $exp = $conn->query($sql);
    while ($row = $exp->fetch_assoc()) {
        fputcsv($out, [
            $row['UserId'],
            $row['UserName'],
            $row['Email'],
            $row['business_name'],
            $row['phone'],
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
    <title>Admin | Business Owners</title>

    <link rel="stylesheet" href="admin_business_owners.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">
</head>
<body>

<?php include(__DIR__ . '/../Common Files/navbar.php'); ?>

<div class="main-content">
<h1>Business Owners Management</h1>

<form method="get">
    <input type="text" name="search"
           placeholder="Search name, email, business, phone"
           value="<?= htmlspecialchars($search) ?>">

    <button type="submit">Search</button>
    <a href="?export=1" class="btn">Export CSV</a>
</form>

<table>
<tr>
    <th>ID</th>
    <th>Username</th>
    <th>Email</th>
    <th>Business Name</th>
    <th>Phone</th>
    <th>Status</th>
    <th>Actions</th>
</tr>

<?php while($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= $row['UserId'] ?></td>
    <td><?= htmlspecialchars($row['UserName']) ?></td>
    <td><?= htmlspecialchars($row['Email']) ?></td>
    <td><?= htmlspecialchars($row['business_name'] ?? '—') ?></td>
    <td><?= htmlspecialchars($row['phone'] ?? '—') ?></td>

    <td>
        <span class="badge <?= $row['Status'] ?>">
            <?= ucfirst($row['Status']) ?>
        </span>
    </td>

    <td>
        <a class="view"
           href="admin_view_business_owner.php?id=<?= $row['UserId'] ?>">
            👁 View
        </a>
        |
        <a class="toggle"
           href="?toggle=<?= $row['UserId'] ?>"
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
