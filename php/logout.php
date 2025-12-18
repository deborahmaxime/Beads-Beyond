<?php
session_start();

/* Unset all session variables */
$_SESSION = [];

/* Destroy the session */
session_destroy();

/* Clear Remember Me cookies */
if (isset($_COOKIE['user_id'])) {
    setcookie('user_id', '', time() - 3600, "/");
}
if (isset($_COOKIE['full_name'])) {
    setcookie('full_name', '', time() - 3600, "/");
}

/* Prevent back-button access */
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

/* Redirect to login page */
header("Location: login.php");
exit;
