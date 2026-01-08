<?php
require_once 'includes/config.php';
$page_title = 'Phương Thức Thanh Toán';
include 'includes/header.php';
?>

<div class="bg-gradient-to-br from-indigo-50 via-white to-purple-50 py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-12">
                <div class="inline-block bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-2 rounded-full mb-4">
                    <i class="fas fa-credit-card mr-2"></i>THANH TOÁN
                </div>
                <h1 class="text-5xl font-bold text-gray-900 mb-4">Phương Thức Thanh Toán</h1>
                <p class="text-gray-600 text-lg">Nhiều lựa chọn thanh toán linh hoạt, an toàn và tiện lợi</p>
            </div>

            <!-- Payment Methods -->
            <div class="space-y-6">
                <!-- COD -->
                <div class="bg-white rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-300">
                    <div class="bg-gradient-to-r from-gold-600 to-gold-400 p-6">
                        <div class="flex items-center justify-between text-white">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 bg-white/20 backdrop-blur rounded-2xl flex items-center justify-center">
                                    <i class="fas fa-money-bill-wave text-3xl"></i>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold">Thanh Toán Khi Nhận Hàng (COD)</h2>
                                    <p class="text-white/90">Phương thức phổ biến nhất</p>
                                </div>
                            </div>
                            <div class="bg-green-500 px-4 py-2 rounded-full text-sm font-bold">
                                PHỔ BIẾN
                            </div>
                        </div>
                    </div>
                    <div class="p-8">
                        <div class="space-y-4">
                            <div class="flex items-start gap-3">
                                <i class="fas fa-check-circle text-green-500 text-xl mt-1"></i>
                                <div>
                                    <h3 class="font-bold text-gray-900 mb-1">Thanh toán trực tiếp</h3>
                                    <p class="text-gray-600">Quý khách thanh toán bằng tiền mặt khi nhận hàng từ shipper</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <i class="fas fa-check-circle text-green-500 text-xl mt-1"></i>
                                <div>
                                    <h3 class="font-bold text-gray-900 mb-1">Kiểm tra trước khi thanh toán</h3>
                                    <p class="text-gray-600">Được phép mở hộp kiểm tra sản phẩm trước khi thanh toán</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <i class="fas fa-check-circle text-green-500 text-xl mt-1"></i>
                                <div>
                                    <h3 class="font-bold text-gray-900 mb-1">An toàn & tiện lợi</h3>
                                    <p class="text-gray-600">Không cần tài khoản ngân hàng, không lo bị lừa đảo</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 p-4 bg-yellow-50 border-l-4 border-yellow-500 rounded-lg">
                            <p class="text-sm text-gray-700">
                                <i class="fas fa-info-circle text-yellow-600 mr-2"></i>
                                <strong>Lưu ý:</strong> Vui lòng chuẩn bị đủ tiền mặt để thanh toán cho shipper
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Bank Transfer -->
                <div class="bg-white rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-300">
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-6">
                        <div class="flex items-center justify-between text-white">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 bg-white/20 backdrop-blur rounded-2xl flex items-center justify-center">
                                    <i class="fas fa-university text-3xl"></i>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold">Chuyển Khoản Ngân Hàng</h2>
                                    <p class="text-white/90">Nhanh chóng & bảo mật</p>
                                </div>
                            </div>
                            <div class="bg-blue-400 px-4 py-2 rounded-full text-sm font-bold">
                                ƯU ĐÃI
                            </div>
                        </div>
                    </div>
                    <div class="p-8">
                        <div class="grid md:grid-cols-2 gap-6 mb-6">
                            <!-- QR Code -->
                            <div class="text-center">
                                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border-2 border-blue-200">
                                    <h3 class="font-bold text-gray-900 mb-4 flex items-center justify-center">
                                        <i class="fas fa-qrcode text-blue-600 text-xl mr-2"></i>
                                        Quét Mã QR
                                    </h3>
                                    <img src="<?php echo BASE_URL; ?>assets/images/qr/qr_bank.png" 
                                         alt="QR Code" 
                                         class="max-w-[200px] mx-auto rounded-xl shadow-lg mb-4">
                                    <p class="text-sm text-gray-600">Quét mã bằng app ngân hàng để chuyển khoản nhanh</p>
                                </div>
                            </div>
                            
                            <!-- Bank Info -->
                            <div>
                                <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-6 border-2 border-gray-200 space-y-4">
                                    <h3 class="font-bold text-gray-900 mb-4 flex items-center">
                                        <i class="fas fa-building text-blue-600 text-xl mr-2"></i>
                                        Thông Tin Tài Khoản
                                    </h3>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Ngân hàng</p>
                                        <p class="font-bold text-gray-900">MB Bank</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Số tài khoản</p>
                                        <p class="font-bold text-gray-900 text-lg">6699990318</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Chủ tài khoản</p>
                                        <p class="font-bold text-gray-900">NGUYEN DUY PHAT</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Chi nhánh</p>
                                        <p class="font-bold text-gray-900">Trà Vinh</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-start gap-3">
                                <i class="fas fa-check-circle text-blue-500 text-xl mt-1"></i>
                                <div>
                                    <h3 class="font-bold text-gray-900 mb-1">Nội dung chuyển khoản</h3>
                                    <p class="text-gray-600">Ghi rõ: <strong>Họ tên + Số điện thoại + Mã đơn hàng</strong></p>
                                    <p class="text-sm text-gray-500 mt-1">Ví dụ: NGUYEN VAN A 0912345678 ORD20250108001</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <i class="fas fa-check-circle text-blue-500 text-xl mt-1"></i>
                                <div>
                                    <h3 class="font-bold text-gray-900 mb-1">Xác nhận nhanh chóng</h3>
                                    <p class="text-gray-600">Shop xác nhận đơn hàng trong vòng 30 phút sau khi nhận tiền</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <i class="fas fa-check-circle text-blue-500 text-xl mt-1"></i>
                                <div>
                                    <h3 class="font-bold text-gray-900 mb-1">Giao hàng ưu tiên</h3>
                                    <p class="text-gray-600">Đơn chuyển khoản được ưu tiên đóng gói và giao hàng trước</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 p-4 bg-blue-50 border-l-4 border-blue-500 rounded-lg">
                            <p class="text-sm text-gray-700 mb-2">
                                <i class="fas fa-gift text-blue-600 mr-2"></i>
                                <strong>Ưu đãi:</strong> Giảm thêm 50.000đ cho đơn hàng từ 2.000.000đ thanh toán chuyển khoản
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Momo (Coming Soon) -->
                <div class="bg-white rounded-3xl shadow-xl overflow-hidden opacity-60">
                    <div class="bg-gradient-to-r from-pink-600 to-red-600 p-6">
                        <div class="flex items-center justify-between text-white">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 bg-white/20 backdrop-blur rounded-2xl flex items-center justify-center">
                                    <i class="fas fa-mobile-alt text-3xl"></i>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold">Ví Điện Tử (MoMo, ZaloPay)</h2>
                                    <p class="text-white/90">Thanh toán siêu tốc</p>
                                </div>
                            </div>
                            <div class="bg-yellow-500 px-4 py-2 rounded-full text-sm font-bold">
                                SẮP RA MẮT
                            </div>
                        </div>
                    </div>
                    <div class="p-8">
                        <p class="text-center text-gray-600">
                            <i class="fas fa-clock text-2xl mb-3 block"></i>
                            Chức năng thanh toán qua ví điện tử đang được phát triển<br/>
                            Vui lòng chọn phương thức thanh toán khác
                        </p>
                    </div>
                </div>

                <!-- Security -->
                <div class="bg-gradient-to-r from-green-600 to-emerald-600 rounded-3xl shadow-xl p-8 text-white">
                    <h3 class="text-2xl font-bold mb-6 text-center">
                        <i class="fas fa-shield-alt mr-2"></i>Cam Kết Bảo Mật & An Toàn
                    </h3>
                    <div class="grid md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <i class="fas fa-lock text-4xl mb-3 opacity-80"></i>
                            <h4 class="font-bold mb-2">Bảo Mật Thông Tin</h4>
                            <p class="text-white/90 text-sm">Thông tin thanh toán được mã hóa SSL 256-bit</p>
                        </div>
                        <div class="text-center">
                            <i class="fas fa-user-shield text-4xl mb-3 opacity-80"></i>
                            <h4 class="font-bold mb-2">Bảo Vệ Khách Hàng</h4>
                            <p class="text-white/90 text-sm">Hoàn tiền 100% nếu có sai sót</p>
                        </div>
                        <div class="text-center">
                            <i class="fas fa-headset text-4xl mb-3 opacity-80"></i>
                            <h4 class="font-bold mb-2">Hỗ Trợ 24/7</h4>
                            <p class="text-white/90 text-sm">Luôn sẵn sàng giải đáp thắc mắc</p>
                        </div>
                    </div>
                </div>

                <!-- CTA -->
                <div class="text-center bg-white rounded-3xl shadow-xl p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Cần Hỗ Trợ Thanh Toán?</h3>
                    <p class="text-gray-600 mb-6">Liên hệ ngay để được tư vấn chi tiết</p>
                    <div class="flex flex-wrap gap-4 justify-center">
                        <a href="tel:1900xxxx" class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-8 py-4 rounded-full font-semibold hover:shadow-lg transition inline-flex items-center text-lg">
                            <i class="fas fa-phone mr-2"></i>Hotline: 0983592506
                        </a>
                        <a href="<?php echo BASE_URL; ?>contact.php" class="bg-gray-100 text-gray-800 px-8 py-4 rounded-full font-semibold hover:bg-gray-200 transition inline-flex items-center text-lg">
                            <i class="fas fa-envelope mr-2"></i>Liên Hệ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
