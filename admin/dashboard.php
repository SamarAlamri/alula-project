<!-- Name: [Zain Aljifry], ID: [2107808], Section: [DAR], Date: [8 march] | Name: Samar Alamri, ID: 2206831, Section: DAR, Date: 8 march |Name: Talah Faloudah, ID: 2206666, Section: DAR, Date: 8 march -->

<?php

include "../includes/admin-auth.php";
include "../includes/db.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Tours</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>

<body>

<nav class="admin-navbar">
    <div class="admin-logo">AlUla Admin</div>

    <div class="admin-links">
        <a href="dashboard.php" class="active">Dashboard</a>
        <a href="users.php">Users</a>
        <a href="../pages/index.php">View Website</a>
        <a href="../pages/logout.php">Logout</a>
    </div>
</nav>

<section class="admin-banner">
    <h1>Manage Tours</h1>
    <p>Add, edit and manage AlUla tours</p>
</section>

<div class="admin-container">

    <div class="admin-form">
        <h2>Add New Tour</h2>

        <form id="addTourForm" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="tour_id" id="tour_id">

            <label>Tour Title:</label>
            <input type="text" name="title" required>

            <label>Description:</label>
            <textarea name="description" required></textarea>

            <label>Duration:</label>
            <select name="duration" required>
                <option value="">Select duration</option>
                <option value="Half Day">Half Day</option>
                <option value="Full Day">Full Day</option>
            </select>

            <label>Category:</label>
            <select name="category" required>
                <option value="">Select category</option>
                <option value="Heritage">Heritage</option>
                <option value="Nature">Nature</option>
                <option value="Adventure">Adventure</option>
            </select>

            <label>Price:</label>
            <input type="number" name="price" step="0.01" required>

            <h3>Tour Schedule</h3>

            <div id="scheduleRows">
                <div class="schedule-row">
                    <input type="time" name="schedule_time[]" required>
                    <input type="text" name="schedule_activity[]" placeholder="Activity" required>
                </div>
            </div>

            <button type="button" class="admin-btn" id="addScheduleRow">
                + Add Schedule Row
            </button>

            <br><br>

            <button type="submit" class="admin-btn" id="submitTourButton">
                Add Tour
            </button>
        </form>
    </div>

    <div class="admin-form">
        <h2>Upload Tours CSV</h2>

        <form id="uploadCsvForm" method="POST" enctype="multipart/form-data">
            <label>Choose CSV File:</label>
            <input type="file" name="tour_csv" accept=".csv" required>

            <button type="submit" class="admin-btn">
                Upload CSV
            </button>
        </form>
    </div>

    <div class="admin-form">
        <h2>Existing Tours</h2>

        <table class="admin-table">
            <thead>
                <tr>
                    <th>Tour Title</th>
                    <th>Category</th>
                    <th>Duration</th>
                    <th>Price</th>
                    <th>Schedule</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody id="tourTableBody">
                <?php
                $sql = "SELECT * FROM tours ORDER BY created_at DESC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($tour = $result->fetch_assoc()) {
                        echo "<tr>";

                        echo "<td>" . htmlspecialchars($tour['title']) . "</td>";
                        echo "<td>" . htmlspecialchars($tour['category']) . "</td>";
                        echo "<td>" . htmlspecialchars($tour['duration']) . "</td>";
                        echo "<td>$" . htmlspecialchars($tour['price']) . "</td>";

                        echo "<td>";

                        $tourId = $tour['id'];

                        $scheduleSql = "SELECT * FROM tour_schedule WHERE tour_id = ?";
                        $scheduleStmt = $conn->prepare($scheduleSql);
                        $scheduleStmt->bind_param("i", $tourId);
                        $scheduleStmt->execute();

                        $scheduleResult = $scheduleStmt->get_result();

                        while ($schedule = $scheduleResult->fetch_assoc()) {
                            echo htmlspecialchars($schedule['time']) .
                                " - " .
                                htmlspecialchars($schedule['activity']) .
                                "<br>";
                        }

                        echo "</td>";

                        echo "<td>";

                        echo "<button type='button' class='admin-btn' onclick=\"editTour(
                            '" . $tour['id'] . "',
                            '" . addslashes(htmlspecialchars($tour['title'], ENT_QUOTES)) . "',
                            '" . addslashes(htmlspecialchars($tour['description'], ENT_QUOTES)) . "',
                            '" . htmlspecialchars($tour['duration'], ENT_QUOTES) . "',
                            '" . htmlspecialchars($tour['category'], ENT_QUOTES) . "',
                            '" . htmlspecialchars($tour['price'], ENT_QUOTES) . "',
                            this
                        )\">Edit</button> ";

                        echo "<button type='button' class='admin-btn' onclick='deleteTour(" . $tour['id'] . ")'>Delete</button>";

                        echo "</td>";

                        echo "</tr>";
                    }
                } else {
                    echo '<tr><td colspan="6">No tours found.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

</div>

<script src="../scripts/main.js"></script>

</body>
</html>