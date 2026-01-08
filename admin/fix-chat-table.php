<?php
require_once 'check_admin.php';

echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Sửa Bảng Chat</title></head><body>";
echo "<h1>Đang kiểm tra và sửa bảng messages...</h1>";

// Kiểm tra bảng messages có tồn tại không
$check = mysqli_query($conn, "SHOW TABLES LIKE 'messages'");

if (mysqli_num_rows($check) == 0) {
    // Tạo bảng mới
    $sql = "CREATE TABLE messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        sender_type ENUM('user', 'admin') NOT NULL,
        message TEXT NOT NULL,
        is_read TINYINT(1) DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_user_id (user_id),
        INDEX idx_sender_type (sender_type),
        INDEX idx_is_read (is_read)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    if (mysqli_query($conn, $sql)) {
        echo "<p style='color: green;'>✓ Đã tạo bảng messages thành công!</p>";
    } else {
        echo "<p style='color: red;'>✗ Lỗi tạo bảng: " . mysqli_error($conn) . "</p>";
    }
} else {
    echo "<p style='color: blue;'>○ Bảng messages đã tồn tại</p>";
    
    // Kiểm tra cấu trúc
    $columns = mysqli_query($conn, "SHOW COLUMNS FROM messages");
    echo "<h3>Cấu trúc bảng hiện tại:</h3><ul>";
    while ($col = mysqli_fetch_assoc($columns)) {
        echo "<li>{$col['Field']} - {$col['Type']}</li>";
    }
    echo "</ul>";
}

// Đếm số tin nhắn
$count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM messages"))['total'];
echo "<p><strong>Tổng số tin nhắn:</strong> $count</p>";

echo "<hr>";
echo "<p><a href='chat-admin.php' style='padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px;'>Quay lại Chat</a></p>";
echo "</body></html>";
?>
