<?php
require '../php/db.php';
require '../php/auth_session.php';

// Redirect non-admin users
if (!is_logged_in() || $_SESSION['is_admin'] !== 1) {
    header('Location: ../php/login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Beads & Beyond</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<!-- HEADER -->
<header class="main-header">
    
        <div class="logo">Beads & Beyond 
            <h6>Admin Panel</h6>
        </div>
        <nav class="main-nav">
            <a href="../index.php">Home</a>
            <a href="admin.php">Dashboard</a>
            <a href="../shop.php">Shop</a>
            <a href="../php/logout.php">Logout</a>
            <button id="darkToggle" class="btn-small">
                Dark
            </button>
        </nav>
    
</header>

<!-- ADMIN DASHBOARD -->
<section class="admin-dashboard">
    <div class="dashboard-header">
        <h1>Welcome, <?= htmlspecialchars($_SESSION['full_name']) ?></h1>
        <p>Manage your store efficiently from this panel</p>
    </div>

    <div class="dashboard-grid">
        <div class="dashboard-card">
            <h3>Manage Products</h3>
            <p>Add, edit, or remove products from the store</p>
            <a href="admin_product.php">Go</a>
        </div>

        <div class="dashboard-card">
            <h3>View Orders</h3>
            <p>See all customer orders and their statuses</p>
            <a href="view_orders.php">Go</a>
        </div>

        <div class="dashboard-card">
            <h3>Manage Users</h3>
            <p>View and manage user accounts and roles</p>
            <a href="manage_users.php">Go</a>
        </div>
    </div>
</section>
<script src="../js/darkmode.js" defer></script>
<!-- FOOTER -->
<footer class="footer">
    <p>© <?= date('Y') ?> Beads & Beyond • Handmade with Love</p>
</footer>

</body>
</html>
