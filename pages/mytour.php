<?php
// Include authentication functions
include "../includes/auth.php";

// Make sure only logged-in users can access this page
requireLogin();

// Include database connection
include "../includes/db.php"; 

// Include session timeout handling
include "../includes/session_timeout.php";

// Include PHPMailer files for sending confirmation emails
require '../phpmailer/PHPMailer.php';
require '../phpmailer/SMTP.php';
require '../phpmailer/Exception.php';

// Import PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Variables used to display booking messages
$bookingMessage = "";
$bookingError = "";

// Get the current logged-in user's ID from the session
$user_id = $_SESSION['user_id'];

// Check if a tour ID was sent in the URL
if (isset($_GET['tour_id'])) {

    // Store selected tour ID
    $tour_id = $_GET['tour_id'];

    // Check if the user already booked this tour
    $checkSql = "SELECT id FROM my_trips WHERE user_id = ? AND tour_id = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("ii", $user_id, $tour_id);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    // If the tour is not already booked, add it to my_trips
    if ($checkResult->num_rows == 0) {

        // Insert new booking into my_trips table
        $insertSql = "INSERT INTO my_trips (user_id, tour_id) VALUES (?, ?)";
        $insertStmt = $conn->prepare($insertSql);
        $insertStmt->bind_param("ii", $user_id, $tour_id);

        // If booking is inserted successfully
        if ($insertStmt->execute()) {

            // Get user information for the confirmation email
            $userSql = "SELECT name, email FROM users WHERE id = ?";
            $userStmt = $conn->prepare($userSql);
            $userStmt->bind_param("i", $user_id);
            $userStmt->execute();
            $user = $userStmt->get_result()->fetch_assoc();

            // Get tour information for the confirmation email
            $tourSql = "SELECT title, duration, price FROM tours WHERE id = ?";
            $tourStmt = $conn->prepare($tourSql);
            $tourStmt->bind_param("i", $tour_id);
            $tourStmt->execute();
            $tourData = $tourStmt->get_result()->fetch_assoc();

            try {
                // Create a new PHPMailer object
                $mail = new PHPMailer(true);

                // SMTP email configuration
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'alula360project@gmail.com';
                $mail->Password = 'qsmnagcqxgavgnmn';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Sender and receiver information
                $mail->setFrom('alula360project@gmail.com', 'AlUla 360');
                $mail->addAddress($user['email'], $user['name']);

                // Email content settings
                $mail->isHTML(true);
                $mail->Subject = 'AlUla 360 Tour Booking Confirmation';

                // Email body
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

                // Send confirmation email
                $mail->send();

                // Success message if booking and email both work
                $bookingMessage = "Tour booked successfully. A confirmation email has been sent to your email.";

            } catch (Exception $e) {

                // Message shown if booking works but email fails
                $bookingError = "Tour booked successfully, but the confirmation email could not be sent.";
            }
        }

    } else {

        // Message shown if user already booked this tour
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
// Set page header information used by header.php
$pageTitle = "My Tour";
$pageSubtitle = "Your advanture in AlUla";
$heroClass = "schedule-hero";

// Include website header
include "../includes/header.php";
?>

<!-- Main content area where the user's booked tours are displayed -->
<div id="main-content">

    <!-- Display booking success message -->
    <?php if (!empty($bookingMessage)): ?>
        <p class="success-message"><?php echo $bookingMessage; ?></p>
    <?php endif; ?>

    <!-- Display booking error message -->
    <?php if (!empty($bookingError)): ?>
        <p class="error-message"><?php echo $bookingError; ?></p>
    <?php endif; ?>

    <?php
    // Retrieve all tours booked by the current user
    $sql = "SELECT tours.*
            FROM my_trips
            JOIN tours ON my_trips.tour_id = tours.id
            WHERE my_trips.user_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user has booked tours
    if ($result->num_rows > 0) {

        // Loop through each booked tour
        while ($tour = $result->fetch_assoc()) {

            // Start itinerary container
            echo "<div class='itinerary-container'>";

            // Start schedule table
            echo "<table class='modern-table'>";

            // Display tour title and duration
            echo "<h2 style='text-align: left !important;'>"
                . htmlspecialchars($tour['title'])
                . " (" . htmlspecialchars($tour['duration']) . ")"
                . "</h2>";

            // Table header
            echo "<thead>";
            echo "<tr>";
            echo "<th>Time</th>";
            echo "<th>Activity</th>";
            echo "</tr>";
            echo "</thead>";

            // Table body
            echo "<tbody>";

            // Retrieve schedule records for the current tour
            $scheduleSql = "SELECT * FROM tour_schedule WHERE tour_id = ? ORDER BY time";
            $scheduleStmt = $conn->prepare($scheduleSql);
            $scheduleStmt->bind_param("i", $tour['id']);
            $scheduleStmt->execute();
            $scheduleResult = $scheduleStmt->get_result();

            // Display each schedule activity
            while ($schedule = $scheduleResult->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($schedule['time']) . "</td>";
                echo "<td>" . htmlspecialchars($schedule['activity']) . "</td>";
                echo "</tr>";
            }

            // Close table body and table
            echo "</tbody>";
            echo "</table>";

            // Cancel tour button with tour ID and date
            echo "<button type='button' class='select-btn cancel-btn cancel-tour-btn'
                    data-tour-id='" . $tour['id'] . "'
                    data-tour-date='" . $tour['tour_date'] . "'>
                    Cancel Tour
                  </button>";

            // End itinerary container
            echo "</div>";
        }

    } else {

        // Message shown if user has no booked tours
        echo "<p class='empty-message'>You have not booked any tours yet.</p>";
    }
    ?>

</div>

<!-- Cancellation policy modal -->
<div id="cancelPolicyModal" class="policy-modal">

    <div class="policy-box">

        <!-- Modal title -->
        <h2>Cancellation Policy</h2>

        <!-- Modal description -->
        <p>Please confirm your cancellation:</p>

        <!-- Cancellation rules -->
        <ul>
            <li>Full refund if cancelled 7 or more days before the tour.</li>
            <li>50% refund if cancelled 3 to 6 days before the tour.</li>
            <li>No refund if cancelled 2 days or less before the tour.</li>
        </ul>

        <!-- Refund message generated by JavaScript -->
        <p id="refundMessage"></p>

        <!-- Confirm cancellation button -->
        <button id="confirmCancelBtn" class="select-btn">Confirm Cancellation</button>

        <!-- Close modal button -->
        <button id="closeCancelBtn" class="select-btn">Keep Tour</button>

    </div>

</div>

<!-- Include website footer -->
<?php include "../includes/footer.php"; ?>

<!-- Link to main JavaScript file -->
<script src="../scripts/main.js"></script>

</body>
</html>