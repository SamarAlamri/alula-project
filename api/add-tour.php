<!-- Name: [Zain Aljifry], ID: [2107808], Section: [DAR], Date: [8 march] | Name: Samar Alamri, ID: 2206831, Section: DAR, Date: 8 march |Name: Talah Faloudah, ID: 2206666, Section: DAR, Date: 8 march -->
<?php

// Database connection
include "../includes/db.php";

// Response array
$response = [
    "success" => false,
    "message" => ""
];

// Check if request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get tour information from form
    $title = trim($_POST["title"]);
    $description = trim($_POST["description"]);
    $duration = trim($_POST["duration"]);
    $category = trim($_POST["category"]);
    $price = $_POST["price"];

    // Get schedule data
    $scheduleTimes = $_POST["schedule_time"];
    $scheduleActivities = $_POST["schedule_activity"];

    // Validate required fields
    if (empty($title) || empty($description) || empty($duration) || empty($category) || empty($price)) {

        $response["message"] = "All tour fields are required.";

    } else {

        // Insert tour into database
        $sql = "INSERT INTO tours (title, description, duration, category, price)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssd", $title, $description, $duration, $category, $price);

        // Check if tour was added successfully
        if ($stmt->execute()) {

            // Get newly created tour ID
            $tour_id = $stmt->insert_id;

            // Insert schedule records
            $scheduleSql = "INSERT INTO tour_schedule (tour_id, time, activity)
                            VALUES (?, ?, ?)";

            $scheduleStmt = $conn->prepare($scheduleSql);

            // Loop through schedule rows
            for ($i = 0; $i < count($scheduleTimes); $i++) {

                $time = $scheduleTimes[$i];
                $activity = trim($scheduleActivities[$i]);

                // Insert non-empty schedule entries
                if (!empty($time) && !empty($activity)) {

                    $scheduleStmt->bind_param("iss", $tour_id, $time, $activity);
                    $scheduleStmt->execute();
                }
            }

            // Success response
            $response["success"] = true;
            $response["message"] = "Tour added successfully.";

        } else {

            // Error response
            $response["message"] = "Failed to add tour.";
        }
    }
}

// Return JSON response
header("Content-Type: application/json");
echo json_encode($response);

?>