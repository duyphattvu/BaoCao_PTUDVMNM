<?php
// File hiển thị giỏ hàng
require_once 'includes/config.php';
require_once 'includes/shipping-config.php';

$page_title = 'Giỏ Hàng';
include 'includes/header.php';

// Lấy giỏ hàng từ session
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total = 0;
?>

<!-- Breadcrumb -->
<div class="bg-gradient-to-r from-amber-50 to-yellow-50 py-6">
    <div class="container mx-auto px-4">
        <div class="flex items-center text-sm text-gray-600">
            <a href="index.php" class="hover:text-amber-600 transition-colors">
                <i class="fas fa-home"></i> Trang chủ
            </a>
            <i class="fas fa-chevron-right mx-3 text-xs"></i>
            <span class="text-amber-600 font-medium">Giỏ hàng</span>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 py-8 lg:py-12">
    <!-- Page Title -->
    <div class="text-center mb-8">
        <h1 class="text-3xl lg:text-4xl font-bold text-gray-800 mb-2">
            <i class="fas fa-shopping-cart text-amber-600"></i> Giỏ Hàng Của Bạn
        </h1>
        <div class="w-24 h-1 bg-gradient-to-r from-amber-400 to-yellow-500 mx-auto rounded-full"></div>
    </div>

    <?php if (empty($cart)): ?>
    <!-- Empty Cart -->
    <div class="max-w-md mx-auto">
        <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
            <div class="w-32 h-32 bg-gradient-to-br from-amber-100 to-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-shopping-cart text-6xl text-amber-400"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-800 mb-3">Giỏ hàng trống</h3>
            <p class="text-gray-500 mb-8">Hãy thêm sản phẩm yêu thích vào giỏ hàng của bạn!</p>
            <a href="index.php" class="inline-flex items-center gap-2 bg-gradient-to-r from-amber-500 to-yellow-500 text-white px-8 py-3 rounded-full font-semibold hover:from-amber-600 hover:to-yellow-600 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                <i class="fas fa-arrow-left"></i>
                <span>Khám phá sản phẩm</span>
            </a>
        </div>
    </div>
    <?php else: ?>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Cart Items -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <!-- Cart Header -->
                <div class="bg-gradient-to-r from-amber-500 to-yellow-500 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center gap-2">
                        <i class="fas fa-list"></i>
                        <span>Sản phẩm đã chọn (<?php echo count($cart); ?>)</span>
                    </h2>
                </div>

                <!-- Cart Items List -->
                <div class="divide-y divide-gray-100">
                    <?php foreach ($cart as $item): 
                        $subtotal = $item['price'] * $item['quantity'];
                        $total += $subtotal;
                    ?>
                    <div class="p-6 hover:bg-amber-50 transition-colors duration-200" id="cart-item-<?php echo $item['id']; ?>">
                        <div class="flex flex-col sm:flex-row gap-6">
                            <!-- Product Image -->
                            <div class="flex-shrink-0">
                                <a href="product-detail.php?slug=<?php echo $item['slug']; ?>" class="block group">
                                    <div class="w-full sm:w-32 h-32 rounded-xl overflow-hidden bg-gray-100 shadow-md group-hover:shadow-xl transition-shadow duration-300">
                                        <img src="<?php echo BASE_URL; ?>assets/images/products/<?php echo $item['image']; ?>" 
                                             alt="<?php echo htmlspecialchars($item['name']); ?>"
                                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                    </div>
                                </a>
                            </div>

                            <!-- Product Info -->
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                                    <div class="flex-1">
                                        <a href="product-detail.php?slug=<?php echo $item['slug']; ?>" 
                                           class="text-lg font-bold text-gray-800 hover:text-amber-600 transition-colors line-clamp-2 mb-2">
                                            <?php echo htmlspecialchars($item['name']); ?>
                                        </a>
                                        
                                        <!-- Price -->
                                        <div class="flex items-baseline gap-2 mb-4">
                                            <span class="text-2xl font-bold text-amber-600">
                                                <?php echo number_format($item['price']); ?>đ
                                            </span>
                                            <span class="text-sm text-gray-400">/ sản phẩm</span>
                                        </div>

                                        <!-- Quantity Controls -->
                                        <div class="flex items-center gap-4">
                                            <span class="text-sm font-medium text-gray-600">Số lượng:</span>
                                            <div class="flex items-center bg-gray-100 rounded-lg overflow-hidden shadow-sm">
                                                <button onclick="updateCartQuantity(<?php echo $item['id']; ?>, <?php echo $item['quantity'] - 1; ?>)" 
                                                        class="w-10 h-10 flex items-center justify-center text-gray-600 hover:bg-amber-500 hover:text-white transition-colors duration-200 font-bold text-lg">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                                <input type="number" 
                                                       value="<?php echo $item['quantity']; ?>" 
                                                       class="w-16 h-10 text-center border-0 bg-white font-bold text-gray-800 focus:outline-none focus:ring-2 focus:ring-amber-500"
                                                       onchange="updateCartQuantity(<?php echo $item['id']; ?>, this.value)" 
                                                       min="1">
                                                <button onclick="updateCartQuantity(<?php echo $item['id']; ?>, <?php echo $item['quantity'] + 1; ?>)" 
                                                        class="w-10 h-10 flex items-center justify-center text-gray-600 hover:bg-amber-500 hover:text-white transition-colors duration-200 font-bold text-lg">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Subtotal & Remove -->
                                    <div class="flex sm:flex-col items-center sm:items-end justify-between sm:justify-start gap-4">
                                        <div class="text-right">
                                            <div class="text-sm text-gray-500 mb-1">Thành tiền</div>
                                            <div class="text-2xl font-bold text-amber-600">
                                                <?php echo number_format($subtotal); ?>đ
                                            </div>
                                        </div>
                                        <button onclick="removeFromCart(<?php echo $item['id']; ?>)" 
                                                class="w-10 h-10 flex items-center justify-center rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-all duration-200 shadow-sm hover:shadow-md">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Continue Shopping Button -->
            <div class="mt-6">
                <a href="products.php" class="inline-flex items-center gap-2 text-amber-600 hover:text-amber-700 font-semibold transition-colors group">
                    <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                    <span>Tiếp tục mua sắm</span>
                </a>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden sticky top-24">
                <!-- Summary Header -->
                <div class="bg-gradient-to-r from-amber-500 to-yellow-500 px-6 py-4">
                    <h3 class="text-xl font-bold text-white flex items-center gap-2">
                        <i class="fas fa-receipt"></i>
                        <span>Tóm tắt đơn hàng</span>
                    </h3>
                </div>

                <div class="p-6">
                    <!-- Price Details -->
                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between items-center text-gray-600">
                            <span class="flex items-center gap-2">
                                <i class="fas fa-box text-amber-500"></i>
                                <span>Tạm tính</span>
                            </span>
                            <span class="font-bold text-gray-800"><?php echo number_format($total); ?>đ</span>
                        </div>
                        
                        <div class="flex justify-between items-center text-gray-600">
                            <span class="flex items-center gap-2">
                                <i class="fas fa-shipping-fast text-amber-500"></i>
                                <span>Phí vận chuyển</span>
                            </span>
                            <span class="font-bold <?php echo $total >= FREE_SHIPPING_THRESHOLD ? 'text-green-600' : 'text-gray-800'; ?>">
                                <?php echo $total >= FREE_SHIPPING_THRESHOLD ? 'Miễn phí' : number_format(DEFAULT_SHIPPING_FEE) . 'đ'; ?>
                            </span>
                        </div>

                        <?php if ($total < FREE_SHIPPING_THRESHOLD): ?>
                        <div class="bg-amber-50 border border-amber-200 rounded-lg p-3">
                            <div class="flex items-start gap-2 text-sm text-amber-800">
                                <i class="fas fa-info-circle mt-0.5"></i>
                                <span>Mua thêm <strong><?php echo number_format(FREE_SHIPPING_THRESHOLD - $total); ?>đ</strong> để được miễn phí vận chuyển!</span>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Total -->
                    <div class="border-t-2 border-gray-200 pt-4 mb-6">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold text-gray-800">Tổng cộng</span>
                            <span class="text-3xl font-bold text-amber-600">
                                <?php 
                                $shipping = $total >= FREE_SHIPPING_THRESHOLD ? 0 : DEFAULT_SHIPPING_FEE;
                                echo number_format($total + $shipping); 
                                ?>đ
                            </span>
                        </div>
                    </div>

                    <!-- Checkout Button -->
                    <a href="checkout.php" class="block w-full bg-gradient-to-r from-amber-500 to-yellow-500 text-white text-center py-4 rounded-xl font-bold text-lg hover:from-amber-600 hover:to-yellow-600 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 mb-4">
                        <i class="fas fa-credit-card mr-2"></i>
                        Thanh toán ngay
                    </a>

                    <!-- Security Info -->
                    <div class="space-y-3">
                        <div class="flex items-center gap-3 text-sm text-gray-600">
                            <i class="fas fa-shield-alt text-green-500"></i>
                            <span>Thanh toán an toàn & bảo mật</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm text-gray-600">
                            <i class="fas fa-undo text-blue-500"></i>
                            <span>Đổi trả miễn phí trong 7 ngày</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm text-gray-600">
                            <i class="fas fa-headset text-purple-500"></i>
                            <span>Hỗ trợ 24/7</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php endif; ?>
</div>

<script>
function updateCartQuantity(productId, quantity) {
    quantity = parseInt(quantity);
    if (quantity < 1) {
        if (!confirm('Bạn có muốn xóa sản phẩm này khỏi giỏ hàng?')) {
            return;
        }
    }
    
    fetch('cart-actions.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=update&product_id=${productId}&quantity=${quantity}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Có lỗi xảy ra!');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi cập nhật giỏ hàng!');
    });
}

function removeFromCart(productId) {
    if (!confirm('Bạn có chắc muốn xóa sản phẩm này?')) {
        return;
    }
    
    fetch('cart-actions.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=remove&product_id=${productId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Có lỗi xảy ra!');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi xóa sản phẩm!');
    });
}
</script>

<?php include 'includes/footer.php'; ?>
