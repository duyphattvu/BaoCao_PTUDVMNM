<?php
require_once 'includes/config.php';

// Kiểm tra và xóa bảng cũ nếu có lỗi cấu trúc
mysqli_query($conn, "DROP TABLE IF EXISTS `messages_backup`");
mysqli_query($conn, "RENAME TABLE `messages` TO `messages_backup`") or true;

// Tạo bảng messages mới
$sql = "CREATE TABLE IF NOT EXISTS `messages` (
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

if (mysqli_query($conn, $sql)) {
    echo "<div style='font-family: Arial; padding: 20px; max-width: 600px; margin: 50px auto; background: #f0f9ff; border-radius: 10px; border: 2px solid #3b82f6;'>";
    echo "<h2 style='color: #1e40af; margin-top: 0;'>✅ Cài đặt Chat thành công!</h2>";
    echo "<p style='color: #1e3a8a;'>Bảng <code>messages</code> đã được tạo trong database.</p>";
    
    // Test insert
    $test_insert = mysqli_query($conn, "INSERT INTO messages (user_id, sender_type, message) VALUES (1, 'admin', 'Chào mừng! Hệ thống chat đã sẵn sàng.')");
    if ($test_insert) {
        echo "<p style='color: #16a34a;'>✅ Test insert thành công - Chat hoạt động bình thường!</p>";
        mysqli_query($conn, "DELETE FROM messages WHERE message = 'Chào mừng! Hệ thống chat đã sẵn sàng.'");
    } else {
        echo "<p style='color: #dc2626;'>⚠️ Test insert thất bại: " . mysqli_error($conn) . "</p>";
    }
    
    echo "<hr style='margin: 20px 0; border: none; border-top: 1px solid #bfdbfe;'>";
    echo "<h3 style='color: #1e40af;'>Bây giờ bạn có thể:</h3>";
    echo "<ul style='color: #1e3a8a;'>";
    echo "<li>👤 <strong>Người dùng:</strong> Click icon chat góc phải màn hình để nhắn tin</li>";
    echo "<li>👨‍💼 <strong>Admin:</strong> Vào menu 'Chat Hỗ Trợ' để trả lời khách hàng</li>";
    echo "</ul>";
    echo "<div style='margin-top: 20px;'>";
    echo "<a href='index.php' style='display: inline-block; padding: 12px 24px; background: #d4af37; color: white; text-decoration: none; border-radius: 5px; margin-right: 10px;'>🏠 Về Trang Chủ</a>";
    echo "<a href='admin/chat-admin.php' style='display: inline-block; padding: 12px 24px; background: #667eea; color: white; text-decoration: none; border-radius: 5px;'>💬 Vào Admin Chat</a>";
    echo "</div>";
    echo "</div>";
} else {
    echo "<div style='font-family: Arial; padding: 20px; max-width: 600px; margin: 50px auto; background: #fef2f2; border-radius: 10px; border: 2px solid #dc2626;'>";
    echo "<h2 style='color: #991b1b; margin-top: 0;'>❌ Lỗi cài đặt!</h2>";
    echo "<p style='color: #7f1d1d;'><strong>Chi tiết lỗi:</strong></p>";
    echo "<pre style='background: white; padding: 15px; border-radius: 5px; overflow-x: auto;'>" . mysqli_error($conn) . "</pre>";
    echo "<h3 style='color: #991b1b;'>Cách khắc phục:</h3>";
    echo "<ol style='color: #7f1d1d;'>";
    echo "<li>Kiểm tra MySQL đã chạy chưa (XAMPP Control Panel → Start MySQL)</li>";
    echo "<li>Kiểm tra file <code>includes/config.php</code> có đúng thông tin database không</li>";
    echo "<li>Thử tải lại trang này (F5)</li>";
    echo "</ol>";
    echo "<a href='index.php' style='display: inline-block; margin-top: 15px; padding: 12px 24px; background: #dc2626; color: white; text-decoration: none; border-radius: 5px;'>← Quay Lại</a>";
    echo "</div>";
}
?>
