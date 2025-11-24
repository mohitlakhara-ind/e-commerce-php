<?php
session_start();
require_once __DIR__ . '/../helpers/asset.php';

if(isset($_GET['id'])) {
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    unset($_SESSION['cart'][$id]);
}

header('Location: ' . site_url('cart'));
exit;