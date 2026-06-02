
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>AlUla 360 - Home</title>
    <link rel="stylesheet" href="../css/main.css">
</head>

<body>
    
<?php
$pageTitle = "AlUla 360";
$pageSubtitle = "A world of revitalisation, heritage, and adventure";
include "../includes/header.php";
?>

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

<?php include "../includes/footer.php"; ?>

</body>
</html>
