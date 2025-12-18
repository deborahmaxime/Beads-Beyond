<?php
require 'php/db.php';
require 'php/auth_session.php';
require_login();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: orders.php');
    exit;
}

$order_id = (int) $_GET['id'];
$user_id = $_SESSION['user_id'];

/* Fetch order */
$stmt = $pdo->prepare("SELECT * FROM orders WHERE order_id = ? AND user_id = ?");
$stmt->execute([$order_id, $user_id]);
$order = $stmt->fetch();

if (!$order) {
    header('Location: orders.php');
    exit;
}

/* Fetch order items with product info */
$stmtItems = $pdo->prepare("
    SELECT oi.*, p.name, p.image
    FROM order_items oi
    JOIN products p ON oi.product_id = p.product_id
    WHERE oi.order_id = ?
");
$stmtItems->execute([$order_id]);
$items = $stmtItems->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order #<?= $order['order_id'] ?> | Beads & Beyond</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header class="main-header">
    <div class="logo">Beads & Beyond<br>
        <h6 class="welcome">Hi, <?= htmlspecialchars($_SESSION['full_name']) ?></h6>
    </div>
    <nav class="main-nav">
        <a href="index.php">Home</a>
        <a href="shop.php">Shop</a>
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

<section class="order-details-page">
    <div class="section-header">
        <h2>Order #<?= $order['order_id'] ?></h2>
        <p>Order placed on <?= date('d M, Y', strtotime($order['created_at'])) ?></p>
        <p>Status: <strong><?= htmlspecialchars($order['order_status']) ?></strong></p>
        <p>Payment Method: <strong><?= htmlspecialchars($order['payment_method']) ?></strong></p>
    </div>

    <div class="order-items-container" style="padding:50px;">
        <?php foreach ($items as $item): ?>
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:20px; border-bottom:1px solid; padding-bottom:10px;">
                <div style="display:flex; align-items:center; gap:10px;">
                    <img src="image/products/<?= htmlspecialchars($item['image']) ?>" 
                         style="width:60px; height:60px; object-fit:cover; border-radius:5px;"
                         alt="<?= htmlspecialchars($item['name']) ?>">
                    <div>
                        <div><?= htmlspecialchars($item['name']) ?></div>
                        <?php if(!empty($item['custom_details'])): ?>
                            <?php $custom = json_decode($item['custom_details'], true); ?>
                            <small style="color:#555;">
                                <?php foreach($custom as $key=>$value): ?>
                                    <?php if(!empty($value)): ?>
                                        <?= ucfirst($key) ?>: <?= htmlspecialchars($value) ?>; 
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </small>
                        <?php endif; ?>
                    </div>
                </div>
                <span>GHS <?= number_format($item['price'],2) ?> × <?= $item['quantity'] ?></span>
            </div>
        <?php endforeach; ?>

        <hr style="margin:20px 0; border-color:#d6c28f;">
        <p style="text-align:right; font-weight:600;">
            Total: GHS <?= number_format($order['total_amount'], 2) ?>
        </p>
    </div>
    <a href="orders.php" class="btn-outline" style="margin-left:50px;">Back to Orders</a>
</section>

<script src="js/darkmode.js" defer></script>
<footer class="footer">
    <p>© <?= date('Y') ?> Beads & Beyond • Handmade with Love</p>
</footer>
</body>
</html>
