<?php
// Include database connection
include "../includes/db.php";

// Variables used to store error messages
$message = "";
$email_error = "";

// Check if the form was submitted using POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get and clean user input
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $plain_password = $_POST['password'];

    // Check if any field is empty
    if (empty($name) || empty($email) || empty($plain_password)) {
        $message = "All fields are required.";
    } 

    // Check username length
    elseif (strlen($name) < 4) {
        $message = "Username must be at least 4 characters.";
    }

    // Prevent spaces in username
    elseif (strpos($name, ' ') !== false) {
        $message = "Username cannot contain spaces.";
    }

    // Validate email format
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || 
            !preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email)) {
        $email_error = "Invalid email format.";
    } 

    // Validate password strength
    elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $plain_password)) {
        $message = "Password must be at least 8 characters and include uppercase, lowercase, numbers, and special characters.";
    }

    // If all validation passes
    else {

        // Check if the email already exists in the database
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check_result = $check->get_result();

        // If email is already registered
        if ($check_result->num_rows > 0) {
            $email_error = "Email already exists.";
        } 

        // If email is available, create the account
        else {

            // Hash the password before storing it
            $hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);

            // Insert new user into the database
            $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $name, $email, $hashed_password);

            // If account creation succeeds, redirect user to login page
            if ($stmt->execute()) {
                header("Location: login.php");
                exit();
            } else {

                // General error message if insertion fails
                $message = "Something went wrong.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <!-- Character encoding -->
    <meta charset="UTF-8">

    <!-- Page title shown in browser tab -->
    <title>Explore AlUla - Sign Up</title>

    <!-- Link to main CSS file -->
    <link rel="stylesheet" href="../css/main.css">

</head>

<body>

    <!-- Registration form container -->
    <div id="Sign-in">

        <!-- Page heading -->
        <h2>Create an Account</h2>

        <!-- Registration form -->
        <form action="register.php" method="POST" onsubmit="return validateRegisterForm()">

            <!-- Username field -->
            <p>
                <label for="new-user">Username</label><br>
                <input type="text" id="new-user" name="name" required>
            </p>

            <!-- Email field -->
            <p>
                <label for="new-email">Email</label><br>
                <input type="email" id="new-email" name="email" required>
            </p>

            <!-- Display email-related errors -->
            <?php if (!empty($email_error)) { ?>
                <p class="email-error"><?php echo $email_error; ?></p>
            <?php } ?>

            <!-- Password field -->
            <p>
                <label for="pass">Password</label><br>
                <input type="password" 
                       id="pass" 
                       name="password" 
                       required 
                       pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$">
            </p>

            <!-- Password requirement checklist updated by JavaScript -->
            <ul id="password-requirements" style="list-style: none; padding: 0; margin-top: 8px; font-size: 0.9em;">
                <li id="req-length" style="color: red;">❌ At least 8 characters</li>
                <li id="req-uppercase" style="color: red;">❌ At least one uppercase letter (A-Z)</li>
                <li id="req-lowercase" style="color: red;">❌ At least one lowercase letter (a-z)</li>
                <li id="req-number" style="color: red;">❌ At least one number (0-9)</li>
                <li id="req-special" style="color: red;">❌ At least one special character (@$!%*?&)</li>
            </ul>

            <!-- Submit button -->
            <p>
                <input type="submit" value="Sign Up">
            </p>

        </form>

        <!-- Link to login page for existing users -->
        <p style="text-align: center !important;">
            Already have an account? <a href="login.php">SignIn</a>
        </p>

        <!-- Display general validation or registration errors -->
        <?php if (!empty($message)) { ?>
            <p style="color: red; margin-top: 15px; font-weight: bold;"><?php echo $message; ?></p>
        <?php } ?>

    </div>

    <!-- Link to main JavaScript file -->
    <script src="../scripts/main.js"></script>

</body>
</html>