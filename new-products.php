<?php
require_once 'includes/config.php';
$page_title = 'Sản Phẩm Mới Nhất';

$limit = 12;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$count_result = mysqli_query($conn, "SELECT COUNT(*) as count FROM products WHERE status = 1 AND is_new = 1");
$total = 0;
if ($count_result) {
    $count_data = mysqli_fetch_assoc($count_result);
    $total = $count_data['count'];
}
$total_pages = ceil($total / $limit);
$products = mysqli_query($conn, "SELECT * FROM products WHERE status = 1 AND is_new = 1 ORDER BY created_at DESC LIMIT $limit OFFSET $offset");

// Hàm tính số lượng đã bán
function getSoldCount($conn, $product_id) {
    $sql = "SELECT SUM(od.quantity) as total_sold 
            FROM order_details od 
            INNER JOIN orders o ON od.order_id = o.id 
            WHERE od.product_id = $product_id 
            AND o.order_status IN ('confirmed', 'shipping', 'completed')";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);
        return $data['total_sold'] ? (int)$data['total_sold'] : 0;
    }
    return 0;
}

include 'includes/header.php';
?>

<div class="bg-gradient-to-br from-green-50 via-white to-green-50 py-12">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12 animate-fadeInUp">
            <div class="inline-block px-6 py-2 bg-gradient-to-r from-green-500 to-green-400 text-white rounded-full mb-4 font-semibold">
                <i class="fas fa-star mr-2"></i>SẢN PHẨM MỚI NHẤT
            </div>
            <h1 class="text-5xl font-bold text-gray-900 mb-4">Bộ Sưu Tập Mới</h1>
            <p class="text-gray-600 text-lg">Cập nhật liên tục các sản phẩm trang sức bạc mới nhất</p>
            <div class="w-24 h-1 bg-gradient-to-r from-green-500 to-green-400 mx-auto mt-4"></div>
            <p class="text-green-600 font-semibold mt-4">Tìm thấy <?php echo $total; ?> sản phẩm</p>
        </div>

        <?php if (mysqli_num_rows($products) > 0): ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
            <?php while($product = mysqli_fetch_assoc($products)): ?>
            <div class="group bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 animate-fadeInUp">
                <div class="relative overflow-hidden aspect-square">
                    <a href="product-detail.php?slug=<?php echo $product['slug']; ?>">
                        <img src="<?php echo BASE_URL; ?>assets/images/products/<?php echo $product['image']; ?>" 
                             alt="<?php echo htmlspecialchars($product['name']); ?>"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    </a>
                    <span class="absolute top-4 left-4 px-4 py-2 bg-gradient-to-r from-green-500 to-green-400 text-white text-sm font-bold rounded-full shadow-lg animate-pulse">
                        <i class="fas fa-star mr-1"></i>MỚI
                    </span>
                    <?php if($product['sale_price'] && $product['sale_price'] < $product['price']): ?>
                    <span class="absolute top-4 right-4 px-3 py-1 bg-gradient-to-r from-red-500 to-red-400 text-white text-sm font-semibold rounded-full">
                        -<?php echo round((($product['price'] - $product['sale_price']) / $product['price']) * 100); ?>%
                    </span>
                    <?php endif; ?>
                    <div class="absolute inset-x-0 bottom-0 flex gap-2 p-4 transform translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                        <button onclick="addToCart(<?php echo $product['id']; ?>)" 
                                class="flex-1 bg-gradient-to-r from-gold-600 to-gold-400 text-white py-2 rounded-lg hover:shadow-lg transition font-semibold">
                            <i class="fas fa-shopping-cart mr-2"></i>Thêm giỏ
                        </button>
                        <a href="product-detail.php?slug=<?php echo $product['slug']; ?>" 
                           class="w-12 h-12 bg-white text-gray-700 rounded-lg hover:bg-gold-600 hover:text-white transition flex items-center justify-center">
                            <i class="fas fa-eye"></i>
                        </a>
                    </div>
                </div>
                <div class="p-6">
                    <a href="product-detail.php?slug=<?php echo $product['slug']; ?>" class="block">
                        <h3 class="font-semibold text-gray-900 mb-2 hover:text-gold-600 transition line-clamp-2 h-12">
                            <?php echo $product['name']; ?>
                        </h3>
                    </a>
                    <div class="flex items-center justify-between mt-4">
                        <?php if($product['sale_price'] && $product['sale_price'] < $product['price']): ?>
                        <div class="flex flex-col">
                            <span class="text-xl font-bold text-gold-600"><?php echo number_format($product['sale_price']); ?>đ</span>
                            <span class="text-sm text-gray-400 line-through"><?php echo number_format($product['price']); ?>đ</span>
                        </div>
                        <?php else: ?>
                        <span class="text-xl font-bold text-gold-600"><?php echo number_format($product['price']); ?>đ</span>
                        <?php endif; ?>
                        <div class="flex items-center space-x-1 text-yellow-400">
                            <i class="fas fa-star text-xs"></i>
                            <i class="fas fa-star text-xs"></i>
                            <i class="fas fa-star text-xs"></i>
                            <i class="fas fa-star text-xs"></i>
                            <i class="fas fa-star text-xs"></i>
                        </div>
                    </div>
                    <?php $sold = getSoldCount($conn, $product['id']); ?>
                    <div class="mt-3 pt-3 border-t border-gray-100 flex items-center justify-between text-sm">
                        <span class="text-gray-500 flex items-center">
                            <i class="fas fa-fire text-orange-500 mr-2"></i>
                            Đã bán: <strong class="text-gray-900 ml-1"><?php echo number_format($sold); ?></strong>
                        </span>
                        <span class="text-gray-400 flex items-center">
                            <i class="fas fa-eye mr-1"></i>
                            <?php echo number_format($product['views']); ?>
                        </span>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>

        <?php if ($total_pages > 1): ?>
        <div class="flex justify-center items-center space-x-2">
            <?php if ($page > 1): ?>
            <a href="?page=<?php echo $page - 1; ?>" class="px-4 py-2 bg-white rounded-lg border-2 border-gray-200 hover:border-green-500 hover:text-green-500 transition font-semibold">
                <i class="fas fa-chevron-left"></i>
            </a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $total_pages; $i++): 
                if ($i == 1 || $i == $total_pages || ($i >= $page - 2 && $i <= $page + 2)):
            ?>
            <a href="?page=<?php echo $i; ?>" class="px-4 py-2 rounded-lg border-2 font-semibold transition <?php echo $page == $i ? 'bg-gradient-to-r from-green-500 to-green-400 text-white border-green-500' : 'bg-white border-gray-200 hover:border-green-500 hover:text-green-500'; ?>">
                <?php echo $i; ?>
            </a>
            <?php elseif ($i == $page - 3 || $i == $page + 3): echo '<span class="px-2 text-gray-400">...</span>'; endif; endfor; ?>
            <?php if ($page < $total_pages): ?>
            <a href="?page=<?php echo $page + 1; ?>" class="px-4 py-2 bg-white rounded-lg border-2 border-gray-200 hover:border-green-500 hover:text-green-500 transition font-semibold">
                <i class="fas fa-chevron-right"></i>
            </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        <?php else: ?>
        <div class="text-center py-20 bg-white rounded-2xl shadow-lg">
            <div class="w-32 h-32 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                <i class="fas fa-star text-6xl text-gray-400"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-4">Chưa có sản phẩm mới</h3>
            <p class="text-gray-600 mb-8">Vui lòng quay lại sau</p>
            <a href="products.php" class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-gold-600 to-gold-400 text-white rounded-full hover:shadow-lg transition-all hover:scale-105 font-semibold">
                <i class="fas fa-tags mr-2"></i>Xem tất cả sản phẩm
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
