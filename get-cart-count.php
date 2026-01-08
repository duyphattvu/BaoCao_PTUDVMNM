<?php
// File trả về số lượng sản phẩm trong giỏ hàng
session_start();
header('Content-Type: application/json');

$cart_count = 0;

if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        if (isset($item['quantity'])) {
            $cart_count += (int)$item['quantity'];
        }
    }
}

echo json_encode(['count' => $cart_count]);
?>
