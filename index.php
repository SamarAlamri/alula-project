<?php
// Starts the session to track logged-in users
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Defines the character encoding -->
    <meta charset="UTF-8">

    <!-- Title shown in the browser tab -->
    <title>AlUla 360 - Home</title>

    <!-- Links the external CSS stylesheet -->
    <link rel="stylesheet" href="global/main.css">

</head>

<body>

<!-- ========================= -->
<!-- Hero Header Section -->
<!-- ========================= -->

<header id="main-hero">

    <!-- Dark overlay placed over the background image -->
    <div class="hero-overlay">

        <!-- Hero content container -->
        <div class="hero-content">

            <!-- Main website title -->
            <h1>AlUla 360</h1>

            <!-- Short description under the title -->
            <p>A world of revitalisation, heritage, and adventure</p>

            <!-- ========================= -->
            <!-- Navigation Menu -->
            <!-- ========================= -->

            <nav>

                <!-- Navigation links -->
                <a href="index.php">Home</a>

                <a href="about.php">About</a>

                <a href="tours.php">Tours</a>

                <a href="mytour.php">My Tour</a>

                <a href="feedback.php">Feedback</a>

                <!-- Checks if the user is logged in -->
                <?php if (isset($_SESSION['user_id'])): ?>

                    <!-- Checks if the logged-in user is an admin -->
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>

                        <!-- Admin dashboard link -->
                        <a href="admin/dashboard.php">Dashboard</a>

                    <?php endif; ?>

                    <!-- Logout link -->
                    <a href="logout.php">Logout</a>

                <?php else: ?>

                    <!-- Login and register links for guests -->
                    <a href="login.php">Login</a>

                    <a href="register.php">Register</a>

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
