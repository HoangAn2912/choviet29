<?php
session_start();
require_once '../model/mLoginLogout.php';

$model = new mLoginLogout();

$baseUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/project/frontend/';

// Xử lý đăng nhập
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = $model->checkLogin($email, $password);

    if ($user && $user['id_vai_tro'] == 2) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['ten_dang_nhap'];
        header('Location: ' . $baseUrl . 'index.php');
        exit;
    } else {
        header('Location: ' . $baseUrl . 'loginlogout/login.php?error=1');
        exit;
    }
}

// Xử lý đăng xuất (duy nhất 1 lần)
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    header('Location: ' . $baseUrl . 'index.php');
    exit;
}
