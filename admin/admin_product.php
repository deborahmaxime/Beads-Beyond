<?php
require '../php/db.php';
require '../php/auth_session.php';
require_login();

// Block non-admin users
if (!is_logged_in() || $_SESSION['is_admin'] !== 1) {
    header('Location: ../index.php');
    exit;
}

// Get selected category
$category = $_GET['category'] ?? null;
$products = [];

if ($category) {
    $stmt = $pdo->prepare("
        SELECT p.*, c.name AS category_name
        FROM products p
        JOIN categories c ON p.category_id = c.category_id
        WHERE c.name = ?
        ORDER BY p.created_at DESC
    ");
    $stmt->execute([$category]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Manage Products | Beads & Beyond</title>
    <link rel="stylesheet" href="../css/style.css">
    <!-- // AI generated-->
    <style>
        
        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2rem;
            background: var(--white);
        }

        .products-table th,
        .products-table td {
            padding: 0.8rem;
            border: 1px solid #ddd;
            text-align: center;
            font-size: 0.9rem;
        }

        .products-table th {
            background: var(--gold);
            color: var(--white);
        }

        .products-table img {
            width: 80px;
            border-radius: 8px;
        }

        .top-actions {
            margin: 1rem 0;
        }

        .category-grid a {
            display: inline-block;
            margin: 0.4rem;
            padding: 0.6rem 1rem;
            background: var(--gold);
            color: #fff;
            border-radius: 6px;
            text-decoration: none;
        }

        .category-grid a:hover {
            opacity: 0.85;
        }
        
    </style>
    <!-- // AI generated stops here-->
</head>
<body>

<!-- HEADER -->
<header class="main-header">
    <div class="logo">
        Beads & Beyond
        <h6 class="logo-tagline">Admin Panel</h6>
    </div>

    <nav class="main-nav">
        <a href="admin.php">Dashboard</a>
        <a href="../php/logout.php" class="btn-outline">Logout</a>
        <button id="darkToggle" class="btn-small">Dark</button>
    </nav>
</header>

<!-- CATEGORY SELECTION -->
<section class="categories">
    <br><h1>Manage Products</h1>
    <p><br>Select a category to view, add, edit, or remove products</p>

    <br><div class="category-grid">
        <a href="admin_product.php?category=Bracelets">Bracelets</a>
        <a href="admin_product.php?category=Necklaces">Necklaces</a>
        <a href="admin_product.php?category=Earrings">Earrings</a>
        <a href="admin_product.php?category=Rings">Rings</a>
        <a href="admin_product.php?category=Waist Beads">Waist Beads</a>
        <a href="admin_product.php?category=Anklets">Anklets</a>
        <a href="admin_product.php?category=Beaded Bags">Beaded Bags</a>
        <a href="admin_product.php?category=Hair Accessories">Hair Accessories</a>
        <a href="admin_product.php?category=Pouches">Pouches</a>
    </div>
</section>

<?php if ($category): ?>
<section class="admin-dashboard">

    <div class="top-actions">
        <a href="add_product.php" class="btn-small">
            Add New Product to <?= htmlspecialchars($category) ?>
        </a>
    </div>

    <table class="products-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Category</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price (GHS)</th>
                <th>Image</th>
                <th>Customizable</th>
                <th>Stock</th>
                <th>Created</th>
                <th>Updated</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>

        <?php if (count($products) > 0): ?>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= htmlspecialchars($product['product_id']) ?></td>
                    <td><?= htmlspecialchars($product['category_name']) ?></td>
                    <td><?= htmlspecialchars($product['name']) ?></td>
                    <td><?= htmlspecialchars($product['description']) ?></td>
                    <td><?= number_format($product['base_price'], 2) ?></td>
                    <td>
                        <img src="../image/products/<?= htmlspecialchars($product['image']) ?>" alt="">
                    </td>
                    <td><?= $product['is_customizable'] ? 'Yes' : 'No' ?></td>
                    <td><?= htmlspecialchars($product['stock_quantity']) ?></td>
                    <td><?= htmlspecialchars($product['created_at']) ?></td>
                    <td><?= htmlspecialchars($product['updated_at']) ?></td>
                    <td>
                        <a href="edit_product.php?id=<?= $product['product_id'] ?>" class="btn-small">Edit</a>
                        <a href="admin_product.php?delete_id=<?= $product['product_id'] ?>&category=<?= urlencode($category) ?>"
                           class="btn-small"
                           onclick="return confirm('Delete this product?');">
                           Delete
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="11">
                    No products found in <strong><?= htmlspecialchars($category) ?></strong>.
                </td>
            </tr>
        <?php endif; ?>

        </tbody>
    </table>
</section>
<?php endif; ?>

<!-- FOOTER -->
<footer class="footer">
    <p>© <?= date('Y') ?> Beads & Beyond • Handmade with Love</p>
</footer>

<script src="../js/darkmode.js" defer></script>
</body>
</html>
