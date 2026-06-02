<!-- Name: [Zain Aljifry], ID: [2107808], Section: [DAR], Date: [8 march] | Name: Samar Alamri, ID: 2206831, Section: DAR, Date: 8 march |Name: Talah Faloudah, ID: 2206666, Section: DAR, Date: 8 march -->

<?php
session_start();
include "../includes/db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);
    $plain_password = $_POST['password'];

    if (empty($email) || empty($plain_password)) {
        $message = "Email and password are required.";
    } 
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format.";
    } 
    else {
        $sql = "SELECT id, name, email, password, role FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($plain_password, $user['password'])) {

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['role'] = $user['role'];

                if ($user['role'] === 'admin') {
                    header("Location: ../admin/dashboard.php");
                    exit();
                } else {
                    header("Location: index.php");
                    exit();
                }

            } else {
                $message = "Incorrect email or password.";
            }
        } else {
            $message = "Incorrect email or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Explore AlUla - Login</title>
    <link rel="stylesheet" href="../css/main.css">
</head>
<body>
    <div id="Sign-in">
        <h2>Login</h2>
        <form action="login.php" method="POST">
            <p>
                <label for="email">Email</label><br>
                <input type="email" id="email" name="email" required>
            </p>

            <p>
                <label for="Signin-pass">Password</label><br>
                <input type="password" id="Signin-pass" name="password" required>
            </p>
            <div class="forgot-password">
              <a href="forgot_password.php">Forgot Password?</a>
            </div>
            <p>
            
                <input type="submit" value="Login">
                
            </p>
        </form>
        <p style="text-align: center !important;">Don't Have An Account? <a href="register.php">Signup</a></p>
    </div>

    <?php if (!empty($message)) { ?>
        <p><?php echo $message; ?></p>
    <?php } ?>

</body>
</html>