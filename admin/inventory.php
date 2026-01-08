<?php
require_once 'check_admin.php';
$page_title = 'Quản Lý Tồn Kho';

// Lấy danh sách sản phẩm với số lượng tồn kho
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

$where = "WHERE 1=1";
if ($search) {
    $where .= " AND (p.name LIKE '%$search%' OR p.id LIKE '%$search%')";
}

if ($filter == 'low') {
    $where .= " AND p.quantity < 10";
} elseif ($filter == 'out') {
    $where .= " AND p.quantity = 0";
} elseif ($filter == 'high') {
    $where .= " AND p.quantity >= 10";
}

$sql = "SELECT p.*, c.name as category_name 
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id 
        $where
        ORDER BY p.quantity ASC, p.name ASC";
$products = mysqli_query($conn, $sql);

// Thống kê
$stats_sql = "SELECT 
              COUNT(*) as total_products,
              SUM(quantity) as total_quantity,
              SUM(CASE WHEN quantity = 0 THEN 1 ELSE 0 END) as out_of_stock,
              SUM(CASE WHEN quantity < 10 THEN 1 ELSE 0 END) as low_stock
              FROM products";
$stats_result = mysqli_query($conn, $stats_sql);
$stats = mysqli_fetch_assoc($stats_result);

// Xử lý cập nhật số lượng
if (isset($_POST['update_quantity'])) {
    $product_id = (int)$_POST['product_id'];
    $new_quantity = (int)$_POST['quantity'];
    
    $update_sql = "UPDATE products SET quantity = $new_quantity WHERE id = $product_id";
    if (mysqli_query($conn, $update_sql)) {
        $success = "Cập nhật số lượng thành công!";
    } else {
        $error = "Lỗi: " . mysqli_error($conn);
    }
    header("Location: inventory.php");
    exit;
}

include 'includes/header.php';
?>

<style>
.stat-card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    text-align: center;
}
.stat-card i {
    font-size: 32px;
    margin-bottom: 10px;
}
.stat-card h3 {
    font-size: 28px;
    margin: 10px 0;
    font-weight: bold;
}
.stat-card p {
    color: #6c757d;
    font-size: 14px;
    margin: 0;
}
.status-badge {
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
}
.status-high { background: #d4edda; color: #155724; }
.status-low { background: #fff3cd; color: #856404; }
.status-out { background: #f8d7da; color: #721c24; }
.filter-btn {
    padding: 8px 16px;
    border: 2px solid #dee2e6;
    background: white;
    border-radius: 20px;
    text-decoration: none;
    color: #495057;
    font-size: 14px;
    transition: all 0.3s;
    display: inline-block;
    margin-right: 8px;
}
.filter-btn:hover, .filter-btn.active {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border-color: #667eea;
}
.quantity-input {
    width: 80px;
    padding: 6px;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    text-align: center;
}
</style>

<div class="admin-card">
    <div class="admin-card-header">
        <h3><i class="fas fa-warehouse"></i> Quản Lý Tồn Kho</h3>
    </div>
    <div class="admin-card-body">
        <!-- Thống kê -->
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px;">
            <div class="stat-card" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white;">
                <i class="fas fa-boxes"></i>
                <h3><?php echo number_format($stats['total_products']); ?></h3>
                <p style="color: rgba(255,255,255,0.9);">Tổng sản phẩm</p>
            </div>
            <div class="stat-card" style="background: linear-gradient(135deg, #f093fb, #f5576c); color: white;">
                <i class="fas fa-cube"></i>
                <h3><?php echo number_format($stats['total_quantity']); ?></h3>
                <p style="color: rgba(255,255,255,0.9);">Tổng số lượng</p>
            </div>
            <div class="stat-card" style="background: linear-gradient(135deg, #ffd89b, #19547b); color: white;">
                <i class="fas fa-exclamation-triangle"></i>
                <h3><?php echo number_format($stats['low_stock']); ?></h3>
                <p style="color: rgba(255,255,255,0.9);">Sắp hết hàng</p>
            </div>
            <div class="stat-card" style="background: linear-gradient(135deg, #f2994a, #f2c94c); color: white;">
                <i class="fas fa-ban"></i>
                <h3><?php echo number_format($stats['out_of_stock']); ?></h3>
                <p style="color: rgba(255,255,255,0.9);">Hết hàng</p>
            </div>
        </div>

        <!-- Filter & Search -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <div>
                <a href="inventory.php?filter=all" class="filter-btn <?php echo $filter == 'all' ? 'active' : ''; ?>">
                    <i class="fas fa-list"></i> Tất cả
                </a>
                <a href="inventory.php?filter=high" class="filter-btn <?php echo $filter == 'high' ? 'active' : ''; ?>">
                    <i class="fas fa-check-circle"></i> Còn hàng
                </a>
                <a href="inventory.php?filter=low" class="filter-btn <?php echo $filter == 'low' ? 'active' : ''; ?>">
                    <i class="fas fa-exclamation-circle"></i> Sắp hết
                </a>
                <a href="inventory.php?filter=out" class="filter-btn <?php echo $filter == 'out' ? 'active' : ''; ?>">
                    <i class="fas fa-times-circle"></i> Hết hàng
                </a>
            </div>
            <form method="GET" style="display: flex; gap: 10px;">
                <input type="hidden" name="filter" value="<?php echo $filter; ?>">
                <input type="text" name="search" placeholder="Tìm theo tên hoặc ID sản phẩm..." 
                       value="<?php echo htmlspecialchars($search); ?>"
                       style="padding: 8px 16px; border: 1px solid #dee2e6; border-radius: 20px; width: 300px;">
                <button type="submit" class="btn btn-primary" style="border-radius: 20px;">
                    <i class="fas fa-search"></i> Tìm
                </button>
            </form>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Hình ảnh</th>
                        <th>ID</th>
                        <th>Tên sản phẩm</th>
                        <th>Danh mục</th>
                        <th>Giá bán</th>
                        <th>Tồn kho</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($products) > 0): ?>
                        <?php while($product = mysqli_fetch_assoc($products)): ?>
                        <tr>
                            <td>
                                <img src="../assets/images/products/<?php echo $product['image']; ?>" 
                                     alt="<?php echo htmlspecialchars($product['name']); ?>"
                                     style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                            </td>
                            <td><strong>#<?php echo $product['id']; ?></strong></td>
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td><?php echo $product['category_name']; ?></td>
                            <td><strong><?php echo number_format($product['price']); ?>đ</strong></td>
                            <td>
                                <strong style="font-size: 18px; color: <?php 
                                    echo $product['quantity'] == 0 ? '#dc3545' : 
                                        ($product['quantity'] < 10 ? '#ffc107' : '#28a745'); 
                                ?>;">
                                    <?php echo $product['quantity']; ?>
                                </strong>
                            </td>
                            <td>
                                <?php if ($product['quantity'] == 0): ?>
                                    <span class="status-badge status-out">
                                        <i class="fas fa-times-circle"></i> Hết hàng
                                    </span>
                                <?php elseif ($product['quantity'] < 10): ?>
                                    <span class="status-badge status-low">
                                        <i class="fas fa-exclamation-triangle"></i> Sắp hết
                                    </span>
                                <?php else: ?>
                                    <span class="status-badge status-high">
                                        <i class="fas fa-check-circle"></i> Còn hàng
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button onclick="openUpdateModal(<?php echo $product['id']; ?>, '<?php echo htmlspecialchars($product['name']); ?>', <?php echo $product['quantity']; ?>)" 
                                        class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i> Cập nhật
                                </button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 40px; color: #999;">
                                <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 10px;"></i>
                                <p>Không có sản phẩm nào</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal cập nhật số lượng -->
<div id="updateModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; padding: 30px; border-radius: 15px; width: 400px; box-shadow: 0 10px 40px rgba(0,0,0,0.3);">
        <h3 style="margin-top: 0;"><i class="fas fa-edit"></i> Cập nhật số lượng tồn kho</h3>
        <form method="POST">
            <input type="hidden" name="product_id" id="modalProductId">
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600;">Sản phẩm:</label>
                <p id="modalProductName" style="color: #667eea; font-weight: bold; font-size: 16px;"></p>
            </div>
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600;">Số lượng hiện tại:</label>
                <p id="modalCurrentQuantity" style="font-size: 24px; font-weight: bold; color: #28a745;"></p>
            </div>
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600;">Số lượng mới:</label>
                <input type="number" name="quantity" id="modalNewQuantity" min="0" required
                       style="width: 100%; padding: 12px; border: 2px solid #dee2e6; border-radius: 8px; font-size: 18px; font-weight: bold; text-align: center;">
            </div>
            <div style="display: flex; gap: 10px;">
                <button type="submit" name="update_quantity" class="btn btn-primary" style="flex: 1;">
                    <i class="fas fa-save"></i> Lưu
                </button>
                <button type="button" onclick="closeUpdateModal()" class="btn btn-secondary" style="flex: 1;">
                    <i class="fas fa-times"></i> Hủy
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openUpdateModal(id, name, quantity) {
    document.getElementById('modalProductId').value = id;
    document.getElementById('modalProductName').textContent = name;
    document.getElementById('modalCurrentQuantity').textContent = quantity + ' sản phẩm';
    document.getElementById('modalNewQuantity').value = quantity;
    document.getElementById('updateModal').style.display = 'flex';
}

function closeUpdateModal() {
    document.getElementById('updateModal').style.display = 'none';
}

// Close modal when clicking outside
document.getElementById('updateModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeUpdateModal();
    }
});
</script>

<?php include 'includes/footer.php'; ?>
