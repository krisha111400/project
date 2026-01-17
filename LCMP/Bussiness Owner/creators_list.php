<?php
session_start();
require_once __DIR__ . '/../Common Files/config.php';

/* 🔐 Only Business Owner / Promoter allowed */
if (
    !isset($_SESSION['UserId']) ||
    !in_array(strtolower($_SESSION['UserType']), ['promoter', 'business'])
) {
    header("Location: ../Authentication/Login-Page.php");
    exit;
}

/* 🔍 Search */
$search = trim($_GET['search'] ?? '');
$searchLike = "%$search%";

/* 📥 Fetch creators */
$sql = "
SELECT 
    c.user_id,
    c.full_name,
    c.city,
    c.social_handle,
    u.Email
FROM creators c
JOIN users_login u ON c.user_id = u.UserId
WHERE 
    c.full_name LIKE ? 
    OR c.city LIKE ?
    OR u.Email LIKE ?
ORDER BY c.full_name ASC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $searchLike, $searchLike, $searchLike);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Creators List | LCMP</title>
<style>
body{
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    background:#f5f6fa;
    margin:0;
    padding:0;
}

.container{
    max-width:1200px;
    margin:40px auto;
    background:#ffffff;
    padding:30px;
    border-radius:12px;
    box-shadow:0 8px 25px rgba(0,0,0,0.08);
}

h1{
    margin-bottom:20px;
    font-size:24px;
    color:#333;
}

/* 🔍 Search box */
.search-box{
    display:flex;
    gap:10px;
    margin-bottom:25px;
    width: 50%;
}

.search-box input{
    flex:1;
    padding:12px 14px;
    border-radius:8px;
    border:1px solid #dcdcdc;
    font-size:15px;
    outline:none;
}

.search-box input:focus{
    border-color:#007bff;
    box-shadow:0 0 0 2px rgba(0,123,255,.15);
}

.search-box button{
    padding:12px 20px;
    border:none;
    background:#007bff;
    color:#fff;
    border-radius:8px;
    cursor:pointer;
    font-size:15px;
    transition:0.3s;
    
}

.search-box button:hover{
    background:#0056b3;
}

/* 📋 Table */
table{
    width:100%;
    border-collapse:collapse;
    background:#fff;
    border-radius:10px;
    overflow:hidden;
}

th, td{
    padding:14px 16px;
    border-bottom:1px solid #eee;
    font-size:15px;
}

th{
    background:#f1f3f6;
    text-align:left;
    color:#444;
    font-weight:600;
}

tr:hover{
    background:#f9fbff;
}

/* 🎯 Button */
.btn{
    padding:8px 14px;
    background:#28a745;
    color:#fff;
    text-decoration:none;
    border-radius:6px;
    font-size:14px;
    font-weight:500;
    display:inline-block;
    transition:0.3s;
}

.btn:hover{
    background:#218838;
    transform:translateY(-1px);
}

/* ❌ Empty state */
td[colspan]{
    padding:25px;
    font-style:italic;
    color:#777;
    text-align:center;
}
</style>

</head>

<body>

<?php include(__DIR__ . "/../Common Files/navbar.php"); ?>

<div class="container">

<h1>👥 Registered Creators</h1>

<!-- 🔍 SEARCH -->
<form class="search-box" method="GET">
    <input 
        type="text" 
        name="search" 
        placeholder="Search by name, city or email"
        value="<?= htmlspecialchars($search); ?>"
    >
    <button type="submit">Search</button>
</form>

<table>
<thead>
<tr>
    <th>Id</th>
    <th>Name</th>
    <th>Email</th>
    <th>City</th>
    <th>Instagram</th>
    <th>Profile</th>
</tr>
</thead>

<tbody>
<?php if ($result->num_rows > 0): ?>
<?php $i = 1; while ($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= $i++; ?></td>
    <td><?= htmlspecialchars($row['full_name']); ?></td>
    <td><?= htmlspecialchars($row['Email']); ?></td>
    <td><?= htmlspecialchars($row['city']); ?></td>
    <td><?= htmlspecialchars($row['social_handle']); ?></td>
    <td>
        <a 
          href="../Bussiness Owner/view_creator_profile.php?creator_id=<?= $row['user_id']; ?>" 
          class="btn"
          target="_blank"
        >
          View
        </a>
    </td>
</tr>
<?php endwhile; ?>
<?php else: ?>
<tr>
    <td colspan="6" style="text-align:center;">No creators found.</td>
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
