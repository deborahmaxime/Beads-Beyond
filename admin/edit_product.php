<?php
require '../php/db.php';
require '../php/auth_session.php';

// Admin-only check
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: ../php/login.php');
    exit;
}

$error = '';
$success = '';

// Get product ID from query
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: admin_product.php');
    exit;
}
$product_id = $_GET['id'];

// Fetch categories
$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();

// Fetch product info
$stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();
if (!$product) {
    header('Location: admin_product.php');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_id     = trim($_POST['category_id']);
    $name            = trim($_POST['name']);
    $description     = trim($_POST['description']);
    $base_price      = trim($_POST['base_price']);
    $stock_quantity  = trim($_POST['stock_quantity']);
    $is_customizable = isset($_POST['is_customizable']) ? 1 : 0;

    if (empty($category_id) || empty($name) || empty($description) || !is_numeric($base_price) || !is_numeric($stock_quantity)) {
        $error = "All fields are required and must be valid.";
    }

    if (!$error) {
        // AI generated
        // Handle image upload if a new file is provided
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $allowed_ext  = ['jpg','jpeg','png','gif'];
            $file_name    = $_FILES['image']['name'];
            $file_tmp     = $_FILES['image']['tmp_name'];
            $file_ext     = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if (!in_array($file_ext, $allowed_ext)) {
                $error = "Invalid image format.";
            } else {
                $upload_dir = '../image/products/';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

                $new_name = uniqid('prod_', true) . '.' . $file_ext;
                $destination = $upload_dir . $new_name;

                if (!move_uploaded_file($file_tmp, $destination)) {
                    $error = "Failed to upload image.";
                } else {
                    // Delete old image file if exists
                    if ($product['image'] && file_exists($upload_dir . $product['image'])) {
                        unlink($upload_dir . $product['image']);
                    }
                    $product['image'] = $new_name;
                }
            }
        }

        if (!$error) {
            $stmt = $pdo->prepare("
                UPDATE products SET
                    category_id = ?, 
                    name = ?, 
                    description = ?, 
                    base_price = ?, 
                    image = ?, 
                    is_customizable = ?, 
                    stock_quantity = ?, 
                    updated_at = NOW()
                WHERE product_id = ?
            ");
            // AI generated stops here
            $stmt->execute([
                $category_id,
                $name,
                $description,
                $base_price,
                $product['image'],
                $is_customizable,
                $stock_quantity,
                $product_id
            ]);
            $success = "Product updated successfully!";
            // Refresh product info
            $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
            $stmt->execute([$product_id]);
            $product = $stmt->fetch();
            header('Location: admin_product.php');
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product | Beads & Beyond</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<header class="main-header">
    <div class="logo">
        Beads & Beyond
        <h6>Admin Panel</h6>
    </div>
    <nav class="main-nav">
        <a href="admin.php">Dashboard</a>
        <a href="admin_product.php">Products</a>
        <a href="../php/logout.php">Logout</a>
        <button id="darkToggle" class="btn-small">
             Dark
        </button>
    </nav>
</header>

<main class="form-card">
    <h2>Edit Product</h2>

    <?php if($error): ?>
        <div class="error-msg"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if($success): ?>
        <div class="success-msg"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>Category</label>
        <select name="category_id" required>
            <option value="">-- Select Category --</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['category_id'] ?>" <?= $cat['category_id'] == $product['category_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Product Name</label>
        <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>

        <label>Description</label>
        <textarea name="description" rows="4" required><?= htmlspecialchars($product['description']) ?></textarea>

        <label>Base Price ($)</label>
        <input type="number" step="0.01" name="base_price" value="<?= $product['base_price'] ?>" required>

        <label>Stock Quantity</label>
        <input type="number" name="stock_quantity" value="<?= $product['stock_quantity'] ?>" required>

        <label>
            <input type="checkbox" name="is_customizable" <?= $product['is_customizable'] ? 'checked' : '' ?>>
            Customizable
        </label>

        <label>Product Image</label>
        <?php if($product['image'] && file_exists('../image/products/' . $product['image'])): ?>
            <img src="../image/products/<?= $product['image'] ?>" alt="Product Image" style="width:120px; margin-bottom:0.5rem;">
        <?php endif; ?>
        <input type="file" name="image" accept="image/*">

        <button type="submit">Update Product</button>
    </form>
</main>
<script src="../js/darkmode.js" defer></script>
</body>
</html>
