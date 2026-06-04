<?php

// Check if the current user is an admin
include "../includes/auth.php";
requireAdmin();

// Database connection
include "../includes/db.php";

// Response array
$response = [
    "success" => false,
    "message" => ""
];

// Check if request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get tour ID from request
    $tour_id = $_POST["tour_id"];

    // Validate tour ID
    if (empty($tour_id)) {

        $response["message"] = "Tour ID is missing.";

    } else {

        // Delete tour from database
        $sql = "DELETE FROM tours WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $tour_id);

        // Check if deletion was successful
        if ($stmt->execute()) {

            $response["success"] = true;
            $response["message"] = "Tour deleted successfully.";

        } else {

            $response["message"] = "Failed to delete tour.";
        }
    }
}

// Return JSON response
header("Content-Type: application/json");
echo json_encode($response);

?>