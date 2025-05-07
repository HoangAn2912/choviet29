<?php
require_once 'mConnect.php';

class mProfile extends Connect {
    public function getUserById($id) {
        $conn = $this->connect();
        $stmt = $conn->prepare("SELECT nguoi_dung.*, taikhoan_chuyentien.id_ck
                                FROM nguoi_dung
                                LEFT JOIN taikhoan_chuyentien 
                                ON nguoi_dung.id = taikhoan_chuyentien.id_nguoi_dung
                                WHERE nguoi_dung.id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getRatingStats($userId) {
        $conn = $this->connect(); // <-- thêm dòng này để lấy kết nối
        $stmt = $conn->prepare("SELECT COUNT(*) as total_reviews, AVG(so_sao) as average_rating FROM danh_gia WHERE id_nguoi_duoc_danh_gia = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getSanPhamTheoTrangThai($userId, $trangThaiBan) {
        $conn = $this->connect();
        $sql = "SELECT id, tieu_de, gia, hinh_anh, ngay_cap_nhat 
                FROM san_pham 
                WHERE id_nguoi_dung = ? 
                  AND trang_thai = 'da_duyet' 
                  AND trang_thai_ban = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $userId, $trangThaiBan);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function capNhatThongTin($id, $email, $so_dien_thoai, $dia_chi, $ngay_tao, $anh_dai_dien = null) {
        $conn = $this->connect();
        if ($anh_dai_dien) {
            $sql = "UPDATE nguoi_dung SET email=?, so_dien_thoai=?, dia_chi=?, ngay_tao=?, anh_dai_dien=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssi", $email, $so_dien_thoai, $dia_chi, $ngay_tao, $anh_dai_dien, $id);
        } else {
            $sql = "UPDATE nguoi_dung SET email=?, so_dien_thoai=?, dia_chi=?, ngay_tao=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssi", $email, $so_dien_thoai, $dia_chi, $ngay_tao, $id);
        }
        $stmt->execute();
    }
    
    
    
}
?>
