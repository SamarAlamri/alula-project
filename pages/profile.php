<?php
include '../includes/auth.php';
requireLogin();

include '../includes/db.php';
include "../includes/session_timeout.php";
$user_id = $_SESSION['user_id'];

$sql = "SELECT name, email, profile_pic FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$pageTitle = "My Profile";
$pageSubtitle = "Manage your account information";
$heroClass = "profile-hero";
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>AlUla 360 - Profile</title>

    <link rel="stylesheet" href="../css/main.css">

</head>

<body>
 <?php
$pageTitle = "Profile";

include '../includes/header.php';
?>
<main id="main-content">

    <div class="form-container">

        <h2>Welcome, <?php echo htmlspecialchars($user['name']); ?></h2>
        
        <p id="profileMessage"></p>

        <div class="profile-box">

            <!-- Displays uploaded profile image -->
            <?php if (!empty($user['profile_pic'])): ?>

                <img src="<?php echo !empty($user['profile_pic']) ? htmlspecialchars($user['profile_pic']) : '../images/user.png'; ?>"
                    alt="Profile Picture"
                    class="profile-img"
                    id="profileImage">

            <?php else: ?>

                <!-- Default image -->
                <img src="../images/user.png"
                     alt="Default Profile"
                     class="profile-img">

            <?php endif; ?>

            <!-- User Information -->
            <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>

            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            
            <!-- Upload Form -->
            <<form id="profileUploadForm" enctype="multipart/form-data">>

                <label for="profile_pic">Upload Profile Picture:</label>

                <input type="file"
                       id="profile_pic"
                       name="profile_pic"
                       accept=".jpg,.jpeg,.png,.webp"
                       required>

                <input type="submit" value="Update Profile Picture">

            </form>

        </div>

    </div>

</main>
<script src="../scripts/main.js"></script>
<?php include '../includes/footer.php'; ?>

</body>
</html>
