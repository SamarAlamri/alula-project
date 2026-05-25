<?php
include "../includes/db.php";

$response = [
    "success" => false,
    "message" => ""
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $tour_id = $_POST["tour_id"];

    if (empty($tour_id)) {
        $response["message"] = "Tour ID is missing.";
    } else {
        $sql = "DELETE FROM tours WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $tour_id);

        if ($stmt->execute()) {
            $response["success"] = true;
            $response["message"] = "Tour deleted successfully.";
        } else {
            $response["message"] = "Failed to delete tour.";
        }
    }
}

header("Content-Type: application/json");
echo json_encode($response);
?>