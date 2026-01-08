<?php
// File xử lý lỗi 404 - Không tìm thấy trang
require_once 'includes/config.php';
$page_title = 'Không Tìm Thấy Trang - 404';
include 'includes/header.php';
?>

<div class="container" style="padding: 60px 20px; text-align: center;">
    <div style="max-width: 600px; margin: 0 auto;">
        <h1 style="font-size: 120px; color: #d4af37; margin: 0;">404</h1>
        <h2 style="font-size: 32px; color: #333; margin: 20px 0;">Không Tìm Thấy Trang</h2>
        <p style="font-size: 18px; color: #666; margin: 20px 0;">
            Xin lỗi, trang bạn đang tìm kiếm không tồn tại hoặc đã bị di chuyển.
        </p>
        <div style="margin-top: 40px;">
            <a href="<?php echo BASE_URL; ?>" class="btn btn-primary" style="padding: 12px 40px; font-size: 16px;">
                Về Trang Chủ
            </a>
            <a href="<?php echo BASE_URL; ?>new-products.php" class="btn btn-secondary" style="padding: 12px 40px; font-size: 16px; margin-left: 10px;">
                Xem Sản Phẩm
            </a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
