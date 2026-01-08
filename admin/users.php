<?php
require_once 'check_admin.php';
$page_title = 'Quản Lý Người Dùng';

$message = '';
$error = '';

// Xử lý xóa người dùng
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    // Không cho xóa admin
    $check = mysqli_query($conn, "SELECT role FROM users WHERE id = $id");
    $user = mysqli_fetch_assoc($check);
    if ($user['role'] != 'admin') {
        $sql = "DELETE FROM users WHERE id = $id";
        if (mysqli_query($conn, $sql)) {
            $message = 'Xóa người dùng thành công!';
        } else {
            $error = 'Lỗi: ' . mysqli_error($conn);
        }
    } else {
        $error = 'Không thể xóa tài khoản admin!';
    }
}

// Xử lý thay đổi trạng thái
if (isset($_GET['toggle_status'])) {
    $id = (int)$_GET['toggle_status'];
    $sql = "UPDATE users SET status = NOT status WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        $message = 'Đã thay đổi trạng thái người dùng!';
    } else {
        $error = 'Lỗi: ' . mysqli_error($conn);
    }
}

// Xử lý thêm/sửa người dùng
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = mysqli_real_escape_string($conn, trim($_POST['fullname']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $phone = mysqli_real_escape_string($conn, trim($_POST['phone']));
    $address = mysqli_real_escape_string($conn, trim($_POST['address']));
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $status = isset($_POST['status']) ? 1 : 0;
    
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // Cập nhật
        $id = (int)$_POST['id'];
        $sql = "UPDATE users SET fullname = '$fullname', email = '$email', phone = '$phone', 
                address = '$address', role = '$role', status = $status WHERE id = $id";
        $msg = 'Cập nhật người dùng thành công!';
        
        // Cập nhật mật khẩu nếu có nhập
        if (!empty($_POST['password'])) {
            $password = md5($_POST['password']); // Dùng MD5 cố định
            mysqli_query($conn, "UPDATE users SET password = '$password' WHERE id = $id");
        }
    } else {
        // Thêm mới
        if (empty($_POST['password'])) {
            $error = 'Vui lòng nhập mật khẩu!';
        } else {
            // Kiểm tra email đã tồn tại chưa
            $check = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email'");
            if (mysqli_num_rows($check) > 0) {
                $error = 'Email đã tồn tại!';
            } else {
                $password = md5($_POST['password']); // Dùng MD5 cố định
                $sql = "INSERT INTO users (fullname, email, phone, address, password, role, status) 
                        VALUES ('$fullname', '$email', '$phone', '$address', '$password', '$role', $status)";
                $msg = 'Thêm người dùng thành công!';
            }
        }
    }
    
    if (!$error && isset($sql)) {
        if (mysqli_query($conn, $sql)) {
            $message = $msg;
        } else {
            $error = 'Lỗi: ' . mysqli_error($conn);
        }
    }
}

// Lấy danh sách người dùng
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 20;
$offset = ($page - 1) * $limit;

// Tìm kiếm
$search = '';
$where = '';
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $where = "WHERE fullname LIKE '%$search%' OR email LIKE '%$search%' OR phone LIKE '%$search%'";
}

// Lọc theo role
if (isset($_GET['role']) && !empty($_GET['role'])) {
    $role_filter = mysqli_real_escape_string($conn, $_GET['role']);
    $where .= ($where ? ' AND' : 'WHERE') . " role = '$role_filter'";
}

$sql = "SELECT * FROM users $where ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
$users = mysqli_query($conn, $sql);

// Đếm tổng
$total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM users $where"))['count'];
$total_pages = ceil($total / $limit);

// Lấy thông tin người dùng để sửa
$edit_user = null;
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $edit_result = mysqli_query($conn, "SELECT * FROM users WHERE id = $edit_id");
    $edit_user = mysqli_fetch_assoc($edit_result);
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
    <!-- Form thêm/sửa -->
    <div class="col-md-4">
        <div class="admin-card">
            <div class="admin-card-header">
                <h3><i class="fas fa-user-plus"></i> <?php echo $edit_user ? 'Sửa Người Dùng' : 'Thêm Người Dùng'; ?></h3>
            </div>
            <div class="admin-card-body">
                <form method="POST">
                    <?php if($edit_user): ?>
                        <input type="hidden" name="id" value="<?php echo $edit_user['id']; ?>">
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label>Họ và tên <span class="text-danger">*</span></label>
                        <input type="text" name="fullname" class="form-control" required
                               value="<?php echo $edit_user['fullname'] ?? ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" required
                               value="<?php echo $edit_user['email'] ?? ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Số điện thoại</label>
                        <input type="text" name="phone" class="form-control"
                               value="<?php echo $edit_user['phone'] ?? ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Địa chỉ</label>
                        <textarea name="address" class="form-control" rows="2"><?php echo $edit_user['address'] ?? ''; ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Mật khẩu <?php echo $edit_user ? '' : '<span class="text-danger">*</span>'; ?></label>
                        <input type="password" name="password" class="form-control" 
                               <?php echo $edit_user ? '' : 'required'; ?>
                               placeholder="<?php echo $edit_user ? 'Để trống nếu không đổi' : ''; ?>">
                        <small class="text-muted">Mật khẩu sẽ được mã hóa MD5</small>
                    </div>
                    
                    <div class="form-group">
                        <label>Vai trò</label>
                        <select name="role" class="form-control">
                            <option value="user" <?php echo ($edit_user['role'] ?? '') == 'user' ? 'selected' : ''; ?>>Khách hàng</option>
                            <option value="admin" <?php echo ($edit_user['role'] ?? '') == 'admin' ? 'selected' : ''; ?>>Quản trị viên</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="status" <?php echo ($edit_user['status'] ?? 1) == 1 ? 'checked' : ''; ?>>
                            Kích hoạt
                        </label>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> <?php echo $edit_user ? 'Cập Nhật' : 'Thêm Mới'; ?>
                    </button>
                    <?php if($edit_user): ?>
                        <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-secondary">Hủy</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>

    <!-- Danh sách -->
    <div class="col-md-8">
        <div class="admin-card">
            <div class="admin-card-header">
                <h3><i class="fas fa-users"></i> Danh Sách Người Dùng (<?php echo $total; ?>)</h3>
            </div>
            <div class="admin-card-body">
                <!-- Filter -->
                <form method="GET" class="filter-form">
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Tìm theo tên, email, SĐT..." 
                                   value="<?php echo htmlspecialchars($search); ?>">
                        </div>
                        <div class="col-md-4">
                            <select name="role" class="form-control">
                                <option value="">Tất cả vai trò</option>
                                <option value="user" <?php echo ($_GET['role'] ?? '') == 'user' ? 'selected' : ''; ?>>Khách hàng</option>
                                <option value="admin" <?php echo ($_GET['role'] ?? '') == 'admin' ? 'selected' : ''; ?>>Admin</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Họ Tên</th>
                                <th>Email</th>
                                <th>SĐT</th>
                                <th>Vai Trò</th>
                                <th>Trạng Thái</th>
                                <th>Ngày Đăng Ký</th>
                                <th>Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($user = mysqli_fetch_assoc($users)): ?>
                            <tr>
                                <td><?php echo $user['id']; ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($user['fullname']); ?></strong>
                                    <?php if($user['role'] == 'admin'): ?>
                                    <span class="badge badge-danger">Admin</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo htmlspecialchars($user['phone']); ?></td>
                                <td>
                                    <?php if($user['role'] == 'admin'): ?>
                                    <span class="badge badge-danger"><i class="fas fa-user-shield"></i> Admin</span>
                                    <?php else: ?>
                                    <span class="badge badge-info"><i class="fas fa-user"></i> Khách hàng</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?php echo $_SERVER['PHP_SELF']; ?>?toggle_status=<?php echo $user['id']; ?>" 
                                       onclick="return confirm('Thay đổi trạng thái?')">
                                        <?php if($user['status'] == 1): ?>
                                        <span class="badge badge-success">Kích hoạt</span>
                                        <?php else: ?>
                                        <span class="badge badge-secondary">Khóa</span>
                                        <?php endif; ?>
                                    </a>
                                </td>
                                <td><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></td>
                                <td>
                                    <a href="<?php echo $_SERVER['PHP_SELF']; ?>?edit=<?php echo $user['id']; ?>" class="btn btn-sm btn-info" title="Sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php if($user['role'] != 'admin'): ?>
                                    <a href="<?php echo $_SERVER['PHP_SELF']; ?>?delete=<?php echo $user['id']; ?>" class="btn btn-sm btn-danger" 
                                       onclick="return confirm('Xóa người dùng này?')" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php 
                    $query_params = $_GET;
                    unset($query_params['page']);
                    $query_string = http_build_query($query_params);
                    $query_string = $query_string ? '&' . $query_string : '';
                    
                    if ($page > 1): ?>
                    <a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=<?php echo $page - 1; ?><?php echo $query_string; ?>" class="btn btn-secondary">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                    <?php endif; ?>
                    
                    <span class="mx-2">Trang <?php echo $page; ?> / <?php echo $total_pages; ?></span>
                    
                    <?php if ($page < $total_pages): ?>
                    <a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=<?php echo $page + 1; ?><?php echo $query_string; ?>" class="btn btn-secondary">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
