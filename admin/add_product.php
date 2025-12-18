<?php
require '../php/db.php';
require '../php/auth_session.php';
require_login();

// Admin-only check
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: ../php/login.php');
    exit;
}
// AI - generated
$error = '';
$success = '';

// Fetch categories
$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_id     = trim($_POST['category_id']);
    $name            = trim($_POST['name']);
    $description     = trim($_POST['description']);
    $base_price      = trim($_POST['base_price']);
    $stock_quantity  = trim($_POST['stock_quantity']);
    $is_customizable = isset($_POST['is_customizable']) ? 1 : 0;

    // Validate inputs
    if (
        empty($category_id) ||
        empty($name) ||
        empty($description) ||
        !is_numeric($base_price) ||
        !is_numeric($stock_quantity)
    ) {
        $error = "All fields are required and must be valid.";
    }

    if (!$error) {
        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $error = "Product image is required.";
        } else {
            $allowed_ext  = ['jpg','jpeg','png','gif'];
            $allowed_mime = ['image/jpeg','image/png','image/gif'];

            $file_name = $_FILES['image']['name'];
            $file_tmp  = $_FILES['image']['tmp_name'];
            $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $mime      = mime_content_type($file_tmp);

            if (!in_array($file_ext, $allowed_ext) || !in_array($mime, $allowed_mime)) {
                $error = "Invalid image format. Allowed: jpg, jpeg, png, gif.";
            } else {
                //  CORRECT ABSOLUTE PATH 
                $upload_dir = '/home/deborah.maxime/public_html/webapp/image/products/';

                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }

                $new_name    = uniqid('prod_', true) . '.' . $file_ext;
                $destination = $upload_dir . $new_name;

                if (!move_uploaded_file($file_tmp, $destination)) {
                    $error = "Failed to upload image.";
                } else {
                    // Save relative path for web use
                    $image_for_db = $new_name;
                    // AI generated stops here
                    $stmt = $pdo->prepare("
                        INSERT INTO products
                        (category_id, name, description, base_price, image, is_customizable, stock_quantity, created_at, updated_at)
                        VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
                    ");
                    $stmt->execute([
                        $category_id,
                        $name,
                        $description,
                        $base_price,
                        $image_for_db,
                        $is_customizable,
                        $stock_quantity
                    ]);

                    header('Location: admin_product.php');
                    exit;
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Add Product | Beads & Beyond</title>
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
    </nav>
    <button id="darkToggle" class="btn-small">Dark</button>
</header>

<div class="form-card">
    <h2>Add New Product</h2>

    <?php if ($error): ?>
        <div class="error-msg"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>Category</label>
        <select name="category_id" required>
            <option value="">-- Select Category --</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['category_id'] ?>">
                    <?= htmlspecialchars($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Product Name</label>
        <input type="text" name="name" required>

        <label>Description</label>
        <textarea name="description" rows="4" required></textarea>

        <label>Base Price ($)</label>
        <input type="number" step="0.01" name="base_price" required>

        <label>Stock Quantity</label>
        <input type="number" name="stock_quantity" required>

        <label>
            <input type="checkbox" name="is_customizable" checked> Customizable
        </label>

        <label>Product Image</label>
        <input type="file" name="image" accept="image/*" required>

        <button type="submit">Add Product</button>
    </form>
</div>

<script src="../js/darkmode.js" defer></script>
</body>
</html>
