<?php
// auth_session.php
session_start();

function is_logged_in() {
    return isset($_SESSION['user_id']);
}
$user_id = $_SESSION['user_id'] ?? null;
$full_name = $_SESSION['full_name'] ?? '';
$is_admin = $_SESSION['is_admin'] ?? false;

function require_login() {
    if (!is_logged_in()) {
        header('Location: php/login.php');
        exit;
    }
    
}

function is_admin() {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
}
?>
