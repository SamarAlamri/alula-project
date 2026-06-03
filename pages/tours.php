<?php
include "../includes/auth.php";
include "../includes/db.php";

$sql = "SELECT * 
        FROM tours
        WHERE tour_date >= CURDATE()
        ORDER BY tour_date ASC";
?>

<!DOCTYPE html>
<html lang="en">

<head>

<!-- Character encoding -->
<meta charset="UTF-8">

<!-- Page title shown in browser tab -->
<title>Explore AlUla - Themed Journeys</title>

<!-- Link to the main CSS file -->
<link rel="stylesheet" href="../css/main.css">

</head>


<body>

<?php
$pageTitle = "Explore AlUla";
$pageSubtitle = "Experience the Wonders of AlUla";
$heroClass = "services-hero";
include "../includes/header.php";
?>

<div id="main-content">

    <h2>Explore AlUla Your Way</h2>

    <p>
        Discover AlUla through carefully designed themed Tour that combine multiple experiences into one seamless trip.
        Each Tour follows a planned schedule of activities, allowing you to explore the destination without the stress of planning.
        Choose between full-day or half-day trips based on your time and interests.
    </p>

    <form id="tourSearchForm">
        <input type="text" id="searchInput" name="search" placeholder="Search tours...">

        <input type="date" id="dateFilter" class="filter-input" name="tour_date">

        <select id="categoryFilter" name="category">
            <option value="">All Categories</option>
            <option value="Heritage">Heritage</option>
            <option value="Nature">Nature</option>
            <option value="Adventure">Adventure</option>
        </select>

        <select id="durationFilter" name="duration">
            <option value="">All Durations</option>
            <option value="Half Day">Half Day</option>
            <option value="Full Day">Full Day</option>
        </select>

    </form>

    <div class="services-grid" id="toursContainer">
    <?php
    if ($result->num_rows > 0) {
        while ($tour = $result->fetch_assoc()) {
            echo "<section class='service-card'>";
            echo "<h3>" . htmlspecialchars($tour['title']) . "</h3>";
            echo "<p>" . htmlspecialchars($tour['description']) . "</p>";
            echo "<p><strong>Category:</strong> " . htmlspecialchars($tour['category']) . "</p>";
            echo "<p><strong>Duration:</strong> " . htmlspecialchars($tour['duration']) . "</p>";
            echo "<p><strong>Date:</strong> " . date("F j, Y", strtotime($tour['tour_date'])) . "</p>";
            echo "<p><strong>Price:</strong> " . htmlspecialchars($tour['price']) . " SAR</p>";
            if (isLoggedIn()) {

                echo "<button type='button' class='select-btn book-tour-btn'
                        data-tour-id='" . $tour['id'] . "'>
                        Book Tour
                    </button>";

            } else {

                echo "<button type='button' class='select-btn login-required-btn'>
                        Book Tour
                    </button>";
            }
            echo "</section>";
        }
    } else {
        echo "<p>No tours found.</p>";
    }
    ?>
    </div>

</div>

<!-- Confirmation Message for the trip selection -->
<div id="confirmation-message">
    ✅ Tour selected successfully!
</div>

<div id="bookingPolicyModal" class="policy-modal">
    <div class="policy-box">
        <h2>Booking Guidelines</h2>

        <p>Please read before confirming your booking:</p>

        <ul>
            <li>Full refund if cancelled 7 or more days before the tour.</li>
            <li>50% refund if cancelled 3 to 6 days before the tour.</li>
            <li>No refund if cancelled 2 days or less before the tour.</li>
            <li>Tour schedules may change due to weather or safety reasons.</li>
        </ul>

        <button id="confirmBookingBtn" class="select-btn">Accept & Continue</button>
        <button id="cancelBookingBtn" class="select-btn">Cancel</button>
    </div>
</div>

<?php include "../includes/footer.php"; ?>

<script src="../scripts/main.js"></script>

</body>
</html>
