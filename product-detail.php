<?php
// File hiển thị chi tiết sản phẩm
require_once 'includes/config.php'; // Nạp file kết nối database

// Bước 1: Lấy đường dẫn sản phẩm từ URL
$duongdan = isset($_GET['slug']) ? mysqli_real_escape_string($conn, $_GET['slug']) : '';

// Bước 2: Truy vấn lấy thông tin sản phẩm kèm theo danh mục
$sql = "SELECT p.*, c.name as category_name, c.slug as category_slug 
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id 
        WHERE p.slug = '$duongdan' AND p.status = 1";
$dulieu = mysqli_query($conn, $sql);

// Bước 3: Nếu không tìm thấy sản phẩm thì quay về trang chủ
if (mysqli_num_rows($dulieu) == 0) {
    header('Location: index.php');
    exit;
}

// Lưu thông tin sản phẩm
$sanpham = mysqli_fetch_assoc($dulieu);
$page_title = $sanpham['name'];

// Giữ biến cũ để tương thích
$slug = $duongdan;
$query = $sql;
$result = $dulieu;
$product = $sanpham;

// Update views
mysqli_query($conn, "UPDATE products SET views = views + 1 WHERE id = {$product['id']}");

// Get related products
$related_query = "SELECT * FROM products 
                 WHERE category_id = {$product['category_id']} 
                 AND id != {$product['id']} 
                 AND status = 1 
                 ORDER BY RAND() LIMIT 4";
$related_products = mysqli_query($conn, $related_query);

include 'includes/header.php';
?>

<div class="container">
    <!-- Breadcrumb -->
    <div style="padding: 20px 0; color: #666;">
        <a href="index.php" style="color: #d4af37;">Trang chủ</a> / 
        <a href="category.php?slug=<?php echo $product['category_slug']; ?>" style="color: #d4af37;">
            <?php echo $product['category_name']; ?>
        </a> / 
        <span><?php echo $product['name']; ?></span>
    </div>

    <!-- Product Detail -->
    <div style="background: #fff; padding: 40px; border-radius: 10px; margin-bottom: 40px;">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 50px;">
            <!-- Image -->
            <div>
                <img src="<?php echo BASE_URL; ?>assets/images/products/<?php echo $product['image']; ?>" 
                     alt="<?php echo htmlspecialchars($product['name']); ?>"
                     style="width: 100%; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
            </div>

            <!-- Info -->
            <div>
                <h1 style="color: #1a1a1a; margin-bottom: 15px; font-size: 32px;">
                    <?php echo $product['name']; ?>
                </h1>

                <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 20px; padding-bottom: 20px; border-bottom: 2px solid #f0f0f0;">
                    <?php if($product['is_new']): ?>
                    <span class="badge badge-success" style="font-size: 14px; padding: 6px 15px;">Sản phẩm mới</span>
                    <?php endif; ?>
                    <?php if($product['is_featured']): ?>
                    <span class="badge badge-warning" style="font-size: 14px; padding: 6px 15px;">Nổi bật</span>
                    <?php endif; ?>
                    <span style="color: #666;">
                        <i class="fas fa-eye"></i> <?php echo $product['views']; ?> lượt xem
                    </span>
                </div>

                <div style="margin-bottom: 30px;">
                    <?php if($product['sale_price'] && $product['sale_price'] < $product['price']): ?>
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 10px;">
                        <span style="font-size: 36px; font-weight: 700; color: #d4af37;">
                            <?php echo number_format($product['sale_price']); ?>đ
                        </span>
                        <span style="font-size: 24px; color: #999; text-decoration: line-through;">
                            <?php echo number_format($product['price']); ?>đ
                        </span>
                        <span class="badge badge-danger" style="font-size: 14px; padding: 6px 15px;">
                            -<?php echo round((($product['price'] - $product['sale_price']) / $product['price']) * 100); ?>%
                        </span>
                    </div>
                    <?php else: ?>
                    <span style="font-size: 36px; font-weight: 700; color: #d4af37;">
                        <?php echo number_format($product['price']); ?>đ
                    </span>
                    <?php endif; ?>
                </div>

                <div style="background: #f8f8f8; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <?php if($product['material']): ?>
                        <div>
                            <strong style="color: #666;">Chất liệu:</strong><br>
                            <span style="color: #1a1a1a;"><?php echo $product['material']; ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if($product['weight']): ?>
                        <div>
                            <strong style="color: #666;">Trọng lượng:</strong><br>
                            <span style="color: #1a1a1a;"><?php echo $product['weight']; ?>g</span>
                        </div>
                        <?php endif; ?>
                        <div>
                            <strong style="color: #666;">Tình trạng:</strong><br>
                            <?php if($product['quantity'] > 0): ?>
                            <span style="color: #28a745; font-weight: 600;">
                                <i class="fas fa-check-circle"></i> Còn hàng (<?php echo $product['quantity']; ?>)
                            </span>
                            <?php else: ?>
                            <span style="color: #dc3545; font-weight: 600;">
                                <i class="fas fa-times-circle"></i> Hết hàng
                            </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <?php if($product['description']): ?>
                <div style="margin-bottom: 30px;">
                    <h3 style="color: #1a1a1a; margin-bottom: 15px;">Mô tả sản phẩm</h3>
                    <p style="color: #666; line-height: 1.8;">
                        <?php echo nl2br($product['description']); ?>
                    </p>
                </div>
                <?php endif; ?>

                <div style="display: flex; gap: 15px; margin-bottom: 20px;">
                    <?php if($product['quantity'] > 0): ?>
                    <button onclick="addToCart(<?php echo $product['id']; ?>)" class="btn btn-primary" 
                            style="flex: 1; padding: 15px; font-size: 18px;">
                        <i class="fas fa-shopping-cart"></i> Thêm Vào Giỏ Hàng
                    </button>
                    <?php else: ?>
                    <button disabled class="btn btn-secondary" style="flex: 1; padding: 15px; font-size: 18px;">
                        <i class="fas fa-times"></i> Hết Hàng
                    </button>
                    <?php endif; ?>
                    <button class="btn btn-secondary" style="padding: 15px 25px; font-size: 18px;">
                        <i class="fas fa-heart"></i>
                    </button>
                </div>

                <div style="padding: 20px; background: #fff3cd; border-radius: 8px; border-left: 4px solid #d4af37;">
                    <div style="margin-bottom: 10px;">
                        <i class="fas fa-shipping-fast" style="color: #d4af37;"></i>
                        <strong> Miễn phí vận chuyển</strong> cho đơn hàng từ 500,000đ
                    </div>
                    <div style="margin-bottom: 10px;">
                        <i class="fas fa-shield-alt" style="color: #d4af37;"></i>
                        <strong> Bảo hành 12 tháng</strong> - Đổi trả trong 7 ngày
                    </div>
                    <div>
                        <i class="fas fa-certificate" style="color: #d4af37;"></i>
                        <strong> Cam kết chính hãng</strong> - Bạc thật 100%
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    <?php if($related_products && mysqli_num_rows($related_products) > 0): ?>
    <div class="section-title" style="margin-top: 50px; margin-bottom: 30px;">
        <h2 style="font-size: 28px; font-weight: 700; color: #1a1a1a; text-align: center; position: relative; padding-bottom: 15px;">
            Sản Phẩm Liên Quan
            <span style="position: absolute; bottom: 0; left: 50%; transform: translateX(-50%); width: 80px; height: 3px; background: linear-gradient(90deg, #d4af37, #f4d03f);"></span>
        </h2>
    </div>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 25px; margin-bottom: 50px;">
        <?php while($related = mysqli_fetch_assoc($related_products)): ?>
        <div class="product-card" style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08); transition: all 0.3s ease;">
            <div style="position: relative; padding-top: 100%; overflow: hidden;">
                <a href="product-detail.php?slug=<?php echo $related['slug']; ?>">
                    <img src="<?php echo BASE_URL; ?>assets/images/products/<?php echo $related['image']; ?>" 
                         alt="<?php echo htmlspecialchars($related['name']); ?>"
                         style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;">
                </a>
            </div>
            <div style="padding: 15px;">
                <a href="product-detail.php?slug=<?php echo $related['slug']; ?>" style="text-decoration: none;">
                    <h3 style="font-size: 15px; color: #1a1a1a; margin: 0 0 10px 0; height: 40px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; line-height: 1.4;">
                        <?php echo $related['name']; ?>
                    </h3>
                </a>
                <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                    <?php if($related['sale_price'] && $related['sale_price'] < $related['price']): ?>
                    <span style="font-size: 18px; font-weight: 700; color: #d4af37;">
                        <?php echo number_format($related['sale_price']); ?>đ
                    </span>
                    <span style="font-size: 14px; color: #999; text-decoration: line-through;">
                        <?php echo number_format($related['price']); ?>đ
                    </span>
                    <?php else: ?>
                    <span style="font-size: 18px; font-weight: 700; color: #d4af37;">
                        <?php echo number_format($related['price']); ?>đ
                    </span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
    <?php endif; ?>
    
    <style>
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    }
    .product-card:hover img {
        transform: scale(1.08);
    }
    </style>
</div>

<?php include 'includes/footer.php'; ?>
