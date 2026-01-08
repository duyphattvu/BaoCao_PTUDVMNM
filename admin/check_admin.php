<?php
// File kiểm tra quyền truy cập Admin
// File này được gọi ở đầu mỗi trang admin để bảo mật

require_once '../includes/config.php'; // Nạp file kết nối database

// Kiểm tra người dùng đã đăng nhập và có quyền admin không
// Nếu chưa đăng nhập hoặc không phải admin thì chuyển về trang đăng nhập
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header('Location: ../login.php'); // Chuyển hướng về trang đăng nhập
    exit; // Dừng thực thi code
}
?>
