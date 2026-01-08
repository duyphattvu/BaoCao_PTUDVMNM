<?php
require_once 'includes/config.php';
$page_title = 'Thông Tin Cá Nhân';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
    exit;
}

$user_id = $_SESSION['user_id'];

// Xử lý cập nhật thông tin
if (isset($_POST['update_profile'])) {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    
    $sql = "UPDATE users SET fullname = '$fullname', phone = '$phone', address = '$address' WHERE id = $user_id";
    
    if (mysqli_query($conn, $sql)) {
        $_SESSION['user_name'] = $fullname;
        $_SESSION['success_message'] = 'Cập nhật thông tin thành công!';
    } else {
        $_SESSION['error_message'] = 'Có lỗi xảy ra, vui lòng thử lại!';
    }
    
    header('Location: profile.php');
    exit;
}

// Xử lý đổi mật khẩu
if (isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Lấy mật khẩu hiện tại
    $user_sql = "SELECT password FROM users WHERE id = $user_id";
    $user_result = mysqli_query($conn, $user_sql);
    $user = mysqli_fetch_assoc($user_result);
    
    if (!password_verify($current_password, $user['password'])) {
        $_SESSION['error_message'] = 'Mật khẩu hiện tại không đúng!';
    } elseif ($new_password !== $confirm_password) {
        $_SESSION['error_message'] = 'Mật khẩu mới không khớp!';
    } elseif (strlen($new_password) < 6) {
        $_SESSION['error_message'] = 'Mật khẩu mới phải có ít nhất 6 ký tự!';
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password = '$hashed_password' WHERE id = $user_id";
        
        if (mysqli_query($conn, $sql)) {
            $_SESSION['success_message'] = 'Đổi mật khẩu thành công!';
        } else {
            $_SESSION['error_message'] = 'Có lỗi xảy ra, vui lòng thử lại!';
        }
    }
    
    header('Location: profile.php');
    exit;
}

// Lấy thông tin user
$sql = "SELECT * FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

// Đếm số đơn hàng
$order_count_sql = "SELECT COUNT(*) as total FROM orders WHERE user_id = $user_id";
$order_count = mysqli_fetch_assoc(mysqli_query($conn, $order_count_sql))['total'];

// Tổng chi tiêu
$total_spent_sql = "SELECT SUM(total_amount) as total FROM orders WHERE user_id = $user_id AND status != 'cancelled'";
$total_spent_result = mysqli_query($conn, $total_spent_sql);
$total_spent = 0;
if ($total_spent_result && mysqli_num_rows($total_spent_result) > 0) {
    $spent_data = mysqli_fetch_assoc($total_spent_result);
    $total_spent = $spent_data['total'] ? $spent_data['total'] : 0;
}

include 'includes/header.php';
?>

<div class="bg-gradient-to-br from-blue-50 via-white to-purple-50 py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-10">
                <h1 class="text-4xl font-bold text-gray-900 mb-3">
                    <i class="fas fa-user-circle text-gold-600 mr-3"></i>Thông Tin Cá Nhân
                </h1>
                <p class="text-gray-600">Quản lý thông tin tài khoản của bạn</p>
            </div>

            <?php if (isset($_SESSION['success_message'])): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6 animate-fadeInUp">
                <i class="fas fa-check-circle mr-2"></i>
                <?php 
                echo $_SESSION['success_message']; 
                unset($_SESSION['success_message']);
                ?>
            </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error_message'])): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6 animate-fadeInUp">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <?php 
                echo $_SESSION['error_message']; 
                unset($_SESSION['error_message']);
                ?>
            </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <!-- User Card -->
                    <div class="bg-white rounded-2xl shadow-lg p-8 mb-6 text-center">
                        <div class="w-32 h-32 bg-gradient-to-br from-gold-400 to-gold-600 rounded-full mx-auto mb-4 flex items-center justify-center shadow-xl">
                            <i class="fas fa-user text-white text-5xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-2"><?php echo htmlspecialchars($user['fullname']); ?></h2>
                        <p class="text-gray-600 mb-4">
                            <i class="fas fa-envelope mr-2"></i><?php echo htmlspecialchars($user['email']); ?>
                        </p>
                        <div class="border-t pt-4">
                            <p class="text-sm text-gray-500 mb-1">Ngày tham gia</p>
                            <p class="font-semibold text-gray-700">
                                <i class="far fa-calendar-alt mr-2"></i>
                                <?php echo date('d/m/Y', strtotime($user['created_at'])); ?>
                            </p>
                        </div>
                    </div>

                    <!-- Stats Card -->
                    <div class="bg-gradient-to-br from-gold-500 to-gold-600 rounded-2xl shadow-lg p-6 text-white mb-6">
                        <h3 class="text-lg font-bold mb-4 flex items-center">
                            <i class="fas fa-chart-line mr-2"></i>Thống Kê
                        </h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="flex items-center">
                                    <i class="fas fa-shopping-bag mr-2"></i>Đơn hàng
                                </span>
                                <span class="text-2xl font-bold"><?php echo $order_count; ?></span>
                            </div>
                            <div class="border-t border-white/30 pt-4">
                                <span class="block text-sm opacity-90 mb-1">Tổng chi tiêu</span>
                                <span class="text-2xl font-bold"><?php echo number_format($total_spent); ?>đ</span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="text-lg font-bold mb-4 text-gray-900">
                            <i class="fas fa-link mr-2"></i>Liên Kết Nhanh
                        </h3>
                        <div class="space-y-3">
                            <a href="orders.php" class="flex items-center text-gray-700 hover:text-gold-600 transition p-3 rounded-lg hover:bg-gray-50 group">
                                <i class="fas fa-shopping-bag mr-3 text-gold-600 group-hover:scale-110 transition"></i>
                                <span>Đơn hàng của tôi</span>
                            </a>
                            <a href="purchase-history.php" class="flex items-center text-gray-700 hover:text-gold-600 transition p-3 rounded-lg hover:bg-gray-50 group">
                                <i class="fas fa-history mr-3 text-gold-600 group-hover:scale-110 transition"></i>
                                <span>Lịch sử mua hàng</span>
                            </a>
                            <a href="cart.php" class="flex items-center text-gray-700 hover:text-gold-600 transition p-3 rounded-lg hover:bg-gray-50 group">
                                <i class="fas fa-shopping-cart mr-3 text-gold-600 group-hover:scale-110 transition"></i>
                                <span>Giỏ hàng</span>
                            </a>
                            <a href="logout.php" class="flex items-center text-gray-700 hover:text-red-600 transition p-3 rounded-lg hover:bg-red-50 group">
                                <i class="fas fa-sign-out-alt mr-3 text-red-600 group-hover:scale-110 transition"></i>
                                <span>Đăng xuất</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="lg:col-span-2">
                    <!-- Update Profile Form -->
                    <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
                        <div class="flex items-center mb-6 pb-4 border-b-2 border-gray-100">
                            <i class="fas fa-edit text-gold-600 text-2xl mr-3"></i>
                            <h2 class="text-2xl font-bold text-gray-900">Cập Nhật Thông Tin</h2>
                        </div>
                        
                        <form method="POST" class="space-y-6">
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">
                                    <i class="fas fa-user mr-2 text-gold-600"></i>Họ và Tên
                                </label>
                                <input type="text" name="fullname" value="<?php echo htmlspecialchars($user['fullname']); ?>" 
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-gold-500 focus:outline-none transition"
                                       required>
                            </div>

                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">
                                    <i class="fas fa-envelope mr-2 text-gold-600"></i>Email
                                </label>
                                <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" 
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg bg-gray-100 cursor-not-allowed"
                                       disabled>
                                <p class="text-sm text-gray-500 mt-1">Email không thể thay đổi</p>
                            </div>

                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">
                                    <i class="fas fa-phone mr-2 text-gold-600"></i>Số Điện Thoại
                                </label>
                                <input type="tel" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" 
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-gold-500 focus:outline-none transition">
                            </div>

                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">
                                    <i class="fas fa-map-marker-alt mr-2 text-gold-600"></i>Địa Chỉ
                                </label>
                                <textarea name="address" rows="3" 
                                          class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-gold-500 focus:outline-none transition"><?php echo htmlspecialchars($user['address']); ?></textarea>
                            </div>

                            <button type="submit" name="update_profile" 
                                    class="w-full bg-gradient-to-r from-gold-600 to-gold-500 text-white py-4 rounded-lg font-bold hover:shadow-xl transition-all duration-300 hover:scale-105">
                                <i class="fas fa-save mr-2"></i>Lưu Thay Đổi
                            </button>
                        </form>
                    </div>

                    <!-- Change Password Form -->
                    <div class="bg-white rounded-2xl shadow-lg p-8">
                        <div class="flex items-center mb-6 pb-4 border-b-2 border-gray-100">
                            <i class="fas fa-lock text-gold-600 text-2xl mr-3"></i>
                            <h2 class="text-2xl font-bold text-gray-900">Đổi Mật Khẩu</h2>
                        </div>
                        
                        <form method="POST" class="space-y-6">
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">
                                    <i class="fas fa-key mr-2 text-gold-600"></i>Mật Khẩu Hiện Tại
                                </label>
                                <input type="password" name="current_password" 
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-gold-500 focus:outline-none transition"
                                       required>
                            </div>

                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">
                                    <i class="fas fa-lock mr-2 text-gold-600"></i>Mật Khẩu Mới
                                </label>
                                <input type="password" name="new_password" 
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-gold-500 focus:outline-none transition"
                                       minlength="6" required>
                                <p class="text-sm text-gray-500 mt-1">Tối thiểu 6 ký tự</p>
                            </div>

                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">
                                    <i class="fas fa-lock mr-2 text-gold-600"></i>Xác Nhận Mật Khẩu Mới
                                </label>
                                <input type="password" name="confirm_password" 
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-gold-500 focus:outline-none transition"
                                       minlength="6" required>
                            </div>

                            <button type="submit" name="change_password" 
                                    class="w-full bg-gradient-to-r from-blue-600 to-blue-500 text-white py-4 rounded-lg font-bold hover:shadow-xl transition-all duration-300 hover:scale-105">
                                <i class="fas fa-shield-alt mr-2"></i>Đổi Mật Khẩu
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
