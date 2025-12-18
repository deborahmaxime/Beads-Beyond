<?php
require 'db.php';
require 'auth_session.php';
require_login();

$user_id = $_SESSION['user_id'];

// Get or create cart
$stmt = $pdo->prepare("SELECT * FROM cart WHERE user_id = ?");
$stmt->execute([$user_id]);
$cart = $stmt->fetch();

if (!$cart) {
    $stmt = $pdo->prepare("INSERT INTO cart (user_id) VALUES (?)");
    $stmt->execute([$user_id]);
    $cart_id = $pdo->lastInsertId();
} else {
    $cart_id = $cart['cart_id'];
}

// Handle actions
$action = $_POST['action'] ?? '';

if ($action === 'add') {
    $product_id = $_POST['product_id'];
    $quantity = max(1, intval($_POST['quantity']));
    $custom_details = $_POST['custom_details'] ?? '';
    $price = $_POST['price'];

    // Check if product already in cart
    $stmt = $pdo->prepare("SELECT * FROM cart_items WHERE cart_id = ? AND product_id = ?");
    $stmt->execute([$cart_id, $product_id]);
    $item = $stmt->fetch();

    if ($item) {
        $stmt = $pdo->prepare("UPDATE cart_items SET quantity = quantity + ?, custom_details = ?, price = ? WHERE cart_item_id = ?");
        $stmt->execute([$quantity, $custom_details, $price, $item['cart_item_id']]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO cart_items (cart_id, product_id, quantity, custom_details, price) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$cart_id, $product_id, $quantity, $custom_details, $price]);
    }

} elseif ($action === 'update') {
    $cart_item_id = $_POST['cart_item_id'];
    $quantity = max(1, intval($_POST['quantity']));
    $stmt = $pdo->prepare("UPDATE cart_items SET quantity = ? WHERE cart_item_id = ?");
    $stmt->execute([$quantity, $cart_item_id]);

} elseif ($action === 'remove') {
    $cart_item_id = $_POST['cart_item_id'];
    $stmt = $pdo->prepare("DELETE FROM cart_items WHERE cart_item_id = ?");
    $stmt->execute([$cart_item_id]);
}

header('Location: ../cart.php');
exit;
?>
