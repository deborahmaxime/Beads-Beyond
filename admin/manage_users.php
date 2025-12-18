<?php
require '../php/db.php';
require '../php/auth_session.php';


// Admin-only access
if (!is_logged_in() || $_SESSION['is_admin'] !== 1) {
    header('Location: ../php/login.php');
    exit;
}

$error = '';
$success = '';
//AI GENERATED PLUS DEBUG
// Handle delete user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = (int) $_POST['delete_id'];
    if ($delete_id !== $_SESSION['user_id']) { // Prevent deleting self
        $stmt = $pdo->prepare("DELETE FROM users WHERE user_id = ?");
        $stmt->execute([$delete_id]);
        $success = "User deleted successfully.";
    } else {
        $error = "You cannot delete your own account!";
    }
}

// Handle status update (admin toggle)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status_id'], $_POST['is_admin'])) {
    $status_id = (int) $_POST['status_id'];
    $is_admin  = (int) $_POST['is_admin'];
    if ($status_id !== $_SESSION['user_id']) { // Prevent demoting self
        $stmt = $pdo->prepare("UPDATE users SET is_admin = ? WHERE user_id = ?");
        $stmt->execute([$is_admin, $status_id]);
        $success = "User status updated.";
    }
}
//AI generated stops here
// Fetch all users
$users = $pdo->query("SELECT user_id, full_name, email, phone, is_admin, created_at FROM users ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Manage Users | Beads & Beyond</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<header class="main-header">
    <div class="logo">
        Beads & Beyond
        <h6 class="logo-tagline">Admin Panel</h6>
    </div>
    <nav class="main-nav">
        <a href="admin.php">Dashboard</a>
        <a href="../php/logout.php" class="btn-outline">Logout</a>
        <button id="darkToggle" class="btn-small">Dark</button>
    </nav>
</header>

<section class="admin-dashboard">
    <h1>Manage Users</h1>
    <?php if ($error): ?>
        <div class="error-msg"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="success-msg"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <table class="users-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Admin</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($users) > 0): ?>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['user_id']) ?></td>
                        <td><?= htmlspecialchars($user['full_name']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['phone']) ?></td>
                        <td>
                            <form method="POST" style="display:inline-block;">
                                <input type="hidden" name="status_id" value="<?= $user['user_id'] ?>">
                                <select name="is_admin" onchange="this.form.submit()">
                                    <option value="1" <?= $user['is_admin'] ? 'selected' : '' ?>>Yes</option>
                                    <option value="0" <?= !$user['is_admin'] ? 'selected' : '' ?>>No</option>
                                </select>
                            </form>
                        </td>
                        <td><?= htmlspecialchars($user['created_at']) ?></td>
                        <td>
                            <?php if ($user['user_id'] !== $_SESSION['user_id']): ?>
                                <form method="POST" style="display:inline-block;">
                                    <input type="hidden" name="delete_id" value="<?= $user['user_id'] ?>">
                                    <button type="submit" class="btn-small btn-danger"
                                            onclick="return confirm('Are you sure you want to delete this user?');">
                                        Delete
                                    </button>
                                </form>
                            <?php else: ?>
                                —
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7" style="text-align:center;">No users found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<footer class="footer">
    <p>© <?= date('Y') ?> Beads & Beyond • Admin</p>
</footer>

<script src="../js/darkmode.js" defer></script>
</body>
</html>
