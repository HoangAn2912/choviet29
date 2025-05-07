<?php
include_once "mConnect.php";

class mPost {
    private $conn;

    public function __construct() {
        $db = new Connect();
        $this->conn = $db->connect();
    }

    public function insertSanPham($tieuDe, $gia, $moTa, $hinhAnh, $idNguoiDung, $idLoaiSanPham) {
        $ngayTao = date('Y-m-d H:i:s');
        $trangThai = 'cho_duyet';
        $trangThaiBan = 'dang_ban';
    
        // Bước 1: Đếm số lượng bài đăng đã có
        $sqlCount = "SELECT COUNT(*) as so_luong FROM san_pham WHERE id_nguoi_dung = ?";
        $stmtCount = $this->conn->prepare($sqlCount);
        $stmtCount->bind_param("i", $idNguoiDung);
        $stmtCount->execute();
        $resultCount = $stmtCount->get_result();
        $rowCount = $resultCount->fetch_assoc();
        $soLuong = (int)$rowCount['so_luong'];
        $stmtCount->close();
    
        // Bước 2: Nếu đã có từ 3 bài trở lên => trừ phí và lưu lịch sử
        if ($soLuong >= 3) {
            // Trừ số dư trong tài khoản
            $phiDangBai = 11000;
    
            // Kiểm tra số dư hiện tại
            $sqlCheck = "SELECT so_du FROM taikhoan_chuyentien WHERE id_nguoi_dung = ?";
            $stmtCheck = $this->conn->prepare($sqlCheck);
            $stmtCheck->bind_param("i", $idNguoiDung);
            $stmtCheck->execute();
            $resultCheck = $stmtCheck->get_result();
            $rowCheck = $resultCheck->fetch_assoc();
            $soDuHienTai = (int)$rowCheck['so_du'];
            $stmtCheck->close();
    
            if ($soDuHienTai < $phiDangBai) {
                return false; // Không đủ tiền
            }
    
            // Cập nhật số dư
            $sqlUpdate = "UPDATE taikhoan_chuyentien SET so_du = so_du - ? WHERE id_nguoi_dung = ?";
            $stmtUpdate = $this->conn->prepare($sqlUpdate);
            $stmtUpdate->bind_param("ii", $phiDangBai, $idNguoiDung);
            $stmtUpdate->execute();
            $stmtUpdate->close();
        }
    
        // Bước 3: Thêm sản phẩm mới
        $sqlInsert = "INSERT INTO san_pham (tieu_de, gia, mo_ta, hinh_anh, ngay_tao, trang_thai, trang_thai_ban, id_nguoi_dung, id_loai_san_pham) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmtInsert = $this->conn->prepare($sqlInsert);
        $stmtInsert->bind_param("sdssssssi", $tieuDe, $gia, $moTa, $hinhAnh, $ngayTao, $trangThai, $trangThaiBan, $idNguoiDung, $idLoaiSanPham);
        $result = $stmtInsert->execute();
        $idSanPhamMoi = $stmtInsert->insert_id;
        $stmtInsert->close();
    
        // Bước 4: Ghi vào lịch sử phí nếu đã trừ tiền
        if ($soLuong >= 3 && $result) {
            $sqlLichSu = "INSERT INTO lich_su_phi_dang_bai (id_san_pham, id_nguoi_dung, so_tien, ngay_tao) 
                          VALUES (?, ?, ?, CURDATE())";
            $stmtLichSu = $this->conn->prepare($sqlLichSu);
            $stmtLichSu->bind_param("iid", $idSanPhamMoi, $idNguoiDung, $phiDangBai);
            $stmtLichSu->execute();
            $stmtLichSu->close();
        }
    
        return $result;
    }
    
    public function layTatCaTinDangTheoNguoiDung($userId) {
        $sql = "SELECT sp.*, tk.so_du 
                FROM san_pham sp
                INNER JOIN nguoi_dung nd ON sp.id_nguoi_dung = nd.id 
                INNER JOIN taikhoan_chuyentien tk ON nd.id = tk.id_nguoi_dung 
                WHERE sp.id_nguoi_dung = ?
                ORDER BY sp.ngay_cap_nhat DESC";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $posts = [];
    
        while ($row = $result->fetch_assoc()) {
            $row['thoi_gian_cu_the'] = $this->tinhThoiGian($row['ngay_cap_nhat']);
            $posts[] = $row;
        }
    
        return $posts;
    }
    
    public function tinhThoiGian($ngay_cap_nhat) {
        $now = new DateTime();
        $created = new DateTime($ngay_cap_nhat);
        $diff = $now->diff($created);
    
        if ($diff->days == 0 && $diff->h == 0 && $diff->i < 60) return $diff->i . " phút trước";
        if ($diff->days == 0 && $diff->h < 24) return $diff->h . " giờ trước";
        if ($diff->days == 1) return "Hôm qua";
        if ($diff->days <= 6) return $diff->days . " ngày trước";
        if ($diff->days <= 30) return "Tuần trước";
        return "Tháng trước";
    }

    public function demSoLuongTheoTrangThai($userId) {
        $sql = "SELECT 
                    SUM(CASE WHEN sp.trang_thai_ban = 'dang_ban' AND sp.trang_thai = 'da_duyet' THEN 1 ELSE 0 END) AS dang_ban,
                    SUM(CASE WHEN sp.trang_thai_ban = 'da_ban' THEN 1 ELSE 0 END) AS da_ban,
                    SUM(CASE WHEN sp.trang_thai_ban = 'da_an' THEN 1 ELSE 0 END) AS da_an,
                    SUM(CASE WHEN sp.trang_thai = 'cho_duyet' THEN 1 ELSE 0 END) AS cho_duyet,
                    SUM(CASE WHEN sp.trang_thai = 'tu_choi' THEN 1 ELSE 0 END) AS tu_choi
                FROM san_pham sp
                WHERE sp.id_nguoi_dung = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function layThongTinNguoiDung($userId) {
        $sql = "SELECT nd.anh_dai_dien, nd.ten_dang_nhap, tk.so_du, nd.dia_chi
                FROM nguoi_dung nd
                INNER JOIN taikhoan_chuyentien tk ON nd.id = tk.id_nguoi_dung
                WHERE nd.id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function demSoLuongTin($userId) {
        $sql = "SELECT COUNT(*) as count FROM san_pham WHERE id_nguoi_dung = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        return intval($res['count']);
    }
    
    public function updateTrangThaiBan($idTin, $trangThaiBanMoi) {
        $sql = "UPDATE san_pham SET trang_thai_ban = ?, ngay_cap_nhat = NOW() WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $trangThaiBanMoi, $idTin);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function laySanPhamTheoId($id) {
        $sql = "SELECT * FROM san_pham WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_assoc();
    }
    
    public function capNhatSanPham($id, $tieuDe, $gia, $moTa, $hinhAnh, $idLoaiSanPham, $idNguoiDung) {
        $sql = "UPDATE san_pham SET 
                        tieu_de = ?, 
                        gia = ?, 
                        mo_ta = ?, 
                        hinh_anh = ?, 
                        id_loai_san_pham = ?, 
                        trang_thai = 'cho_duyet', 
                        ngay_cap_nhat = NOW() 
                    WHERE id = ? AND id_nguoi_dung = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sdssiii", $tieuDe, $gia, $moTa, $hinhAnh, $idLoaiSanPham, $id, $idNguoiDung);
        return $stmt->execute();
    }

    public function laySoDuNguoiDung($idNguoiDung) {
        $sql = "SELECT so_du FROM taikhoan_chuyentien WHERE id_nguoi_dung = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $idNguoiDung);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        return intval($res['so_du'] ?? 0);
    }
    
    public function dayTin($idTin, $idNguoiDung) {
        // 1. Trừ tiền
        $stmt = $this->conn->prepare("UPDATE taikhoan_chuyentien SET so_du = so_du - 11000 WHERE id_nguoi_dung = ? AND so_du >= 11000");
        $stmt->bind_param("i", $idNguoiDung);
        $stmt->execute();
        if ($stmt->affected_rows <= 0) return false;
        $stmt->close();
    
        // 2. Ghi lịch sử đẩy tin
        $stmt2 = $this->conn->prepare("INSERT INTO lich_su_day_tin (id_san_pham, id_nguoi_dung, so_tien, thoi_gian_day) VALUES (?, ?, 11000, NOW())");
        $stmt2->bind_param("ii", $idTin, $idNguoiDung);
        $stmt2->execute();
        $stmt2->close();

        // 3. Cập nhật trạng thái bài viết => cho_duyet
        $stmt3 = $this->conn->prepare("UPDATE san_pham SET trang_thai = 'cho_duyet', ngay_cap_nhat = NOW() WHERE id = ? AND id_nguoi_dung = ?");
        $stmt3->bind_param("ii", $idTin, $idNguoiDung);
        $stmt3->execute();
        $stmt3->close();
    
        return true;
    }
    
    
      

}
?>
