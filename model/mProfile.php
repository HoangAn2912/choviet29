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
                AND trang_thai = 'Đã duyệt' 
                AND trang_thai_ban = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $userId, $trangThaiBan);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }


    public function capNhatThongTin($id, $email, $so_dien_thoai, $dia_chi, $ngay_sinh, $anh_dai_dien = null) {
        // Kiểm tra tuổi
        $dob = new DateTime($ngay_sinh);
        $today = new DateTime();
        $age = $today->diff($dob)->y;
        if ($age < 18) {
            return false;
        }

        $conn = $this->connect();
        if ($anh_dai_dien) {
            $sql = "UPDATE nguoi_dung SET email=?, so_dien_thoai=?, dia_chi=?, ngay_sinh=?, anh_dai_dien=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssi", $email, $so_dien_thoai, $dia_chi, $ngay_sinh, $anh_dai_dien, $id);
        } else {
            $sql = "UPDATE nguoi_dung SET email=?, so_dien_thoai=?, dia_chi=?, ngay_sinh=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssi", $email, $so_dien_thoai, $dia_chi, $ngay_sinh, $id);
        }
        $stmt->execute();
        return true;
    }
    
    public function countSanPhamTheoTrangThai($userId, $trangThaiBan) {
        $conn = $this->connect();
        $sql = "SELECT COUNT(*) as total FROM san_pham WHERE id_nguoi_dung = ? AND trang_thai = 'Đã duyệt' AND trang_thai_ban = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $userId, $trangThaiBan);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['total'] ?? 0;
    }
    
    public function getUserByUsername($username) {
        $conn = $this->connect();
        $cleanUsername = $this->createSlug($username);
        
        $stmt = $conn->prepare("SELECT id FROM nguoi_dung WHERE LOWER(REPLACE(REPLACE(REPLACE(ten_dang_nhap, ' ', ''), 'đ', 'd'), 'Đ', 'D')) = ?");
        $stmt->bind_param("s", $cleanUsername);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            return $user['id'];
        }
        return null;
    }
    
    // Hàm chuyển đổi tên đăng nhập thành slug URL
    public function createSlug($str) {
        $str = trim(mb_strtolower($str, 'UTF-8'));
        
        // Chuyển đổi các ký tự có dấu thành không dấu
        $unicode = array(
            'a'=>'á|à|ả|ã|ạ|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ',
            'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
            'i'=>'í|ì|ỉ|ĩ|ị',
            'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
            'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'y'=>'ý|ỳ|ỷ|ỹ|ỵ',
            'd'=>'đ'
        );
        
        foreach($unicode as $nonUnicode=>$uni) {
            $str = preg_replace("/($uni)/i", $nonUnicode, $str);
        }
        
        // Loại bỏ các ký tự đặc biệt và khoảng trắng
        $str = preg_replace('/[^a-z0-9]/', '', $str);
        
        return $str;
    }
}
?>
