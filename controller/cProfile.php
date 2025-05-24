<?php
require_once 'model/mProfile.php';

class cProfile {
    private $model;

    public function __construct() {
        $this->model = new mProfile();
    }

    public function getSanPhamDangHienThi($userId) {
        return $this->model->getSanPhamTheoTrangThai($userId, 'Đang bán');
    }

    public function getSanPhamDaBan($userId) {
        return $this->model->getSanPhamTheoTrangThai($userId, 'Đã bán');
    }

    public function capNhatThongTin() {
    if (!isset($_SESSION['user_id'])) return;

    $id = $_SESSION['user_id'];
    $email = $_POST['email'];
    $so_dien_thoai = $_POST['so_dien_thoai'];
    $dia_chi = $_POST['dia_chi'];
    $ngay_sinh = $_POST['ngay_sinh'];
    $anh_dai_dien = null;

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: index.php?thongtin&toast=" . urlencode("❌ Email không hợp lệ") . "&type=error");
        exit;
    }
    if (!preg_match('/^[0-9]{10,11}$/', $so_dien_thoai)) {
        header("Location: index.php?thongtin&toast=" . urlencode("❌ Số điện thoại không hợp lệ! Phải có 10–11 chữ số") . "&type=error");
        exit;
    }
    // Kiểm tra tuổi
    $dob = new DateTime($ngay_sinh);
    $today = new DateTime();
    $age = $today->diff($dob)->y;
    if ($age < 18) {
        header("Location: index.php?thongtin&toast=" . urlencode("❌ Ngày sinh không hợp lệ. Bạn phải đủ 18 tuổi trở lên!") . "&type=error");
        exit;
    }

    // Xử lý upload ảnh đại diện nếu có
    if (isset($_FILES['anh_dai_dien']) && $_FILES['anh_dai_dien']['error'] == 0) {
        $ext = strtolower(pathinfo($_FILES['anh_dai_dien']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg', 'jpeg', 'png'])) {
            header("Location: index.php?thongtin&toast=" . urlencode("❌ Ảnh phải có định dạng .jpg, .jpeg hoặc .png") . "&type=error");
            exit;
        }
        $targetDir = "img/";
        $fileName = time() . '_' . uniqid() . '.' . $ext;
        $targetFile = $targetDir . $fileName;
        if (move_uploaded_file($_FILES['anh_dai_dien']['tmp_name'], $targetFile)) {
            $anh_dai_dien = $fileName;
        }
    }

    $this->model->capNhatThongTin($id, $email, $so_dien_thoai, $dia_chi, $ngay_sinh, $anh_dai_dien);

    header("Location: index.php?thongtin&toast=" . urlencode("✅ Bạn đã cập nhật thông tin thành công!") . "&type=success");
    exit;
}

    public function countSanPhamDangHienThi($userId) {
        return $this->model->countSanPhamTheoTrangThai($userId, 'Đang bán');
    }

    public function countSanPhamDaBan($userId) {
        return $this->model->countSanPhamTheoTrangThai($userId, 'Đã bán');
    }
}
