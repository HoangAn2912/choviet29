-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th5 13, 2025 lúc 06:03 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `choviet`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chi_phi`
--

CREATE TABLE `chi_phi` (
  `id` int(11) NOT NULL,
  `ma_chi_phi` varchar(20) NOT NULL,
  `ngay_chi_phi` date NOT NULL,
  `mo_ta` text DEFAULT NULL,
  `so_tien` decimal(15,2) NOT NULL,
  `id_danh_muc` int(11) DEFAULT NULL,
  `id_phuong_thuc_thanh_toan` int(11) DEFAULT NULL,
  `trang_thai` enum('da_thanh_toan','chua_thanh_toan') NOT NULL DEFAULT 'da_thanh_toan',
  `ghi_chu` text DEFAULT NULL,
  `nguoi_tao` int(11) NOT NULL,
  `ngay_tao` datetime NOT NULL DEFAULT current_timestamp(),
  `ngay_cap_nhat` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `chi_phi`
--

INSERT INTO `chi_phi` (`id`, `ma_chi_phi`, `ngay_chi_phi`, `mo_ta`, `so_tien`, `id_danh_muc`, `id_phuong_thuc_thanh_toan`, `trang_thai`, `ghi_chu`, `nguoi_tao`, `ngay_tao`, `ngay_cap_nhat`) VALUES
(1, 'CP001', '2023-04-24', 'Mua văn phòng phẩm', 1200.00, 7, 4, 'da_thanh_toan', NULL, 1, '2025-04-28 23:15:11', NULL),
(2, 'CP002', '2023-04-22', 'Bảo trì máy chủ', 2800.00, 8, 1, 'da_thanh_toan', NULL, 1, '2025-04-28 23:15:11', NULL),
(3, 'CP003', '2023-04-20', 'Lương nhân viên tháng 4', 35000.00, 9, 1, 'da_thanh_toan', NULL, 1, '2025-04-28 23:15:11', NULL),
(4, 'CP004', '2023-04-15', 'Tiền thuê văn phòng Q2/2023', 12000.00, 10, 1, 'da_thanh_toan', NULL, 1, '2025-04-28 23:15:11', NULL),
(5, 'CP005', '2023-04-10', 'Mua thiết bị văn phòng', 5500.00, 7, 2, 'da_thanh_toan', NULL, 2, '2025-04-28 23:15:11', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danh_gia`
--

CREATE TABLE `danh_gia` (
  `id` int(11) NOT NULL,
  `id_nguoi_danh_gia` int(11) NOT NULL,
  `id_nguoi_duoc_danh_gia` int(11) NOT NULL,
  `id_san_pham` int(11) NOT NULL,
  `so_sao` int(11) DEFAULT NULL CHECK (`so_sao` >= 1 and `so_sao` <= 5),
  `binh_luan` varchar(1000) DEFAULT NULL,
  `ngay_tao` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `danh_gia`
--

INSERT INTO `danh_gia` (`id`, `id_nguoi_danh_gia`, `id_nguoi_duoc_danh_gia`, `id_san_pham`, `so_sao`, `binh_luan`, `ngay_tao`) VALUES
(1, 2, 1, 1, 5, 'Rất tốt, giao hàng nhanh.', '2025-04-10'),
(4, 2, 3, 2, 4, 'Sản phẩm anh ta bán khá ok', '2025-04-09'),
(5, 3, 2, 3, 5, 'Rất ok', '2025-04-10'),
(9, 1, 3, 7, 5, 'sản phẩm này tốt', '2025-05-11');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `doanh_thu`
--

CREATE TABLE `doanh_thu` (
  `id` int(11) NOT NULL,
  `ma_doanh_thu` varchar(20) NOT NULL,
  `ngay_doanh_thu` date NOT NULL,
  `id_khach_hang` int(11) NOT NULL,
  `mo_ta` text DEFAULT NULL,
  `so_tien` decimal(15,2) NOT NULL,
  `id_danh_muc` int(11) DEFAULT NULL,
  `id_phuong_thuc_thanh_toan` int(11) DEFAULT NULL,
  `trang_thai` enum('da_thanh_toan','chua_thanh_toan') NOT NULL DEFAULT 'chua_thanh_toan',
  `ghi_chu` text DEFAULT NULL,
  `nguoi_tao` int(11) NOT NULL,
  `ngay_tao` datetime NOT NULL DEFAULT current_timestamp(),
  `ngay_cap_nhat` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `doanh_thu`
--

INSERT INTO `doanh_thu` (`id`, `ma_doanh_thu`, `ngay_doanh_thu`, `id_khach_hang`, `mo_ta`, `so_tien`, `id_danh_muc`, `id_phuong_thuc_thanh_toan`, `trang_thai`, `ghi_chu`, `nguoi_tao`, `ngay_tao`, `ngay_cap_nhat`) VALUES
(1, 'DT001', '2023-04-25', 1, 'Giấy phép phần mềm', 12500.00, 1, 1, 'da_thanh_toan', NULL, 1, '2025-04-28 23:14:27', NULL),
(2, 'DT002', '2023-04-23', 2, 'Dịch vụ tư vấn', 8500.00, 2, 2, 'chua_thanh_toan', NULL, 1, '2025-04-28 23:14:27', NULL),
(3, 'DT003', '2023-04-20', 3, 'Hợp đồng bảo trì', 5000.00, 3, 1, 'da_thanh_toan', NULL, 1, '2025-04-28 23:14:27', NULL),
(4, 'DT004', '2023-04-18', 4, 'Bán sản phẩm', 15750.00, 4, 4, 'da_thanh_toan', NULL, 2, '2025-04-28 23:14:27', NULL),
(5, 'DT005', '2023-04-15', 5, 'Phát triển web', 9800.00, 5, 3, 'chua_thanh_toan', NULL, 2, '2025-04-28 23:14:27', NULL),
(6, 'DT006', '2023-04-12', 6, 'Bán phần cứng', 22000.00, 4, 1, 'da_thanh_toan', NULL, 1, '2025-04-28 23:14:27', NULL),
(7, 'DT007', '2023-04-10', 2, 'Dịch vụ đám mây', 7500.00, 6, 2, 'da_thanh_toan', NULL, 2, '2025-04-28 23:14:27', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `giao_dich`
--

CREATE TABLE `giao_dich` (
  `id` int(11) NOT NULL,
  `id_nguoi_dung` int(11) NOT NULL,
  `loai_giao_dich` varchar(20) NOT NULL,
  `so_tien` decimal(10,2) NOT NULL,
  `trang_thai` varchar(20) DEFAULT 'hoan_thanh',
  `ngay_tao` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `giao_dich`
--

INSERT INTO `giao_dich` (`id`, `id_nguoi_dung`, `loai_giao_dich`, `so_tien`, `trang_thai`, `ngay_tao`) VALUES
(1, 1, 'phi_dang_bai', 11000.00, 'hoan_thanh', '2025-04-10'),
(2, 2, 'phi_dang_bai', 11000.00, 'hoan_thanh', '2025-04-10'),
(3, 1, 'day_tin', 15500.00, 'hoan_thanh', '2025-04-10'),
(4, 2, 'day_tin', 15500.00, 'hoan_thanh', '2025-04-10');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lich_su_chuyen_khoan`
--

CREATE TABLE `lich_su_chuyen_khoan` (
  `id_lich_su` int(11) NOT NULL,
  `id_nguoi_dung` int(11) NOT NULL,
  `noi_dung_ck` varchar(255) NOT NULL,
  `hinh_anh_ck` varchar(255) NOT NULL,
  `trang_thai_ck` varchar(255) NOT NULL,
  `ngay_tao` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `lich_su_chuyen_khoan`
--

INSERT INTO `lich_su_chuyen_khoan` (`id_lich_su`, `id_nguoi_dung`, `noi_dung_ck`, `hinh_anh_ck`, `trang_thai_ck`, `ngay_tao`) VALUES
(1, 1, '1111_Nguyễn Phúc_20000', 'ck_1747147941_5357.png', 'Đang chờ duyệt', '2025-05-13 21:52:21'),
(2, 1, '1111_Nguyễn Phúc_20000', 'ck_1747148337_4645.png', 'Đang chờ duyệt', '2025-05-13 21:58:57');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lich_su_day_tin`
--

CREATE TABLE `lich_su_day_tin` (
  `id` int(11) NOT NULL,
  `id_san_pham` int(11) NOT NULL,
  `id_nguoi_dung` int(11) NOT NULL,
  `so_tien` decimal(10,2) NOT NULL,
  `thoi_gian_day` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `lich_su_day_tin`
--

INSERT INTO `lich_su_day_tin` (`id`, `id_san_pham`, `id_nguoi_dung`, `so_tien`, `thoi_gian_day`) VALUES
(1, 1, 1, 15500.00, '2025-04-10 00:00:00'),
(2, 6, 2, 15500.00, '2025-04-10 00:00:00'),
(3, 11, 1, 15500.00, '2025-04-10 00:00:00'),
(4, 16, 2, 15500.00, '2025-04-10 00:00:00'),
(5, 21, 1, 15500.00, '2025-04-10 00:00:00'),
(6, 2, 1, 11000.00, '2025-05-03 23:28:22'),
(7, 2, 1, 11000.00, '2025-05-03 23:28:33'),
(8, 2, 1, 11000.00, '2025-05-03 23:30:21'),
(9, 2, 1, 11000.00, '2025-05-03 23:31:25'),
(11, 2, 1, 11000.00, '2025-05-03 23:42:04'),
(12, 36, 1, 11000.00, '2025-05-11 11:08:43'),
(13, 36, 1, 11000.00, '2025-05-11 11:15:20'),
(14, 35, 1, 11000.00, '2025-05-11 11:15:23'),
(15, 11, 1, 11000.00, '2025-05-13 21:28:28');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lich_su_phi_dang_bai`
--

CREATE TABLE `lich_su_phi_dang_bai` (
  `id` int(11) NOT NULL,
  `id_san_pham` int(11) NOT NULL,
  `id_nguoi_dung` int(11) NOT NULL,
  `so_tien` decimal(10,2) NOT NULL,
  `ngay_tao` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `lich_su_phi_dang_bai`
--

INSERT INTO `lich_su_phi_dang_bai` (`id`, `id_san_pham`, `id_nguoi_dung`, `so_tien`, `ngay_tao`) VALUES
(1, 1, 1, 11000.00, '2025-04-10'),
(2, 6, 2, 11000.00, '2025-04-10'),
(3, 11, 1, 11000.00, '2025-04-10'),
(4, 16, 2, 11000.00, '2025-04-10'),
(5, 21, 1, 11000.00, '2025-04-10'),
(6, 27, 1, 11000.00, '2025-05-02'),
(7, 28, 1, 11000.00, '2025-05-02'),
(8, 29, 1, 11000.00, '2025-05-02'),
(9, 30, 1, 11000.00, '2025-05-02'),
(10, 31, 1, 11000.00, '2025-05-02'),
(11, 32, 1, 11000.00, '2025-05-07'),
(12, 33, 1, 11000.00, '2025-05-07'),
(13, 34, 1, 11000.00, '2025-05-07'),
(14, 35, 1, 11000.00, '2025-05-07'),
(15, 36, 1, 11000.00, '2025-05-07'),
(16, 37, 1, 11000.00, '2025-05-11');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `loai_san_pham`
--

CREATE TABLE `loai_san_pham` (
  `id` int(11) NOT NULL,
  `ten_loai_san_pham` varchar(100) NOT NULL,
  `id_loai_san_pham_cha` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `loai_san_pham`
--

INSERT INTO `loai_san_pham` (`id`, `ten_loai_san_pham`, `id_loai_san_pham_cha`) VALUES
(1, 'Xe máy', 1),
(2, 'Ô tô', 1),
(3, 'Xe điện', 1),
(4, 'Phụ tùng xe', 1),
(5, 'Khác', 1),
(6, 'Laptop', 2),
(7, 'Điện thoại', 2),
(8, 'Máy tính bảng', 2),
(9, 'Máy ảnh', 2),
(10, 'Thiết bị thông minh', 2),
(11, 'Khác', 2),
(12, 'Quần', 3),
(13, 'Áo', 3),
(14, 'Túi xách', 3),
(15, 'Dép', 3),
(16, 'Mũ', 3),
(17, 'Khác', 3),
(18, 'Bàn ghế', 4),
(19, 'Tủ kệ', 4),
(20, 'Dụng cụ bếp', 4),
(21, 'Dụng cụ trang trí', 4),
(22, 'Khác', 4),
(23, 'Nhạc cụ', 5),
(24, 'Đồ thể thao', 5),
(25, 'Thiết bị chơi game', 5),
(26, 'Khác', 5),
(27, 'Khác', 6);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `loai_san_pham_cha`
--

CREATE TABLE `loai_san_pham_cha` (
  `id_loai_san_pham_cha` int(11) NOT NULL,
  `ten_loai_san_pham_cha` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `loai_san_pham_cha`
--

INSERT INTO `loai_san_pham_cha` (`id_loai_san_pham_cha`, `ten_loai_san_pham_cha`) VALUES
(1, 'Xe cộ'),
(2, 'Đồ điện tử'),
(3, 'Thời Trang'),
(4, 'Nội thất'),
(5, 'Giải trí'),
(6, 'Khác');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nguoi_dung`
--

CREATE TABLE `nguoi_dung` (
  `id` int(11) NOT NULL,
  `ten_dang_nhap` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mat_khau` varchar(255) NOT NULL,
  `so_dien_thoai` varchar(20) DEFAULT NULL,
  `dia_chi` varchar(255) DEFAULT NULL,
  `id_vai_tro` int(11) NOT NULL,
  `anh_dai_dien` varchar(255) DEFAULT NULL,
  `ngay_tao` date DEFAULT curdate(),
  `ngay_cap_nhat` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `nguoi_dung`
--

INSERT INTO `nguoi_dung` (`id`, `ten_dang_nhap`, `email`, `mat_khau`, `so_dien_thoai`, `dia_chi`, `id_vai_tro`, `anh_dai_dien`, `ngay_tao`, `ngay_cap_nhat`) VALUES
(1, 'user1', 'user1@gmail.com', '202cb962ac59075b964b07152d234b70', '0901111111', 'Đường số 15, P.Linh Chiểu, Tp.Thủ Đức, Tp.Hồ Chí Minh', 2, 'xeab.jpg', '2025-04-10', '2025-04-10'),
(2, 'user2', 'user2@gmail.com', '202cb962ac59075b964b07152d234b70', '0902222222', 'Nguyễn Văn Bảo, P.4, Q.Gò Vấp, Tp.Hồ Chí Minh', 2, 'user2.jpg', '2025-04-10', '2025-04-10'),
(3, 'user3', 'user3@gmail.com', '202cb962ac59075b964b07152d234b70', '09033333333', 'Lê Lợi, P.4, Q.Gò Vấp, Tp.Hồ Chí Minh', 2, 'xeab2.jpg', '2025-04-10', '2025-04-10'),
(4, 'user4', 'user4@gmail.com', '202cb962ac59075b964b07152d234b70', '0904444444', 'Dương Quang Hàm, P.4, Q.Gò Vấp, Tp.Hồ Chí Minh', 2, 'user4.jpg', '2025-04-10', '2025-04-10'),
(5, 'admin', 'admin@gmail.com', '202cb962ac59075b964b07152d234b70', '0905555555', 'Tô Ngọc Vân, P.Linh Tây, Tp.Thủ Đức, Tp.Hồ Chí Minh', 1, 'admin.jpg', '2025-04-10', '2025-04-10'),
(6, 'Nguyễn Phúc', 'user5@gmail.com', '202cb962ac59075b964b07152d234b70', NULL, NULL, 2, NULL, '2025-05-11', '2025-05-11'),
(9, 'Nguyễn Phúc', 'user6@gmail.com', '202cb962ac59075b964b07152d234b70', NULL, NULL, 2, NULL, '2025-05-11', '2025-05-11'),
(10, 'Nguyễn Phúc', 'user7@gmail.com', '202cb962ac59075b964b07152d234b70', NULL, NULL, 2, NULL, '2025-05-11', '2025-05-11'),
(11, 'Nguyễn Phúc', 'user8@gmail.com', '202cb962ac59075b964b07152d234b70', NULL, NULL, 2, NULL, '2025-05-11', '2025-05-11'),
(12, 'Nguyễn Phúc', 'user9@gmail.com', '202cb962ac59075b964b07152d234b70', NULL, NULL, 2, NULL, '2025-05-11', '2025-05-11'),
(13, 'Nguyễn hoàng', 'a@gmail.com', '202cb962ac59075b964b07152d234b70', NULL, NULL, 2, NULL, '2025-05-11', '2025-05-11'),
(14, 'Nguyễn hoàng', 'b@gmail.com', '202cb962ac59075b964b07152d234b70', NULL, NULL, 2, NULL, '2025-05-11', '2025-05-11');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phuong_thuc_thanh_toan`
--

CREATE TABLE `phuong_thuc_thanh_toan` (
  `id` int(11) NOT NULL,
  `ten_phuong_thuc` varchar(100) NOT NULL,
  `mo_ta` text DEFAULT NULL,
  `trang_thai` tinyint(1) NOT NULL DEFAULT 1,
  `ngay_tao` datetime NOT NULL DEFAULT current_timestamp(),
  `ngay_cap_nhat` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `phuong_thuc_thanh_toan`
--

INSERT INTO `phuong_thuc_thanh_toan` (`id`, `ten_phuong_thuc`, `mo_ta`, `trang_thai`, `ngay_tao`, `ngay_cap_nhat`) VALUES
(1, 'Chuyển khoản ngân hàng', 'Thanh toán qua chuyển khoản ngân hàng', 1, '2025-04-28 23:14:12', NULL),
(2, 'Thẻ tín dụng', 'Thanh toán qua thẻ tín dụng', 1, '2025-04-28 23:14:12', NULL),
(3, 'PayPal', 'Thanh toán qua PayPal', 1, '2025-04-28 23:14:12', NULL),
(4, 'Tiền mặt', 'Thanh toán bằng tiền mặt', 1, '2025-04-28 23:14:12', NULL),
(5, 'Ví điện tử', 'Thanh toán qua ví điện tử như MoMo, ZaloPay', 1, '2025-04-28 23:14:12', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `san_pham`
--

CREATE TABLE `san_pham` (
  `id` int(11) NOT NULL,
  `id_nguoi_dung` int(11) NOT NULL,
  `id_loai_san_pham` int(11) NOT NULL,
  `tieu_de` varchar(255) NOT NULL,
  `mo_ta` text DEFAULT NULL,
  `gia` decimal(10,2) NOT NULL,
  `hinh_anh` varchar(255) DEFAULT NULL,
  `trang_thai` varchar(20) DEFAULT 'cho_duyet',
  `trang_thai_ban` varchar(50) NOT NULL,
  `ngay_tao` datetime DEFAULT current_timestamp(),
  `ngay_cap_nhat` datetime DEFAULT current_timestamp(),
  `ghi_chu` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `san_pham`
--

INSERT INTO `san_pham` (`id`, `id_nguoi_dung`, `id_loai_san_pham`, `tieu_de`, `mo_ta`, `gia`, `hinh_anh`, `trang_thai`, `trang_thai_ban`, `ngay_tao`, `ngay_cap_nhat`, `ghi_chu`) VALUES
(1, 1, 1, 'Xe máy Honda A', 'Xe máy Honda 2015, còn tốt.Xe máy Honda 2015, còn tốt.Xe máy Honda 2015, còn tốt.', 10000000.00, 'xe_may1.jpg, xe_may2.jpg, xe_may3.jpg', 'Chờ duyệt', 'Đang bán', '2025-04-10 00:00:00', '2025-05-11 17:19:05', ''),
(2, 1, 2, 'Ô tô Toyotaa', 'Ô tô Toyota Vios 2020.', 99999998.99, 'oto.jpg', 'Đã duyệt', 'Đã ẩn', '2025-04-10 00:00:00', '2025-05-11 12:25:33', ''),
(3, 1, 3, 'Xe điện VinFast ABS', 'Xe điện VinFast Klara.a a', 19000000.00, 'xe_dien.jpg', 'Chờ duyệt', 'Đang bán', '2025-04-10 00:00:00', '2025-05-11 16:35:34', ''),
(4, 2, 4, 'Phụ tùng xe máy', 'Phụ tùng xe máy mới 90%.', 500000.00, 'phu_tung_xe.jpg, phu_tung_xe2.jpg', 'Đã duyệt', 'Đang bán', '2025-04-10 00:00:00', '2025-04-10 00:00:00', ''),
(6, 2, 6, 'Laptop Dell', 'Laptop Dell i7, ram 16GB.', 15000000.00, 'laptop.jpg', 'Đã duyệt', 'Đang bán', '2025-04-10 00:00:00', '2025-04-10 00:00:00', ''),
(7, 3, 7, 'iPhone 12', 'Điện thoại iPhone 12.', 18000000.00, 'iphone.jpg', 'Đã duyệt', 'Đang bán', '2025-04-10 00:00:00', '2025-04-10 00:00:00', ''),
(8, 2, 8, 'iPad Pro', 'Máy tính bảng iPad Pro.', 22000000.00, 'ipad.jpg', 'Đã duyệt', 'Đang bán', '2025-04-10 00:00:00', '2025-04-10 00:00:00', ''),
(9, 2, 9, 'Máy ảnh Canon', 'Máy ảnh Canon EOS.', 12000000.00, 'may_anh.jpg', 'Đã duyệt', 'Đang bán', '2025-04-10 00:00:00', '2025-04-10 00:00:00', ''),
(10, 2, 10, 'Thiết bị thông minh', 'Đồng hồ thông minh Samsung.', 5000000.00, 'dongho.jpg', 'Đã duyệt', 'Đang bán', '2025-04-10 00:00:00', '2025-04-10 00:00:00', ''),
(11, 1, 12, 'Quần jean nam', 'Quần jean size 32.', 400000.00, 'quan_jean.jpg, quan_jean2.jpg', 'Chờ duyệt', 'Đang bán', '2025-04-10 00:00:00', '2025-05-13 21:28:28', ''),
(12, 1, 12, 'Áo thun nữ', 'Áo thun cotton nữ.', 300000.00, 'ao_thun.jpg', 'Đã duyệt', 'Đã bán', '2025-04-10 00:00:00', '2025-05-11 12:19:08', ''),
(13, 1, 13, 'Túi xách da', 'Túi xách da cao cấp.', 2500000.00, 'tui_xach.jpg', 'Đã duyệt', 'Đã bán', '2025-04-10 00:00:00', '2025-05-11 12:06:06', ''),
(14, 1, 14, 'Dép sandal', 'Dép sandal thoáng mát.', 200000.00, 'dep.jpg', 'Đã duyệt', 'Đã ẩn', '2025-04-10 00:00:00', '2025-05-11 12:15:18', ''),
(15, 1, 15, 'Mũ bảo hiểm', 'Mũ bảo hiểm chất lượng.', 400000.00, 'mu_bao_hiem.jpg', 'Đã duyệt', 'Đã bán', '2025-04-10 00:00:00', '2025-05-11 12:12:40', ''),
(16, 2, 16, 'Bàn làm việc', 'Bàn làm việc gỗ công nghiệp.', 1500000.00, 'ban.jpg', 'Đã duyệt', 'Đang bán', '2025-04-10 00:00:00', '2025-04-10 00:00:00', ''),
(17, 2, 17, 'Tủ quần áo', 'Tủ quần áo 3 cánh.', 2500000.00, 'tu.jpg', 'Đã duyệt', 'Đang bán', '2025-04-10 00:00:00', '2025-04-10 00:00:00', ''),
(18, 2, 18, 'Nồi cơm điện', 'Nồi cơm điện Sharp.', 700000.00, 'noi_com.jpg', 'Đã duyệt', 'Đang bán', '2025-04-10 00:00:00', '2025-04-10 00:00:00', ''),
(19, 2, 19, 'Đèn trang trí', 'Đèn trang trí phòng khách.', 1200000.00, 'den.jpg', 'Đã duyệt', 'Đang bán', '2025-04-10 00:00:00', '2025-04-10 00:00:00', ''),
(20, 2, 20, 'Nội thất khác', 'Nội thất các loại.', 1000000.00, 'noi_that_khac.jpg', 'Đã duyệt', 'Đang bán', '2025-04-10 00:00:00', '2025-04-10 00:00:00', ''),
(21, 1, 21, 'Guitar Yamaha', 'Đàn guitar Yamaha F310.', 3000000.00, 'guitar.jpg', 'Đã duyệt', 'Đã ẩn', '2025-04-10 00:00:00', '2025-05-03 00:10:24', ''),
(22, 1, 22, 'Bóng đá FIFA', 'Bóng đá tiêu chuẩn FIFA.', 500000.00, 'bong.jpg', 'Đã duyệt', 'Đã bán', '2025-04-10 00:00:00', '2025-04-10 00:00:00', ''),
(23, 1, 23, 'Máy chơi game PS5', 'Máy chơi game PlayStation 5.', 15000000.00, 'ps5.jpg', 'Chờ duyệt', 'Đang bán', '2025-04-10 00:00:00', '2025-04-10 00:00:00', ''),
(24, 1, 24, 'Giải trí khác', 'Các sản phẩm giải trí khác.', 2000000.00, 'giai_tri_khac.jpg', 'Từ chối', 'Đang bán', '2025-04-10 00:00:00', '2025-04-10 00:00:00', ''),
(25, 2, 25, 'Sản phẩm khác', 'Các sản phẩm chưa phân loại.', 500000.00, 'khac.jpg', 'Đã duyệt', 'Đang bán', '2025-04-10 00:00:00', '2025-04-10 00:00:00', ''),
(26, 1, 1, 'Xe Ab', '2342342\r\nfsdfssfdf', 4234234.00, '680d5d7608f00.jpg,680d5d7609025.jpg', 'Đã duyệt', 'Đã bán', '2025-04-27 00:25:58', '2025-04-27 05:25:58', ''),
(27, 1, 1, 'Xe Ab', 'dsgdgdsgdfgdsfg', 99999999.99, '6814d5e03b0d1.jpg,6814d5e03b1fe.jpg', 'Đã duyệt', 'Đang bán', '2025-05-02 16:25:36', '2025-05-03 00:03:52', ''),
(28, 1, 1, '53453245', 'sdfgsdfgsdf', 4124124.00, '6814e1d0f07e8.jpg,6814e1d0f08fc.jpg', 'Đã duyệt', 'Đã bán', '2025-05-02 17:16:32', '2025-05-11 12:22:19', ''),
(29, 1, 1, '53453245', 'fsdgfsdgsd', 234234.00, '6814e26deda7a.jpg,6814e26dedbdf.jpg', 'Đã duyệt', 'Đã ẩn', '2025-05-02 17:19:09', '2025-05-11 12:24:53', ''),
(30, 1, 1, 'Năm A', 'Sản phầm này còn ngon, chưa qua chỉnh sửa gì cả', 12312993.00, '6814f137b655b.jpg,6814f137b6857.jpg', 'Chờ duyệt', 'Đang bán', '2025-05-02 18:22:15', '2025-05-11 16:37:59', ''),
(31, 1, 1, '5324vbnvn', '2342342ưerwerwerw', 2312312.00, '6814f235a56d6.jpg,6814f235a5b11.jpg', 'Chờ duyệt', 'Đang bán', '2025-05-02 18:26:29', '2025-05-11 17:19:15', ''),
(32, 1, 1, '5324vbnvn', '423423423423', 423423.00, '681acc8c4c9c5.png,681acc8c4caf9.png', 'Đã duyệt', 'Đã bán', '2025-05-07 04:59:24', '2025-05-11 12:05:18', ''),
(33, 1, 1, 'Ô tô Toyota', '345342532453', 5345345.00, '681ace4b7d229.jpg,681ace4b7d422.jpg', 'Đã duyệt', 'Đã ẩn', '2025-05-07 05:06:51', '2025-05-11 12:03:33', ''),
(34, 1, 2, 'Ô tô Toyota', 'ưerwsefsfww', 2342342.00, '681acfe024e45.png,681acfe024f77.png', 'Đã duyệt', 'Đã bán', '2025-05-07 05:13:36', '2025-05-11 12:03:15', ''),
(35, 1, 1, 'Ô tô Toyotaa', '2342342342342', 23423423.00, '681ad5dc05b32.jpg,681ad5dc05c79.jpg', 'Chờ duyệt', 'Đang bán', '2025-05-07 05:39:08', '2025-05-11 11:15:23', ''),
(36, 1, 1, 'Ô tô Toyota', '53245324', 3453453.00, '681ad72b291f7.png,681ad72b29377.png', 'Chờ duyệt', 'Đã bán', '2025-05-07 05:44:43', '2025-05-11 11:47:18', ''),
(37, 1, 1, 'Ô tô Toyota', 'sfsadfasfasdf', 99999999.99, '681fa0302ef68.png,681fa0302f06d.png', 'Chờ duyệt', 'Đang bán', '2025-05-10 20:51:28', '2025-05-11 01:51:28', '');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `taikhoan_chuyentien`
--

CREATE TABLE `taikhoan_chuyentien` (
  `id` int(11) NOT NULL,
  `id_ck` int(11) NOT NULL,
  `id_nguoi_dung` int(11) NOT NULL,
  `so_du` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `taikhoan_chuyentien`
--

INSERT INTO `taikhoan_chuyentien` (`id`, `id_ck`, `id_nguoi_dung`, `so_du`) VALUES
(1, 1111, 1, 79000),
(2, 1112, 2, 10000),
(3, 1113, 3, 100000),
(4, 1114, 4, 50000);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tin_nhan`
--

CREATE TABLE `tin_nhan` (
  `id` int(11) NOT NULL,
  `id_nguoi_gui` int(11) NOT NULL,
  `id_nguoi_nhan` int(11) NOT NULL,
  `id_san_pham` int(11) NOT NULL,
  `noi_dung` text NOT NULL,
  `ngay_tao` date DEFAULT curdate(),
  `thoi_gian` datetime DEFAULT current_timestamp(),
  `da_doc` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tin_nhan`
--

INSERT INTO `tin_nhan` (`id`, `id_nguoi_gui`, `id_nguoi_nhan`, `id_san_pham`, `noi_dung`, `ngay_tao`, `thoi_gian`, `da_doc`) VALUES
(49, 1, 3, 7, 'chat_1_3.json', '2025-05-07', '2025-05-07 09:13:34', 0),
(52, 2, 1, 31, 'chat_1_2.json', '2025-05-07', '2025-05-07 09:42:58', 0),
(53, 1, 1, 30, 'chat_1_1.json', '2025-05-08', '2025-05-08 22:50:38', 0),
(54, 2, 3, 7, 'chat_2_3.json', '2025-05-09', '2025-05-09 11:47:21', 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `vai_tro`
--

CREATE TABLE `vai_tro` (
  `id` int(11) NOT NULL,
  `ten_vai_tro` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `vai_tro`
--

INSERT INTO `vai_tro` (`id`, `ten_vai_tro`) VALUES
(1, 'Quản trị'),
(2, 'Người dùng');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `chi_phi`
--
ALTER TABLE `chi_phi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ma_chi_phi` (`ma_chi_phi`),
  ADD KEY `id_danh_muc` (`id_danh_muc`),
  ADD KEY `id_phuong_thuc_thanh_toan` (`id_phuong_thuc_thanh_toan`),
  ADD KEY `nguoi_tao` (`nguoi_tao`);

--
-- Chỉ mục cho bảng `danh_gia`
--
ALTER TABLE `danh_gia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_nguoi_danh_gia` (`id_nguoi_danh_gia`),
  ADD KEY `id_nguoi_duoc_danh_gia` (`id_nguoi_duoc_danh_gia`),
  ADD KEY `danh_gia_ibfk_3` (`id_san_pham`);

--
-- Chỉ mục cho bảng `doanh_thu`
--
ALTER TABLE `doanh_thu`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ma_doanh_thu` (`ma_doanh_thu`),
  ADD KEY `id_khach_hang` (`id_khach_hang`),
  ADD KEY `id_danh_muc` (`id_danh_muc`),
  ADD KEY `id_phuong_thuc_thanh_toan` (`id_phuong_thuc_thanh_toan`),
  ADD KEY `nguoi_tao` (`nguoi_tao`);

--
-- Chỉ mục cho bảng `giao_dich`
--
ALTER TABLE `giao_dich`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_nguoi_dung` (`id_nguoi_dung`);

--
-- Chỉ mục cho bảng `lich_su_chuyen_khoan`
--
ALTER TABLE `lich_su_chuyen_khoan`
  ADD PRIMARY KEY (`id_lich_su`),
  ADD KEY `fk_nguoidung_lichsu` (`id_nguoi_dung`);

--
-- Chỉ mục cho bảng `lich_su_day_tin`
--
ALTER TABLE `lich_su_day_tin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_san_pham` (`id_san_pham`),
  ADD KEY `id_nguoi_dung` (`id_nguoi_dung`);

--
-- Chỉ mục cho bảng `lich_su_phi_dang_bai`
--
ALTER TABLE `lich_su_phi_dang_bai`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_san_pham` (`id_san_pham`),
  ADD KEY `id_nguoi_dung` (`id_nguoi_dung`);

--
-- Chỉ mục cho bảng `loai_san_pham`
--
ALTER TABLE `loai_san_pham`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_loai_san_pham_cha` (`id_loai_san_pham_cha`);

--
-- Chỉ mục cho bảng `loai_san_pham_cha`
--
ALTER TABLE `loai_san_pham_cha`
  ADD PRIMARY KEY (`id_loai_san_pham_cha`);

--
-- Chỉ mục cho bảng `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `id_vai_tro` (`id_vai_tro`);

--
-- Chỉ mục cho bảng `phuong_thuc_thanh_toan`
--
ALTER TABLE `phuong_thuc_thanh_toan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ten_phuong_thuc` (`ten_phuong_thuc`);

--
-- Chỉ mục cho bảng `san_pham`
--
ALTER TABLE `san_pham`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_nguoi_ban` (`id_nguoi_dung`),
  ADD KEY `id_loai_san_pham` (`id_loai_san_pham`);

--
-- Chỉ mục cho bảng `taikhoan_chuyentien`
--
ALTER TABLE `taikhoan_chuyentien`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ck` (`id_nguoi_dung`);

--
-- Chỉ mục cho bảng `tin_nhan`
--
ALTER TABLE `tin_nhan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_nguoi_gui` (`id_nguoi_gui`),
  ADD KEY `id_nguoi_nhan` (`id_nguoi_nhan`),
  ADD KEY `id_san_pham` (`id_san_pham`);

--
-- Chỉ mục cho bảng `vai_tro`
--
ALTER TABLE `vai_tro`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `chi_phi`
--
ALTER TABLE `chi_phi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `danh_gia`
--
ALTER TABLE `danh_gia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `doanh_thu`
--
ALTER TABLE `doanh_thu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `giao_dich`
--
ALTER TABLE `giao_dich`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `lich_su_chuyen_khoan`
--
ALTER TABLE `lich_su_chuyen_khoan`
  MODIFY `id_lich_su` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `lich_su_day_tin`
--
ALTER TABLE `lich_su_day_tin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT cho bảng `lich_su_phi_dang_bai`
--
ALTER TABLE `lich_su_phi_dang_bai`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT cho bảng `loai_san_pham`
--
ALTER TABLE `loai_san_pham`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT cho bảng `loai_san_pham_cha`
--
ALTER TABLE `loai_san_pham_cha`
  MODIFY `id_loai_san_pham_cha` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT cho bảng `phuong_thuc_thanh_toan`
--
ALTER TABLE `phuong_thuc_thanh_toan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `san_pham`
--
ALTER TABLE `san_pham`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT cho bảng `taikhoan_chuyentien`
--
ALTER TABLE `taikhoan_chuyentien`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `tin_nhan`
--
ALTER TABLE `tin_nhan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT cho bảng `vai_tro`
--
ALTER TABLE `vai_tro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `danh_gia`
--
ALTER TABLE `danh_gia`
  ADD CONSTRAINT `danh_gia_ibfk_1` FOREIGN KEY (`id_nguoi_danh_gia`) REFERENCES `nguoi_dung` (`id`),
  ADD CONSTRAINT `danh_gia_ibfk_2` FOREIGN KEY (`id_nguoi_duoc_danh_gia`) REFERENCES `nguoi_dung` (`id`),
  ADD CONSTRAINT `danh_gia_ibfk_3` FOREIGN KEY (`id_san_pham`) REFERENCES `san_pham` (`id`);

--
-- Các ràng buộc cho bảng `giao_dich`
--
ALTER TABLE `giao_dich`
  ADD CONSTRAINT `giao_dich_ibfk_1` FOREIGN KEY (`id_nguoi_dung`) REFERENCES `nguoi_dung` (`id`);

--
-- Các ràng buộc cho bảng `lich_su_chuyen_khoan`
--
ALTER TABLE `lich_su_chuyen_khoan`
  ADD CONSTRAINT `fk_nguoidung_lichsu` FOREIGN KEY (`id_nguoi_dung`) REFERENCES `nguoi_dung` (`id`);

--
-- Các ràng buộc cho bảng `lich_su_day_tin`
--
ALTER TABLE `lich_su_day_tin`
  ADD CONSTRAINT `lich_su_day_tin_ibfk_1` FOREIGN KEY (`id_san_pham`) REFERENCES `san_pham` (`id`),
  ADD CONSTRAINT `lich_su_day_tin_ibfk_2` FOREIGN KEY (`id_nguoi_dung`) REFERENCES `nguoi_dung` (`id`);

--
-- Các ràng buộc cho bảng `lich_su_phi_dang_bai`
--
ALTER TABLE `lich_su_phi_dang_bai`
  ADD CONSTRAINT `lich_su_phi_dang_bai_ibfk_1` FOREIGN KEY (`id_san_pham`) REFERENCES `san_pham` (`id`),
  ADD CONSTRAINT `lich_su_phi_dang_bai_ibfk_2` FOREIGN KEY (`id_nguoi_dung`) REFERENCES `nguoi_dung` (`id`);

--
-- Các ràng buộc cho bảng `loai_san_pham`
--
ALTER TABLE `loai_san_pham`
  ADD CONSTRAINT `loai_san_pham_ibfk_1` FOREIGN KEY (`id_loai_san_pham_cha`) REFERENCES `loai_san_pham` (`id`);

--
-- Các ràng buộc cho bảng `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  ADD CONSTRAINT `nguoi_dung_ibfk_1` FOREIGN KEY (`id_vai_tro`) REFERENCES `vai_tro` (`id`);

--
-- Các ràng buộc cho bảng `san_pham`
--
ALTER TABLE `san_pham`
  ADD CONSTRAINT `san_pham_ibfk_1` FOREIGN KEY (`id_nguoi_dung`) REFERENCES `nguoi_dung` (`id`),
  ADD CONSTRAINT `san_pham_ibfk_2` FOREIGN KEY (`id_loai_san_pham`) REFERENCES `loai_san_pham` (`id`);

--
-- Các ràng buộc cho bảng `taikhoan_chuyentien`
--
ALTER TABLE `taikhoan_chuyentien`
  ADD CONSTRAINT `fk_ck` FOREIGN KEY (`id_nguoi_dung`) REFERENCES `nguoi_dung` (`id`);

--
-- Các ràng buộc cho bảng `tin_nhan`
--
ALTER TABLE `tin_nhan`
  ADD CONSTRAINT `tin_nhan_ibfk_1` FOREIGN KEY (`id_nguoi_gui`) REFERENCES `nguoi_dung` (`id`),
  ADD CONSTRAINT `tin_nhan_ibfk_2` FOREIGN KEY (`id_nguoi_nhan`) REFERENCES `nguoi_dung` (`id`),
  ADD CONSTRAINT `tin_nhan_ibfk_3` FOREIGN KEY (`id_san_pham`) REFERENCES `san_pham` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
