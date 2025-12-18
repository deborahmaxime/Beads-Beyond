<?php
require '../php/db.php';
require '../php/auth_session.php';
require_login();

// Admin-only access
if (!is_logged_in() || intval($_SESSION['is_admin']) !== 1) {
    header('Location: ../php/login.php');
    exit;
}

// Update order status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $stmt = $pdo->prepare("UPDATE orders SET order_status = ?, updated_at = NOW() WHERE order_id = ?");
    $stmt->execute([$_POST['status'], $_POST['order_id']]);
}

// Fetch all orders
$stmt = $pdo->query("
    SELECT o.*, u.full_name, u.email
    FROM orders o
    JOIN users u ON o.user_id = u.user_id
    ORDER BY o.created_at DESC
");
$orders = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - View Orders | Beads & Beyond</title>
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
    <h1>Customer Orders</h1>
    <p>View and manage all customer orders</p>

    <table class="orders-table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Email</th>
                <th>Total (GHS)</th>
                <th>Status</th>
                <th>Order Date</th>
                <th>Update Status</th>
            </tr>
        </thead>
        <tbody>
        <?php if (count($orders) > 0): ?>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= htmlspecialchars($order['order_id']) ?></td>
                    <td><?= htmlspecialchars($order['full_name']) ?></td>
                    <td><?= htmlspecialchars($order['email']) ?></td>
                    <td><?= number_format($order['total_amount'], 2) ?></td>
                    <td>
                        <span class="status <?= htmlspecialchars($order['order_status']) ?>">
                            <?= htmlspecialchars($order['order_status']) ?>
                        </span>
                    </td>
                    <td><?= htmlspecialchars($order['created_at']) ?></td>
                    <td>
                        <form method="POST" style="display:flex; gap:0.5rem; justify-content:center;">
                            <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                            <select name="status">
                                <option value="Pending" <?= $order['order_status']=='Pending'?'selected':'' ?>>Pending</option>
                                <option value="Paid" <?= $order['order_status']=='Paid'?'selected':'' ?>>Paid</option>
                                <option value="Shipped" <?= $order['order_status']=='Shipped'?'selected':'' ?>>Shipped</option>
                                <option value="Completed" <?= $order['order_status']=='Completed'?'selected':'' ?>>Completed</option>
                                <option value="Cancelled" <?= $order['order_status']=='Cancelled'?'selected':'' ?>>Cancelled</option>
                            </select>
                            <button type="submit" class="btn-small">Save</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" style="text-align:center;">No orders found.</td>
            </tr>
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
