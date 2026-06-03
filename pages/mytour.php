<?php
include "../includes/auth.php";
requireLogin();

include "../includes/db.php"; 
require '../phpmailer/PHPMailer.php';
require '../phpmailer/SMTP.php';
require '../phpmailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$bookingMessage = "";
$bookingError = "";

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

        if ($insertStmt->execute()) {

            $userSql = "SELECT name, email FROM users WHERE id = ?";
            $userStmt = $conn->prepare($userSql);
            $userStmt->bind_param("i", $user_id);
            $userStmt->execute();
            $user = $userStmt->get_result()->fetch_assoc();

            $tourSql = "SELECT title, duration, price FROM tours WHERE id = ?";
            $tourStmt = $conn->prepare($tourSql);
            $tourStmt->bind_param("i", $tour_id);
            $tourStmt->execute();
            $tourData = $tourStmt->get_result()->fetch_assoc();

            try {
                $mail = new PHPMailer(true);

                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'alula360project@gmail.com';
                $mail->Password = 'qsmnagcqxgavgnmn';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('alula360project@gmail.com', 'AlUla 360');
                $mail->addAddress($user['email'], $user['name']);

                $mail->isHTML(true);
                $mail->Subject = 'AlUla 360 Tour Booking Confirmation';

                $mail->Body = "
                    <div style='font-family: Arial, sans-serif; line-height: 1.7; color: #4a3a33;'>
                        <h2 style='color:#7a3b1d;'>Tour Booking Confirmed</h2>

                        <p>Dear <strong>{$user['name']}</strong>,</p>

                        <p>Your tour booking has been successfully confirmed.</p>

                        <p>
                            <strong>Tour:</strong> {$tourData['title']}<br>
                            <strong>Duration:</strong> {$tourData['duration']}<br>
                            <strong>Price:</strong> {$tourData['price']} SAR
                        </p>

                        <p>You can view your itinerary anytime from the My Tour page.</p>

                        <p>Thank you for choosing AlUla 360.</p>
                    </div>
                ";

                $mail->send();

                $bookingMessage = "Tour booked successfully. A confirmation email has been sent to your email.";
            } catch (Exception $e) {
                $bookingError = "Tour booked successfully, but the confirmation email could not be sent.";
            }
        }
    } else {
        $bookingMessage = "This tour is already in your My Tour page.";
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

        <?php if (!empty($bookingMessage)): ?>
            <p class="success-message"><?php echo $bookingMessage; ?></p>
        <?php endif; ?>

        <?php if (!empty($bookingError)): ?>
            <p class="error-message"><?php echo $bookingError; ?></p>
        <?php endif; ?>
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

                echo "<div class='itinerary-container'>";
                echo "<table class='modern-table'>";
                echo "<h2 style='text-align: left !important;'>"
                    . htmlspecialchars($tour['title'])
                    . " (" . htmlspecialchars($tour['duration']) . ")"
                    . "</h2>";

                echo "<thead>";
                echo "<tr>";
                echo "<th>Time</th>";
                echo "<th>Activity</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";

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

                echo "</tbody>";
                echo "</table>";

                echo "<button type='button' class='select-btn cancel-btn cancel-tour-btn'
                data-tour-id='" . $tour['id'] . "'
                data-tour-date='" . $tour['tour_date'] . "'>
                Cancel Tour
                </button>";
                echo "</div>";
            }
        } else {
            echo "<p>Your tour list is empty.</p>";
        }
        ?>


    </div>

    <div id="cancelPolicyModal" class="policy-modal">
        <div class="policy-box">
            <h2>Cancellation Policy</h2>

            <p>Please confirm your cancellation:</p>

            <ul>
                <li>Full refund if cancelled 7 or more days before the tour.</li>
                <li>50% refund if cancelled 3 to 6 days before the tour.</li>
                <li>No refund if cancelled 2 days or less before the tour.</li>
            </ul>

            <p id="refundMessage"></p>

            <button id="confirmCancelBtn" class="select-btn">Confirm Cancellation</button>
            <button id="closeCancelBtn" class="select-btn">Keep Tour</button>
        </div>
    </div>
 <?php include "../includes/footer.php"; ?>

 <script src="../scripts/main.js"></script>

</body>
</html>