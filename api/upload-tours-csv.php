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

    // Check if CSV file was uploaded correctly
    if (!isset($_FILES["tour_csv"]) || $_FILES["tour_csv"]["error"] != 0) {
        $response["message"] = "Please upload a CSV file.";
        echo json_encode($response);
        exit();
    }

    // Get uploaded file information
    $fileName = $_FILES["tour_csv"]["name"];
    $fileSize = $_FILES["tour_csv"]["size"];
    $fileTmp = $_FILES["tour_csv"]["tmp_name"];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // Allowed file MIME types
    $allowedMimeTypes = [
        "text/csv",
        "text/plain",
        "application/vnd.ms-excel"
    ];

    // Get actual file MIME type
    $fileMimeType = mime_content_type($fileTmp);

    // Validate file MIME type
    if (!in_array($fileMimeType, $allowedMimeTypes)) {
        $response["message"] = "Invalid file type.";
        echo json_encode($response);
        exit();
    }

    // Validate file extension
    if ($fileExt !== "csv") {
        $response["message"] = "Only CSV files are allowed.";
        echo json_encode($response);
        exit();
    }

    // Validate file size
    if ($fileSize > 2 * 1024 * 1024) {
        $response["message"] = "File size must not exceed 2MB.";
        echo json_encode($response);
        exit();
    }

    // Create a unique name for the uploaded file
    $newFileName = uniqid("tours_", true) . ".csv";
    $uploadPath = "../uploads/tour_csv/" . $newFileName;

    // Move uploaded file to uploads folder
    if (!move_uploaded_file($fileTmp, $uploadPath)) {
        $response["message"] = "Failed to upload file.";
        echo json_encode($response);
        exit();
    }

    // Open uploaded CSV file
    $file = fopen($uploadPath, "r");

    // Skip header row
    fgetcsv($file);

    // Read CSV rows
    while (($row = fgetcsv($file)) !== false) {

        // Skip incomplete rows
        if (count($row) < 8) {
            continue;
        }

        // Get tour data from CSV row
        $title = trim($row[0]);
        $description = trim($row[1]);
        $tour_date = trim($row[2]);
        $duration = trim($row[3]);
        $category = trim($row[4]);
        $price = trim($row[5]);
        $time = trim($row[6]);
        $activity = trim($row[7]);

        // Skip invalid rows
        if (empty($title) || empty($time) || empty($activity)) {
            continue;
        }

        // Check if tour already exists
        $checkSql = "SELECT id FROM tours WHERE title = ?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("s", $title);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        // Use existing tour ID if tour already exists
        if ($result->num_rows > 0) {
            $tour = $result->fetch_assoc();
            $tour_id = $tour["id"];

        // Insert new tour if it does not exist
        } else {
            $insertTourSql = "INSERT INTO tours
                            (title, description, tour_date, duration, category, price)
                            VALUES (?, ?, ?, ?, ?, ?)";

            $insertTourStmt = $conn->prepare($insertTourSql);
            $insertTourStmt->bind_param(
                "sssssd",
                $title,
                $description,
                $tour_date,
                $duration,
                $category,
                $price
            );
            $insertTourStmt->execute();

            $tour_id = $insertTourStmt->insert_id;
        }

        // Insert schedule row for the tour
        $insertScheduleSql = "INSERT INTO tour_schedule (tour_id, time, activity)
                              VALUES (?, ?, ?)";

        $insertScheduleStmt = $conn->prepare($insertScheduleSql);
        $insertScheduleStmt->bind_param("iss", $tour_id, $time, $activity);
        $insertScheduleStmt->execute();
    }

    // Close CSV file
    fclose($file);

    // Success response
    $response["success"] = true;
    $response["message"] = "CSV uploaded and tours imported successfully.";
}

// Return JSON response
header("Content-Type: application/json");
echo json_encode($response);

?>