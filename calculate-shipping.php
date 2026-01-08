<?php
// API tính phí vận chuyển
require_once 'includes/config.php';
require_once 'includes/shipping-config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = isset($_POST['address']) ? trim($_POST['address']) : '';
    $total = isset($_POST['total']) ? floatval($_POST['total']) : 0;
    
    if (empty($address)) {
        echo json_encode([
            'success' => false,
            'message' => 'Vui lòng nhập địa chỉ'
        ]);
        exit;
    }
    
    $shipping_fee = calculateShippingFee($address, $total);
    $is_free = ($total >= FREE_SHIPPING_THRESHOLD);
    
    echo json_encode([
        'success' => true,
        'shipping_fee' => $shipping_fee,
        'shipping_fee_formatted' => number_format($shipping_fee),
        'total_amount' => $total + $shipping_fee,
        'total_amount_formatted' => number_format($total + $shipping_fee),
        'is_free_shipping' => $is_free,
        'free_shipping_threshold' => FREE_SHIPPING_THRESHOLD,
        'free_shipping_threshold_formatted' => number_format(FREE_SHIPPING_THRESHOLD)
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
}
?>
