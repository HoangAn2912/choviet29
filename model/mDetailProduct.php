<?php
include_once("mConnect.php");

class mDetailProduct {
    private $conn;

    public function __construct() {
        $p = new Connect();
        $this->conn = $p->connect();
    }

    public function getDetailById($id) {
        $sql = "SELECT 
                        sp.*, 
                        nd.ten_dang_nhap, 
                        nd.anh_dai_dien, 
                        nd.so_dien_thoai,
                        nd.dia_chi,
                        (
                            SELECT COUNT(*) 
                            FROM san_pham 
                            WHERE id_nguoi_dung = nd.id AND trang_thai_ban = 'da_ban'
                        ) AS so_luong_da_ban,
                        (
                            SELECT ROUND(AVG(so_sao), 1) 
                            FROM danh_gia dg 
                            JOIN san_pham sp2 ON dg.id_san_pham = sp2.id 
                            WHERE sp2.id_nguoi_dung = nd.id
                        ) AS diem_danh_gia,
                        (
                            SELECT COUNT(*) 
                            FROM danh_gia dg 
                            JOIN san_pham sp2 ON dg.id_san_pham = sp2.id 
                            WHERE sp2.id_nguoi_dung = nd.id
                        ) AS so_nguoi_danh_gia
                    FROM san_pham sp 
                    JOIN nguoi_dung nd ON sp.id_nguoi_dung = nd.id 
                    WHERE sp.id = ?
                    ";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        if ($data && isset($data['hinh_anh'])) {
            $data['ds_anh'] = array_map('trim', explode(',', $data['hinh_anh']));
        }

        $stmt->close();
        $this->conn->close();

        return $data;
    }
}
