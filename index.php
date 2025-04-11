<?php
session_start();
//xử lý đăng xuất
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    header('Location: index.php');
    exit;
}
include_once("controller/cCategory.php");
$p = new cCategory();
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Chợ Đồ Cũ - Nơi trao đổi hàng hóa</title>
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

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>


    <?php
    
        if(isset($_GET['shop'])){
            include_once("view/shop.html");
        } else if(isset($_GET['cart'])){
            include_once("view/cart.html");
        } else if(isset($_GET['checkout'])){
            include_once("view/checkout.html");
        } else if(isset($_GET['detail'])){
            include_once("view/detail.html");
        } else if(isset($_GET['contact'])){
            include_once("view/contact.html");
        } else if(isset($_GET['login'])){
            include_once("loginlogout/login.php");
        } else if(isset($_GET['thongtin'])){
            include_once("view/profile/index.php");
        } else {
            include_once("view/index.php");
        }
    ?>

    


</body>

</html>