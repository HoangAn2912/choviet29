<?php
require_once 'frontend/model/mLoginLogout.php';

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
include_once("frontend/header.php");
?>


<!-- Profile Container -->
<div class="container my-5">
    <div class="row">
        <!-- Left Column: User Info -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm p-3 text-center">
            <?php
                // Xử lý ảnh đại diện
                $avatarPath = '../../img/';
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
                    <p><i class="fas fa-envelope text-primary"></i> <?php echo htmlspecialchars($user['email']); ?></p>
                    <p><i class="fas fa-phone text-primary"></i> <?php echo htmlspecialchars($user['so_dien_thoai']); ?></p>
                    <p><i class="fas fa-map-marker-alt text-primary"></i> <?php echo htmlspecialchars($user['dia_chi']); ?></p>
                    <p><i class="fas fa-calendar-alt text-primary"></i> Ngày tham gia: <?php echo htmlspecialchars($user['ngay_tao']); ?></p>
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
                <a href="#">Đã bán (35)</a>
            </div>
            <a href="../../index.php" class="btn btn-primary">Đăng tin ngay</a>
        </div>
        <div class="text-center">
            <img src="../../img/no-posts.png" alt="No Posts" class="img-fluid mb-3" style="max-width: 200px;">
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
        <div class="text-center">
            <img src="../../img/no-posts.png" alt="No Posts" class="img-fluid mb-3" style="max-width: 200px;">
            <p>Bạn chưa có đánh giá nào</p>
        </div>
    </div>
</div>


        </div>
    </div>
</div>

<?php
    include_once("frontend/footer.php");
    ?>

