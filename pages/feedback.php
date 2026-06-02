<?php
// Starts the session to access logged-in user information
session_start();

// Includes the database connection file
include '../includes/db.php';
require '../phpmailer/PHPMailer.php';
require '../phpmailer/SMTP.php';
require '../phpmailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
// Variables used to display success or error messages
$success = "";
$error = "";

// Checks if the feedback form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Checks if the user is logged in before submitting feedback
    if (!isset($_SESSION['user_id'])) {

        $error = "Please login first before submitting feedback.";

    } else {

        // Gets the logged-in user's ID from the session
        $user_id = $_SESSION['user_id'];

        // Gets and sanitizes form input values
        $name = trim($_POST['username']);
        $email = trim($_POST['useremail']);
        $rating = $_POST['rating'] ?? "Not selected";
        $travel_mode = $_POST['travel_mode'] ?? "Not selected";
        $comments = trim($_POST['comments']);

        // Converts selected activities from array to text
        if (isset($_POST['activity'])) {
            $activities = implode(", ", $_POST['activity']);
        } else {
            $activities = "Not selected";
        }

        // Combines all feedback details into one message
        $message = "Name: " . $name . "\n";
        $message .= "Email: " . $email . "\n";
        $message .= "Rating: " . $rating . "\n";
        $message .= "Activities: " . $activities . "\n";
        $message .= "Travel Mode: " . $travel_mode . "\n";
        $message .= "Comments: " . $comments;

        // Inserts feedback into the database using a prepared statement
        $sql = "INSERT INTO feedback (user_id, message) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $user_id, $message);

    // Executes the query and displays the appropriate message
if ($stmt->execute()) {

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;

        $mail->Username = 'alula360project@gmail.com';
        $mail->Password = 'qsmnagcqxgavgnmn';

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('alula360project@gmail.com', 'AlUla 360');
        $mail->addAddress($email, $name);

        $mail->isHTML(true);
        $mail->Subject = 'AlUla 360 Feedback Confirmation';

        $mail->Body = "
    <div style='font-family: Arial, sans-serif; line-height: 1.7; color: #4a3a33;'>

        <h2 style='color:#7a3b1d;'>Thank You for Your Feedback!</h2>

        <p>Dear <strong>$name</strong>,</p>

        <p>
            Thank you for sharing your experience with AlUla 360.
            Your feedback is valuable to us and helps improve our platform and visitor experience.
        </p>

        <p>
            We appreciate your time and hope you enjoyed exploring the beauty,
            culture, and heritage of AlUla.
        </p>

        <hr>

        <h3 style='color:#7a3b1d;'>Your Submitted Feedback</h3>

        <pre style='background:#fdfaf5; padding:15px; border-radius:8px; border:1px solid #d4a373;'>
$message
        </pre>

        <p>
            Thank you again for supporting AlUla 360.
        </p>

        <br>

        <p>
            Best regards,<br>
            <strong>AlUla 360 Team</strong>
        </p>

    </div>
";

        $mail->send();

        $success = "Feedback submitted successfully. Confirmation email sent.";

    } catch (Exception $e) {
        $success = "Feedback saved, but email could not be sent.";
    }

} else {
    $error = "Something went wrong. Please try again.";
}
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>AlUla 360 - Feedback</title>
    <link rel="stylesheet" href="../global/main.css">
</head>

<body>

 <?php
$pageTitle = "Your Voice";
$pageSubtitle = "Share your AlUla journey with us";
$heroClass = "feedback-hero";
include "../includes/header.php";
?>

<div id="main-content">
    <div class="form-container">

        <h2>We Value Your Feedback</h2>

        <p class="center-text">
            Please tell us about your experience in AlUla.
        </p>

        <?php if ($success): ?>
            <p class="success-message"><?php echo $success; ?></p>
        <?php endif; ?>

        <?php if ($error): ?>
            <p class="error-message"><?php echo $error; ?></p>
        <?php endif; ?>

        <form action="" method="POST">

            <p>
                <label for="username">Full Name:</label><br>
                <input type="text" id="username" name="username" required>
            </p>

            <p>
                <label for="useremail">Email Address:</label><br>
                <input type="email" id="useremail" name="useremail" required>
            </p>

            <p>
                How would you rate your visit? <br>

                <input type="radio" id="good" name="rating" value="Good">
                <label for="good">Good</label>

                <input type="radio" id="average" name="rating" value="Average">
                <label for="average">Average</label>

                <input type="radio" id="poor" name="rating" value="Poor">
                <label for="poor">Poor</label>
            </p>

            <p>
                Which activities did you enjoy? (Select all that apply):<br>

                <input type="checkbox" id="hiking" name="activity[]" value="Hiking">
                <label for="hiking">Hiking</label><br>

                <input type="checkbox" id="tours" name="activity[]" value="Tours">
                <label for="tours">Heritage Tours</label><br>

                <input type="checkbox" id="stargazing" name="activity[]" value="Stargazing">
                <label for="stargazing">Stargazing</label>
            </p>

            <p>
                <label for="travel-mode">Preferred Travel Mode:</label><br>

                <select id="travel-mode" name="travel_mode">
                    <option value="">Select One</option>
                    <option value="Solo Traveler">Solo Traveler</option>
                    <option value="Family Group">Family Group</option>
                    <option value="Friends">Friends</option>
                </select>
            </p>

            <p>
                <label for="comments">Additional Comments:</label><br>
                <textarea id="comments" name="comments" rows="4" cols="40" required></textarea>
            </p>

            <p>
                <input type="submit" value="Submit Feedback">
                <input type="reset" value="Clear Form">
            </p>

        </form>

        <p id="formMessage"></p>

    </div>
</div>

<?php include "../includes/footer.php"; ?>

<script src="scripts/main.js"></script>

</body>
</html>
