<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$userType = isset($_SESSION['UserType']) ? strtolower($_SESSION['UserType']) : null;
?>

<link rel="stylesheet" href="../Common Files/navbar.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">

<header>
  <div class="navbar">

    <!-- LOGO -->
    <div class="nav-logo">
      <div class="logo">
        <h4>Creator</h4>
        <h3>Connect</h3>
      </div>
    </div>

    <?php if ($userType === 'admin'): ?>
    <!-- ================= ADMIN NAVBAR ================= -->

      

      <div class="nav-item">
        <i class="fa-solid fa-users"></i>
        <a href="../Admin/admin_creators.php">Creators</a>
      </div>

      <div class="nav-item">
        <i class="fa-solid fa-building"></i>
        <a href="../Admin/admin_business_owners.php">Business Owners</a>
      </div>

      <div class="nav-item">
        <i class="fa-solid fa-bullhorn"></i>
        <a href="../Admin/manage_campaigns.php">Campaigns</a>
      </div>

      <div class="nav-item">
        <i class="fa-solid fa-chart-line"></i>
        <a href="../Admin/admin_dashboard.php">Reports</a>
      </div>

     

      


<div class="nav-item">
  <i class="fa-solid fa-pen-to-square"></i>
  <a href="../Admin/manage_contact.php">Manage Contact Page</a>
</div>






      <div class="nav-item">
        <i class="fa-solid fa-user"></i>
        <a href="../Admin/admin_profile.php">Profile</a>
      </div>

      <div class="nav-item">
        <i class="fa-solid fa-right-from-bracket"></i>
        <a href="../Authentication/Logout.php">Logout</a>
      </div>

    <?php else: ?>
    <!-- ================= EXISTING NAVBAR (UNCHANGED) ================= -->

      <!-- HOME -->
      <div class="nav-item">
        <i class="fa-solid fa-house"></i>
        <a href="../Common Files/Home Page.php">Home</a>
      </div>

      <!-- PROMOTER / CAMPAIGNS -->
      <div class="nav-item">
        <i class="fa-solid fa-bullhorn"></i>

        <?php if (!isset($_SESSION['UserId'])): ?>
          <a href="../Bussiness Owner/promoter.php">Promoter</a>

        <?php elseif ($userType === 'creator'): ?>
          <a href="../Creator/creator_campaigns.php">Campaigns</a>

        <?php elseif ($userType === 'promoter' || $userType === 'business'): ?>
          <a href="../Bussiness Owner/owner_dashboard.php">Business Overview</a>
        <?php endif; ?>
      </div>

      <!-- CREATOR -->
      <div class="nav-item">
        <i class="fa-solid fa-users"></i>

        <?php if (!isset($_SESSION['UserId'])): ?>
          <a href="../Creator/creator.php">Creator</a>

        <?php elseif ($userType === 'promoter' || $userType === 'business'): ?>
          <a href="../Bussiness Owner/creators_list.php">Creators</a>

        <?php elseif ($userType === 'creator'): ?>
          <a href="../Creator/creator_dashboard.php">My Profile</a>
        <?php endif; ?>
      </div>

      <!-- CONTACT -->
      <div class="nav-item">
        <i class="fa-solid fa-address-card"></i>
        <a href="../Common Files/contact.php">Contact</a>
      </div>

      <!-- AUTH -->
      <div class="nav-auth">
        <i class="fa-solid fa-user-lock"></i>

        <?php if (!isset($_SESSION['UserId'])): ?>
          <a href="../Authentication/Login-Page.php">Login</a> |
          <a href="../Authentication/Register-page.php">Register</a>
        <?php else: ?>
          <span>Welcome, <?= htmlspecialchars($_SESSION['UserName']); ?></span> |
          <a href="../Authentication/Logout.php">Logout</a>
        <?php endif; ?>
      </div>

    <?php endif; ?>

  </div>
</header>
