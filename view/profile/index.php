<?php
require_once 'model/mLoginLogout.php';

$baseUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/project/frontend/';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?login');
    exit;
}

$model = new mLoginLogout();
$user = $model->getUserById($_SESSION['user_id']);
$ratingStats = $model->getRatingStats($_SESSION['user_id']);
$totalReviews = $ratingStats['total_reviews'] ?? 0;
$averageRating = number_format($ratingStats['average_rating'] ?? 0, 1);

?>

<?php
include_once("controller/cReview.php");
$cReview = new cReview();
$reviews = $cReview->getReviewsBySeller();
?>

<?php
include_once("view/header.php");
?>


<!-- Profile Container -->
<div class="container my-5">
    <div class="row">
        <!-- Left Column: User Info -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm p-3 text-center">
            <?php
                // Xử lý ảnh đại diện
                $avatarPath = 'img/';
                $avatarFile = (!empty($user['anh_dai_dien']) && file_exists($avatarPath . $user['anh_dai_dien']))
                    ? $user['anh_dai_dien']
                    : 'default.jpg';
                ?>

                <img src="<?php echo $avatarPath . htmlspecialchars($avatarFile); ?>" class="profile-avatar mb-3" alt="Ảnh đại diện">

                <h5><?php echo htmlspecialchars($user['ten_dang_nhap']); ?></h5>
                <p class="text-muted">Chào mừng bạn đến với trang cá nhân của bạn.</p>
                
                <?php if ($totalReviews > 0): ?>
    


                <div class="text-left mt-3">
                <p class="mt-2">
                    <strong><?php echo $averageRating; ?></strong>
                    <?php for ($i = 0; $i < floor($averageRating); $i++): ?>
                        <i class="fas fa-star text-warning"></i>
                    <?php endfor; ?>
                    <?php if ($averageRating - floor($averageRating) >= 0.5): ?>
                        <i class="fas fa-star-half-alt text-warning"></i>
                    <?php endif; ?>
                    (<?php echo $totalReviews; ?> đánh giá)
                </p>
            <?php else: ?>
                <p class="mt-2 text-muted">(Chưa có đánh giá)</p>
            <?php endif; ?>
                    <p><i class="fas fa-envelope text-primary">:</i> <?php echo htmlspecialchars($user['email']); ?></p>
                    <p><i class="fas fa-phone text-primary">:</i> <?php echo htmlspecialchars($user['so_dien_thoai']); ?></p>
                    <p><i class="fas fa-map-marker-alt text-primary">:</i> <?php echo htmlspecialchars($user['dia_chi']); ?></p>
                    <p><i class="fas fa-calendar-alt text-primary">:</i> Ngày tham gia: <?php echo htmlspecialchars($user['ngay_tao']); ?></p>
                </div>
                <a href="#" class="btn btn-warning mt-3">Chỉnh sửa thông tin</a>
            </div>
        </div>

        <!-- Right Column: Posts -->
<div class="col-md-8">
    <!-- Tin đăng -->
    <div class="card shadow-sm p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="profile-tabs">
                <a href="#" class="tab-active">Đang hiển thị (0)</a>
                <a href="#">Đã bán (3)</a>
            </div>
            <a href="../index.php" class="btn btn-primary">Đăng tin ngay</a>
        </div>
        <div class="text-center">
            <img src="img/no-posts.png" alt="No Posts" class="img-fluid mb-3" style="max-width: 200px;">
            <p>Bạn chưa có tin đăng nào</p>
        </div>
    </div>

<!-- Đánh giá sản phẩm -->
<div class="card shadow-sm p-4 mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="profile-tabs">
            <a href="#" class="tab-active">Đánh giá sản phẩm</a>
        </div>
    </div>

    <!-- Giữ nguyên phần "Bạn chưa có đánh giá nào" -->
    <?php if (empty($reviews)): ?>
        <div class="text-center">
            <img src="img/no-posts.png" alt="No Posts" class="img-fluid mb-3" style="max-width: 200px;">
            <p>Bạn chưa có đánh giá nào</p>
        </div>
    <?php else: ?>
        <!-- Thêm phần hiển thị danh sách đánh giá ở dưới -->
        <?php foreach ($reviews as $review): ?>
            <div class="review-item mb-4 d-flex align-items-start">
                <!-- Ảnh sản phẩm -->
                <img src="img/<?= htmlspecialchars($review['hinh_san_pham']) ?>" alt="<?= htmlspecialchars($review['ten_san_pham']) ?>" style="width: 80px; height: 80px; object-fit: cover; margin-right: 20px; border-radius: 4px;">

                <div>
                    <!-- Người đánh giá và ngày -->
                    <div class="d-flex align-items-center mb-2">
                        <strong class="mr-2"><?= htmlspecialchars($review['ten_nguoi_danh_gia']) ?></strong>
                        <span class="text-muted small ml-2"><?= date('d/m/Y', strtotime($review['ngay_danh_gia'])) ?></span>
                    </div>

                    <!-- Sao đánh giá -->
                    <div class="rating mb-1">
                        <?php
                        $fullStars = floor($review['so_sao']);
                        $halfStar = ($review['so_sao'] - $fullStars) >= 0.5;
                        for ($i = 1; $i <= 5; $i++) {
                            if ($i <= $fullStars) {
                                echo '<i class="fas fa-star text-warning"></i>';
                            } elseif ($halfStar && $i == $fullStars + 1) {
                                echo '<i class="fas fa-star-half-alt text-warning"></i>';
                            } else {
                                echo '<i class="far fa-star text-warning"></i>';
                            }
                        }
                        ?>
                    </div>

                    <!-- Tên sản phẩm và giá -->
                    <div class="product-name mb-1">
                        <strong><?= htmlspecialchars($review['ten_san_pham']) ?></strong>
                        - <span class="text-danger"><?= number_format($review['gia_ban'], 0, ',', '.') ?> đ</span>
                    </div>

                    <!-- Bình luận -->
                    <div class="review-comment">
                        <?= htmlspecialchars($review['mo_ta']) ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>



        </div>
    </div>
</div>

<?php
    include_once("view/footer.php");
    ?>

