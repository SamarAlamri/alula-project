<!-- Name: [Zain Aljifry], ID: [2107808], Section: [DAR], Date: [8 march] | Name: Samar Alamri, ID: 2206831, Section: DAR, Date: 8 march |Name: Talah Faloudah, ID: 2206666, Section: DAR, Date: 8 march -->

<?php
include "../includes/db.php";

$response = [
    "success" => false,
    "message" => ""
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $tour_id = $_POST["tour_id"];

    $title = trim($_POST["title"]);
    $description = trim($_POST["description"]);
    $duration = trim($_POST["duration"]);
    $category = trim($_POST["category"]);
    $price = $_POST["price"];

    $scheduleTimes = $_POST["schedule_time"];
    $scheduleActivities = $_POST["schedule_activity"];

    if (empty($tour_id)) {

        $response["message"] = "Tour ID is missing.";

    } else {

        $sql = "UPDATE tours
                SET title = ?, description = ?, duration = ?, category = ?, price = ?
                WHERE id = ?";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "ssssdi",
            $title,
            $description,
            $duration,
            $category,
            $price,
            $tour_id
        );

        if ($stmt->execute()) {

            $deleteScheduleSql = "DELETE FROM tour_schedule WHERE tour_id = ?";
            $deleteStmt = $conn->prepare($deleteScheduleSql);
            $deleteStmt->bind_param("i", $tour_id);
            $deleteStmt->execute();

            $scheduleSql = "INSERT INTO tour_schedule (tour_id, time, activity)
                            VALUES (?, ?, ?)";

            $scheduleStmt = $conn->prepare($scheduleSql);

            for ($i = 0; $i < count($scheduleTimes); $i++) {

                $time = $scheduleTimes[$i];
                $activity = trim($scheduleActivities[$i]);

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

            $response["success"] = true;
            $response["message"] = "Tour updated successfully.";

        } else {

            $response["message"] = "Failed to update tour.";
        }
    }
}

header("Content-Type: application/json");
echo json_encode($response);
?>