<?php
// Script tự động sửa encoding cho tất cả file PHP
set_time_limit(300);

echo "<!DOCTYPE html>";
echo "<html><head><meta charset='UTF-8'><title>Sửa Encoding Tất Cả File</title>";
echo "<style>body{font-family:Arial;padding:20px;} .success{color:green;} .error{color:red;} .info{color:blue;}</style>";
echo "</head><body>";
echo "<h1>Đang sửa encoding cho tất cả file PHP...</h1>";

$fixed = 0;
$errors = 0;

// Danh sách các file cần sửa (các file chính)
$files_to_fix = [
    'login.php',
    'register.php',
    'search.php',
    'product-detail.php',
    'checkout.php',
    'order-success.php',
    'profile.php',
    'purchase-history.php',
    'orders.php',
    'order-detail.php',
    'pages/cart.php',
];

// Các chuỗi cần thay thế (lỗi encoding phổ biến)
$replacements = [
    // Các ký tự lỗi encoding phổ biến
    'Ä' => 'Đ',
    'Ã¡' => 'á',
    'Ã ' => 'à',
    'áº£' => 'ả',
    'Ã£' => 'ã',
    'áº¡' => 'ạ',
    'Ã©' => 'é',
    'Ã¨' => 'è',
    'áº»' => 'ẻ',
    'áº½' => 'ẽ',
    'áº¹' => 'ẹ',
    'Ã­' => 'í',
    'Ã¬' => 'ì',
    'áº£' => 'ỉ',
    'Ä©' => 'ĩ',
    'á»‹' => 'ị',
    'Ã³' => 'ó',
    'Ã²' => 'ò',
    'á»' => 'ỏ',
    'Ãµ' => 'õ',
    'á»' => 'ọ',
    'Ãº' => 'ú',
    'Ã¹' => 'ù',
    'á»§' => 'ủ',
    'Å©' => 'ũ',
    'á»¥' => 'ụ',
    'Ã½' => 'ý',
    'á»³' => 'ỳ',
    'á»·' => 'ỷ',
    'á»¹' => 'ỹ',
    'á»±' => 'ự',
    // Chữ hoa
    'Ã' => 'Á',
    'Ã€' => 'À',
    'áº¢' => 'Ả',
    'Ãƒ' => 'Ã',
    'áº ' => 'Ạ',
    'Ã‰' => 'É',
    'Ãˆ' => 'È',
    'áºº' => 'Ẻ',
    'áº¼' => 'Ẽ',
    'áº¸' => 'Ẹ',
    'Ã' => 'Í',
    'ÃŒ' => 'Ì',
    'Æ¯' => 'ư',
    'Æ°' => 'Ư',
    'Æ¡' => 'ơ',
    'Æ ' => 'Ơ',
];

foreach ($files_to_fix as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        $original = $content;
        
        // Thay thế các ký tự lỗi
        $content = str_replace(array_keys($replacements), array_values($replacements), $content);
        
        if ($content !== $original) {
            if (file_put_contents($file, $content)) {
                echo "<p class='success'>✓ Đã sửa: <strong>$file</strong></p>";
                $fixed++;
            } else {
                echo "<p class='error'>✗ Lỗi khi sửa: <strong>$file</strong></p>";
                $errors++;
            }
        } else {
            echo "<p class='info'>○ Không cần sửa: <strong>$file</strong></p>";
        }
    }
}

echo "<hr>";
echo "<h2>Kết quả:</h2>";
echo "<p class='success'>✓ Đã sửa: <strong>$fixed</strong> file</p>";
echo "<p class='error'>✗ Lỗi: <strong>$errors</strong> file</p>";
echo "<hr>";
echo "<p><strong>Lưu ý:</strong> Nếu vẫn còn lỗi, hãy chạy file <code>fix-encoding.php</code> để sửa database.</p>";
echo "<p><a href='index.php' style='padding: 10px 20px; background: #d4af37; color: white; text-decoration: none; border-radius: 5px;'>Về trang chủ</a></p>";
echo "</body></html>";
?>
