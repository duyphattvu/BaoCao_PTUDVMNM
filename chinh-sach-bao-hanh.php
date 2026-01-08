<?php
require_once 'includes/config.php';
$page_title = 'Chính Sách Bảo Hành';
include 'includes/header.php';
?>

<div class="bg-gradient-to-br from-purple-50 via-white to-blue-50 py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-12">
                <div class="inline-block bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-2 rounded-full mb-4">
                    <i class="fas fa-shield-alt mr-2"></i>BẢO HÀNH
                </div>
                <h1 class="text-5xl font-bold text-gray-900 mb-4">Chính Sách Bảo Hành</h1>
                <p class="text-gray-600 text-lg">Cam kết chất lượng - Bảo vệ quyền lợi khách hàng lâu dài</p>
            </div>

            <!-- Content -->
            <div class="bg-white rounded-3xl shadow-2xl p-8 md:p-12 space-y-8">
                <!-- Section 1 -->
                <div class="border-l-4 border-blue-600 pl-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                        <span class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center mr-3 text-lg">1</span>
                        Thời Gian Bảo Hành
                    </h2>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="bg-gradient-to-br from-gold-50 to-yellow-50 rounded-xl p-5 border-2 border-gold-200">
                            <div class="flex items-center mb-3">
                                <i class="fas fa-gem text-gold-600 text-2xl mr-3"></i>
                                <h3 class="font-bold text-lg text-gray-900">Sản phẩm bạc</h3>
                            </div>
                            <p class="text-gray-700">Bảo hành <strong class="text-gold-600">6 tháng</strong> kể từ ngày mua</p>
                        </div>
                        <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl p-5 border-2 border-purple-200">
                            <div class="flex items-center mb-3">
                                <i class="fas fa-ring text-purple-600 text-2xl mr-3"></i>
                                <h3 class="font-bold text-lg text-gray-900">Trang sức đá quý</h3>
                            </div>
                            <p class="text-gray-700">Bảo hành <strong class="text-purple-600">12 tháng</strong> kể từ ngày mua</p>
                        </div>
                    </div>
                </div>

                <!-- Section 2 -->
                <div class="border-l-4 border-green-600 pl-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                        <span class="w-10 h-10 bg-green-600 text-white rounded-full flex items-center justify-center mr-3 text-lg">2</span>
                        Nội Dung Bảo Hành
                    </h2>
                    <div class="space-y-3 text-gray-700 leading-relaxed">
                        <p class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-3 flex-shrink-0"></i>
                            <span><strong>Làm sạch, đánh bóng miễn phí:</strong> Giữ sản phẩm luôn sáng bóng như mới</span>
                        </p>
                        <p class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-3 flex-shrink-0"></i>
                            <span><strong>Sửa chữa miễn phí:</strong> Khắc phục các lỗi do nhà sản xuất (gãy khóa, rơi đá, nứt mối hàn...)</span>
                        </p>
                        <p class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-3 flex-shrink-0"></i>
                            <span><strong>Thay đá miễn phí:</strong> Nếu đá bị rơi, bong tróc trong thời gian bảo hành</span>
                        </p>
                        <p class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-3 flex-shrink-0"></i>
                            <span><strong>Chỉnh size:</strong> Chỉnh lại kích thước nhẫn, lắc phù hợp (giới hạn 2 lần)</span>
                        </p>
                        <p class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-3 flex-shrink-0"></i>
                            <span><strong>Kiểm tra định kỳ:</strong> Kiểm tra chất lượng, độ bền của sản phẩm</span>
                        </p>
                    </div>
                </div>

                <!-- Section 3 -->
                <div class="border-l-4 border-orange-600 pl-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                        <span class="w-10 h-10 bg-orange-600 text-white rounded-full flex items-center justify-center mr-3 text-lg">3</span>
                        Điều Kiện Bảo Hành
                    </h2>
                    <div class="space-y-3 text-gray-700 leading-relaxed">
                        <p class="flex items-start">
                            <i class="fas fa-check text-blue-500 mt-1 mr-3"></i>
                            <span>Sản phẩm phải còn trong thời hạn bảo hành</span>
                        </p>
                        <p class="flex items-start">
                            <i class="fas fa-check text-blue-500 mt-1 mr-3"></i>
                            <span>Có <strong>Phiếu bảo hành</strong> hoặc <strong>Hóa đơn mua hàng</strong> hợp lệ</span>
                        </p>
                        <p class="flex items-start">
                            <i class="fas fa-check text-blue-500 mt-1 mr-3"></i>
                            <span>Sản phẩm bị hư hỏng do lỗi kỹ thuật, không do tác động ngoại lực</span>
                        </p>
                        <p class="flex items-start">
                            <i class="fas fa-check text-blue-500 mt-1 mr-3"></i>
                            <span>Không có dấu hiệu sửa chữa tại nơi khác</span>
                        </p>
                    </div>
                </div>

                <!-- Section 4 -->
                <div class="border-l-4 border-red-600 pl-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                        <span class="w-10 h-10 bg-red-600 text-white rounded-full flex items-center justify-center mr-3 text-lg">4</span>
                        Trường Hợp KHÔNG Bảo Hành
                    </h2>
                    <div class="space-y-3 text-gray-700 leading-relaxed">
                        <p class="flex items-start">
                            <i class="fas fa-times-circle text-red-600 mt-1 mr-3"></i>
                            <span>Sản phẩm hết thời hạn bảo hành</span>
                        </p>
                        <p class="flex items-start">
                            <i class="fas fa-times-circle text-red-600 mt-1 mr-3"></i>
                            <span>Hư hỏng do va đập mạnh, rơi vỡ, biến dạng do tác động ngoại lực</span>
                        </p>
                        <p class="flex items-start">
                            <i class="fas fa-times-circle text-red-600 mt-1 mr-3"></i>
                            <span>Sử dụng không đúng cách, tiếp xúc hóa chất ăn mòn (nước biển, clo, xà phòng...)</span>
                        </p>
                        <p class="flex items-start">
                            <i class="fas fa-times-circle text-red-600 mt-1 mr-3"></i>
                            <span>Đã sửa chữa, can thiệp tại nơi khác không phải của cửa hàng</span>
                        </p>
                        <p class="flex items-start">
                            <i class="fas fa-times-circle text-red-600 mt-1 mr-3"></i>
                            <span>Không có phiếu bảo hành hoặc hóa đơn mua hàng</span>
                        </p>
                    </div>
                </div>

                <!-- Section 5 -->
                <div class="border-l-4 border-purple-600 pl-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                        <span class="w-10 h-10 bg-purple-600 text-white rounded-full flex items-center justify-center mr-3 text-lg">5</span>
                        Quy Trình Bảo Hành
                    </h2>
                    <div class="space-y-4">
                        <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-4 border-l-4 border-purple-600">
                            <p class="font-semibold text-gray-900 mb-2">
                                <i class="fas fa-store text-purple-600 mr-2"></i>Bước 1: Mang sản phẩm đến cửa hàng
                            </p>
                            <p class="text-gray-700 text-sm">
                                Mang sản phẩm + phiếu bảo hành/hóa đơn đến cửa hàng gần nhất
                            </p>
                        </div>
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4 border-l-4 border-blue-600">
                            <p class="font-semibold text-gray-900 mb-2">
                                <i class="fas fa-clipboard-check text-blue-600 mr-2"></i>Bước 2: Kiểm tra & tiếp nhận
                            </p>
                            <p class="text-gray-700 text-sm">
                                Nhân viên kiểm tra tình trạng sản phẩm và xác nhận bảo hành
                            </p>
                        </div>
                        <div class="bg-gradient-to-r from-orange-50 to-yellow-50 rounded-xl p-4 border-l-4 border-orange-600">
                            <p class="font-semibold text-gray-900 mb-2">
                                <i class="fas fa-tools text-orange-600 mr-2"></i>Bước 3: Sửa chữa
                            </p>
                            <p class="text-gray-700 text-sm">
                                Thời gian sửa chữa: <strong>3-7 ngày làm việc</strong> tùy mức độ hư hỏng
                            </p>
                        </div>
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-4 border-l-4 border-green-600">
                            <p class="font-semibold text-gray-900 mb-2">
                                <i class="fas fa-gift text-green-600 mr-2"></i>Bước 4: Nhận sản phẩm
                            </p>
                            <p class="text-gray-700 text-sm">
                                Cửa hàng thông báo và bạn đến nhận sản phẩm đã được bảo hành
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Tips -->
                <div class="bg-gradient-to-r from-blue-100 to-purple-100 rounded-2xl p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-lightbulb text-yellow-500 text-2xl mr-3"></i>
                        Mẹo Bảo Quản Trang Sức
                    </h3>
                    <ul class="space-y-2 text-gray-700">
                        <li>✓ Tháo trang sức khi tắm, bơi, tập thể dục</li>
                        <li>✓ Tránh tiếp xúc với nước biển, clo, hóa chất</li>
                        <li>✓ Bảo quản trong hộp riêng, tránh va đập</li>
                        <li>✓ Lau sạch sau mỗi lần đeo bằng vải mềm</li>
                        <li>✓ Mang đến cửa hàng vệ sinh định kỳ 3-6 tháng/lần</li>
                    </ul>
                </div>

                <!-- Contact CTA -->
                <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl p-8 text-center text-white mt-12">
                    <h3 class="text-2xl font-bold mb-3">Cần Hỗ Trợ Bảo Hành?</h3>
                    <p class="mb-6 text-white/90">Liên hệ ngay để được tư vấn chi tiết</p>
                    <div class="flex flex-wrap gap-4 justify-center">
                        <a href="tel:1900xxxx" class="bg-white text-blue-600 px-8 py-3 rounded-full font-semibold hover:bg-gray-100 transition inline-flex items-center">
                            <i class="fas fa-phone mr-2"></i>Hotline: 1900-xxxx
                        </a>
                        <a href="contact.php" class="bg-white/20 backdrop-blur text-white px-8 py-3 rounded-full font-semibold hover:bg-white/30 transition inline-flex items-center">
                            <i class="fas fa-map-marker-alt mr-2"></i>Đến Cửa Hàng
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
