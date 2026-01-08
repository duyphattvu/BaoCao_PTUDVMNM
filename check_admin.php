<?php
// File kiểm tra và tự động sửa mật khẩu admin
require_once 'includes/config.php';

echo '<style>
body { font-family: Arial; padding: 20px; background: #f5f5f5; }
.box { background: white; padding: 20px; margin: 10px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
h2 { color: #333; }
.success { color: green; font-weight: bold; }
.error { color: red; font-weight: bold; }
.info { background: #e3f2fd; padding: 15px; border-left: 4px solid #2196f3; margin: 15px 0; }
</style>';

echo '<div class="box">';
echo '<h2>🔍 Kiểm Tra & Khắc Phục Tài Khoản Admin</h2>';
echo '<div class="info">File này sẽ tự động kiểm tra và sửa mật khẩu admin nếu cần thiết.</div>';

// Kiểm tra user admin
$sql = "SELECT * FROM users WHERE role = 'admin'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($user = mysqli_fetch_assoc($result)) {
        echo '<p><strong>ID:</strong> ' . $user['id'] . '</p>';
        echo '<p><strong>Tên:</strong> ' . $user['fullname'] . '</p>';
        echo '<p><strong>Email:</strong> ' . $user['email'] . '</p>';
        echo '<p><strong>Role:</strong> ' . $user['role'] . '</p>';
        echo '<p><strong>Status:</strong> ' . ($user['status'] == 1 ? 'Active' : 'Inactive') . '</p>';
        echo '<p><strong>Password Hash (50 ký tự đầu):</strong> <code>' . substr($user['password'], 0, 50) . '...</code></p>';
        echo '<hr>';
        
        // Test password với MD5 cố định
        echo '<h3>🔐 Test Mật Khẩu "admin123":</h3>';
        
        $correct_md5 = '0192023a7bbd73250516f069df18b500'; // MD5 của "admin123"
        $current_hash = $user['password'];
        
        if ($current_hash === $correct_md5 || md5('admin123') === $current_hash) {
            echo '<p class="success">✓ Mật khẩu "admin123" ĐÚNG - Bạn có thể đăng nhập!</p>';
            echo '<div style="background: #c8e6c9; padding: 15px; border-radius: 5px; margin: 15px 0;">';
            echo '<strong>✅ THÀNH CÔNG!</strong><br>';
            echo 'Tài khoản admin đã hoạt động bình thường.<br>';
            echo 'Bạn có thể đăng nhập với:<br>';
            echo '• Email: <strong>admin@trangsuc.com</strong><br>';
            echo '• Mật khẩu: <strong>admin123</strong><br>';
            echo '• Hash MD5: <strong>0192023a7bbd73250516f069df18b500</strong> (Cố định)';
            echo '</div>';
        } else {
            echo '<p class="error">✗ Mật khẩu "admin123" SAI - Password hash không khớp!</p>';
            echo '<p><strong>Hash hiện tại:</strong> <code>' . $current_hash . '</code></p>';
            
            // Cập nhật về MD5 cố định
            echo '<h4>⚙️ Đang cập nhật về mật khẩu MD5 cố định...</h4>';
            $update_sql = "UPDATE users SET password = '$correct_md5' WHERE email = 'admin@trangsuc.com'";
            if (mysqli_query($conn, $update_sql)) {
                echo '<div style="background: #c8e6c9; padding: 15px; border-radius: 5px; margin: 15px 0;">';
                echo '<p class="success">✓ ĐÃ CẬP NHẬT MẬT KHẨU CỐ ĐỊNH THÀNH CÔNG!</p>';
                echo '<strong>Thông tin đăng nhập:</strong><br>';
                echo '• Email: <strong>admin@trangsuc.com</strong><br>';
                echo '• Mật khẩu: <strong>admin123</strong><br>';
                echo '• Hash MD5: <strong>0192023a7bbd73250516f069df18b500</strong><br>';
                echo '• <em>Hash này cố định, mọi máy đều đăng nhập được!</em><br><br>';
                echo '<a href="login.php" style="display:inline-block; padding:10px 20px; background:#4caf50; color:white; text-decoration:none; border-radius:5px;">Đăng Nhập Ngay</a>';
                echo '</div>';
            } else {
                echo '<p class="error">✗ Lỗi cập nhật: ' . mysqli_error($conn) . '</p>';
            }
        }
        echo '<hr>';
    }
} else {
    echo '<p class="error">❌ Không tìm thấy tài khoản admin trong database!</p>';
    
    // Tạo admin mới với MD5 cố định
    echo '</div><div class="box">';
    echo '<h3>➕ Tạo Tài Khoản Admin Mới</h3>';
    $password_md5 = '0192023a7bbd73250516f069df18b500'; // MD5 của "admin123" - CỐ ĐỊNH
    $insert_sql = "INSERT INTO users (fullname, email, password, role, status) VALUES ('Administrator', 'admin@trangsuc.com', '$password_md5', 'admin', 1)";
    
    if (mysqli_query($conn, $insert_sql)) {
        echo '<div style="background: #c8e6c9; padding: 15px; border-radius: 5px; margin: 15px 0;">';
        echo '<p class="success">✓ Đã tạo tài khoản admin mới thành công!</p>';
        echo '<strong>Thông tin đăng nhập:</strong><br>';
        echo '• Email: <strong>admin@trangsuc.com</strong><br>';
        echo '• Password: <strong>admin123</strong><br>';
        echo '• Hash MD5: <strong>0192023a7bbd73250516f069df18b500</strong> (Cố định)<br>';
        echo '• <em>Mật khẩu này cố định, mọi máy đều đăng nhập được!</em><br><br>';
        echo '<a href="login.php" style="display:inline-block; padding:10px 20px; background:#4caf50; color:white; text-decoration:none; border-radius:5px;">Đăng Nhập Ngay</a>';
        echo '</div>';
    } else {
        echo '<p class="error">✗ Lỗi tạo tài khoản: ' . mysqli_error($conn) . '</p>';
    }
}

echo '</div>';

echo '<div class="box">';
echo '<h3>📝 Hướng Dẫn Đăng Nhập</h3>';
echo '<ol>';
echo '<li>Truy cập: <a href="login.php">http://localhost/trangsuc/login.php</a></li>';
echo '<li>Nhập email: <strong>admin@trangsuc.com</strong></li>';
echo '<li>Nhập password: <strong>admin123</strong></li>';
echo '<li>Click "Đăng Nhập"</li>';
echo '</ol>';
echo '</div>';

echo '<div class="box">';
echo '<p><a href="login.php" style="display:inline-block; padding:10px 20px; background:#007bff; color:white; text-decoration:none; border-radius:5px;">🔐 Đi Tới Trang Đăng Nhập</a></p>';
echo '</div>';
?>
