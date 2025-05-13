<?php
require_once __DIR__ . '/../model/mLoginLogout.php';

$model = new mLoginLogout();
$baseUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/project/';

// Xử lý đăng nhập
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = md5($_POST['password']); // Mã hoá MD5

    $user = $model->checkLogin($email, $password);

    if ($user) {
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['ten_dang_nhap'];
        $_SESSION['avatar'] = !empty($user['anh_dai_dien']) ? $user['anh_dai_dien'] : 'default-avatar.jpg';
        $_SESSION['role'] = $user['id_vai_tro'];

        // Phân quyền chuyển trang
        if ($user['id_vai_tro'] == 1) {
            header("Location: " . $baseUrl . "ad");
        } elseif ($user['id_vai_tro'] == 2) {
            header("Location: " . $baseUrl . "index.php");
        } else {
            header("Location: index.php?login&toast=" . urlencode("❌ Email không hợp lệ!") . "&type=error");
            exit;
        }
        exit;
    } else {
        header("Location: index.php?login&toast=" . urlencode("❌ Email hoặc mật khẩu không đúng") . "&type=error");
        exit;
    }
}

// Xử lý đăng ký
if (isset($_POST['register'])) {
    $ten_dang_nhap = trim($_POST['ten_dang_nhap']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $repassword = $_POST['repassword'];

    if ($password !== $repassword) {
        header("Location: index.php?signup&toast=" . urlencode("❌ Mật khẩu không khớp") . "&type=error");
    }

    if ($model->checkEmailExists($email)) {
        header("Location: index.php?signup&toast=" . urlencode("❌ Email đã tồn tại") . "&type=error");
    }

    // Thêm tài khoản mới
    $password_md5 = md5($password);
    $ok = $model->registerUser($ten_dang_nhap, $email, $password_md5);

    if ($ok) {
        header("Location: index.php?signup&toast=" . urlencode("✅ Đăng ký thành công! Vui lòng đăng nhập.") . "&type=success");
        exit;
    } else {
        header("Location: index.php?signup&toast=" . urlencode("❌ Đăng ký thất bại!") . "&type=error");
        exit;
    }

    $ok = $model->dayTin($idTin, $idNguoiDung);
        if ($ok) {
            header("Location: index.php?quan-ly-tin&toast=" . urlencode("🚀 Đã đẩy tin thành công!") . "&type=success");
        } else {
            header("Location: index.php?quan-ly-tin&toast=" . urlencode("❌ Có lỗi xảy ra khi đẩy tin!") . "&type=error");
        }
}
?>