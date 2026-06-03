<?php
include "../includes/auth.php";
requireLogin();

include "../includes/db.php";

$response = [
    "success" => false,
    "message" => "",
    "image_path" => ""
];

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!isset($_FILES['profile_pic']) || $_FILES['profile_pic']['error'] != 0) {
        $response["message"] = "Please upload an image.";
        echo json_encode($response);
        exit();
    }

    $uploadDir = "../uploads/profiles/";

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileName = $_FILES['profile_pic']['name'];
    $fileTmp = $_FILES['profile_pic']['tmp_name'];
    $fileSize = $_FILES['profile_pic']['size'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    $allowedTypes = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array($fileExt, $allowedTypes)) {
        $response["message"] = "Only JPG, JPEG, PNG, and WEBP files are allowed.";
        echo json_encode($response);
        exit();
    }

    if ($fileSize > 2 * 1024 * 1024) {
        $response["message"] = "Image size must not exceed 2MB.";
        echo json_encode($response);
        exit();
    }

    $newFileName = "profile_" . $user_id . "_" . time() . "." . $fileExt;
    $targetPath = $uploadDir . $newFileName;

    if (move_uploaded_file($fileTmp, $targetPath)) {

        $dbPath = "../uploads/profiles/" . $newFileName;

        $updateSql = "UPDATE users SET profile_pic = ? WHERE id = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("si", $dbPath, $user_id);

        if ($updateStmt->execute()) {
            $response["success"] = true;
            $response["message"] = "Profile picture updated successfully.";
            $response["image_path"] = $dbPath;
        } else {
            $response["message"] = "Failed to update database.";
        }

    } else {
        $response["message"] = "Failed to upload image.";
    }
}

header("Content-Type: application/json");
echo json_encode($response);
?>