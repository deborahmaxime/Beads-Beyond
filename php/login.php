<?php
session_start();
require 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember_me']); // match the input name

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        // Set session
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['is_admin'] = $user['is_admin'];

        // Set cookie if Remember Me is checked (30 days)
        if ($remember) {
            setcookie('user_id', $user['user_id'], time() + (86400 * 30), "/"); // 30 days
            setcookie('full_name', $user['full_name'], time() + (86400 * 30), "/");
        }

        if($_SESSION['is_admin'] === 1) {
            header('Location: ../admin/admin.php');
        } else {
            header('Location: ../index.php');
        }
        exit;
    } else {
        $error = "Invalid email or password.";
    }
}

// Check for existing cookies and auto-login
if (!isset($_SESSION['user_id']) && isset($_COOKIE['user_id'])) {
    $_SESSION['user_id'] = $_COOKIE['user_id'];
    $_SESSION['full_name'] = $_COOKIE['full_name'];
    header('Location: ../index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Beads & Beyond</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="auth-page">
    <div class="auth-wrapper">
        <!-- Left: Login Form -->
        <div class="auth-container">
            <h2>Login to Your Account</h2>

            <?php if($error): ?>
                <div class="error-msg"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form action="" method="POST">
                <label>Email</label>
                <input type="email" name="email" required>

                <label>Password</label>
                <input type="password" name="password" required>
                <div class="remember-me">
                    <label>
                        <input type="checkbox" name="remember_me"  id="remember_me">
                        Remember Me
                    </label>
                </div>
                

                <button type="submit">Login</button>
            </form>


            <p>Don't have an account? <a href="signup.php">Sign up</a></p>
        </div>

        <!-- Right: Decorative Image -->
        <div class="auth-image"></div>
    </div>
<script src="../js/darkmode.js" defer></script>
</body>
</html>
