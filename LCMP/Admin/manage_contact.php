<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once __DIR__ . "/../Common Files/config.php";

/* ===========================
   ADMIN AUTH CHECK
=========================== */
if (!isset($_SESSION['UserId']) || $_SESSION['UserType'] !== 'admin') {
    header("Location: ../Authentication/Login-Page.php");
    exit;
}

/* ===========================
   ADD / UPDATE BENEFIT
=========================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $type        = trim($_POST['type']);
    $title       = trim($_POST['title']);
    $description = trim($_POST['description']);
    $id          = $_POST['id'] ?? '';

    if ($type && $title && $description) {

        if ($id) {
            // UPDATE
            $stmt = $conn->prepare(
                "UPDATE contact_benefits 
                 SET type=?, title=?, description=? 
                 WHERE id=?"
            );
            $stmt->bind_param("sssi", $type, $title, $description, $id);
            $stmt->execute();
        } else {
            // INSERT
            $stmt = $conn->prepare(
                "INSERT INTO contact_benefits (type, title, description) 
                 VALUES (?,?,?)"
            );
            $stmt->bind_param("sss", $type, $title, $description);
            $stmt->execute();
        }
    }

    header("Location: manage_contact.php");
    exit;
}

/* ===========================
   DELETE BENEFIT
=========================== */
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];

    $stmt = $conn->prepare("DELETE FROM contact_benefits WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: manage_contact.php");
    exit;
}

/* ===========================
   EDIT FETCH
=========================== */
$edit = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];

    $stmt = $conn->prepare("SELECT * FROM contact_benefits WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $edit = $stmt->get_result()->fetch_assoc();
}

/* ===========================
   FETCH ALL BENEFITS
=========================== */
$benefits = $conn->query(
    "SELECT * FROM contact_benefits 
     ORDER BY type ASC, id DESC"
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Contact Page</title>


</head>

<body>
<link rel="stylesheet" href="manage_contact.css">

<?php include("../Common Files/navbar.php"); ?>

<h2>🛠 Manage Contact Page Benefits</h2>

<div class="form-box">
<form method="post">
    <input type="hidden" name="id" value="<?= $edit['id'] ?? '' ?>">

    <select name="type" required>
        <option value="">Select Type</option>
        <option value="creator" <?= ($edit['type'] ?? '') === 'creator' ? 'selected' : '' ?>>Creator</option>
        <option value="promoter" <?= ($edit['type'] ?? '') === 'promoter' ? 'selected' : '' ?>>Promoter</option>
    </select>

    <input type="text" name="title" placeholder="Benefit Title"
           value="<?= htmlspecialchars($edit['title'] ?? '') ?>" required>

    <textarea name="description" rows="4" placeholder="Benefit Description" required><?= htmlspecialchars($edit['description'] ?? '') ?></textarea>

    <button type="submit">
        <?= $edit ? 'Update Benefit' : 'Add Benefit' ?>
    </button>
</form>
</div>

<table>
<tr>
    <th>Type</th>
    <th>Title</th>
    <th>Description</th>
    <th>Actions</th>
</tr>

<?php while($b = $benefits->fetch_assoc()): ?>
<tr>
    <td><?= ucfirst($b['type']) ?></td>
    <td><?= htmlspecialchars($b['title']) ?></td>
    <td><?= htmlspecialchars($b['description']) ?></td>
    <td class="actions">
<a href="edit_benefit.php?id=<?= $b['id'] ?>">✏ Edit</a>
        <a class="delete" href="?delete=<?= $b['id'] ?>" onclick="return confirm('Delete this benefit?')">🗑 Delete</a>
    </td>
</tr>
<?php endwhile; ?>
</table>

</body>
</html>
