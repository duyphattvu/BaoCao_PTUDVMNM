<?php
// Endpoint xác nhận thanh toán cho admin
require_once 'check_admin.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
if (!$order_id) {
    echo json_encode(['success' => false, 'message' => 'Missing order ID']);
    exit;
}

// Verify order exists and belongs to awaiting_verification status
$res = mysqli_query($conn, "SELECT id, order_code, payment_status FROM orders WHERE id = $order_id LIMIT 1");
if (!$res || mysqli_num_rows($res) === 0) {
    echo json_encode(['success' => false, 'message' => 'Order not found']);
    exit;
}
$order = mysqli_fetch_assoc($res);

// Only allow confirmation if awaiting_verification
if ($order['payment_status'] !== 'awaiting_verification') {
    echo json_encode(['success' => false, 'message' => 'Order is not awaiting verification']);
    exit;
}

// Update order to mark as paid
$admin_id = $_SESSION['admin_id'] ?? 0;
$update_sql = "UPDATE orders SET payment_status = 'paid', confirmed_by = $admin_id, confirmed_at = NOW() WHERE id = $order_id";

if (mysqli_query($conn, $update_sql)) {
    echo json_encode(['success' => true, 'message' => 'Thanh toán đã được xác nhận', 'order_code' => $order['order_code']]);
    exit;
} else {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . mysqli_error($conn)]);
    exit;
}
