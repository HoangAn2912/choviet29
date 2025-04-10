<?php
require_once 'mConnect.php';

class mLoginLogout extends Connect {
    public function checkLogin($email, $password) {
        $conn = $this->connect();
        $stmt = $conn->prepare("SELECT * FROM nguoi_dung WHERE email = ? AND mat_khau = ?");
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getUserById($id) {
        $conn = $this->connect();
        $stmt = $conn->prepare("SELECT * FROM nguoi_dung WHERE id = ?");
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
    
}
?>
