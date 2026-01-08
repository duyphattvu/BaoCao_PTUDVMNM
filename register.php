<?php
require_once 'includes/config.php';
$page_title = 'Đăng Ký';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = mysqli_real_escape_string($conn, trim($_POST['fullname']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $phone = mysqli_real_escape_string($conn, trim($_POST['phone']));
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    if (empty($fullname) || empty($email) || empty($password)) {
        $error = 'Vui lòng điền đầy đủ thông tin bắt buộc!';
    } elseif ($password !== $confirm_password) {
        $error = 'Mật khẩu xác nhận không khớp!';
    } elseif (strlen($password) < 6) {
        $error = 'Mật khẩu phải có ít nhất 6 ký tự!';
    } else {
        $check_email = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email'");
        if (mysqli_num_rows($check_email) > 0) {
            $error = 'Email đã được sử dụng!';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (fullname, email, phone, password, role) VALUES ('$fullname', '$email', '$phone', '$hashed_password', 'user')";
            
            if (mysqli_query($conn, $sql)) {
                $success = 'Đăng ký thành công! Đang chuyển hướng...';
                echo "<script>setTimeout(function(){ window.location.href='login.php'; }, 2000);</script>";
            } else {
                $error = 'Có lỗi xảy ra. Vui lòng thử lại!';
            }
        }
    }
}

include 'includes/header.php';
?>

<div class="min-h-screen bg-gradient-to-br from-blue-100 via-white to-blue-100 flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full">
        <div class="text-center mb-8 animate-fadeInUp">
            <div class="w-20 h-20 bg-gradient-to-br from-blue-600 to-blue-400 rounded-full flex items-center justify-center mx-auto mb-4 shadow-xl">
                <i class="fas fa-user-plus text-white text-3xl"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Đăng Ký</h1>
            <p class="text-gray-600">Tạo tài khoản mới để mua sắm</p>
        </div>

        <div class="bg-white rounded-3xl shadow-2xl p-8 animate-fadeInUp">
            <?php if($error): ?>
            <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-xl mr-3"></i>
                    <span class="font-semibold"><?php echo $error; ?></span>
                </div>
            </div>
            <?php endif; ?>

            <?php if($success): ?>
            <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-xl mr-3"></i>
                    <span class="font-semibold"><?php echo $success; ?></span>
                </div>
            </div>
            <?php endif; ?>

            <form method="POST" class="space-y-5">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">
                        <i class="fas fa-user text-blue-600 mr-2"></i>Họ và tên <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="fullname" required
                           class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-blue-600 focus:outline-none transition"
                           placeholder="Nguyễn Văn A">
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">
                        <i class="fas fa-envelope text-blue-600 mr-2"></i>Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" required
                           class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-blue-600 focus:outline-none transition"
                           placeholder="email@example.com">
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">
                        <i class="fas fa-phone text-blue-600 mr-2"></i>Số điện thoại
                    </label>
                    <input type="tel" name="phone"
                           class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-blue-600 focus:outline-none transition"
                           placeholder="0123456789">
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">
                        <i class="fas fa-lock text-blue-600 mr-2"></i>Mật khẩu <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password" required
                           class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-blue-600 focus:outline-none transition"
                           placeholder="••••••••">
                    <p class="text-xs text-gray-500 mt-1">Tối thiểu 6 ký tự</p>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">
                        <i class="fas fa-lock text-blue-600 mr-2"></i>Xác nhận mật khẩu <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="confirm_password" required
                           class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-blue-600 focus:outline-none transition"
                           placeholder="••••••••">
                </div>

                <div class="flex items-start">
                    <input type="checkbox" required class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 mt-1">
                    <label class="ml-2 text-sm text-gray-600">
                        Tôi đồng ý với <a href="#" class="text-blue-600 hover:underline">Điều khoản dịch vụ</a> và 
                        <a href="#" class="text-blue-600 hover:underline">Chính sách bảo mật</a>
                    </label>
                </div>

                <button type="submit" class="w-full py-4 bg-gradient-to-r from-blue-600 to-blue-400 text-white font-bold rounded-xl hover:shadow-xl transition-all duration-300 hover:scale-105">
                    <i class="fas fa-user-plus mr-2"></i>Đăng Ký
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-gray-600">
                    Đã có tài khoản? 
                    <a href="login.php" class="text-blue-600 hover:text-blue-700 font-bold">Đăng nhập ngay</a>
                </p>
            </div>
        </div>

        <div class="mt-8 text-center text-sm text-gray-600">
            <p>🔒 Thông tin của bạn được bảo mật tuyệt đối</p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
