<?php
require_once 'mConnect.php';

class mReview extends Connect {
    public function getReviewsBySellerId($sellerId) {
        $conn = $this->connect();
        $sql = "SELECT 
                    nguoi_dung.ten_dang_nhap AS ten_nguoi_danh_gia,
                    san_pham.tieu_de AS ten_san_pham,
                    san_pham.gia AS gia_ban,
                    san_pham.hinh_anh AS hinh_san_pham,
                    danh_gia.so_sao,
                    danh_gia.binh_luan AS mo_ta,
                    danh_gia.ngay_tao AS ngay_danh_gia
                FROM danh_gia
                INNER JOIN nguoi_dung ON danh_gia.id_nguoi_danh_gia = nguoi_dung.id
                INNER JOIN san_pham ON danh_gia.id_san_pham = san_pham.id
                WHERE danh_gia.id_nguoi_duoc_danh_gia = ?
                ORDER BY danh_gia.ngay_tao DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $sellerId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $conn->close();
        return $result;
    }
    
    public function themDanhGia($idNguoiDanhGia, $idNguoiDuocDanhGia, $idSanPham, $soSao, $binhLuan) {
        $conn = $this->connect();
        $stmt = $conn->prepare("INSERT INTO danh_gia 
            (id_nguoi_danh_gia, id_nguoi_duoc_danh_gia, id_san_pham, so_sao, binh_luan, ngay_tao)
            VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("iiiis", $idNguoiDanhGia, $idNguoiDuocDanhGia, $idSanPham, $soSao, $binhLuan);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }
    


    public function daDanhGia($idNguoiDanhGia, $idNguoiDuocDanhGia, $idSanPham) {
        $conn = $this->connect();
        $stmt = $conn->prepare("SELECT COUNT(*) FROM danh_gia WHERE id_nguoi_danh_gia = ? AND id_nguoi_duoc_danh_gia = ? AND id_san_pham = ?");
        $stmt->bind_param("iii", $idNguoiDanhGia, $idNguoiDuocDanhGia, $idSanPham);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        $conn->close();
        return $count > 0;
    }
}
?>
