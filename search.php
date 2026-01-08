<?php
require_once 'includes/config.php';

// Lấy từ khóa tìm kiếm
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$keyword_safe = mysqli_real_escape_string($conn, $keyword);

$page_title = $keyword ? "Kết quả tìm kiếm: $keyword" : 'Tìm kiếm sản phẩm';

// Phân trang
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 12;
$offset = ($page - 1) * $limit;

// Đếm tổng số sản phẩm
$count_sql = "SELECT COUNT(*) as total FROM products WHERE status = 1";
if ($keyword) {
    $count_sql .= " AND (name LIKE '%$keyword_safe%' OR description LIKE '%$keyword_safe%')";
}
$count_result = mysqli_query($conn, $count_sql);
$total_products = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total_products / $limit);

// Lấy sản phẩm
$sql = "SELECT * FROM products WHERE status = 1";
if ($keyword) {
    $sql .= " AND (name LIKE '%$keyword_safe%' OR description LIKE '%$keyword_safe%')";
}
$sql .= " ORDER BY id DESC LIMIT $limit OFFSET $offset";
$products = mysqli_query($conn, $sql);

include 'includes/header.php';
?>

<div class="container mx-auto px-4 py-12">
    <!-- Search Header -->
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">
            <?php if ($keyword): ?>
                <i class="fas fa-search text-gold-600"></i> Kết quả tìm kiếm
            <?php else: ?>
                <i class="fas fa-search text-gold-600"></i> Tìm kiếm sản phẩm
            <?php endif; ?>
        </h1>
        <?php if ($keyword): ?>
            <p class="text-gray-600 text-lg">
                Tìm thấy <span class="font-bold text-gold-600"><?php echo $total_products; ?></span> sản phẩm cho từ khóa 
                <span class="font-bold">"<?php echo htmlspecialchars($keyword); ?>"</span>
            </p>
        <?php endif; ?>
    </div>

    <!-- Search Form (Mobile) -->
    <div class="md:hidden mb-8">
        <form action="search.php" method="GET">
            <div class="relative">
                <input type="text" name="keyword" value="<?php echo htmlspecialchars($keyword); ?>"
                       placeholder="Tìm kiếm sản phẩm..." 
                       class="w-full px-6 py-4 rounded-xl border-2 border-gray-200 focus:border-gold-600 focus:outline-none">
                <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 bg-gradient-to-r from-gold-600 to-gold-400 text-white px-6 py-2 rounded-lg">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
    </div>

    <?php if ($total_products > 0): ?>
        <!-- Products Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            <?php while($product = mysqli_fetch_assoc($products)): ?>
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 group">
                <div class="relative overflow-hidden aspect-square">
                    <a href="product-detail.php?slug=<?php echo $product['slug']; ?>">
                        <img src="<?php echo BASE_URL; ?>assets/images/products/<?php echo $product['image']; ?>" 
                             alt="<?php echo $product['name']; ?>"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    </a>
                    <?php if ($product['sale_price']): ?>
                    <div class="absolute top-4 right-4 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-bold">
                        -<?php echo round((($product['price'] - $product['sale_price']) / $product['price']) * 100); ?>%
                    </div>
                    <?php endif; ?>
                    <?php if ($product['quantity'] <= 0): ?>
                    <div class="absolute inset-0 bg-black/60 flex items-center justify-center">
                        <span class="bg-white text-gray-900 px-6 py-2 rounded-full font-bold">Hết hàng</span>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="p-6">
                    <a href="product-detail.php?slug=<?php echo $product['slug']; ?>" 
                       class="block hover:text-gold-600 transition">
                        <h3 class="font-semibold text-lg text-gray-900 mb-2 line-clamp-2">
                            <?php echo $product['name']; ?>
                        </h3>
                    </a>
                    
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <?php if ($product['sale_price']): ?>
                                <div class="text-gray-400 line-through text-sm">
                                    <?php echo number_format($product['price']); ?>đ
                                </div>
                                <div class="text-gold-600 font-bold text-xl">
                                    <?php echo number_format($product['sale_price']); ?>đ
                                </div>
                            <?php else: ?>
                                <div class="text-gold-600 font-bold text-xl">
                                    <?php echo number_format($product['price']); ?>đ
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <?php if ($product['quantity'] > 0): ?>
                        <button onclick="addToCart(<?php echo $product['id']; ?>)" 
                                class="w-full bg-gradient-to-r from-gold-600 to-gold-400 text-white py-3 rounded-xl hover:shadow-lg transition-all duration-300 font-semibold hover:scale-105 transform">
                            <i class="fas fa-cart-plus mr-2"></i>Thêm vào giỏ
                        </button>
                    <?php else: ?>
                        <button disabled 
                                class="w-full bg-gray-300 text-gray-500 py-3 rounded-xl cursor-not-allowed font-semibold">
                            <i class="fas fa-ban mr-2"></i>Hết hàng
                        </button>
                    <?php endif; ?>
                </div>
            </div>
            <?php endwhile; ?>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
        <div class="flex justify-center items-center space-x-2 mt-12">
            <?php if ($page > 1): ?>
                <a href="?keyword=<?php echo urlencode($keyword); ?>&page=<?php echo $page-1; ?>" 
                   class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    <i class="fas fa-chevron-left"></i>
                </a>
            <?php endif; ?>
            
            <?php for($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?keyword=<?php echo urlencode($keyword); ?>&page=<?php echo $i; ?>" 
                   class="px-4 py-2 rounded-lg transition <?php echo $i == $page ? 'bg-gold-600 text-white' : 'bg-white border border-gray-300 hover:bg-gray-50'; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
            
            <?php if ($page < $total_pages): ?>
                <a href="?keyword=<?php echo urlencode($keyword); ?>&page=<?php echo $page+1; ?>" 
                   class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    <i class="fas fa-chevron-right"></i>
                </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>

    <?php else: ?>
        <!-- No Results -->
        <div class="text-center py-20">
            <i class="fas fa-search text-gray-300 text-8xl mb-6"></i>
            <h2 class="text-3xl font-bold text-gray-800 mb-4">Không tìm thấy sản phẩm</h2>
            <p class="text-gray-600 mb-8">
                <?php if ($keyword): ?>
                    Không có sản phẩm nào phù hợp với từ khóa "<span class="font-bold"><?php echo htmlspecialchars($keyword); ?></span>"
                <?php else: ?>
                    Vui lòng nhập từ khóa để tìm kiếm
                <?php endif; ?>
            </p>
            <a href="<?php echo BASE_URL; ?>products.php" 
               class="inline-block bg-gradient-to-r from-gold-600 to-gold-400 text-white px-8 py-3 rounded-full hover:shadow-lg transition">
                <i class="fas fa-shopping-bag mr-2"></i>Xem tất cả sản phẩm
            </a>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
