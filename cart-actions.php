<?php
// File xử lý các thao tác với giỏ hàng (AJAX)
error_reporting(E_ALL);
ini_set('display_errors', 0); // Không hiển thị lỗi ra màn hình
ini_set('log_errors', 1); // Ghi lỗi vào log

require_once 'includes/config.php'; // Nạp file kết nối
header('Content-Type: application/json'); // Trả về dữ liệu dạng JSON

// Mảng chứa kết quả trả về
$ketqua = ['success' => false, 'message' => '', 'cart_count' => 0, 'require_login' => false];

// Kiểm tra kết nối database
if (!isset($conn) || !$conn) {
    $ketqua['message'] = 'Lỗi kết nối cơ sở dữ liệu!';
    echo json_encode($ketqua);
    exit;
}

// Kiểm tra có gửi hành động không
if (!isset($_POST['action'])) {
    echo json_encode($ketqua);
    exit;
}

$hanhdong = $_POST['action']; // Lấy hành động: add (thêm), update (cập nhật), remove (xóa)

// KIỂM TRA ĐĂNG NHẬP - BẮT BUỘC phải đăng nhập mới được thêm giỏ hàng
if ($hanhdong === 'add' && !isset($_SESSION['user_id'])) {
    $ketqua['require_login'] = true;
    $ketqua['message'] = 'Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng!';
    echo json_encode($ketqua);
    exit;
}

// Khởi tạo giỏ hàng nếu chưa có
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Xử lý theo từng hành động
switch ($hanhdong) {
    case 'add': // Thêm sản phẩm vào giỏ
        $id_sanpham = (int)$_POST['product_id'];
        $soluong = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
        
        // Lấy thông tin sản phẩm từ database
        $sql = "SELECT * FROM products WHERE id = $id_sanpham AND status = 1";
        $dulieu = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($dulieu) > 0) {
            $sanpham = mysqli_fetch_assoc($dulieu);
            
            // Kiểm tra số lượng tồn kho
            if ($sanpham['quantity'] < $soluong) {
                $ketqua['message'] = 'Sản phẩm không đủ số lượng trong kho!';
                break;
            }
            
            // Thêm vào giỏ hàng
            if (isset($_SESSION['cart'][$id_sanpham])) {
                // Nếu sản phẩm đã có trong giỏ thì tăng số lượng
                $_SESSION['cart'][$id_sanpham]['quantity'] += $soluong;
            } else {
                // Nếu chưa có thì thêm mới
                $_SESSION['cart'][$id_sanpham] = [
                    'id' => $sanpham['id'],
                    'name' => $sanpham['name'],
                    'slug' => $sanpham['slug'],
                    'image' => $sanpham['image'],
                    'price' => $sanpham['sale_price'] ?: $sanpham['price'], // Ưu tiên giá khuyến mãi
                    'quantity' => $soluong
                ];
            }
            
            // Đếm tổng số sản phẩm trong giỏ
            $total_items = 0;
            foreach ($_SESSION['cart'] as $item) {
                $total_items += $item['quantity'];
            }
            
            $ketqua['success'] = true;
            $ketqua['message'] = 'Đã thêm sản phẩm vào giỏ hàng!';
            $ketqua['cart_count'] = $total_items;
        } else {
            $ketqua['message'] = 'Sản phẩm không tồn tại!';
        }
        break;
        
    case 'update':
        $product_id = (int)$_POST['product_id'];
        $quantity = (int)$_POST['quantity'];
        
        if (isset($_SESSION['cart'][$product_id])) {
            if ($quantity <= 0) {
                unset($_SESSION['cart'][$product_id]);
                $ketqua['success'] = true;
                $ketqua['message'] = 'Đã xóa sản phẩm!';
            } else {
                // Kiểm tra số lượng tồn kho
                $sql = "SELECT quantity FROM products WHERE id = $product_id";
                $result = mysqli_query($conn, $sql);
                if ($result && mysqli_num_rows($result) > 0) {
                    $product = mysqli_fetch_assoc($result);
                    if ($quantity > $product['quantity']) {
                        $ketqua['message'] = 'Số lượng trong kho không đủ! (Còn: ' . $product['quantity'] . ')';
                        break;
                    }
                }
                
                // Cập nhật số lượng mới (thay thế, không cộng dồn)
                $_SESSION['cart'][$product_id]['quantity'] = $quantity;
                $ketqua['success'] = true;
                $ketqua['message'] = 'Đã cập nhật số lượng!';
            }
        }
        break;
        
    case 'remove':
        $product_id = (int)$_POST['product_id'];
        
        if (isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);
            $ketqua['success'] = true;
            $ketqua['message'] = 'Đã xóa sản phẩm!';
        }
        break;
}

// Đếm tổng số sản phẩm trong giỏ
$total_cart = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $total_cart += $item['quantity'];
    }
}
$ketqua['cart_count'] = $total_cart;

echo json_encode($ketqua);
?>
