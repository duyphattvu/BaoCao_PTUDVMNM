<?php
require_once 'includes/config.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$order_code = isset($_GET['code']) ? mysqli_real_escape_string($conn, $_GET['code']) : '';

if (empty($order_code)) {
    header('Location: orders.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Kiểm tra đơn hàng có thuộc về user không và đang ở trạng thái pending
$sql = "SELECT * FROM orders WHERE order_code = '$order_code' AND user_id = $user_id AND status = 'pending'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // Cập nhật trạng thái đơn hàng thành cancelled
    $update_sql = "UPDATE orders SET status = 'cancelled' WHERE order_code = '$order_code'";
    
    if (mysqli_query($conn, $update_sql)) {
        $_SESSION['success_message'] = 'Đã hủy đơn hàng thành công!';
    } else {
        $_SESSION['error_message'] = 'Có lỗi xảy ra khi hủy đơn hàng!';
    }
} else {
    $_SESSION['error_message'] = 'Không thể hủy đơn hàng này!';
}

header('Location: orders.php');
exit;
?>
