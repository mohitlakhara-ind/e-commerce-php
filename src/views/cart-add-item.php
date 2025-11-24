<?php

session_start();

require __DIR__ . '/../helpers/asset.php';
require __DIR__ . '/../csrf.php';
require __DIR__ . '/db.php';

$redirect = $_POST['redirect'] ?? site_url('products');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['token']) && CSRF::validateToken($_POST['token'])) {
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $quantity = filter_input(INPUT_POST, 'quantity', FILTER_SANITIZE_NUMBER_INT);
    $quantity = ($quantity && $quantity > 0) ? $quantity : 1;

    if ($id) {
        $statement = $pdo->prepare("SELECT * FROM products WHERE id = ?");
        $statement->execute([$id]);
        $product = $statement->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            $images = @unserialize($product['images']) ?: [];
            $image = $images[0] ?? '';

            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            if (isset($_SESSION['cart'][$id])) {
                $_SESSION['cart'][$id]['quantity'] += $quantity;
            } else {
                $_SESSION['cart'][$id] = [
                    'id' => $product['id'],
                    'title' => $product['title'],
                    'price' => $product['price'],
                    'description' => $product['description'],
                    'category' => $product['category'],
                    'quantity' => $quantity,
                    'image' => $image,
                ];
            }
        }
    }
}

header('Location: ' . $redirect);
exit;

