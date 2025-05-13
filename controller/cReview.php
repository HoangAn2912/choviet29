<?php
require_once(__DIR__ . '/../model/mReview.php');

class cReview {
    public function getReviewsBySeller($sellerId) {
        $model = new mReview();
        return $model->getReviewsBySellerId($sellerId);
    }

    public function themDanhGia() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $model = new mReview();
        $idNguoiDanhGia = $_POST['id_nguoi_danh_gia'];
        $idNguoiDuocDanhGia = $_POST['id_nguoi_duoc_danh_gia'];
        $idSanPham = $_POST['id_san_pham'];
        $soSao = $_POST['so_sao'];
        $binhLuan = trim($_POST['binh_luan']);

        // Kiểm tra đã đánh giá chưa
        if ($model->daDanhGia($idNguoiDanhGia, $idNguoiDuocDanhGia, $idSanPham)) {
           header("Location: ../index.php?tin-nhan&to=$idNguoiDuocDanhGia&msg=" . urlencode("Bạn đã đánh giá người này cho sản phẩm này rồi!"));
            exit;
        }

        $ok = $model->themDanhGia($idNguoiDanhGia, $idNguoiDuocDanhGia, $idSanPham, $soSao, $binhLuan);
        header("Location: ../index.php?tin-nhan&to=$idNguoiDuocDanhGia&danhgia=" . ($ok ? "success" : "fail"));
        exit;
        }
    }
    
}
