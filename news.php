<?php
// File hiển thị trang tin tức
require_once 'includes/config.php'; // Nạp file kết nối database

$page_title = 'Tin Tức - Kiến Thức Trang Sức'; // Tiêu đề trang

// Phân trang - Hiển thị 9 tin tức mỗi trang
$limit = 9;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Đếm tổng số tin tức
$count_query = "SELECT COUNT(*) as count FROM news WHERE status = 1";
$total = mysqli_fetch_assoc(mysqli_query($conn, $count_query))['count'];
$total_pages = ceil($total / $limit);

// Lấy danh sách tin tức
$news_query = "SELECT * FROM news WHERE status = 1 ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
$news_list = mysqli_query($conn, $news_query);

include 'includes/header.php';
?>

<div class="container">
    <div class="section-title">
        <h2>Tin Tức & Kiến Thức</h2>
        <p style="color: #666; margin-top: 10px;">Cập nhật thông tin mới nhất về trang sức bạc</p>
        <p style="color: #999; margin-top: 5px;">Tìm thấy <?php echo $total; ?> bài viết</p>
    </div>

    <?php if (mysqli_num_rows($news_list) > 0): ?>
    <div class="news-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px; margin-bottom: 40px;">
        <?php while($news = mysqli_fetch_assoc($news_list)): ?>
        <div class="news-card" style="background: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); transition: transform 0.3s;">
            <?php if($news['image']): ?>
            <div class="news-image" style="height: 200px; overflow: hidden;">
                <a href="news-detail.php?slug=<?php echo $news['slug']; ?>">
                    <img src="<?php echo BASE_URL; ?>assets/images/news/<?php echo $news['image']; ?>" 
                         alt="<?php echo htmlspecialchars($news['title']); ?>"
                         style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s;">
                </a>
            </div>
            <?php endif; ?>
            
            <div class="news-content" style="padding: 20px;">
                <div style="color: #999; font-size: 14px; margin-bottom: 10px;">
                    <i class="far fa-calendar"></i> <?php echo date('d/m/Y', strtotime($news['created_at'])); ?>
                    <?php if($news['author']): ?>
                    | <i class="far fa-user"></i> <?php echo htmlspecialchars($news['author']); ?>
                    <?php endif; ?>
                </div>
                
                <h3 style="margin: 10px 0; font-size: 18px;">
                    <a href="news-detail.php?slug=<?php echo $news['slug']; ?>" 
                       style="color: #333; text-decoration: none; transition: color 0.3s;">
                        <?php echo htmlspecialchars($news['title']); ?>
                    </a>
                </h3>
                
                <p style="color: #666; line-height: 1.6; margin: 15px 0;">
                    <?php 
                    $content = strip_tags($news['content']);
                    echo mb_substr($content, 0, 120, 'UTF-8') . (mb_strlen($content, 'UTF-8') > 120 ? '...' : ''); 
                    ?>
                </p>
                
                <a href="news-detail.php?slug=<?php echo $news['slug']; ?>" 
                   class="btn btn-secondary" 
                   style="display: inline-block; padding: 8px 20px; font-size: 14px;">
                    Xem thêm <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
        <?php endwhile; ?>
    </div>

    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
    <div class="pagination">
        <?php if ($page > 1): ?>
        <a href="?page=<?php echo $page - 1; ?>">&laquo; Trước</a>
        <?php endif; ?>
        
        <?php for ($i = 1; $i <= $total_pages; $i++): 
            if ($i == 1 || $i == $total_pages || ($i >= $page - 2 && $i <= $page + 2)):
        ?>
        <a href="?page=<?php echo $i; ?>" class="<?php echo $page == $i ? 'active' : ''; ?>">
            <?php echo $i; ?>
        </a>
        <?php 
            elseif ($i == $page - 3 || $i == $page + 3):
                echo '<span>...</span>';
            endif;
        endfor; 
        ?>
        
        <?php if ($page < $total_pages): ?>
        <a href="?page=<?php echo $page + 1; ?>">Sau &raquo;</a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    
    <?php else: ?>
    <div style="text-align: center; padding: 60px; background: #fff; border-radius: 10px;">
        <i class="fas fa-newspaper" style="font-size: 64px; color: #ccc; margin-bottom: 20px;"></i>
        <h3 style="color: #666;">Chưa có tin tức nào</h3>
        <p style="color: #999; margin-top: 10px;">Vui lòng quay lại sau để xem tin tức mới nhất</p>
    </div>
    <?php endif; ?>
</div>

<style>
.news-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}

.news-card:hover img {
    transform: scale(1.1);
}

.news-card h3 a:hover {
    color: #d4af37;
}
</style>

<?php include 'includes/footer.php'; ?>
