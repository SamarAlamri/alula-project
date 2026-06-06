<?php
// Include authentication functions, such as isLoggedIn()
include "../includes/auth.php";

// Include database connection
include "../includes/db.php";

// Retrieve only upcoming tours from the database
// CURDATE() means today's date
$sql = "SELECT * 
        FROM tours
        WHERE tour_date >= CURDATE()
        ORDER BY tour_date ASC";

// Execute the SQL query
$result = $conn->query($sql);
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
// Set page header information used by header.php
$pageTitle = "Explore AlUla";
$pageSubtitle = "Experience the Wonders of AlUla";
$heroClass = "services-hero";

// Include website header
include "../includes/header.php";
?>

<div id="main-content">

    <!-- Main page heading -->
    <h2>Explore AlUla Your Way</h2>

    <!-- Page introduction -->
    <p>
        Discover AlUla through carefully designed themed Tour that combine multiple experiences into one seamless trip.
        Each Tour follows a planned schedule of activities, allowing you to explore the destination without the stress of planning.
        Choose between full-day or half-day trips based on your time and interests.
    </p>

    <!-- Tour search and filter form -->
    <form id="tourSearchForm">

        <!-- Search tours by title or keyword -->
        <input type="text" id="searchInput" name="search" placeholder="Search tours...">

        <!-- Filter tours by date -->
        <input type="date" id="dateFilter" class="filter-input" name="tour_date">

        <!-- Filter tours by category -->
        <select id="categoryFilter" name="category">
            <option value="">All Categories</option>
            <option value="Heritage">Heritage</option>
            <option value="Nature">Nature</option>
            <option value="Adventure">Adventure</option>
        </select>

        <!-- Filter tours by duration -->
        <select id="durationFilter" name="duration">
            <option value="">All Durations</option>
            <option value="Half Day">Half Day</option>
            <option value="Full Day">Full Day</option>
        </select>

    </form>

    <!-- Container where tour cards are displayed -->
    <div class="services-grid" id="toursContainer">

        <?php
        // Check if there are tours available
        if ($result->num_rows > 0) {

            // Loop through each tour record
            while ($tour = $result->fetch_assoc()) {

                // Start tour card
                echo "<section class='service-card'>";

                // Display tour title
                echo "<h3>" . htmlspecialchars($tour['title']) . "</h3>";

                // Display tour description
                echo "<p>" . htmlspecialchars($tour['description']) . "</p>";

                // Display tour category
                echo "<p><strong>Category:</strong> " . htmlspecialchars($tour['category']) . "</p>";

                // Display tour duration
                echo "<p><strong>Duration:</strong> " . htmlspecialchars($tour['duration']) . "</p>";

                // Display formatted tour date
                echo "<p><strong>Date:</strong> " . date("F j, Y", strtotime($tour['tour_date'])) . "</p>";

                // Display tour price
                echo "<p><strong>Price:</strong> " . htmlspecialchars($tour['price']) . " SAR</p>";

                // If user is logged in, allow booking
                if (isLoggedIn()) {

                    echo "<button type='button' class='select-btn book-tour-btn'
                            data-tour-id='" . $tour['id'] . "'>
                            Book Tour
                        </button>";

                } else {

                    // If user is not logged in, show login-required button
                    echo "<button type='button' class='select-btn login-required-btn'>
                            Book Tour
                        </button>";
                }

                // End tour card
                echo "</section>";
            }

        } else {

            // Message shown if no tours exist
            echo "<p>No tours found.</p>";
        }
        ?>

    </div>

</div>

<!-- Confirmation message shown after selecting a tour -->
<div id="confirmation-message">
    ✅ Tour selected successfully!
</div>

<!-- Booking policy modal -->
<div id="bookingPolicyModal" class="policy-modal">

    <div class="policy-box">

        <!-- Modal title -->
        <h2>Booking Guidelines</h2>

        <!-- Modal description -->
        <p>Please read before confirming your booking:</p>

        <!-- Booking and cancellation rules -->
        <ul>
            <li>Full refund if cancelled 7 or more days before the tour.</li>
            <li>50% refund if cancelled 3 to 6 days before the tour.</li>
            <li>No refund if cancelled 2 days or less before the tour.</li>
            <li>Tour schedules may change due to weather or safety reasons.</li>
        </ul>

        <!-- Confirm booking button -->
        <button id="confirmBookingBtn" class="select-btn">Accept & Continue</button>

        <!-- Cancel modal button -->
        <button id="cancelBookingBtn" class="select-btn">Cancel</button>

    </div>

</div>

<!-- Include website footer -->
<?php include "../includes/footer.php"; ?>

<!-- Link to main JavaScript file -->
<script src="../scripts/main.js"></script>

</body>
</html>