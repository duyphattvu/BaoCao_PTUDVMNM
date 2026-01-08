<?php
require_once 'includes/config.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$order_code = isset($_GET['code']) ? mysqli_real_escape_string($conn, $_GET['code']) : '';

if (empty($order_code)) {
    header('Location: orders.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Lấy thông tin đơn hàng
$sql = "SELECT o.* 
        FROM orders o 
        WHERE o.order_code = '$order_code' AND o.user_id = $user_id";
$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) == 0) {
    header('Location: orders.php');
    exit;
}

$order = mysqli_fetch_assoc($result);
$page_title = 'Chi Tiết Đơn Hàng #' . $order_code;

// Lấy chi tiết sản phẩm
$order_id = $order['id'];
$details_sql = "SELECT * FROM order_details WHERE order_id = $order_id";
$details = mysqli_query($conn, $details_sql);

include 'includes/header.php';

// Lấy trạng thái đơn hàng
$order_status = isset($order['order_status']) && !empty($order['order_status']) ? $order['order_status'] : 'pending';

// Màu trạng thái
$status_colors = [
    'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'border' => 'border-yellow-300', 'icon' => 'fa-clock'],
    'confirmed' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'border' => 'border-blue-300', 'icon' => 'fa-check-circle'],
    'shipping' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800', 'border' => 'border-purple-300', 'icon' => 'fa-shipping-fast'],
    'completed' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'border' => 'border-green-300', 'icon' => 'fa-check-double'],
    'cancelled' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'border' => 'border-red-300', 'icon' => 'fa-times-circle']
];
$color = $status_colors[$order_status] ?? $status_colors['pending'];

$status_names = [
    'pending' => 'Chờ xác nhận',
    'confirmed' => 'Đã xác nhận',
    'shipping' => 'Đang giao hàng',
    'completed' => 'Hoàn thành',
    'cancelled' => 'Đã hủy'
];
$status_text = $status_names[$order_status] ?? 'Chờ xác nhận';
?>

<!-- Breadcrumb -->
<div class="bg-gradient-to-r from-amber-50 to-yellow-50 py-6 no-print">
    <div class="container mx-auto px-4">
        <div class="flex items-center text-sm text-gray-600">
            <a href="index.php" class="hover:text-amber-600 transition-colors">
                <i class="fas fa-home"></i> Trang chủ
            </a>
            <i class="fas fa-chevron-right mx-3 text-xs"></i>
            <a href="orders.php" class="hover:text-amber-600 transition-colors">Đơn hàng của tôi</a>
            <i class="fas fa-chevron-right mx-3 text-xs"></i>
            <span class="text-amber-600 font-medium">Chi tiết đơn hàng</span>
        </div>
    </div>
</div>

<div class="bg-gray-50 py-8 lg:py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-5xl mx-auto">
            <!-- Back Button -->
            <div class="mb-6 no-print">
                <a href="orders.php" class="inline-flex items-center text-gray-600 hover:text-amber-600 transition font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>Quay lại danh sách đơn hàng
                </a>
            </div>

            <!-- Invoice Card -->
            <div id="invoice" class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-amber-500 to-yellow-500 text-white px-6 lg:px-8 py-5">
                    <div class="flex flex-wrap justify-between items-start gap-4">
                        <div>
                            <h1 class="text-2xl lg:text-3xl font-bold mb-1">PHIẾU XUẤT HÀNG</h1>
                            <p class="text-white/90 text-sm">TRANG SỨC BẠC CAO CẤP</p>
                        </div>
                        <div class="text-right">
                            <div class="<?php echo $color['bg']; ?> <?php echo $color['text']; ?> px-4 py-2 rounded-full font-bold inline-flex items-center text-sm shadow-sm">
                                <i class="fas <?php echo $color['icon']; ?> mr-2"></i>
                                <?php echo $status_text; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6 lg:p-8">
                    <!-- Order Info -->
                    <div class="grid md:grid-cols-2 gap-5 mb-6 pb-6 border-b-2 border-gray-200">
                        <div class="bg-blue-50 rounded-lg p-4 border-l-4 border-blue-400">
                            <h3 class="text-base font-bold text-gray-900 mb-3 flex items-center">
                                <i class="fas fa-file-invoice text-blue-500 mr-2"></i>
                                Thông Tin Đơn Hàng
                            </h3>
                            <div class="space-y-2 text-sm">
                                <p class="flex">
                                    <span class="text-gray-600 w-32 flex-shrink-0">Mã đơn hàng:</span>
                                    <span class="font-bold text-gray-900"><?php echo $order['order_code']; ?></span>
                                </p>
                                <p class="flex">
                                    <span class="text-gray-600 w-32 flex-shrink-0">Ngày đặt:</span>
                                    <span class="text-gray-900"><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></span>
                                </p>
                                <p class="flex">
                                    <span class="text-gray-600 w-32 flex-shrink-0">Thanh toán:</span>
                                    <span class="text-gray-900">
                                        <?php echo $order['payment_method'] == 'cod' ? '<i class="fas fa-money-bill-wave text-green-500 mr-1"></i>COD' : '<i class="fas fa-university text-blue-500 mr-1"></i>Chuyển khoản'; ?>
                                    </span>
                                </p>
                                <p class="flex">
                                    <span class="text-gray-600 w-32 flex-shrink-0">TT Thanh toán:</span>
                                    <span class="font-semibold <?php echo $order['payment_status'] == 'paid' ? 'text-green-600' : 'text-orange-600'; ?>">
                                        <?php echo $order['payment_status'] == 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán'; ?>
                                    </span>
                                </p>
                            </div>
                        </div>

                        <div class="bg-amber-50 rounded-lg p-4 border-l-4 border-amber-400">
                            <h3 class="text-base font-bold text-gray-900 mb-3 flex items-center">
                                <i class="fas fa-user-circle text-amber-500 mr-2"></i>
                                Thông Tin Khách Hàng
                            </h3>
                            <div class="space-y-2 text-sm">
                                <p class="flex">
                                    <span class="text-gray-600 w-32 flex-shrink-0">Họ tên:</span>
                                    <span class="font-semibold text-gray-900"><?php echo $order['fullname']; ?></span>
                                </p>
                                <p class="flex">
                                    <span class="text-gray-600 w-32 flex-shrink-0">Điện thoại:</span>
                                    <span class="text-gray-900"><?php echo $order['phone']; ?></span>
                                </p>
                                <p class="flex">
                                    <span class="text-gray-600 w-32 flex-shrink-0">Email:</span>
                                    <span class="text-gray-900 break-all"><?php echo $order['email']; ?></span>
                                </p>
                                <p class="flex items-start">
                                    <span class="text-gray-600 w-32 flex-shrink-0">Địa chỉ:</span>
                                    <span class="text-gray-900 flex-1"><?php echo $order['address']; ?></span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Products Table -->
                    <div class="mb-6">
                        <h3 class="text-base font-bold text-gray-900 mb-3 flex items-center">
                            <i class="fas fa-shopping-bag text-amber-500 mr-2"></i>
                            Danh Sách Sản Phẩm
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="px-3 py-2.5 text-left font-bold text-gray-700">STT</th>
                                        <th class="px-3 py-2.5 text-left font-bold text-gray-700">Sản phẩm</th>
                                        <th class="px-3 py-2.5 text-center font-bold text-gray-700">Đơn giá</th>
                                        <th class="px-3 py-2.5 text-center font-bold text-gray-700">SL</th>
                                        <th class="px-3 py-2.5 text-right font-bold text-gray-700">Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $stt = 1;
                                    mysqli_data_seek($details, 0);
                                    while($item = mysqli_fetch_assoc($details)): 
                                    ?>
                                    <tr class="border-b border-gray-200 hover:bg-amber-50">
                                        <td class="px-3 py-3 text-gray-700"><?php echo $stt++; ?></td>
                                        <td class="px-3 py-3">
                                            <div class="flex items-center gap-2">
                                                <img src="<?php echo BASE_URL; ?>assets/images/products/<?php echo $item['product_image']; ?>" 
                                                     alt="" class="w-12 h-12 object-cover rounded-lg print-hide">
                                                <span class="font-semibold text-gray-900"><?php echo $item['product_name']; ?></span>
                                            </div>
                                        </td>
                                        <td class="px-3 py-3 text-center text-gray-700"><?php echo number_format($item['price']); ?>đ</td>
                                        <td class="px-3 py-3 text-center font-semibold text-gray-900"><?php echo $item['quantity']; ?></td>
                                        <td class="px-3 py-3 text-right font-bold text-amber-600"><?php echo number_format($item['total']); ?>đ</td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Note -->
                    <?php if ($order['note']): ?>
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 rounded-lg p-3 mb-6">
                        <p class="text-sm font-bold text-gray-900 mb-1">
                            <i class="fas fa-sticky-note text-yellow-500 mr-2"></i>Ghi chú:
                        </p>
                        <p class="text-sm text-gray-700"><?php echo nl2br($order['note']); ?></p>
                    </div>
                    <?php endif; ?>

                    <!-- Total -->
                    <div class="bg-gradient-to-r from-amber-50 to-yellow-50 rounded-xl p-5 border-2 border-amber-200">
                        <div class="flex justify-between items-center text-xl lg:text-2xl font-bold">
                            <span class="text-gray-900">TỔNG CỘNG:</span>
                            <span class="text-amber-600"><?php echo number_format($order['total_amount']); ?>đ</span>
                        </div>
                        <p class="text-xs lg:text-sm text-gray-600 mt-2 text-right italic">
                            (<?php 
                            // Hàm chuyển số thành chữ đơn giản
                            function num_to_words($num) {
                                $ones = ['', 'một', 'hai', 'ba', 'bốn', 'năm', 'sáu', 'bảy', 'tám', 'chín'];
                                $tens = ['', '', 'hai mươi', 'ba mươi', 'bốn mươi', 'năm mươi', 'sáu mươi', 'bảy mươi', 'tám mươi', 'chín mươi'];
                                $hundreds = ['', 'một trăm', 'hai trăm', 'ba trăm', 'bốn trăm', 'năm trăm', 'sáu trăm', 'bảy trăm', 'tám trăm', 'chín trăm'];
                                
                                if ($num < 10) return $ones[$num];
                                if ($num < 100) {
                                    $t = floor($num / 10);
                                    $o = $num % 10;
                                    return $tens[$t] . ($o ? ' ' . $ones[$o] : '');
                                }
                                if ($num < 1000) {
                                    $h = floor($num / 100);
                                    $r = $num % 100;
                                    return $hundreds[$h] . ($r ? ' ' . num_to_words($r) : '');
                                }
                                if ($num < 1000000) {
                                    $k = floor($num / 1000);
                                    $r = $num % 1000;
                                    return num_to_words($k) . ' nghìn' . ($r ? ' ' . num_to_words($r) : '');
                                }
                                $m = floor($num / 1000000);
                                $r = $num % 1000000;
                                return num_to_words($m) . ' triệu' . ($r ? ' ' . num_to_words($r) : '');
                            }
                            echo ucfirst(num_to_words($order['total_amount']));
                            ?> đồng)
                        </p>
                    </div>

                    <!-- Signatures -->
                    <div class="grid grid-cols-2 gap-8 mt-12 pt-8 border-t-2 border-gray-200 print-show">
                        <div class="text-center">
                            <p class="font-bold text-gray-900 mb-16">Người nhận hàng</p>
                            <p class="text-sm text-gray-600">(Ký, ghi rõ họ tên)</p>
                        </div>
                        <div class="text-center">
                            <p class="font-bold text-gray-900 mb-2">Người bán hàng</p>
                            <p class="text-sm text-gray-500 mb-12"><?php echo date('d/m/Y'); ?></p>
                            <p class="text-sm text-gray-600">(Ký, đóng dấu)</p>
                        </div>
                    </div>

                    <!-- Footer Note -->
                    <div class="mt-8 text-center text-sm text-gray-500 print-show">
                        <p>Cảm ơn quý khách đã mua hàng tại Trang Sức Bạc!</p>
                        <p>Hotline: 1900-xxxx | Email: info@trangsuc.com</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-3 mt-6 no-print">
                <button onclick="window.print()" 
                        class="flex-1 bg-gradient-to-r from-amber-500 to-yellow-500 text-white px-6 py-3 rounded-xl font-bold hover:shadow-lg transition">
                    <i class="fas fa-print mr-2"></i>In Phiếu Xuất
                </button>
                <a href="orders.php" 
                   class="flex-1 bg-gray-100 text-gray-700 px-6 py-3 rounded-xl font-bold hover:bg-gray-200 transition text-center">
                    <i class="fas fa-list mr-2"></i>Danh Sách Đơn
                </a>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    #invoice, #invoice * {
        visibility: visible;
    }
    #invoice {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        box-shadow: none !important;
        border-radius: 0 !important;
    }
    .no-print {
        display: none !important;
    }
    .print-hide {
        display: none !important;
    }
    .print-show {
        display: block !important;
    }
}
.print-show {
    display: none;
}
</style>

<script>
function downloadPDF() {
    window.print();
}
</script>

<?php include 'includes/footer.php'; ?>
