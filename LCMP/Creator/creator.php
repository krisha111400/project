<?php
session_start();

/* 🔐 If creator already logged in → dashboard */
if (
    isset($_SESSION['UserId']) &&
    isset($_SESSION['UserType']) &&
    strtolower($_SESSION['UserType']) === 'creator'
) {
    header("Location: creator_dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Creator | Local Creator Marketing</title>
    <link rel="stylesheet" href="creator.css">
</head>
<body>

<?php include(__DIR__ . "/../Common Files/navbar.php"); ?>

<!-- ================= HERO SECTION ================= -->
<section class="hero">
    <span class="hero-tag">🎥 For Local Content Creators</span>

    <h1>
        Get Paid for Promoting <br>
        <span>Local Businesses</span>
    </h1>

    <p>
        Collaborate with real local brands, run genuine campaigns,
        and turn your influence into consistent income — all within your city.
    </p>

    <div class="hero-buttons">
        <?php if (!isset($_SESSION['UserId'])): ?>
            <a href="../Authentication/Register-page.php" class="btn primary">
                Join as Creator →
            </a>
            <a href="../Authentication/Login-Page.php" class="btn secondary">
                Login as Creator
            </a>
        <?php else: ?>
            <a href="creator_dashboard.php" class="btn primary">
                Go to Dashboard →
            </a>
        <?php endif; ?>
    </div>
</section>

<!-- ================= FEATURES ================= -->
<section class="stats">
    <div class="stat-card">
        <h3>📍 Local Brand Deals</h3>
        <p>
            Work with businesses from your own city
            who want real local reach.
        </p>
    </div>

    <div class="stat-card">
        <h3>💰 Paid Campaigns</h3>
        <p>
            Get paid for posts, reels, videos
            and genuine promotions.
        </p>
    </div>

    <div class="stat-card">
        <h3>🤝 Direct Collaboration</h3>
        <p>
            No agencies — connect directly
            with business owners.
        </p>
    </div>

    <div class="stat-card">
        <h3>📊 Simple Dashboard</h3>
        <p>
            Apply, track campaigns
            and manage everything easily.
        </p>
    </div>
</section>

<!-- ================= PROBLEM → SOLUTION ================= -->
<section class="flow">
    <div class="flow-item">
        <h4>❌ Hard to Find Paid Deals</h4>
        <p>Brands don’t respond or want free promotions.</p>
    </div>

    <div class="flow-item">
        <h4>❌ Fake Brand Requests</h4>
        <p>No clarity on payment or expectations.</p>
    </div>

    <div class="flow-item">
        <h4>❌ No Local Opportunities</h4>
        <p>Most platforms ignore local creators.</p>
    </div>

    <div class="flow-item">
        <h4>✅ Real Local Campaigns</h4>
        <p>Verified businesses, clear budgets & local reach.</p>
    </div>
</section>

<!-- ================= IMPACT SECTION ================= -->
<section class="impact">
    <div class="impact-row">
        <span>LOCAL</span>
        <p>Work with brands from your own city</p>
    </div>

    <div class="impact-row">
        <span>PAID</span>
        <p>Clear budgets and transparent payments</p>
    </div>

    <div class="impact-row">
        <span>TRUSTED</span>
        <p>Verified businesses & genuine campaigns</p>
    </div>

    <div class="impact-row">
        <span>GROWTH</span>
        <p>Build long-term local brand relationships</p>
    </div>
</section>

<!-- ================= FOOTER ================= -->
<footer class="footer">
    © <?= date('Y') ?> Local Creator Marketing Platform
</footer>

</body>
</html>
