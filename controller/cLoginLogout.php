<?php
require_once 'model/mLoginLogout.php';

$model = new mLoginLogout();

$baseUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/project/';

// Xử lý đăng nhập
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = $model->checkLogin($email, $password);

    if ($user && $user['id_vai_tro'] == 2) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['ten_dang_nhap'];
        header("location: index.php");
        header('Location: ' . $baseUrl . 'index.php');
        exit;
    } else {
        // ✅ Sửa đoạn này!
        header('Location: ' . $baseUrl . 'index.php?login=1&error=1');
    }
}
?>