<?php
require 'php/db.php';
require 'php/auth_session.php';
require_login();

/* Ensure cart exists */
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit;
}

$cart = $_SESSION['cart'];
$user_id = $_SESSION['user_id'];
$total_amount = 0;

/* Calculate total */
foreach ($cart as $item) {
    $total_amount += $item['price'] * $item['quantity'];
}

/* Handle order submission */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {
        $pdo->beginTransaction();

        // Get selected payment method, default to COD
        $payment_method = $_POST['payment_method'] ?? 'COD';

        /* Create order with payment_method */
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount, payment_method) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $total_amount, $payment_method]);
        $order_id = $pdo->lastInsertId();

        /* Insert order items */
        $stmtItem = $pdo->prepare("
            INSERT INTO order_items
            (order_id, product_id, quantity, custom_details, price)
            VALUES (?, ?, ?, ?, ?)
        ");
        foreach ($cart as $item) {
            $custom_details = json_encode($item['custom']);
            $stmtItem->execute([$order_id, $item['product_id'], $item['quantity'], $custom_details, $item['price']]);
        }

        $pdo->commit();
        $_SESSION['current_order_id'] = $order_id;

        /* Clear cart */
        unset($_SESSION['cart']);

        /* Redirect to Thank You page */
        header('Location: thank_you.php');
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        $error = "Something went wrong. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout | Beads & Beyond</title>
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

<!-- CHECKOUT -->
<section class="checkout-page">
    <div class="section-header">
        <h2>Checkout</h2>
        <p>Review your order before payment</p>
    </div>

    <?php if (!empty($error)): ?>
        <div class="error-msg"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="checkout-container" style="display:flex; gap:40px; padding:50px; flex-wrap:wrap;">

        <!-- Left: Order Summary -->
        <div class="checkout-summary-container" style="flex:1; min-width:500px;">
            <h3>Your Order</h3><br>

            <?php foreach ($cart as $item): ?>
                <div class="checkout-item" style="display:flex; align-items:center; justify-content:space-between; margin-bottom:12px;">
                    <div style="display:flex; align-items:center; gap:10px;">
                        <img src="image/products/<?= htmlspecialchars($item['image']) ?>"
                             alt="<?= htmlspecialchars($item['name']) ?>"
                             style="width:70px; height:70px; object-fit:cover; border-radius:5px;">
                        <div>
                            <div><?= htmlspecialchars($item['name']) ?></div>
                            <?php if(!empty($item['custom'])): ?>
                                <small style="color:#555;">
                                    <?php foreach($item['custom'] as $key=>$value): ?>
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
            <p class="checkout-total" style="font-weight:600; text-align:right;">
                Total: GHS <?= number_format($total_amount, 2) ?>
            </p>
        </div>

        <!-- Right: Payment Options -->
        <div class="checkout-form-container" style="flex:2; min-width:300px; padding:50px;">
            <h3>Payment Options</h3><br>
            <p>Please choose your payment method:</p><br>

            <form method="POST" class="checkout-form">
                <label>
                    <input type="radio" name="payment_method" value="COD" checked>
                    Cash on Delivery
                </label><br><br>

                <label>
                    <input type="radio" name="payment_method" value="Bank Transfer">
                    Bank Transfer
                </label><br><br>

                <div id="bank-details" style="display:none; margin-top:10px;">
                    <p>Transfer the total amount to the following account:</p>
                    <ul>
                        <li>Bank: EcoBank</li>
                        <li>Account Name: Deborah Maxime</li>
                        <li>Account Number: 1234567890</li>
                    </ul>
                    <p>After payment, send proof to: <strong>deborahagossou422@gmail.com</strong></p>
                </div>

                <button type="submit" class="btn"
                        style="padding:12px 25px; background:#d4af37; color:#fff; border:none; border-radius:8px; font-weight:600; cursor:pointer;">
                    Confirm Order
                </button>
            </form>
        </div>

    </div>
</section>

<script>
    // Show bank details only if Bank Transfer is selected
    const codRadio = document.querySelector('input[value="COD"]');
    const bankRadio = document.querySelector('input[value="Bank Transfer"]');
    const bankDetails = document.getElementById('bank-details');

    codRadio.addEventListener('change', () => bankDetails.style.display = 'none');
    bankRadio.addEventListener('change', () => bankDetails.style.display = 'block');
</script>
<script src="js/darkmode.js" defer></script>

<!-- FOOTER -->
<footer class="footer">
    <p>© <?= date('Y') ?> Beads & Beyond • Handmade with Love</p>
</footer>
</body>
</html>
