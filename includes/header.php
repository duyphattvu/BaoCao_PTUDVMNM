<!DOCTYPE html>
<html lang="vi" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'Trang Sức Bạc Cao Cấp'; ?></title>
    <base href="<?php echo BASE_URL; ?>">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        gold: {
                            50: '#fefce8',
                            100: '#fef9c3',
                            200: '#fef08a',
                            300: '#fde047',
                            400: '#facc15',
                            500: '#eab308',
                            600: '#d4af37',
                            700: '#a16207',
                            800: '#854d0e',
                            900: '#713f12',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        .gradient-gold {
            background: linear-gradient(135deg, #d4af37 0%, #f9d87d 100%);
        }
        .hover-scale {
            transition: transform 0.3s ease;
        }
        .hover-scale:hover {
            transform: scale(1.05);
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fadeInUp {
            animation: fadeInUp 0.6s ease-out;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Admin Back Button (if coming from admin) -->
    <?php if(isset($_GET['from_admin']) || (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin' && basename($_SERVER['PHP_SELF']) != 'login.php')): ?>
    <div class="bg-gradient-to-r from-blue-600 to-blue-500 text-white py-3 shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-shield-alt text-xl"></i>
                    <span class="font-semibold">Chế Độ Quản Trị</span>
                </div>
                <a href="<?php echo BASE_URL; ?>admin/index.php" class="flex items-center space-x-2 bg-white/20 hover:bg-white/30 px-6 py-2 rounded-lg transition-all duration-300 backdrop-blur-sm hover:scale-105 transform">
                    <i class="fas fa-arrow-left"></i>
                    <span class="font-semibold">Quay Lại Trang Quản Trị</span>
                </a>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Top Bar -->
    <div class="bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 text-white py-2 border-b border-gold-600/30">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap justify-between items-center text-sm">
                <div class="flex items-center space-x-6">
                    <a href="tel:1900xxxx" class="flex items-center space-x-2 hover:text-gold-400 transition">
                        <i class="fas fa-phone"></i>
                        <span>Hotline: 0983592506</span>
                    </a>
                    <a href="mailto:info@trangsuc.com" class="flex items-center space-x-2 hover:text-gold-400 transition">
                        <i class="fas fa-envelope"></i>
                        <span>nguyenduyphat2019@gmail.com</span>
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="profile.php" class="hover:text-gold-400 transition">
                            <i class="fas fa-user"></i> <?php echo $_SESSION['user_name']; ?>
                        </a>
                        <span class="text-gray-600">|</span>
                        <a href="purchase-history.php" class="hover:text-gold-400 transition">
                            <i class="fas fa-history"></i> Lịch sử
                        </a>
                        <span class="text-gray-600">|</span>
                        <a href="logout.php" class="hover:text-gold-400 transition">
                            <i class="fas fa-sign-out-alt"></i> Đăng xuất
                        </a>
                    <?php else: ?>
                        <a href="login.php" class="hover:text-gold-400 transition">
                            <i class="fas fa-sign-in-alt"></i> Đăng nhập
                        </a>
                        <span class="text-gray-600">|</span>
                        <a href="register.php" class="hover:text-gold-400 transition">
                            <i class="fas fa-user-plus"></i> Đăng ký
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Header -->
    <header class="glass-effect shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between py-4">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="<?php echo BASE_URL; ?>" class="flex items-center space-x-3 group">
                        <div class="w-12 h-12 bg-gradient-to-br from-gold-600 to-gold-400 rounded-full flex items-center justify-center group-hover:rotate-12 transition-transform duration-300">
                            <i class="fas fa-gem text-white text-2xl"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold bg-gradient-to-r from-gold-700 to-gold-500 bg-clip-text text-transparent">
                                TRANG SỨC BẠC
                            </h1>
                            <p class="text-xs text-gray-600">Luxury Silver Jewelry</p>
                        </div>
                    </a>
                </div>
                
                <!-- Search Bar -->
                <div class="hidden md:flex flex-1 max-w-2xl mx-8">
                    <form action="search.php" method="GET" class="w-full">
                        <div class="relative">
                            <input type="text" name="keyword" 
                                   placeholder="Tìm kiếm sản phẩm..." 
                                   class="w-full px-6 py-3 rounded-full border-2 border-gray-200 focus:border-gold-600 focus:outline-none transition">
                            <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 bg-gradient-to-r from-gold-600 to-gold-400 text-white px-6 py-2 rounded-full hover:shadow-lg transition">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Header Actions -->
                <div class="flex items-center space-x-3">
                    <!-- Orders Button -->
                    <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="orders.php" class="relative group hidden sm:block" title="Đơn hàng của tôi">
                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center group-hover:bg-amber-100 transition">
                            <i class="fas fa-receipt text-xl text-gray-700 group-hover:text-amber-600"></i>
                        </div>
                    </a>
                    <?php endif; ?>

                    <!-- Cart Button -->
                    <a href="cart.php" class="relative group" title="Giỏ hàng">
                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center group-hover:bg-amber-100 transition">
                            <i class="fas fa-shopping-cart text-xl text-gray-700 group-hover:text-amber-600"></i>
                        </div>
                        <span class="cart-count absolute -top-1 -right-1 w-6 h-6 bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-bold">
                            <?php 
                            $cart_total = 0;
                            if (isset($_SESSION['cart'])) {
                                foreach ($_SESSION['cart'] as $item) {
                                    $cart_total += $item['quantity'];
                                }
                            }
                            echo $cart_total;
                            ?>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Navigation -->
    <nav class="bg-white shadow-md border-t border-gray-100">
        <div class="container mx-auto px-4">
            <ul class="flex items-center justify-center space-x-1 py-0">
                <li>
                    <a href="<?php echo BASE_URL; ?>index.php" 
                       class="px-6 py-4 inline-block hover:text-gold-600 transition <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'text-gold-600 border-b-2 border-gold-600 font-semibold' : 'text-gray-700'; ?>">
                        <i class="fas fa-home mr-2"></i>Trang chủ
                    </a>
                </li>
                <li>
                    <a href="<?php echo BASE_URL; ?>products.php" 
                       class="px-6 py-4 inline-block hover:text-gold-600 transition <?php echo basename($_SERVER['PHP_SELF']) == 'products.php' ? 'text-gold-600 border-b-2 border-gold-600 font-semibold' : 'text-gray-700'; ?>">
                        <i class="fas fa-tags mr-2"></i>Sản phẩm
                    </a>
                </li>
                <li>
                    <a href="<?php echo BASE_URL; ?>new-products.php" 
                       class="px-6 py-4 inline-block hover:text-gold-600 transition <?php echo basename($_SERVER['PHP_SELF']) == 'new-products.php' ? 'text-gold-600 border-b-2 border-gold-600 font-semibold' : 'text-gray-700'; ?>">
                        <i class="fas fa-star mr-2"></i>Hàng mới
                    </a>
                </li>
                <li class="relative group">
                    <a href="#" class="px-6 py-4 inline-block text-gray-700 hover:text-gold-600 transition">
                        <i class="fas fa-list mr-2"></i>Danh mục <i class="fas fa-chevron-down ml-1 text-xs"></i>
                    </a>
                    <ul class="absolute left-0 top-full w-56 bg-white shadow-xl rounded-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 border border-gray-100 overflow-hidden">
                        <?php
                        $cat_query = "SELECT * FROM categories WHERE status = 1 ORDER BY name";
                        $cat_result = mysqli_query($conn, $cat_query);
                        while($cat = mysqli_fetch_assoc($cat_result)):
                        ?>
                        <li>
                            <a href="<?php echo BASE_URL; ?>category.php?slug=<?php echo $cat['slug']; ?>" 
                               class="block px-6 py-3 hover:bg-gold-50 hover:text-gold-600 transition">
                                <?php echo $cat['name']; ?>
                            </a>
                        </li>
                        <?php endwhile; ?>
                    </ul>
                </li>
                <li>
                    <a href="<?php echo BASE_URL; ?>news.php" 
                       class="px-6 py-4 inline-block hover:text-gold-600 transition <?php echo basename($_SERVER['PHP_SELF']) == 'news.php' ? 'text-gold-600 border-b-2 border-gold-600 font-semibold' : 'text-gray-700'; ?>">
                        <i class="fas fa-newspaper mr-2"></i>Tin tức
                    </a>
                </li>
                <li>
                    <a href="<?php echo BASE_URL; ?>contact.php" 
                       class="px-6 py-4 inline-block hover:text-gold-600 transition <?php echo basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'text-gold-600 border-b-2 border-gold-600 font-semibold' : 'text-gray-700'; ?>">
                        <i class="fas fa-phone-alt mr-2"></i>Liên hệ
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="min-h-screen">
