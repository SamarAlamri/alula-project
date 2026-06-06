<!-- Name: [Zain Aljifry], ID: [2107808], Section: [DAR], Date: [8 march] | Name: Samar Alamri, ID: 2206831, Section: DAR, Date: 8 march |Name: Talah Faloudah, ID: 2206666, Section: DAR, Date: 8 march -->

<?php

// Start session to store user login data
session_start();

// Database connection
include "../includes/db.php";

// Message variable for login feedback
$message = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get user input
    $email = trim($_POST['email']);
    $plain_password = $_POST['password'];

    // Validate required fields
    if (empty($email) || empty($plain_password)) {

        $message = "Email and password are required.";

    } 
    // Validate email format
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Invalid email format.";

    } 
    else {

        // Retrieve user information by email
        $sql = "SELECT id, name, email, password, role FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();

        // Check if user exists
        if ($result->num_rows === 1) {

            $user = $result->fetch_assoc();

            // Verify entered password against hashed password
            if (password_verify($plain_password, $user['password'])) {

                // Store user information in session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['role'] = $user['role'];

                // Store last activity time for session timeout
                $_SESSION['last_activity'] = time();

                // Redirect user based on role
                if ($user['role'] === 'admin') {

                    header("Location: ../admin/dashboard.php");
                    exit();

                } else {

                    header("Location: index.php");
                    exit();
                }

            } else {

                // Incorrect password
                $message = "Incorrect email or password.";
            }

        } else {

            // Email not found
            $message = "Incorrect email or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <!-- Page title -->
    <title>Explore AlUla - Login</title>

    <!-- Main stylesheet -->
    <link rel="stylesheet" href="../css/main.css">
</head>
<body>

    <!-- Login form container -->
    <div id="Sign-in">

        <h2>Login</h2>

        <!-- Login form -->
        <form action="login.php" method="POST">

            <!-- Email field -->
            <p>
                <label for="email">Email</label><br>
                <input type="email" id="email" name="email" required>
            </p>

            <!-- Password field -->
            <p>
                <label for="Signin-pass">Password</label><br>
                <input type="password" id="Signin-pass" name="password" required>
            </p>

            <!-- Forgot password link -->
            <div class="forgot-password">
                <a href="forgot_password.php">Forgot Password?</a>
            </div>

            <!-- Login button -->
            <p>
                <input type="submit" value="Login">
            </p>

        </form>

        <!-- Registration link -->
        <p style="text-align: center !important;">
            Don't Have An Account?
            <a href="register.php">SignUp</a>
        </p>

    </div>

    <!-- Display login message -->
    <?php if (!empty($message)) { ?>
        <p><?php echo $message; ?></p>
    <?php } ?>

</body>
</html>