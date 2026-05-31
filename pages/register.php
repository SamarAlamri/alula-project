<!-- Name: [Zain Aljifry], ID: [2107808], Section: [DAR], Date: [8 march] | Name: Samar Alamri, ID: 2206831, Section: DAR, Date: 8 march |Name: Talah Faloudah, ID: 2206666, Section: DAR, Date: 8 march -->
<?php
include "../includes/db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $plain_password = $_POST['password'];

    if (empty($name) || empty($email) || empty($plain_password)) {
        $message = "All fields are required.";
    } 
    elseif (strpos($name, ' ') !== false) {
        $message = "Username cannot contain spaces.";
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format.";
    } 
    elseif (strlen($plain_password) < 8) {
        $message = "Password must be at least 8 characters.";
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
            $message = "Email already exists or something went wrong.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Explore AlUla - Sign Up</title>
    <link rel="stylesheet" href="../global/main.css">
</head>
<body>
    <div id="Sign-in">
        <h2>Create an Account</h2>
        <form action="register.php" method="POST" onsubmit="return validateRegisterForm()">
            <p>
                <label for="new-user">Username:</label><br>
                <input type="text" id="new-user" name="name" required>
            </p>
            <p>
                <label for="new-email">Email:</label><br>
                <input type="email" id="new-email" name="email" required>
            </p>
            <p>
                <label for="pass">Password:</label><br>
                <input type="password" id="pass" name="password" required minlength="8">
            </p>
            <p>
                <input type="submit" value="Sign Up">
            </p>
        </form>
        <p>Already have an account? <a href="login.php">SignIn here</a></p>
        
        <?php if (!empty($message)) { ?>
            <p style="color: red; margin-top: 15px; font-weight: bold;"><?php echo $message; ?></p>
        <?php } ?>
    </div>

    <script src="../scripts/main.js"></script>
</body>
</html>