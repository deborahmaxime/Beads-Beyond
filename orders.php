<?php
require 'php/db.php';
require 'php/auth_session.php';
require_login();

$user_id = $_SESSION['user_id'];

/* Fetch all orders for this user */
$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Orders | Beads & Beyond</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header class="main-header">
    <div class="logo">Beads & Beyond<br>
        <h6 class="welcome">Hi, <?= htmlspecialchars($_SESSION['full_name']) ?></h6>
    </div>
    <nav class="main-nav">
        <a href="index.php">Home</a>
        <a href="about.php">About</a>
        <?php if(is_logged_in()): ?>
                <a href="shop.php">Shop</a>
                <a href="cart.php">Cart</a>
                <a href="orders.php">Orders</a>
                <a href="php/logout.php" class="btn-outline">Logout</a>
            <?php else: ?>
                <a href="php/login.php">Login</a>
            <?php endif; ?>
        <button id="darkToggle" class="btn-small">Dark</button>
    </nav>
</header>

<section class="orders-page">
    <div class="section-header">
        <h2>My Orders</h2>
        <p>View all your past and current orders</p>
    </div>

    <div class="orders-container" style="padding:50px;">
        <?php if (empty($orders)): ?>
            <p><br>You haven't placed any orders yet.</p><br>
            <a href="shop.php" class="btn-outline">Start Shopping</a>
        <?php else: ?>
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="background:#d4af37; color:#fff;">
                        <th>Order ID</th>
                        <th>Date</th>
                        <th>Total (GHS)</th>
                        <th>Status</th>
                        <th>Payment Method</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr style="border-bottom:1px solid #ccc;">
                            <td>#00000za34y01<?= $order['order_id'] ?></td>
                            <td><?= date('d M, Y', strtotime($order['created_at'])) ?></td>
                            <td><?= number_format($order['total_amount'], 2) ?></td>
                            <td><?= htmlspecialchars($order['order_status']) ?></td>
                            <td><?= htmlspecialchars($order['payment_method']) ?></td>
                            <td>
                                <a href="orders_details.php?id=<?= $order['order_id'] ?>" class="btn-small">View Details</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</section>
<script src="js/darkmode.js" defer></script>
<footer class="footer">
    <p>© <?= date('Y') ?> Beads & Beyond • Handmade with Love</p>
</footer>
</body>
</html>
