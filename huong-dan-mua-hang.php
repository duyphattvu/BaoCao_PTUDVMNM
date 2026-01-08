<?php
require_once 'includes/config.php';
$page_title = 'Hướng Dẫn Mua Hàng';
include 'includes/header.php';
?>

<div class="bg-gradient-to-br from-green-50 via-white to-blue-50 py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-12">
                <div class="inline-block bg-gradient-to-r from-green-600 to-emerald-600 text-white px-6 py-2 rounded-full mb-4">
                    <i class="fas fa-shopping-bag mr-2"></i>HƯỚNG DẪN
                </div>
                <h1 class="text-5xl font-bold text-gray-900 mb-4">Hướng Dẫn Mua Hàng</h1>
                <p class="text-gray-600 text-lg">Quy trình mua hàng đơn giản, nhanh chóng chỉ 4 bước</p>
            </div>

            <!-- Content -->
            <div class="space-y-6">
                <!-- Step 1 -->
                <div class="bg-white rounded-3xl shadow-xl p-8 border-l-8 border-green-600 hover:shadow-2xl transition-all duration-300">
                    <div class="flex items-start gap-6">
                        <div class="flex-shrink-0">
                            <div class="w-20 h-20 bg-gradient-to-br from-green-600 to-emerald-600 rounded-2xl flex items-center justify-center text-white text-3xl font-bold shadow-lg">
                                1
                            </div>
                        </div>
                        <div class="flex-1">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">
                                <i class="fas fa-search text-green-600 mr-2"></i>Chọn Sản Phẩm
                            </h2>
                            <div class="space-y-3 text-gray-700">
                                <p class="flex items-start">
                                    <i class="fas fa-chevron-right text-green-600 mt-1 mr-3"></i>
                                    <span>Duyệt qua danh mục sản phẩm hoặc sử dụng thanh tìm kiếm để tìm sản phẩm mong muốn</span>
                                </p>
                                <p class="flex items-start">
                                    <i class="fas fa-chevron-right text-green-600 mt-1 mr-3"></i>
                                    <span>Xem chi tiết sản phẩm: hình ảnh, giá cả, mô tả, kích thước...</span>
                                </p>
                                <p class="flex items-start">
                                    <i class="fas fa-chevron-right text-green-600 mt-1 mr-3"></i>
                                    <span>Chọn số lượng và nhấn <strong>"Thêm vào giỏ hàng"</strong></span>
                                </p>
                            </div>
                            <div class="mt-4 flex gap-3">
                                <a href="<?php echo BASE_URL; ?>products.php" class="inline-flex items-center text-green-600 hover:text-green-700 font-semibold">
                                    <i class="fas fa-arrow-right mr-2"></i>Xem sản phẩm
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="bg-white rounded-3xl shadow-xl p-8 border-l-8 border-blue-600 hover:shadow-2xl transition-all duration-300">
                    <div class="flex items-start gap-6">
                        <div class="flex-shrink-0">
                            <div class="w-20 h-20 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center text-white text-3xl font-bold shadow-lg">
                                2
                            </div>
                        </div>
                        <div class="flex-1">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">
                                <i class="fas fa-shopping-cart text-blue-600 mr-2"></i>Kiểm Tra Giỏ Hàng
                            </h2>
                            <div class="space-y-3 text-gray-700">
                                <p class="flex items-start">
                                    <i class="fas fa-chevron-right text-blue-600 mt-1 mr-3"></i>
                                    <span>Nhấn vào <strong>icon giỏ hàng</strong> ở góc trên cùng để xem danh sách sản phẩm</span>
                                </p>
                                <p class="flex items-start">
                                    <i class="fas fa-chevron-right text-blue-600 mt-1 mr-3"></i>
                                    <span>Kiểm tra lại số lượng, tổng tiền và áp dụng mã giảm giá (nếu có)</span>
                                </p>
                                <p class="flex items-start">
                                    <i class="fas fa-chevron-right text-blue-600 mt-1 mr-3"></i>
                                    <span>Có thể cập nhật số lượng hoặc xóa sản phẩm không mong muốn</span>
                                </p>
                            </div>
                            <div class="mt-4 bg-blue-50 border-l-4 border-blue-600 p-4 rounded-lg">
                                <p class="text-sm text-gray-700">
                                    <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                                    <strong>Lưu ý:</strong> Đơn hàng từ <strong>999.000đ</strong> trở lên được <strong>MIỄN PHÍ VẬN CHUYỂN</strong>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="bg-white rounded-3xl shadow-xl p-8 border-l-8 border-purple-600 hover:shadow-2xl transition-all duration-300">
                    <div class="flex items-start gap-6">
                        <div class="flex-shrink-0">
                            <div class="w-20 h-20 bg-gradient-to-br from-purple-600 to-pink-600 rounded-2xl flex items-center justify-center text-white text-3xl font-bold shadow-lg">
                                3
                            </div>
                        </div>
                        <div class="flex-1">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">
                                <i class="fas fa-file-invoice text-purple-600 mr-2"></i>Điền Thông Tin & Thanh Toán
                            </h2>
                            <div class="space-y-3 text-gray-700">
                                <p class="flex items-start">
                                    <i class="fas fa-chevron-right text-purple-600 mt-1 mr-3"></i>
                                    <span>Nhấn <strong>"Thanh toán"</strong> từ trang giỏ hàng</span>
                                </p>
                                <p class="flex items-start">
                                    <i class="fas fa-chevron-right text-purple-600 mt-1 mr-3"></i>
                                    <span>Điền đầy đủ thông tin nhận hàng: Họ tên, Email, SĐT, Địa chỉ</span>
                                </p>
                                <p class="flex items-start">
                                    <i class="fas fa-chevron-right text-purple-600 mt-1 mr-3"></i>
                                    <span>Chọn phương thức thanh toán:</span>
                                </p>
                            </div>
                            <div class="mt-4 grid md:grid-cols-2 gap-4">
                                <div class="bg-gradient-to-br from-gold-50 to-yellow-50 border-2 border-gold-200 rounded-xl p-4">
                                    <div class="flex items-center mb-2">
                                        <i class="fas fa-money-bill-wave text-gold-600 text-2xl mr-3"></i>
                                        <h4 class="font-bold text-gray-900">COD</h4>
                                    </div>
                                    <p class="text-sm text-gray-700">Thanh toán khi nhận hàng</p>
                                </div>
                                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-xl p-4">
                                    <div class="flex items-center mb-2">
                                        <i class="fas fa-university text-blue-600 text-2xl mr-3"></i>
                                        <h4 class="font-bold text-gray-900">Chuyển khoản</h4>
                                    </div>
                                    <p class="text-sm text-gray-700">Quét mã QR ngân hàng</p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <p class="flex items-start">
                                    <i class="fas fa-chevron-right text-purple-600 mt-1 mr-3"></i>
                                    <span>Kiểm tra lại thông tin và nhấn <strong>"Đặt hàng"</strong></span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 4 -->
                <div class="bg-white rounded-3xl shadow-xl p-8 border-l-8 border-orange-600 hover:shadow-2xl transition-all duration-300">
                    <div class="flex items-start gap-6">
                        <div class="flex-shrink-0">
                            <div class="w-20 h-20 bg-gradient-to-br from-orange-600 to-red-600 rounded-2xl flex items-center justify-center text-white text-3xl font-bold shadow-lg">
                                4
                            </div>
                        </div>
                        <div class="flex-1">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">
                                <i class="fas fa-box text-orange-600 mr-2"></i>Nhận Hàng & Đánh Giá
                            </h2>
                            <div class="space-y-3 text-gray-700">
                                <p class="flex items-start">
                                    <i class="fas fa-chevron-right text-orange-600 mt-1 mr-3"></i>
                                    <span>Nhận email xác nhận đơn hàng với <strong>mã đơn hàng</strong></span>
                                </p>
                                <p class="flex items-start">
                                    <i class="fas fa-chevron-right text-orange-600 mt-1 mr-3"></i>
                                    <span>Shop liên hệ xác nhận đơn trong vòng <strong>24h</strong></span>
                                </p>
                                <p class="flex items-start">
                                    <i class="fas fa-chevron-right text-orange-600 mt-1 mr-3"></i>
                                    <span>Thời gian giao hàng: <strong>2-5 ngày</strong> (tùy khu vực)</span>
                                </p>
                                <p class="flex items-start">
                                    <i class="fas fa-chevron-right text-orange-600 mt-1 mr-3"></i>
                                    <span>Kiểm tra sản phẩm kỹ trước khi thanh toán (với COD)</span>
                                </p>
                            </div>
                            <div class="mt-4 bg-orange-50 border-l-4 border-orange-600 p-4 rounded-lg">
                                <p class="text-sm text-gray-700 mb-2">
                                    <i class="fas fa-gift text-orange-600 mr-2"></i>
                                    <strong>Quà tặng kèm:</strong>
                                </p>
                                <ul class="text-sm text-gray-700 space-y-1 ml-6">
                                    <li>✓ Hộp đựng trang sức cao cấp</li>
                                    <li>✓ Túi giấy đựng sang trọng</li>
                                    <li>✓ Phiếu bảo hành chính hãng</li>
                                    <li>✓ Khăn lau chuyên dụng</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Info -->
                <div class="bg-gradient-to-r from-gold-600 to-gold-400 rounded-3xl shadow-xl p-8 text-white">
                    <h3 class="text-2xl font-bold mb-6 text-center">
                        <i class="fas fa-question-circle mr-2"></i>Câu Hỏi Thường Gặp
                    </h3>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-bold mb-2 flex items-center">
                                <i class="fas fa-truck mr-2"></i>Phí vận chuyển?
                            </h4>
                            <p class="text-white/90 text-sm">
                                50.000đ (Đơn < 500.000đ)<br/>
                                MIỄN PHÍ (Đơn ≥ 999.000đ)
                            </p>
                        </div>
                        <div>
                            <h4 class="font-bold mb-2 flex items-center">
                                <i class="fas fa-clock mr-2"></i>Thời gian giao hàng?
                            </h4>
                            <p class="text-white/90 text-sm">
                                Nội thành: 1-2 ngày<br/>
                                Ngoại thành: 3-5 ngày
                            </p>
                        </div>
                        <div>
                            <h4 class="font-bold mb-2 flex items-center">
                                <i class="fas fa-undo mr-2"></i>Có được đổi trả?
                            </h4>
                            <p class="text-white/90 text-sm">
                                Có, trong vòng 7 ngày<br/>
                                <a href="chinh-sach-doi-tra.php" class="underline hover:text-white">Xem chi tiết</a>
                            </p>
                        </div>
                        <div>
                            <h4 class="font-bold mb-2 flex items-center">
                                <i class="fas fa-shield-alt mr-2"></i>Bảo hành bao lâu?
                            </h4>
                            <p class="text-white/90 text-sm">
                                6-12 tháng (tùy sản phẩm)<br/>
                                <a href="chinh-sach-bao-hanh.php" class="underline hover:text-white">Xem chi tiết</a>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- CTA -->
                <div class="text-center bg-white rounded-3xl shadow-xl p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Sẵn Sàng Mua Sắm?</h3>
                    <p class="text-gray-600 mb-6">Khám phá bộ sưu tập trang sức bạc cao cấp của chúng tôi</p>
                    <div class="flex flex-wrap gap-4 justify-center">
                        <a href="<?php echo BASE_URL; ?>products.php" class="bg-gradient-to-r from-green-600 to-emerald-600 text-white px-8 py-4 rounded-full font-semibold hover:shadow-lg transition inline-flex items-center text-lg">
                            <i class="fas fa-shopping-bag mr-2"></i>Mua Ngay
                        </a>
                        <a href="<?php echo BASE_URL; ?>contact.php" class="bg-gray-100 text-gray-800 px-8 py-4 rounded-full font-semibold hover:bg-gray-200 transition inline-flex items-center text-lg">
                            <i class="fas fa-phone mr-2"></i>Liên Hệ Tư Vấn
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
