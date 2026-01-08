<?php
// File xử lý thanh toán đơn hàng
require_once 'includes/config.php'; // Nạp file kết nối database
require_once 'includes/shipping-config.php'; // Nạp cấu hình phí vận chuyển

// Lấy giỏ hàng từ session
$giohang = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// Debug: Kiểm tra giỏ hàng
// echo '<pre>'; print_r($giohang); echo '</pre>'; exit;

// Kiểm tra giỏ hàng có trống không - Nếu trống thì quay lại trang giỏ hàng
if (empty($giohang)) {
    header('Location: cart.php');
    exit;
}

$thongbao = ''; // Biến lưu thông báo thành công
$loi = '';      // Biến lưu thông báo lỗi

// Khởi tạo biến để tương thích với code cũ
$cart = $giohang;
$message = $thongbao;
$error = $loi;

// Kiểm tra nếu người dùng nhấn nút Đặt hàng
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy thông tin từ form thanh toán
    $hoten = mysqli_real_escape_string($conn, trim($_POST['fullname']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $sodienthoai = mysqli_real_escape_string($conn, trim($_POST['phone']));
    $tinh_thanh = mysqli_real_escape_string($conn, trim($_POST['province']));
    $diachi_chitiet = mysqli_real_escape_string($conn, trim($_POST['address']));
    // Ghép địa chỉ đầy đủ
    $diachi = $diachi_chitiet . ', ' . $tinh_thanh;
    $ghichu = mysqli_real_escape_string($conn, trim($_POST['note']));
    $phuongthuc_thanhtoan = mysqli_real_escape_string($conn, $_POST['payment_method']);
    
    // Bước 1: Kiểm tra dữ liệu có bỏ trống không
    if (empty($hoten) || empty($email) || empty($sodienthoai) || empty($diachi_chitiet) || empty($tinh_thanh)) {
        $loi = 'Vui lòng điền đầy đủ thông tin bắt buộc!';
        $error = $loi; // Cập nhật biến $error
    } else {
        // Bước 2: Tính tổng tiền đơn hàng
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        // Tính phí vận chuyển dựa trên địa chỉ và tổng tiền
        $shipping = calculateShippingFee($diachi, $total);
        $total_amount = $total + $shipping;
        
        // Generate order code
        $order_code = 'ORD' . date('YmdHis') . rand(100, 999);
        
        // Get user_id if logged in
        $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'NULL';

        // Normalize variable names used in INSERT
        $fullname = $hoten;
        $phone = $sodienthoai;
        $address = $diachi;
        $note = $ghichu;
        $payment_method = $phuongthuc_thanhtoan;

        // Insert order
        $insert_order = "INSERT INTO orders (user_id, order_code, fullname, email, phone, address, note, total_amount, payment_method) 
                VALUES ($user_id, '$order_code', '$fullname', '$email', '$phone', '$address', '$note', $total_amount, '$payment_method')";
        
        if (mysqli_query($conn, $insert_order)) {
            $order_id = mysqli_insert_id($conn);
            
            // Insert order details
            foreach ($cart as $item) {
                $product_id = $item['id'];
                $product_name = mysqli_real_escape_string($conn, $item['name']);
                $product_image = $item['image'];
                $price = $item['price'];
                $quantity = $item['quantity'];
                $item_total = $price * $quantity;
                
                $insert_detail = "INSERT INTO order_details (order_id, product_id, product_name, product_image, price, quantity, total) 
                                VALUES ($order_id, $product_id, '$product_name', '$product_image', $price, $quantity, $item_total)";
                mysqli_query($conn, $insert_detail);
                
                // Update product quantity
                mysqli_query($conn, "UPDATE products SET quantity = quantity - $quantity WHERE id = $product_id");
            }
            
            // Xử lý theo phương thức thanh toán
            // Nếu COD: xóa giỏ hàng ngay và redirect success
            // Nếu Chuyển khoản: giữ giỏ hàng, redirect tới form upload
            if ($payment_method === 'cod') {
                // Clear cart for COD
                unset($_SESSION['cart']);
                // Redirect to success page
                header('Location: order-success.php?order_code=' . $order_code . '&payment_method=' . urlencode($payment_method));
                exit;
            } else {
                // For bank transfer: keep cart for now, will clear after upload confirmation
                // Don't unset cart yet
                header('Location: order-success.php?order_code=' . $order_code . '&payment_method=' . urlencode($payment_method));
                exit;
            }
        } else {
            $loi = 'Có lỗi xảy ra. Vui lòng thử lại!';
            $error = $loi; // Cập nhật biến $error
        }
    }
}

$page_title = 'Thanh Toán';
include 'includes/header.php';

$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}
// Phí vận chuyển sẽ được tính sau khi khách nhập địa chỉ
// Tạm tính với địa chỉ mặc định
$default_shipping = DEFAULT_SHIPPING_FEE;
if ($total >= FREE_SHIPPING_THRESHOLD) {
    $default_shipping = 0;
}
$shipping = $default_shipping;
$total_amount = $total + $shipping;
?>

<!-- Breadcrumb -->
<div class="bg-gradient-to-r from-amber-50 to-yellow-50 py-6">
    <div class="container mx-auto px-4">
        <div class="flex items-center text-sm text-gray-600">
            <a href="index.php" class="hover:text-amber-600 transition-colors">
                <i class="fas fa-home"></i> Trang chủ
            </a>
            <i class="fas fa-chevron-right mx-3 text-xs"></i>
            <a href="cart.php" class="hover:text-amber-600 transition-colors">Giỏ hàng</a>
            <i class="fas fa-chevron-right mx-3 text-xs"></i>
            <span class="text-amber-600 font-medium">Thanh toán</span>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 py-8 lg:py-12">
    <!-- Page Title -->
    <div class="text-center mb-8">
        <h1 class="text-3xl lg:text-4xl font-bold text-gray-800 mb-2">
            <i class="fas fa-shopping-bag text-amber-600"></i> Thanh Toán Đơn Hàng
        </h1>
        <div class="w-24 h-1 bg-gradient-to-r from-amber-400 to-yellow-500 mx-auto rounded-full"></div>
    </div>

    <?php if ($error): ?>
    <div class="max-w-2xl mx-auto mb-6">
        <div class="bg-red-50 border-2 border-red-500 rounded-xl p-4 flex items-center gap-3 text-red-700">
            <i class="fas fa-exclamation-circle text-2xl"></i>
            <span class="font-semibold"><?php echo $error; ?></span>
        </div>
    </div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Billing Info -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-amber-500 to-yellow-500 px-6 py-4">
                        <h3 class="text-xl font-bold text-white flex items-center gap-2">
                            <i class="fas fa-user-circle"></i>
                            <span>Thông Tin Nhận Hàng</span>
                        </h3>
                    </div>

                    <div class="p-6 lg:p-8">
                        <!-- Full Name -->
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-2">
                                <i class="fas fa-signature text-amber-500 mr-2"></i>
                                Họ và tên <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="fullname" required
                                   value="<?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : ''; ?>"
                                   placeholder="Nguyễn Văn A"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-amber-500 focus:ring-4 focus:ring-amber-100 transition-all outline-none">
                        </div>

                        <!-- Email & Phone -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">
                                    <i class="fas fa-envelope text-amber-500 mr-2"></i>
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" name="email" required
                                       value="<?php echo isset($_SESSION['user_email']) ? htmlspecialchars($_SESSION['user_email']) : ''; ?>"
                                       placeholder="example@gmail.com"
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-amber-500 focus:ring-4 focus:ring-amber-100 transition-all outline-none">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">
                                    <i class="fas fa-phone text-amber-500 mr-2"></i>
                                    Số điện thoại <span class="text-red-500">*</span>
                                </label>
                                <input type="tel" name="phone" required placeholder="0912345678"
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-amber-500 focus:ring-4 focus:ring-amber-100 transition-all outline-none">
                            </div>
                        </div>

                        <!-- Province/City -->
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-2">
                                <i class="fas fa-map-marked-alt text-amber-500 mr-2"></i>
                                Tỉnh/Thành phố <span class="text-red-500">*</span>
                            </label>
                            <select name="province" id="province-select" required
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-amber-500 focus:ring-4 focus:ring-amber-100 transition-all outline-none">
                                <option value="">-- Chọn Tỉnh/Thành phố --</option>
                                <?php
                                $provinces = getProvincesList();
                                foreach ($provinces as $province):
                                    $fee = getShippingFeeByProvince($province);
                                ?>
                                <option value="<?php echo htmlspecialchars($province); ?>" data-fee="<?php echo $fee; ?>">
                                    <?php echo htmlspecialchars($province); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Address -->
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-2">
                                <i class="fas fa-map-marker-alt text-amber-500 mr-2"></i>
                                Địa chỉ chi tiết <span class="text-red-500">*</span>
                            </label>
                            <textarea name="address" id="address-input" rows="3" required
                                      placeholder="Số nhà, tên đường, phường/xã, quận/huyện"
                                      class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-amber-500 focus:ring-4 focus:ring-amber-100 transition-all outline-none resize-none"></textarea>
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="fas fa-info-circle mr-1"></i>
                                Phí vận chuyển sẽ được tính dựa trên tỉnh/thành phố bạn chọn
                            </p>
                        </div>

                        <!-- Note -->
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-2">
                                <i class="fas fa-sticky-note text-amber-500 mr-2"></i>
                                Ghi chú đơn hàng
                            </label>
                            <textarea name="note" rows="3"
                                      placeholder="Ghi chú về đơn hàng: thời gian giao hàng, địa chỉ chi tiết..."
                                      class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-amber-500 focus:ring-4 focus:ring-amber-100 transition-all outline-none resize-none"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Payment Methods -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden mt-6">
                    <div class="bg-gradient-to-r from-amber-500 to-yellow-500 px-6 py-4">
                        <h3 class="text-xl font-bold text-white flex items-center gap-2">
                            <i class="fas fa-credit-card"></i>
                            <span>Phương Thức Thanh Toán</span>
                        </h3>
                    </div>

                    <div class="p-6 lg:p-8">
                        <!-- COD -->
                        <label class="payment-option-card block cursor-pointer mb-4">
                            <input type="radio" name="payment_method" value="cod" checked class="hidden payment-radio">
                            <div class="flex items-center p-5 border-2 border-gray-200 rounded-xl transition-all hover:border-amber-400 hover:shadow-md">
                                <div class="flex-shrink-0 w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center mr-4">
                                    <i class="fas fa-money-bill-wave text-2xl text-amber-600"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="font-bold text-gray-800 text-lg mb-1">Thanh toán khi nhận hàng (COD)</div>
                                    <div class="text-sm text-gray-500">Thanh toán bằng tiền mặt khi nhận hàng</div>
                                </div>
                                <div class="flex-shrink-0">
                                    <div class="w-6 h-6 rounded-full border-2 border-gray-300 flex items-center justify-center check-circle">
                                        <i class="fas fa-check text-white text-xs hidden check-icon"></i>
                                    </div>
                                </div>
                            </div>
                        </label>

                        <!-- Bank Transfer -->
                        <label class="payment-option-card block cursor-pointer">
                            <input type="radio" name="payment_method" value="bank_transfer" class="hidden payment-radio">
                            <div class="flex items-center p-5 border-2 border-gray-200 rounded-xl transition-all hover:border-amber-400 hover:shadow-md">
                                <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                                    <i class="fas fa-university text-2xl text-blue-600"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="font-bold text-gray-800 text-lg mb-1">Chuyển khoản ngân hàng</div>
                                    <div class="text-sm text-gray-500">Mã QR sẽ hiển thị sau khi bạn đặt hàng</div>
                                </div>
                                <div class="flex-shrink-0">
                                    <div class="w-6 h-6 rounded-full border-2 border-gray-300 flex items-center justify-center check-circle">
                                        <i class="fas fa-check text-white text-xs hidden check-icon"></i>
                                    </div>
                                </div>
                            </div>
                        </label>

                        <!-- Bank Transfer Info -->
                        <div id="bankInfo" class="hidden mt-4 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                            <div class="flex items-start gap-3">
                                <i class="fas fa-info-circle text-blue-500 text-xl mt-0.5"></i>
                                <div class="text-sm text-blue-800">
                                    <p class="font-semibold mb-1">Thanh toán chuyển khoản</p>
                                    <p>Sau khi đặt hàng, bạn sẽ nhận được mã QR để quét và thanh toán. Đơn hàng sẽ được xử lý sau khi chúng tôi xác nhận thanh toán của bạn.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden sticky top-24">
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-amber-500 to-yellow-500 px-6 py-4">
                        <h3 class="text-xl font-bold text-white flex items-center gap-2">
                            <i class="fas fa-receipt"></i>
                            <span>Đơn hàng của bạn</span>
                        </h3>
                    </div>

                    <div class="p-6">
                        <!-- Products List -->
                        <div class="max-h-80 overflow-y-auto mb-6 space-y-4">
                            <?php foreach ($cart as $item): ?>
                            <div class="flex gap-3 pb-4 border-b border-gray-100 last:border-0">
                                <div class="flex-shrink-0">
                                    <div class="w-16 h-16 rounded-lg overflow-hidden bg-gray-100">
                                        <img src="<?php echo BASE_URL; ?>assets/images/products/<?php echo htmlspecialchars($item['image']); ?>" 
                                             alt="<?php echo htmlspecialchars($item['name']); ?>"
                                             class="w-full h-full object-cover">
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-semibold text-gray-800 mb-1 line-clamp-2">
                                        <?php echo htmlspecialchars($item['name']); ?>
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        <?php echo number_format($item['price']); ?>đ × <?php echo $item['quantity']; ?>
                                    </div>
                                </div>
                                <div class="flex-shrink-0 text-right">
                                    <div class="text-sm font-bold text-amber-600">
                                        <?php echo number_format($item['price'] * $item['quantity']); ?>đ
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Price Summary -->
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between items-center text-gray-600">
                                <span class="flex items-center gap-2">
                                    <i class="fas fa-box text-amber-500"></i>
                                    <span>Tạm tính</span>
                                </span>
                                <span class="font-bold text-gray-800" id="subtotal"><?php echo number_format($total); ?>đ</span>
                            </div>
                            
                            <div class="flex justify-between items-center text-gray-600">
                                <span class="flex items-center gap-2">
                                    <i class="fas fa-shipping-fast text-amber-500"></i>
                                    <span>Phí vận chuyển</span>
                                </span>
                                <span class="font-bold text-gray-800" id="shipping-fee">
                                    <span class="text-gray-400 text-sm">Nhập địa chỉ để tính</span>
                                </span>
                            </div>

                            <?php if ($total < FREE_SHIPPING_THRESHOLD): ?>
                            <div class="bg-amber-50 border border-amber-200 rounded-lg p-3" id="free-shipping-notice">
                                <div class="flex items-start gap-2 text-sm text-amber-800">
                                    <i class="fas fa-info-circle mt-0.5"></i>
                                    <span>Mua thêm <strong id="remaining-amount"><?php echo number_format(FREE_SHIPPING_THRESHOLD - $total); ?>đ</strong> để được miễn phí vận chuyển!</span>
                                </div>
                            </div>
                            <?php else: ?>
                            <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                                <div class="flex items-start gap-2 text-sm text-green-800">
                                    <i class="fas fa-check-circle mt-0.5"></i>
                                    <span>Đơn hàng của bạn được <strong>miễn phí vận chuyển</strong>!</span>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- Total -->
                        <div class="border-t-2 border-gray-200 pt-4 mb-6">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-bold text-gray-800">Tổng cộng</span>
                                <span class="text-3xl font-bold text-amber-600" id="total-amount">
                                    <?php echo number_format($total_amount); ?>đ
                                </span>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full bg-gradient-to-r from-amber-500 to-yellow-500 text-white py-4 rounded-xl font-bold text-lg hover:from-amber-600 hover:to-yellow-600 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 mb-4">
                            <i class="fas fa-check-circle mr-2"></i>
                            Đặt hàng ngay
                        </button>

                        <!-- Back to Cart -->
                        <a href="cart.php" class="block text-center text-gray-600 hover:text-amber-600 transition-colors font-medium">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Quay lại giỏ hàng
                        </a>

                        <!-- Security Info -->
                        <div class="mt-6 pt-6 border-t border-gray-200 space-y-3">
                            <div class="flex items-center gap-3 text-sm text-gray-600">
                                <i class="fas fa-shield-alt text-green-500"></i>
                                <span>Thanh toán an toàn</span>
                            </div>
                            <div class="flex items-center gap-3 text-sm text-gray-600">
                                <i class="fas fa-truck text-blue-500"></i>
                                <span>Giao hàng toàn quốc</span>
                            </div>
                            <div class="flex items-center gap-3 text-sm text-gray-600">
                                <i class="fas fa-undo text-purple-500"></i>
                                <span>Đổi trả trong 7 ngày</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
.payment-option-card input[type="radio"]:checked + div {
    border-color: #f59e0b !important;
    background: linear-gradient(135deg, #fffbeb 0%, #fff 100%);
}

.payment-option-card input[type="radio"]:checked + div .check-circle {
    background: linear-gradient(135deg, #f59e0b, #eab308);
    border-color: #f59e0b;
}

.payment-option-card input[type="radio"]:checked + div .check-icon {
    display: inline !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function(){
    const bankInfo = document.getElementById('bankInfo');
    const paymentRadios = document.querySelectorAll('.payment-radio');
    const provinceSelect = document.getElementById('province-select');
    const addressInput = document.getElementById('address-input');
    const shippingFeeEl = document.getElementById('shipping-fee');
    const totalAmountEl = document.getElementById('total-amount');
    const freeShippingNotice = document.getElementById('free-shipping-notice');
    const remainingAmountEl = document.getElementById('remaining-amount');
    
    const subtotal = <?php echo $total; ?>;
    const freeShippingThreshold = <?php echo FREE_SHIPPING_THRESHOLD; ?>;
    
    let currentShippingFee = <?php echo $default_shipping; ?>;

    function updatePaymentMethod() {
        const selected = document.querySelector('.payment-radio:checked');
        
        // Show/hide bank transfer info
        if (bankInfo && selected && selected.value === 'bank_transfer') {
            bankInfo.classList.remove('hidden');
            bankInfo.classList.add('animate-fadeIn');
        } else if (bankInfo) {
            bankInfo.classList.add('hidden');
        }
    }

    function calculateShippingByProvince() {
        const selectedOption = provinceSelect.options[provinceSelect.selectedIndex];
        
        if (!selectedOption.value) {
            shippingFeeEl.innerHTML = '<span class="text-gray-400 text-sm">Chọn tỉnh/thành để tính</span>';
            return;
        }
        
        const provinceFee = parseInt(selectedOption.dataset.fee) || <?php echo DEFAULT_SHIPPING_FEE; ?>;
        const province = selectedOption.value;
        
        // Build full address
        const detailAddress = addressInput.value.trim();
        const fullAddress = detailAddress ? `${detailAddress}, ${province}` : province;
        
        // Show loading
        shippingFeeEl.innerHTML = '<span class="text-gray-400 text-sm"><i class="fas fa-spinner fa-spin mr-1"></i>Đang tính...</span>';
        
        // Call API to calculate shipping
        fetch('calculate-shipping.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `address=${encodeURIComponent(fullAddress)}&total=${subtotal}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                currentShippingFee = data.shipping_fee;
                
                // Update shipping fee display
                if (data.is_free_shipping) {
                    shippingFeeEl.innerHTML = '<span class="text-green-600 font-bold">Miễn phí</span>';
                    if (freeShippingNotice) {
                        freeShippingNotice.innerHTML = `
                            <div class="flex items-start gap-2 text-sm text-green-800">
                                <i class="fas fa-check-circle mt-0.5"></i>
                                <span>Đơn hàng của bạn được <strong>miễn phí vận chuyển</strong>!</span>
                            </div>
                        `;
                        freeShippingNotice.className = 'bg-green-50 border border-green-200 rounded-lg p-3';
                    }
                } else {
                    shippingFeeEl.innerHTML = `<span class="text-gray-800 font-bold">${data.shipping_fee_formatted}đ</span>`;
                    
                    // Update free shipping notice
                    if (freeShippingNotice && remainingAmountEl) {
                        const remaining = freeShippingThreshold - subtotal;
                        remainingAmountEl.textContent = remaining.toLocaleString('vi-VN') + 'đ';
                        freeShippingNotice.className = 'bg-amber-50 border border-amber-200 rounded-lg p-3';
                    }
                }
                
                // Update total amount
                totalAmountEl.textContent = data.total_amount_formatted + 'đ';
            } else {
                shippingFeeEl.innerHTML = '<span class="text-red-500 text-sm">Lỗi tính phí</span>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            shippingFeeEl.innerHTML = '<span class="text-red-500 text-sm">Lỗi kết nối</span>';
        });
    }

    // Listen to province selection
    if (provinceSelect) {
        provinceSelect.addEventListener('change', calculateShippingByProvince);
    }

    // Listen to address input changes (optional, for recalculation)
    if (addressInput) {
        let timeout;
        addressInput.addEventListener('input', function() {
            if (provinceSelect.value) {
                clearTimeout(timeout);
                timeout = setTimeout(calculateShippingByProvince, 1000);
            }
        });
    }

    paymentRadios.forEach(radio => {
        radio.addEventListener('change', updatePaymentMethod);
    });

    updatePaymentMethod();
});
</script>

<?php include 'includes/footer.php'; ?>
