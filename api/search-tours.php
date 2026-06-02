<?php
include "../includes/db.php";

$search = isset($_GET['search']) ? trim($_GET['search']) : "";
$category = isset($_GET['category']) ? trim($_GET['category']) : "";
$duration = isset($_GET['duration']) ? trim($_GET['duration']) : "";

// Use 1=1 so appending "AND ..." always works seamlessly
$sql = "SELECT * FROM tours WHERE 1=1";
$params = [];
$types = "";

if (!empty($search)) {
    $sql .= " AND title LIKE ?";
    $params[] = "%" . $search . "%";
    $types .= "s";
}

if (!empty($category)) {
    $sql .= " AND category = ?";
    $params[] = $category;
    $types .= "s";
}

if (!empty($duration)) {
    $sql .= " AND duration = ?";
    $params[] = $duration;
    $types .= "s";
}

$sql .= " ORDER BY created_at DESC";

// CRITICAL: Prepare the statement AFTER the complete SQL string is completely built!
$stmt = $conn->prepare($sql);

// Bind parameters only if filters were actually used
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$tours = [];
if ($result) {
    while ($tour = $result->fetch_assoc()) {
        $tours[] = $tour;
    }
}

header("Content-Type: application/json");
echo json_encode($tours);
?>