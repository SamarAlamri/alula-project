<?php
include "../includes/db.php";

$message = "";
$password_error = "";

$token = $_GET["token"] ?? $_POST["token"] ?? "";

if (empty($token)) {
    $message = "Invalid reset link.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($token)) {

    $new_password = $_POST["new_password"];
    $confirm_password = $_POST["confirm_password"];

    if (empty($new_password) || empty($confirm_password)) {
        $message = "All fields are required.";
    } elseif ($new_password !== $confirm_password) {
        $password_error = "Passwords do not match.";
    } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $new_password)) {
        $message = "Password must be at least 8 characters and include uppercase, lowercase, numbers, and special characters.";
    } else {

    
        $current_time = date("Y-m-d H:i:s");

        $stmt = $conn->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_expires > ?");
        $stmt->bind_param("ss", $token, $current_time);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {

            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            $update = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE reset_token = ?");
            $update->bind_param("ss", $hashed_password, $token);

            if ($update->execute()) {
                $message = "Password has been reset successfully. You can now login.";
            } else {
                $message = "Something went wrong.";
            }

        } else {
            $message = "Invalid or expired reset link.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="../css/main.css">
</head>
<body>

    <div id="Sign-in">
        <h2>Reset Password</h2>

        <form action="reset_password.php" method="POST">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

            <p>
                <label for="new_password">New Password</label><br>
                <input type="password" id="new_password" name="new_password" required>
            </p>

            <p>
                <label for="confirm_password">Confirm Password</label><br>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </p>

            <?php if (!empty($password_error)) { ?>
                <p class="password-error"><?php echo $password_error; ?></p>
            <?php } ?>

            <p>
                <input type="submit" value="Reset Password">
            </p>
        </form>

        <p style="text-align: center !important;">
            <a href="login.php">Back to Login</a>
        </p>

        <?php if (!empty($message)) { ?>
            <p style="text-align: center !important;"><?php echo $message; ?></p>
        <?php } ?>
    </div>

</body>
</html>