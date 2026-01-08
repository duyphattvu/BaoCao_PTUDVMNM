<?php
require_once 'includes/config.php';

echo "<h2>Kiểm tra kết nối Database</h2>";

// Test connection
if ($conn) {
    echo "✓ Kết nối database thành công<br><br>";
    
    // Test products table
    $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM products");
    $row = mysqli_fetch_assoc($result);
    echo "Tổng số sản phẩm: " . $row['total'] . "<br><br>";
    
    // Get sample products
    $products = mysqli_query($conn, "SELECT id, name, slug, status FROM products LIMIT 5");
    echo "<h3>Danh sách 5 sản phẩm đầu tiên:</h3>";
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>ID</th><th>Tên</th><th>Slug</th><th>Status</th><th>Link</th></tr>";
    
    while ($p = mysqli_fetch_assoc($products)) {
        $link = "product-detail.php?slug=" . $p['slug'];
        echo "<tr>";
        echo "<td>{$p['id']}</td>";
        echo "<td>{$p['name']}</td>";
        echo "<td>{$p['slug']}</td>";
        echo "<td>{$p['status']}</td>";
        echo "<td><a href='$link' target='_blank'>Xem chi tiết</a></td>";
        echo "</tr>";
    }
    echo "</table>";
    
} else {
    echo "✗ Lỗi kết nối database<br>";
}
?>
