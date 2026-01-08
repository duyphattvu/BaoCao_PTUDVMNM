<?php
require_once 'includes/config.php';
$page_title = 'Đơn Hàng Của Tôi';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
    exit;
}

$user_id = $_SESSION['user_id'];

// Lọc theo trạng thái
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$where_status = $status_filter ? "AND order_status = '$status_filter'" : '';

// Lấy danh sách đơn hàng của user
$sql = "SELECT * FROM orders WHERE user_id = $user_id $where_status ORDER BY created_at DESC";
$orders_query = mysqli_query($conn, $sql);

// Kiểm tra query thành công
if (!$orders_query) {
    die("Lỗi truy vấn: " . mysqli_error($conn));
}
$orders = $orders_query;

// Đếm số lượng đơn hàng theo từng trạng thái
$count_sql = "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN order_status = 'pending' OR order_status IS NULL THEN 1 ELSE 0 END) as pending,
    SUM(CASE WHEN order_status = 'confirmed' THEN 1 ELSE 0 END) as confirmed,
    SUM(CASE WHEN order_status = 'shipping' THEN 1 ELSE 0 END) as shipping,
    SUM(CASE WHEN order_status = 'completed' THEN 1 ELSE 0 END) as completed,
    SUM(CASE WHEN order_status = 'cancelled' THEN 1 ELSE 0 END) as cancelled
    FROM orders WHERE user_id = $user_id";
$count_query = mysqli_query($conn, $count_sql);

// Khởi tạo giá trị mặc định
$count_result = [
    'total' => 0,
    'pending' => 0,
    'confirmed' => 0,
    'shipping' => 0,
    'completed' => 0,
    'cancelled' => 0
];

// Lấy kết quả nếu query thành công
if ($count_query && mysqli_num_rows($count_query) > 0) {
    $temp = mysqli_fetch_assoc($count_query);
    $count_result = [
        'total' => (int)$temp['total'],
        'pending' => (int)$temp['pending'],
        'confirmed' => (int)$temp['confirmed'],
        'shipping' => (int)$temp['shipping'],
        'completed' => (int)$temp['completed'],
        'cancelled' => (int)$temp['cancelled']
    ];
}

include 'includes/header.php';
?>

<!-- Breadcrumb -->
<div class="bg-gradient-to-r from-amber-50 to-yellow-50 py-6">
    <div class="container mx-auto px-4">
        <div class="flex items-center text-sm text-gray-600">
            <a href="index.php" class="hover:text-amber-600 transition-colors">
                <i class="fas fa-home"></i> Trang chủ
            </a>
            <i class="fas fa-chevron-right mx-3 text-xs"></i>
            <span class="text-amber-600 font-medium">Đơn hàng của tôi</span>
        </div>
    </div>
</div>

<div class="bg-gray-50 py-8 lg:py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl lg:text-4xl font-bold text-gray-800 mb-2">
                    <i class="fas fa-shopping-bag text-amber-600"></i> Đơn Hàng Của Tôi
                </h1>
                <div class="w-24 h-1 bg-gradient-to-r from-amber-400 to-yellow-500 mx-auto rounded-full mb-3"></div>
                <p class="text-gray-600">Quản lý và theo dõi tất cả đơn hàng của bạn</p>
            </div>

            <!-- Status Filter Tabs -->
            <div class="bg-white rounded-2xl shadow-md p-4 mb-6 overflow-x-auto">
                <div class="flex gap-2 min-w-max">
                    <a href="orders.php" class="<?php echo !$status_filter ? 'bg-gradient-to-r from-amber-500 to-yellow-500 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'; ?> px-5 py-2.5 rounded-xl font-semibold transition-all duration-300 flex items-center gap-2 whitespace-nowrap">
                        <i class="fas fa-list"></i>
                        <span>Tất cả</span>
                        <span class="<?php echo !$status_filter ? 'bg-white/30' : 'bg-gray-200'; ?> px-2 py-0.5 rounded-full text-xs font-bold"><?php echo $count_result['total']; ?></span>
                    </a>
                    <a href="orders.php?status=pending" class="<?php echo $status_filter == 'pending' ? 'bg-gradient-to-r from-yellow-500 to-yellow-400 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'; ?> px-5 py-2.5 rounded-xl font-semibold transition-all duration-300 flex items-center gap-2 whitespace-nowrap">
                        <i class="fas fa-clock"></i>
                        <span>Chờ xác nhận</span>
                        <span class="<?php echo $status_filter == 'pending' ? 'bg-white/30' : 'bg-gray-200'; ?> px-2 py-0.5 rounded-full text-xs font-bold"><?php echo $count_result['pending']; ?></span>
                    </a>
                    <a href="orders.php?status=confirmed" class="<?php echo $status_filter == 'confirmed' ? 'bg-gradient-to-r from-blue-500 to-blue-400 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'; ?> px-5 py-2.5 rounded-xl font-semibold transition-all duration-300 flex items-center gap-2 whitespace-nowrap">
                        <i class="fas fa-check-circle"></i>
                        <span>Đã xác nhận</span>
                        <span class="<?php echo $status_filter == 'confirmed' ? 'bg-white/30' : 'bg-gray-200'; ?> px-2 py-0.5 rounded-full text-xs font-bold"><?php echo $count_result['confirmed']; ?></span>
                    </a>
                    <a href="orders.php?status=shipping" class="<?php echo $status_filter == 'shipping' ? 'bg-gradient-to-r from-purple-500 to-purple-400 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'; ?> px-5 py-2.5 rounded-xl font-semibold transition-all duration-300 flex items-center gap-2 whitespace-nowrap">
                        <i class="fas fa-shipping-fast"></i>
                        <span>Đang giao</span>
                        <span class="<?php echo $status_filter == 'shipping' ? 'bg-white/30' : 'bg-gray-200'; ?> px-2 py-0.5 rounded-full text-xs font-bold"><?php echo $count_result['shipping']; ?></span>
                    </a>
                    <a href="orders.php?status=completed" class="<?php echo $status_filter == 'completed' ? 'bg-gradient-to-r from-green-500 to-green-400 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'; ?> px-5 py-2.5 rounded-xl font-semibold transition-all duration-300 flex items-center gap-2 whitespace-nowrap">
                        <i class="fas fa-check-double"></i>
                        <span>Đã giao</span>
                        <span class="<?php echo $status_filter == 'completed' ? 'bg-white/30' : 'bg-gray-200'; ?> px-2 py-0.5 rounded-full text-xs font-bold"><?php echo $count_result['completed']; ?></span>
                    </a>
                    <a href="orders.php?status=cancelled" class="<?php echo $status_filter == 'cancelled' ? 'bg-gradient-to-r from-red-500 to-red-400 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'; ?> px-5 py-2.5 rounded-xl font-semibold transition-all duration-300 flex items-center gap-2 whitespace-nowrap">
                        <i class="fas fa-times-circle"></i>
                        <span>Đã hủy</span>
                        <span class="<?php echo $status_filter == 'cancelled' ? 'bg-white/30' : 'bg-gray-200'; ?> px-2 py-0.5 rounded-full text-xs font-bold"><?php echo $count_result['cancelled']; ?></span>
                    </a>
                </div>
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

            <?php if (mysqli_num_rows($orders) > 0): ?>
                <div class="space-y-6">
                    <?php while($order = mysqli_fetch_assoc($orders)): ?>
                        <?php
                        // Lấy chi tiết đơn hàng
                        $order_id = $order['id'];
                        $details_sql = "SELECT * FROM order_details WHERE order_id = $order_id";
                        $details = mysqli_query($conn, $details_sql);
                        
                        // Xác định màu status
                        $status_colors = [
                            'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'border' => 'border-yellow-300', 'icon' => 'fa-clock'],
                            'confirmed' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'border' => 'border-blue-300', 'icon' => 'fa-check-circle'],
                            'shipping' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800', 'border' => 'border-purple-300', 'icon' => 'fa-shipping-fast'],
                            'completed' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'border' => 'border-green-300', 'icon' => 'fa-check-double'],
                            'cancelled' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'border' => 'border-red-300', 'icon' => 'fa-times-circle']
                        ];
                        
                        // Lấy status, nếu không có thì mặc định là pending
                        $order_status = isset($order['order_status']) && !empty($order['order_status']) ? $order['order_status'] : 'pending';
                        $color = $status_colors[$order_status] ?? $status_colors['pending'];
                        
                        // Tên trạng thái
                        $status_names = [
                            'pending' => 'Chờ xác nhận',
                            'confirmed' => 'Đã xác nhận',
                            'shipping' => 'Đang giao hàng',
                            'completed' => 'Hoàn thành',
                            'cancelled' => 'Đã hủy'
                        ];
                        $status_text = $status_names[$order_status] ?? 'Chờ xác nhận';
                        ?>
                        
                        <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-all duration-300 border border-gray-100">
                            <!-- Order Header -->
                            <div class="bg-gradient-to-r from-amber-500 to-yellow-500 px-4 lg:px-6 py-3">
                                <div class="flex flex-wrap items-center justify-between gap-3">
                                    <div class="flex items-center gap-3 lg:gap-4">
                                        <div>
                                            <p class="text-xs text-white/80 mb-0.5">Mã đơn hàng</p>
                                            <p class="font-bold text-white text-base lg:text-lg"><?php echo $order['order_code']; ?></p>
                                        </div>
                                        <div class="h-10 w-px bg-white/30"></div>
                                        <div>
                                            <p class="text-xs text-white/80 mb-0.5">Ngày đặt</p>
                                            <p class="font-semibold text-white text-sm">
                                                <i class="far fa-calendar-alt mr-1"></i>
                                                <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="<?php echo $color['bg']; ?> <?php echo $color['text']; ?> px-3 py-1.5 rounded-full font-bold text-xs lg:text-sm inline-flex items-center shadow-sm">
                                            <i class="fas <?php echo $color['icon']; ?> mr-1.5"></i>
                                            <?php echo $status_text; ?>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Order Items -->
                            <div class="p-4 lg:p-6">
                                <div class="space-y-3 mb-5">
                                    <?php while($item = mysqli_fetch_assoc($details)): ?>
                                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg hover:bg-amber-50 transition">
                                        <img src="<?php echo BASE_URL; ?>assets/images/products/<?php echo $item['product_image']; ?>" 
                                             alt="<?php echo $item['product_name']; ?>"
                                             class="w-16 h-16 lg:w-20 lg:h-20 object-cover rounded-lg shadow-sm">
                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-semibold text-gray-900 mb-1 text-sm lg:text-base line-clamp-2"><?php echo $item['product_name']; ?></h4>
                                            <p class="text-xs lg:text-sm text-gray-600">
                                                <span class="font-semibold text-amber-600"><?php echo number_format($item['price']); ?>đ</span>
                                                <span class="mx-1.5">×</span>
                                                <span><?php echo $item['quantity']; ?></span>
                                            </p>
                                        </div>
                                        <div class="text-right flex-shrink-0">
                                            <p class="text-xs text-gray-500 mb-0.5">Thành tiền</p>
                                            <p class="font-bold text-base lg:text-lg text-amber-600"><?php echo number_format($item['total']); ?>đ</p>
                                        </div>
                                    </div>
                                    <?php endwhile; ?>
                                </div>

                                <!-- Order Info -->
                                <div class="grid md:grid-cols-2 gap-4 mb-5">
                                    <div class="bg-blue-50 rounded-lg p-4 border-l-4 border-blue-400">
                                        <h4 class="font-bold text-gray-900 mb-2.5 flex items-center text-sm">
                                            <i class="fas fa-user-circle text-blue-500 mr-2"></i>
                                            Thông Tin Nhận Hàng
                                        </h4>
                                        <div class="space-y-1.5 text-xs lg:text-sm">
                                            <p class="flex items-start gap-2">
                                                <i class="fas fa-user text-gray-400 w-4 mt-0.5 flex-shrink-0"></i>
                                                <span class="text-gray-700"><?php echo $order['fullname']; ?></span>
                                            </p>
                                            <p class="flex items-start gap-2">
                                                <i class="fas fa-phone text-gray-400 w-4 mt-0.5 flex-shrink-0"></i>
                                                <span class="text-gray-700"><?php echo $order['phone']; ?></span>
                                            </p>
                                            <p class="flex items-start gap-2">
                                                <i class="fas fa-envelope text-gray-400 w-4 mt-0.5 flex-shrink-0"></i>
                                                <span class="text-gray-700 break-all"><?php echo $order['email']; ?></span>
                                            </p>
                                            <p class="flex items-start gap-2">
                                                <i class="fas fa-map-marker-alt text-gray-400 w-4 mt-0.5 flex-shrink-0"></i>
                                                <span class="text-gray-700"><?php echo $order['address']; ?></span>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="bg-amber-50 rounded-lg p-4 border-l-4 border-amber-400">
                                        <h4 class="font-bold text-gray-900 mb-2.5 flex items-center text-sm">
                                            <i class="fas fa-credit-card text-amber-500 mr-2"></i>
                                            Thanh Toán
                                        </h4>
                                        <div class="space-y-1.5 text-xs lg:text-sm">
                                            <p class="flex justify-between items-center">
                                                <span class="text-gray-600">Phương thức:</span>
                                                <span class="font-semibold text-gray-900">
                                                    <?php 
                                                    echo $order['payment_method'] == 'cod' 
                                                        ? '<i class="fas fa-money-bill-wave mr-1 text-green-500"></i>COD' 
                                                        : '<i class="fas fa-university mr-1 text-blue-500"></i>Chuyển khoản'; 
                                                    ?>
                                                </span>
                                            </p>
                                            <p class="flex justify-between items-center">
                                                <span class="text-gray-600">Trạng thái:</span>
                                                <span class="font-semibold <?php echo $order['payment_status'] == 'paid' ? 'text-green-600' : 'text-orange-600'; ?>">
                                                    <?php echo $order['payment_status'] == 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán'; ?>
                                                </span>
                                            </p>
                                            <div class="border-t border-amber-200 my-2"></div>
                                            <p class="flex justify-between items-center text-base lg:text-lg">
                                                <span class="font-bold text-gray-900">Tổng tiền:</span>
                                                <span class="font-bold text-amber-600"><?php echo number_format($order['total_amount']); ?>đ</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <?php if ($order['note']): ?>
                                <div class="bg-yellow-50 rounded-lg p-3 border-l-4 border-yellow-400 mb-5">
                                    <h4 class="font-bold text-gray-900 mb-1.5 flex items-center text-sm">
                                        <i class="fas fa-sticky-note text-yellow-500 mr-2"></i>
                                        Ghi Chú
                                    </h4>
                                    <p class="text-gray-700 text-xs lg:text-sm"><?php echo nl2br($order['note']); ?></p>
                                </div>
                                <?php endif; ?>

                                <!-- Actions -->
                                <div class="flex flex-wrap gap-2">
                                    <a href="order-detail.php?code=<?php echo $order['order_code']; ?>" 
                                       class="flex-1 bg-gradient-to-r from-amber-500 to-yellow-500 text-white px-4 py-2.5 rounded-lg font-semibold hover:shadow-lg transition text-center text-sm">
                                        <i class="fas fa-eye mr-1.5"></i>Xem Chi Tiết
                                    </a>
                                    <?php if ($order_status == 'pending'): ?>
                                    <button onclick="if(confirm('Bạn có chắc muốn hủy đơn hàng này?')) window.location.href='cancel-order.php?code=<?php echo $order['order_code']; ?>'" 
                                            class="bg-red-100 text-red-700 px-4 py-2.5 rounded-lg font-semibold hover:bg-red-200 transition text-sm">
                                        <i class="fas fa-times-circle mr-1.5"></i>Hủy Đơn
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <!-- Empty State -->
                <div class="bg-white rounded-2xl shadow-md p-12 lg:p-16 text-center">
                    <div class="w-24 h-24 lg:w-32 lg:h-32 bg-gradient-to-br from-amber-100 to-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-shopping-bag text-amber-400 text-5xl lg:text-6xl"></i>
                    </div>
                    <h3 class="text-xl lg:text-2xl font-bold text-gray-900 mb-2">Chưa Có Đơn Hàng Nào</h3>
                    <p class="text-gray-600 mb-6 lg:mb-8">Bạn chưa có đơn hàng nào. Hãy khám phá và mua sắm ngay!</p>
                    <a href="<?php echo BASE_URL; ?>products.php" 
                       class="inline-flex items-center bg-gradient-to-r from-amber-500 to-yellow-500 text-white px-6 lg:px-8 py-3 lg:py-4 rounded-full font-semibold hover:shadow-lg transition text-base lg:text-lg">
                        <i class="fas fa-shopping-cart mr-2"></i>Mua Sắm Ngay
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
@media print {
    header, footer, .no-print {
        display: none !important;
    }
    .container {
        max-width: 100% !important;
    }
}
</style>

<?php include 'includes/footer.php'; ?>
