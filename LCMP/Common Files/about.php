<?php
session_start();
include("config.php");
$userName = $_SESSION['UserName'] ?? "Guest";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About Us - Local Creator Marketing Platform</title>
  <link rel="stylesheet" href="About1.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" crossorigin="anonymous" />
</head>
<body>

<header>
  <div class="navbar">
    <div class="nav-logo border">
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
      <?php if (isset($_SESSION['UserId'])): ?>
        <span>Welcome, <?php echo htmlspecialchars($_SESSION['UserName']); ?></span> |
        <a href="logout.php">Logout</a>
        <?php if (isset($_SESSION['UserType']) && $_SESSION['UserType'] === 'Admin'): ?>
          | <a href="admin_dashboard.php" style="color: #27ae60; font-weight: bold;">🔙 Admin Panel</a>
        <?php endif; ?>
      <?php else: ?>
        <a href="Login-Page.php">Login</a>
      <?php endif; ?>
    </div>
  </div>
</header>

<aside class="sidebar">
  <h2>Local Creator</h2>
  <ul>
    <li><a href="Home Page.php"><i class="fa-solid fa-house"></i> Home</a></li>
    <li><a href="about.php" class="active"><i class="fa-solid fa-list"></i> About Us</a></li>
    <li><a href="creator_dashboard.php"><i class="fa-solid fa-user"></i> Creators</a></li>
    <li><a href="owner_dashboard.php"><i class="fa-solid fa-briefcase"></i> Businesses</a></li>
    <li><a href="contact.php"><i class="fa-solid fa-envelope"></i> Contact</a></li>
    <li><a href="Login-Page.php"><i class="fa-solid fa-right-to-bracket"></i> Login</a></li>
  </ul>
</aside>

<main class="main-content">
  <header>
    <h1>About Us</h1>
  </header>

  <section class="section">
    <h2>Who We Are</h2>
    <p>
      Local Creator Marketing Platform connects talented creators with businesses
      to collaborate on marketing campaigns. We aim to support local talent and help businesses
      reach the right audience efficiently.
    </p>
  </section>

  <section class="section">
    <h2>Our Team</h2>
    <ul>
      <li>Krishna - Founder & CEO</li>
      <li>Team Member - Lead Developer</li>
      <li>Marketing Head - Marketing & Outreach</li>
    </ul>
  </section>

  <section class="section">
    <h2>Our Mission</h2>
    <p>
      To provide a reliable, user