<?php
include "../includes/auth.php";
requireLogin();

include "../includes/db.php";

$response = [
    "success" => false,
    "message" => ""
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user_id = $_SESSION['user_id'];
    $tour_id = $_POST['tour_id'];

    if (empty($tour_id)) {
        $response["message"] = "Tour ID is missing.";
    } else {
        $sql = "DELETE FROM my_trips WHERE user_id = ? AND tour_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $user_id, $tour_id);

        if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $response["success"] = true;
            $response["message"] = "Tour cancelled successfully.";
        } else {
            $response["message"] = "No matching booked tour was found.";
        }
    }
    }
}

header("Content-Type: application/json");
echo json_encode($response);
?>