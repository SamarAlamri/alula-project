<?php
session_start();

$timeout_duration = 900; // 15 minutes

if (isset($_SESSION['LAST_ACTIVITY'])) {
    if (time() - $_SESSION['LAST_ACTIVITY'] > $timeout_duration) {
        session_unset();
        session_destroy();

        header("Location: ../pages/login.php?timeout=1");
        exit();
    }
}

$_SESSION['LAST_ACTIVITY'] = time();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/login.php");
    exit();
}
?>