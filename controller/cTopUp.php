<?php
include_once("model/mTopUp.php");

class cTopUp {
    public function xuLyNopTien($userId) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_ck'])) {
        $noi_dung_ck = trim($_POST['noi_dung_ck']);
        $trang_thai_ck = "Đang chờ duyệt";
        $hinh_anh_ck = '';

        // Xử lý upload ảnh
        if (isset($_FILES['hinh_anh_ck']) && $_FILES['hinh_anh_ck']['error'] == 0) {
            $ext = pathinfo($_FILES['hinh_anh_ck']['name'], PATHINFO_EXTENSION);
            $fileName = 'ck_' . time() . '_' . rand(1000,9999) . '.' . $ext;
            move_uploaded_file($_FILES['hinh_anh_ck']['tmp_name'], 'img/' . $fileName);
            $hinh_anh_ck = $fileName;
        }

        // Lưu vào DB
        $mTopUp = new mTopUp();
        $ok = $mTopUp->insertChuyenKhoan($userId, $noi_dung_ck, $hinh_anh_ck, $trang_thai_ck);

        if ($ok !== false) {
            header("Location: index.php?nap-tien&toast=" . urlencode("✅ Gửi yêu cầu nạp tiền thành công! Đang chờ duyệt.") . "&type=success");
        } else {
            header("Location: index.php?nap-tien&toast=" . urlencode("❌ Gửi yêu cầu thất bại!") . "&type=error");
        }
        exit;
    }
}

    public function getLichSu($userId) {
        $mTopUp = new mTopUp();
        return $mTopUp->getLichSuChuyenKhoan($userId);
    }
}
?>