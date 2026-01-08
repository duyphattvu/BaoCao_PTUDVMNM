<?php
// File trang chủ Admin - Dashboard (Bảng điều khiển)
require_once 'check_admin.php'; // Kiểm tra quyền admin

$page_title = 'Bảng Điều Khiển'; // Tiêu đề trang

// Lấy các thống kê từ database
// Đếm tổng số sản phẩm
$tong_sanpham = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM products"))['count'];

// Đếm tổng số đơn hàng
$tong_donhang = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM orders"))['count'];

// Đếm tổng số khách hàng (không tính admin)
$tong_khachhang = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM users WHERE role = 'user'"))['count'];

// Tính tổng doanh thu (tính đơn đã thanh toán HOẶC đã hoàn thành)
$tong_doanhthu = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_amount) as total FROM orders WHERE payment_status = 'paid' OR order_status = 'completed'"))['total'] ?: 0;

// Lấy tháng và năm hiện tại hoặc từ filter
$selected_month = isset($_GET['month']) ? (int)$_GET['month'] : date('n');
$selected_year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');

// Tính doanh thu theo tháng được chọn
$doanhthu_thang = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_amount) as total FROM orders WHERE (payment_status = 'paid' OR order_status = 'completed') AND MONTH(created_at) = $selected_month AND YEAR(created_at) = $selected_year"))['total'] ?: 0;

// Đếm đơn hàng trong tháng
$donhang_thang = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM orders WHERE MONTH(created_at) = $selected_month AND YEAR(created_at) = $selected_year"))['count'];

// Lấy doanh thu 12 tháng gần nhất để vẽ biểu đồ
$revenue_by_month = [];
for ($i = 11; $i >= 0; $i--) {
    $month = date('n', strtotime("-$i months"));
    $year = date('Y', strtotime("-$i months"));
    $revenue = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_amount) as total FROM orders WHERE (payment_status = 'paid' OR order_status = 'completed') AND MONTH(created_at) = $month AND YEAR(created_at) = $year"))['total'] ?: 0;
    $revenue_by_month[] = [
        'month' => $month,
        'year' => $year,
        'label' => "T$month/$year",
        'revenue' => $revenue
    ];
}

// Lấy danh sách 10 đơn hàng gần nhất
$sql_donhang_ganday = "SELECT o.*, u.fullname as user_name 
                       FROM orders o 
                       LEFT JOIN users u ON o.user_id = u.id 
                       ORDER BY o.created_at DESC LIMIT 10";
$donhang_ganday = mysqli_query($conn, $sql_donhang_ganday);

// Giữ biến cũ để tương thích
$total_products = $tong_sanpham;
$total_orders = $tong_donhang;
$total_users = $tong_khachhang;
$total_revenue = $tong_doanhthu;
$recent_orders_query = $sql_donhang_ganday;
$recent_orders = $donhang_ganday;

include 'includes/header.php';
?>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="fas fa-box"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo $total_products; ?></h3>
            <p>Tổng Sản Phẩm</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon green">
            <i class="fas fa-shopping-cart"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo $total_orders; ?></h3>
            <p>Tổng Đơn Hàng</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon orange">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo $total_users; ?></h3>
            <p>Khách Hàng</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon purple">
            <i class="fas fa-money-bill-wave"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo number_format($total_revenue); ?>đ</h3>
            <p>Tổng Doanh Thu</p>
        </div>
    </div>
</div>

<!-- Monthly Revenue Management -->
<div class="admin-card" style="margin-top: 20px;">
    <div class="admin-card-header">
        <h3><i class="fas fa-chart-line"></i> Quản Lý Doanh Thu Theo Tháng</h3>
    </div>
    <div class="admin-card-body">
        <!-- Filter -->
        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
            <form method="GET" style="display: flex; gap: 15px; align-items: end; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 200px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #333;">
                        <i class="fas fa-calendar-alt"></i> Chọn Tháng
                    </label>
                    <select name="month" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                        <?php for($m = 1; $m <= 12; $m++): ?>
                            <option value="<?php echo $m; ?>" <?php echo $selected_month == $m ? 'selected' : ''; ?>>
                                Tháng <?php echo $m; ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div style="flex: 1; min-width: 200px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #333;">
                        <i class="fas fa-calendar"></i> Chọn Năm
                    </label>
                    <select name="year" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                        <?php for($y = date('Y'); $y >= 2020; $y--): ?>
                            <option value="<?php echo $y; ?>" <?php echo $selected_year == $y ? 'selected' : ''; ?>>
                                Năm <?php echo $y; ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary" style="padding: 10px 30px;">
                        <i class="fas fa-filter"></i> Lọc
                    </button>
                </div>
            </form>
        </div>

        <!-- Monthly Stats -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 25px; border-radius: 12px; color: white; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div style="background: rgba(255,255,255,0.2); padding: 15px; border-radius: 12px; font-size: 28px;">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div>
                        <div style="font-size: 14px; opacity: 0.9; margin-bottom: 5px;">Tháng <?php echo $selected_month; ?>/<?php echo $selected_year; ?></div>
                        <div style="font-size: 24px; font-weight: bold;"><?php echo $donhang_thang; ?> đơn</div>
                    </div>
                </div>
            </div>
            <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 25px; border-radius: 12px; color: white; box-shadow: 0 4px 15px rgba(245, 87, 108, 0.4);">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div style="background: rgba(255,255,255,0.2); padding: 15px; border-radius: 12px; font-size: 28px;">
                        <i class="fas fa-coins"></i>
                    </div>
                    <div>
                        <div style="font-size: 14px; opacity: 0.9; margin-bottom: 5px;">Doanh Thu Tháng</div>
                        <div style="font-size: 24px; font-weight: bold;"><?php echo number_format($doanhthu_thang); ?>đ</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue Chart -->
        <div style="background: white; padding: 20px; border-radius: 8px; border: 1px solid #e9ecef;">
            <h4 style="margin: 0 0 20px 0; color: #333;">
                <i class="fas fa-chart-bar"></i> Biểu Đồ Doanh Thu 12 Tháng Gần Nhất
            </h4>
            <canvas id="revenueChart" style="max-height: 400px;"></canvas>
        </div>
    </div>
</div>

<!-- Recent Orders -->
<div class="admin-card" style="margin-top: 20px;">
    <div class="admin-card-header">
        <h3><i class="fas fa-shopping-cart"></i> Đơn Hàng Gần Đây</h3>
        <a href="orders.php" class="btn btn-primary">Xem Tất Cả</a>
    </div>
    <div class="admin-card-body">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Mã Đơn</th>
                    <th>Khách Hàng</th>
                    <th>Tổng Tiền</th>
                    <th>Trạng Thái</th>
                    <th>Ngày Đặt</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php while($order = mysqli_fetch_assoc($recent_orders)): ?>
                <tr>
                    <td><strong><?php echo $order['order_code']; ?></strong></td>
                    <td><?php echo $order['fullname']; ?></td>
                    <td><strong style="color: #d4af37;"><?php echo number_format($order['total_amount']); ?>đ</strong></td>
                    <td>
                        <?php
                        $status_class = 'info';
                        $status_text = '';
                        switch($order['order_status']) {
                            case 'pending':
                                $status_class = 'warning';
                                $status_text = 'Chờ xử lý';
                                break;
                            case 'confirmed':
                                $status_class = 'info';
                                $status_text = 'Đã xác nhận';
                                break;
                            case 'shipping':
                                $status_class = 'primary';
                                $status_text = 'Đang giao';
                                break;
                            case 'completed':
                                $status_class = 'success';
                                $status_text = 'Hoàn thành';
                                break;
                            case 'cancelled':
                                $status_class = 'danger';
                                $status_text = 'Đã hủy';
                                break;
                        }
                        ?>
                        <span class="badge badge-<?php echo $status_class; ?>"><?php echo $status_text; ?></span>
                    </td>
                    <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                    <td>
                        <div class="action-buttons">
                            <a href="order-detail.php?id=<?php echo $order['id']; ?>" class="btn-icon btn-view" title="Xem">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Dữ liệu doanh thu 12 tháng
const revenueData = <?php echo json_encode($revenue_by_month); ?>;

// Tạo biểu đồ
const ctx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: revenueData.map(item => item.label),
        datasets: [{
            label: 'Doanh Thu (đ)',
            data: revenueData.map(item => item.revenue),
            backgroundColor: 'rgba(102, 126, 234, 0.8)',
            borderColor: 'rgba(102, 126, 234, 1)',
            borderWidth: 2,
            borderRadius: 8,
            hoverBackgroundColor: 'rgba(118, 75, 162, 0.9)'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: true,
                position: 'top',
                labels: {
                    font: {
                        size: 14,
                        family: "'Segoe UI', sans-serif"
                    },
                    padding: 15
                }
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 12,
                titleFont: {
                    size: 14
                },
                bodyFont: {
                    size: 13
                },
                callbacks: {
                    label: function(context) {
                        return 'Doanh thu: ' + context.parsed.y.toLocaleString('vi-VN') + 'đ';
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return value.toLocaleString('vi-VN') + 'đ';
                    },
                    font: {
                        size: 11
                    }
                },
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)'
                }
            },
            x: {
                ticks: {
                    font: {
                        size: 12
                    }
                },
                grid: {
                    display: false
                }
            }
        }
    }
});
</script>

<?php include 'includes/footer.php'; ?>
