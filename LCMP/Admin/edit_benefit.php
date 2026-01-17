<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once __DIR__ . "/../Common Files/config.php";

/* ===========================
   ADMIN AUTH CHECK
=========================== */
if (!isset($_SESSION['UserId']) || strtolower($_SESSION['UserType']) !== 'admin') {
    header("Location: ../Authentication/Login-Page.php");
    exit;
}

/* ===========================
   BENEFIT ID CHECK
=========================== */
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: manage_contact.php");
    exit;
}

$id = (int)$_GET['id'];

/* ===========================
   FETCH BENEFIT DATA
=========================== */
$stmt = $conn->prepare("SELECT * FROM contact_benefits WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    header("Location: manage_contact.php");
    exit;
}

$benefit = $result->fetch_assoc();

/* ===========================
   UPDATE BENEFIT
=========================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $type        = trim($_POST['type']);
    $title       = trim($_POST['title']);
    $description = trim($_POST['description']);

    if ($type && $title && $description) {

        $stmt = $conn->prepare(
            "UPDATE contact_benefits 
             SET type=?, title=?, description=? 
             WHERE id=?"
        );
        $stmt->bind_param("sssi", $type, $title, $description, $id);
        $stmt->execute();

        header("Location: manage_contact.php?updated=1");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Benefit</title>

<link rel="stylesheet" href="manage_contact.css">
</head>

<body>

<?php include("../Common Files/navbar.php"); ?>

<h2>✏ Edit Contact Page Benefit</h2>

<div class="form-box">
<form method="post">

    <label>Benefit Type</label>
    <select name="type" required>
        <option value="creator" <?= $benefit['type']==='creator'?'selected':'' ?>>Creator</option>
        <option value="promoter" <?= $benefit['type']==='promoter'?'selected':'' ?>>Promoter</option>
    </select>

    <label>Benefit Title</label>
    <input type="text" name="title"
           value="<?= htmlspecialchars($benefit['title']) ?>" required>

    <label>Benefit Description</label>
    <textarea name="description" rows="5" required><?= htmlspecialchars($benefit['description']) ?></textarea>

    <div style="display:flex; gap:15px;">
        <button type="submit">💾 Update Benefit</button>
        <a href="manage_contact.php" class="cancel-btn">Cancel</a>
    </div>

</form>
</div>

</body>
</html>
