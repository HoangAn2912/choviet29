-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th5 12, 2025 lúc 05:23 PM
-- Phiên bản máy phục vụ: 10.4.28-MariaDB
-- Phiên bản PHP: 8.2.4

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
-- Cấu trúc bảng cho bảng `lich_su_chuyen_khoan`
--

CREATE TABLE `lich_su_chuyen_khoan` (
  `id_lich_su` int(11) NOT NULL,
  `noi_dung_ck` varchar(255) NOT NULL,
  `hinh_anh_ck` varchar(255) NOT NULL,
  `trang_thai_ck` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `lich_su_chuyen_khoan`
--

INSERT INTO `lich_su_chuyen_khoan` (`id_lich_su`, `noi_dung_ck`, `hinh_anh_ck`, `trang_thai_ck`) VALUES
(1, 'NAP 500000 Nguyen Van A', 'transfer1.jpg', '0'),
(2, 'NAP 300000 Tran Thi B', 'transfer2.jpg', '0'),
(3, 'NAP 750000 Le Van C', 'transfer3.jpg', '2'),
(4, 'NAP 250000 Pham Thi D', 'transfer4.jpg', '1'),
(5, 'NAP 500000 Nguyen Van A', 'transfer5.jpg', '1'),
(6, 'NAP 200000 Tran Thi B', 'transfer6.jpg', '1'),
(7, 'NAP 1000000 Hoang Van E', 'transfer7.jpg', '2'),
(8, 'NAP 250000 Le Van C', '', '2'),
(9, 'NAP 150000 Pham Thi D', 'transfer9.jpg', '1'),
(10, 'NAP 500000 Hoang Van E', 'transfer10.jpg', '0');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `lich_su_chuyen_khoan`
--
ALTER TABLE `lich_su_chuyen_khoan`
  ADD PRIMARY KEY (`id_lich_su`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `lich_su_chuyen_khoan`
--
ALTER TABLE `lich_su_chuyen_khoan`
  MODIFY `id_lich_su` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
