<?php
require_once 'mConnect.php';

class mReview extends Connect {
    public function getReviewsBySellerId($sellerId) {
        $conn = $this->connect();
        $sql = "
    SELECT 
        nguoi_dung.ten_dang_nhap AS ten_nguoi_danh_gia,
        san_pham.ten AS ten_san_pham,
        san_pham.gia_ban,
        danh_gia.so_sao,
        danh_gia.mo_ta
    FROM danh_gia
    INNER JOIN nguoi_dung ON danh_gia.id_nguoi_danh_gia = nguoi_dung.id
    INNER JOIN san_pham ON danh_gia.id_san_pham = san_pham.id
    WHERE san_pham.id_nguoi_ban = ? 
      AND san_pham.trang_thai_ban = 'da_ban'
";


        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $sellerId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
?>
