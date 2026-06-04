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

    // Get updated tour information
    $title = trim($_POST["title"]);
    $description = trim($_POST["description"]);
    $tour_date = $_POST["tour_date"];
    $duration = trim($_POST["duration"]);
    $category = trim($_POST["category"]);
    $price = $_POST["price"];

    // Get updated schedule data
    $scheduleTimes = $_POST["schedule_time"];
    $scheduleActivities = $_POST["schedule_activity"];

    // Validate tour ID
    if (empty($tour_id)) {

        $response["message"] = "Tour ID is missing.";

    } else {

        // Update tour details
        $sql = "UPDATE tours
                SET title = ?, description = ?, tour_date = ?, duration = ?, category = ?, price = ?
                WHERE id = ?";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "sssssdi",
            $title,
            $description,
            $tour_date,
            $duration,
            $category,
            $price,
            $tour_id
        );

        // Check if tour update was successful
        if ($stmt->execute()) {

            // Delete old schedule rows
            $deleteScheduleSql = "DELETE FROM tour_schedule WHERE tour_id = ?";
            $deleteStmt = $conn->prepare($deleteScheduleSql);
            $deleteStmt->bind_param("i", $tour_id);
            $deleteStmt->execute();

            // Insert updated schedule rows
            $scheduleSql = "INSERT INTO tour_schedule (tour_id, time, activity)
                            VALUES (?, ?, ?)";

            $scheduleStmt = $conn->prepare($scheduleSql);

            // Loop through schedule rows
            for ($i = 0; $i < count($scheduleTimes); $i++) {

                $time = $scheduleTimes[$i];
                $activity = trim($scheduleActivities[$i]);

                // Insert non-empty schedule entries
                if (!empty($time) && !empty($activity)) {

                    $scheduleStmt->bind_param(
                        "iss",
                        $tour_id,
                        $time,
                        $activity
                    );

                    $scheduleStmt->execute();
                }
            }

            // Success response
            $response["success"] = true;
            $response["message"] = "Tour updated successfully.";

        } else {

            // Error response
            $response["message"] = "Failed to update tour.";
        }
    }
}

// Return JSON response
header("Content-Type: application/json");
echo json_encode($response);

?>