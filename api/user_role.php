<?php

// Check admin access
include "../includes/auth.php";
requireAdmin();

// Database connection
include "../includes/db.php";

// Return JSON response
header("Content-Type: application/json");

// Response array
$response = [
    "success" => false,
    "message" => ""
];

// Check if request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get user ID and action
    $userId = intval($_POST["id"]);
    $action = $_POST["action"];

    // Set role based on action
    if ($action == "make_admin") {
        $newRole = "admin";

    } elseif ($action == "remove_admin") {
        $newRole = "user";
    }

    // Update role in database
    if (isset($newRole)) {

        $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->bind_param("si", $newRole, $userId);

        // Check if update was successful
        if ($stmt->execute()) {

            $response["success"] = true;
            $response["role"] = ucfirst($newRole);

        } else {

            $response["message"] = "Failed to update role.";
        }

    } else {

        $response["message"] = "Invalid action.";
    }

} else {

    $response["message"] = "Invalid request.";
}

// Return response
echo json_encode($response);

?>