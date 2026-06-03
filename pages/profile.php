<?php
session_start();

include '../includes/db.php';
include '../includes/auth.php';

// Prevents access if user is not logged in
requireLogin();

// Header Variables
$pageTitle = "My Profile";

$pageSubtitle = "Manage your account information";

$heroClass = "profile-hero";

// Includes reusable header
include '../includes/header.php';

$user_id = $_SESSION['user_id'];

$success = "";
$error = "";

// Gets user information from database
$sql = "SELECT name, email, profile_pic FROM users WHERE id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $user_id);

$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();


// Handles profile picture upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['profile_pic'])) {

    // Upload folder path
    $uploadDir = "../uploads/profiles/";

    // Creates folder if it doesn't exist
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // File information
    $fileName = $_FILES['profile_pic']['name'];

    $fileTmp = $_FILES['profile_pic']['tmp_name'];

    $fileSize = $_FILES['profile_pic']['size'];

    // Gets file extension
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // Allowed image types
    $allowedTypes = ['jpg', 'jpeg', 'png', 'webp'];

    // Validates image type
    if (!in_array($fileExt, $allowedTypes)) {

        $error = "Only JPG, JPEG, PNG, and WEBP files are allowed.";

    }

    // Validates image size
    elseif ($fileSize > 2 * 1024 * 1024) {

        $error = "Image size must not exceed 2MB.";

    }

    else {

        // Creates unique image name
        $newFileName = "profile_" . $user_id . "_" . time() . "." . $fileExt;

        // Final upload path
        $targetPath = $uploadDir . $newFileName;

        // Uploads image
        if (move_uploaded_file($fileTmp, $targetPath)) {

            // Saves image path into database
            $updateSql = "UPDATE users SET profile_pic = ? WHERE id = ?";

            $updateStmt = $conn->prepare($updateSql);

            $updateStmt->bind_param("si", $targetPath, $user_id);

            if ($updateStmt->execute()) {

                $success = "Profile picture updated successfully.";

                // Updates displayed image immediately
                $user['profile_pic'] = $targetPath;

            } else {

                $error = "Failed to update database.";

            }

        } else {

            $error = "Failed to upload image.";

        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>AlUla 360 - Profile</title>

    <link rel="stylesheet" href="../global/main.css">

</head>

<body>

<main id="main-content">

    <div class="form-container">

        <h2>Welcome, <?php echo htmlspecialchars($user['name']); ?></h2>

        <!-- Success Message -->
        <?php if ($success): ?>

            <p class="success-message">

                <?php echo $success; ?>

            </p>

        <?php endif; ?>

        <!-- Error Message -->
        <?php if ($error): ?>

            <p class="error-message">

                <?php echo $error; ?>

            </p>

        <?php endif; ?>

        <div class="profile-box">

            <!-- Displays uploaded profile image -->
            <?php if (!empty($user['profile_pic'])): ?>

                <img src="<?php echo htmlspecialchars($user['profile_pic']); ?>"
                     alt="Profile Picture"
                     class="profile-img">

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
            <form action="" method="POST" enctype="multipart/form-data">

                <label for="profile_pic">

                    Upload Profile Picture:

                </label>

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

<?php include '../includes/footer.php'; ?>

</body>
</html>
