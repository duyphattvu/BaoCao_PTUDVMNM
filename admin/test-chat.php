<?php
require_once '../includes/config.php';

// Kiểm tra bảng messages
$check = mysqli_query($conn, "SHOW TABLES LIKE 'messages'");
if (mysqli_num_rows($check) == 0) {
    die("<h2 style='color: red;'>❌ Bảng messages chưa tồn tại! <a href='../setup-chat.php'>Chạy setup</a></h2>");
}

// Lấy 10 tin nhắn mới nhất
$messages = mysqli_query($conn, "
    SELECT m.*, u.fullname, u.email 
    FROM messages m 
    LEFT JOIN users u ON m.user_id = u.id 
    ORDER BY m.created_at DESC 
    LIMIT 10
");

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Test Chat</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: Arial; padding: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; }
        .message { padding: 15px; margin: 10px 0; border-radius: 8px; }
        .user { background: #e3f2fd; border-left: 4px solid #2196f3; }
        .admin { background: #f3e5f5; border-left: 4px solid #9c27b0; }
        .btn { padding: 10px 20px; background: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn:hover { background: #45a049; }
    </style>
</head>
<body>
    <div class="container">
        <h1><i class="fas fa-comments"></i> Test Chat System</h1>
        
        <div style="margin: 20px 0; padding: 15px; background: #e8f5e9; border-radius: 5px;">
            <h3>✅ Hệ thống chat hoạt động!</h3>
            <p>Tổng số tin nhắn: <strong><?php echo mysqli_num_rows($messages); ?></strong></p>
        </div>

        <div style="margin: 20px 0;">
            <a href="../admin/chat-admin.php" class="btn" style="background: #9c27b0;">
                <i class="fas fa-user-shield"></i> Vào Admin Chat
            </a>
            <a href="../index.php" class="btn" style="background: #2196f3;">
                <i class="fas fa-home"></i> Vào Trang User (Test Mini Chat)
            </a>
        </div>

        <h2>10 Tin nhắn gần nhất:</h2>
        <?php if (mysqli_num_rows($messages) > 0): ?>
            <?php while($msg = mysqli_fetch_assoc($messages)): ?>
                <div class="message <?php echo $msg['sender_type']; ?>">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                        <strong>
                            <i class="fas fa-<?php echo $msg['sender_type'] == 'admin' ? 'user-shield' : 'user'; ?>"></i>
                            <?php echo $msg['sender_type'] == 'admin' ? 'Admin' : htmlspecialchars($msg['fullname']); ?>
                        </strong>
                        <small style="color: #666;">
                            <?php echo date('H:i d/m/Y', strtotime($msg['created_at'])); ?>
                        </small>
                    </div>
                    <div><?php echo nl2br(htmlspecialchars($msg['message'])); ?></div>
                    <small style="color: #999;">User ID: <?php echo $msg['user_id']; ?> | <?php echo $msg['email']; ?></small>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="text-align: center; color: #999; padding: 40px;">
                <i class="fas fa-inbox" style="font-size: 48px;"></i><br>
                Chưa có tin nhắn nào
            </p>
        <?php endif; ?>

        <div style="margin-top: 30px; padding: 15px; background: #fff3cd; border-radius: 5px;">
            <h3>📝 Hướng dẫn sử dụng:</h3>
            <ol>
                <li><strong>Đăng nhập admin:</strong> <a href="../admin/">localhost/trangsuc/admin/</a></li>
                <li><strong>Vào menu "Chat Hỗ Trợ"</strong> (icon <i class="fas fa-comments"></i>)</li>
                <li><strong>Chọn user bên trái</strong> để xem tin nhắn</li>
                <li><strong>Gửi tin nhắn</strong> - không cần reload trang!</li>
            </ol>
        </div>
    </div>
</body>
</html>
