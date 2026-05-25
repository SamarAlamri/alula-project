<?php
session_start();
include "../includes/db.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Tours</title>
    <link rel="stylesheet" href="../global/main.css">
</head>
<body>

    <h1>Manage Tours</h1>

    <!-- Add Tour Form -->
    <section>
        <h2>Add New Tour</h2>

        <form id="addTourForm" method="POST" enctype="multipart/form-data">
           
            <input type="hidden" name="tour_id" id="tour_id">

            <p>
                <label>Tour Title:</label><br>
                <input type="text" name="title" required>
            </p>

            <p>
                <label>Description:</label><br>
                <textarea name="description" required></textarea>
            </p>

            <p>
                <label>Duration:</label><br>
                <select name="duration" required>
                    <option value="">Select duration</option>
                    <option value="Half Day">Half Day</option>
                    <option value="Full Day">Full Day</option>
                </select>
            </p>

            <p>
                <label>Category:</label><br>
                <select name="category" required>
                    <option value="">Select category</option>
                    <option value="Heritage">Heritage</option>
                    <option value="Nature">Nature</option>
                    <option value="Adventure">Adventure</option>
                </select>
            </p>

            <p>
                <label>Price:</label><br>
                <input type="number" name="price" step="0.01" required>
            </p>

            <h3>Tour Schedule</h3>

            <div id="scheduleRows">
                <div class="schedule-row">
                    <input type="time" name="schedule_time[]" required>
                    <input type="text" name="schedule_activity[]" placeholder="Activity" required>
                </div>
            </div>

            <button type="button" id="addScheduleRow">+ Add Schedule Row</button>

            <br><br>

            <button type="submit" id="submitTourButton">Add Tour</button>
        </form>
    </section>

    <section>
        <h2>Upload Tours CSV</h2>

        <form id="uploadCsvForm" method="POST" enctype="multipart/form-data">
            <p>
                <label>Choose CSV File:</label><br>
                <input type="file" name="tour_csv" accept=".csv" required>
            </p>

            <button type="submit">Upload CSV</button>
        </form>
    </section>
    
    <hr>

    <!-- Existing Tours -->
    <section>
        <h2>Existing Tours</h2>

        <table border="1" cellpadding="8">
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

                        $tourJson = htmlspecialchars(json_encode($tour), ENT_QUOTES, 'UTF-8');
                        
                        echo "<td>";
                        echo "<button type='button' onclick=\"editTour(
                            '" . $tour['id'] . "',
                            '" . addslashes(htmlspecialchars($tour['title'], ENT_QUOTES)) . "',
                            '" . addslashes(htmlspecialchars($tour['description'], ENT_QUOTES)) . "',
                            '" . htmlspecialchars($tour['duration'], ENT_QUOTES) . "',
                            '" . htmlspecialchars($tour['category'], ENT_QUOTES) . "',
                            '" . htmlspecialchars($tour['price'], ENT_QUOTES) . "',
                            this
                        )\">Edit</button>";
                        echo "<button type='button' onclick='deleteTour(" . $tour['id'] . ")'>Delete</button>";
                        echo "</td>";

                        echo "</tr>";
                    }

                } else {

                    echo '<tr><td colspan="6">No tours found.</td></tr>';
                }

                ?>

            </tbody>
        </table>
    </section>
    <script src="../scripts/main.js"></script>
</body>
</html>