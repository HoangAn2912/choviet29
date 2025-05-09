<?php
require_once 'mConnect.php';

class mLoginLogout extends Connect {
    public function checkLogin($email, $password) {
    $conn = $this->connect();
    $stmt = $conn->prepare("SELECT id, ten_dang_nhap, anh_dai_dien, id_vai_tro FROM nguoi_dung WHERE email = ? AND mat_khau = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

}
?>
