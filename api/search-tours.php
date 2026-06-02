<!-- Name: [Zain Aljifry], ID: [2107808], Section: [DAR], Date: [8 march] | Name: Samar Alamri, ID: 2206831, Section: DAR, Date: 8 march |Name: Talah Faloudah, ID: 2206666, Section: DAR, Date: 8 march -->

<?php

// Database connection
include "../includes/db.php";

// Get filter values from URL
$search = isset($_GET['search']) ? trim($_GET['search']) : "";
$category = isset($_GET['category']) ? trim($_GET['category']) : "";
$duration = isset($_GET['duration']) ? trim($_GET['duration']) : "";

// Use 1=1 so appending "AND ..." always works seamlessly
$sql = "SELECT * FROM tours WHERE 1=1";
$params = [];
$types = "";

// Add search filter if provided
if (!empty($search)) {
    $sql .= " AND title LIKE ?";
    $params[] = "%" . $search . "%";
    $types .= "s";
}

// Add category filter if provided
if (!empty($category)) {
    $sql .= " AND category = ?";
    $params[] = $category;
    $types .= "s";
}

// Add duration filter if provided
if (!empty($duration)) {
    $sql .= " AND duration = ?";
    $params[] = $duration;
    $types .= "s";
}

// Sort newest tours first
$sql .= " ORDER BY created_at DESC";

// CRITICAL: Prepare the statement AFTER the complete SQL string is completely built!
$stmt = $conn->prepare($sql);

// Bind parameters only if filters were actually used
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

// Store tours in an array
$tours = [];
if ($result) {
    while ($tour = $result->fetch_assoc()) {
        $tours[] = $tour;
    }
}

// Return tours as JSON
header("Content-Type: application/json");
echo json_encode($tours);

?>