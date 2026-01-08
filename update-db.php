<?php
// File chạy migration/update DB để thêm cột thanh toán chuyển khoản
require_once 'includes/config.php';

echo "<h2>Cập nhật Cơ sở dữ liệu - Thêm cột Thanh Toán</h2>";
echo "<p>Đang cập nhật...</p>";

$updates = [
    "ALTER TABLE orders ADD COLUMN IF NOT EXISTS payment_status VARCHAR(50) DEFAULT 'pending'",
    "ALTER TABLE orders ADD COLUMN IF NOT EXISTS transfer_proof VARCHAR(255) NULL",
    "ALTER TABLE orders ADD COLUMN IF NOT EXISTS transfer_amount DECIMAL(10,2) NULL",
    "ALTER TABLE orders ADD COLUMN IF NOT EXISTS transfer_date DATETIME NULL",
    "ALTER TABLE orders ADD COLUMN IF NOT EXISTS bank_name VARCHAR(100) NULL",
    "ALTER TABLE orders ADD COLUMN IF NOT EXISTS transaction_id VARCHAR(100) NULL",
    "ALTER TABLE orders ADD COLUMN IF NOT EXISTS customer_confirmed_at DATETIME NULL",
    "ALTER TABLE orders ADD COLUMN IF NOT EXISTS confirmed_by INT NULL",
    "ALTER TABLE orders ADD COLUMN IF NOT EXISTS confirmed_at DATETIME NULL",
];

$success_count = 0;
$error_count = 0;

foreach ($updates as $sql) {
    if (mysqli_query($conn, $sql)) {
        echo "<p style='color: green;'>✓ " . substr($sql, 0, 60) . "...</p>";
        $success_count++;
    } else {
        echo "<p style='color: red;'>✗ " . substr($sql, 0, 60) . "... - Lỗi: " . mysqli_error($conn) . "</p>";
        $error_count++;
    }
}

echo "<hr>";
echo "<p><strong>Kết quả: " . $success_count . " thành công, " . $error_count . " lỗi</strong></p>";

if ($error_count === 0) {
    echo "<p style='color: green; font-weight: bold;'>✓ Cập nhật thành công! Các cột đã được thêm vào bảng orders.</p>";
    echo "<p><a href='admin/orders.php'>Quay lại quản lý đơn hàng</a></p>";
} else {
    echo "<p style='color: orange;'>⚠ Có thể một số cột đã tồn tại hoặc có lỗi khác.</p>";
}

?>
