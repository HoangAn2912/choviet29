<?php
session_start();
require_once '../../model/mLoginLogout.php';

$baseUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/project/frontend/';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../loginlogout/login.php');
    exit;
}

$model = new mLoginLogout();
$user = $model->getUserById($_SESSION['user_id']);
$ratingStats = $model->getRatingStats($_SESSION['user_id']);
$totalReviews = $ratingStats['total_reviews'] ?? 0;
$averageRating = number_format($ratingStats['average_rating'] ?? 0, 1);



?>


<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Trang cá nhân - Chợ Việt</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Favicon -->
    <link href="../../img/favicon.ico" rel="icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- CSS Libraries -->
    <link href="../../lib/animate/animate.min.css" rel="stylesheet">
    <link href="../../lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Main CSS -->
    <link href="../../css/style.css" rel="stylesheet">

    <!-- Profile Page CSS -->
    <link href="../../css/profile.css" rel="stylesheet">
</head>

<body>
 <!-- Topbar Start -->
 <div class="container-fluid">
        <div class="row align-items-center bg-light py-3 px-xl-5 d-none d-lg-flex">
            <div class="col-lg-4">
                <a href="" class="text-decoration-none">
                    <span class="h1 text-uppercase text-primary bg-dark px-2">Chợ</span>
                    <span class="h1 text-uppercase text-dark bg-primary px-2 ml-n1">Việt</span>
                </a>
            </div>
            <div class="col-lg-4 col-6 text-left">
                <form action="">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search for products">
                        <div class="input-group-append">
                            <span class="input-group-text bg-transparent text-primary">
                                <i class="fa fa-search"></i>
                            </span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-lg-4 col-6 text-right">
                <p class="m-0">Hotline hỗ trợ:</p>
                <h5 class="m-0">+012 345 6789</h5>
            </div>
        </div>
    </div>
    <!-- Topbar End -->


    <!-- Navbar Start -->
    <div class="container-fluid bg-dark mb-30">
        <div class="row px-xl-5">
            <div class="col-lg-3 d-none d-lg-block">
                <a class="btn d-flex align-items-center justify-content-between bg-primary w-100" data-toggle="collapse" href="#navbar-vertical" style="height: 65px; padding: 0 30px;">
                    <h6 class="text-dark m-0"><i class="fa fa-bars mr-2"></i>Danh mục</h6>
                    <i class="fa fa-angle-down text-dark"></i>
                </a>
                <nav class="collapse position-absolute navbar navbar-vertical navbar-light align-items-start p-0 bg-light" id="navbar-vertical" style="width: calc(100% - 30px); z-index: 999;">
                <div class="navbar-nav w-100">
                    <?php if (!empty($data)) : ?>
                        <?php foreach ($data as $parent) : ?>
                            <?php if (!empty($parent['con'])) : ?>
                                <!-- Có danh mục con -->
                                <div class="nav-item dropdown dropright">
                                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                                        <?= htmlspecialchars($parent['ten_cha']) ?>
                                        <i class="fa fa-angle-right float-right mt-1"></i>
                                    </a>
                                    <div class="dropdown-menu position-absolute rounded-0 border-0 m-0">
                                        <?php foreach ($parent['con'] as $child) : ?>
                                            <a href="category.php?id=<?= $child['id_con'] ?>" class="dropdown-item">
                                                <?= htmlspecialchars($child['ten_con']) ?>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php else : ?>
                                <!-- Không có danh mục con -->
                                <a href="category.php?id=<?= $parent['id_cha'] ?>" class="nav-item nav-link">
                                    <?= htmlspecialchars($parent['ten_cha']) ?>
                                </a>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                </nav>
            </div>
            <div class="col-lg-9">
                <nav class="navbar navbar-expand-lg bg-dark navbar-dark py-3 py-lg-0 px-0">
                    <a href="" class="text-decoration-none d-block d-lg-none">
                        <span class="h1 text-uppercase text-dark bg-light px-2">Chợ</span>
                        <span class="h1 text-uppercase text-light bg-primary px-2 ml-n1">Việt</span>
                    </a>
                    <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                        <div class="navbar-nav mr-auto py-0">
                            <a href="index.html" class="nav-item nav-link active">Home</a>
                            <a href="shop.html" class="nav-item nav-link">Shop</a>
                            <a href="detail.html" class="nav-item nav-link">Shop Detail</a>
                            <div class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Pages <i class="fa fa-angle-down mt-1"></i></a>
                                <div class="dropdown-menu bg-primary rounded-0 border-0 m-0">
                                    <a href="cart.html" class="dropdown-item">Shopping Cart</a>
                                    <a href="checkout.html" class="dropdown-item">Checkout</a>
                                </div>
                            </div>
                            <a href="contact.html" class="nav-item nav-link">Contact</a>
                        </div>

                            <div class="navbar-nav ml-auto py-0 d-none d-lg-block">
                                <div class="btn-group">
                                <button type="button" class="btn px-0 dropdown-toggle d-flex align-items-center" style="gap: 4px; line-height: 1; font-size: 18px; font-weight: 400; color: white; background: none; border: none;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php if (isset($_SESSION['user_name'])): ?>
                                        <span style="color: white; font-weight: 400; text-decoration: none; line-height: 1;"> <?php echo htmlspecialchars($_SESSION['user_name']); ?> </span>
                                    <?php endif; ?>
                                    <i class="fas fa-user text-primary" style="font-size: 18px;"></i>
                                </button>

                                    <div class="dropdown-menu dropdown-menu-right">
                                        <?php if (isset($_SESSION['user_id'])): ?>
                                            <a class="dropdown-item" href="index.php">Quản lý thông tin</a>
                                            <a class="dropdown-item" href="<?php echo $baseUrl; ?>controller/cLoginLogout.php?action=logout">Đăng xuất</a>
                                        <?php else: ?>
                                            <a class="dropdown-item" href="loginlogout/login.php">Đăng nhập</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                    </div>
                </nav>
            </div>
        </div>
    </div>
    <!-- Navbar End -->

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

    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-secondary mt-5 pt-5">
        <div class="row px-xl-5 pt-5">
            <div class="col-lg-4 col-md-12 mb-5 pr-3 pr-xl-5">
                <h5 class="text-secondary text-uppercase mb-4">Get In Touch</h5>
                <p class="mb-4">No dolore ipsum accusam no lorem. Invidunt sed clita kasd clita et et dolor sed dolor. Rebum tempor no vero est magna amet no</p>
                <p class="mb-2"><i class="fa fa-map-marker-alt text-primary mr-3"></i>123 Street, New York, USA</p>
                <p class="mb-2"><i class="fa fa-envelope text-primary mr-3"></i>info@example.com</p>
                <p class="mb-0"><i class="fa fa-phone-alt text-primary mr-3"></i>+012 345 67890</p>
            </div>
            <div class="col-lg-8 col-md-12">
                <div class="row">
                    <div class="col-md-4 mb-5">
                        <h5 class="text-secondary text-uppercase mb-4">Quick Shop</h5>
                        <div class="d-flex flex-column justify-content-start">
                            <a class="text-secondary mb-2" href="#"><i class="fa fa-angle-right mr-2"></i>Home</a>
                            <a class="text-secondary mb-2" href="#"><i class="fa fa-angle-right mr-2"></i>Our Shop</a>
                            <a class="text-secondary mb-2" href="#"><i class="fa fa-angle-right mr-2"></i>Shop Detail</a>
                            <a class="text-secondary mb-2" href="#"><i class="fa fa-angle-right mr-2"></i>Shopping Cart</a>
                            <a class="text-secondary mb-2" href="#"><i class="fa fa-angle-right mr-2"></i>Checkout</a>
                            <a class="text-secondary" href="#"><i class="fa fa-angle-right mr-2"></i>Contact Us</a>
                        </div>
                    </div>
                    <div class="col-md-4 mb-5">
                        <h5 class="text-secondary text-uppercase mb-4">My Account</h5>
                        <div class="d-flex flex-column justify-content-start">
                            <a class="text-secondary mb-2" href="#"><i class="fa fa-angle-right mr-2"></i>Home</a>
                            <a class="text-secondary mb-2" href="#"><i class="fa fa-angle-right mr-2"></i>Our Shop</a>
                            <a class="text-secondary mb-2" href="#"><i class="fa fa-angle-right mr-2"></i>Shop Detail</a>
                            <a class="text-secondary mb-2" href="#"><i class="fa fa-angle-right mr-2"></i>Shopping Cart</a>
                            <a class="text-secondary mb-2" href="#"><i class="fa fa-angle-right mr-2"></i>Checkout</a>
                            <a class="text-secondary" href="#"><i class="fa fa-angle-right mr-2"></i>Contact Us</a>
                        </div>
                    </div>
                    <div class="col-md-4 mb-5">
                        <h5 class="text-secondary text-uppercase mb-4">Newsletter</h5>
                        <p>Duo stet tempor ipsum sit amet magna ipsum tempor est</p>
                        <form action="">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Your Email Address">
                                <div class="input-group-append">
                                    <button class="btn btn-primary">Sign Up</button>
                                </div>
                            </div>
                        </form>
                        <h6 class="text-secondary text-uppercase mt-4 mb-3">Follow Us</h6>
                        <div class="d-flex">
                            <a class="btn btn-primary btn-square mr-2" href="#"><i class="fab fa-twitter"></i></a>
                            <a class="btn btn-primary btn-square mr-2" href="#"><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-primary btn-square mr-2" href="#"><i class="fab fa-linkedin-in"></i></a>
                            <a class="btn btn-primary btn-square" href="#"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row border-top mx-xl-5 py-4" style="border-color: rgba(256, 256, 256, .1) !important;">
            <div class="col-md-6 px-xl-0">
                <p class="mb-md-0 text-center text-md-left text-secondary">
                    &copy; <a class="text-primary" href="#">Domain</a>. All Rights Reserved. Designed
                    by
                    <a class="text-primary" href="https://htmlcodex.com">HTML Codex</a>
                </p>
            </div>
            <div class="col-md-6 px-xl-0 text-center text-md-right">
                <img class="img-fluid" src="img/payments.png" alt="">
            </div>
        </div>
    </div>
    <!-- Footer End -->

<!-- Back to Top -->
<a href="#" class="btn btn-primary back-to-top"><i class="fa fa-angle-double-up"></i></a>

<!-- JS Libraries -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
<script src="../../lib/easing/easing.min.js"></script>
<script src="../../lib/owlcarousel/owl.carousel.min.js"></script>
<script src="../../js/main.js"></script>
</body>
</html>
