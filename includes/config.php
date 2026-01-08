<?php
// File kết nối cơ sở dữ liệu

// Tắt cảnh báo deprecated cho PHP 8.0+
error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);

// Bước 1: Kết nối đến server MySQL
$kn = mysqli_connect("localhost", "root", "") or die("Lỗi: Không thể kết nối đến server MySQL. " . mysqli_connect_error());

// Bước 2: Chọn cơ sở dữ liệu cần dùng
$csdl = mysqli_select_db($kn, "trangsuc_db") or die("Lỗi: Không thể chọn cơ sở dữ liệu trangsuc_db. " . mysqli_error($kn));

// Bước 3: Thiết lập mã hóa UTF-8 để hiển thị tiếng Việt đúng
mysqli_query($kn, "SET NAMES 'utf8'");

// Tạo biến $conn (kết nối) để dùng trong các file khác
$conn = $kn;

// Tự động phát hiện đường dẫn gốc của website
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
$script_path = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$base_path = rtrim(preg_replace('#/(admin|includes).*$#', '', $script_path), '/') . '/';

define('DUONG_DAN_GOC', $protocol . $host . $base_path);
define('DUONG_DAN_ADMIN', $protocol . $host . $base_path . 'admin/');

// Giữ tên cũ để tương thích
define('BASE_URL', $protocol . $host . $base_path);
define('ADMIN_URL', $protocol . $host . $base_path . 'admin/');

// Khởi động phiên làm việc (session) để lưu thông tin đăng nhập
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Tự động tạo bảng messages nếu chưa có (cho chức năng chat)
$check_table = mysqli_query($conn, "SHOW TABLES LIKE 'messages'");
if (mysqli_num_rows($check_table) == 0) {
    $create_messages = "CREATE TABLE IF NOT EXISTS `messages` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `user_id` int(11) NOT NULL,
      `sender_type` enum('user','admin') NOT NULL DEFAULT 'user',
      `message` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
      `is_read` tinyint(1) NOT NULL DEFAULT 0,
      `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      KEY `user_id` (`user_id`),
      KEY `created_at` (`created_at`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";
    
    mysqli_query($conn, $create_messages);
}
?>
