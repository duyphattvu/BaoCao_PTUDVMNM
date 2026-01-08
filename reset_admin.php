<?php
require_once 'includes/config.php';

echo '<style>
body { font-family: Arial; padding: 20px; background: #f5f5f5; }
.box { background: white; padding: 30px; margin: 20px auto; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); max-width: 600px; }
h2 { color: #333; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
.success { color: green; font-weight: bold; padding: 10px; background: #d4edda; border-radius: 5px; margin: 10px 0; }
.error { color: red; font-weight: bold; padding: 10px; background: #f8d7da; border-radius: 5px; margin: 10px 0; }
.info { background: #d1ecf1; padding: 15px; border-radius: 5px; margin: 15px 0; border-left: 4px solid #0c5460; }
.btn { display: inline-block; padding: 12px 30px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin-top: 15px; font-weight: bold; }
.btn:hover { background: #0056b3; }
code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; color: #c7254e; }
</style>';

echo '<div class="box">';
echo '<h2>🔄 Reset Mật Khẩu Admin</h2>';

// Bước 1: Xóa tất cả admin cũ
echo '<h3>Bước 1: Xóa tài khoản admin cũ</h3>';
$delete_sql = "DELETE FROM users WHERE email = 'admin@trangsuc.com'";
if (mysqli_query($conn, $delete_sql)) {
    echo '<p class="success">✓ Đã xóa tài khoản admin cũ</p>';
} else {
    echo '<p class="info">ℹ️ Không có tài khoản admin cũ hoặc đã xóa</p>';
}

// Bước 2: Tạo password hash mới
echo '<h3>Bước 2: Tạo password hash mới</h3>';
$new_password = 'admin123';
$password_hash = password_hash($new_password, PASSWORD_DEFAULT);
echo '<p><strong>Mật khẩu:</strong> <code>' . $new_password . '</code></p>';
echo '<p><strong>Hash:</strong> <code>' . $password_hash . '</code></p>';

// Bước 3: Tạo admin mới
echo '<h3>Bước 3: Tạo tài khoản admin mới</h3>';
$insert_sql = "INSERT INTO users (fullname, email, password, role, status, created_at) 
               VALUES ('Administrator', 'admin@trangsuc.com', '$password_hash', 'admin', 1, NOW())";

if (mysqli_query($conn, $insert_sql)) {
    $admin_id = mysqli_insert_id($conn);
    echo '<p class="success">✓ Tạo tài khoản admin mới thành công!</p>';
    echo '<p><strong>ID:</strong> ' . $admin_id . '</p>';
    
    // Bước 4: Verify lại
    echo '<h3>Bước 4: Kiểm tra lại</h3>';
    $verify_sql = "SELECT * FROM users WHERE email = 'admin@trangsuc.com'";
    $verify_result = mysqli_query($conn, $verify_sql);
    
    if ($verify_row = mysqli_fetch_assoc($verify_result)) {
        echo '<p class="success">✓ Tìm thấy tài khoản admin</p>';
        echo '<p><strong>Email:</strong> ' . $verify_row['email'] . '</p>';
        echo '<p><strong>Role:</strong> ' . $verify_row['role'] . '</p>';
        echo '<p><strong>Status:</strong> ' . ($verify_row['status'] == 1 ? 'Active ✓' : 'Inactive ✗') . '</p>';
        
        // Test password
        if (password_verify($new_password, $verify_row['password'])) {
            echo '<p class="success">✓✓✓ Mật khẩu "' . $new_password . '" hoạt động HOÀN HẢO!</p>';
        } else {
            echo '<p class="error">✗ Mật khẩu vẫn không khớp (Lỗi nghiêm trọng)</p>';
        }
    }
    
} else {
    echo '<p class="error">✗ Lỗi: ' . mysqli_error($conn) . '</p>';
}

echo '</div>';

// Thông tin đăng nhập
echo '<div class="box">';
echo '<h2>📋 Thông Tin Đăng Nhập</h2>';
echo '<div class="info">';
echo '<p><strong>URL:</strong> <a href="login.php">http://localhost/trangsuc/login.php</a></p>';
echo '<p><strong>Email:</strong> <code>admin@trangsuc.com</code></p>';
echo '<p><strong>Password:</strong> <code>admin123</code></p>';
echo '</div>';
echo '<a href="login.php" class="btn">🔐 Đăng Nhập Ngay</a>';
echo '</div>';

// Debug info
echo '<div class="box">';
echo '<h2>🐛 Debug Info</h2>';
echo '<p><strong>PHP Version:</strong> ' . phpversion() . '</p>';
echo '<p><strong>Password Hash Algorithm:</strong> ' . PASSWORD_DEFAULT . '</p>';
echo '<p><strong>Database:</strong> ' . (isset($conn) ? 'Connected ✓' : 'Not Connected ✗') . '</p>';
echo '</div>';
?>
