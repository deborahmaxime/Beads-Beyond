<?php
require 'php/db.php';
require 'php/auth_session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About Us - Beads & Beyond</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- =====================================
     HEADER
===================================== -->
<header class="main-header">
    
        <div class="logo">
            Beads & Beyond
            <h6>Where Beauty Meets Craftsmanship</h6>
           
        </div>

        <nav class="main-nav">
            <a href="index.php">Home</a>
            <?php if(!is_logged_in()): ?>
                <a href="php/login.php" class="btn-outline">Login</a>
            <?php endif; ?>
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
     HERO SECTION
===================================== -->
<section class="hero-slider" style="height: 40vh; display: flex; align-items: center; justify-content: center; background: url('image/auth/img4.jpeg') no-repeat center center; background-size: cover;">
    <div style="background: rgba(0,0,0,0.3); padding: 2rem 3rem; border-radius: 15px; color: white; text-align: center;">
        <h1>About Beads & Beyond</h1>
        <p>Handcrafted jewelry & accessories designed with love and passion</p>
    </div>
</section>

<!-- =====================================
     ABOUT CONTENT
===================================== -->
<section class="about-content">
    <div class="section-header">
        <h2>Our Story</h2>
        <p>Learn more about our journey and passion for handcrafted jewelry</p>
    </div>

    <div class="about-text" style="max-width: 900px; margin: auto; text-align: center; line-height: 1.8; color: var(--black);">
        <p>Beads & Beyond started with a simple idea: to create jewelry and accessories that celebrate individuality and craftsmanship. Every piece is handcrafted with love, blending traditional techniques with modern designs.</p>
        <p>Our mission is to bring beauty and uniqueness to every customer, allowing you to express yourself through colors, materials, and intricate designs. From bracelets to beaded bags, each item tells a story.</p>
        <p>We believe in quality, creativity, and a personal touch in every item we create. Join us on this journey of elegance, art, and passion.</p>
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
