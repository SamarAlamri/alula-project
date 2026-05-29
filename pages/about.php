<?php
// Starts the session
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Character encoding -->
    <meta charset="UTF-8">

    <!-- Browser title -->
    <title>AlUla 360 - About</title>

    <!-- Main stylesheet -->
    <link rel="stylesheet" href="global/main.css">

</head>

<body>

<!-- ========================= -->
<!-- Hero Header Section -->
<!-- ========================= -->

<header id="main-hero" class="history-hero slim-header">

    <div class="hero-overlay">

        <div class="hero-content">

            <!-- Main page title -->
            <h1>About AlUla</h1>

            <!-- Short subtitle -->
            <p>Discover the story behind one of Saudi Arabia’s greatest treasures</p>

            <!-- Navigation Menu -->
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

                    <a href="pages/logout.php">Logout</a>

                <?php else: ?>

                    <a href="pages/login.php">Login</a>

                    <a href="pages/register.php">Register</a>

                <?php endif; ?>

            </nav>

        </div>

    </div>

</header>

<!-- ========================= -->
<!-- Main Content -->
<!-- ========================= -->

<main id="main-content">

    <!-- Intro Section -->
    <section class="history-row">

        <div class="history-text">

            <h3>History & Heritage</h3>

            <p>
                AlUla is one of the most historically rich regions in the Arabian Peninsula.
                For thousands of years, it served as an important center of trade,
                culture, and civilization.
            </p>

            <p>
                Ancient caravan routes once passed through AlUla, connecting southern Arabia
                with the Mediterranean world and creating a unique blend of cultures and traditions.
            </p>

        </div>

        <div class="history-image">

            <img src="images/2560px-Madaan-saleh-1767900822.webp"
                 alt="Hegra Madain Saleh">

        </div>

    </section>

    <!-- Ancient Civilizations -->
    <section class="history-row reverse">

        <div class="history-text">

            <h3>Ancient Civilizations</h3>

            <p>
                Several ancient civilizations lived in AlUla and contributed to its
                historical importance. These civilizations built settlements,
                temples, and monumental tombs that still exist today.
            </p>

            <ul>
                <li>The Dadanite Kingdom</li>
                <li>The Lihyanite Civilization</li>
                <li>The Nabataean Civilization</li>
            </ul>

        </div>

        <div class="history-image">

            <img src="images/2_3nd.webp"
                 alt="Historic Architecture">

        </div>

    </section>

    <!-- Modern AlUla -->
    <section class="history-row">

        <div class="history-text">

            <h3>Modern AlUla</h3>

            <p>
                Today, AlUla has become one of the most important tourism destinations
                in Saudi Arabia. Major efforts are being made to preserve its heritage
                while developing sustainable tourism experiences.
            </p>

            <p>
                International festivals, exhibitions, cultural events,
                and adventure experiences now attract visitors from all around the world.
            </p>

        </div>

        <div class="history-image">

            <img src="images/main_pic.jpg"
                 alt="Modern AlUla">

        </div>

    </section>

    <!-- Quote Section -->
    <section id="quote-section">

        <blockquote class="fancy-quote">

            "The history of AlUla reflects the story of civilizations that shaped the cultural heritage of the Arabian Peninsula."

        </blockquote>

        <cite class="quote-author">

            — Historical Research on Arabian Civilizations

        </cite>

    </section>

</main>

<!-- ========================= -->
<!-- Footer -->
<!-- ========================= -->

<footer>

    <p>&copy; 2026 AlUla 360 Project</p>

</footer>

</body>
</html>
