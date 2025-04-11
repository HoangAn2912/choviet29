<?php
error_reporting(0);
include_once "controller/cCategory.php";

$cCategory = new cCategory();
$data = $cCategory->index();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Chợ Việt - Nơi trao đổi hàng hóa</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">  

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/profile.css" rel="stylesheet">
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
                            <a href="index.php" class="nav-item nav-link active">Home</a>
                            <a href="?shop" class="nav-item nav-link">Shop</a>
                            <a href="?detail" class="nav-item nav-link">Shop Detail</a>
                            <div class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Pages <i class="fa fa-angle-down mt-1"></i></a>
                                <div class="dropdown-menu bg-primary rounded-0 border-0 m-0">
                                    <a href="?cart" class="dropdown-item">Shopping Cart</a>
                                    <a href="?checkout" class="dropdown-item">Checkout</a>
                                </div>
                            </div>
                            <a href="?contact" class="nav-item nav-link">Contact</a>
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
                                            <a class="dropdown-item" href="?thongtin">Quản lý thông tin</a>
                                            <a class="dropdown-item" href="?action=logout">Đăng xuất</a>
                                        <?php else: ?>
                                            <a class="dropdown-item" href="?login">Đăng nhập</a>
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

