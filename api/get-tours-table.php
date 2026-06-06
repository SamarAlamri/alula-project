<?php
include_once "../includes/auth.php";
requireAdmin();

include "../includes/db.php";


                // Retrieve all tours
                $sql = "SELECT * FROM tours ORDER BY created_at DESC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {

                    // Loop through each tour
                    while ($tour = $result->fetch_assoc()) {

                        echo "<tr>";

                        // Display tour details
                        echo "<td>" . htmlspecialchars($tour['title']) . "</td>";
                        echo "<td>" . htmlspecialchars($tour['tour_date']) . "</td>";
                        echo "<td>" . htmlspecialchars($tour['category']) . "</td>";
                        echo "<td>" . htmlspecialchars($tour['duration']) . "</td>";
                        echo "<td>$" . htmlspecialchars($tour['price']) . "</td>";

                        echo "<td>";

                        $tourId = $tour['id'];

                        // Get schedule for current tour
                        $scheduleSql = "SELECT * FROM tour_schedule WHERE tour_id = ?";
                        $scheduleStmt = $conn->prepare($scheduleSql);
                        $scheduleStmt->bind_param("i", $tourId);
                        $scheduleStmt->execute();

                        $scheduleResult = $scheduleStmt->get_result();

                        // Display schedule rows
                        while ($schedule = $scheduleResult->fetch_assoc()) {
                            echo htmlspecialchars($schedule['time']) .
                                " - " .
                                htmlspecialchars($schedule['activity']) .
                                "<br>";
                        }

                        echo "</td>";

                        echo "<td>";

                        // Edit button
                        echo "<button type='button' class='admin-btn' onclick=\"editTour(
                            '" . $tour['id'] . "',
                            '" . addslashes(htmlspecialchars($tour['title'], ENT_QUOTES)) . "',
                            '" . addslashes(htmlspecialchars($tour['description'], ENT_QUOTES)) . "',
                            '" . htmlspecialchars($tour['tour_date'], ENT_QUOTES) . "',
                            '" . htmlspecialchars($tour['duration'], ENT_QUOTES) . "',
                            '" . htmlspecialchars($tour['category'], ENT_QUOTES) . "',
                            '" . htmlspecialchars($tour['price'], ENT_QUOTES) . "',
                            this
                        )\">Edit</button> ";

                        // Delete button
                        echo "<button type='button' class='admin-btn' onclick='deleteTour(" . $tour['id'] . ")'>Delete</button>";

                        echo "</td>";

                        echo "</tr>";
                    }

                } else {

                    // Show message if no tours exist
                    echo '<tr><td colspan="6">No tours found.</td></tr>';
                }
?>
