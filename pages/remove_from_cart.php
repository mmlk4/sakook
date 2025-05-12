<?php
session_start();

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    if (isset($_SESSION['cart'][$id])) {
        unset($_SESSION['cart'][$id]);
    }
}

$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

echo json_encode(['status' => 'success', 'cart_count' => $cart_count]);
?>