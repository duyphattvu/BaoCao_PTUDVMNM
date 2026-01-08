<?php
require_once 'check_admin.php';
$page_title = 'Quản Lý Danh Mục';

$message = '';
$error = '';

// Xử lý xóa danh mục
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $sql = "DELETE FROM categories WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        $message = 'Xóa danh mục thành công!';
    } else {
        $error = 'Lỗi: ' . mysqli_error($conn);
    }
}

// Xử lý thêm/sửa danh mục
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $slug = mysqli_real_escape_string($conn, strtolower(str_replace(' ', '-', trim($_POST['slug']))));
    $description = mysqli_real_escape_string($conn, trim($_POST['description']));
    $image = $_POST['image'] ?? 'category-default.jpg';
    $status = isset($_POST['status']) ? 1 : 0;
    
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // Update
        $id = (int)$_POST['id'];
        $sql = "UPDATE categories SET name = '$name', slug = '$slug', description = '$description', image = '$image', status = $status WHERE id = $id";
        $msg = 'Cập nhật danh mục thành công!';
    } else {
        // Insert
        $sql = "INSERT INTO categories (name, slug, description, image, status) VALUES ('$name', '$slug', '$description', '$image', $status)";
        $msg = 'Thêm danh mục thành công!';
    }
    
    if (mysqli_query($conn, $sql)) {
        $message = $msg;
    } else {
        $error = 'Lỗi: ' . mysqli_error($conn);
    }
}

// Lấy danh sách danh mục
$sql = "SELECT c.*, COUNT(p.id) as product_count FROM categories c LEFT JOIN products p ON c.id = p.category_id GROUP BY c.id ORDER BY c.id DESC";
$categories = mysqli_query($conn, $sql);

// Lấy thông tin danh mục để sửa
$edit_category = null;
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $edit_result = mysqli_query($conn, "SELECT * FROM categories WHERE id = $edit_id");
    $edit_category = mysqli_fetch_assoc($edit_result);
}

include 'includes/header.php';
?>

<?php if($message): ?>
<div class="alert alert-success">
    <i class="fas fa-check-circle"></i> <?php echo $message; ?>
</div>
<?php endif; ?>

<?php if($error): ?>
<div class="alert alert-danger">
    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
</div>
<?php endif; ?>

<div class="row">
    <!-- Form thêm/sửa danh mục -->
    <div class="col-md-4">
        <div class="admin-card">
            <div class="admin-card-header">
                <h3><i class="fas fa-plus"></i> <?php echo $edit_category ? 'Sửa Danh Mục' : 'Thêm Danh Mục'; ?></h3>
            </div>
            <div class="admin-card-body">
                <form method="POST">
                    <?php if($edit_category): ?>
                        <input type="hidden" name="id" value="<?php echo $edit_category['id']; ?>">
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label>Tên Danh Mục <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="<?php echo $edit_category['name'] ?? ''; ?>" required placeholder="Dây chuyền">
                    </div>
                    
                    <div class="form-group">
                        <label>Slug <span class="text-danger">*</span></label>
                        <input type="text" name="slug" class="form-control" value="<?php echo $edit_category['slug'] ?? ''; ?>" required placeholder="day-chuyen">
                        <small class="text-muted">Không dấu, chữ thường, dùng dấu gạch ngang</small>
                    </div>
                    
                    <div class="form-group">
                        <label>Mô Tả</label>
                        <textarea name="description" class="form-control" rows="3"><?php echo $edit_category['description'] ?? ''; ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Hình Ảnh</label>
                        <input type="text" name="image" class="form-control" value="<?php echo $edit_category['image'] ?? ''; ?>" placeholder="category.jpg">
                        <small class="text-muted">Đặt file trong assets/images/categories/</small>
                    </div>
                    
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="status" <?php echo ($edit_category['status'] ?? 1) == 1 ? 'checked' : ''; ?>>
                            Hiển thị
                        </label>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> <?php echo $edit_category ? 'Cập Nhật' : 'Thêm Mới'; ?>
                    </button>
                    <?php if($edit_category): ?>
                        <a href="categories.php" class="btn btn-secondary">Hủy</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>

    <!-- Danh sách danh mục -->
    <div class="col-md-8">
        <div class="admin-card">
            <div class="admin-card-header">
                <h3><i class="fas fa-folder"></i> Danh Sách Danh Mục</h3>
            </div>
            <div class="admin-card-body">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên Danh Mục</th>
                            <th>Slug</th>
                            <th>Số SP</th>
                            <th>Trạng Thái</th>
                            <th>Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(mysqli_num_rows($categories) > 0): ?>
                            <?php while($category = mysqli_fetch_assoc($categories)): ?>
                                <tr>
                                    <td><?php echo $category['id']; ?></td>
                                    <td><strong><?php echo $category['name']; ?></strong></td>
                                    <td><code><?php echo $category['slug']; ?></code></td>
                                    <td><?php echo $category['product_count']; ?> sản phẩm</td>
                                    <td>
                                        <?php if($category['status'] == 1): ?>
                                            <span class="badge badge-success">Hiển thị</span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary">Ẩn</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="?edit=<?php echo $category['id']; ?>" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="?delete=<?php echo $category['id']; ?>" 
                                           class="btn btn-sm btn-danger" 
                                           onclick="return confirm('Bạn có chắc muốn xóa? Tất cả sản phẩm trong danh mục này cũng sẽ bị xóa!')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">Chưa có danh mục nào</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
