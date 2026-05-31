<?php
session_start();
if (isset($_GET['logout'])) {

    session_unset();
    session_destroy();

    header("Location: pages/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>AlUla 360 - Home</title>
    <link rel="stylesheet" href="global/main.css">
</head>

<body>

<header id="main-hero">
    <div class="hero-overlay">
        <div class="hero-content">
            <h1>AlUla 360</h1>
            <p>A world of revitalisation, heritage, and adventure</p>

            <nav>
                <a href="index.php">Home</a>
                <a href="about.php">About</a>
                <a href="tours.php">Tours</a>
                <a href="mytour.php">My Tour</a>
                <a href="feedback.php">Feedback</a>

                <?php if (isset($_SESSION['user_id'])): ?>

                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <a href="admin/dashboard.php">Dashboard</a>
                    <?php endif; ?>

                    <a href="index.php?logout=true">Logout</a>

                <?php else: ?>

                    <a href="pages/login.php">Login</a>
                    <a href="pages/register.php">Register</a>

                <?php endif; ?>
            </nav>

        </div>
    </div>
</header>
<!-- ========================= -->
<!-- Main Content Section -->
<!-- ========================= -->

<main id="main-content">

    <!-- Introductory Hero Text Section -->
    <section id="hero-header">

        <div id="hero-text-content">

            <!-- Main section heading -->
            <h2>Explore AlUla</h2>

            <!-- Introduction paragraph -->
            <p>
                AlUla is one of the most breathtaking travel destinations in Saudi Arabia.
                Located in the northwest of the Kingdom, AlUla is famous for its stunning
                desert landscapes, ancient heritage sites, and unique rock formations.
            </p>

            <!-- Additional project description -->
            <p>
                Through AlUla 360, visitors can discover tours, explore cultural landmarks,
                view available activities, and create their own travel experience in an easy
                and interactive way.
            </p>

            <!-- Button that redirects users to the tours page -->
            <a href="tours.php" class="select-btn">Explore Tours</a>

        </div>

    </section>

    <!-- ========================= -->
    <!-- Information Grid Section -->
    <!-- ========================= -->

    <section id="grid-container">

        <!-- Landmarks card -->
        <div class="grid-item">

            <h3>Key Landmarks</h3>

            <ul>
                <li>Hegra (Madain Saleh)</li>
                <li>Elephant Rock</li>
                <li>AlUla Old Town</li>
                <li>Dadan Archaeological Site</li>
            </ul>

        </div>

        <!-- Activities card -->
        <div class="grid-item">

            <h3>Things You Can Do</h3>

            <ol>
                <li>Guided heritage tours</li>
                <li>Archaeological site visits</li>
                <li>Desert hiking</li>
                <li>Cultural festivals</li>
                <li>Stargazing experiences</li>
            </ol>

        </div>

        <!-- Features card -->
        <div class="grid-item">

            <h3>Why AlUla 360?</h3>

            <ul>
                <li>Browse available tours easily</li>
                <li>Search and filter experiences</li>
                <li>Save selected tours</li>
                <li>Send feedback to improve services</li>
            </ul>

        </div>

    </section>

    <!-- ========================= -->
    <!-- Quote Section -->
    <!-- ========================= -->

    <section id="quote-section">

        <!-- Inspirational quote -->
        <blockquote class="fancy-quote">

            "AlUla is where history, culture, and nature come together to create a truly unforgettable travel experience."

        </blockquote>

        <!-- Quote author -->
        <cite class="quote-author">

            — AlUla 360 Project

        </cite>

    </section>

</main>

<!-- ========================= -->
<!-- Footer Section -->
<!-- ========================= -->

<footer>

    <!-- Copyright text -->
    <p>&copy; 2026 AlUla 360 Project</p>

</footer>

</body>
</html>
