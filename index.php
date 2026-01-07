<?php
// File trang chủ website
require_once 'includes/config.php'; // Nạp file kết nối database

$page_title = 'Trang Chủ - Trang Sức Bạc Cao Cấp'; // Tiêu đề trang
include 'includes/header.php'; // Nạp phần header

// Bước 1: Lấy danh sách banner quảng cáo (tối đa 5 banner)
$sql_banner = "SELECT * FROM banners WHERE status = 1 ORDER BY position LIMIT 5";
$ds_banner = mysqli_query($conn, $sql_banner);

// Bước 2: Lấy 8 sản phẩm mới nhất
$sql_sanpham_moi = "SELECT * FROM products WHERE status = 1 AND is_new = 1 ORDER BY created_at DESC LIMIT 8";
$ds_sanpham_moi = mysqli_query($conn, $sql_sanpham_moi);

// Bước 3: Lấy 8 sản phẩm nổi bật (được xem nhiều nhất)
$sql_sanpham_noibat = "SELECT * FROM products WHERE status = 1 AND is_featured = 1 ORDER BY views DESC LIMIT 8";
$ds_sanpham_noibat = mysqli_query($conn, $sql_sanpham_noibat);

// Bước 4: Lấy tất cả danh mục sản phẩm
$sql_danhmuc = "SELECT * FROM categories WHERE status = 1 ORDER BY name";
$ds_danhmuc = mysqli_query($conn, $sql_danhmuc);

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

// Giữ biến cũ để tương thích
$banner_query = $sql_banner;
$banners = $ds_banner;
$new_products_query = $sql_sanpham_moi;
$new_products = $ds_sanpham_moi;
$featured_products_query = $sql_sanpham_noibat;
$featured_products = $ds_sanpham_noibat;
$categories_query = $sql_danhmuc;
$categories = $ds_danhmuc;
?>

<div class="container mx-auto px-4">
    <!-- Banner Slider -->
    <div class="relative h-[500px] rounded-3xl overflow-hidden shadow-2xl mb-16 group">
        <?php 
        $first = true;
        $bannerCount = 0;
        mysqli_data_seek($banners, 0); // Reset pointer
        while($banner = mysqli_fetch_assoc($banners)): 
            $bannerCount++;
        ?>
        <div class="absolute inset-0 transition-opacity duration-700 <?php echo $first ? 'opacity-100' : 'opacity-0'; ?>" id="banner<?php echo $bannerCount; ?>">
            <img src="<?php echo BASE_URL; ?>assets/images/banners/<?php echo $banner['image']; ?>" 
                 alt="<?php echo htmlspecialchars($banner['title']); ?>"
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent flex items-end">
                <div class="p-12 text-white">
                    <h2 class="text-5xl font-bold mb-4 animate-fadeInUp"><?php echo htmlspecialchars($banner['title']); ?></h2>
                    <?php if($banner['link']): ?>
                    <a href="<?php echo $banner['link']; ?>" class="inline-block px-8 py-3 bg-gradient-to-r from-gold-600 to-gold-400 rounded-full hover:shadow-2xl transition-all duration-300 hover:scale-105">
                        Khám phá ngay
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php 
        $first = false;
        endwhile; 
        ?>
        <!-- Banner indicators -->
        <?php if($bannerCount > 1): ?>
        <div class="absolute bottom-6 left-1/2 transform -translate-x-1/2 flex space-x-2 z-10">
            <?php for($i = 1; $i <= $bannerCount; $i++): ?>
            <button class="w-3 h-3 rounded-full bg-white/50 hover:bg-white transition" onclick="showBanner(<?php echo $i; ?>)"></button>
            <?php endfor; ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Categories Section -->
    <section class="mb-20 animate-fadeInUp">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Danh Mục Sản Phẩm</h2>
            <div class="w-24 h-1 bg-gradient-to-r from-gold-600 to-gold-400 mx-auto"></div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
            <?php mysqli_data_seek($categories, 0); while($category = mysqli_fetch_assoc($categories)): ?>
            <a href="category.php?slug=<?php echo $category['slug']; ?>" 
               class="group bg-white rounded-2xl p-8 text-center hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 border border-gray-100">
                <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-gold-100 to-gold-50 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-gem text-3xl text-gold-600"></i>
                </div>
                <h3 class="text-gray-900 font-semibold group-hover:text-gold-600 transition"><?php echo $category['name']; ?></h3>
            </a>
            <?php endwhile; ?>
        </div>
    </section>

    <!-- New Products Section -->
    <section class="mb-20">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-900 mb-2">Sản Phẩm Mới</h2>
            <p class="text-gray-600">Những sản phẩm trang sức bạc mới nhất</p>
            <div class="w-24 h-1 bg-gradient-to-r from-gold-600 to-gold-400 mx-auto mt-4"></div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php mysqli_data_seek($new_products, 0); while($product = mysqli_fetch_assoc($new_products)): ?>
            <div class="group bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">
                <div class="relative overflow-hidden aspect-square">
                    <a href="product-detail.php?slug=<?php echo $product['slug']; ?>">
                        <img src="assets/images/products/<?php echo $product['image']; ?>" 
                             alt="<?php echo htmlspecialchars($product['name']); ?>"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    </a>
                    <?php if($product['is_new']): ?>
                    <span class="absolute top-4 left-4 px-3 py-1 bg-gradient-to-r from-green-500 to-green-400 text-white text-sm font-semibold rounded-full">
                        Mới
                    </span>
                    <?php endif; ?>
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
                        <h3 class="font-semibold text-gray-900 mb-2 hover:text-gold-600 transition line-clamp-2">
                            <?php echo $product['name']; ?>
                        </h3>
                    </a>
                    <div class="flex items-center justify-between">
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
        <div class="text-center mt-12 md:hidden">
            <a href="new-products.php" class="inline-flex items-center space-x-2 px-8 py-3 bg-gradient-to-r from-gold-600 to-gold-400 text-white rounded-full hover:shadow-lg transition-all hover:scale-105 font-semibold">
                <span>Xem Tất Cả Sản Phẩm Mới</span>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </section>

    <!-- Featured Products Section -->
    <section class="mb-20">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-900 mb-2">Sản Phẩm Nổi Bật</h2>
            <p class="text-gray-600">Những sản phẩm được yêu thích nhất</p>
            <div class="w-24 h-1 bg-gradient-to-r from-gold-600 to-gold-400 mx-auto mt-4"></div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php mysqli_data_seek($featured_products, 0); while($product = mysqli_fetch_assoc($featured_products)): ?>
            <div class="group bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">
                <div class="relative overflow-hidden aspect-square">
                    <a href="product-detail.php?slug=<?php echo $product['slug']; ?>">
                        <img src="<?php echo BASE_URL; ?>assets/images/products/<?php echo $product['image']; ?>" 
                             alt="<?php echo htmlspecialchars($product['name']); ?>"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    </a>
                    <?php if($product['is_featured']): ?>
                    <span class="absolute top-4 left-4 px-3 py-1 bg-gradient-to-r from-red-500 to-orange-400 text-white text-sm font-semibold rounded-full animate-pulse">
                        <i class="fas fa-fire mr-1"></i>Hot
                    </span>
                    <?php endif; ?>
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
                        <h3 class="font-semibold text-gray-900 mb-2 hover:text-gold-600 transition line-clamp-2">
                            <?php echo $product['name']; ?>
                        </h3>
                    </a>
                    <div class="flex items-center justify-between">
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
    </section>

    <!-- Features Section -->
    <section class="mb-20">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="bg-white rounded-2xl p-8 text-center hover:shadow-xl transition-all duration-300 hover:-translate-y-2 group">
                <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-blue-500 to-blue-400 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-shipping-fast text-3xl text-white"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Miễn Phí Vận Chuyển</h3>
                <p class="text-gray-600">Đơn hàng từ 500.000đ</p>
            </div>
            <div class="bg-white rounded-2xl p-8 text-center hover:shadow-xl transition-all duration-300 hover:-translate-y-2 group">
                <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-green-500 to-green-400 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-shield-alt text-3xl text-white"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Bảo Hành 12 Tháng</h3>
                <p class="text-gray-600">Đổi trả trong 7 ngày</p>
            </div>
            <div class="bg-white rounded-2xl p-8 text-center hover:shadow-xl transition-all duration-300 hover:-translate-y-2 group">
                <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-gold-600 to-gold-400 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-certificate text-3xl text-white"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Chất Lượng Đảm Bảo</h3>
                <p class="text-gray-600">Bạc thật 100%</p>
            </div>
            <div class="bg-white rounded-2xl p-8 text-center hover:shadow-xl transition-all duration-300 hover:-translate-y-2 group">
                <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-purple-500 to-purple-400 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-headset text-3xl text-white"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Hỗ Trợ 24/7</h3>
                <p class="text-gray-600">Tư vấn nhiệt tình</p>
            </div>
        </div>
    </section>
</div>

<script>
// Banner slider auto-play
let currentBanner = 1;
const totalBanners = <?php echo $bannerCount > 0 ? $bannerCount : 1; ?>;

function showBanner(n) {
    for(let i = 1; i <= totalBanners; i++) {
        const banner = document.getElementById('banner' + i);
        if(banner) {
            banner.style.opacity = (i === n) ? '1' : '0';
        }
    }
    currentBanner = n;
}

if(totalBanners > 1) {
    setInterval(() => {
        currentBanner = (currentBanner % totalBanners) + 1;
        showBanner(currentBanner);
    }, 5000);
}
</script>

<?php include 'includes/footer.php'; ?>
