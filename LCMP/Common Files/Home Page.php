<?php
session_start();
include("config.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Local Creator Marketing Platform</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="Home Page.css" />
</head>
<body>

<!-- Navbar -->
<?php include("../Common Files/navbar.php"); ?>

<!-- Hero Section -->
<section class="hero">
  <div class="hero-content">

    <span class="badge">Performance-Driven Creator Marketing</span>

    <h1>
      Connect with <br>
      <span class="gradient-text">
        Local Creators That Actually Convert.
      </span>
    </h1>

    <p>
      Our smart matching system helps businesses find verified local creators
      based on real engagement, audience relevance, and campaign performance —
      no guesswork, only results.
    </p>

    <?php if (!isset($_SESSION['UserId'])): ?>
<div class="buttons">
  <a href="/project/LCMP/Authentication/Register-page.php" class="btn primary">
    Find Creators
  </a>
  <a href="/project/LCMP/Authentication/Register-page.php" class="btn secondary">
    Join as Creator
  </a>
</div>
<?php endif; ?>


    <div class="features">
      <span></span>
      <span></span>
    </div>

  </div>
</section>



<!-- Dynamic Campaign List -->
<div class="Campaign-list">
<?php
$sql = "SELECT c.CampaignId, c.Title, u.UserName AS OwnerName, c.Category, c.Budget , c.City
        FROM campaigns c 
        JOIN users_login u ON c.OwnerId = u.UserId 
        ORDER BY c.CampaignId DESC
        LIMIT 2";

$result = $conn->query($sql);

if ($result->num_rows > 0):
while($row = $result->fetch_assoc()):
?>
  <div class="Campaign-box">
    <h3>Title: <?= htmlspecialchars($row['Title']); ?></h3>

    <p><strong>Business Owner:</strong> <?= htmlspecialchars($row['OwnerName']); ?></p>
    <p><strong>Creator Type Needed:</strong> <?= htmlspecialchars($row['Category']); ?></p>
    <p><strong>City:</strong> <?= htmlspecialchars($row['City']); ?></p>
    <p><strong>Budget:</strong> <?= htmlspecialchars($row['Budget']); ?></p>

    <?php if (!isset($_SESSION['UserId'])): ?>

        <!-- ❌ Not logged in -->
        <a href="../Authentication/Register-page.php" class="btn primary">
          Apply For Campaign
        </a>

    <?php elseif (strtolower($_SESSION['UserType']) === 'creator'): ?>

        <!-- ✅ Creator logged in -->
        <a href="../Creator/creator_campaigns.php?campaign_id=<?= $row['CampaignId']; ?>"
           class="btn primary">
          Apply For Campaign
        </a>

    <?php elseif (strtolower($_SESSION['UserType']) === 'promoter'): ?>

        <!-- 🟡 Promoter logged in -->
        <div class="campaign-actions">

          <a href="../Bussiness Owner/edit_campaign.php?id=<?= $row['CampaignId']; ?>"
             class="btn small">
            ✏️ Edit
          </a>

          <a href="../Bussiness Owner/delete_campaign.php?id=<?= $row['CampaignId']; ?>"
             class="btn small danger"
             onclick="return confirm('Are you sure you want to delete this campaign?');">
            🗑 Delete
          </a>

          <a href="../Bussiness Owner/campaign_applicants.php?campaign_id=<?= $row['CampaignId']; ?>"
             class="btn small primary">
            👥 View Applications
          </a>

        </div>

    <?php endif; ?>

  </div>
<?php
endwhile;
else:
  echo "<p>No campaigns found.</p>";
endif;
?>
</div>







<!-- Dynamic Counter Section -->
<?php
$creatorCount = $conn->query("SELECT COUNT(*) as total FROM creators")->fetch_assoc()['total'];
$ownerCount = $conn->query("SELECT COUNT(*) as total FROM business_owners")->fetch_assoc()['total'];
$campaignCount = $conn->query("SELECT COUNT(*) as total FROM campaigns")->fetch_assoc()['total'];
?>
<div class="Counter-section">
  <div class="Counter-box border">
    <div class="Counter-number"><?php echo $creatorCount; ?>+</div>
    <div class="Counter-label"><h3>Creators</h3></div>
  </div>
  <div class="Counter-box">
    <div class="Counter-number"><?php echo $ownerCount; ?>+</div>
    <div class="Counter-label"><h3>Business Owners</h3></div>
  </div>
  <div class="Counter-box">
    <div class="Counter-number"><?php echo $campaignCount; ?>+</div>
    <div class="Counter-label"><h3>Campaigns</h3></div>
  </div>
</div>






<!-- Why LCMP Section -->
<section class="why-lcmp">
  <h2>Why LCMP?</h2>
  <p class="why-subtitle">
    We don’t just list creators — we build meaningful local collaborations.
  </p>

  <div class="why-cards">

    <div class="why-card">
      <div class="icon blue">
        <i class="fa-solid fa-location-dot"></i>
      </div>
      <h3>Smart Local Matching</h3>
      <p>
        Connect with creators based on location, category, and audience relevance
        for authentic local campaigns.
      </p>
    </div>

    <div class="why-card">
      <div class="icon purple">
        <i class="fa-solid fa-bullhorn"></i>
      </div>
      <h3>Easy Campaign Management</h3>
      <p>
        Post campaigns, review applications, and manage collaborations
        from a single dashboard.
      </p>
    </div>

    <div class="why-card">
      <div class="icon pink">
        <i class="fa-solid fa-shield-halved"></i>
      </div>
      <h3>Verified Profiles</h3>
      <p>
        All creators and businesses are verified to ensure genuine reach,
        real engagement, and trusted partnerships.
      </p>
    </div>

  </div>
</section>





<section class="creator-highlight">
  <div class="creator-layout">

    <!-- LEFT CARD -->
    <div class="creator-card">
      <span class="creator-tag">INFLUENCERS</span>
      <h2 class="creator-title">
        How Local Creators<br>
        Drive Local Brand<br>
        Success
      </h2>
    </div>

    <!-- RIGHT CONTENT -->
    <div class="creator-description">
      <div class="creator-arrow">↳</div>
      <p class="creator-text">
        “Our local creator marketing platform connects creators with nearby brands,
        making collaborations simple, authentic, and profitable — while helping
        businesses grow through trusted local voices.”
      </p>
    </div>

  </div>
</section>






<!-- Grow Your Brand with Local Creators Section -->
  <section class="grow-brand-local-creators">
    <div class="grow-brand-content">

      <h1 class="grow-brand-heading">
        Grow Your Brand with <span>Local Creators</span>
      </h1>

      <p class="grow-brand-description">
        Connect with trusted creators from your city and turn local influence
        into real business growth.
      </p>

      <?php if (!isset($_SESSION['UserId'])): ?>
<div class="grow-brand-buttons">
  <a href="/project/LCMP/Authentication/Register-page.php"
     class="grow-brand-btn primary">
     Create Free Account
  </a>
</div>
<?php endif; ?>


    </div>
  </section>









<!-- Footer -->



</body>
</html>

<?php include("footer.php"); ?>