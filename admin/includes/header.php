<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'Admin - Trang Sức Bạc'; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    <link rel="stylesheet" href="<?php echo ADMIN_URL; ?>assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <base href="<?php echo BASE_URL; ?>">
</head>
<body class="admin-body">
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="admin-logo">
                <i class="fas fa-gem"></i>
                <h2>ADMIN PANEL</h2>
            </div>
            
            <nav class="admin-nav">
                <ul>
                    <li>
                        <a href="<?php echo ADMIN_URL; ?>index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo ADMIN_URL; ?>profile.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : ''; ?>">
                            <i class="fas fa-user-circle"></i> Hồ Sơ
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo ADMIN_URL; ?>banners.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'banners.php' ? 'active' : ''; ?>">
                            <i class="fas fa-images"></i> Quản Lý Banner
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo ADMIN_URL; ?>qr.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'qr.php' ? 'active' : ''; ?>">
                            <i class="fas fa-qrcode"></i> Mã QR Thanh Toán
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo ADMIN_URL; ?>categories.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'categories.php' ? 'active' : ''; ?>">
                            <i class="fas fa-folder"></i> Danh Mục
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo ADMIN_URL; ?>products.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'products.php' ? 'active' : ''; ?>">
                            <i class="fas fa-box"></i> Sản Phẩm
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo ADMIN_URL; ?>orders.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'orders.php' ? 'active' : ''; ?>">
                            <i class="fas fa-shopping-cart"></i> Đặt Hàng
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo ADMIN_URL; ?>users.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'users.php' ? 'active' : ''; ?>">
                            <i class="fas fa-users"></i> Người Dùng
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo ADMIN_URL; ?>contacts.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'contacts.php' ? 'active' : ''; ?>">
                            <i class="fas fa-envelope"></i> Liên Hệ
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo ADMIN_URL; ?>chat-admin.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'chat-admin.php' ? 'active' : ''; ?>" style="position: relative;">
                            <i class="fas fa-comments"></i> Chat Hỗ Trợ
                            <?php
                            // Đếm số tin nhắn chưa đọc
                            $unread_sql = "SELECT COUNT(*) as count FROM messages WHERE sender_type = 'user' AND is_read = 0";
                            $unread_result = mysqli_query($conn, $unread_sql);
                            $unread_count = 0;
                            if ($unread_result) {
                                $unread_data = mysqli_fetch_assoc($unread_result);
                                $unread_count = $unread_data['count'];
                            }
                            if ($unread_count > 0):
                            ?>
                            <span style="position: absolute; top: 5px; right: 10px; background: #ef4444; color: white; border-radius: 10px; padding: 2px 6px; font-size: 10px; font-weight: bold;">
                                <?php echo $unread_count; ?>
                            </span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>index.php?from_admin=1">
                            <i class="fas fa-home"></i> Xem Trang Chủ
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>logout.php" style="color: #e74c3c;">
                            <i class="fas fa-sign-out-alt"></i> Đăng Xuất
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="admin-main">
            <!-- Top Bar -->
            <header class="admin-header">
                <div class="admin-header-left">
                    <button class="sidebar-toggle" onclick="toggleSidebar()">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 style="font-size: 20px; color: #1a1a1a; margin: 0;">
                        <?php echo isset($page_title) ? $page_title : 'Dashboard'; ?>
                    </h1>
                </div>
                <div class="admin-header-right">
                    <span style="margin-right: 15px; color: #666;">
                        <i class="fas fa-user"></i> <?php echo $_SESSION['user_name']; ?>
                    </span>
                    <a href="<?php echo BASE_URL; ?>logout.php" class="btn btn-secondary" style="padding: 8px 20px;">
                        <i class="fas fa-sign-out-alt"></i> Đăng xuất
                    </a>
                </div>
            </header>

            <!-- Content -->
            <div class="admin-content">
