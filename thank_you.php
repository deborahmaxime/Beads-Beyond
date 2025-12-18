<?php
require 'php/db.php';
require 'php/auth_session.php';
require_login();

/* Ensure order exists */
if (!isset($_SESSION['current_order_id'])) {
    header('Location: index.php');
    exit;
}

$order_id = $_SESSION['current_order_id'];

/* Fetch order */
$stmt = $pdo->prepare("SELECT * FROM orders WHERE order_id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch();

if (!$order) {
    header('Location: index.php');
    exit;
}

/* Fetch order items */
$stmtItems = $pdo->prepare("SELECT * FROM order_items WHERE order_id = ?");
$stmtItems->execute([$order_id]);
$order_items = $stmtItems->fetchAll();

/* Clear current order session */
unset($_SESSION['current_order_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Confirmed | Beads & Beyond</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- HEADER -->
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

<!-- THANK YOU SECTION -->
<section class="thank-you-page" style="padding:50px;">
    <div class="section-header">
        <h2>Thank You for Your Order!</h2>
        <p>Your order has been successfully placed.</p>
    </div>

    <div class="order-container" style="display:flex; gap:40px; flex-wrap:wrap;">

        <!-- Left: Order Summary -->
        <div class="order-summary" style="flex:1; min-width:500px;">
            <h3>Order Summary</h3><br>

            <?php foreach ($order_items as $item): ?>
                <?php $custom = json_decode($item['custom_details'], true); ?>
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:12px;">
                    <div style="display:flex; align-items:center; gap:10px;">
                        <?php
                        // Get product image
                        $stmtProd = $pdo->prepare("SELECT image, name FROM products WHERE product_id = ?");
                        $stmtProd->execute([$item['product_id']]);
                        $product = $stmtProd->fetch();
                        ?>
                        <img src="image/products/<?= htmlspecialchars($product['image']) ?>" 
                             alt="<?= htmlspecialchars($product['name']) ?>"
                             style="width:70px; height:70px; object-fit:cover; border-radius:5px;">
                        <div>
                            <div><?= htmlspecialchars($product['name']) ?></div>
                            <?php if(!empty($custom)): ?>
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

            <hr style="margin:15px 0; border-color:#d6c28f;">
            <p style="font-weight:600; text-align:right;">
                Total: GHS <?= number_format($order['total_amount'],2) ?>
            </p>

            <p style="margin-top:20px; font-weight:600;">
                Payment Method: <?= htmlspecialchars($order['payment_method']) ?>
            </p>

            <?php if($order['payment_method'] === 'Bank Transfer'): ?>
                <div style="margin-top:10px;">
                    <p>Please complete the bank transfer using the details below:</p>
                    <ul>
                        <li>Bank: EcoBank</li>
                        <li>Account Name: Deborah Maxime</li>
                        <li>Account Number: 1234567890</li>
                    </ul>
                    <p>Send proof of payment to: <strong>deborahagossou422@gmail.com</strong></p>
                </div>
            <?php endif; ?>

        </div>

        <!-- Right: Continue Shopping -->
        <div style="flex:1; min-width:250px; padding:20px;">
            <h3>Next Steps</h3><br>
            <p>You can continue shopping on our app.</p><br>
            <a href="shop.php" class="btn" style="padding:12px 25px; background:#d4af37; color:#fff; border:none; border-radius:8px; font-weight:600; cursor:pointer;">Continue Shopping</a>
        </div>

    </div>
</section>

<script src="js/darkmode.js" defer></script>

<!-- FOOTER -->
<footer class="footer">
    <p>© <?= date('Y') ?> Beads & Beyond • Handmade with Love</p>
</footer>
</body>
</html>
