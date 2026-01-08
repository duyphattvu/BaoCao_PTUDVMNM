<?php
require_once 'includes/config.php';
$page_title = 'Chính Sách Đổi Trả';
include 'includes/header.php';
?>

<div class="bg-gradient-to-br from-blue-50 via-white to-purple-50 py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-12">
                <div class="inline-block bg-gradient-to-r from-gold-600 to-gold-400 text-white px-6 py-2 rounded-full mb-4">
                    <i class="fas fa-exchange-alt mr-2"></i>CHÍNH SÁCH
                </div>
                <h1 class="text-5xl font-bold text-gray-900 mb-4">Chính Sách Đổi Trả</h1>
                <p class="text-gray-600 text-lg">Cam kết đổi trả linh hoạt, bảo vệ quyền lợi khách hàng</p>
            </div>

            <!-- Content -->
            <div class="bg-white rounded-3xl shadow-2xl p-8 md:p-12 space-y-8">
                <!-- Section 1 -->
                <div class="border-l-4 border-gold-600 pl-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                        <span class="w-10 h-10 bg-gold-600 text-white rounded-full flex items-center justify-center mr-3 text-lg">1</span>
                        Điều Kiện Đổi Trả
                    </h2>
                    <div class="space-y-3 text-gray-700 leading-relaxed">
                        <p class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                            <span>Sản phẩm còn nguyên vẹn, chưa qua sử dụng, không trầy xước hoặc hư hỏng</span>
                        </p>
                        <p class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                            <span>Còn đầy đủ hộp, giấy tờ, phụ kiện kèm theo (nếu có)</span>
                        </p>
                        <p class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                            <span>Thời gian đổi trả trong vòng <strong>7 ngày</strong> kể từ ngày nhận hàng</span>
                        </p>
                        <p class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                            <span>Có hóa đơn mua hàng hoặc mã đơn hàng hợp lệ</span>
                        </p>
                    </div>
                </div>

                <!-- Section 2 -->
                <div class="border-l-4 border-blue-600 pl-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                        <span class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center mr-3 text-lg">2</span>
                        Trường Hợp Được Đổi Trả
                    </h2>
                    <div class="space-y-3 text-gray-700 leading-relaxed">
                        <p class="flex items-start">
                            <i class="fas fa-times-circle text-red-500 mt-1 mr-3"></i>
                            <span>Sản phẩm bị lỗi kỹ thuật từ nhà sản xuất</span>
                        </p>
                        <p class="flex items-start">
                            <i class="fas fa-times-circle text-red-500 mt-1 mr-3"></i>
                            <span>Sản phẩm giao không đúng mẫu mã, kích thước như đơn đặt hàng</span>
                        </p>
                        <p class="flex items-start">
                            <i class="fas fa-times-circle text-red-500 mt-1 mr-3"></i>
                            <span>Sản phẩm bị hư hỏng trong quá trình vận chuyển</span>
                        </p>
                        <p class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                            <span>Khách hàng không hài lòng về mẫu mã, kích thước (áp dụng đổi 1 lần)</span>
                        </p>
                    </div>
                </div>

                <!-- Section 3 -->
                <div class="border-l-4 border-purple-600 pl-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                        <span class="w-10 h-10 bg-purple-600 text-white rounded-full flex items-center justify-center mr-3 text-lg">3</span>
                        Quy Trình Đổi Trả
                    </h2>
                    <div class="space-y-4">
                        <div class="bg-gradient-to-r from-gold-50 to-yellow-50 rounded-xl p-4 border-l-4 border-gold-600">
                            <p class="font-semibold text-gray-900 mb-2">
                                <i class="fas fa-phone text-gold-600 mr-2"></i>Bước 1: Liên hệ
                            </p>
                            <p class="text-gray-700 text-sm">
                                Gọi hotline <strong>0983592506</strong> hoặc nhắn tin qua Fanpage để thông báo đổi trả
                            </p>
                        </div>
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4 border-l-4 border-blue-600">
                            <p class="font-semibold text-gray-900 mb-2">
                                <i class="fas fa-box text-blue-600 mr-2"></i>Bước 2: Gửi sản phẩm
                            </p>
                            <p class="text-gray-700 text-sm">
                                Đóng gói cẩn thận và gửi về địa chỉ cửa hàng (phí ship khách hàng chịu nếu đổi do không vừa)
                            </p>
                        </div>
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-4 border-l-4 border-green-600">
                            <p class="font-semibold text-gray-900 mb-2">
                                <i class="fas fa-check-double text-green-600 mr-2"></i>Bước 3: Kiểm tra & xử lý
                            </p>
                            <p class="text-gray-700 text-sm">
                                Cửa hàng kiểm tra và xử lý đổi/trả/hoàn tiền trong vòng <strong>2-3 ngày làm việc</strong>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Section 4 -->
                <div class="border-l-4 border-red-600 pl-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                        <span class="w-10 h-10 bg-red-600 text-white rounded-full flex items-center justify-center mr-3 text-lg">4</span>
                        Trường Hợp KHÔNG Được Đổi Trả
                    </h2>
                    <div class="space-y-3 text-gray-700 leading-relaxed">
                        <p class="flex items-start">
                            <i class="fas fa-ban text-red-600 mt-1 mr-3"></i>
                            <span>Sản phẩm đã qua sử dụng, có dấu hiệu trầy xước, biến dạng</span>
                        </p>
                        <p class="flex items-start">
                            <i class="fas fa-ban text-red-600 mt-1 mr-3"></i>
                            <span>Quá thời hạn đổi trả (7 ngày)</span>
                        </p>
                        <p class="flex items-start">
                            <i class="fas fa-ban text-red-600 mt-1 mr-3"></i>
                            <span>Không có hóa đơn hoặc bằng chứng mua hàng</span>
                        </p>
                        <p class="flex items-start">
                            <i class="fas fa-ban text-red-600 mt-1 mr-3"></i>
                            <span>Sản phẩm làm theo yêu cầu riêng, thiết kế đặc biệt</span>
                        </p>
                    </div>
                </div>

                <!-- Contact CTA -->
                <div class="bg-gradient-to-r from-gold-600 to-gold-400 rounded-2xl p-8 text-center text-white mt-12">
                    <h3 class="text-2xl font-bold mb-3">Cần Hỗ Trợ Đổi Trả?</h3>
                    <p class="mb-6 text-white/90">Đội ngũ chúng tôi luôn sẵn sàng hỗ trợ bạn 24/7</p>
                    <div class="flex flex-wrap gap-4 justify-center">
                        <a href="tel:1900xxxx" class="bg-white text-gold-600 px-8 py-3 rounded-full font-semibold hover:bg-gray-100 transition inline-flex items-center">
                            <i class="fas fa-phone mr-2"></i>Gọi Hotline
                        </a>
                        <a href="contact.php" class="bg-white/20 backdrop-blur text-white px-8 py-3 rounded-full font-semibold hover:bg-white/30 transition inline-flex items-center">
                            <i class="fas fa-envelope mr-2"></i>Liên Hệ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
