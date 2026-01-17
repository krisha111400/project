<?php
session_start();
include("config.php");

$success = "";
$error = "";



/* =========================
   HANDLE CONTACT FORM (USERS)
========================= */
if ($_SERVER["REQUEST_METHOD"] == "POST" && (!isset($_SESSION['UserType']) || $_SESSION['UserType'] !== 'Admin')) {
    $name    = trim($_POST['name']);
    $email   = trim($_POST['email']);
    $message = trim($_POST['message']);

    if ($name && $email && $message) {
        $stmt = $conn->prepare(
            "INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)"
        );
        $stmt->bind_param("sss", $name, $email, $message);

        if ($stmt->execute()) {
            $success = "Thank you! Your message has been sent.";
        } else {
            $error = "Something went wrong. Please try again.";
        }
    } else {
        $error = "All fields are required.";
    }
}


/* =========================
   FETCH BENEFITS (DYNAMIC)
========================= */
$creatorBenefits  = [];
$promoterBenefits = [];

$benefits = $conn->query("SELECT * FROM contact_benefits ORDER BY id DESC");

while ($b = $benefits->fetch_assoc()) {
    if ($b['type'] === 'creator') {
        $creatorBenefits[] = $b;
    } else {
        $promoterBenefits[] = $b;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Contact Us - Local Creator Marketing Platform</title>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css"
crossorigin="anonymous"/>

<link rel="stylesheet" href="contact-page.css">
</head>
<body>

<!-- Navbar -->
<?php include("navbar.php"); ?>

<section class="contact-creator-section">
<div class="contact-container">

<!-- ================= BENEFITS SECTION ================= -->
<section class="benefits-section">

  <div class="benefits-header">
    <span class="contact-tag">WHY LCMP?</span>
    <h2>
      Grow Together with<br>
      <span>Local Creator Marketing Platform</span>
    </h2>
    <p>
      Whether you are a Creator or a Promoter, our platform is built to help
      you collaborate locally, grow faster, and earn smarter.
    </p>
  </div>

  <div class="benefits-grid">

    <!-- CREATOR BENEFITS -->
    <div class="benefit-card creator">
      <h3>🎥 Creators</h3>

      <ul>
        <?php if ($creatorBenefits): ?>
          <?php foreach ($creatorBenefits as $b): ?>
            <li>
              <strong><?= htmlspecialchars($b['title']) ?></strong><br>
              <?= htmlspecialchars($b['description']) ?>
            </li>
          <?php endforeach; ?>
        <?php else: ?>
          <li>No creator benefits added yet.</li>
        <?php endif; ?>
      </ul>

      <?php if (!isset($_SESSION['UserId'])): ?>
        <a href="../Authentication/Register-page.php" class="benefit-btn">
          Join as Creator
        </a>
      <?php endif; ?>
    </div>

    <!-- PROMOTER BENEFITS -->
    <div class="benefit-card promoter">
      <h3>📢 Promoters / Brands</h3>

      <ul>
        <?php if ($promoterBenefits): ?>
          <?php foreach ($promoterBenefits as $b): ?>
            <li>
              <strong><?= htmlspecialchars($b['title']) ?></strong><br>
              <?= htmlspecialchars($b['description']) ?>
            </li>
          <?php endforeach; ?>
        <?php else: ?>
          <li>No promoter benefits added yet.</li>
        <?php endif; ?>
      </ul>

      <?php if (!isset($_SESSION['UserId'])): ?>
        <a href="../Authentication/Register-page.php" class="benefit-btn">
          Join as Promoter
        </a>
      <?php endif; ?>
    </div>

  </div>
</section>

</div>
</section>












<section class="faq">
  <div class="faq-container">
    <h2>Local Creator Marketing Platform – FAQ</h2>
    <p class="faq-intro">
      Here are the most common questions and answers for brands and local creators.
    </p>

    <div class="faq-grid">
      <details class="faq-item" open>
        <summary>What is this platform?</summary>
        <div class="faq-content">
          <p>
            This platform connects local businesses and creators to collaborate on marketing campaigns,
            promotions, and authentic content creation.
          </p>
        </div>
      </details>

      <details class="faq-item">
        <summary>How is it different from e-commerce sites?</summary>
        <div class="faq-content">
          <p>
            E-commerce sites sell products directly. Our platform does not sell products—it enables
            collaborations between brands and creators for campaigns and promotions.
          </p>
        </div>
      </details>

      <details class="faq-item">
        <summary>Who can sign up?</summary>
        <div class="faq-content">
          <ul>
            <li><strong>Brands:</strong> Local shops, startups, agencies, service providers</li>
            <li><strong>Creators:</strong> Influencers, YouTubers, Instagram micro-creators, photographers</li>
          </ul>
        </div>
      </details>

      <details class="faq-item">
        <summary>Is this platform safe and legit?</summary>
        <div class="faq-content">
          <p>
            Yes. We verify both brands and creators, use secure payment gateways, and keep all
            communication within the platform to ensure transparency.
          </p>
        </div>
      </details>

      <details class="faq-item">
        <summary>What services do you offer?</summary>
        <div class="faq-content">
          <ul>
            <li>Creator discovery (filters by niche, city, language, audience size)</li>
            <li>Campaign management (briefs, deliverables, timelines)</li>
            <li>Performance tracking (views, clicks, conversions, footfall)</li>
            <li>Secure payouts and invoicing</li>
            <li>Local-first features (vernacular content, geo-targeted campaigns)</li>
          </ul>
        </div>
      </details>

      <details class="faq-item">
        <summary>How do I start a campaign?</summary>
        <div class="faq-content">
          <ol>
            <li>Create a brand account</li>
            <li>Define campaign goals, deliverables, and budget</li>
            <li>Shortlist creators using filters</li>
            <li>Send briefs and finalize terms</li>
            <li>Track progress and release payments after approval</li>
          </ol>
        </div>
      </details>

      <details class="faq-item">
        <summary>How does payment work?</summary>
        <div class="faq-content">
          <p>
            Payments are milestone-based. Brands fund campaigns securely, and creators receive payouts
            once deliverables are approved. GST-compliant invoices are supported.
          </p>
        </div>
      </details>

      <details class="faq-item">
        <summary>Is my payment and data safe?</summary>
        <div class="faq-content">
          <p>
            Yes. We use encrypted payment gateways and follow strict privacy policies. Always keep
            communication and payments on-platform for safety.
          </p>
        </div>
      </details>

      <details class="faq-item">
        <summary>How do I contact customer support?</summary>
        <div class="faq-content">
          <p>
            You can reach us via the in-app Help Center (chat or ticket system) or email
            support@yourplatform.in. Provide your campaign ID for faster resolution.
          </p>
        </div>
      </details>

      <details class="faq-item">
        <summary>What documentation is required for registration?</summary>
        <div class="faq-content">
          <ul>
            <li><strong>Brands:</strong> Business name, GST (if applicable), website/social links, address, payment details</li>
            <li><strong>Creators:</strong> Government ID (PAN/Aadhaar), social handles, bank details, portfolio links</li>
          </ul>
        </div>
      </details>

      <details class="faq-item">
        <summary>Can I update my information later?</summary>
        <div class="faq-content">
          <p>
            Yes. You can edit your profile, payment details, and GST info anytime in your dashboard.
            Updates may require re-verification.
          </p>
        </div>
      </details>

      <details class="faq-item">
        <summary>Can I run campaigns in local languages?</summary>
        <div class="faq-content">
          <p>
            Absolutely. We support Hindi, Gujarati, Marathi, and other regional languages to help brands
            connect authentically with local audiences.
          </p>
        </div>
      </details>
    </div>
  </div>
</section>






<!-- Footer -->
<footer>
  <p>&copy; <?= date("Y"); ?> LocalCreatorPlatform.com. All Rights Reserved.</p>
</footer>

</body>
</html>