<?php
require_once 'check_admin.php';
$page_title = 'Quản Lý Phản Hồi';

$message = '';
$error = '';

// Xử lý xóa phản hồi
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $sql = "DELETE FROM feedbacks WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        $message = 'Xóa phản hồi thành công!';
    } else {
        $error = 'Lỗi: ' . mysqli_error($conn);
    }
}

// Xử lý ẩn/hiện phản hồi
if (isset($_GET['toggle_status'])) {
    $id = (int)$_GET['toggle_status'];
    $sql = "UPDATE feedbacks SET status = IF(status = 1, 0, 1) WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        $message = 'Cập nhật trạng thái thành công!';
    } else {
        $error = 'Lỗi: ' . mysqli_error($conn);
    }
}

// Phân trang
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 20;
$offset = ($page - 1) * $limit;

// Lấy tổng số
$total_sql = "SELECT COUNT(*) as total FROM feedbacks";
$total_result = mysqli_query($conn, $total_sql);
$total_row = mysqli_fetch_assoc($total_result);
$total = $total_row['total'];
$total_pages = ceil($total / $limit);

// Lấy danh sách phản hồi
$sql = "SELECT f.*, u.fullname as user_name, p.name as product_name 
        FROM feedbacks f 
        LEFT JOIN users u ON f.user_id = u.id 
        LEFT JOIN products p ON f.product_id = p.id 
        ORDER BY f.created_at DESC 
        LIMIT $limit OFFSET $offset";
$feedbacks = mysqli_query($conn, $sql);

// Thống kê
$total_feedbacks = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM feedbacks"))['count'];
$active_feedbacks = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM feedbacks WHERE status = 1"))['count'];
$avg_rating = mysqli_fetch_assoc(mysqli_query($conn, "SELECT AVG(rating) as avg FROM feedbacks"))['avg'] ?: 0;

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

<!-- Stats -->
<div class="stats-grid" style="margin-bottom: 30px;">
    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="fas fa-comments"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo $total_feedbacks; ?></h3>
            <p>Tổng Phản Hồi</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon green">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo $active_feedbacks; ?></h3>
            <p>Đang Hiển Thị</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon orange">
            <i class="fas fa-star"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo number_format($avg_rating, 1); ?></h3>
            <p>Đánh Giá Trung Bình</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon purple">
            <i class="fas fa-eye-slash"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo $total_feedbacks - $active_feedbacks; ?></h3>
            <p>Đã Ẩn</p>
        </div>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card-header">
        <h3><i class="fas fa-comments"></i> Danh Sách Phản Hồi</h3>
    </div>
    <div class="admin-card-body">
        <table class="admin-table">
            <thead>
                <tr>
                    <th width="5%">ID</th>
                    <th width="15%">Khách Hàng</th>
                    <th width="20%">Sản Phẩm</th>
                    <th width="10%">Đánh Giá</th>
                    <th width="30%">Nhận Xét</th>
                    <th width="10%">Trạng Thái</th>
                    <th width="10%">Ngày</th>
                    <th width="10%">Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php if(mysqli_num_rows($feedbacks) > 0): ?>
                    <?php while($feedback = mysqli_fetch_assoc($feedbacks)): ?>
                        <tr>
                            <td><?php echo $feedback['id']; ?></td>
                            <td>
                                <strong><?php echo $feedback['user_name'] ?? 'Khách'; ?></strong>
                            </td>
                            <td>
                                <?php if($feedback['product_name']): ?>
                                    <a href="<?php echo BASE_URL; ?>product-detail.php?id=<?php echo $feedback['product_id']; ?>" target="_blank">
                                        <?php echo $feedback['product_name']; ?>
                                    </a>
                                <?php else: ?>
                                    <em>Sản phẩm đã xóa</em>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="rating">
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                        <i class="fas fa-star" style="color: <?php echo $i <= $feedback['rating'] ? '#ffc107' : '#ddd'; ?>;"></i>
                                    <?php endfor; ?>
                                </div>
                            </td>
                            <td>
                                <?php if($feedback['comment']): ?>
                                    <div style="max-height: 60px; overflow: hidden;">
                                        <?php echo substr($feedback['comment'], 0, 100); ?><?php echo strlen($feedback['comment']) > 100 ? '...' : ''; ?>
                                    </div>
                                <?php else: ?>
                                    <em class="text-muted">Không có nhận xét</em>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($feedback['status'] == 1): ?>
                                    <span class="badge badge-success">Hiển thị</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary">Ẩn</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo date('d/m/Y', strtotime($feedback['created_at'])); ?></td>
                            <td>
                                <a href="?toggle_status=<?php echo $feedback['id']; ?>" 
                                   class="btn btn-sm btn-<?php echo $feedback['status'] == 1 ? 'warning' : 'success'; ?>" 
                                   title="<?php echo $feedback['status'] == 1 ? 'Ẩn' : 'Hiện'; ?>">
                                    <i class="fas fa-eye<?php echo $feedback['status'] == 1 ? '-slash' : ''; ?>"></i>
                                </a>
                                <a href="?delete=<?php echo $feedback['id']; ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Bạn có chắc muốn xóa?')"
                                   title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center">Chưa có phản hồi nào</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <?php if($total_pages > 1): ?>
            <div class="pagination">
                <?php for($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>" 
                       class="btn btn-sm <?php echo $page == $i ? 'btn-primary' : 'btn-secondary'; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
