<?php
include_once "mConnect.php";
class mTopUp {
    private $conn;
    public function __construct() {
        $db = new Connect();
        $this->conn = $db->connect();
    }
    public function insertChuyenKhoan($userId, $noi_dung_ck, $hinh_anh_ck, $trang_thai_ck) {
        $sql = "INSERT INTO lich_su_chuyen_khoan (id_nguoi_dung, noi_dung_ck, hinh_anh_ck, trang_thai_ck, ngay_tao) VALUES (?, ?, ?, ?, NOW())";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("isss", $userId, $noi_dung_ck, $hinh_anh_ck, $trang_thai_ck);
        $stmt->execute();
        $stmt->close();
    }
    public function getLichSuChuyenKhoan($userId) {
        $sql = "SELECT * FROM lich_su_chuyen_khoan WHERE id_nguoi_dung = ? ORDER BY ngay_tao DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];
        while ($row = $result->fetch_assoc()) $data[] = $row;
        $stmt->close();
        return $data;
    }
}
?>