<?php
session_start();
include_once("frontend/controller/cCategory.php");
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
    <link href="frontend/img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">  

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="frontend/lib/animate/animate.min.css" rel="stylesheet">
    <link href="frontend/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="frontend/css/style.css" rel="stylesheet">
</head>

<body>


    <?php
        if(isset($_GET['shop'])){
            include_once("frontend/view/shop.html");
        } else if(isset($_GET['cart'])){
            include_once("frontend/view/cart.html");
        } else if(isset($_GET['checkout'])){
            include_once("frontend/view/checkout.html");
        } else if(isset($_GET['detail'])){
            include_once("frontend/view/detail.html");
        } else if(isset($_GET['contact'])){
            include_once("frontend/view/contact.html");
        } else if(isset($_GET['login'])){
            include_once("frontend/loginlogout/login.php");
        } else if(isset($_GET['thongtin'])){
            include_once("frontend/view/profile/index.php");
        } else {
            include_once("frontend/index.php");
        }
    ?>

    


</body>

</html>