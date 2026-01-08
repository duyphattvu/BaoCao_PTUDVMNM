<?php
require_once 'includes/config.php';

echo "<h2>Kiểm tra bảng messages</h2>";

// Kiểm tra bảng tồn tại
$check = mysqli_query($conn, "SHOW TABLES LIKE 'messages'");
if (mysqli_num_rows($check) == 0) {
    echo "<p style='color: red;'>❌ Bảng messages chưa tồn tại! Hãy chạy <a href='setup-chat.php'>setup-chat.php</a></p>";
    exit;
}

echo "<p style='color: green;'>✅ Bảng messages đã tồn tại</p>";

// Đếm tin nhắn
$count = mysqli_query($conn, "SELECT COUNT(*) as total FROM messages");
$total = mysqli_fetch_assoc($count)['total'];

echo "<p>Tổng số tin nhắn: <strong>$total</strong></p>";

if ($total > 0) {
    echo "<h3>Danh sách tin nhắn:</h3>";
    echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
    echo "<tr><th>ID</th><th>User ID</th><th>Người gửi</th><th>Tin nhắn</th><th>Thời gian</th></tr>";
    
    $messages = mysqli_query($conn, "SELECT * FROM messages ORDER BY created_at DESC LIMIT 20");
    while ($msg = mysqli_fetch_assoc($messages)) {
        echo "<tr>";
        echo "<td>{$msg['id']}</td>";
        echo "<td>{$msg['user_id']}</td>";
        echo "<td style='color: " . ($msg['sender_type'] == 'admin' ? 'blue' : 'green') . ";'><strong>{$msg['sender_type']}</strong></td>";
        echo "<td>" . htmlspecialchars($msg['message']) . "</td>";
        echo "<td>{$msg['created_at']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color: orange;'>⚠️ Chưa có tin nhắn nào. Hãy thử gửi tin nhắn từ mini chat box.</p>";
}

echo "<br><a href='admin/chat-admin.php'>Vào Admin Chat</a> | ";
echo "<a href='index.php'>Về trang chủ</a>";
?>
