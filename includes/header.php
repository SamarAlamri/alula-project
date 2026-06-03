<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<header id="main-hero" class="slim-header <?php echo $heroClass; ?>">
    <div class="hero-overlay">
        <div class="hero-content">

            <h1><?php echo $pageTitle; ?></h1>
            <p><?php echo $pageSubtitle; ?></p>

            <nav>
                <a href="index.php">Home</a>
                <a href="history.php">History</a>
                <a href="video.php">Video</a>
                <a href="tours.php">Tours</a>
                <a href="mytour.php">My Tour</a>
                <a href="feedback.php">Feedback</a>

                <?php if (isset($_SESSION['user_id'])): ?>

                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                       <a href="../admin/dashboard.php">Dashboard</a>
                    <?php endif; ?>
                   
                   <a href="profile.php">Profile</a>

                    <a href="logout.php">Logout</a>

                <?php else: ?>

                    <a href="login.php">Login</a>

                <?php endif; ?>
            </nav>

        </div>
    </div>
</header>
