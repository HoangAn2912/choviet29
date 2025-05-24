<?php
include_once "mConnect.php";

class mCategory {
    private $conn;

    public function __construct() {
        $p = new Connect();
        $this->conn = $p->connect();
    }

    public function layDanhMuc() {
        $sql = "
            SELECT 
                cha.id_loai_san_pham_cha AS id_cha,
                cha.ten_loai_san_pham_cha AS ten_cha,
                con.id AS id_con,
                con.ten_loai_san_pham AS ten_con
            FROM loai_san_pham_cha cha
            LEFT JOIN loai_san_pham con ON cha.id_loai_san_pham_cha = con.id_loai_san_pham_cha
            ORDER BY cha.id_loai_san_pham_cha, con.id
        ";

        $result = $this->conn->query($sql);
        $data = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }
// hiển thị sản phẩm khi đang trạng thái đang bán và tìm được trên danh mục
    public function getProductsByCategoryId($id_loai) {
        $sql = "SELECT * FROM san_pham WHERE id_loai_san_pham = ? AND trang_thai_ban = 'Đang bán'";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_loai);
        $stmt->execute();
        $result = $stmt->get_result();
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        return $products;
    }

    public function layDanhMucVaSoLuong() {
        $sql = "
            SELECT 
                con.id AS id_loai,
                con.ten_loai_san_pham,
                COUNT(sp.id) AS so_luong
            FROM loai_san_pham con
            LEFT JOIN san_pham sp ON con.id = sp.id_loai_san_pham AND sp.trang_thai_ban = 'Đang bán'
            WHERE con.ten_loai_san_pham NOT LIKE 'Khác'
            GROUP BY con.id, con.ten_loai_san_pham
            ORDER BY con.ten_loai_san_pham ASC
        ";
    
        $result = $this->conn->query($sql);
        $data = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function getUserById($id) {
        $sql = "SELECT id, ten_dang_nhap, anh_dai_dien FROM nguoi_dung WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    
}