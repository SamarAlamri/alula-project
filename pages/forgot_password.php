<?php

// Database connection
include "../includes/db.php";

// Import PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load PHPMailer files
require "../phpmailer/PHPMailer.php";
require "../phpmailer/SMTP.php";
require "../phpmailer/Exception.php";

// Message variable for user feedback
$message = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get and clean email input
    $email = trim($_POST["email"]);

    // Validate email field
    if (empty($email)) {

        $message = "Email is required.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Invalid email format.";

    } else {

        // Check if email exists in database
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {

            // Generate secure reset token
            $token = bin2hex(random_bytes(32));

            // Set token expiration time (1 hour)
            $expires = date("Y-m-d H:i:s", strtotime("+1 hour"));

            // Save token and expiry date in database
            $update = $conn->prepare("
                UPDATE users 
                SET reset_token = ?, reset_token_expiry = ?
                WHERE email = ?
            ");

            $update->bind_param("sss", $token, $expires, $email);
            $update->execute();

            // Create password reset link
            $resetLink = "https://cyan-eagle-451975.hostingersite.com/pages/reset_password.php?token=" . $token;

            // Create PHPMailer instance
            $mail = new PHPMailer(true);

            try {

                // SMTP configuration
                $mail->isSMTP();
                $mail->Host = "smtp.gmail.com";
                $mail->SMTPAuth = true;

                // Gmail account credentials
                $mail->Username = "alula360project@gmail.com";
                $mail->Password = "qsmnagcqxgavgnmn";

                // Encryption and port settings
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Email sender and recipient
                $mail->setFrom("alula360project@gmail.com", "AlUla 360");
                $mail->addAddress($email);

                // Email content settings
                $mail->isHTML(true);
                $mail->Subject = "Reset Your Password";

                // Email body
                $mail->Body = "
                    <h2>Password Reset Request</h2>
                    <p>Click the link below to reset your password:</p>
                    <p><a href='$resetLink'>Reset Password</a></p>
                    <p>This link will expire in 1 hour.</p>
                ";

                // Send email
                $mail->send();

                $message = "Reset link has been sent to your email.";

            } catch (Exception $e) {

                // Display mail sending error
                $message = "Mailer Error: " . $mail->ErrorInfo;

            }

        } else {

            // Email does not exist
            $message = "Email not found.";

        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <!-- Page title -->
    <title>Forgot Password</title>

    <!-- Main stylesheet -->
    <link rel="stylesheet" href="../css/main.css">
</head>
<body>

<!-- Forgot password container -->
<div id="Sign-in">

    <h2>Forgot Password</h2>

    <!-- Reset password form -->
    <form action="forgot_password.php" method="POST">

        <p>
            <label for="email">Email</label><br>
            <input type="email" id="email" name="email" required>
        </p>

        <p>
            <input type="submit" value="Send Reset Link">
        </p>

    </form>

    <!-- Back to login page -->
    <p style="text-align:center;">
        <a href="login.php">Back to Login</a>
    </p>

    <!-- Display success or error message -->
    <?php if (!empty($message)) { ?>
        <p style="text-align:center;">
            <?php echo $message; ?>
        </p>
    <?php } ?>

</div>

</body>
</html>