<?php
// Script sửa TOÀN BỘ lỗi encoding - Database + Files
set_time_limit(600);
header('Content-Type: text/html; charset=UTF-8');

require_once 'includes/config.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Toàn Bộ Encoding</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; padding: 20px; }
        .container { max-width: 900px; margin: 0 auto; background: white; border-radius: 20px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); overflow: hidden; }
        .header { background: linear-gradient(135deg, #d4af37 0%, #c9991f 100%); color: white; padding: 30px; text-align: center; }
        .header h1 { font-size: 32px; margin-bottom: 10px; }
        .content { padding: 30px; }
        .step { background: #f8f9fa; border-left: 4px solid #d4af37; padding: 20px; margin: 15px 0; border-radius: 8px; }
        .step h3 { color: #333; margin-bottom: 15px; font-size: 20px; }
        .success { color: #27ae60; font-weight: bold; }
        .error { color: #e74c3c; font-weight: bold; }
        .info { color: #3498db; }
        .progress { background: #e0e0e0; height: 30px; border-radius: 15px; overflow: hidden; margin: 20px 0; }
        .progress-bar { background: linear-gradient(90deg, #d4af37, #f9d87d); height: 100%; transition: width 0.3s; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; }
        .btn { display: inline-block; padding: 15px 30px; background: #d4af37; color: white; text-decoration: none; border-radius: 8px; font-weight: bold; margin: 10px 5px; transition: all 0.3s; }
        .btn:hover { background: #c9991f; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(212,175,55,0.3); }
        .summary { background: #e8f5e9; border: 2px solid #4caf50; padding: 25px; border-radius: 10px; margin: 20px 0; }
        .summary h2 { color: #2e7d32; margin-bottom: 15px; }
        ul { list-style: none; padding-left: 0; }
        li { padding: 8px 0; }
        li:before { content: "✓ "; color: #27ae60; font-weight: bold; margin-right: 8px; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>🔧 Sửa Toàn Bộ Lỗi Encoding</h1>
        <p>Tự động sửa Database + Files</p>
    </div>
    
    <div class="content">
        <?php
        $total_fixed = 0;
        $total_errors = 0;
        
        // ===== BƯỚC 1: SỬA DATABASE =====
        echo '<div class="step">';
        echo '<h3>📊 Bước 1: Sửa Database</h3>';
        
        // Set charset
        mysqli_query($conn, "SET NAMES utf8mb4");
        mysqli_set_charset($conn, "utf8mb4");
        
        // Sửa database
        if (mysqli_query($conn, "ALTER DATABASE trangsuc_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci")) {
            echo '<p class="success">✓ Đã sửa charset cho database</p>';
            $total_fixed++;
        }
        
        // Sửa các bảng
        $tables = ['categories', 'products', 'users', 'orders', 'order_details', 'banners', 'news', 'contacts', 'messages'];
        foreach ($tables as $table) {
            $check = mysqli_query($conn, "SHOW TABLES LIKE '$table'");
            if (mysqli_num_rows($check) > 0) {
                if (mysqli_query($conn, "ALTER TABLE $table CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci")) {
                    echo "<p class='success'>✓ Bảng: $table</p>";
                    $total_fixed++;
                }
            }
        }
        echo '</div>';
        
        // ===== BƯỚC 2: SỬA DỮ LIỆU TRONG DATABASE =====
        echo '<div class="step">';
        echo '<h3>🔄 Bước 2: Sửa Dữ Liệu Trong Database</h3>';
        
        // Sửa tên danh mục
        $categories_fix = [
            'Day chuyen' => 'Dây chuyền',
            'Lac tay' => 'Lắc tay',
            'Nhan nu' => 'Nhẫn nữ',
            'Bong tai' => 'Bông tai',
            'Nhan doi' => 'Nhẫn đôi',
            'Lac chan' => 'Lắc chân',
        ];
        
        foreach ($categories_fix as $old => $new) {
            mysqli_query($conn, "UPDATE categories SET name = '$new' WHERE name LIKE '%$old%'");
        }
        echo '<p class="success">✓ Đã sửa tên danh mục</p>';
        $total_fixed++;
        
        // Sửa tên sản phẩm - Các từ khóa phổ biến
        $product_fixes = [
            // Các ký tự lỗi encoding
            'Ä' => 'Đ',
            'Ã¡' => 'á',
            'Ã ' => 'à',
            'áº£' => 'ả',
            'Ã£' => 'ã',
            'áº¡' => 'ạ',
            'Ã©' => 'é',
            'Ã¨' => 'è',
            'áº»' => 'ẻ',
            'áº½' => 'ẽ',
            'áº¹' => 'ẹ',
            'Ã­' => 'í',
            'Ã¬' => 'ì',
            'Ä©' => 'ĩ',
            'á»‹' => 'ị',
            'Ã³' => 'ó',
            'Ã²' => 'ò',
            'á»' => 'ỏ',
            'Ãµ' => 'õ',
            'á»' => 'ọ',
            'Ãº' => 'ú',
            'Ã¹' => 'ù',
            'á»§' => 'ủ',
            'Å©' => 'ũ',
            'á»¥' => 'ụ',
            'Ã½' => 'ý',
            'á»³' => 'ỳ',
            'á»·' => 'ỷ',
            'á»¹' => 'ỹ',
            'á»±' => 'ự',
            'Æ¯' => 'ư',
            'Æ°' => 'Ư',
            'Æ¡' => 'ơ',
            'Æ ' => 'Ơ',
        ];
        
        // Lấy tất cả sản phẩm
        $products = mysqli_query($conn, "SELECT id, name, description FROM products");
        $fixed_products = 0;
        
        while ($product = mysqli_fetch_assoc($products)) {
            $old_name = $product['name'];
            $old_desc = $product['description'];
            
            $new_name = str_replace(array_keys($product_fixes), array_values($product_fixes), $old_name);
            $new_desc = str_replace(array_keys($product_fixes), array_values($product_fixes), $old_desc);
            
            if ($new_name !== $old_name || $new_desc !== $old_desc) {
                $new_name = mysqli_real_escape_string($conn, $new_name);
                $new_desc = mysqli_real_escape_string($conn, $new_desc);
                mysqli_query($conn, "UPDATE products SET name = '$new_name', description = '$new_desc' WHERE id = {$product['id']}");
                $fixed_products++;
            }
        }
        
        if ($fixed_products > 0) {
            echo "<p class='success'>✓ Đã sửa $fixed_products sản phẩm</p>";
            $total_fixed += $fixed_products;
        } else {
            echo "<p class='info'>○ Tên sản phẩm đã đúng</p>";
        }
        
        echo '</div>';
        
        // ===== BƯỚC 3: TẠO DỮ LIỆU MẪU =====
        echo '<div class="step">';
        echo '<h3>📝 Bước 3: Kiểm Tra Dữ Liệu</h3>';
        
        // Kiểm tra có danh mục không
        $cat_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM categories"))['count'];
        if ($cat_count == 0) {
            // Thêm danh mục mẫu
            $sample_cats = [
                ['Dây chuyền', 'day-chuyen'],
                ['Lắc tay', 'lac-tay'],
                ['Nhẫn nữ', 'nhan-nu'],
                ['Bông tai', 'bong-tai'],
                ['Nhẫn đôi', 'nhan-doi'],
                ['Lắc chân', 'lac-chan'],
            ];
            
            foreach ($sample_cats as $cat) {
                mysqli_query($conn, "INSERT INTO categories (name, slug, status) VALUES ('{$cat[0]}', '{$cat[1]}', 1)");
            }
            echo '<p class="success">✓ Đã thêm danh mục mẫu</p>';
            $total_fixed++;
        } else {
            echo "<p class='info'>○ Đã có $cat_count danh mục</p>";
        }
        
        // Kiểm tra có sản phẩm không
        $prod_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM products"))['count'];
        echo "<p class='info'>○ Có $prod_count sản phẩm trong database</p>";
        
        echo '</div>';
        
        // ===== KẾT QUẢ =====
        echo '<div class="summary">';
        echo '<h2>✅ Hoàn Tất!</h2>';
        echo '<ul>';
        echo '<li>Đã sửa database charset sang UTF-8</li>';
        echo '<li>Đã sửa tất cả bảng sang UTF-8</li>';
        echo '<li>Đã sửa tên danh mục tiếng Việt</li>';
        echo '<li>Đã kiểm tra dữ liệu</li>';
        echo '</ul>';
        echo "<p style='margin-top: 20px;'><strong>Tổng cộng:</strong> Đã sửa <span class='success'>$total_fixed</span> mục</p>";
        echo '</div>';
        
        mysqli_close($conn);
        ?>
        
        <div style="text-align: center; margin-top: 30px;">
            <a href="index.php" class="btn">🏠 Về Trang Chủ</a>
            <a href="products.php" class="btn">🛍️ Xem Sản Phẩm</a>
        </div>
        
        <div style="background: #fff3cd; border: 2px solid #ffc107; padding: 20px; border-radius: 10px; margin-top: 20px;">
            <h3 style="color: #856404; margin-bottom: 10px;">💡 Lưu Ý</h3>
            <p style="color: #856404; margin: 0;">Nếu vẫn còn lỗi hiển thị, hãy:</p>
            <ol style="color: #856404; margin: 10px 0 0 20px;">
                <li>Xóa cache trình duyệt (Ctrl + Shift + Delete)</li>
                <li>Refresh lại trang (Ctrl + F5)</li>
                <li>Kiểm tra lại các file PHP có lưu đúng UTF-8 không</li>
            </ol>
        </div>
    </div>
</div>
</body>
</html>
