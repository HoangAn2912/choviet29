<?php
require_once 'model/mLoginLogout.php';

$model = new mLoginLogout();

$baseUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/project/';

// Xử lý đăng nhập
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password = md5($password);
    $user = $model->checkLogin($email, $password);
    echo "<pre>";

    if ($user && $user['id_vai_tro'] == 2) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['ten_dang_nhap'];
        $_SESSION['avatar'] = !empty($user['anh_dai_dien']) ? $user['anh_dai_dien'] : 'default-avatar.jpg';
        // header("location: index.php");
        header('Location: ' . $baseUrl . 'index.php');
        exit;
    } else {
        // ✅ Sửa đoạn này!
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['ho_ten'];
        header("location: ad");
	}
}
?>