<?php
// Xử lý actions trước khi có bất kỳ output nào
session_start();
require_once '../includes/config.php';

// Kiểm tra quyền admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

// Xử lý xóa liên hệ
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
    $sql = "DELETE FROM contacts WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['success_message'] = 'Xóa liên hệ thành công!';
    } else {
        $_SESSION['error_message'] = 'Lỗi: ' . mysqli_error($conn);
    }
    $redirect_url = $_SERVER['PHP_SELF'] . "?filter=$filter";
    header("Location: $redirect_url");
    exit;
}

// Xử lý cập nhật trạng thái
if (isset($_GET['update_status'])) {
    $id = (int)$_GET['update_status'];
    $status = mysqli_real_escape_string($conn, $_GET['status']);
    $filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
    $sql = "UPDATE contacts SET status = '$status' WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['success_message'] = 'Cập nhật trạng thái thành công!';
    } else {
        $_SESSION['error_message'] = 'Lỗi: ' . mysqli_error($conn);
    }
    $redirect_url = $_SERVER['PHP_SELF'] . "?filter=$filter";
    header("Location: $redirect_url");
    exit;
}

// Xử lý gửi email trả lời
if (isset($_GET['action']) && $_GET['action'] == 'send_reply' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $contact_id = (int)$_POST['contact_id'];
    $to_email = mysqli_real_escape_string($conn, $_POST['to_email']);
    $to_name = mysqli_real_escape_string($conn, $_POST['to_name']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $filter = isset($_POST['filter']) ? $_POST['filter'] : 'all';
    
    // Cấu hình email
    $from_email = "noreply@trangsuc.com";
    $from_name = "Trang Sức Store";
    
    // Tạo nội dung email
    $email_body = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
            .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 8px 8px; }
            .message { background: white; padding: 20px; border-radius: 6px; margin: 20px 0; }
            .footer { text-align: center; margin-top: 20px; color: #666; font-size: 12px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>Trả lời từ $from_name</h2>
            </div>
            <div class='content'>
                <p>Xin chào <strong>$to_name</strong>,</p>
                <div class='message'>
                    " . nl2br($message) . "
                </div>
                <div class='footer'>
                    <p>Email này được gửi từ hệ thống quản lý của $from_name</p>
                    <p>Vui lòng không trả lời trực tiếp email này.</p>
                </div>
            </div>
        </div>
    </body>
    </html>
    ";
    
    // Headers cho email HTML
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: $from_name <$from_email>" . "\r\n";
    $headers .= "Reply-To: $from_email" . "\r\n";
    
    // Gửi email
    if (mail($to_email, $subject, $email_body, $headers)) {
        // Cập nhật trạng thái liên hệ thành "processing" nếu đang là "new"
        $update_sql = "UPDATE contacts SET status = 'processing' WHERE id = $contact_id AND status = 'new'";
        mysqli_query($conn, $update_sql);
        
        $_SESSION['success_message'] = 'Đã gửi email trả lời thành công đến ' . $to_email;
    } else {
        $_SESSION['error_message'] = 'Không thể gửi email. Vui lòng kiểm tra cấu hình email server.';
    }
    
    $redirect_url = $_SERVER['PHP_SELF'] . "?filter=$filter";
    header("Location: $redirect_url");
    exit;
}

// Sau khi xử lý xong mới set page title
$page_title = 'Quản Lý Liên Hệ';

$message = '';
$error = '';

// Hiển thị thông báo từ session
if (isset($_SESSION['success_message'])) {
    $message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}
if (isset($_SESSION['error_message'])) {
    $error = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}

// Lọc theo trạng thái
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$where = '';
switch($filter) {
    case 'new':
        $where = "WHERE status = 'new'";
        break;
    case 'processing':
        $where = "WHERE status = 'processing'";
        break;
    case 'completed':
        $where = "WHERE status = 'completed'";
        break;
}

// Phân trang
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 20;
$offset = ($page - 1) * $limit;

// Lấy tổng số
$total_sql = "SELECT COUNT(*) as total FROM contacts $where";
$total_result = mysqli_query($conn, $total_sql);
$total_row = mysqli_fetch_assoc($total_result);
$total = $total_row['total'];
$total_pages = ceil($total / $limit);

// Lấy danh sách liên hệ
$sql = "SELECT * FROM contacts $where ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
$contacts = mysqli_query($conn, $sql);

// Đếm số lượng theo trạng thái
$new_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM contacts WHERE status = 'new'"))['count'];
$processing_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM contacts WHERE status = 'processing'"))['count'];
$completed_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM contacts WHERE status = 'completed'"))['count'];

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

<!-- Filter Tabs -->
<div class="admin-card" style="margin-bottom: 20px;">
    <div class="admin-card-body">
        <div class="filter-tabs">
            <a href="<?php echo $_SERVER['PHP_SELF']; ?>?filter=all" class="btn <?php echo $filter == 'all' ? 'btn-primary' : 'btn-secondary'; ?>">
                <i class="fas fa-list"></i> Tất Cả (<?php echo $total; ?>)
            </a>
            <a href="<?php echo $_SERVER['PHP_SELF']; ?>?filter=new" class="btn <?php echo $filter == 'new' ? 'btn-primary' : 'btn-secondary'; ?>">
                <i class="fas fa-envelope"></i> Mới (<?php echo $new_count; ?>)
            </a>
            <a href="<?php echo $_SERVER['PHP_SELF']; ?>?filter=processing" class="btn <?php echo $filter == 'processing' ? 'btn-primary' : 'btn-secondary'; ?>">
                <i class="fas fa-spinner"></i> Đang Xử Lý (<?php echo $processing_count; ?>)
            </a>
            <a href="<?php echo $_SERVER['PHP_SELF']; ?>?filter=completed" class="btn <?php echo $filter == 'completed' ? 'btn-primary' : 'btn-secondary'; ?>">
                <i class="fas fa-check"></i> Hoàn Thành (<?php echo $completed_count; ?>)
            </a>
        </div>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card-header">
        <h3><i class="fas fa-envelope"></i> Danh Sách Liên Hệ</h3>
    </div>
    <div class="admin-card-body">
        <table class="admin-table">
            <thead>
                <tr>
                    <th width="5%">ID</th>
                    <th width="15%">Họ Tên</th>
                    <th width="15%">Email/SĐT</th>
                    <th width="15%">Tiêu Đề</th>
                    <th width="25%">Nội Dung</th>
                    <th width="10%">Trạng Thái</th>
                    <th width="10%">Ngày</th>
                    <th width="10%">Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php if(mysqli_num_rows($contacts) > 0): ?>
                    <?php while($contact = mysqli_fetch_assoc($contacts)): ?>
                        <tr>
                            <td><?php echo $contact['id']; ?></td>
                            <td><strong><?php echo htmlspecialchars($contact['fullname']); ?></strong></td>
                            <td>
                                <div><?php echo htmlspecialchars($contact['email']); ?></div>
                                <?php if($contact['phone']): ?>
                                    <small class="text-muted"><?php echo htmlspecialchars($contact['phone']); ?></small>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $contact['subject'] ? htmlspecialchars($contact['subject']) : '<em>Không có</em>'; ?></td>
                            <td>
                                <div style="max-height: 60px; overflow: hidden;">
                                    <?php echo htmlspecialchars(substr($contact['message'], 0, 100)); ?><?php echo strlen($contact['message']) > 100 ? '...' : ''; ?>
                                </div>
                            </td>
                            <td>
                                <?php
                                $status_colors = [
                                    'new' => 'info',
                                    'processing' => 'warning',
                                    'completed' => 'success'
                                ];
                                $status_labels = [
                                    'new' => 'Mới',
                                    'processing' => 'Đang xử lý',
                                    'completed' => 'Hoàn thành'
                                ];
                                $color = isset($status_colors[$contact['status']]) ? $status_colors[$contact['status']] : 'secondary';
                                $label = isset($status_labels[$contact['status']]) ? $status_labels[$contact['status']] : $contact['status'];
                                ?>
                                <span class="badge badge-<?php echo $color; ?>"><?php echo $label; ?></span>
                            </td>
                            <td><?php echo date('d/m/Y', strtotime($contact['created_at'])); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-icon btn-success" onclick="toggleReplyForm(<?php echo $contact['id']; ?>)" title="Trả lời">
                                        <i class="fas fa-comment-dots"></i>
                                    </button>
                                    <div class="dropdown" style="display: inline-block; position: relative;">
                                        <button class="btn-icon btn-secondary" onclick="toggleDropdown(event, <?php echo $contact['id']; ?>)" title="Thêm">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <div id="dropdown-<?php echo $contact['id']; ?>" class="dropdown-menu" style="display: none; position: absolute; right: 0; top: 100%; background: white; border: 1px solid #ddd; padding: 8px; border-radius: 8px; z-index: 1000; box-shadow: 0 4px 12px rgba(0,0,0,0.15); min-width: 180px; margin-top: 5px;">
                                            <a href="<?php echo $_SERVER['PHP_SELF']; ?>?update_status=<?php echo $contact['id']; ?>&status=processing&filter=<?php echo $filter; ?>" style="display: block; padding: 10px 12px; color: #333; text-decoration: none; border-radius: 5px; white-space: nowrap;" onmouseover="this.style.background='#f0f0f0'" onmouseout="this.style.background='white'">
                                                <i class="fas fa-spinner" style="width: 20px;"></i> Đang xử lý
                                            </a>
                                            <a href="<?php echo $_SERVER['PHP_SELF']; ?>?update_status=<?php echo $contact['id']; ?>&status=completed&filter=<?php echo $filter; ?>" style="display: block; padding: 10px 12px; color: #333; text-decoration: none; border-radius: 5px; white-space: nowrap;" onmouseover="this.style.background='#f0f0f0'" onmouseout="this.style.background='white'">
                                                <i class="fas fa-check" style="width: 20px;"></i> Hoàn thành
                                            </a>
                                            <div style="border-top: 1px solid #eee; margin: 8px 0;"></div>
                                            <a href="<?php echo $_SERVER['PHP_SELF']; ?>?delete=<?php echo $contact['id']; ?>&filter=<?php echo $filter; ?>" style="display: block; padding: 10px 12px; color: #dc3545; text-decoration: none; border-radius: 5px; white-space: nowrap;" onclick="return confirm('Bạn có chắc muốn xóa?')" onmouseover="this.style.background='#fee'" onmouseout="this.style.background='white'">
                                                <i class="fas fa-trash" style="width: 20px;"></i> Xóa
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <!-- Reply Form Row -->
                        <tr id="reply-form-<?php echo $contact['id']; ?>" style="display: none;">
                            <td colspan="8" style="background: #f8f9fa; padding: 20px;">
                                <div style="max-width: 800px; margin: 0 auto;">
                                    <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 2px solid #e9ecef;">
                                            <h4 style="margin: 0; color: #333;">
                                                <i class="fas fa-reply" style="color: #28a745;"></i> Trả lời liên hệ
                                            </h4>
                                            <button onclick="toggleReplyForm(<?php echo $contact['id']; ?>)" style="background: none; border: none; font-size: 24px; color: #999; cursor: pointer; padding: 0; line-height: 1;">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                        
                                        <div style="background: #e7f3ff; padding: 15px; border-radius: 6px; margin-bottom: 20px; border-left: 4px solid #007bff;">
                                            <div style="margin-bottom: 8px;">
                                                <strong style="color: #333;">Từ:</strong> 
                                                <span style="color: #666;"><?php echo htmlspecialchars($contact['fullname']); ?></span>
                                                <span style="color: #999; margin-left: 10px;">&lt;<?php echo htmlspecialchars($contact['email']); ?>&gt;</span>
                                            </div>
                                            <div style="margin-bottom: 8px;">
                                                <strong style="color: #333;">Tiêu đề:</strong> 
                                                <span style="color: #666;"><?php echo $contact['subject'] ? htmlspecialchars($contact['subject']) : 'Không có'; ?></span>
                                            </div>
                                            <div>
                                                <strong style="color: #333;">Nội dung:</strong>
                                                <div style="color: #666; margin-top: 8px; padding: 10px; background: white; border-radius: 4px;">
                                                    <?php echo nl2br(htmlspecialchars($contact['message'])); ?>
                                                </div>
                                            </div>
                                        </div>

                                        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=send_reply" style="margin: 0;">
                                            <input type="hidden" name="contact_id" value="<?php echo $contact['id']; ?>">
                                            <input type="hidden" name="to_email" value="<?php echo htmlspecialchars($contact['email']); ?>">
                                            <input type="hidden" name="to_name" value="<?php echo htmlspecialchars($contact['fullname']); ?>">
                                            <input type="hidden" name="filter" value="<?php echo $filter; ?>">
                                            
                                            <div style="margin-bottom: 15px;">
                                                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #333;">
                                                    <i class="fas fa-heading"></i> Tiêu đề email
                                                </label>
                                                <input type="text" name="subject" 
                                                       value="Re: <?php echo htmlspecialchars($contact['subject'] ?? 'Liên hệ từ website'); ?>"
                                                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;"
                                                       required>
                                            </div>
                                            
                                            <div style="margin-bottom: 15px;">
                                                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #333;">
                                                    <i class="fas fa-envelope"></i> Nội dung trả lời
                                                </label>
                                                <textarea name="message" rows="6" 
                                                          style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; resize: vertical;"
                                                          placeholder="Nhập nội dung trả lời..." required></textarea>
                                            </div>
                                            
                                            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                                                <button type="button" onclick="toggleReplyForm(<?php echo $contact['id']; ?>)" 
                                                        style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px;">
                                                    <i class="fas fa-times"></i> Hủy
                                                </button>
                                                <button type="submit" 
                                                        style="padding: 10px 20px; background: #28a745; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px;">
                                                    <i class="fas fa-paper-plane"></i> Gửi trả lời
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center">Không có liên hệ nào</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <?php if($total_pages > 1): ?>
            <div class="pagination">
                <?php for($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=<?php echo $i; ?>&filter=<?php echo $filter; ?>" 
                       class="btn btn-sm <?php echo $page == $i ? 'btn-primary' : 'btn-secondary'; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.btn-icon.btn-success {
    background: #28a745;
    color: white;
}
.btn-icon.btn-success:hover {
    background: #218838;
}
</style>

<script>
function toggleReplyForm(id) {
    var form = document.getElementById('reply-form-' + id);
    if (form.style.display === 'none' || form.style.display === '') {
        // Đóng tất cả form khác
        var allForms = document.querySelectorAll('[id^="reply-form-"]');
        allForms.forEach(function(f) {
            f.style.display = 'none';
        });
        // Mở form hiện tại
        form.style.display = 'table-row';
    } else {
        form.style.display = 'none';
    }
}

function toggleDropdown(event, id) {
    event.stopPropagation();
    
    // Đóng tất cả dropdown khác
    var allDropdowns = document.querySelectorAll('.dropdown-menu');
    allDropdowns.forEach(function(dropdown) {
        if (dropdown.id !== 'dropdown-' + id) {
            dropdown.style.display = 'none';
        }
    });
    
    // Toggle dropdown hiện tại
    var dropdown = document.getElementById('dropdown-' + id);
    if (dropdown.style.display === 'none' || dropdown.style.display === '') {
        dropdown.style.display = 'block';
    } else {
        dropdown.style.display = 'none';
    }
}

// Đóng dropdown khi click bên ngoài
document.addEventListener('click', function(event) {
    if (!event.target.closest('.dropdown')) {
        var dropdowns = document.querySelectorAll('.dropdown-menu');
        dropdowns.forEach(function(dropdown) {
            dropdown.style.display = 'none';
        });
    }
});
</script>

<?php include 'includes/footer.php'; ?>
