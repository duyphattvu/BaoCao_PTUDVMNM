// Banner Slider
let currentSlide = 0;
const slides = document.querySelectorAll('.banner-slide');

function showSlide(n) {
    if (slides.length === 0) return;
    
    slides.forEach(slide => slide.classList.remove('active'));
    
    currentSlide = (n + slides.length) % slides.length;
    slides[currentSlide].classList.add('active');
}

function nextSlide() {
    showSlide(currentSlide + 1);
}

// Auto slide every 5 seconds
if (slides.length > 0) {
    showSlide(0);
    setInterval(nextSlide, 5000);
}

// Add to cart function với kiểm tra đăng nhập
function addToCart(productId) {
    fetch('cart-actions.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=add&product_id=${productId}`
    })
    .then(response => {
        console.log('Response status:', response.status);
        // Kiểm tra response có ok không
        if (!response.ok) {
            throw new Error('HTTP error! status: ' + response.status);
        }
        // Đọc response text trước để debug
        return response.text();
    })
    .then(text => {
        console.log('Response text:', text);
        try {
            return JSON.parse(text);
        } catch(e) {
            console.error('JSON parse error:', e);
            throw new Error('Invalid JSON: ' + text);
        }
    })
    .then(data => {
        console.log('Cart data:', data); // Debug
        
        if (data.require_login) {
            // Nếu chưa đăng nhập, hiển thị thông báo đẹp
            showNotification('⚠️ ' + data.message, 'warning');
            
            // Sau 1 giây hỏi chuyển trang
            setTimeout(() => {
                if (confirm('Bạn có muốn chuyển đến trang đăng nhập không?')) {
                    window.location.href = 'login.php?redirect=' + encodeURIComponent(window.location.href);
                }
            }, 500);
        } else if (data.success) {
            // Cập nhật số lượng giỏ hàng
            updateCartCount(data.cart_count);
            // Hiển thị thông báo thành công
            showNotification('✓ Đã thêm sản phẩm vào giỏ hàng!', 'success');
        } else if (data.message) {
            // Hiển thị thông báo lỗi cụ thể
            showNotification('✕ ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Cart error:', error);
        showNotification('✕ Không thể thêm vào giỏ hàng. Vui lòng thử lại!', 'error');
    });
}

// Hàm hiển thị thông báo đẹp
function showNotification(message, type = 'success') {
    // Xóa thông báo cũ nếu có
    const oldNotification = document.querySelector('.notification');
    if (oldNotification) {
        oldNotification.remove();
    }
    
    // Màu sắc theo loại
    let bgColor = '#4CAF50'; // success - xanh lá
    if (type === 'error') bgColor = '#f44336'; // đỏ
    if (type === 'warning') bgColor = '#ff9800'; // cam
    if (type === 'info') bgColor = '#2196F3'; // xanh dương
    
    // Tạo element thông báo
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 25px;
        background: ${bgColor};
        color: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 10000;
        animation: slideIn 0.3s ease-out;
        font-weight: 500;
        min-width: 250px;
    `;
    notification.textContent = message;
    
    // Thêm animation CSS
    if (!document.querySelector('#notification-style')) {
        const style = document.createElement('style');
        style.id = 'notification-style';
        style.textContent = `
            @keyframes slideIn {
                from {
                    transform: translateX(400px);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            @keyframes slideOut {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(400px);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    }
    
    document.body.appendChild(notification);
    
    // Tự động xóa sau 3 giây
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease-in';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Update cart count
function updateCartCount(count) {
    const cartCount = document.querySelector('.cart-count');
    if (cartCount) {
        cartCount.textContent = count;
    }
}

// Update cart quantity
function updateCartQuantity(productId, quantity) {
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
    });
}

// Remove from cart
function removeFromCart(productId) {
    if (confirm('Bạn có chắc muốn xóa sản phẩm này?')) {
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
        });
    }
}

// Form validation
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return true;
    
    const inputs = form.querySelectorAll('[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.style.borderColor = 'red';
            isValid = false;
        } else {
            input.style.borderColor = '#ddd';
        }
    });
    
    return isValid;
}

// Price filter
function applyPriceFilter() {
    const minPrice = document.getElementById('min-price')?.value || 0;
    const maxPrice = document.getElementById('max-price')?.value || 999999999;
    const currentUrl = new URL(window.location.href);
    
    currentUrl.searchParams.set('min_price', minPrice);
    currentUrl.searchParams.set('max_price', maxPrice);
    
    window.location.href = currentUrl.toString();
}

// Preview image before upload
function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const preview = document.getElementById(previewId);
            if (preview) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });
});

// Smooth scroll
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});
