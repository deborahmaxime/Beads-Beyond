<?php
require 'php/db.php';
require 'php/auth_session.php';
require_login();

/* Initialize cart */
$cart = $_SESSION['cart'] ?? [];
$total = 0;

/* Handle remove item */
if (isset($_GET['remove']) && is_numeric($_GET['remove'])) {
    $index = (int) $_GET['remove'];
    if (isset($_SESSION['cart'][$index])) {
        unset($_SESSION['cart'][$index]);
        $_SESSION['cart'] = array_values($_SESSION['cart']); // reindex
    }
    header('Location: cart.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart | Beads & Beyond</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- =====================================
     HEADER
===================================== -->
<header class="main-header">
    
        <div class="logo">Beads & Beyond<br>
            <h6 class="welcome">Hi, <?= htmlspecialchars($_SESSION['full_name']) ?> <h6>
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
            <button id="darkToggle" class="btn-small">
                Dark
            </button>
        </nav>
    
</header>

<!-- =====================================
     CART CONTENT
===================================== -->
<section class="cart-page">
    <div class="section-header">
        <h2>Your Shopping Cart</h2>
        <p>Review your handcrafted selections</p>
    </div>

    <?php if (empty($cart)): ?>
        <p style="text-align:center;">Your cart is currently empty.</p>
        <div style="text-align:center; margin-top:2rem;">
            <a href="shop.php" class="btn-outline">Continue Shopping</a>
        </div>
    <?php else: ?>

    <div class="cart-container">

        <?php foreach ($cart as $index => $item): 
            $item_total = $item['price'] * $item['quantity'];
            $total += $item_total;
        ?>
            <div class="products-grid">

                <img src="image/products/<?= htmlspecialchars($item['image']) ?>" alt="" style="max-width: 400px;margin-left: 30%;border-radius: 5px;padding-bottom: 30px;">

                <div class="product-info">
                    <h3><?= htmlspecialchars($item['name']) ?></h3><br>

                    <p class="cart-price">
                        Quantity: GHS <?= number_format($item['price'], 2) ?> × <?= $item['quantity'] ?>
                    </p><br>

                    <!-- Customizations -->
                    <ul class="cart-custom">
                        <?php foreach ($item['custom'] as $key => $value): ?>
                            <?php if (!empty($value)): ?>
                                <li><strong><?= ucfirst($key) ?>:</strong> <?= htmlspecialchars($value) ?></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>

                    <p class="cart-subtotal">
                        Subtotal: <strong>GHS<?= number_format($item_total, 2) ?></strong>
                    </p><br>

                    <a href="cart.php?remove=<?= $index ?>" class="btn-small">
                        Remove
                    </a>
                    <a href="edit_cart.php?index=<?= $index ?>" class="btn-small">Edit</a>

                </div>
            </div>
        <?php endforeach; ?>

        <!-- Cart Summary -->
        <div class="cart-summary">
            <br><h3>Total: GHS <?= number_format($total, 2) ?></h3><br>

            <div class="cart-actions">
                <a href="shop.php" class="btn-outline">Continue Shopping</a>
                <a href="checkout.php" class="btn">Proceed to Checkout</a>
            </div><br>
        </div>

    </div>
    <?php endif; ?>
</section>
<script src="js/darkmode.js" defer></script>
<!-- =====================================
     FOOTER
===================================== -->
<footer class="footer">
    <p>© <?= date('Y') ?> Beads & Beyond • Handmade with Love</p>
</footer>

</body>
</html>
