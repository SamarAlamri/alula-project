<!-- Name: [Zain Aljifry], ID: [2107808], Section: [DAR], Date: [8 march] | Name: Samar Alamri, ID: 2206831, Section: DAR, Date: 8 march |Name: Talah Faloudah, ID: 2206666, Section: DAR, Date: 8 march -->
<?php

// Includes the database connection file
include '../includes/db.php';
include "../includes/auth.php";
include "../includes/session_timeout.php";
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
    if (!isLoggedIn()) {
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

   // Executes the query and checks if feedback was saved successfully
if ($stmt->execute()) {

    // Create PHPMailer object
    $mail = new PHPMailer(true);

    try {

        // Configure SMTP settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;

        // Gmail account credentials
        $mail->Username = 'alula360project@gmail.com';
        $mail->Password = 'qsmnagcqxgavgnmn';

        // Email encryption and port
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Sender and recipient information
        $mail->setFrom('alula360project@gmail.com', 'AlUla 360');
        $mail->addAddress($email, $name);

        // Email content settings
        $mail->isHTML(true);
        $mail->Subject = 'AlUla 360 Feedback Confirmation';

        // Confirmation email body
        $mail->Body = "
    <div style='font-family: Arial, sans-serif; line-height: 1.7; color: #4a3a33;'>

        <h2 style='color:#7a3b1d;'>Thank You for Your Feedback!</h2>

        <p>Dear <strong>$name</strong>,</p>

        <p>
            Thank you for sharing your experience with AlUla 360.
            Your feedback is valuable to us and helps improve our platform and user experience.
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

        // Send confirmation email
        $mail->send();

        $success = "Feedback submitted successfully. Confirmation email sent.";

    } catch (Exception $e) {

        // Feedback saved but email failed
        $success = "Feedback saved, but email could not be sent.";
    }

} else {

    // Database insertion failed
    $error = "Something went wrong. Please try again.";
}