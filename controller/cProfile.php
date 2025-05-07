<?php
require_once 'model/mProfile.php';

class cProfile {
    private $model;

    public function __construct() {
        $this->model = new mProfile();
    }

    public function getSanPhamDangHienThi($userId) {
        return $this->model->getSanPhamTheoTrangThai($userId, 'dang_ban');
    }

    public function getSanPhamDaBan($userId) {
        return $this->model->getSanPhamTheoTrangThai($userId, 'da_ban');
    }

    public function capNhatThongTin() {
        if (!isset($_SESSION['user_id'])) return;

        $id = $_SESSION['user_id'];
        $email = $_POST['email'];
        $so_dien_thoai = $_POST['so_dien_thoai'];
        $dia_chi = $_POST['dia_chi'];
        $ngay_tao = $_POST['ngay_tao'];
        $anh_dai_dien = $_FILES['anh_dai_dien']['name'];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header("Location: index.php?thongtin&toast=" . urlencode("❌ Email không hợp lệ") . "&type=error");
            exit;
        }
        if (!preg_match('/^[0-9]{10,11}$/', $so_dien_thoai)) {
            header("Location: index.php?thongtin&toast=" . urlencode("❌ Số điện thoại phải có 10–11 chữ số") . "&type=error");
            exit;
        }
        if (!empty($anh_dai_dien)) {
            $ext = strtolower(pathinfo($anh_dai_dien, PATHINFO_EXTENSION));
            if (!in_array($ext, ['jpg', 'jpeg', 'png'])) {
                header("Location: index.php?thongtin&toast=" . urlencode("❌ Ảnh phải có định dạng .jpg, .jpeg hoặc .png") . "&type=error");
                exit;
            }
        }

        $this->model->capNhatThongTin($id, $email, $so_dien_thoai, $dia_chi, $ngay_tao, $anh_dai_dien);

        header("Location: index.php?thongtin&toast=" . urlencode("✅ Bạn đã cập nhật thông tin thành công!") . "&type=success");
        exit;
    }
}
