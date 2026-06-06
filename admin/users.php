<!-- Name: [Zain Aljifry], ID: [2107808], Section: [DAR], Date: [8 march] | Name: Samar Alamri, ID: 2206831, Section: DAR, Date: 8 march |Name: Talah Faloudah, ID: 2206666, Section: DAR, Date: 8 march -->
<?php

// Check admin access
include "../includes/auth.php";
requireAdmin();

// Database connection
include "../includes/db.php";

// Session timeout protection
include "../includes/session_timeout.php";

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

                        $role = strtolower($user['role']);

                        echo "<tr>";

                        echo "<td>" . htmlspecialchars($user['name']) . "</td>";
                        echo "<td>" . htmlspecialchars($user['email']) . "</td>";

                        echo "<td class='role-cell'><strong>" .
                             ucfirst(htmlspecialchars($user['role'])) .
                             "</strong></td>";

                        echo "<td style='text-align: center;'>";

                        // Show action based on current role
                        if ($role == 'admin') {

                            echo "<button
                                    class='admin-btn role-btn'
                                    data-id='" . $user['id'] . "'
                                    data-action='remove_admin'
                                    style='background-color: #5a1a0a;'>
                                    Remove Admin
                                  </button>";

                        } else {

                            echo "<button
                                    class='admin-btn role-btn'
                                    data-id='" . $user['id'] . "'
                                    data-action='make_admin'>
                                    Make Admin
                                  </button>";
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

<script>

// Select all role buttons
document.querySelectorAll(".role-btn").forEach(button => {

    // Handle role update click
    button.addEventListener("click", function () {

        // Get user ID and action
        let userId = this.dataset.id;
        let action = this.dataset.action;

        // Get current table row
        let row = this.closest("tr");

        // Get role cell
        let roleCell = row.querySelector(".role-cell");

        // Send request to role API
        fetch("../api/user_role.php", {

            method: "POST",

            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },

            body: "id=" + userId + "&action=" + action
        })

        // Convert response to JSON
        .then(response => response.json())

        // Update role without page reload
        .then(data => {

            if (data.success) {

                // Update displayed role
                roleCell.innerHTML =
                    "<strong>" + data.role + "</strong>";

                // Change button to remove admin
                if (data.role.toLowerCase() === "admin") {

                    button.innerText = "Remove Admin";
                    button.dataset.action = "remove_admin";
                    button.style.backgroundColor = "#5a1a0a";

                } else {

                    // Change button to make admin
                    button.innerText = "Make Admin";
                    button.dataset.action = "make_admin";
                    button.style.backgroundColor = "";
                }

            } else {

                // Display error message
                alert(data.message);
            }
        });
    });
});

</script>

</body>
</html>