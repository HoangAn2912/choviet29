<?php
require_once 'mConnect.php';

/**
 * Class quản lý đăng nhập, đăng ký và xác thực OTP
 */
class mLoginLogout extends Connect {
    
    /**
     * Hàm connect công khai để controller có thể sử dụng
     */
    public function connect() {
        return parent::connect();
    }
    
    // ==================== PHƯƠNG THỨC ĐĂNG NHẬP ====================
    
    /**
     * Kiểm tra đăng nhập
     * 
     * @param string $email Email người dùng
     * @param string $password Mật khẩu đã mã hóa
     * @return array|false Thông tin người dùng hoặc false nếu thất bại
     */
    public function checkLogin($email, $password) {
        $conn = $this->connect();
        
        try {
        $stmt = $conn->prepare("SELECT id, ten_dang_nhap, anh_dai_dien, id_vai_tro FROM nguoi_dung WHERE email = ? AND mat_khau = ?");
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
            
            return $user;
        } catch (Exception $e) {
            error_log("Lỗi đăng nhập: " . $e->getMessage());
            return false;
        } finally {
        $conn->close();
        }
    }
    
    // ==================== PHƯƠNG THỨC KIỂM TRA TỒN TẠI ====================
    
    /**
     * Kiểm tra email đã tồn tại
     * 
     * @param string $email Email cần kiểm tra
     * @return bool True nếu email đã tồn tại
     */
    public function checkEmailExists($email) {
        return $this->checkContactExists('email', $email);
    }
    
    /**
     * Kiểm tra tên đăng nhập đã tồn tại
     * 
     * @param string $ten_dang_nhap Tên đăng nhập cần kiểm tra
     * @return bool True nếu tên đăng nhập đã tồn tại
     */
    public function checkUsernameExists($ten_dang_nhap) {
        $conn = $this->connect();
        
        try {
            $stmt = $conn->prepare("SELECT id FROM nguoi_dung WHERE ten_dang_nhap = ?");
            $stmt->bind_param("s", $ten_dang_nhap);
            $stmt->execute();
            $stmt->store_result();
            $exists = $stmt->num_rows > 0;
            $stmt->close();
            
            return $exists;
        } catch (Exception $e) {
            error_log("Lỗi kiểm tra tên đăng nhập: " . $e->getMessage());
            return false;
        } finally {
            $conn->close();
        }
    }
    
    /**
     * Kiểm tra tên đăng nhập hợp lệ
     * 
     * @param string $ten_dang_nhap Tên đăng nhập cần kiểm tra
     * @return array Kết quả validation
     */
    public function validateUsername($ten_dang_nhap) {
        $errors = [];
        
        // Kiểm tra độ dài
        if (strlen($ten_dang_nhap) < 3) {
            $errors[] = 'Tên đăng nhập phải có ít nhất 3 ký tự';
        }
        
        if (strlen($ten_dang_nhap) > 20) {
            $errors[] = 'Tên đăng nhập không được quá 20 ký tự';
        }
        
        // Kiểm tra khoảng trắng
        if (preg_match('/\s/', $ten_dang_nhap)) {
            $errors[] = 'Tên đăng nhập không được chứa khoảng trắng';
        }
        
        // Kiểm tra dấu của chữ cái (chỉ cho phép chữ cái, số, dấu gạch dưới)
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $ten_dang_nhap)) {
            $errors[] = 'Tên đăng nhập chỉ được chứa chữ cái, số và dấu gạch dưới (_)';
        }
        
        // Kiểm tra ký tự đầu tiên
        if (!preg_match('/^[a-zA-Z]/', $ten_dang_nhap)) {
            $errors[] = 'Tên đăng nhập phải bắt đầu bằng chữ cái';
        }
        
        // Kiểm tra trùng lặp
        if ($this->checkUsernameExists($ten_dang_nhap)) {
            $errors[] = 'Tên đăng nhập đã tồn tại';
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'message' => empty($errors) ? 'Tên đăng nhập hợp lệ' : implode(', ', $errors)
        ];
    }
    
    /**
     * Kiểm tra mật khẩu hợp lệ
     * 
     * @param string $password Mật khẩu cần kiểm tra
     * @return array Kết quả validation
     */
    public function validatePassword($password) {
        $errors = [];
        
        // Kiểm tra độ dài
        if (strlen($password) < 6) {
            $errors[] = 'Mật khẩu phải có ít nhất 6 ký tự';
        }
        
        if (strlen($password) > 50) {
            $errors[] = 'Mật khẩu không được quá 50 ký tự';
        }
        
        // Kiểm tra khoảng trắng
        if (preg_match('/\s/', $password)) {
            $errors[] = 'Mật khẩu không được chứa khoảng trắng';
        }
        
        // Kiểm tra dấu của chữ cái (chỉ cho phép chữ cái, số, ký tự đặc biệt)
        if (!preg_match('/^[a-zA-Z0-9!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?]+$/', $password)) {
            $errors[] = 'Mật khẩu chỉ được chứa chữ cái, số và ký tự đặc biệt (!@#$%^&*()_+-=[]{};\':"\\|,.<>/?/)';
        }
        
        // Kiểm tra có ít nhất 1 chữ cái
        if (!preg_match('/[a-zA-Z]/', $password)) {
            $errors[] = 'Mật khẩu phải chứa ít nhất 1 chữ cái';
        }
        
        // Kiểm tra có ít nhất 1 số
        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = 'Mật khẩu phải chứa ít nhất 1 số';
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'message' => empty($errors) ? 'Mật khẩu hợp lệ' : implode(', ', $errors)
        ];
    }
    
    /**
     * Kiểm tra thông tin liên hệ đã tồn tại (email hoặc số điện thoại)
     * 
     * @param string $field Tên trường (email hoặc so_dien_thoai)
     * @param string $value Giá trị cần kiểm tra
     * @return bool True nếu đã tồn tại
     */
    private function checkContactExists($field, $value) {
        $conn = $this->connect();
        
        try {
            $stmt = $conn->prepare("SELECT id FROM nguoi_dung WHERE $field = ?");
            $stmt->bind_param("s", $value);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
            
            return $exists;
        } catch (Exception $e) {
            error_log("Lỗi kiểm tra $field: " . $e->getMessage());
            return false;
        } finally {
        $conn->close();
        }
    }
    
    // ==================== PHƯƠNG THỨC ĐĂNG KÝ ====================
    
    /**
     * Đăng ký tài khoản mới
     * 
     * @param string $ten_dang_nhap Tên đăng nhập
     * @param string $email Email (có thể rỗng)
     * @param string $so_dien_thoai Số điện thoại (có thể rỗng)
     * @param string $password_md5 Mật khẩu đã mã hóa
     * @param int $is_verified Trạng thái xác thực (0: chưa xác thực, 1: đã xác thực)
     * @return bool True nếu đăng ký thành công
     */
    public function registerUser($ten_dang_nhap, $email, $so_dien_thoai, $password_md5, $is_verified = 0) {
        $conn = $this->connect();

        try {
            // Ghi log thông tin đăng ký
            $this->logRegistration($ten_dang_nhap, $email, $so_dien_thoai, $is_verified);
            
            // Kiểm tra cấu trúc bảng
            $this->logTableStructure($conn);
            
            // Kiểm tra kết nối
            if (!$conn) {
                error_log("LỖI: Không thể kết nối database");
                return false;
            }
            
            // Thực hiện đăng ký
            $newUserId = $this->insertUser($conn, $ten_dang_nhap, $email, $so_dien_thoai, $password_md5, $is_verified);
            
            error_log("Kết quả insertUser: $newUserId");
            
            if ($newUserId > 0) {
                // Tạo tài khoản chuyển tiền
                $this->createPaymentAccount($conn, $newUserId);
                error_log("ĐĂNG KÝ THÀNH CÔNG với ID: $newUserId");
                return true;
            } else {
                error_log("ĐĂNG KÝ THẤT BẠI: insertUser trả về $newUserId");
                return false;
            }
            
        } catch (Exception $e) {
            error_log("EXCEPTION TRONG REGISTERUSER: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            return false;
        } finally {
            if ($conn) {
                $conn->close();
            }
        }
    }
    
    /**
     * Ghi log thông tin đăng ký
     */
    private function logRegistration($ten_dang_nhap, $email, $so_dien_thoai, $is_verified) {
        error_log("ĐĂNG KÝ MỚI: ten_dang_nhap=$ten_dang_nhap, email=$email, so_dien_thoai=$so_dien_thoai, is_verified=$is_verified");
    }
    
    /**
     * Ghi log cấu trúc bảng
     */
    private function logTableStructure($conn) {
        $tableCheck = $conn->query("SHOW COLUMNS FROM nguoi_dung");
        $columns = [];
        while ($row = $tableCheck->fetch_assoc()) {
            $columns[] = $row['Field'];
        }
        error_log("Cấu trúc bảng nguoi_dung: " . implode(", ", $columns));
    }
    
    /**
     * Thêm người dùng vào cơ sở dữ liệu
     */
    private function insertUser($conn, $ten_dang_nhap, $email, $so_dien_thoai, $password_md5, $is_verified) {
        // Chuẩn bị dữ liệu
        $email = !empty($email) ? $email : null;
        $so_dien_thoai = !empty($so_dien_thoai) ? $so_dien_thoai : null;
        
        error_log("Dữ liệu đăng ký: ten_dang_nhap=$ten_dang_nhap, email=$email, so_dien_thoai=$so_dien_thoai, is_verified=$is_verified");
        
        // Câu lệnh SQL
        $sql = "INSERT INTO nguoi_dung (ten_dang_nhap, email, so_dien_thoai, mat_khau, id_vai_tro, trang_thai_hd, is_verified) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        error_log("SQL: $sql");
        error_log("Tham số: ten_dang_nhap='$ten_dang_nhap', email=" . ($email ? "'$email'" : "NULL") . ", so_dien_thoai=" . ($so_dien_thoai ? "'$so_dien_thoai'" : "NULL") . ", password_md5='$password_md5', id_vai_tro=2, trang_thai_hd=1, is_verified=$is_verified");
        
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            error_log("LỖI PREPARE SQL: " . $conn->error);
            return 0;
        }
        
        // Bind tham số
        $id_vai_tro = 2;
        $trang_thai_hd = 1;
        $bindResult = $stmt->bind_param("ssssiii", $ten_dang_nhap, $email, $so_dien_thoai, $password_md5, $id_vai_tro, $trang_thai_hd, $is_verified);
        if (!$bindResult) {
            error_log("LỖI BIND PARAM: " . $stmt->error);
            $stmt->close();
            return 0;
        }
        
        // Thực thi
        $ok = $stmt->execute();
        if (!$ok) {
            error_log("LỖI EXECUTE SQL: " . $stmt->error);
            $stmt->close();
            return 0;
        }
        
        $newUserId = $conn->insert_id;
        error_log("ĐĂNG KÝ THÀNH CÔNG: ID người dùng mới: $newUserId");
        $stmt->close();

        return $newUserId;
    }
    
    /**
     * Tạo tài khoản chuyển tiền cho người dùng mới
     */
    private function createPaymentAccount($conn, $newUserId) {
        try {
            // Lấy ID chuyển khoản tiếp theo
            $result = $conn->query("SELECT MAX(id_ck) AS max_ck FROM taikhoan_chuyentien");
            $row = $result->fetch_assoc();
            $next_ck = ($row && isset($row['max_ck']) && $row['max_ck']) ? intval($row['max_ck']) + 1 : 1000;
            
            error_log("ID chuyển khoản tiếp theo: $next_ck");
            
            // Tạo tài khoản chuyển tiền
            $stmt = $conn->prepare("INSERT INTO taikhoan_chuyentien (id_ck, id_nguoi_dung, so_du) VALUES (?, ?, 0)");
            if (!$stmt) {
                error_log("LỖI PREPARE SQL (tài khoản chuyển tiền): " . $conn->error);
                return;
            }
            
            $stmt->bind_param("ii", $next_ck, $newUserId);
            $ok = $stmt->execute();
            
            if (!$ok) {
                error_log("LỖI TẠO TÀI KHOẢN CHUYỂN TIỀN: " . $stmt->error);
            } else {
                error_log("TẠO TÀI KHOẢN CHUYỂN TIỀN THÀNH CÔNG: id_ck=$next_ck, id_nguoi_dung=$newUserId");
            }
            
            $stmt->close();
        } catch (Exception $e) {
            error_log("Lỗi tạo tài khoản chuyển tiền: " . $e->getMessage());
        }
    }
    
    // ==================== PHƯƠNG THỨC OTP ====================
    
    /**
     * Tạo và lưu mã OTP
     * 
     * @param string $contact Email
     * @param string $method Phương thức (email)
     * @return string|false Mã OTP hoặc false nếu thất bại
     */
    public function createOTP($contact, $method) {
        $conn = $this->connect();
        
        try {
            // Tạo mã OTP ngẫu nhiên 6 chữ số
            $otp = $this->generateOTP();
            
            // Thời gian hết hạn (10 phút)
            $expires_at = date('Y-m-d H:i:s', strtotime('+10 minutes'));
            
            // Xóa OTP cũ nếu có
            $this->deleteOldOTP($conn, $contact);
            
            // Lưu OTP mới
            $result = $this->saveOTP($conn, $contact, $otp, $expires_at);
            
            return $result ? $otp : false;
        } catch (Exception $e) {
            error_log("Lỗi tạo OTP: " . $e->getMessage());
            return false;
        } finally {
            $conn->close();
        }
    }
    
    /**
     * Tạo mã OTP ngẫu nhiên
     */
    private function generateOTP() {
        return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    }
    
    /**
     * Xóa OTP cũ
     */
    private function deleteOldOTP($conn, $contact) {
        $stmt = $conn->prepare("DELETE FROM otp_verification WHERE email = ?");
        $stmt->bind_param("s", $contact);
        $stmt->execute();
        $stmt->close();
    }
    
    /**
     * Lưu OTP mới
     */
    private function saveOTP($conn, $contact, $otp, $expires_at) {
        $stmt = $conn->prepare("INSERT INTO otp_verification (email, otp, expires_at) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $contact, $otp, $expires_at);
        $result = $stmt->execute();
        $stmt->close();
        
        return $result;
    }
    
    /**
     * Xác thực mã OTP
     * 
     * @param string $contact Email
     * @param string $method Phương thức (email)
     * @param string $otp Mã OTP cần xác thực
     * @return array Kết quả xác thực
     */
    public function verifyOTP($contact, $method, $otp) {
        $conn = $this->connect();
        
        try {
            error_log("XÁC THỰC OTP: method=$method, contact=$contact, otp=$otp");
            
            // Lấy thông tin OTP
            $otpData = $this->getOTPData($conn, $contact);
            
            if (!$otpData) {
                error_log("KHÔNG TÌM THẤY OTP: method=$method, contact=$contact");
                return ['success' => false, 'message' => 'Không tìm thấy mã OTP hoặc đã được sử dụng'];
            }
            
            // Kiểm tra OTP
            $validationResult = $this->validateOTP($otpData, $otp);
            if (!$validationResult['success']) {
                return $validationResult;
            }
            
            // Đánh dấu OTP đã được xác thực
            $this->markOTPAsVerified($conn, $otpData['id']);
            
            error_log("XÁC THỰC OTP THÀNH CÔNG: id=" . $otpData['id']);
            return ['success' => true, 'message' => 'Xác thực thành công'];
            
        } catch (Exception $e) {
            error_log("EXCEPTION TRONG VERIFYOTP: " . $e->getMessage());
            return ['success' => false, 'message' => 'Lỗi xác thực: ' . $e->getMessage()];
        } finally {
        $conn->close();
        }
    }
    
    /**
     * Lấy thông tin OTP từ cơ sở dữ liệu
     */
    private function getOTPData($conn, $contact) {
        $stmt = $conn->prepare("SELECT id, otp, expires_at FROM otp_verification WHERE email = ? AND verified = 0");
        $stmt->bind_param("s", $contact);
        $stmt->execute();
        $result = $stmt->get_result();
        $otpData = $result->fetch_assoc();
        $stmt->close();
        
        if ($otpData) {
            error_log("DỮ LIỆU OTP: id=" . $otpData['id'] . ", otp=" . $otpData['otp'] . ", expires_at=" . $otpData['expires_at']);
        }
        
        return $otpData;
    }
    
    /**
     * Kiểm tra tính hợp lệ của OTP
     */
    private function validateOTP($otpData, $otp) {
        // Kiểm tra thời gian hết hạn
        if (strtotime($otpData['expires_at']) < time()) {
            error_log("OTP HẾT HẠN: expires_at=" . $otpData['expires_at'] . ", hiện tại=" . date('Y-m-d H:i:s'));
            return ['success' => false, 'message' => 'Mã OTP đã hết hạn'];
        }
        
        // Kiểm tra OTP có khớp không
        if ($otpData['otp'] !== $otp) {
            error_log("OTP KHÔNG KHỚP: nhập=$otp, csdl=" . $otpData['otp']);
            return ['success' => false, 'message' => 'Mã OTP không chính xác'];
        }
        
        return ['success' => true];
    }
    
    /**
     * Đánh dấu OTP đã được xác thực
     */
    private function markOTPAsVerified($conn, $otpId) {
        $stmt = $conn->prepare("UPDATE otp_verification SET verified = 1 WHERE id = ?");
        $stmt->bind_param("i", $otpId);
        $ok = $stmt->execute();
        $stmt->close();
        
        if (!$ok) {
            error_log("LỖI CẬP NHẬT TRẠNG THÁI OTP: " . $conn->error);
        }
    }
    
    /**
     * Cập nhật mật khẩu mới
     * 
     * @param string $email Email người dùng
     * @param string $newPasswordMd5 Mật khẩu mới đã mã hóa MD5
     * @return bool True nếu cập nhật thành công
     */
    public function updatePassword($email, $newPasswordMd5) {
        $conn = $this->connect();
        
        try {
            error_log("Cập nhật mật khẩu: email=$email");
            
            $stmt = $conn->prepare("UPDATE nguoi_dung SET mat_khau = ? WHERE email = ?");
            $stmt->bind_param("ss", $newPasswordMd5, $email);
            
            $ok = $stmt->execute();
            
            if (!$ok) {
                error_log("LỖI CẬP NHẬT MẬT KHẨU: " . $stmt->error);
            } else {
                error_log("CẬP NHẬT MẬT KHẨU THÀNH CÔNG: email=$email");
            }
            
            $stmt->close();
            return $ok;
            
        } catch (Exception $e) {
            error_log("EXCEPTION TRONG UPDATEPASSWORD: " . $e->getMessage());
            return false;
        } finally {
            $conn->close();
        }
    }
}
?>