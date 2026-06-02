<?php
include "../includes/db.php";

$message = "";
$email_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $plain_password = $_POST['password'];

    if (empty($name) || empty($email) || empty($plain_password)) {
        $message = "All fields are required.";
    } 
    elseif (strlen($name) < 4) {
        $message = "Username must be at least 4 characters.";
    }
    elseif (strpos($name, ' ') !== false) {
        $message = "Username cannot contain spaces.";
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email)) {
        $email_error = "Invalid email format.";
    } 
    elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $plain_password)) {
        $message = "Password must be at least 8 characters and include uppercase, lowercase, numbers, and special characters.";
    }
    else {
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check_result = $check->get_result();

        if ($check_result->num_rows > 0) {
            $email_error = "Email already exists.";
        } 
        else {
            $hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $name, $email, $hashed_password);

            if ($stmt->execute()) {
                header("Location: login.php");
                exit();
            } else {
                $message = "Something went wrong.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Explore AlUla - Sign Up</title>
    <link rel="stylesheet" href="../css/main.css">
</head>

<body>
    <div id="Sign-in">
        <h2>Create an Account</h2>

        <form action="register.php" method="POST" onsubmit="return validateRegisterForm()">
            <p>
                <label for="new-user">Username</label><br>
                <input type="text" id="new-user" name="name" required>
            </p>

            <p>
                <label for="new-email">Email</label><br>
                <input type="email" id="new-email" name="email" required>
            </p>

            <?php if (!empty($email_error)) { ?>
                <p class="email-error"><?php echo $email_error; ?></p>
            <?php } ?>

            <p>
                <label for="pass">Password</label><br>
                <input type="password" 
                       id="pass" 
                       name="password" 
                       required 
                       pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$">
            </p>

            <ul id="password-requirements" style="list-style: none; padding: 0; margin-top: 8px; font-size: 0.9em;">
                <li id="req-length" style="color: red;">❌ At least 8 characters</li>
                <li id="req-uppercase" style="color: red;">❌ At least one uppercase letter (A-Z)</li>
                <li id="req-lowercase" style="color: red;">❌ At least one lowercase letter (a-z)</li>
                <li id="req-number" style="color: red;">❌ At least one number (0-9)</li>
                <li id="req-special" style="color: red;">❌ At least one special character (@$!%*?&)</li>
            </ul>

            <p>
                <input type="submit" value="Sign Up">
            </p>
        </form>

        <p style="text-align: center !important;">
            Already have an account? <a href="login.php">SignIn</a>
        </p>
        
        <?php if (!empty($message)) { ?>
            <p style="color: red; margin-top: 15px; font-weight: bold;"><?php echo $message; ?></p>
        <?php } ?>
    </div>

    <script src="../scripts/main.js"></script>
</body>
</html>