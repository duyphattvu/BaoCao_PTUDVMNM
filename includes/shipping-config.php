<?php
// Cấu hình phí vận chuyển theo tỉnh/thành phố

// Ngưỡng miễn phí vận chuyển
define('FREE_SHIPPING_THRESHOLD', 999000);

// Danh sách tỉnh/thành và phí vận chuyển
$shipping_fees = [
    // Miền Bắc - Nội thành Hà Nội
    'Hà Nội' => 25000,
    'Hải Phòng' => 30000,
    'Quảng Ninh' => 35000,
    'Bắc Ninh' => 30000,
    'Hải Dương' => 30000,
    'Hưng Yên' => 30000,
    'Vĩnh Phúc' => 30000,
    'Thái Nguyên' => 35000,
    'Bắc Giang' => 35000,
    
    // Miền Trung
    'Đà Nẵng' => 35000,
    'Huế' => 40000,
    'Quảng Nam' => 40000,
    'Quảng Ngãi' => 45000,
    'Bình Định' => 45000,
    'Phú Yên' => 50000,
    'Khánh Hòa' => 50000,
    'Ninh Thuận' => 55000,
    'Bình Thuận' => 55000,
    
    // Miền Nam - TP.HCM và lân cận
    'Hồ Chí Minh' => 25000,
    'TP Hồ Chí Minh' => 25000,
    'TP.HCM' => 25000,
    'Sài Gòn' => 25000,
    'Biên Hòa' => 30000,
    'Đồng Nai' => 30000,
    'Bình Dương' => 30000,
    'Long An' => 35000,
    'Tây Ninh' => 40000,
    'Bà Rịa - Vũng Tàu' => 35000,
    'Vũng Tàu' => 35000,
    
    // Đồng bằng sông Cửu Long
    'Cần Thơ' => 40000,
    'An Giang' => 45000,
    'Tiền Giang' => 40000,
    'Bến Tre' => 45000,
    'Vĩnh Long' => 45000,
    'Đồng Tháp' => 45000,
    'Trà Vinh' => 50000,
    'Sóc Trăng' => 50000,
    'Hậu Giang' => 50000,
    'Kiên Giang' => 55000,
    'Bạc Liêu' => 55000,
    'Cà Mau' => 60000,
    
    // Tây Nguyên
    'Đắk Lắk' => 50000,
    'Gia Lai' => 55000,
    'Kon Tum' => 60000,
    'Đắk Nông' => 55000,
    'Lâm Đồng' => 50000,
];

// Phí mặc định cho các tỉnh không có trong danh sách
define('DEFAULT_SHIPPING_FEE', 40000);

/**
 * Tính phí vận chuyển dựa trên địa chỉ và tổng tiền đơn hàng
 * @param string $address Địa chỉ giao hàng
 * @param float $total Tổng tiền đơn hàng
 * @return int Phí vận chuyển
 */
function calculateShippingFee($address, $total) {
    global $shipping_fees;
    
    // Nếu đơn hàng >= 999,000đ thì miễn phí
    if ($total >= FREE_SHIPPING_THRESHOLD) {
        return 0;
    }
    
    // Tìm tỉnh/thành trong địa chỉ
    $address = strtolower($address);
    $address = str_replace(['tỉnh', 'thành phố', 'tp.', 'tp'], '', $address);
    
    foreach ($shipping_fees as $province => $fee) {
        $province_lower = strtolower($province);
        if (strpos($address, $province_lower) !== false) {
            return $fee;
        }
    }
    
    // Trả về phí mặc định nếu không tìm thấy
    return DEFAULT_SHIPPING_FEE;
}

/**
 * Lấy danh sách tỉnh/thành để hiển thị trong dropdown
 * @return array Danh sách tỉnh/thành
 */
function getProvincesList() {
    global $shipping_fees;
    $provinces = array_keys($shipping_fees);
    sort($provinces);
    return $provinces;
}

/**
 * Lấy phí vận chuyển theo tỉnh/thành
 * @param string $province Tên tỉnh/thành
 * @return int Phí vận chuyển
 */
function getShippingFeeByProvince($province) {
    global $shipping_fees;
    return $shipping_fees[$province] ?? DEFAULT_SHIPPING_FEE;
}
?>
