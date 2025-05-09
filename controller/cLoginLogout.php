<?php
require_once 'model/mLoginLogout.php';

$model = new mLoginLogout();
$baseUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/project/';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = md5($_POST['password']); // Mã hoá MD5

    $user = $model->checkLogin($email, $password);

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['ten_dang_nhap'];
        $_SESSION['avatar'] = !empty($user['anh_dai_dien']) ? $user['anh_dai_dien'] : 'default-avatar.jpg';
        $_SESSION['role'] = $user['id_vai_tro'];

        // Phân quyền chuyển trang
        if ($user['id_vai_tro'] == 1) {
            // Quản trị
            header("Location: " . $baseUrl . "ad");
        } elseif ($user['id_vai_tro'] == 2) {
            // Người dùng
            header("Location: " . $baseUrl . "index.php");
        } else {
            echo "<script>alert('Tài khoản không hợp lệ');window.location.href='login.php';</script>";
        }
        exit;
    } else {
        echo "<script>alert('Email hoặc mật khẩu không đúng');window.location.href='login.php';</script>";
    }
}

?>