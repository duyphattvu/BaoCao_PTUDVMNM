<?php
require_once 'includes/config.php';
$page_title = 'Lịch Sử Mua Hàng';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
    exit;
}

$user_id = $_SESSION['user_id'];

// Thống kê tổng quan
$stats_sql = "SELECT 
    COUNT(*) as total_orders,
    SUM(CASE WHEN order_status = 'completed' THEN 1 ELSE 0 END) as completed_orders,
    SUM(CASE WHEN order_status = 'completed' THEN total_amount ELSE 0 END) as total_spent,
    SUM(CASE WHEN order_status = 'cancelled' THEN 1 ELSE 0 END) as cancelled_orders
    FROM orders WHERE user_id = $user_id";
$stats_result = mysqli_query($conn, $stats_sql);
$stats = mysqli_fetch_assoc($stats_result);

// Lọc theo năm/tháng
$year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
$month = isset($_GET['month']) ? (int)$_GET['month'] : 0;

$date_filter = "YEAR(created_at) = $year";
if ($month > 0) {
    $date_filter .= " AND MONTH(created_at) = $month";
}

// Lấy danh sách đơn hàng
$sql = "SELECT o.*, 
        (SELECT SUM(quantity) FROM order_details WHERE order_id = o.id) as total_items
        FROM orders o 
        WHERE user_id = $user_id AND $date_filter 
        ORDER BY created_at DESC";
$orders = mysqli_query($conn, $sql);

// Lấy danh sách năm có đơn hàng
$years_sql = "SELECT DISTINCT YEAR(created_at) as year FROM orders WHERE user_id = $user_id ORDER BY year DESC";
$years = mysqli_query($conn, $years_sql);

include 'includes/header.php';
?>

<div class="bg-gradient-to-br from-blue-50 via-white to-purple-50 py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-10">
                <h1 class="text-4xl font-bold text-gray-900 mb-3">
                    <i class="fas fa-history text-gold-600 mr-3"></i>Lịch Sử Mua Hàng
                </h1>
                <p class="text-gray-600">Xem lại tất cả các đơn hàng bạn đã mua</p>
            </div>

            <!-- Thống kê -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between mb-2">
                        <i class="fas fa-shopping-bag text-3xl opacity-80"></i>
                        <span class="text-4xl font-bold"><?php echo $stats['total_orders']; ?></span>
                    </div>
                    <p class="text-blue-100">Tổng đơn hàng</p>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between mb-2">
                        <i class="fas fa-check-circle text-3xl opacity-80"></i>
                        <span class="text-4xl font-bold"><?php echo $stats['completed_orders']; ?></span>
                    </div>
                    <p class="text-green-100">Hoàn thành</p>
                </div>

                <div class="bg-gradient-to-br from-gold-500 to-gold-600 rounded-2xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between mb-2">
                        <i class="fas fa-coins text-3xl opacity-80"></i>
                        <span class="text-2xl font-bold"><?php echo number_format($stats['total_spent']); ?>đ</span>
                    </div>
                    <p class="text-gold-100">Tổng chi tiêu</p>
                </div>

                <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-2xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between mb-2">
                        <i class="fas fa-times-circle text-3xl opacity-80"></i>
                        <span class="text-4xl font-bold"><?php echo $stats['cancelled_orders']; ?></span>
                    </div>
                    <p class="text-red-100">Đã hủy</p>
                </div>
            </div>

            <!-- Filter -->
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
                <form method="GET" class="flex flex-wrap gap-4 items-end">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-gray-700 font-semibold mb-2">
                            <i class="far fa-calendar mr-2"></i>Chọn năm
                        </label>
                        <select name="year" class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-gold-500 focus:outline-none">
                            <?php mysqli_data_seek($years, 0); while($y = mysqli_fetch_assoc($years)): ?>
                            <option value="<?php echo $y['year']; ?>" <?php echo $year == $y['year'] ? 'selected' : ''; ?>>
                                Năm <?php echo $y['year']; ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-gray-700 font-semibold mb-2">
                            <i class="far fa-calendar-alt mr-2"></i>Chọn tháng
                        </label>
                        <select name="month" class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-gold-500 focus:outline-none">
                            <option value="0" <?php echo $month == 0 ? 'selected' : ''; ?>>Tất cả các tháng</option>
                            <?php for($m = 1; $m <= 12; $m++): ?>
                            <option value="<?php echo $m; ?>" <?php echo $month == $m ? 'selected' : ''; ?>>
                                Tháng <?php echo $m; ?>
                            </option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <button type="submit" class="px-8 py-3 bg-gradient-to-r from-gold-600 to-gold-500 text-white rounded-lg font-bold hover:shadow-xl transition-all hover:scale-105">
                        <i class="fas fa-filter mr-2"></i>Lọc
                    </button>

                    <a href="purchase-history.php" class="px-8 py-3 bg-gray-200 text-gray-700 rounded-lg font-bold hover:bg-gray-300 transition">
                        <i class="fas fa-redo mr-2"></i>Đặt lại
                    </a>
                </form>
            </div>

            <!-- Danh sách đơn hàng -->
            <?php if (mysqli_num_rows($orders) > 0): ?>
                <div class="space-y-6">
                    <?php while($order = mysqli_fetch_assoc($orders)): ?>
                        <?php
                        // Lấy chi tiết đơn hàng
                        $order_id = $order['id'];
                        $details_sql = "SELECT od.*, p.image as product_image 
                                       FROM order_details od 
                                       LEFT JOIN products p ON od.product_id = p.id 
                                       WHERE od.order_id = $order_id";
                        $details = mysqli_query($conn, $details_sql);
                        
                        // Màu trạng thái
                        $status_colors = [
                            'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'icon' => 'fa-clock'],
                            'confirmed' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'icon' => 'fa-check-circle'],
                            'shipping' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800', 'icon' => 'fa-shipping-fast'],
                            'completed' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'icon' => 'fa-check-double'],
                            'cancelled' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'icon' => 'fa-times-circle']
                        ];
                        
                        $order_status = $order['order_status'] ?? 'pending';
                        $color = $status_colors[$order_status] ?? $status_colors['pending'];
                        
                        $status_names = [
                            'pending' => 'Chờ xác nhận',
                            'confirmed' => 'Đã xác nhận',
                            'shipping' => 'Đang giao',
                            'completed' => 'Hoàn thành',
                            'cancelled' => 'Đã hủy'
                        ];
                        $status_text = $status_names[$order_status] ?? 'Chờ xác nhận';
                        ?>
                        
                        <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition">
                            <!-- Header -->
                            <div class="bg-gray-50 px-6 py-4 border-b flex flex-wrap items-center justify-between gap-4">
                                <div class="flex items-center gap-6">
                                    <div>
                                        <p class="text-xs text-gray-500">Mã đơn</p>
                                        <p class="font-bold text-gray-900"><?php echo $order['order_code']; ?></p>
                                    </div>
                                    <div class="h-10 w-px bg-gray-300"></div>
                                    <div>
                                        <p class="text-xs text-gray-500">Ngày đặt</p>
                                        <p class="font-semibold text-gray-700">
                                            <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?>
                                        </p>
                                    </div>
                                    <div class="h-10 w-px bg-gray-300"></div>
                                    <div>
                                        <p class="text-xs text-gray-500">Số sản phẩm</p>
                                        <p class="font-semibold text-gray-700"><?php echo $order['total_items']; ?> sản phẩm</p>
                                    </div>
                                </div>
                                <span class="<?php echo $color['bg']; ?> <?php echo $color['text']; ?> px-4 py-2 rounded-full font-bold text-sm">
                                    <i class="fas <?php echo $color['icon']; ?> mr-2"></i><?php echo $status_text; ?>
                                </span>
                            </div>

                            <!-- Products -->
                            <div class="p-6">
                                <div class="space-y-3 mb-4">
                                    <?php while($item = mysqli_fetch_assoc($details)): ?>
                                    <div class="flex items-center gap-4 p-3 bg-gray-50 rounded-lg">
                                        <img src="assets/images/products/<?php echo $item['product_image']; ?>" 
                                             class="w-16 h-16 object-cover rounded-lg">
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-gray-900"><?php echo $item['product_name']; ?></h4>
                                            <p class="text-sm text-gray-500">Số lượng: <?php echo $item['quantity']; ?></p>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-bold text-gold-600"><?php echo number_format($item['price']); ?>đ</p>
                                        </div>
                                    </div>
                                    <?php endwhile; ?>
                                </div>

                                <!-- Total -->
                                <div class="border-t pt-4 flex items-center justify-between">
                                    <div>
                                        <p class="text-gray-600">Phương thức thanh toán:</p>
                                        <p class="font-semibold">
                                            <?php echo $order['payment_method'] == 'cod' ? 'Thanh toán khi nhận hàng' : 'Chuyển khoản ngân hàng'; ?>
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-gray-600 mb-1">Tổng tiền:</p>
                                        <p class="text-2xl font-bold text-gold-600"><?php echo number_format($order['total_amount']); ?>đ</p>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="mt-4 flex gap-3">
                                    <a href="order-detail.php?id=<?php echo $order['id']; ?>" 
                                       class="flex-1 text-center px-6 py-3 bg-gold-600 text-white rounded-lg hover:bg-gold-700 font-semibold transition">
                                        <i class="fas fa-file-invoice mr-2"></i>Xem chi tiết
                                    </a>
                                    <?php if ($order_status == 'completed'): ?>
                                    <a href="products.php" 
                                       class="flex-1 text-center px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold transition">
                                        <i class="fas fa-redo mr-2"></i>Mua lại
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                    <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Chưa có đơn hàng nào</h3>
                    <p class="text-gray-600 mb-6">Bạn chưa có đơn hàng nào trong khoảng thời gian này</p>
                    <a href="products.php" class="inline-block px-8 py-3 bg-gradient-to-r from-gold-600 to-gold-500 text-white rounded-lg font-bold hover:shadow-xl transition">
                        <i class="fas fa-shopping-bag mr-2"></i>Mua sắm ngay
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
