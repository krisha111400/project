<?php
session_start();
require_once __DIR__ . '/../Common Files/config.php';

/* 🔐 Only Promoter allowed */
if (
    !isset($_SESSION['UserId']) ||
    strtolower($_SESSION['UserType']) !== 'promoter'
) {
    header("Location: ../Authentication/Login-Page.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Campaign | Business Overview</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">

    <style>
        .form-box{
            max-width:600px;
            margin:40px auto;
            background:#fff;
            padding:30px;
            border-radius:12px;
            box-shadow:0 6px 20px rgba(0,0,0,.08);
        }
        .form-box h2{color:#20204a;margin-bottom:20px;}
        .form-box input, .form-box textarea, .form-box select{
            width:100%;
            padding:12px;
            margin-bottom:15px;
            border-radius:8px;
            border:1px solid #ccc;
        }
        .form-box button{
            background:#e5533d;
            color:#fff;
            border:none;
            padding:12px;
            border-radius:8px;
            font-weight:bold;
            cursor:pointer;
        }
    </style>
</head>
<body>

<?php include(__DIR__ . "/../Common Files/navbar.php"); ?>





<div class="form-box">
    <h2>📢 Create New Campaign</h2>

    <form action="create_campaign_process.php" method="POST">
        <input type="text" name="title" placeholder="Campaign Title" required>

        <select name="category" required>
            <option value="">Select Category</option>
            <option value="Food">Food</option>
            <option value="Fashion">Fashion</option>
            <option value="Tech">Tech</option>
            <option value="Local Business">Local Business</option>
        </select>

        <textarea name="description" rows="4" placeholder="Campaign Description" required></textarea>

        <input type="number" name="budget" placeholder="Budget (₹1,000 – ₹10,000)" 
    min="1000" 
    max="10000" 
    step="500"
    required>

<select name="city" required>
    <option value="">Select City</option>
    <option value="Ahmedabad">Ahmedabad</option>
    <option value="Surat">Surat</option>
    <option value="Vadodara">Deesa</option>
    <option value="Rajkot">Palanpur</option>
    <option value="Gandhinagar">Gandhinagar</option>
</select>


        <button type="submit">Create Campaign</button>
    </form>
</div>

</body>
</html>
