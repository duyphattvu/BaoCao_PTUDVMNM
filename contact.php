<?php
require_once 'includes/config.php';
$page_title = 'Liên Hệ';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = mysqli_real_escape_string($conn, trim($_POST['fullname']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $phone = mysqli_real_escape_string($conn, trim($_POST['phone']));
    $subject = mysqli_real_escape_string($conn, trim($_POST['subject']));
    $msg = mysqli_real_escape_string($conn, trim($_POST['message']));
    
    if (empty($fullname) || empty($email) || empty($msg)) {
        $error = 'Vui lòng điền đầy đủ thông tin bắt buộc!';
    } else {
        $sql = "INSERT INTO contacts (fullname, email, phone, subject, message) VALUES ('$fullname', '$email', '$phone', '$subject', '$msg')";
        if (mysqli_query($conn, $sql)) {
            $message = 'Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi sớm nhất.';
        } else {
            $error = 'Có lỗi xảy ra. Vui lòng thử lại!';
        }
    }
}

include 'includes/header.php';
?>

<!-- Hero Section with Overlay -->
<div class="relative bg-gradient-to-br from-blue-900 via-blue-700 to-purple-900 py-20 overflow-hidden">
    <div class="absolute inset-0 bg-black opacity-30"></div>
    <div class="absolute inset-0">
        <div class="absolute top-10 left-10 w-72 h-72 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse"></div>
        <div class="absolute bottom-10 right-10 w-96 h-96 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse" style="animation-delay: 1s;"></div>
    </div>
    <div class="container mx-auto px-4 relative z-10">
        <div class="text-center animate-fadeInUp">
            <div class="inline-block mb-4 px-6 py-2 bg-white/10 backdrop-blur-sm rounded-full border border-white/20">
                <span class="text-white/90 text-sm font-semibold tracking-wider">CHÚNG TÔI LUÔN SẴN SÀNG</span>
            </div>
            <h1 class="text-6xl md:text-7xl font-bold text-white mb-6 leading-tight">
                Liên Hệ Với<br/>
                <span class="bg-gradient-to-r from-yellow-300 via-yellow-200 to-yellow-300 bg-clip-text text-transparent">Trang Sức Cao Cấp</span>
            </h1>
            <p class="text-white/90 text-xl max-w-2xl mx-auto mb-8">
                Đội ngũ tư vấn chuyên nghiệp sẵn sàng hỗ trợ bạn 24/7 về mọi vấn đề liên quan đến trang sức bạc
            </p>
            <div class="flex items-center justify-center space-x-4">
                <div class="w-16 h-1 bg-gradient-to-r from-transparent via-yellow-300 to-transparent"></div>
                <i class="fas fa-gem text-yellow-300 text-2xl"></i>
                <div class="w-16 h-1 bg-gradient-to-r from-transparent via-yellow-300 to-transparent"></div>
            </div>
        </div>
    </div>
</div>

<div class="bg-gradient-to-br from-gray-50 via-white to-blue-50 py-16">
    <div class="container mx-auto px-4">

        <?php if($message): ?>
        <div class="mb-8 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-lg animate-fadeInUp">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-2xl mr-3"></i>
                <span class="font-semibold"><?php echo $message; ?></span>
            </div>
        </div>
        <?php endif; ?>
        
        <?php if($error): ?>
        <div class="mb-8 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-lg animate-fadeInUp">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-2xl mr-3"></i>
                <span class="font-semibold"><?php echo $error; ?></span>
            </div>
        </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
            <!-- Form - Takes 3 columns -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-3xl shadow-2xl p-10 border border-gray-100 animate-fadeInUp hover:shadow-3xl transition-all duration-500">
                    <div class="mb-8">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-600 to-purple-600 rounded-2xl mb-4 shadow-lg">
                            <i class="fas fa-envelope text-white text-2xl"></i>
                        </div>
                        <h2 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent mb-3">
                            Gửi Tin Nhắn Cho Chúng Tôi
                        </h2>
                        <p class="text-gray-600 text-lg">Điền thông tin bên dưới, chúng tôi sẽ phản hồi trong vòng 24 giờ</p>
                    </div>

                    <form method="POST" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="group">
                                <label class="block text-gray-700 font-bold mb-3 text-sm uppercase tracking-wide">
                                    <i class="fas fa-user text-blue-600 mr-2"></i>
                                    Họ và tên <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="fullname" required
                                       class="w-full px-5 py-4 rounded-xl border-2 border-gray-200 focus:border-blue-600 focus:ring-4 focus:ring-blue-100 focus:outline-none transition-all duration-300 bg-gray-50 group-hover:bg-white"
                                       placeholder="Nguyễn Văn A">
                            </div>
                            <div class="group">
                                <label class="block text-gray-700 font-bold mb-3 text-sm uppercase tracking-wide">
                                    <i class="fas fa-envelope text-blue-600 mr-2"></i>
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" name="email" required
                                       class="w-full px-5 py-4 rounded-xl border-2 border-gray-200 focus:border-blue-600 focus:ring-4 focus:ring-blue-100 focus:outline-none transition-all duration-300 bg-gray-50 group-hover:bg-white"
                                       placeholder="email@example.com">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="group">
                                <label class="block text-gray-700 font-bold mb-3 text-sm uppercase tracking-wide">
                                    <i class="fas fa-phone text-blue-600 mr-2"></i>
                                    Số điện thoại
                                </label>
                                <input type="tel" name="phone"
                                       class="w-full px-5 py-4 rounded-xl border-2 border-gray-200 focus:border-blue-600 focus:ring-4 focus:ring-blue-100 focus:outline-none transition-all duration-300 bg-gray-50 group-hover:bg-white"
                                       placeholder="0123456789">
                            </div>
                            <div class="group">
                                <label class="block text-gray-700 font-bold mb-3 text-sm uppercase tracking-wide">
                                    <i class="fas fa-tag text-blue-600 mr-2"></i>
                                    Tiêu đề
                                </label>
                                <input type="text" name="subject"
                                       class="w-full px-5 py-4 rounded-xl border-2 border-gray-200 focus:border-blue-600 focus:ring-4 focus:ring-blue-100 focus:outline-none transition-all duration-300 bg-gray-50 group-hover:bg-white"
                                       placeholder="Tư vấn sản phẩm">
                            </div>
                        </div>

                        <div class="group">
                            <label class="block text-gray-700 font-bold mb-3 text-sm uppercase tracking-wide">
                                <i class="fas fa-comment-dots text-blue-600 mr-2"></i>
                                Nội dung tin nhắn <span class="text-red-500">*</span>
                            </label>
                            <textarea name="message" rows="6" required
                                      class="w-full px-5 py-4 rounded-xl border-2 border-gray-200 focus:border-blue-600 focus:ring-4 focus:ring-blue-100 focus:outline-none transition-all duration-300 bg-gray-50 group-hover:bg-white resize-none"
                                      placeholder="Nhập nội dung tin nhắn của bạn..."></textarea>
                        </div>

                        <div class="pt-4">
                            <button type="submit" 
                                    class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-5 px-8 rounded-xl font-bold text-lg hover:from-blue-700 hover:to-purple-700 transform hover:scale-[1.02] transition-all duration-300 shadow-xl hover:shadow-2xl flex items-center justify-center space-x-3">
                                <i class="fas fa-paper-plane text-xl"></i>
                                <span>Gửi Tin Nhắn</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Contact Info & Map - Takes 2 columns -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Contact Cards -->
                <div class="bg-white rounded-3xl shadow-2xl p-8 border border-gray-100 animate-fadeInUp">
                    <div class="mb-6">
                        <h2 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent mb-2">
                            Thông Tin Liên Hệ
                        </h2>
                        <p class="text-gray-600">Hãy đến và trải nghiệm dịch vụ của chúng tôi</p>
                    </div>

                    <div class="space-y-5">
                        <div class="group p-5 bg-gradient-to-br from-blue-50 to-purple-50 rounded-2xl hover:shadow-lg transition-all duration-300 hover:scale-[1.02] border border-blue-100">
                            <div class="flex items-center space-x-4">
                                <div class="w-14 h-14 bg-gradient-to-br from-blue-600 to-purple-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg group-hover:scale-110 transition-transform">
                                    <i class="fas fa-map-marker-alt text-white text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900 mb-1 text-lg">Cửa Hàng Chính</h3>
                                    <p class="text-gray-700">Đường Nguyễn Đáng, Phường Trà Vinh, Tỉnh Vĩnh Long</p>
                                </div>
                            </div>
                        </div>

                        <div class="group p-5 bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl hover:shadow-lg transition-all duration-300 hover:scale-[1.02] border border-green-100">
                            <div class="flex items-center space-x-4">
                                <div class="w-14 h-14 bg-gradient-to-br from-green-600 to-emerald-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg group-hover:scale-110 transition-transform">
                                    <i class="fas fa-phone-alt text-white text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900 mb-1 text-lg">Hotline 24/7</h3>
                                    <p class="text-gray-700">
                                        <a href="tel:1900xxxx" class="text-green-600 hover:text-green-700 font-semibold">0983592506</a>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="group p-5 bg-gradient-to-br from-orange-50 to-red-50 rounded-2xl hover:shadow-lg transition-all duration-300 hover:scale-[1.02] border border-orange-100">
                            <div class="flex items-center space-x-4">
                                <div class="w-14 h-14 bg-gradient-to-br from-orange-600 to-red-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg group-hover:scale-110 transition-transform">
                                    <i class="fas fa-envelope text-white text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900 mb-1 text-lg">Email Hỗ Trợ</h3>
                                    <p class="text-gray-700">
                                        <a href="mailto:info@trangsuc.com" class="text-orange-600 hover:text-orange-700 font-semibold">nguyenduyphat2019@gmail.com</a>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="group p-5 bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl hover:shadow-lg transition-all duration-300 hover:scale-[1.02] border border-purple-100">
                            <div class="flex items-center space-x-4">
                                <div class="w-14 h-14 bg-gradient-to-br from-purple-600 to-pink-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg group-hover:scale-110 transition-transform">
                                    <i class="fas fa-clock text-white text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900 mb-1 text-lg">Giờ Làm Việc</h3>
                                    <p class="text-gray-700">8:00 - 22:00 (Tất cả các ngày)</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Social -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <p class="text-gray-700 font-semibold mb-4 text-center">Kết Nối Với Chúng Tôi</p>
                        <div class="flex justify-center space-x-4">
                            <a href="https://web.facebook.com/share/1DH7RiRx4a/?mibextid=wwXIfr&_rdc=1&_rdr" class="w-12 h-12 bg-gradient-to-br from-blue-600 to-blue-700 rounded-full flex items-center justify-center text-white hover:scale-110 transition-transform shadow-lg">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://www.instagram.com/ngtan_0804/" class="w-12 h-12 bg-gradient-to-br from-pink-600 to-red-600 rounded-full flex items-center justify-center text-white hover:scale-110 transition-transform shadow-lg">
                                <i class="fab fa-instagram"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Map -->
                <div class="bg-white rounded-3xl shadow-2xl p-8 border border-gray-100 animate-fadeInUp">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent mb-2">
                            Tìm Chúng Tôi
                        </h2>
                        <p class="text-gray-600">Ghé thăm cửa hàng để trải nghiệm trực tiếp</p>
                    </div>
                    <div class="aspect-square bg-gray-200 rounded-2xl overflow-hidden shadow-inner">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.5251023446444!2d106.69530731533423!3d10.772752962208428!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f4b3330bcc9%3A0x10bd3a1e726eb14f!2sHCMC!5e0!3m2!1sen!2s!4v1234567890123" 
                                width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
            </div>          <div>
                                <h3 class="font-bold text-gray-900 mb-1">Email</h3>
                                <p class="text-gray-600"><a href="mailto:info@trangsuc.com" class="text-blue-600 hover:underline">nguyenduyphat2019@gmail.com</a></p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-lg hover:bg-blue-50 transition">
                            <div class="w-12 h-12 bg-gradient-to-br from-purple-600 to-purple-400 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-clock text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 mb-1">Giờ làm việc</h3>
                                <p class="text-gray-600">8:00 - 22:00 (Cả tuần)</p>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
