<?php
require 'php/auth_session.php';
require_login();

/* Validate cart index */
if (!isset($_GET['index']) || !isset($_SESSION['cart'][$_GET['index']])) {
    header('Location: cart.php');
    exit;
}

$index = (int) $_GET['index'];
$item  = $_SESSION['cart'][$index];

/* Handle update */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $_SESSION['cart'][$index]['quantity'] = (int) $_POST['quantity'];

    $_SESSION['cart'][$index]['custom'] = [
        'color'    => $_POST['color'] ?? '',
        'size'     => $_POST['size'] ?? '',
        'material' => $item['custom']['material'] ?? '',
        'notes'    => trim($_POST['notes'] ?? '')
    ];

    header('Location: cart.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit <?= htmlspecialchars($item['name']) ?> | Beads & Beyond</title>
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
        <button id="darkToggle" class="btn-small">Dark</button>
    </nav>

</header>

<!-- =====================================
     EDIT PRODUCT
===================================== -->
<section class="product-details">
    <div class="products-grid">

        <!-- Image -->
        <div class="product-image">
            <img src="image/products/<?= htmlspecialchars($item['image']) ?>"
                 style="max-width: 500px; margin-left: 15%; border-radius: 5px"
                 alt="<?= htmlspecialchars($item['name']) ?>"><br>
        </div>

        <!-- Info -->
        <div class="product-info">
            <h1>Edit <?= htmlspecialchars($item['name']) ?></h1>
            <p class="price"> Price: GHS <?= number_format($item['price'], 2) ?></p><br>

            <!-- Edit Cart Form -->
            <form method="POST" class="custom-form">

                <label style="display:block; margin-top:12px; font-weight:500;">Color</label>
                <select name="color" style="width:70%; padding:8px; border-radius:6px;">
                    <option value="">Default</option>
                    <option <?= ($item['custom']['color'] ?? '') === 'Gold' ? 'selected' : '' ?>>Gold</option>
                    <option <?= ($item['custom']['color'] ?? '') === 'Silver' ? 'selected' : '' ?>>Silver</option>
                    <option <?= ($item['custom']['color'] ?? '') === 'Rose Gold' ? 'selected' : '' ?>>Rose Gold</option>
                    <option <?= ($item['custom']['color'] ?? '') === 'Mixed' ? 'selected' : '' ?>>Mixed</option>
                </select>

                <label style="display:block; margin-top:12px; font-weight:500;">Size</label>
                <select name="size" style="width:70%; padding:8px; border-radius:6px;">
                    <option value="">Standard</option>
                    <option <?= ($item['custom']['size'] ?? '') === 'Small' ? 'selected' : '' ?>>Small</option>
                    <option <?= ($item['custom']['size'] ?? '') === 'Medium' ? 'selected' : '' ?>>Medium</option>
                    <option <?= ($item['custom']['size'] ?? '') === 'Large' ? 'selected' : '' ?>>Large</option>
                </select>

                <label style="display:block; margin-top:12px; font-weight:500;">Custom Notes</label>
                <textarea name="notes"
                          style="width:70%; padding:8px; border-radius:6px;"
                          placeholder="Any special request?"><?= htmlspecialchars($item['custom']['notes'] ?? '') ?></textarea>

                <label style="display:block; margin-top:12px; font-weight:500;">Quantity</label>
                <input type="number" name="quantity" min="1"
                       value="<?= (int) $item['quantity'] ?>" required
                       style="width:70%; padding:8px; border-radius:6px;">

                <button type="submit"
                        style="margin-top:18px; width:30%; padding:10px;
                               background:#d4af37; color:#fff; border:none;
                               border-radius:8px; font-weight:600; cursor:pointer;">
                    Update Cart
                </button>

                <a href="cart.php"
                   style="display:inline-block; margin-left:10px;
                          margin-top:18px; padding:10px 14px;
                          border:1px solid #d4af37; border-radius:8px;
                          color:#d4af37; text-decoration:none;">
                    Cancel
                </a>
            </form>

        </div>

    </div>
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
