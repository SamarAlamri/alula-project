<!-- Name: [Zain Aljifry], ID: [2107808], Section: [DAR], Date: [8 march] | Name: Samar Alamri, ID: 2206831, Section: DAR, Date: 8 march |Name: Talah Faloudah, ID: 2206666, Section: DAR, Date: 8 march -->

<?php
include "../includes/auth.php";
requireLogin();

include "../includes/auth.php"; 

$user_id = $_SESSION['user_id'];

if (isset($_GET['tour_id'])) {
    $tour_id = $_GET['tour_id'];

    $checkSql = "SELECT id FROM my_trips WHERE user_id = ? AND tour_id = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("ii", $user_id, $tour_id);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows == 0) {
        $insertSql = "INSERT INTO my_trips (user_id, tour_id) VALUES (?, ?)";
        $insertStmt = $conn->prepare($insertSql);
        $insertStmt->bind_param("ii", $user_id, $tour_id);
        $insertStmt->execute();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Defines the character encoding used for the webpage -->
    <meta charset="UTF-8">

    <!-- Title displayed in the browser tab -->
    <title>AlUla 360 - Tour Schedule</title>

    <!-- Link to the main stylesheet used for screen display -->
    <link rel="stylesheet" href="../css/main.css">

    <!-- Link to a special stylesheet used when the page is printed -->
    <link rel="stylesheet" href="../css/print.css" media="print">

</head>

<body>

 <?php
$pageTitle = "My Tour";
$pageSubtitle = "Your advanture in AlUla";
$heroClass = "schedule-hero";
include "../includes/header.php";
?>


    <!-- Main content area where the tour schedule is displayed -->
    <div id="main-content">

        <?php
        $sql = "SELECT tours.*
                FROM my_trips
                JOIN tours ON my_trips.tour_id = tours.id
                WHERE my_trips.user_id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($tour = $result->fetch_assoc()) {

                echo "<h3>" . htmlspecialchars($tour['title']) . "</h3>";
                echo "<p>" . htmlspecialchars($tour['description']) . "</p>";

                echo "<table border='1' width='100%'>";
                echo "<tr><th>Time</th><th>Activity</th></tr>";

                $scheduleSql = "SELECT * FROM tour_schedule WHERE tour_id = ? ORDER BY time";
                $scheduleStmt = $conn->prepare($scheduleSql);
                $scheduleStmt->bind_param("i", $tour['id']);
                $scheduleStmt->execute();
                $scheduleResult = $scheduleStmt->get_result();

                while ($schedule = $scheduleResult->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($schedule['time']) . "</td>";
                    echo "<td>" . htmlspecialchars($schedule['activity']) . "</td>";
                    echo "</tr>";
                }

                echo "</table><hr>";
            }
        } else {
            echo "<p>Your tour list is empty.</p>";
        }
        ?>

    </div>


 <?php include "../includes/footer.php"; ?>

</body>
</html>