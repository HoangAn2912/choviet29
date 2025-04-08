
-- Bảng Người Dùng
CREATE TABLE nguoi_dung (
    id INT PRIMARY KEY,
    ten_dang_nhap VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    mat_khau VARCHAR(255) NOT NULL,
    so_dien_thoai VARCHAR(20),
    dia_chi VARCHAR(255),
    vai_tro VARCHAR(20) DEFAULT 'nguoi_mua',
    anh_dai_dien VARCHAR(255),
    ngay_tao DATE,
    ngay_cap_nhat DATE
);

CREATE TABLE loai_san_pham_cha (
    id_loai_san_pham_cha INT PRIMARY KEY,
    ten_loai_san_pham_cha VARCHAR(100) NOT NULL
);

-- Bảng Loại Sản Phẩm
CREATE TABLE loai_san_pham (
    id INT PRIMARY KEY,
    ten_loai_san_pham VARCHAR(100) NOT NULL,
    loai_cha INT DEFAULT NULL,
    FOREIGN KEY (loai_cha) REFERENCES loai_san_pham(id)
);

-- Bảng Sản Phẩm
CREATE TABLE san_pham (
    id INT PRIMARY KEY ,
    id_nguoi_ban INT NOT NULL,
    id_loai_san_pham INT NOT NULL,
    tieu_de VARCHAR(255) NOT NULL,
    mo_ta VARCHAR(1000),
    gia DECIMAL(10,2) NOT NULL,
    hinh_anh VARCHAR(255),
    trang_thai VARCHAR(20) DEFAULT 'cho_duyet',
    ngay_tao DATE ,
    ngay_cap_nhat DATE ,
    FOREIGN KEY (id_nguoi_ban) REFERENCES nguoi_dung(id),
    FOREIGN KEY (id_loai_san_pham) REFERENCES loai_san_pham(id)
);

-- Bảng Tin Nhắn
CREATE TABLE tin_nhan (
    id INT PRIMARY KEY ,
    id_nguoi_gui INT NOT NULL,
    id_nguoi_nhan INT NOT NULL,
    id_san_pham INT NOT NULL,
    noi_dung VARCHAR(1000) NOT NULL,
    ngay_tao DATE ,
    FOREIGN KEY (id_nguoi_gui) REFERENCES nguoi_dung(id),
    FOREIGN KEY (id_nguoi_nhan) REFERENCES nguoi_dung(id),
    FOREIGN KEY (id_san_pham) REFERENCES san_pham(id)
);

-- Bảng Đánh Giá
CREATE TABLE danh_gia (
    id INT PRIMARY KEY,
    id_nguoi_danh_gia INT NOT NULL,
    id_nguoi_duoc_danh_gia INT NOT NULL,
    so_sao INT CHECK (so_sao >= 1 AND so_sao <= 5),
    binh_luan VARCHAR(1000),
    ngay_tao DATE ,
    FOREIGN KEY (id_nguoi_danh_gia) REFERENCES nguoi_dung(id),
    FOREIGN KEY (id_nguoi_duoc_danh_gia) REFERENCES nguoi_dung(id)
);

-- Bảng Lịch Sử Phí Đăng Bài
CREATE TABLE lich_su_phi_dang_bai (
    id INT PRIMARY KEY,
    id_san_pham INT NOT NULL,
    id_nguoi_dung INT NOT NULL,
    so_tien DECIMAL(10,2) NOT NULL,
    ngay_tao DATE,
    FOREIGN KEY (id_san_pham) REFERENCES san_pham(id),
    FOREIGN KEY (id_nguoi_dung) REFERENCES nguoi_dung(id)
);

-- Bảng Lịch Sử Đẩy Tin
CREATE TABLE lich_su_day_tin (
    id INT PRIMARY KEY,
    id_san_pham INT NOT NULL,
    id_nguoi_dung INT NOT NULL,
    so_tien DECIMAL(10,2) NOT NULL,
    thoi_gian_day DATE,
    FOREIGN KEY (id_san_pham) REFERENCES san_pham(id),
    FOREIGN KEY (id_nguoi_dung) REFERENCES nguoi_dung(id)
);

-- Bảng Giao Dịch
CREATE TABLE giao_dich (
    id INT PRIMARY KEY ,
    id_nguoi_dung INT NOT NULL,
    loai_giao_dich VARCHAR(20) NOT NULL,
    so_tien DECIMAL(10,2) NOT NULL,
    trang_thai VARCHAR(20) DEFAULT 'hoan_thanh',
    ngay_tao DATE ,
    FOREIGN KEY (id_nguoi_dung) REFERENCES nguoi_dung(id)
);

-- 1. Bảng Người Dùng
INSERT INTO nguoi_dung (id, ten_dang_nhap, email, mat_khau, so_dien_thoai, dia_chi, vai_tro, anh_dai_dien) VALUES
(1, 'user1', 'user1@example.com', 'password', '0901111111', 'Hà Nội', 'nguoi_ban', 'user1.jpg'),
(2, 'user2', 'user2@example.com', 'password', '0902222222', 'TP.HCM', 'nguoi_ban', 'user2.jpg'),
(3, 'user3', 'user3@example.com', 'password', '0903333333', 'Đà Nẵng', 'nguoi_mua', 'user3.jpg'),
(4, 'user4', 'user4@example.com', 'password', '0904444444', 'Huế', 'nguoi_mua', 'user4.jpg'),
(5, 'admin', 'admin@example.com', 'admin', '0905555555', 'Cần Thơ', 'quan_tri', 'admin.jpg');

-- 2. Bảng Loại Sản Phẩm Cha
INSERT INTO loai_san_pham_cha (id_loai_san_pham_cha, ten_loai_san_pham_cha) VALUES
(1,'Xe cộ'),
(2,'Đồ điện tử'),
(3,'Thời Trang'),
(4,'Nội thất'),
(5,'Giải trí'),
(6,'Khác');

-- 3. Bảng Loại Sản Phẩm
INSERT INTO loai_san_pham (id, loai_cha, ten_loai_san_pham) VALUES
(1,1, 'Xe máy'),
(2,1, 'Ô tô'),
(3,1, 'Xe điện'),
(4, 1, 'Phụ tùng xe'),
(5, 1, 'Khác'),

(6,2, 'Laptop'),
(7, 2, 'Điện thoại'),
(8, 2, 'Máy tính bảng'),
(9, 2, 'Máy ảnh'),
(10, 2, 'Thiết bị thông minh'),
(11, 2, 'Khác'),

(12,3, 'Quần'),
(13,3, 'Áo'),
(14,3, 'Túi xách'),
(15,3, 'Dép'),
(16,3, 'Mũ'),
(17,3, 'Khác'),

(18,4, 'Bàn ghế'),
(19,4, 'Tủ kệ'),
(20, 4, 'Dụng cụ bếp'),
(21,4, 'Dụng cụ trang trí'),
(22, 4, 'Khác'),

(23, 5, 'Nhạc cụ'),
(24, 5, 'Đồ thể thao'),
(25, 5, 'Thiết bị chơi game'),
(26, 5, 'Khác'),

(27, 6, 'Khác');
ALTER TABLE san_pham
ALTER COLUMN gia DECIMAL(15,2);

-- 4. Bảng Sản Phẩm (tạo sản phẩm đầy đủ từ bảng loại sản phẩm)
INSERT INTO san_pham (id, id_nguoi_ban, id_loai_san_pham, tieu_de, mo_ta, gia, hinh_anh, trang_thai) VALUES
(1,1, 1, 'Xe máy Honda', 'Xe máy Honda 2015, còn tốt.', 10000000, 'xe_may.jpg', 'da_duyet'),
(2,1, 2, 'Ô tô Toyota', 'Ô tô Toyota Vios 2018.', 450000000, 'oto.jpg', 'da_duyet'),
(3,1, 3, 'Xe điện VinFast', 'Xe điện VinFast Klara.', 20000000, 'xe_dien.jpg', 'da_duyet'),
(4,1, 4, 'Phụ tùng xe máy', 'Phụ tùng xe máy mới 90%.', 500000, 'phu_tung_xe.jpg', 'da_duyet'),
(5,1, 5, 'Xe khác', 'Các loại xe khác.', 15000000, 'xe_khac.jpg', 'da_duyet'),

(6,2, 6, 'Laptop Dell', 'Laptop Dell i7, ram 16GB.', 15000000, 'laptop.jpg', 'da_duyet'),
(7, 2, 7, 'iPhone 12', 'Điện thoại iPhone 12.', 18000000, 'iphone.jpg', 'da_duyet'),
(8,2, 8, 'iPad Pro', 'Máy tính bảng iPad Pro.', 22000000, 'ipad.jpg', 'da_duyet'),
(9, 2, 9, 'Máy ảnh Canon', 'Máy ảnh Canon EOS.', 12000000, 'may_anh.jpg', 'da_duyet'),
(10,2, 10, 'Thiết bị thông minh', 'Đồng hồ thông minh Samsung.', 5000000, 'dongho.jpg', 'da_duyet'),

(11, 1, 11, 'Quần jean nam', 'Quần jean size 32.', 400000, 'quan_jean.jpg', 'da_duyet'),
(12,1, 12, 'Áo thun nữ', 'Áo thun cotton nữ.', 300000, 'ao_thun.jpg', 'da_duyet'),
(13,1, 13, 'Túi xách da', 'Túi xách da cao cấp.', 2500000, 'tui_xach.jpg', 'da_duyet'),
(14,1, 14, 'Dép sandal', 'Dép sandal thoáng mát.', 200000, 'dep.jpg', 'da_duyet'),
(15,1, 15, 'Mũ bảo hiểm', 'Mũ bảo hiểm chất lượng.', 400000, 'mu_bao_hiem.jpg', 'da_duyet'),

(16, 2, 16, 'Bàn làm việc', 'Bàn làm việc gỗ công nghiệp.', 1500000, 'ban.jpg', 'da_duyet'),
(17,2, 17, 'Tủ quần áo', 'Tủ quần áo 3 cánh.', 2500000, 'tu.jpg', 'da_duyet'),
(18, 2, 18, 'Nồi cơm điện', 'Nồi cơm điện Sharp.', 700000, 'noi_com.jpg', 'da_duyet'),
(19, 2, 19, 'Đèn trang trí', 'Đèn trang trí phòng khách.', 1200000, 'den.jpg', 'da_duyet'),
(20, 2, 20, 'Nội thất khác', 'Nội thất các loại.', 1000000, 'noi_that_khac.jpg', 'da_duyet'),

(21, 1, 21, 'Guitar Yamaha', 'Đàn guitar Yamaha F310.', 3000000, 'guitar.jpg', 'da_duyet'),
(22, 1, 22, 'Bóng đá FIFA', 'Bóng đá tiêu chuẩn FIFA.', 500000, 'bong.jpg', 'da_duyet'),
(23, 1, 23, 'Máy chơi game PS5', 'Máy chơi game PlayStation 5.', 15000000, 'ps5.jpg', 'da_duyet'),
(24, 1, 24, 'Giải trí khác', 'Các sản phẩm giải trí khác.', 2000000, 'giai_tri_khac.jpg', 'da_duyet'),

(25, 2, 25, 'Sản phẩm khác', 'Các sản phẩm chưa phân loại.', 500000, 'khac.jpg', 'da_duyet');

-- 5. Bảng Tin Nhắn
INSERT INTO tin_nhan (id,id_nguoi_gui, id_nguoi_nhan, id_san_pham, noi_dung) VALUES
(1,3, 1, 1, 'Sản phẩm còn không bạn?'),
(2,1, 3, 1, 'Vẫn còn bạn nhé!'),
(3,4, 2, 6, 'Laptop này còn bảo hành không?'),
(4,2, 4, 6, 'Có bảo hành 6 tháng bạn nhé!'),
(5,3, 1, 7, 'iPhone có trầy xước không?'),
(6,1, 3, 7, 'Máy đẹp như mới bạn nhé!');

-- 6. Bảng Đánh Giá
INSERT INTO danh_gia (id, id_nguoi_danh_gia, id_nguoi_duoc_danh_gia, so_sao, binh_luan) VALUES
(1,3, 1, 5, 'Rất tốt, giao hàng nhanh.'),
(2,4, 2, 4, 'Sản phẩm như mô tả.'),
(3, 3, 2, 5, 'Hài lòng về sản phẩm.');

-- 7. Bảng Lịch Sử Phí Đăng Bài
INSERT INTO lich_su_phi_dang_bai (id, id_san_pham, id_nguoi_dung, so_tien) VALUES
(1,1, 1, 11000),
(2,6, 2, 11000),
(3,11, 1, 11000),
(4,16, 2, 11000),
(5,21, 1, 11000);

-- 8. Bảng Lịch Sử Đẩy Tin
INSERT INTO lich_su_day_tin (id, id_san_pham, id_nguoi_dung, so_tien) VALUES
(1,1, 1, 15500),
(2,6, 2, 15500),
(3,11, 1, 15500),
(4,16, 2, 15500),
(5,21, 1, 15500);

-- 9. Bảng Giao Dịch
INSERT INTO giao_dich (id,id_nguoi_dung, loai_giao_dich, so_tien, trang_thai) VALUES
(1,1, 'phi_dang_bai', 11000, 'hoan_thanh'),
(2,2, 'phi_dang_bai', 11000, 'hoan_thanh'),
(3,1, 'day_tin', 15500, 'hoan_thanh'),
(4,2, 'day_tin', 15500, 'hoan_thanh');


-- 1. Người dùng
SELECT * FROM nguoi_dung;

-- 2. Loại sản phẩm cha
SELECT * FROM loai_san_pham_cha;

-- 3. Loại sản phẩm
SELECT * FROM loai_san_pham;

-- 4. Sản phẩm
SELECT * FROM san_pham;

-- 5. Tin nhắn
SELECT * FROM tin_nhan;

-- 6. Đánh giá
SELECT * FROM danh_gia;

-- 7. Lịch sử phí đăng bài
SELECT * FROM lich_su_phi_dang_bai;

-- 8. Lịch sử đẩy tin
SELECT * FROM lich_su_day_tin;

-- 9. Giao dịch
SELECT * FROM giao_dich;
