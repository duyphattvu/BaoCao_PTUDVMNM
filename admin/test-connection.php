<?php
// Test đơn giản
echo "<!DOCTYPE html>";
echo "<html><head><title>Test Chat</title></head><body>";
echo "<h1>Chat Admin Test Page</h1>";
echo "<p>Nếu thấy dòng này = Apache hoạt động OK</p>";

require_once '../includes/config.php';

echo "<p>Database: " . (mysqli_ping($conn) ? "✅ Kết nối OK" : "❌ Lỗi") . "</p>";

// Test messages table
$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM messages");
if ($result) {
    $row = mysqli_fetch_assoc($result);
    echo "<p>Bảng messages: ✅ Có {$row['total']} tin nhắn</p>";
} else {
    echo "<p>Bảng messages: ❌ Lỗi - " . mysqli_error($conn) . "</p>";
}

// Test users
$users = mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='user'");
if ($users) {
    $row = mysqli_fetch_assoc($users);
    echo "<p>Users: ✅ Có {$row['total']} người dùng</p>";
}

echo "<hr>";
echo "<h2>Links để test:</h2>";
echo "<a href='chat-admin.php' style='display:block; padding:10px; background:#4CAF50; color:white; text-decoration:none; margin:10px 0;'>Chat Admin (Cần đăng nhập)</a>";
echo "<a href='../admin/' style='display:block; padding:10px; background:#2196F3; color:white; text-decoration:none; margin:10px 0;'>Vào Admin Panel</a>";
echo "<a href='test-chat.php' style='display:block; padding:10px; background:#FF9800; color:white; text-decoration:none; margin:10px 0;'>Test Chat (Không cần login)</a>";

echo "</body></html>";
?>
