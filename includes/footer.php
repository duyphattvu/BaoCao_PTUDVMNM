    </main>

    <!-- Footer -->
    <footer class="bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 text-white mt-20">
        <!-- Main Footer -->
        <div class="container mx-auto px-4 py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
                <!-- Company Info -->
                <div class="space-y-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-gold-600 to-gold-400 rounded-full flex items-center justify-center">
                            <i class="fas fa-gem text-white text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold">TRANG SỨC BẠC</h3>
                    </div>
                    <p class="text-gray-400 leading-relaxed">
                        Chuyên cung cấp các sản phẩm trang sức bạc cao cấp, thiết kế tinh xảo, chất lượng đảm bảo.
                    </p>
                    <div class="flex space-x-4">
                        <a href="https://web.facebook.com/share/1DH7RiRx4a/?mibextid=wwXIfr&_rdc=1&_rdr" class="w-10 h-10 bg-gray-800 hover:bg-gold-600 rounded-full flex items-center justify-center transition-all duration-300 hover:scale-110">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://www.instagram.com/ngtan_0804/" class="w-10 h-10 bg-gray-800 hover:bg-gold-600 rounded-full flex items-center justify-center transition-all duration-300 hover:scale-110">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>

                <!-- Categories -->
                <div>
                    <h4 class="text-xl font-bold mb-6 text-gold-400">Danh Mục</h4>
                    <ul class="space-y-3">
                        <li><a href="category.php?slug=day-chuyen" class="text-gray-400 hover:text-gold-400 transition flex items-center group">
                            <i class="fas fa-chevron-right mr-2 text-xs opacity-0 group-hover:opacity-100 transition"></i>
                            Dây chuyền
                        </a></li>
                        <li><a href="category.php?slug=lac-tay" class="text-gray-400 hover:text-gold-400 transition flex items-center group">
                            <i class="fas fa-chevron-right mr-2 text-xs opacity-0 group-hover:opacity-100 transition"></i>
                            Lắc tay
                        </a></li>
                        <li><a href="category.php?slug=nhan-nu" class="text-gray-400 hover:text-gold-400 transition flex items-center group">
                            <i class="fas fa-chevron-right mr-2 text-xs opacity-0 group-hover:opacity-100 transition"></i>
                            Nhẫn nữ
                        </a></li>
                        <li><a href="category.php?slug=bong-tai" class="text-gray-400 hover:text-gold-400 transition flex items-center group">
                            <i class="fas fa-chevron-right mr-2 text-xs opacity-0 group-hover:opacity-100 transition"></i>
                            Bông tai
                        </a></li>
                        <li><a href="category.php?slug=nhan-doi" class="text-gray-400 hover:text-gold-400 transition flex items-center group">
                            <i class="fas fa-chevron-right mr-2 text-xs opacity-0 group-hover:opacity-100 transition"></i>
                            Nhẫn đôi
                        </a></li>
                        <li><a href="category.php?slug=lac-chan" class="text-gray-400 hover:text-gold-400 transition flex items-center group">
                            <i class="fas fa-chevron-right mr-2 text-xs opacity-0 group-hover:opacity-100 transition"></i>
                            Lắc chân
                        </a></li>
                    </ul>
                </div>

                <!-- Customer Support -->
                <div>
                    <h4 class="text-xl font-bold mb-6 text-gold-400">Hỗ Trợ Khách Hàng</h4>
                    <ul class="space-y-3">
                        <li><a href="<?php echo BASE_URL; ?>chinh-sach-doi-tra.php" class="text-gray-400 hover:text-gold-400 transition flex items-center group">
                            <i class="fas fa-chevron-right mr-2 text-xs opacity-0 group-hover:opacity-100 transition"></i>
                            Chính sách đổi trả
                        </a></li>
                        <li><a href="<?php echo BASE_URL; ?>chinh-sach-bao-hanh.php" class="text-gray-400 hover:text-gold-400 transition flex items-center group">
                            <i class="fas fa-chevron-right mr-2 text-xs opacity-0 group-hover:opacity-100 transition"></i>
                            Chính sách bảo hành
                        </a></li>
                        <li><a href="<?php echo BASE_URL; ?>huong-dan-mua-hang.php" class="text-gray-400 hover:text-gold-400 transition flex items-center group">
                            <i class="fas fa-chevron-right mr-2 text-xs opacity-0 group-hover:opacity-100 transition"></i>
                            Hướng dẫn mua hàng
                        </a></li>
                        <li><a href="<?php echo BASE_URL; ?>phuong-thuc-thanh-toan.php" class="text-gray-400 hover:text-gold-400 transition flex items-center group">
                            <i class="fas fa-chevron-right mr-2 text-xs opacity-0 group-hover:opacity-100 transition"></i>
                            Phương thức thanh toán
                        </a></li>
                        <li><a href="<?php echo BASE_URL; ?>contact.php" class="text-gray-400 hover:text-gold-400 transition flex items-center group">
                            <i class="fas fa-chevron-right mr-2 text-xs opacity-0 group-hover:opacity-100 transition"></i>
                            Liên hệ
                        </a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div>
                    <h4 class="text-xl font-bold mb-6 text-gold-400">Thông Tin Liên Hệ</h4>
                    <ul class="space-y-4">
                        <li class="flex items-start space-x-3 text-gray-400">
                            <i class="fas fa-map-marker-alt text-gold-400 mt-1"></i>
                            <span>Đường Nguyễn Đáng, Phường Trà Vinh, Tỉnh Vĩnh Long</span>
                        </li>
                        <li class="flex items-center space-x-3 text-gray-400">
                            <i class="fas fa-phone text-gold-400"></i>
                            <a href="tel:1900xxxx" class="hover:text-gold-400 transition">Hotline: 0983592506</a>
                        </li>
                        <li class="flex items-center space-x-3 text-gray-400">
                            <i class="fas fa-envelope text-gold-400"></i>
                            <a href="mailto:info@trangsuc.com" class="hover:text-gold-400 transition">nguyenduyphat2019@gmail.com</a>
                        </li>
                        <li class="flex items-center space-x-3 text-gray-400">
                            <i class="fas fa-clock text-gold-400"></i>
                            <span>8:00 - 22:00 (Cả tuần)</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Bottom Footer -->
        <div class="border-t border-gray-800">
            <div class="container mx-auto px-4 py-6">
                <div class="flex flex-col md:flex-row justify-between items-center text-gray-400 text-sm">
                    <p>&copy; 2024 Trang Sức Bạc. All rights reserved.</p>
                    <div class="flex items-center space-x-4 mt-4 md:mt-0">
                        <span>Designed with</span>
                        <i class="fas fa-heart text-red-500 animate-pulse"></i>
                        <span>by Your Team</span>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scroll to Top Button -->
    <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" 
            class="fixed bottom-8 right-8 w-12 h-12 bg-gradient-to-r from-gold-600 to-gold-400 text-white rounded-full shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-110 z-50 opacity-0 invisible" 
            id="scrollTopBtn">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Chat/Message Button -->
    <?php if(isset($_SESSION['user_id'])): ?>
    <button onclick="toggleChatBox()" id="chatButton"
       class="fixed bottom-24 right-8 w-14 h-14 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-full shadow-2xl hover:shadow-blue-500/50 transition-all duration-300 hover:scale-110 z-50 flex items-center justify-center group animate-bounce"
       style="animation: bounce 2s infinite; border: none; cursor: pointer;">
        <i class="fas fa-comments text-2xl group-hover:scale-110 transition-transform"></i>
        <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full animate-ping"></span>
        <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full"></span>
    </button>

    <!-- Mini Chat Box -->
    <div id="chatBox" class="fixed bottom-24 right-24 w-96 bg-white rounded-2xl shadow-2xl z-50" style="display: none; height: 500px; max-height: 80vh;">
        <!-- Chat Header -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-4 rounded-t-2xl flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-headset"></i>
                </div>
                <div>
                    <h3 class="font-bold">Chat Hỗ Trợ</h3>
                    <p class="text-xs text-blue-100 flex items-center">
                        <span class="w-2 h-2 bg-green-400 rounded-full mr-1 animate-pulse"></span>
                        Online
                    </p>
                </div>
            </div>
            <button onclick="toggleChatBox()" class="text-white hover:bg-white/20 rounded-full w-8 h-8 flex items-center justify-center transition">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Messages Area -->
        <div id="chatMessages" class="p-4 overflow-y-auto bg-gray-50" style="height: calc(100% - 140px);">
            <div class="text-center py-8 text-gray-400">
                <i class="fas fa-comments text-4xl mb-2"></i>
                <p class="text-sm">Đang tải tin nhắn...</p>
            </div>
        </div>

        <!-- Input Area -->
        <div class="p-4 border-t bg-white rounded-b-2xl">
            <form onsubmit="sendMessage(event)" class="flex gap-2">
                <input type="text" id="chatInput" placeholder="Nhập tin nhắn..." 
                       class="flex-1 px-4 py-2 border-2 border-gray-200 rounded-full focus:border-blue-500 focus:outline-none text-sm"
                       required>
                <button type="submit" class="w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-full hover:shadow-lg transition flex items-center justify-center">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>

    <script>
    let chatBoxOpen = false;
    let chatRefreshInterval;

    function toggleChatBox() {
        chatBoxOpen = !chatBoxOpen;
        const chatBox = document.getElementById('chatBox');
        const chatButton = document.getElementById('chatButton');
        
        if (chatBoxOpen) {
            chatBox.style.display = 'block';
            chatButton.style.animation = 'none';
            loadMessages();
            chatRefreshInterval = setInterval(loadMessages, 3000);
        } else {
            chatBox.style.display = 'none';
            chatButton.style.animation = 'bounce 2s infinite';
            clearInterval(chatRefreshInterval);
        }
    }

    function loadMessages() {
        fetch('<?php echo BASE_URL; ?>chat-api.php?action=get')
            .then(response => response.json())
            .then(data => {
                const chatMessages = document.getElementById('chatMessages');
                if (data.messages && data.messages.length > 0) {
                    let html = '';
                    data.messages.forEach(msg => {
                        const isUser = msg.sender_type === 'user';
                        html += `
                            <div class="mb-3 flex ${isUser ? 'justify-end' : 'justify-start'}">
                                <div class="max-w-[80%]">
                                    ${!isUser ? '<div class="flex items-center gap-2 mb-1"><i class="fas fa-user-shield text-blue-600 text-xs"></i><span class="text-xs text-gray-500 font-semibold">Admin</span></div>' : ''}
                                    <div class="${isUser ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white' : 'bg-white text-gray-800'} rounded-2xl px-4 py-2 shadow-md">
                                        <p class="text-sm">${msg.message.replace(/\n/g, '<br>')}</p>
                                    </div>
                                    <p class="text-xs text-gray-400 mt-1 ${isUser ? 'text-right' : ''}">${msg.time}</p>
                                </div>
                            </div>
                        `;
                    });
                    chatMessages.innerHTML = html;
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                } else {
                    chatMessages.innerHTML = '<div class="text-center py-8 text-gray-400"><i class="fas fa-comments text-4xl mb-2"></i><p class="text-sm">Chưa có tin nhắn. Hãy gửi lời chào!</p></div>';
                }
            })
            .catch(error => console.error('Error:', error));
    }

    function sendMessage(event) {
        event.preventDefault();
        const input = document.getElementById('chatInput');
        const message = input.value.trim();
        
        if (message) {
            // Disable input while sending
            input.disabled = true;
            
            fetch('<?php echo BASE_URL; ?>chat-api.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=send&message=' + encodeURIComponent(message)
            })
            .then(response => response.json())
            .then(data => {
                input.disabled = false;
                if (data.success) {
                    input.value = '';
                    loadMessages();
                } else {
                    alert('Lỗi: ' + (data.message || 'Không thể gửi tin nhắn'));
                    console.error('Send error:', data);
                }
            })
            .catch(error => {
                input.disabled = false;
                alert('Lỗi kết nối! Vui lòng kiểm tra:\n1. Đã chạy setup-chat.php chưa?\n2. Database có bảng messages chưa?');
                console.error('Error:', error);
            });
        }
    }
    </script>
    <?php else: ?>
    <a href="login.php"
       class="fixed bottom-24 right-8 w-14 h-14 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-full shadow-2xl hover:shadow-blue-500/50 transition-all duration-300 hover:scale-110 z-50 flex items-center justify-center group animate-bounce"
       style="animation: bounce 2s infinite;"
       title="Đăng nhập để chat">
        <i class="fas fa-comments text-2xl group-hover:scale-110 transition-transform"></i>
        <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full animate-ping"></span>
        <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full"></span>
    </a>
    <?php endif; ?>

    <style>
        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }
    </style>

    <script>
        // Scroll to top button
        window.addEventListener('scroll', function() {
            const scrollBtn = document.getElementById('scrollTopBtn');
            if (window.pageYOffset > 300) {
                scrollBtn.classList.remove('opacity-0', 'invisible');
            } else {
                scrollBtn.classList.add('opacity-0', 'invisible');
            }
        });

        // Add to cart function with enhanced error handling
        function addToCart(productId) {
            console.log('Adding product to cart:', productId);
            
            fetch('cart-actions.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=add&product_id=' + productId + '&quantity=1'
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.text().then(text => {
                    console.log('Response text:', text);
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        console.error('JSON parse error:', e);
                        throw new Error('Lỗi phản hồi từ server: ' + text);
                    }
                });
            })
            .then(data => {
                console.log('Cart data:', data);
                
                if (data.require_login) {
                    showNotification('Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng!', 'warning');
                    setTimeout(() => {
                        window.location.href = 'login.php';
                    }, 1500);
                    return;
                }
                
                if (data.success) {
                    showNotification('Đã thêm vào giỏ hàng!', 'success');
                    updateCartCount();
                } else {
                    showNotification(data.message || 'Không thể thêm vào giỏ hàng', 'error');
                }
            })
            .catch(error => {
                console.error('Cart error:', error);
                showNotification('Lỗi kết nối: ' + error.message, 'error');
            });
        }

        function showNotification(message, type) {
            const notification = document.createElement('div');
            let bgColor = 'bg-red-500';
            let icon = 'exclamation-circle';
            
            if (type === 'success') {
                bgColor = 'bg-green-500';
                icon = 'check-circle';
            } else if (type === 'warning') {
                bgColor = 'bg-orange-500';
                icon = 'exclamation-triangle';
            }
            
            notification.className = `fixed top-24 right-8 px-6 py-4 rounded-lg shadow-xl z-50 animate-fadeInUp ${bgColor} text-white`;
            notification.innerHTML = `<i class="fas fa-${icon} mr-2"></i>${message}`;
            document.body.appendChild(notification);
            setTimeout(() => notification.remove(), 3000);
        }

        function updateCartCount() {
            fetch('get-cart-count.php')
                .then(response => response.json())
                .then(data => {
                    console.log('Cart count data:', data);
                    const cartCount = document.querySelector('.cart-count');
                    if (cartCount && data.count !== undefined) {
                        cartCount.textContent = data.count;
                        console.log('Updated cart count to:', data.count);
                    }
                })
                .catch(error => console.error('Update cart count error:', error));
        }
    </script>
</body>
</html>
