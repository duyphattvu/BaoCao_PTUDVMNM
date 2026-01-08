<?php
require_once 'check_admin.php';

$page_title = 'Quản lý Mã QR';
include 'includes/header.php';

$rootQrDir = __DIR__ . '/../assets/images/qr/';
$webQrPath = BASE_URL . 'assets/images/qr/';
$filename = 'qr_bank.png';
$targetPath = $rootQrDir . $filename;

// Ensure path normalization for Windows backslashes
$rootQrDir = str_replace('\\', '/', $rootQrDir);
$targetPath = str_replace('\\', '/', $targetPath);

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['qr_image']) && $_FILES['qr_image']['error'] === UPLOAD_ERR_OK) {
        $fileTmp = $_FILES['qr_image']['tmp_name'];
        $fileName = $_FILES['qr_image']['name'];
        $fileSize = $_FILES['qr_image']['size'];
        $fileType = mime_content_type($fileTmp);

        // Validate mime type (allow common image types)
        $allowed = array('image/png', 'image/jpeg', 'image/jpg', 'image/webp');
        if (!in_array($fileType, $allowed)) {
            $error = 'Chỉ chấp nhận ảnh PNG, JPG, JPEG hoặc WEBP.';
        } elseif ($fileSize > 5 * 1024 * 1024) {
            $error = 'Kích thước file quá lớn. Giới hạn 5MB.';
        } else {
            // Ensure directory exists
            if (!is_dir($rootQrDir)) {
                mkdir($rootQrDir, 0755, true);
            }

            // Move uploaded file to target (replace existing)
            if (move_uploaded_file($fileTmp, $targetPath)) {
                // Set proper permissions
                @chmod($targetPath, 0644);
                $message = 'Tải lên thành công. Ảnh QR đã được cập nhật.';
            } else {
                $error = 'Không thể lưu file. Vui lòng kiểm tra quyền thư mục.';
            }
        }
    } else {
        $error = 'Vui lòng chọn file để tải lên.';
    }
}
?>

<div class="admin-card">
    <div class="admin-card-header">
        <h3><i class="fas fa-qrcode"></i> Quản Lý Mã QR Thanh Toán</h3>
    </div>

    <div class="admin-card-body">
        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <p>Bạn có thể upload ảnh mã QR để khách hàng quét thanh toán. Ảnh sẽ được lưu đè lên <code><?php echo htmlspecialchars($filename); ?></code>.</p>

        <div style="display:flex; gap:20px; align-items:flex-start;">
            <div style="flex:1;">
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Chọn ảnh QR (PNG/JPG/WEBP, tối đa 5MB)</label>
                        <input type="file" name="qr_image" accept="image/*" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Tải Lên</button>
                </form>
            </div>

            <div style="width:280px; text-align:center;">
                <p>Ảnh QR hiện tại:</p>
                <?php if (file_exists($targetPath)): ?>
                    <img src="<?php echo $webQrPath . $filename; ?>?v=<?php echo filemtime($targetPath); ?>" alt="QR hiện tại" style="max-width:100%; border:1px solid #eee; padding:8px; background:#fff;">
                <?php else: ?>
                    <div style="padding:30px; background:#fafafa; border:1px dashed #ddd;">Chưa có ảnh QR</div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<?php include 'includes/footer.php'; ?>