<?php
// Script kiểm tra encoding của tất cả file PHP
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Kiểm Tra Encoding</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; border-bottom: 3px solid #d4af37; padding-bottom: 10px; }
        .file-list { margin: 20px 0; }
        .file-item { padding: 10px; margin: 5px 0; border-left: 4px solid #ddd; background: #f9f9f9; }
        .has-issue { border-left-color: #e74c3c; background: #fee; }
        .no-issue { border-left-color: #27ae60; background: #efe; }
        .btn { display: inline-block; padding: 12px 24px; background: #d4af37; color: white; text-decoration: none; border-radius: 5px; margin: 10px 5px; font-weight: bold; }
        .btn:hover { background: #c9991f; }
        .stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin: 20px 0; }
        .stat-box { padding: 20px; text-align: center; border-radius: 8px; }
        .stat-box.total { background: #3498db; color: white; }
        .stat-box.issue { background: #e74c3c; color: white; }
        .stat-box.ok { background: #27ae60; color: white; }
        .stat-number { font-size: 36px; font-weight: bold; }
    </style>
</head>
<body>
<div class="container">
    <h1>🔍 Kiểm Tra Encoding Tất Cả File PHP</h1>
    
    <?php
    $total = 0;
    $has_issues = 0;
    $no_issues = 0;
    $files_with_issues = [];
    
    // Các pattern lỗi encoding phổ biến
    $error_patterns = [
        '/[ÃÄÅÆáºáº»áº½áº¹á»á»§á»¥á»³á»·á»¹á»±Æ¯Æ°Æ¡Æ ]/',
        '/\?{2,}/',  // Nhiều dấu ? liên tiếp
    ];
    
    // Quét tất cả file PHP
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator('.', RecursiveDirectoryIterator::SKIP_DOTS)
    );
    
    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $filepath = $file->getPathname();
            
            // Bỏ qua một số thư mục
            if (strpos($filepath, 'vendor') !== false || 
                strpos($filepath, 'node_modules') !== false ||
                strpos($filepath, '.git') !== false) {
                continue;
            }
            
            $total++;
            $content = file_get_contents($filepath);
            $has_error = false;
            
            foreach ($error_patterns as $pattern) {
                if (preg_match($pattern, $content)) {
                    $has_error = true;
                    break;
                }
            }
            
            if ($has_error) {
                $has_issues++;
                $files_with_issues[] = $filepath;
            } else {
                $no_issues++;
            }
        }
    }
    ?>
    
    <div class="stats">
        <div class="stat-box total">
            <div class="stat-number"><?php echo $total; ?></div>
            <div>Tổng số file</div>
        </div>
        <div class="stat-box issue">
            <div class="stat-number"><?php echo $has_issues; ?></div>
            <div>File có vấn đề</div>
        </div>
        <div class="stat-box ok">
            <div class="stat-number"><?php echo $no_issues; ?></div>
            <div>File OK</div>
        </div>
    </div>
    
    <?php if ($has_issues > 0): ?>
    <h2>⚠️ Danh sách file có vấn đề encoding:</h2>
    <div class="file-list">
        <?php foreach ($files_with_issues as $file): ?>
        <div class="file-item has-issue">
            <strong>❌ <?php echo htmlspecialchars($file); ?></strong>
        </div>
        <?php endforeach; ?>
    </div>
    
    <div style="background: #fff3cd; border: 2px solid #ffc107; padding: 20px; border-radius: 8px; margin: 20px 0;">
        <h3 style="margin-top: 0;">💡 Cách khắc phục:</h3>
        <ol>
            <li>Chạy script <strong>fix-encoding.php</strong> để sửa database</li>
            <li>Mở từng file bị lỗi và lưu lại với encoding UTF-8</li>
            <li>Hoặc liên hệ để được hỗ trợ sửa thủ công</li>
        </ol>
    </div>
    <?php else: ?>
    <div style="background: #d4edda; border: 2px solid #28a745; padding: 20px; border-radius: 8px; margin: 20px 0;">
        <h3 style="margin-top: 0; color: #155724;">✅ Tuyệt vời!</h3>
        <p style="color: #155724; margin: 0;">Tất cả file PHP đều có encoding đúng!</p>
    </div>
    <?php endif; ?>
    
    <div style="text-align: center; margin-top: 30px;">
        <a href="fix-encoding.php" class="btn">🔧 Sửa Database</a>
        <a href="index.php" class="btn">🏠 Về Trang Chủ</a>
    </div>
</div>
</body>
</html>
