<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$userType = $_SESSION['UserType'] ?? null;
?>
<link rel="stylesheet" href="footers.css">
<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">

<!-- Footer Section -->
<footer class="lcmp-footer">
  <div class="footer-container">

    <!-- Brand Info -->
    <div class="footer-brand">
      <h2>Local Creator Marketing Platform</h2>
      <p>
        Connecting local creators with local businesses for authentic,
        high-performance marketing collaborations.
      </p>
      <div class="footer-social">
        <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
        <a href="#"><i class="fa-brands fa-instagram"></i></a>
        <a href="#"><i class="fa-brands fa-x-twitter"></i></a>
        <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
      </div>
    </div>

    <!-- Quick Links -->
    <div class="footer-links">
      <h3>Quick Links</h3>
      <ul>

        <?php if (!$userType): ?>
          <!-- NOT LOGGED IN -->
          <li><a href="../Common Files/Home Page.php">Home</a></li>
          <li><a href="../Bussiness Owner/promoter.php">Promoter</a></li>
          <li><a href="../Creator/creator.php">Creator</a></li>
                    <li><a href="../Common Files/contact.php">Contact</a></li>

          <li><a href="../Authentication/Login-Page.php">Login</a></li>
          <li><a href="../Authentication/Register-page.php">Register</a></li>

        <?php elseif ($userType === 'promoter' || $userType === 'Business Owner'): ?>
          <!-- PROMOTER -->
          <li><a href="../Common Files/Home Page.php">Home</a></li>
          <li><a href="../Bussiness Owner/owner_dashboard.php">Business Overview</a></li>
          <li><a href="../Bussiness Owner/creators_list.php">Creators</a></li>
          <li><a href="../Common Files/contact.php">Contact</a></li>

        <?php elseif ($userType === 'creator'): ?>
          <!-- CREATOR -->
          <li><a href="../Common Files/Home Page.php">Home</a></li>
          <li><a href="../Common Files/campaigns.php">Campaigns</a></li>
          <li><a href="../Creator/creator_dashboard.php">My Profile</a></li>
          <li><a href="../Common Files/contact.php">Contact</a></li>
        <?php endif; ?>

      </ul>
    </div>

    <!-- Contact -->
    <div class="footer-contact">
      <h3>Contact Us</h3>
      <p><i class="fa-solid fa-location-dot"></i> Deesa, Gujarat, India</p>
      <p><i class="fa-solid fa-envelope"></i> localcreatormarketingplatform@gmail.com</p>
    </div>

  </div>

  <!-- Bottom Bar -->
  <div class="footer-bottom">
    <p>© 2026 Local Creator Marketing Platform. All Rights Reserved.</p>
  </div>
</footer>
