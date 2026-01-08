<?php
// Script sửa encoding cho database
require_once 'includes/config.php';

echo "<!DOCTYPE html>";
echo "<html><head><meta charset='UTF-8'><title>Sửa Encoding Database</title></head><body>";
echo "<h1>Đang sửa encoding cho database...</h1>";

// Đặt charset cho kết nối
mysqli_query($conn, "SET NAMES utf8mb4");
mysqli_set_charset($conn, "utf8mb4");

// Sửa charset cho database
mysqli_query($conn, "ALTER DATABASE trangsuc_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
echo "<p>✓ Đã sửa charset cho database</p>";

// Danh sách các bảng cần sửa
$tables = ['categories', 'products', 'users', 'orders', 'order_details', 'banners', 'news', 'contacts', 'messages'];

foreach ($tables as $table) {
    // Kiểm tra bảng có tồn tại không
    $check = mysqli_query($conn, "SHOW TABLES LIKE '$table'");
    if (mysqli_num_rows($check) > 0) {
        // Sửa charset cho bảng
        mysqli_query($conn, "ALTER TABLE $table CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        echo "<p>✓ Đã sửa charset cho bảng: <strong>$table</strong></p>";
    }
}

echo "<h2 style='color: green;'>Hoàn tất! Database đã được sửa encoding.</h2>";
echo "<p><a href='index.php' style='padding: 10px 20px; background: #d4af37; color: white; text-decoration: none; border-radius: 5px;'>Về trang chủ</a></p>";
echo "</body></html>";

mysqli_close($conn);
?>
