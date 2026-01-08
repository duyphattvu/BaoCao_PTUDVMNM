<?php
require_once 'check_admin.php';
$page_title = 'Hồ Sơ Cá Nhân';

$message = '';
$error = '';

// Lấy thông tin admin hiện tại
$admin_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = $admin_id";
$result = mysqli_query($conn, $sql);
$admin = mysqli_fetch_assoc($result);

// Xử lý cập nhật thông tin
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_info'])) {
        $fullname = mysqli_real_escape_string($conn, trim($_POST['fullname']));
        $email = mysqli_real_escape_string($conn, trim($_POST['email']));
        $phone = mysqli_real_escape_string($conn, trim($_POST['phone']));
        $address = mysqli_real_escape_string($conn, trim($_POST['address']));
        
        $update_sql = "UPDATE users SET fullname = '$fullname', email = '$email', phone = '$phone', address = '$address' WHERE id = $admin_id";
        if (mysqli_query($conn, $update_sql)) {
            $_SESSION['user_name'] = $fullname;
            $message = 'Cập nhật thông tin thành công!';
            // Refresh data
            $result = mysqli_query($conn, $sql);
            $admin = mysqli_fetch_assoc($result);
        } else {
            $error = 'Lỗi: ' . mysqli_error($conn);
        }
    }
    
    // Đổi mật khẩu
    if (isset($_POST['change_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        
        if (password_verify($current_password, $admin['password'])) {
            if ($new_password == $confirm_password) {
                if (strlen($new_password) >= 6) {
                    $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
                    $update_sql = "UPDATE users SET password = '$password_hash' WHERE id = $admin_id";
                    if (mysqli_query($conn, $update_sql)) {
                        $message = 'Đổi mật khẩu thành công!';
                    } else {
                        $error = 'Lỗi: ' . mysqli_error($conn);
                    }
                } else {
                    $error = 'Mật khẩu mới phải có ít nhất 6 ký tự!';
                }
            } else {
                $error = 'Mật khẩu xác nhận không khớp!';
            }
        } else {
            $error = 'Mật khẩu hiện tại không đúng!';
        }
    }
}

include 'includes/header.php';
?>

<?php if($message): ?>
<div class="alert alert-success">
    <i class="fas fa-check-circle"></i> <?php echo $message; ?>
</div>
<?php endif; ?>

<?php if($error): ?>
<div class="alert alert-danger">
    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
</div>
<?php endif; ?>

<div class="row">
    <!-- Thông tin cá nhân -->
    <div class="col-md-6">
        <div class="admin-card">
            <div class="admin-card-header">
                <h3><i class="fas fa-user"></i> Thông Tin Cá Nhân</h3>
            </div>
            <div class="admin-card-body">
                <form method="POST">
                    <div class="form-group">
                        <label>Họ và Tên <span class="text-danger">*</span></label>
                        <input type="text" name="fullname" class="form-control" value="<?php echo $admin['fullname']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" value="<?php echo $admin['email']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Số Điện Thoại</label>
                        <input type="text" name="phone" class="form-control" value="<?php echo $admin['phone']; ?>">
                    </div>
                    <div class="form-group">
                        <label>Địa Chỉ</label>
                        <textarea name="address" class="form-control" rows="3"><?php echo $admin['address']; ?></textarea>
                    </div>
                    <button type="submit" name="update_info" class="btn btn-primary">
                        <i class="fas fa-save"></i> Cập Nhật Thông Tin
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Đổi mật khẩu -->
    <div class="col-md-6">
        <div class="admin-card">
            <div class="admin-card-header">
                <h3><i class="fas fa-lock"></i> Đổi Mật Khẩu</h3>
            </div>
            <div class="admin-card-body">
                <form method="POST">
                    <div class="form-group">
                        <label>Mật Khẩu Hiện Tại <span class="text-danger">*</span></label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Mật Khẩu Mới <span class="text-danger">*</span></label>
                        <input type="password" name="new_password" class="form-control" required minlength="6">
                        <small class="text-muted">Ít nhất 6 ký tự</small>
                    </div>
                    <div class="form-group">
                        <label>Xác Nhận Mật Khẩu <span class="text-danger">*</span></label>
                        <input type="password" name="confirm_password" class="form-control" required>
                    </div>
                    <button type="submit" name="change_password" class="btn btn-warning">
                        <i class="fas fa-key"></i> Đổi Mật Khẩu
                    </button>
                </form>
            </div>
        </div>

        <!-- Thông tin tài khoản -->
        <div class="admin-card" style="margin-top: 20px;">
            <div class="admin-card-header">
                <h3><i class="fas fa-info-circle"></i> Thông Tin Tài Khoản</h3>
            </div>
            <div class="admin-card-body">
                <table class="table">
                    <tr>
                        <td><strong>ID:</strong></td>
                        <td><?php echo $admin['id']; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Vai Trò:</strong></td>
                        <td><span class="badge badge-success">Admin</span></td>
                    </tr>
                    <tr>
                        <td><strong>Ngày Tạo:</strong></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($admin['created_at'])); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Trạng Thái:</strong></td>
                        <td>
                            <?php if($admin['status'] == 1): ?>
                                <span class="badge badge-success">Hoạt Động</span>
                            <?php else: ?>
                                <span class="badge badge-danger">Khóa</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
