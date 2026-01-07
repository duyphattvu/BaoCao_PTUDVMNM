<?php
// File hiển thị chi tiết tin tức
require_once 'includes/config.php'; // Nạp file kết nối database

// Lấy slug từ URL
$slug = isset($_GET['slug']) ? mysqli_real_escape_string($conn, $_GET['slug']) : '';

// Truy vấn tin tức
$sql = "SELECT * FROM news WHERE slug = '$slug' AND status = 1";
$result = mysqli_query($conn, $sql);

// Nếu không tìm thấy thì về trang tin tức
if (mysqli_num_rows($result) == 0) {
    header('Location: news.php');
    exit;
}

$news = mysqli_fetch_assoc($result);
$page_title = $news['title'];

// Lấy tin tức liên quan (4 tin mới nhất khác tin hiện tại)
$related_query = "SELECT * FROM news WHERE id != {$news['id']} AND status = 1 ORDER BY created_at DESC LIMIT 4";
$related_news = mysqli_query($conn, $related_query);

include 'includes/header.php';
?>

<div class="container">
    <!-- Breadcrumb -->
    <div style="padding: 20px 0; color: #666;">
        <a href="index.php" style="color: #d4af37;">Trang chủ</a> / 
        <a href="news.php" style="color: #d4af37;">Tin tức</a> / 
        <span><?php echo htmlspecialchars($news['title']); ?></span>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 350px; gap: 40px; margin: 30px 0;">
        <!-- Nội dung chính -->
        <div class="news-detail" style="background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <h1 style="font-size: 32px; color: #333; margin-bottom: 20px; line-height: 1.4;">
                <?php echo htmlspecialchars($news['title']); ?>
            </h1>
            
            <div style="color: #999; font-size: 14px; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #f0f0f0;">
                <i class="far fa-calendar"></i> <?php echo date('d/m/Y H:i', strtotime($news['created_at'])); ?>
                <?php if($news['author']): ?>
                | <i class="far fa-user"></i> <?php echo htmlspecialchars($news['author']); ?>
                <?php endif; ?>
            </div>

            <?php if($news['image']): ?>
            <div style="margin: 30px 0;">
                <img src="<?php echo BASE_URL; ?>assets/images/news/<?php echo $news['image']; ?>" 
                     alt="<?php echo htmlspecialchars($news['title']); ?>"
                     style="width: 100%; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            </div>
            <?php endif; ?>

            <div class="news-content" style="color: #555; line-height: 1.8; font-size: 16px;">
                <?php echo nl2br($news['content']); ?>
            </div>

            <!-- Share buttons -->
            <div style="margin-top: 40px; padding-top: 30px; border-top: 2px solid #f0f0f0;">
                <h4 style="margin-bottom: 15px; color: #333;">Chia sẻ bài viết:</h4>
                <div style="display: flex; gap: 10px;">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(BASE_URL . 'news-detail.php?slug=' . $news['slug']); ?>" 
                       target="_blank" class="btn" style="background: #3b5998; color: white; padding: 10px 20px; border-radius: 5px;">
                        <i class="fab fa-facebook-f"></i> Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(BASE_URL . 'news-detail.php?slug=' . $news['slug']); ?>&text=<?php echo urlencode($news['title']); ?>" 
                       target="_blank" class="btn" style="background: #1da1f2; color: white; padding: 10px 20px; border-radius: 5px;">
                        <i class="fab fa-twitter"></i> Twitter
                    </a>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div>
            <!-- Tin tức liên quan -->
            <?php if (mysqli_num_rows($related_news) > 0): ?>
            <div style="background: #fff; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 30px;">
                <h3 style="font-size: 20px; color: #333; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid #d4af37;">
                    <i class="fas fa-newspaper"></i> Tin liên quan
                </h3>
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    <?php while($related = mysqli_fetch_assoc($related_news)): ?>
                    <div style="display: flex; gap: 15px;">
                        <?php if($related['image']): ?>
                        <a href="news-detail.php?slug=<?php echo $related['slug']; ?>" style="flex-shrink: 0;">
                            <img src="<?php echo BASE_URL; ?>assets/images/news/<?php echo $related['image']; ?>" 
                                 alt="<?php echo htmlspecialchars($related['title']); ?>"
                                 style="width: 80px; height: 80px; object-fit: cover; border-radius: 5px;">
                        </a>
                        <?php endif; ?>
                        <div style="flex: 1;">
                            <a href="news-detail.php?slug=<?php echo $related['slug']; ?>" 
                               style="color: #333; text-decoration: none; font-size: 14px; line-height: 1.4; display: block; margin-bottom: 5px;">
                                <?php echo htmlspecialchars($related['title']); ?>
                            </a>
                            <div style="color: #999; font-size: 12px;">
                                <i class="far fa-calendar"></i> <?php echo date('d/m/Y', strtotime($related['created_at'])); ?>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
                <a href="news.php" class="btn btn-primary" style="width: 100%; margin-top: 20px; text-align: center;">
                    Xem tất cả tin tức
                </a>
            </div>
            <?php endif; ?>

            <!-- Banner quảng cáo (optional) -->
            <div style="background: linear-gradient(135deg, #d4af37 0%, #aa8a2e 100%); padding: 30px; border-radius: 10px; text-align: center; color: white;">
                <i class="fas fa-gem" style="font-size: 48px; margin-bottom: 15px;"></i>
                <h4 style="font-size: 18px; margin-bottom: 10px;">Bộ sưu tập mới</h4>
                <p style="font-size: 14px; margin-bottom: 20px; opacity: 0.9;">
                    Khám phá những mẫu trang sức bạc mới nhất
                </p>
                <a href="products.php" class="btn" style="background: white; color: #d4af37; padding: 10px 25px; border-radius: 5px; font-weight: 600;">
                    Xem ngay
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.news-content h2, .news-content h3, .news-content h4 {
    color: #333;
    margin: 25px 0 15px 0;
}

.news-content p {
    margin: 15px 0;
}

.news-content img {
    max-width: 100%;
    height: auto;
    border-radius: 5px;
    margin: 20px 0;
}

.news-content ul, .news-content ol {
    margin: 15px 0;
    padding-left: 30px;
}

.news-content li {
    margin: 8px 0;
}

@media (max-width: 768px) {
    .container > div {
        grid-template-columns: 1fr !important;
    }
}
</style>

<?php include 'includes/footer.php'; ?>
