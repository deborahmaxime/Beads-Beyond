<?php
require 'php/db.php';
require 'php/auth_session.php';
require_login(); // Redirects to login if not logged in

/* Category filter from URL */
$category_name = isset($_GET['category']) ? trim($_GET['category']) : null;

/* Search filter */
$search_term = isset($_GET['search']) ? trim($_GET['search']) : '';

$params = [];
$sql = "SELECT * FROM products";

if ($category_name) {
    // Get category_id from name
    $cat_stmt = $pdo->prepare("SELECT category_id FROM categories WHERE name = ?");
    $cat_stmt->execute([$category_name]);
    $cat = $cat_stmt->fetch();

    if ($cat) {
        $sql .= " WHERE category_id = ?";
        $params[] = $cat['category_id'];
    } else {
        $sql .= " WHERE 0"; // category doesn't exist
    }
}

if ($search_term) {
    if ($category_name && $cat) {
        $sql .= " AND name LIKE ?";
    } else {
        $sql .= " WHERE name LIKE ?";
    }
    $params[] = "%$search_term%";
}

$sql .= " ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shop | Beads & Beyond</title>
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

<!-- SHOP HEADER + SEARCH -->
<section class="shop-header">
    <div class="section-header">
        <h2><?= $category_name ? htmlspecialchars($category_name) : 'Our Collection' ?></h2>
        <p>Handcrafted jewelry designed with love</p>

        <!-- Search form -->
        <form method="GET" action="shop.php" class="shop-search">
            <?php if ($category_name): ?>
                <input type="hidden" name="category" value="<?= htmlspecialchars($category_name) ?>">
            <?php endif; ?>
            <input type="text" name="search" placeholder="Search products..." value="<?= htmlspecialchars($search_term) ?>">
            <button type="submit">Search</button>
        </form>
    </div>
</section>

<!-- PRODUCT LIST -->
<section class="shop-products">
    <div class="products-grid">
        <?php if ($stmt && $stmt->rowCount() > 0): ?>
            <?php while ($product = $stmt->fetch()): ?>
                <div class="product-card">
                    <img src="image/products/<?= htmlspecialchars($product['image']) ?>" 
                         alt="<?= htmlspecialchars($product['name']) ?>">

                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                    <p class="price">$<?= number_format($product['base_price'], 2) ?></p>

                    <a href="product.php?id=<?= $product['product_id'] ?>" class="btn-small">View Details</a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="text-align:center; grid-column:1/-1;">
                No products found<?= $search_term ? " for '" . htmlspecialchars($search_term) . "'" : "" ?>.
            </p>
        <?php endif; ?>
    </div>
</section>

<script src="js/darkmode.js" defer></script>

<!-- FOOTER -->
<footer class="footer">
    <p>© <?= date('Y') ?> Beads & Beyond • Handmade with Love</p>
</footer>

</body>
</html>
