<?php
// File quản lý đơn hàng (Xem, Cập nhật trạng thái)
require_once 'check_admin.php'; // Kiểm tra quyền admin

$page_title = 'Quản Lý Đơn Hàng'; // Tiêu đề trang

// Xử lý cập nhật trạng thái đơn hàng
if (isset($_POST['update_status'])) {
    $id_donhang = (int)$_POST['order_id']; // Lấy ID đơn hàng
    $trangthai_moi = mysqli_real_escape_string($conn, $_POST['order_status']); // Lấy trạng thái mới
    
    // Lấy thông tin trước khi update để debug
    $before = mysqli_fetch_assoc(mysqli_query($conn, "SELECT order_status FROM orders WHERE id = $id_donhang"));
    
    // Cập nhật trạng thái trong database
    // Nếu đơn hàng được đánh dấu là "completed", tự động cập nhật payment_status thành 'paid'
    if ($trangthai_moi === 'completed') {
        $update_sql = "UPDATE orders SET order_status = '$trangthai_moi', payment_status = 'paid' WHERE id = $id_donhang";
    } else {
        $update_sql = "UPDATE orders SET order_status = '$trangthai_moi' WHERE id = $id_donhang";
    }
    $update_result = mysqli_query($conn, $update_sql);
    
    // Kiểm tra kết quả và lấy giá trị sau khi update
    if ($update_result) {
        $after = mysqli_fetch_assoc(mysqli_query($conn, "SELECT order_status FROM orders WHERE id = $id_donhang"));
        $before_status = $before['order_status'] ? $before['order_status'] : 'null';
        $after_status = $after['order_status'] ? $after['order_status'] : 'null';
        $_SESSION['update_debug'] = "ID: $id_donhang | Trước: $before_status → Sau: $after_status";
    } else {
        $_SESSION['update_error'] = "Lỗi SQL: " . mysqli_error($conn);
    }
    
    // Giữ lại filter và search khi redirect
    $redirect_params = ['msg' => 'updated'];
    if (isset($_GET['status'])) $redirect_params['status'] = $_GET['status'];
    if (isset($_GET['search'])) $redirect_params['search'] = $_GET['search'];
    if (isset($_GET['page'])) $redirect_params['page'] = $_GET['page'];
    
    $redirect_url = 'orders.php?' . http_build_query($redirect_params);
    header("Location: $redirect_url");
    exit;
}

// Tìm kiếm đơn hàng
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$search_condition = '';
if ($search) {
    $search_condition = "(order_code LIKE '%$search%' OR fullname LIKE '%$search%' OR phone LIKE '%$search%' OR email LIKE '%$search%')";
}

// Lọc đơn hàng theo trạng thái (pending, confirmed, shipping, completed, cancelled)
$loc_trangthai = isset($_GET['status']) ? $_GET['status'] : '';
$status_condition = $loc_trangthai ? "order_status = '$loc_trangthai'" : '';

// Kết hợp điều kiện
$conditions = array_filter([$search_condition, $status_condition]);
$dieu_kien = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';

// Phân trang - Hiển thị 20 đơn hàng mỗi trang
$gioi_han = 20;
$trang_hientai = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$vi_tri_batdau = ($trang_hientai - 1) * $gioi_han;

// Đếm số lượng đơn hàng theo từng trạng thái
$count_sql = "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN order_status = 'pending' OR order_status IS NULL THEN 1 ELSE 0 END) as pending,
    SUM(CASE WHEN order_status = 'confirmed' THEN 1 ELSE 0 END) as confirmed,
    SUM(CASE WHEN order_status = 'shipping' THEN 1 ELSE 0 END) as shipping,
    SUM(CASE WHEN order_status = 'completed' THEN 1 ELSE 0 END) as completed,
    SUM(CASE WHEN order_status = 'cancelled' THEN 1 ELSE 0 END) as cancelled
    FROM orders";
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

// Giữ biến cũ để tương thích
$status_filter = $loc_trangthai;
$where = $dieu_kien;
$limit = $gioi_han;
$page = $trang_hientai;
$offset = $vi_tri_batdau;
$total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM orders $where"))['count'];
$total_pages = ceil($total / $limit);

// Get orders
$orders_query = "SELECT * FROM orders $where ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
$orders = mysqli_query($conn, $orders_query);

include 'includes/header.php';
?>

<?php if(isset($_GET['msg']) && $_GET['msg'] == 'updated'): ?>
<div class="alert alert-success">
    Đã cập nhật trạng thái đơn hàng!
    <?php if(isset($_SESSION['update_debug'])): ?>
        <br><small><?php echo $_SESSION['update_debug']; unset($_SESSION['update_debug']); ?></small>
    <?php endif; ?>
</div>
<?php endif; ?>

<?php if(isset($_SESSION['update_error'])): ?>
<div class="alert alert-danger">
    <?php echo $_SESSION['update_error']; unset($_SESSION['update_error']); ?>
</div>
<?php endif; ?>

<div class="admin-card">
    <div class="admin-card-header">
        <h3><i class="fas fa-shopping-cart"></i> Danh Sách Đơn Hàng</h3>
    </div>
    <div class="admin-card-body">
        <!-- Search Bar -->
        <form method="GET" style="margin-bottom: 20px;">
            <div style="display: flex; gap: 10px; align-items: center;">
                <div style="flex: 1; position: relative;">
                    <i class="fas fa-search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #999;"></i>
                    <input type="text" name="search" placeholder="Tìm theo mã đơn, tên, số điện thoại, email..." 
                           value="<?php echo htmlspecialchars($search); ?>"
                           style="width: 100%; padding: 10px 10px 10px 40px; border: 2px solid #ddd; border-radius: 8px; font-size: 14px;">
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Tìm kiếm
                </button>
                <?php if($search): ?>
                <a href="orders.php<?php echo $status_filter ? '?status='.$status_filter : ''; ?>" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Xóa
                </a>
                <?php endif; ?>
            </div>
            <?php if($status_filter): ?>
            <input type="hidden" name="status" value="<?php echo $status_filter; ?>">
            <?php endif; ?>
        </form>

        <!-- Filter Tabs với số lượng -->
        <div style="margin-bottom: 20px; display: flex; gap: 10px; flex-wrap: wrap;">
            <a href="orders.php" class="btn <?php echo !$status_filter ? 'btn-primary' : 'btn-secondary'; ?>" style="position: relative;">
                <i class="fas fa-list"></i> Tất cả
                <span style="background: rgba(255,255,255,0.3); padding: 2px 8px; border-radius: 12px; margin-left: 5px; font-size: 12px;">
                    <?php echo $count_result['total']; ?>
                </span>
            </a>
            <a href="orders.php?status=pending" class="btn <?php echo $status_filter == 'pending' ? 'btn-primary' : 'btn-secondary'; ?>" style="position: relative;">
                <i class="fas fa-clock"></i> Chờ xác nhận
                <span style="background: rgba(255,255,255,0.3); padding: 2px 8px; border-radius: 12px; margin-left: 5px; font-size: 12px;">
                    <?php echo $count_result['pending']; ?>
                </span>
            </a>
            <a href="orders.php?status=confirmed" class="btn <?php echo $status_filter == 'confirmed' ? 'btn-primary' : 'btn-secondary'; ?>" style="position: relative;">
                <i class="fas fa-check-circle"></i> Đã xác nhận
                <span style="background: rgba(255,255,255,0.3); padding: 2px 8px; border-radius: 12px; margin-left: 5px; font-size: 12px;">
                    <?php echo $count_result['confirmed']; ?>
                </span>
            </a>
            <a href="orders.php?status=shipping" class="btn <?php echo $status_filter == 'shipping' ? 'btn-primary' : 'btn-secondary'; ?>" style="position: relative;">
                <i class="fas fa-shipping-fast"></i> Đang giao
                <span style="background: rgba(255,255,255,0.3); padding: 2px 8px; border-radius: 12px; margin-left: 5px; font-size: 12px;">
                    <?php echo $count_result['shipping']; ?>
                </span>
            </a>
            <a href="orders.php?status=completed" class="btn <?php echo $status_filter == 'completed' ? 'btn-primary' : 'btn-secondary'; ?>" style="position: relative;">
                <i class="fas fa-check-double"></i> Đã giao
                <span style="background: rgba(255,255,255,0.3); padding: 2px 8px; border-radius: 12px; margin-left: 5px; font-size: 12px;">
                    <?php echo $count_result['completed']; ?>
                </span>
            </a>
            <a href="orders.php?status=cancelled" class="btn <?php echo $status_filter == 'cancelled' ? 'btn-primary' : 'btn-secondary'; ?>" style="position: relative;">
                <i class="fas fa-times-circle"></i> Đã hủy
                <span style="background: rgba(255,255,255,0.3); padding: 2px 8px; border-radius: 12px; margin-left: 5px; font-size: 12px;">
                    <?php echo $count_result['cancelled']; ?>
                </span>
            </a>
        </div>

        <table class="admin-table">
            <thead>
                <tr>
                    <th>Mã Đơn</th>
                    <th>Khách Hàng</th>
                    <th>Điện Thoại</th>
                    <th>Tổng Tiền</th>
                    <th>Phương Thức</th>
                    <th>Trạng Thái TT</th>
                    <th>Trạng Thái</th>
                    <th>Ngày Đặt</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php if(mysqli_num_rows($orders) > 0): ?>
                <?php while($order = mysqli_fetch_assoc($orders)): ?>
                <tr>
                    <td><strong><?php echo $order['order_code']; ?></strong></td>
                    <td><?php echo $order['fullname']; ?></td>
                    <td><?php echo $order['phone']; ?></td>
                    <td><strong style="color: #d4af37;"><?php echo number_format($order['total_amount']); ?>đ</strong></td>
                    <td>
                        <?php if($order['payment_method'] == 'cod'): ?>
                        <span class="badge badge-warning">COD</span>
                        <?php else: ?>
                        <span class="badge badge-info">Chuyển khoản</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php 
                        $payment_status = $order['payment_status'] ?? 'pending';
                        if ($payment_status === 'paid'): ?>
                            <span class="badge" style="background-color: #28a745; color: white;">Đã thanh toán</span>
                        <?php elseif ($payment_status === 'awaiting_verification'): ?>
                            <div style="display: flex; gap: 5px; align-items: center;">
                                <span class="badge" style="background-color: #ffc107; color: #000;">Chờ xác thực</span>
                                <?php if($order['transfer_proof']): ?>
                                <a href="javascript:void(0)" onclick="viewProof('<?php echo htmlspecialchars($order['transfer_proof']); ?>')" class="btn-icon" style="font-size: 12px;">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <?php endif; ?>
                                <button type="button" onclick="confirmPayment(<?php echo $order['id']; ?>, '<?php echo htmlspecialchars($order['order_code']); ?>')" class="btn-icon" style="background-color: #28a745; color: white; padding: 5px 10px; border: none; border-radius: 4px; cursor: pointer; font-size: 12px;">
                                    <i class="fas fa-check"></i> Xác nhận
                                </button>
                            </div>
                        <?php elseif ($payment_status === 'pending'): ?>
                            <span class="badge" style="background-color: #6c757d; color: white;">Chờ xử lý</span>
                        <?php else: ?>
                            <span class="badge" style="background-color: #dc3545; color: white;"><?php echo ucfirst($payment_status); ?></span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <form method="POST" style="display: inline-block;" id="form_<?php echo $order['id']; ?>">
                            <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                            <input type="hidden" name="update_status" value="1">
                            <select name="order_status" class="form-control" style="padding: 5px; font-size: 12px;" 
                                    onchange="if(confirm('Bạn có chắc muốn đổi trạng thái đơn hàng này?')) { document.getElementById('form_<?php echo $order['id']; ?>').submit(); } else { this.value='<?php echo $order['order_status']; ?>'; }">
                                <option value="pending" <?php echo (!isset($order['order_status']) || $order['order_status'] == 'pending') ? 'selected' : ''; ?>>Chờ xử lý</option>
                                <option value="confirmed" <?php echo (isset($order['order_status']) && $order['order_status'] == 'confirmed') ? 'selected' : ''; ?>>Đã xác nhận</option>
                                <option value="shipping" <?php echo (isset($order['order_status']) && $order['order_status'] == 'shipping') ? 'selected' : ''; ?>>Đang giao</option>
                                <option value="completed" <?php echo (isset($order['order_status']) && $order['order_status'] == 'completed') ? 'selected' : ''; ?>>Hoàn thành</option>
                                <option value="cancelled" <?php echo (isset($order['order_status']) && $order['order_status'] == 'cancelled') ? 'selected' : ''; ?>>Đã hủy</option>
                            </select>
                        </form>
                    </td>
                    <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                    <td>
                        <a href="order-detail.php?id=<?php echo $order['id']; ?>" class="btn-icon btn-view" title="Chi tiết">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
                <?php else: ?>
                <tr>
                    <td colspan="9" style="text-align: center; padding: 40px; color: #999;">
                        <i class="fas fa-shopping-cart" style="font-size: 48px; margin-bottom: 15px; display: block;"></i>
                        Không có đơn hàng nào
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <?php if($total_pages > 1): ?>
        <div class="admin-pagination">
            <?php for($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?php echo $i; ?>&status=<?php echo $status_filter; ?>" 
               class="<?php echo $page == $i ? 'active' : ''; ?>">
                <?php echo $i; ?>
            </a>
            <?php endfor; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal để xem chứng từ -->
<div id="proofModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center;">
    <div style="background: white; border-radius: 8px; padding: 20px; max-width: 600px; max-height: 80vh; overflow-y: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <h4>Chứng từ chuyển khoản</h4>
            <button onclick="closeProofModal()" style="background: none; border: none; font-size: 20px; cursor: pointer;">×</button>
        </div>
        <div id="proofContent" style="text-align: center;">
            <p>Đang tải...</p>
        </div>
    </div>
</div>

<script>
function viewProof(proofPath) {
    const modal = document.getElementById('proofModal');
    const content = document.getElementById('proofContent');
    const baseUrl = '<?php echo BASE_URL; ?>';
    const fullPath = baseUrl + proofPath;

    if (proofPath.toLowerCase().endsWith('.pdf')) {
        content.innerHTML = '<iframe src="' + fullPath + '" style="width: 100%; height: 500px; border: none;"></iframe>';
    } else {
        content.innerHTML = '<img src="' + fullPath + '" style="max-width: 100%; max-height: 500px; border-radius: 8px;">';
    }
    
    modal.style.display = 'flex';
}

function closeProofModal() {
    document.getElementById('proofModal').style.display = 'none';
}

function confirmPayment(orderId, orderCode) {
    if (!confirm('Bạn có chắc muốn xác nhận thanh toán cho đơn ' + orderCode + '?')) return;

    const fd = new FormData();
    fd.append('order_id', orderId);

    fetch('confirm-payment.php', {
        method: 'POST',
        body: fd
    }).then(r => r.json()).then(data => {
        if (data.success) {
            alert('Thanh toán đã được xác nhận!');
            location.reload();
        } else {
            alert('Lỗi: ' + data.message);
        }
    }).catch(err => {
        console.error(err);
        alert('Lỗi kết nối');
    });
}
</script>

<?php include 'includes/footer.php'; ?>
