<!-- Name: [Zain Aljifry], ID: [2107808], Section: [DAR], Date: [8 march] | Name: Samar Alamri, ID: 2206831, Section: DAR, Date: 8 march | Name: Talah Faloudah, ID: 2206666, Section: DAR, Date: 8 march -->

<?php

// Start the session so PHP can access the current user's session data
session_start();

// Remove all session variables, such as user_id, name, and role
session_unset();

// Destroy the current session completely
session_destroy();

// Redirect the user to the login page after logging out
header("Location: login.php");

// Stop the script after redirecting
exit();

?>