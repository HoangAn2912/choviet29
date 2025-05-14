<?php
include_once "mConnect.php";

class mProduct {
    private $conn;

    public function __construct() {
        $p = new Connect();
        $this->conn = $p->connect();
    }

    public function getSanPhamMoiNhat($limit = 100) {
        $sql = "SELECT * FROM san_pham 
            WHERE trang_thai_ban = 'Đang bán' AND trang_thai = 'Đã duyệt'
            ORDER BY ngay_cap_nhat DESC, ngay_tao DESC
            LIMIT ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];

        while ($row = $result->fetch_assoc()) {
            // Nếu có nhiều ảnh, tách lấy ảnh đầu tiên
            if (!empty($row['hinh_anh'])) {
                $dsAnh = array_map('trim', explode(',', $row['hinh_anh']));
                $row['anh_dau'] = $dsAnh[0] ?? ''; // ảnh đầu tiên
            } else {
                $row['anh_dau'] = '';
            }
            $data[] = $row;
        }
        return $data;
    }

    public function tinhThoiGian($ngay_tao) {
        $now = new DateTime();
        $created = new DateTime($ngay_tao);
        $diff = $now->diff($created);
    
        if ($diff->days == 0 && $diff->h == 0 && $diff->i < 60) return $diff->i . " phút trước";
        if ($diff->days == 0 && $diff->h < 24) return $diff->h . " giờ trước";
        if ($diff->days == 1) return "Hôm qua";
        if ($diff->days <= 6) return $diff->days . " ngày trước";
        if ($diff->days <= 30) return "Tuần trước";
        return "Tháng trước";
    }


    public function getSanPhamById($id) {
        $sql = "SELECT * FROM san_pham WHERE id = ? AND trang_thai = 'Đã duyệt'";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
    
        // xử lý chuỗi ảnh thành mảng
        if ($data && isset($data['hinh_anh'])) {
            $data['ds_anh'] = array_map('trim', explode(',', $data['hinh_anh']));
        }
    
        return $data;
    }
    
    public function searchProducts($keyword) {
        $sql = "SELECT * FROM san_pham WHERE trang_thai_ban = 'Đang bán' AND tieu_de LIKE ?";
        $stmt = $this->conn->prepare($sql);
        $likeKeyword = '%' . $keyword . '%';
        $stmt->bind_param("s", $likeKeyword);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $data = [];
        while ($row = $result->fetch_assoc()) {
            if (!empty($row['hinh_anh'])) {
                $dsAnh = array_map('trim', explode(',', $row['hinh_anh']));
                $row['anh_dau'] = $dsAnh[0] ?? '';
            } else {
                $row['anh_dau'] = '';
            }
            $data[] = $row;
        }
        return $data;
    }
    
    
}
?>
