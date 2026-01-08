<?php
require_once 'includes/config.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$order_code = isset($_POST['order_code']) ? mysqli_real_escape_string($conn, $_POST['order_code']) : '';
if (empty($order_code)) {
    echo json_encode(['success' => false, 'message' => 'Missing order code']);
    exit;
}

// Validate order exists
$res = mysqli_query($conn, "SELECT id, payment_method FROM orders WHERE order_code = '$order_code' LIMIT 1");
if (!$res || mysqli_num_rows($res) === 0) {
    echo json_encode(['success' => false, 'message' => 'Order not found']);
    exit;
}
$order = mysqli_fetch_assoc($res);
$order_id = $order['id'];

// Handle uploaded proof (optional but recommended)
$proof_path = null;
if (isset($_FILES['transfer_proof']) && $_FILES['transfer_proof']['error'] !== UPLOAD_ERR_NO_FILE) {
    $file = $_FILES['transfer_proof'];
    $allowed = ['image/jpeg','image/png','application/pdf'];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'message' => 'Upload error: ' . $file['error']]);
        exit;
    }
    if (!in_array($file['type'], $allowed)) {
        echo json_encode(['success' => false, 'message' => 'Only JPG/PNG/PDF allowed']);
        exit;
    }
    if ($file['size'] > 6 * 1024 * 1024) {
        echo json_encode(['success' => false, 'message' => 'File size exceeds 6MB']);
        exit;
    }

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $name = uniqid('tf_', true) . '.' . $ext;
    $targetDir = __DIR__ . '/uploads/transfer_proofs/';
    if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
    $targetPath = $targetDir . $name;
    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        echo json_encode(['success' => false, 'message' => 'Cannot save uploaded file']);
        exit;
    }
    $proof_path = 'uploads/transfer_proofs/' . $name;
}

$transfer_amount = isset($_POST['transfer_amount']) ? floatval(str_replace(',','',$_POST['transfer_amount'])) : null;
$bank_name = isset($_POST['bank_name']) ? mysqli_real_escape_string($conn, trim($_POST['bank_name'])) : null;
$transaction_id = isset($_POST['transaction_id']) ? mysqli_real_escape_string($conn, trim($_POST['transaction_id'])) : null;
$transfer_date = isset($_POST['transfer_date']) ? mysqli_real_escape_string($conn, trim($_POST['transfer_date'])) : null;

// Update order: set payment_status to awaiting_verification and save provided fields
$updates = [];
$updates[] = "payment_status = 'awaiting_verification'";
if ($proof_path) $updates[] = "transfer_proof = '" . mysqli_real_escape_string($conn, $proof_path) . "'";
if (!is_null($transfer_amount)) $updates[] = "transfer_amount = " . floatval($transfer_amount);
if ($bank_name) $updates[] = "bank_name = '" . $bank_name . "'";
if ($transaction_id) $updates[] = "transaction_id = '" . $transaction_id . "'";
if ($transfer_date) $updates[] = "transfer_date = '" . $transfer_date . "'";
$updates[] = "customer_confirmed_at = NOW()";

$sql = "UPDATE orders SET " . implode(', ', $updates) . " WHERE id = $order_id";
if (mysqli_query($conn, $sql)) {
    // Clear cart after successful confirmation
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    unset($_SESSION['cart']);
    
    // Optionally, notify admin here (email or internal notification)
    echo json_encode(['success' => true, 'message' => 'Đã ghi nhận thông tin chuyển khoản. Đơn hàng đang chờ xác thực.', 'order_code' => $order_code]);
    exit;
} else {
    echo json_encode(['success' => false, 'message' => 'Cập nhật đơn hàng thất bại: ' . mysqli_error($conn)]);
    exit;
}
