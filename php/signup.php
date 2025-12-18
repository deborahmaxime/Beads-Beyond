<?php
session_start();
require 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];

    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    try {
        $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password_hash, phone) VALUES (?, ?, ?, ?)");
        $stmt->execute([$full_name, $email, $password_hash, $phone]);
        header('Location: login.php');
        exit;
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) { // duplicate email
            $error = "Email already exists.";
        } else {
            $error = "Database error: " . $e->getMessage();
        }
    }
    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up - Beads & Beyond</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="auth-page" >
    <div class="auth-container">
        <h2>Create an Account</h2>

        <?php if($error): ?>
            <div class="error-msg"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <label>Full Name</label>
            <input type="text" name="full_name" required>

            <label>Email</label>
            <input type="email" name="email" required>

            <label>Phone</label>
            <input type="text" name="phone">

            <label>Password</label>
            <input type="password" name="password" required>

            <button type="submit">Sign Up</button>
        </form>

        <p>Already have an account? <a href="login.php">Login</a></p>
    </div>
<script src="../js/darkmode.js" defer></script>
</body>
</html>
