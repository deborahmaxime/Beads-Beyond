<?php
require 'php/db.php';
require 'php/auth_session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Beads & Beyond | Where Beauty Meets Craftsmanship</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/slider.js" defer></script>
</head>
<body>

<!-- ================= HEADER ================= -->
<header class="main-header">
    <div class="logo">Beads & Beyond
        <h6>Where Beauty Meets Craftsmanship</h6>
    </div>

    <nav>
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

<!-- ================= HERO SECTION ================= -->
<section class="hero-slider" style="height: 85vh; display: flex; align-items: center; background: url('image/auth/img7.jpeg') no-repeat center center; background-size: cover;">
    <div class="slide active">
        <h1>Handcrafted Jewelry, Made Just for You</h1>
        <p>Bracelets • Waist Beads • Anklets • Beaded Bags</p>
        <a href="shop.php" class="btn">Shop Now</a>
    </div>

    <div class="slide">
        <h1>Customize Your Style</h1>
        <p>Choose colors, materials & charms</p>
        <a href="shop.php" class="btn">Customize Now</a>
    </div>

    <div class="slide">
        <h1>Where Beauty Meets Craftsmanship</h1>
        <p>Made with love. Designed for you.</p>
        <a href="shop.php" class="btn">Explore Collection</a>
    </div>
</section>

<!-- ================= CATEGORIES ================= -->
<section class="categories">
    <h2>Shop by Category</h2>
    <div class="category-grid">
        <a href="shop.php?category=Bracelets">Bracelets</a>
        <a href="shop.php?category=Waist Beads">Waist Beads</a>
        <a href="shop.php?category=Anklets">Anklets</a>
        <a href="shop.php?category=Beaded Bags">Beaded Bags</a>
        <a href="shop.php?category=Necklaces">Necklaces</a>
        <a href="shop.php?category=Earrings">Earrings</a>
        <a href="shop.php?category=Rings">Rings</a>
        <a href="shop.php?category=Hair Accessories">Hair Accessories</a>
        <a href="shop.php?category=Pouches">Pouches</a>
        <a href="shop.php?category=Lifestyle Accessories">Lifestyle Accessories</a>
        <a href="shop.php?category=Special & Custom Pieces">Special & Custom Pieces</a>
    </div>
</section>

<!-- ================= FEATURED PRODUCTS ================= -->
<section class="featured-products">
    <h2>Featured Pieces</h2>
    <div class="products-grid">
        <?php
        // Fetch 4 latest products
        $stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC LIMIT 3");
        while ($product = $stmt->fetch()):
        ?>
        <div class="product-card">
            <img src="image/products/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
            <h3><?= htmlspecialchars($product['name']) ?></h3>
            <p>GHS <?= number_format($product['base_price'],2) ?></p>
            <a href="product.php?id=<?= $product['product_id'] ?>" class="btn-small">View</a>
        </div>
        <?php endwhile; ?>
    </div>
</section>

<script src="js/darkmode.js" defer></script>

<!-- ================= FOOTER ================= -->
<footer>
    <p>© <?= date('Y') ?> Beads & Beyond • Handmade with Love</p>
</footer>

</body>
</html>
