<?php
require_once 'check_admin.php';
$page_title = 'Quản Lý Banner';

$message = '';
$error = '';

// Xử lý xóa banner
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $sql = "DELETE FROM banners WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        $message = 'Xóa banner thành công!';
    } else {
        $error = 'Lỗi: ' . mysqli_error($conn);
    }
}

// Xử lý thêm/sửa banner
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = mysqli_real_escape_string($conn, trim($_POST['title']));
    $link = mysqli_real_escape_string($conn, trim($_POST['link']));
    $position = (int)$_POST['position'];
    $status = isset($_POST['status']) ? 1 : 0;
    $image = $_POST['image'] ?? 'banner-1.jpg'; // Default image
    
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // Update
        $id = (int)$_POST['id'];
        $sql = "UPDATE banners SET title = '$title', image = '$image', link = '$link', position = $position, status = $status WHERE id = $id";
        $msg = 'Cập nhật banner thành công!';
    } else {
        // Insert
        $sql = "INSERT INTO banners (title, image, link, position, status) VALUES ('$title', '$image', '$link', $position, $status)";
        $msg = 'Thêm banner thành công!';
    }
    
    if (mysqli_query($conn, $sql)) {
        $message = $msg;
    } else {
        $error = 'Lỗi: ' . mysqli_error($conn);
    }
}

// Lấy danh sách banner
$sql = "SELECT * FROM banners ORDER BY position ASC";
$banners = mysqli_query($conn, $sql);

// Lấy thông tin banner để sửa
$edit_banner = null;
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $edit_result = mysqli_query($conn, "SELECT * FROM banners WHERE id = $edit_id");
    $edit_banner = mysqli_fetch_assoc($edit_result);
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
    <!-- Form thêm/sửa banner -->
    <div class="col-md-4">
        <div class="admin-card">
            <div class="admin-card-header">
                <h3><i class="fas fa-plus"></i> <?php echo $edit_banner ? 'Sửa Banner' : 'Thêm Banner'; ?></h3>
            </div>
            <div class="admin-card-body">
                <form method="POST">
                    <?php if($edit_banner): ?>
                        <input type="hidden" name="id" value="<?php echo $edit_banner['id']; ?>">
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label>Tiêu Đề</label>
                        <input type="text" name="title" class="form-control" value="<?php echo $edit_banner['title'] ?? ''; ?>" placeholder="Banner Khuyến Mãi">
                    </div>
                    
                    <div class="form-group">
                        <label>Hình Ảnh</label>
                        <input type="text" name="image" class="form-control" value="<?php echo $edit_banner['image'] ?? 'banner-1.jpg'; ?>" placeholder="banner-1.jpg">
                        <small class="text-muted">Đặt file trong assets/images/banners/</small>
                    </div>
                    
                    <div class="form-group">
                        <label>Liên Kết</label>
                        <input type="text" name="link" class="form-control" value="<?php echo $edit_banner['link'] ?? '#'; ?>" placeholder="#">
                    </div>
                    
                    <div class="form-group">
                        <label>Vị Trí</label>
                        <input type="number" name="position" class="form-control" value="<?php echo $edit_banner['position'] ?? 0; ?>" min="0">
                    </div>
                    
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="status" <?php echo ($edit_banner['status'] ?? 1) == 1 ? 'checked' : ''; ?>>
                            Hiển thị
                        </label>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> <?php echo $edit_banner ? 'Cập Nhật' : 'Thêm Mới'; ?>
                    </button>
                    <?php if($edit_banner): ?>
                        <a href="banners.php" class="btn btn-secondary">Hủy</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>

    <!-- Danh sách banner -->
    <div class="col-md-8">
        <div class="admin-card">
            <div class="admin-card-header">
                <h3><i class="fas fa-images"></i> Danh Sách Banner</h3>
            </div>
            <div class="admin-card-body">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Hình Ảnh</th>
                            <th>Tiêu Đề</th>
                            <th>Vị Trí</th>
                            <th>Trạng Thái</th>
                            <th>Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(mysqli_num_rows($banners) > 0): ?>
                            <?php while($banner = mysqli_fetch_assoc($banners)): ?>
                                <tr>
                                    <td><?php echo $banner['id']; ?></td>
                                    <td>
                                        <img src="<?php echo BASE_URL; ?>assets/images/banners/<?php echo $banner['image']; ?>" 
                                             alt="<?php echo $banner['title']; ?>" 
                                             style="width: 100px; height: 50px; object-fit: cover; border-radius: 4px;">
                                    </td>
                                    <td><?php echo $banner['title']; ?></td>
                                    <td><?php echo $banner['position']; ?></td>
                                    <td>
                                        <?php if($banner['status'] == 1): ?>
                                            <span class="badge badge-success">Hiển thị</span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary">Ẩn</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="?edit=<?php echo $banner['id']; ?>" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="?delete=<?php echo $banner['id']; ?>" 
                                           class="btn btn-sm btn-danger" 
                                           onclick="return confirm('Bạn có chắc muốn xóa?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">Chưa có banner nào</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
