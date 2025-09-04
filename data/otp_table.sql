-- Tạo bảng lưu trữ mã OTP
CREATE TABLE IF NOT EXISTS otp_verification (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NULL,
    so_dien_thoai VARCHAR(20) NULL,
    otp VARCHAR(6) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP,
    verified TINYINT(1) DEFAULT 0,
    INDEX (email),
    INDEX (so_dien_thoai)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Thêm cột số điện thoại vào bảng người dùng nếu chưa có
ALTER TABLE nguoi_dung ADD COLUMN IF NOT EXISTS so_dien_thoai VARCHAR(20) NULL;
ALTER TABLE nguoi_dung ADD COLUMN IF NOT EXISTS is_verified TINYINT(1) DEFAULT 0;
ALTER TABLE nguoi_dung ADD UNIQUE INDEX IF NOT EXISTS idx_email (email);
ALTER TABLE nguoi_dung ADD UNIQUE INDEX IF NOT EXISTS idx_so_dien_thoai (so_dien_thoai);

