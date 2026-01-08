<?php
// File quản lý sản phẩm (Xem, Thêm, Sửa, Xóa)
require_once 'check_admin.php'; // Kiểm tra quyền admin

$page_title = 'Quản Lý Sản Phẩm'; // Tiêu đề trang

// Xử lý xóa sản phẩm
if (isset($_GET['delete'])) {
    $id_sanpham = (int)$_GET['delete']; // Lấy ID sản phẩm cần xóa
    mysqli_query($conn, "DELETE FROM products WHERE id = $id_sanpham"); // Xóa khỏi database
    header('Location: products.php?msg=deleted'); // Quay lại trang danh sách
    exit;
}

// Phân trang - Hiển thị 20 sản phẩm mỗi trang
$gioi_han = 20; // Số sản phẩm mỗi trang
$trang_hientai = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Trang hiện tại
$vi_tri_batdau = ($trang_hientai - 1) * $gioi_han; // Vị trí bắt đầu lấy dữ liệu

// Tìm kiếm sản phẩm theo tên
$tu_khoa = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$dieu_kien = $tu_khoa ? "WHERE name LIKE '%$tu_khoa%'" : ''; // Điều kiện WHERE

// Đếm tổng số sản phẩm (để tính số trang)
$tong_sanpham = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM products $dieu_kien"))['count'];
$tong_sotrang = ceil($tong_sanpham / $gioi_han); // Tổng số trang

// Giữ biến cũ để tương thích
$limit = $gioi_han;
$page = $trang_hientai;
$offset = $vi_tri_batdau;
$search = $tu_khoa;
$where = $dieu_kien;
$total = $tong_sanpham;
$total_pages = $tong_sotrang;

// Get products
$products_query = "SELECT p.*, c.name as category_name 
                  FROM products p 
                  LEFT JOIN categories c ON p.category_id = c.id 
                  $where
                  ORDER BY p.created_at DESC 
                  LIMIT $limit OFFSET $offset";
$products = mysqli_query($conn, $products_query);

include 'includes/header.php';
?>

<?php if(isset($_GET['msg'])): ?>
<div class="alert alert-success">
    <?php
    switch($_GET['msg']) {
        case 'added': echo 'Đã thêm sản phẩm thành công!'; break;
        case 'updated': echo 'Đã cập nhật sản phẩm!'; break;
        case 'deleted': echo 'Đã xóa sản phẩm!'; break;
    }
    ?>
</div>
<?php endif; ?>

<div class="admin-card">
    <div class="admin-card-header">
        <h3><i class="fas fa-box"></i> Danh Sách Sản Phẩm</h3>
        <a href="product-add.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Thêm Sản Phẩm
        </a>
    </div>
    <div class="admin-card-body">
        <!-- Search -->
        <form method="GET" style="margin-bottom: 20px;">
            <div style="display: flex; gap: 10px; max-width: 500px;">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm sản phẩm..." 
                       value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Tìm
                </button>
            </div>
        </form>

        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Hình Ảnh</th>
                    <th>Tên Sản Phẩm</th>
                    <th>Danh Mục</th>
                    <th>Giá</th>
                    <th>Giá KM</th>
                    <th>Số Lượng</th>
                    <th>Trạng Thái</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php if(mysqli_num_rows($products) > 0): ?>
                <?php while($product = mysqli_fetch_assoc($products)): ?>
                <tr>
                    <td><?php echo $product['id']; ?></td>
                    <td>
                        <img src="<?php echo BASE_URL; ?>assets/images/products/<?php echo $product['image']; ?>" 
                             alt="<?php echo $product['name']; ?>">
                    </td>
                    <td>
                        <strong><?php echo $product['name']; ?></strong>
                        <?php if($product['is_new']): ?>
                        <span class="badge badge-success">Mới</span>
                        <?php endif; ?>
                        <?php if($product['is_featured']): ?>
                        <span class="badge badge-warning">Hot</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo $product['category_name']; ?></td>
                    <td><strong><?php echo number_format($product['price']); ?>đ</strong></td>
                    <td>
                        <?php if($product['sale_price']): ?>
                        <strong style="color: #e74c3c;"><?php echo number_format($product['sale_price']); ?>đ</strong>
                        <?php else: ?>
                        -
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="badge <?php echo $product['quantity'] > 10 ? 'badge-success' : 'badge-warning'; ?>">
                            <?php echo $product['quantity']; ?>
                        </span>
                    </td>
                    <td>
                        <?php if($product['status']): ?>
                        <span class="badge badge-success">Hiển thị</span>
                        <?php else: ?>
                        <span class="badge badge-danger">Ẩn</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="product-edit.php?id=<?php echo $product['id']; ?>" class="btn-icon btn-edit" title="Sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="products.php?delete=<?php echo $product['id']; ?>" 
                               onclick="return confirm('Bạn có chắc muốn xóa?')" 
                               class="btn-icon btn-delete" title="Xóa">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
                <?php else: ?>
                <tr>
                    <td colspan="9" style="text-align: center; padding: 40px; color: #999;">
                        <i class="fas fa-box-open" style="font-size: 48px; margin-bottom: 15px; display: block;"></i>
                        Không có sản phẩm nào
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <?php if($total_pages > 1): ?>
        <div class="admin-pagination">
            <?php for($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?php echo $i; ?>&search=<?php echo $search; ?>" 
               class="<?php echo $page == $i ? 'active' : ''; ?>">
                <?php echo $i; ?>
            </a>
            <?php endfor; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
