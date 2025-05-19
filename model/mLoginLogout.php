<?php
require_once 'mConnect.php';

class mLoginLogout extends Connect {
    public function checkLogin($email, $password) {
        $conn = $this->connect();
        $stmt = $conn->prepare("SELECT id, ten_dang_nhap, anh_dai_dien, id_vai_tro FROM nguoi_dung WHERE email = ? AND mat_khau = ?");
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        $conn->close();
        return $user;
    }

    // Kiểm tra email đã tồn tại
    public function checkEmailExists($email) {
        $conn = $this->connect();
        $stmt = $conn->prepare("SELECT id FROM nguoi_dung WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        $conn->close();
        return $exists;
    }

   // Đăng ký tài khoản mới
    public function registerUser($ten_dang_nhap, $email, $password_md5) {
        $conn = $this->connect();

        // Thêm cả cột trang_thai_hd vào INSERT
        $stmt = $conn->prepare("INSERT INTO nguoi_dung (ten_dang_nhap, email, mat_khau, id_vai_tro, trang_thai_hd) VALUES (?, ?, ?, 2, 1)");
        $stmt->bind_param("sss", $ten_dang_nhap, $email, $password_md5);
        $ok = $stmt->execute();
        $newUserId = $conn->insert_id;
        $stmt->close();

        if ($ok && $newUserId > 0) {
            // Lấy id_ck lớn nhất
            $result = $conn->query("SELECT MAX(id_ck) AS max_ck FROM taikhoan_chuyentien");
            $row = $result->fetch_assoc();
            $next_ck = ($row && $row['max_ck']) ? intval($row['max_ck']) + 1 : 1000;

            // Tạo tài khoản chuyển tiền cho user mới
            $stmt2 = $conn->prepare("INSERT INTO taikhoan_chuyentien (id_ck, id_nguoi_dung, so_du) VALUES (?, ?, 0)");
            $stmt2->bind_param("ii", $next_ck, $newUserId);
            $stmt2->execute();
            $stmt2->close();
        }

        $conn->close();
        return $ok;
    }

}
?>