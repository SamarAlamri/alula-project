<!-- Name: [Zain Aljifry], ID: [2107808], Section: [DAR], Date: [8 march] | Name: Samar Alamri, ID: 2206831, Section: DAR, Date: 8 march |Name: Talah Faloudah, ID: 2206666, Section: DAR, Date: 8 march -->
<?php

// Check admin access
include "../includes/admin-auth.php";

// Database connection
include "../includes/db.php";

// Handle role update actions
if (isset($_GET['action']) && isset($_GET['id'])) {

    // Get user ID and action from URL
    $userId = intval($_GET['id']);
    $action = $_GET['action'];
    
    // Set user as admin
    if ($action == 'make_admin') {
        $updateSql = "UPDATE users SET role = 'admin' WHERE id = ?";

    // Remove admin privileges
    } else if ($action == 'remove_admin') {
        $updateSql = "UPDATE users SET role = 'visitor' WHERE id = ?";
    }
    
    // Execute update query
    if (isset($updateSql)) {
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        // Refresh page after update
        header("Location: users.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <!-- Page title -->
    <title>User Management</title>

    <!-- Admin stylesheet -->
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>

<!-- Admin navigation bar -->
<nav class="admin-navbar">
    <div class="admin-logo">AlUla Admin</div>

    <div class="admin-links">
        <a href="dashboard.php">Dashboard</a>
        <a href="users.php" class="active">Users</a>
        <a href="../pages/index.php">View Website</a>
        <a href="../pages/logout.php">Logout</a>
    </div>
</nav>

<!-- Page banner -->
<section class="admin-banner" style="text-align: center;">
    <h1>User Management</h1>
    <p>Manage users and admin permissions</p>
</section>

<div class="admin-container">

    <!-- Users table section -->
    <div class="admin-form">

        <h2>Users & Permissions</h2>

        <table class="admin-table" style="margin-top: 20px;">

            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th style="text-align: center;">Action</th>
                </tr>
            </thead>

            <tbody>
                <?php

                // Retrieve all users
                $sql = "SELECT * FROM users ORDER BY id DESC";
                $result = $conn->query($sql);

                if ($result && $result->num_rows > 0) {

                    // Display each user
                    while ($user = $result->fetch_assoc()) {

                        echo "<tr>";

                        echo "<td>" . htmlspecialchars($user['name']) . "</td>";
                        echo "<td>" . htmlspecialchars($user['email']) . "</td>";
                        echo "<td><strong>" . ucfirst(htmlspecialchars($user['role'])) . "</strong></td>";

                        echo "<td style='text-align: center;'>";

                        // Show action based on current role
                        if (strtolower($user['role']) == 'admin') {

                            echo "<a href='users.php?action=remove_admin&id=" . $user['id'] . "' class='admin-btn' style='background-color: #5a1a0a; text-decoration: none; display: inline-block;'>Remove Admin</a>";

                        } else {

                            echo "<a href='users.php?action=make_admin&id=" . $user['id'] . "' class='admin-btn' style='text-decoration: none; display: inline-block;'>Make Admin</a>";
                        }

                        echo "</td>";
                        echo "</tr>";
                    }

                } else {

                    // Display message if no users exist
                    echo '<tr><td colspan="4" style="text-align: center;">No users found.</td></tr>';
                }
                ?>
            </tbody>

        </table>

    </div>

</div>

</body>
</html>