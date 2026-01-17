<?php
session_start();

/* 🔐 If promoter already logged in → dashboard */
if (
    isset($_SESSION['UserId']) &&
    isset($_SESSION['UserType']) &&
    strtolower($_SESSION['UserType']) === 'promoter'
) {
    header("Location: owner_dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Promoter | Local Creator Marketing</title>
    <link rel="stylesheet" href="promoter.css">
</head>
<body>

<?php include(__DIR__ . "/../Common Files/navbar.php"); ?>

<!-- ================= HERO SECTION ================= -->
<section class="hero">
    <span class="hero-tag">📍 For Local Businesses & Promoters</span>

    <h1>
        Local Creator Marketing for <br>
        <span>Small Businesses & Brands</span>
    </h1>

    <p>
        Connect with verified creators from your city, launch hyper-local campaigns,
        and turn local influence into real customers — all from one platform.
    </p>

    <div class="hero-buttons">
        <?php if (!isset($_SESSION['UserId'])): ?>
            <a href="../Authentication/Register-page.php" class="btn primary">
                Start Your  Campaign →
            </a>
            <a href="../Authentication/Login-Page.php" class="btn secondary">
                Login as Promoter
            </a>
        <?php else: ?>
            <a href="owner_dashboard.php" class="btn primary">
                Go to Dashboard →
            </a>
        <?php endif; ?>
    </div>
</section>

<!-- ================= FEATURES / STATS ================= -->
<section class="stats">
    <div class="stat-card">
        <h3>📍 City-Focused Promotion</h3>
        <p>
            Work with creators who already influence
            audiences in your own city.
        </p>
    </div>

    <div class="stat-card">
        <h3>✔ Trusted Creator Network</h3>
        <p>
            Every creator profile is reviewed
            to ensure genuine reach and engagement.
        </p>
    </div>

    <div class="stat-card">
        <h3>⚡ Fast Campaign Launch</h3>
        <p>
            Publish campaigns quickly without
            complicated setup or agencies.
        </p>
    </div>

    <div class="stat-card">
        <h3>🤝 Direct Collaboration</h3>
        <p>
            Communicate directly with creators
            and manage everything in one place.
        </p>
    </div>
</section>

<!-- ================= PROBLEM → SOLUTION FLOW ================= -->
<section class="flow">
    <div class="flow-item">
        <h4>❌ Finding Local Influencers is Hard</h4>
        <p>Discover creators who are active in your city.</p>
    </div>

    <div class="flow-item">
        <h4>❌ Ads Feel Fake</h4>
        <p>Creators promote your brand naturally to their audience.</p>
    </div>

    <div class="flow-item">
        <h4>❌ No Campaign Control</h4>
        <p>Track progress, creators, and results from one dashboard.</p>
    </div>

    <div class="flow-item">
        <h4>✅ Real Local Impact</h4>
        <p>More footfall, conversations, and real customers.</p>
    </div>
</section>

<!-- ================= IMPACT SECTION ================= -->
<section class="impact">
    <div class="impact-row">
        <span>LOCAL</span>
        <p>Creators who already influence your city audience</p>
    </div>

    <div class="impact-row">
        <span>TRUSTED</span>
        <p>Verified profiles with real engagement</p>
    </div>

    <div class="impact-row">
        <span>SIMPLE</span>
        <p>Launch and manage campaigns without complexity</p>
    </div>

    <div class="impact-row">
        <span>RESULTS</span>
        <p>More footfall, conversations, and customers</p>
    </div>
</section>

<!-- ================= FOOTER ================= -->
<footer class="footer">
    © <?= date('Y') ?> Local Creator Marketing Platform
</footer>

</body>
</html>
