<?php
require_once 'includes/config.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kiểm Tra Hệ Thống Chat</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .check-item {
            background: white;
            padding: 15px;
            margin: 10px 0;
            border-radius: 8px;
            border-left: 4px solid #ddd;
        }
        .check-item.success {
            border-color: #27ae60;
        }
        .check-item.error {
            border-color: #e74c3c;
        }
        .check-item h3 {
            margin: 0 0 10px 0;
            font-size: 16px;
        }
        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 14px;
            font-weight: bold;
        }
        .status.ok {
            background: #d4edda;
            color: #155724;
        }
        .status.fail {
            background: #f8d7da;
            color: #721c24;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #d4af37;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h1>🔍 Kiểm Tra Hệ Thống Chat</h1>
    
    <?php
    $all_ok = true;
    
    // 1. Kiểm tra kết nối database
    echo '<div class="check-item ' . ($conn ? 'success' : 'error') . '">';
    echo '<h3>1. Kết nối Database</h3>';
    if ($conn) {
        echo '<span class="status ok">✅ OK</span>';
        echo '<p>Đã kết nối thành công đến database.</p>';
    } else {
        echo '<span class="status fail">❌ LỖI</span>';
        echo '<p>Không thể kết nối database. Kiểm tra file config.php</p>';
        $all_ok = false;
    }
    echo '</div>';
    
    // 2. Kiểm tra bảng messages
    $table_check = mysqli_query($conn, "SHOW TABLES LIKE 'messages'");
    $table_exists = mysqli_num_rows($table_check) > 0;
    
    echo '<div class="check-item ' . ($table_exists ? 'success' : 'error') . '">';
    echo '<h3>2. Bảng Messages</h3>';
    if ($table_exists) {
        echo '<span class="status ok">✅ OK</span>';
        
        // Đếm số tin nhắn
        $count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM messages"));
        echo '<p>Bảng messages đã tồn tại với ' . $count['total'] . ' tin nhắn.</p>';
    } else {
        echo '<span class="status fail">❌ LỖI</span>';
        echo '<p>Bảng messages chưa được tạo!</p>';
        echo '<p><strong>Cách khắc phục:</strong> Chạy file <a href="setup-chat.php" style="color: #667eea;">setup-chat.php</a></p>';
        $all_ok = false;
    }
    echo '</div>';
    
    // 3. Kiểm tra file chat-api.php
    $chat_api_exists = file_exists('chat-api.php');
    
    echo '<div class="check-item ' . ($chat_api_exists ? 'success' : 'error') . '">';
    echo '<h3>3. File chat-api.php</h3>';
    if ($chat_api_exists) {
        echo '<span class="status ok">✅ OK</span>';
        echo '<p>File chat-api.php đã tồn tại.</p>';
    } else {
        echo '<span class="status fail">❌ LỖI</span>';
        echo '<p>File chat-api.php không tồn tại!</p>';
        $all_ok = false;
    }
    echo '</div>';
    
    // 4. Kiểm tra BASE_URL
    echo '<div class="check-item success">';
    echo '<h3>4. Cấu Hình BASE_URL</h3>';
    echo '<span class="status ok">✅ OK</span>';
    echo '<p>BASE_URL hiện tại: <strong>' . BASE_URL . '</strong></p>';
    echo '<p>Server: ' . $_SERVER['HTTP_HOST'] . '</p>';
    echo '</div>';
    
    // 5. Kiểm tra phiên bản PHP
    $php_version = phpversion();
    $php_ok = version_compare($php_version, '7.0', '>=');
    
    echo '<div class="check-item ' . ($php_ok ? 'success' : 'error') . '">';
    echo '<h3>5. Phiên Bản PHP</h3>';
    if ($php_ok) {
        echo '<span class="status ok">✅ OK</span>';
        echo '<p>PHP version: ' . $php_version . '</p>';
    } else {
        echo '<span class="status fail">❌ LỖI</span>';
        echo '<p>PHP version quá cũ: ' . $php_version . '</p>';
        $all_ok = false;
    }
    echo '</div>';
    
    // 6. Kiểm tra session
    echo '<div class="check-item success">';
    echo '<h3>6. Session</h3>';
    if (isset($_SESSION['user_id'])) {
        echo '<span class="status ok">✅ Đã đăng nhập</span>';
        echo '<p>User ID: ' . $_SESSION['user_id'] . '</p>';
    } else {
        echo '<span class="status ok">ℹ️ Chưa đăng nhập</span>';
        echo '<p>Bạn cần đăng nhập để test chat.</p>';
    }
    echo '</div>';
    
    // Kết luận
    echo '<hr style="margin: 30px 0;">';
    if ($all_ok) {
        echo '<h2 style="color: #27ae60;">✅ Hệ thống chat hoạt động bình thường!</h2>';
        echo '<p>Tất cả kiểm tra đã pass. Bạn có thể sử dụng chat.</p>';
    } else {
        echo '<h2 style="color: #e74c3c;">❌ Phát hiện lỗi!</h2>';
        echo '<p>Vui lòng khắc phục các lỗi trên trước khi sử dụng chat.</p>';
    }
    ?>
    
    <a href="index.php" class="btn">Về Trang Chủ</a>
    <?php if (!$table_exists): ?>
    <a href="setup-chat.php" class="btn" style="background: #667eea;">Cài Đặt Chat</a>
    <?php endif; ?>
</body>
</html>
