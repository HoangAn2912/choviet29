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
        $stmt = $conn->prepare("INSERT INTO nguoi_dung (ten_dang_nhap, email, mat_khau, id_vai_tro) VALUES (?, ?, ?, 2)");
        $stmt->bind_param("sss", $ten_dang_nhap, $email, $password_md5);
        $ok = $stmt->execute();
        $stmt->close();
        $conn->close();
        return $ok;
    }
}
?>