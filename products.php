<?php
// File hiển thị tất cả sản phẩm với giao diện Tailwind CSS
require_once 'includes/config.php';

$page_title = 'Tất Cả Sản Phẩm';

// Lấy các tham số lọc
$min_price = isset($_GET['min_price']) ? (int)$_GET['min_price'] : 0;
$max_price = isset($_GET['max_price']) ? (int)$_GET['max_price'] : 10000000;
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
$category_id = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$limit = 12;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Xây dựng WHERE
$where = "status = 1";
if ($category_id > 0) $where .= " AND category_id = $category_id";
if ($min_price > 0) $where .= " AND (sale_price >= $min_price OR (sale_price IS NULL AND price >= $min_price))";
if ($max_price < 10000000) $where .= " AND (sale_price <= $max_price OR (sale_price IS NULL AND price <= $max_price))";

// Sắp xếp
switch($sort) {
    case 'price_asc':
        $order = "COALESCE(sale_price, price) ASC";
        break;
    case 'price_desc':
        $order = "COALESCE(sale_price, price) DESC";
        break;
    case 'name_asc':
        $order = "name ASC";
        break;
    case 'name_desc':
        $order = "name DESC";
        break;
    case 'popular':
        $order = "views DESC";
        break;
    default:
        $order = "created_at DESC";
        break;
}

$total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM products WHERE $where"))['count'];
$total_pages = ceil($total / $limit);
$products = mysqli_query($conn, "SELECT * FROM products WHERE $where ORDER BY $order LIMIT $limit OFFSET $offset");
$categories = mysqli_query($conn, "SELECT * FROM categories WHERE status = 1 ORDER BY name");

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

<div class="bg-gradient-to-br from-gold-50 via-white to-gold-50 py-12">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12 animate-fadeInUp">
            <h1 class="text-5xl font-bold text-gray-900 mb-4">Tất Cả Sản Phẩm</h1>
            <p class="text-gray-600 text-lg">Khám phá bộ sưu tập trang sức bạc cao cấp</p>
            <div class="w-24 h-1 bg-gradient-to-r from-gold-600 to-gold-400 mx-auto mt-4"></div>
            <p class="text-gold-600 font-semibold mt-4">Tìm thấy <?php echo $total; ?> sản phẩm</p>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 mb-12">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                <div>
                    <label class="block mb-2 font-semibold text-gray-700"><i class="fas fa-list mr-2 text-gold-600"></i>Danh mục</label>
                    <select name="category" class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-gold-600 focus:outline-none transition">
                        <option value="0">Tất cả danh mục</option>
                        <?php while($cat = mysqli_fetch_assoc($categories)): ?>
                        <option value="<?php echo $cat['id']; ?>" <?php echo $category_id == $cat['id'] ? 'selected' : ''; ?>><?php echo $cat['name']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div>
                    <label class="block mb-2 font-semibold text-gray-700"><i class="fas fa-tag mr-2 text-gold-600"></i>Giá từ</label>
                    <input type="number" name="min_price" value="<?php echo $min_price > 0 ? $min_price : ''; ?>" placeholder="0đ" class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-gold-600 focus:outline-none transition">
                </div>
                <div>
                    <label class="block mb-2 font-semibold text-gray-700"><i class="fas fa-tag mr-2 text-gold-600"></i>Giá đến</label>
                    <input type="number" name="max_price" value="<?php echo $max_price < 10000000 ? $max_price : ''; ?>" placeholder="10,000,000đ" class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-gold-600 focus:outline-none transition">
                </div>
                <div>
                    <label class="block mb-2 font-semibold text-gray-700"><i class="fas fa-sort mr-2 text-gold-600"></i>Sắp xếp</label>
                    <select name="sort" class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-gold-600 focus:outline-none transition">
                        <option value="newest" <?php echo $sort == 'newest' ? 'selected' : ''; ?>>Mới nhất</option>
                        <option value="popular" <?php echo $sort == 'popular' ? 'selected' : ''; ?>>Phổ biến</option>
                        <option value="price_asc" <?php echo $sort == 'price_asc' ? 'selected' : ''; ?>>Giá tăng dần</option>
                        <option value="price_desc" <?php echo $sort == 'price_desc' ? 'selected' : ''; ?>>Giá giảm dần</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-gold-600 to-gold-400 text-white rounded-lg hover:shadow-lg transition-all font-semibold hover:scale-105">
                        <i class="fas fa-filter mr-2"></i>Lọc
                    </button>
                    <a href="products.php" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-semibold">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </form>
        </div>

        <?php if (mysqli_num_rows($products) > 0): ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
            <?php while($product = mysqli_fetch_assoc($products)): ?>
            <div class="group bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">
                <div class="relative overflow-hidden aspect-square">
                    <a href="product-detail.php?slug=<?php echo $product['slug']; ?>">
                        <img src="<?php echo BASE_URL; ?>assets/images/products/<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    </a>
                    <?php if($product['is_new']): ?><span class="absolute top-4 left-4 px-3 py-1 bg-gradient-to-r from-green-500 to-green-400 text-white text-sm font-semibold rounded-full">Mới</span><?php endif; ?>
                    <?php if($product['sale_price'] && $product['sale_price'] < $product['price']): ?><span class="absolute top-4 right-4 px-3 py-1 bg-gradient-to-r from-red-500 to-red-400 text-white text-sm font-semibold rounded-full">-<?php echo round((($product['price'] - $product['sale_price']) / $product['price']) * 100); ?>%</span><?php endif; ?>
                    <div class="absolute inset-x-0 bottom-0 flex gap-2 p-4 transform translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                        <button onclick="addToCart(<?php echo $product['id']; ?>)" class="flex-1 bg-gradient-to-r from-gold-600 to-gold-400 text-white py-2 rounded-lg hover:shadow-lg transition font-semibold"><i class="fas fa-shopping-cart mr-2"></i>Thêm giỏ</button>
                        <a href="product-detail.php?slug=<?php echo $product['slug']; ?>" class="w-12 h-12 bg-white text-gray-700 rounded-lg hover:bg-gold-600 hover:text-white transition flex items-center justify-center"><i class="fas fa-eye"></i></a>
                    </div>
                </div>
                <div class="p-6">
                    <a href="product-detail.php?slug=<?php echo $product['slug']; ?>" class="block">
                        <h3 class="font-semibold text-gray-900 mb-2 hover:text-gold-600 transition line-clamp-2 h-12"><?php echo $product['name']; ?></h3>
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
            <?php 
            $query_params = $_GET; unset($query_params['page']);
            $query_string = http_build_query($query_params);
            $query_string = $query_string ? '&' . $query_string : '';
            if ($page > 1): ?>
            <a href="?page=<?php echo $page - 1; ?><?php echo $query_string; ?>" class="px-4 py-2 bg-white rounded-lg border-2 border-gray-200 hover:border-gold-600 hover:text-gold-600 transition font-semibold"><i class="fas fa-chevron-left"></i></a>
            <?php endif; for ($i = 1; $i <= $total_pages; $i++): if ($i == 1 || $i == $total_pages || ($i >= $page - 2 && $i <= $page + 2)): ?>
            <a href="?page=<?php echo $i; ?><?php echo $query_string; ?>" class="px-4 py-2 rounded-lg border-2 font-semibold transition <?php echo $page == $i ? 'bg-gradient-to-r from-gold-600 to-gold-400 text-white border-gold-600' : 'bg-white border-gray-200 hover:border-gold-600 hover:text-gold-600'; ?>"><?php echo $i; ?></a>
            <?php elseif ($i == $page - 3 || $i == $page + 3): echo '<span class="px-2 text-gray-400">...</span>'; endif; endfor; if ($page < $total_pages): ?>
            <a href="?page=<?php echo $page + 1; ?><?php echo $query_string; ?>" class="px-4 py-2 bg-white rounded-lg border-2 border-gray-200 hover:border-gold-600 hover:text-gold-600 transition font-semibold"><i class="fas fa-chevron-right"></i></a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        <?php else: ?>
        <div class="text-center py-20 bg-white rounded-2xl shadow-lg">
            <div class="w-32 h-32 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center"><i class="fas fa-box-open text-6xl text-gray-400"></i></div>
            <h3 class="text-2xl font-bold text-gray-900 mb-4">Không tìm thấy sản phẩm nào</h3>
            <a href="products.php" class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-gold-600 to-gold-400 text-white rounded-full hover:shadow-lg transition-all hover:scale-105 font-semibold"><i class="fas fa-redo mr-2"></i>Xem tất cả</a>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
