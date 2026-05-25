<?php
include "../includes/db.php";

$response = [
    "success" => false,
    "message" => ""
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!isset($_FILES["tour_csv"]) || $_FILES["tour_csv"]["error"] != 0) {
        $response["message"] = "Please upload a CSV file.";
        echo json_encode($response);
        exit();
    }

    $fileName = $_FILES["tour_csv"]["name"];
    $fileSize = $_FILES["tour_csv"]["size"];
    $fileTmp = $_FILES["tour_csv"]["tmp_name"];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if ($fileExt !== "csv") {
        $response["message"] = "Only CSV files are allowed.";
        echo json_encode($response);
        exit();
    }

    if ($fileSize > 2 * 1024 * 1024) {
        $response["message"] = "File size must not exceed 2MB.";
        echo json_encode($response);
        exit();
    }

    $newFileName = uniqid("tours_", true) . ".csv";
    $uploadPath = "../uploads/tour_csv/" . $newFileName;


    if (!move_uploaded_file($fileTmp, $uploadPath)) {
        $response["message"] = "Failed to upload file.";
        echo json_encode($response);
        exit();
    }

    $file = fopen($uploadPath, "r");

    // Skip header row
    fgetcsv($file);

    while (($row = fgetcsv($file)) !== false) {

        if (count($row) < 7) {
            continue;
        }

        $title = trim($row[0]);
        $description = trim($row[1]);
        $duration = trim($row[2]);
        $category = trim($row[3]);
        $price = trim($row[4]);
        $time = trim($row[5]);
        $activity = trim($row[6]);

        if (empty($title) || empty($time) || empty($activity)) {
            continue;
        }

        // Check if tour already exists
        $checkSql = "SELECT id FROM tours WHERE title = ?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("s", $title);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result->num_rows > 0) {
            $tour = $result->fetch_assoc();
            $tour_id = $tour["id"];
        } else {
            $insertTourSql = "INSERT INTO tours (title, description, duration, category, price)
                              VALUES (?, ?, ?, ?, ?)";

            $insertTourStmt = $conn->prepare($insertTourSql);
            $insertTourStmt->bind_param("ssssd", $title, $description, $duration, $category, $price);
            $insertTourStmt->execute();

            $tour_id = $insertTourStmt->insert_id;
        }

        $insertScheduleSql = "INSERT INTO tour_schedule (tour_id, time, activity)
                              VALUES (?, ?, ?)";

        $insertScheduleStmt = $conn->prepare($insertScheduleSql);
        $insertScheduleStmt->bind_param("iss", $tour_id, $time, $activity);
        $insertScheduleStmt->execute();
    }

    fclose($file);

    $response["success"] = true;
    $response["message"] = "CSV uploaded and tours imported successfully.";
}

header("Content-Type: application/json");
echo json_encode($response);
?>