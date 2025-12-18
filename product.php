<?php
require 'php/db.php';
require 'php/auth_session.php';
require_login();

/* Validate product ID */
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: shop.php');
    exit;
}

$product_id = (int) $_GET['id'];

/* Fetch product */
$stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    header('Location: shop.php');
    exit;
}

/* Initialize cart */
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

/* Handle Add to Cart */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item = [
        'product_id' => $product['product_id'],
        'name'       => $product['name'],
        'price'      => $product['base_price'],
        'image'      => $product['image'],
        'quantity'   => (int) $_POST['quantity'],
        'custom'     => [
            'color'    => $_POST['color'] ?? '',
            'size'     => $_POST['size'] ?? '',
            'material' => $_POST['material'] ?? '',
            'notes'    => trim($_POST['notes'] ?? '')
        ]
    ];

    $_SESSION['cart'][] = $item;

    header('Location: cart.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($product['name']) ?> | Beads & Beyond</title>
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
     PRODUCT DETAILS
===================================== -->
<section class="product-details">
    <div class="products-grid" style="display: flex;">

        <!-- Image -->
        <div class="product-image" >
            <img src="image/products/<?= htmlspecialchars($product['image']) ?>" style="max-width: 400px;margin-left: 15%;border-radius: 5px"
                 alt="<?= htmlspecialchars($product['name']) ?>"><br>
        </div>

        <!-- Info -->
        <div class="product-info" style ="margin-left: 50px;"   >
            <h1><?= htmlspecialchars($product['name']) ?></h1>
            <p class="price"> Price: GHS <?= number_format($product['base_price'], 2) ?></p><br>
            <p class="description">Description: <?= nl2br(htmlspecialchars($product['description'])) ?></p>

            <!-- Add to Cart -->
            <form method="POST" class="custom-form">
            <!-- Customization -->
            <label style="display:block; margin-top:12px; font-weight:500;">Color</label>
            <select name="color" style="width:70%; padding:8px; border-radius:6px; ">
                <option value="">Default</option>
                <option>Gold</option>
                <option>Silver</option>
                <option>Rose Gold</option>
                <option>Mixed</option>
            </select>

            <label style="display:block; margin-top:12px; font-weight:500;">Size</label>
            <select name="size" style="width:70%; padding:8px; border-radius:6px; ">
                <option value="">Standard</option>
                <option>Small</option>
                <option>Medium</option>
                <option>Large</option>
            </select>

            <label style="display:block; margin-top:12px; font-weight:500;">Custom Notes</label>
            <textarea name="notes" placeholder="Any special request?"
                    style="width:70%; padding:8px; border-radius:6px; "></textarea>

            <label style="display:block; margin-top:12px; font-weight:500;">Quantity</label>
            <input type="number" name="quantity" min="1" value="1" required
                style="width:70%; padding:8px; border-radius:6px; ">

            <button type="submit"
                    style="margin-top:18px; width:30%; padding:10px; background:#d4af37; color:#fff; border:none; border-radius:8px; font-weight:600; cursor:pointer;">
                Add to Cart
            </button>
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
