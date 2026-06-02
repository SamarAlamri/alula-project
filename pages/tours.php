<?php
session_start();
include "../includes/db.php";

$search = isset($_GET['search']) ? trim($_GET['search']) : "";
$category = isset($_GET['category']) ? trim($_GET['category']) : "";
$duration = isset($_GET['duration']) ? trim($_GET['duration']) : "";

$sql = "SELECT * FROM tours WHERE 1";

if (!empty($search)) {
    $sql .= " AND title LIKE '%" . $conn->real_escape_string($search) . "%'";
}

if (!empty($category)) {
    $sql .= " AND category = '" . $conn->real_escape_string($category) . "'";
}

if (!empty($duration)) {
    $sql .= " AND duration = '" . $conn->real_escape_string($duration) . "'";
}

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
<link rel="stylesheet" href="../global/main.css">

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
            echo "<p><strong>Price:</strong> " . htmlspecialchars($tour['price']) . " SAR</p>";
            echo "<a href='mytour.php?tour_id=" . $tour['id'] . "'><button class='select-btn'>Book Tour</button></a>";
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

<script>
    const buttons = document.querySelectorAll('.select-btn');
    const message = document.getElementById('confirmation-message');

    buttons.forEach(button => {
        button.addEventListener('click', () => {
            message.classList.add('show');

            // hide after 2 seconds
            setTimeout(() => {
                message.classList.remove('show');
            }, 2000);
        });
    });

    document.getElementById('tourSearchForm').addEventListener('submit', function(e) {
    e.preventDefault(); // Stops form from submitting and refreshing the page
    });
    
</script>


<?php include "../includes/footer.php"; ?>

<script src="../scripts/main.js"></script>

</body>
</html>
