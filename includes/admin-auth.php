<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/login.php");
    exit();
}

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../pages/index.php");
    exit();
}
?>