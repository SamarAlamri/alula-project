<?php

include "../includes/db.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require "../phpmailer/PHPMailer.php";
require "../phpmailer/SMTP.php";
require "../phpmailer/Exception.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);

    if (empty($email)) {

        $message = "Email is required.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Invalid email format.";

    } else {

        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {

            $token = bin2hex(random_bytes(32));
            $expires = date("Y-m-d H:i:s", strtotime("+1 hour"));

            $update = $conn->prepare("
                UPDATE users 
                SET reset_token = ?, reset_token_expiry = ?
                WHERE email = ?
            ");

            $update->bind_param("sss", $token, $expires, $email);
            $update->execute();

            $resetLink = "https://cyan-eagle-451975.hostingersite.com/pages/reset_password.php?token=" . $token;

            $mail = new PHPMailer(true);

            try {

                $mail->isSMTP();
                $mail->Host = "smtp.gmail.com";
                $mail->SMTPAuth = true;

                $mail->Username = "alula360project@gmail.com";
                $mail->Password = "qsmnagcqxgavgnmn";

                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom("alula360project@gmail.com", "AlUla 360");
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = "Reset Your Password";

                $mail->Body = "
                    <h2>Password Reset Request</h2>
                    <p>Click the link below to reset your password:</p>
                    <p><a href='$resetLink'>Reset Password</a></p>
                    <p>This link will expire in 1 hour.</p>
                ";

                $mail->send();

                $message = "Reset link has been sent to your email.";

            } catch (Exception $e) {

                $message = "Mailer Error: " . $mail->ErrorInfo;

            }

        } else {

            $message = "Email not found.";

        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="../css/main.css">
</head>
<body>

<div id="Sign-in">

    <h2>Forgot Password</h2>

    <form action="forgot_password.php" method="POST">

        <p>
            <label for="email">Email</label><br>
            <input type="email" id="email" name="email" required>
        </p>

        <p>
            <input type="submit" value="Send Reset Link">
        </p>

    </form>

    <p style="text-align:center;">
        <a href="login.php">Back to Login</a>
    </p>

    <?php if (!empty($message)) { ?>
        <p style="text-align:center;">
            <?php echo $message; ?>
        </p>
    <?php } ?>

</div>

</body>
</html>