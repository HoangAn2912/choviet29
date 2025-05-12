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
(1, 1, 1, 1000000),
(2, 2, 2, 500000),
(3, 3, 3, 750000),
(4, 4, 4, 250000),
(5, 5, 5, 0),
(8, 6, 6, 453453),
(9, 7, 7, 27272),
(10, 8, 8, 0),
(11, 9, 3, 0);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `taikhoan_chuyentien`
--
ALTER TABLE `taikhoan_chuyentien`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ck` (`id_nguoi_dung`),
  ADD KEY `id_ck` (`id_ck`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `taikhoan_chuyentien`
--
ALTER TABLE `taikhoan_chuyentien`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `taikhoan_chuyentien`
--
ALTER TABLE `taikhoan_chuyentien`
  ADD CONSTRAINT `fk_ck` FOREIGN KEY (`id_nguoi_dung`) REFERENCES `nguoi_dung` (`id`),
  ADD CONSTRAINT `taikhoan_chuyentien_ibfk_1` FOREIGN KEY (`id_ck`) REFERENCES `lich_su_chuyen_khoan` (`id_lich_su`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
