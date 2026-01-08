<?php
require_once 'includes/config.php';
$page_title = 'Đăng Nhập';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = $_POST['password'];
    
    $sql = "SELECT * FROM users WHERE email = '$email' AND status = 1";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        // Check password using password_hash first
        if (password_verify($password, $user['password'])) {
            // OK
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['fullname'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_email'] = $user['email'];

            // Kiểm tra có redirect không
            if (isset($_GET['redirect']) && !empty($_GET['redirect'])) {
                $redirect = $_GET['redirect'];
                header('Location: ' . $redirect);
            } else {
                if ($user['role'] == 'admin') {
                    header('Location: admin/index.php');
                } else {
                    header('Location: index.php');
                }
            }
            exit;
        } else {
            // Kiểm tra MD5 (cố định, không tự động chuyển đổi)
            // MD5 của "admin123" = 0192023a7bbd73250516f069df18b500
            $stored = $user['password'];
            
            // Kiểm tra MD5 match (GIỮ NGUYÊN MD5, KHÔNG chuyển đổi)
            if (strlen($stored) == 32 && md5($password) === $stored) {
                // Đăng nhập thành công với MD5
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['fullname'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['user_email'] = $user['email'];
                
                // Kiểm tra có redirect không
                if (isset($_GET['redirect']) && !empty($_GET['redirect'])) {
                    $redirect = $_GET['redirect'];
                    header('Location: ' . $redirect);
                } else {
                    if ($user['role'] == 'admin') {
                        header('Location: admin/index.php');
                    } else {
                        header('Location: index.php');
                    }
                }
                exit;
            }
            
            // Kiểm tra plaintext (cho user thường)
            if ($stored === $password) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['fullname'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['user_email'] = $user['email'];
                
                // Kiểm tra có redirect không
                if (isset($_GET['redirect']) && !empty($_GET['redirect'])) {
                    $redirect = $_GET['redirect'];
                    header('Location: ' . $redirect);
                } else {
                    if ($user['role'] == 'admin') {
                        header('Location: admin/index.php');
                    } else {
                        header('Location: index.php');
                    }
                }
                exit;
            }

            $error = 'Mật khẩu không chính xác!';
        }
    } else {
        $error = 'Email không tồn tại!';
    }
}

include 'includes/header.php';
?>

<div class="min-h-screen bg-gradient-to-br from-gold-100 via-white to-gold-100 flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full">
        <div class="text-center mb-8 animate-fadeInUp">
            <div class="w-20 h-20 bg-gradient-to-br from-gold-600 to-gold-400 rounded-full flex items-center justify-center mx-auto mb-4 shadow-xl">
                <i class="fas fa-gem text-white text-3xl"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Đăng Nhập</h1>
            <p class="text-gray-600">Chào mừng bạn quay trở lại!</p>
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

            <form method="POST" class="space-y-6">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">
                        <i class="fas fa-envelope text-gold-600 mr-2"></i>Email
                    </label>
                    <input type="email" name="email" required
                           class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-gold-600 focus:outline-none transition"
                           placeholder="email@example.com">
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">
                        <i class="fas fa-lock text-gold-600 mr-2"></i>Mật khẩu
                    </label>
                    <input type="password" name="password" required
                           class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-gold-600 focus:outline-none transition"
                           placeholder="••••••••">
                </div>

                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center">
                        <input type="checkbox" class="w-4 h-4 text-gold-600 border-gray-300 rounded focus:ring-gold-500">
                        <span class="ml-2 text-gray-600">Ghi nhớ đăng nhập</span>
                    </label>
                    <a href="#" class="text-gold-600 hover:text-gold-700 font-semibold">Quên mật khẩu?</a>
                </div>

                <button type="submit" class="w-full py-4 bg-gradient-to-r from-gold-600 to-gold-400 text-white font-bold rounded-xl hover:shadow-xl transition-all duration-300 hover:scale-105">
                    <i class="fas fa-sign-in-alt mr-2"></i>Đăng Nhập
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-gray-600">
                    Chưa có tài khoản? 
                    <a href="register.php" class="text-gold-600 hover:text-gold-700 font-bold">Đăng ký ngay</a>
                </p>
            </div>
        </div>

        <div class="mt-8 text-center text-sm text-gray-600">
            <p>🔒 Thông tin của bạn được bảo mật tuyệt đối</p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
