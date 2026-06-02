<!-- Name: [Zain Aljifry], ID: [2107808], Section: [DAR], Date: [8 march] | Name: Samar Alamri, ID: 2206831, Section: DAR, Date: 8 march |Name: Talah Faloudah, ID: 2206666, Section: DAR, Date: 8 march -->

<?php
include "../includes/db.php";

$response = [
    "success" => false,
    "message" => ""
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = trim($_POST["title"]);
    $description = trim($_POST["description"]);
    $duration = trim($_POST["duration"]);
    $category = trim($_POST["category"]);
    $price = $_POST["price"];

    $scheduleTimes = $_POST["schedule_time"];
    $scheduleActivities = $_POST["schedule_activity"];

    if (empty($title) || empty($description) || empty($duration) || empty($category) || empty($price)) {
        $response["message"] = "All tour fields are required.";
    } else {

        $sql = "INSERT INTO tours (title, description, duration, category, price)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssd", $title, $description, $duration, $category, $price);

        if ($stmt->execute()) {

            $tour_id = $stmt->insert_id;

            $scheduleSql = "INSERT INTO tour_schedule (tour_id, time, activity)
                            VALUES (?, ?, ?)";

            $scheduleStmt = $conn->prepare($scheduleSql);

            for ($i = 0; $i < count($scheduleTimes); $i++) {
                $time = $scheduleTimes[$i];
                $activity = trim($scheduleActivities[$i]);

                if (!empty($time) && !empty($activity)) {
                    $scheduleStmt->bind_param("iss", $tour_id, $time, $activity);
                    $scheduleStmt->execute();
                }
            }

            $response["success"] = true;
            $response["message"] = "Tour added successfully.";
        } else {
            $response["message"] = "Failed to add tour.";
        }
    }
}

header("Content-Type: application/json");
echo json_encode($response);
?>