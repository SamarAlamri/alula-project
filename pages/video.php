<!-- Name: [Zain Aljifry], ID: [2107808], Section: [DAR], Date: [8 march] | Name: Samar Alamri, ID: 2206831, Section: DAR, Date: 8 march |Name: Talah Faloudah, ID: 2206666, Section: DAR, Date: 8 march -->

<?php
$pageTitle = "A Living Museum";
$pageSubtitle = "Experience the Journey in Motion";
$heroClass = "video-hero";

include "../includes/header.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>

<!-- Character encoding -->
<meta charset="UTF-8">

<!-- Page title -->
<title>Explore AlUla - Video</title>

<!-- Linking the CSS file -->
<link rel="stylesheet" href="../global/main.css">

</head>

<body>

<div id="main-content" class="video-page-content">
    <div>
        <h2>A Glimpse into AlUla</h2>
        <p class="center-text">The video highlights the natural beauty, 
            historical monuments, and cultural heritage of AlUla. It showcases locations such as Hegra,
             Elephant Rock, and the historic Old Town.</p>
    </div>

    <div class="video-wrapper">
        <video controls poster="../images/174109.jpg">
            <source src="../videos/video.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>
</div>


<?php include "../includes/footer.php"; ?>


</body>
</html>