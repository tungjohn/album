-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th12 03, 2020 lúc 05:08 AM
-- Phiên bản máy phục vụ: 10.4.11-MariaDB
-- Phiên bản PHP: 7.4.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `nukeviet4`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nv4_vi_album_image`
--

CREATE TABLE `nv4_vi_album_image` (
  `image_id` int(11) NOT NULL COMMENT 'id ảnh',
  `album_id` int(11) NOT NULL COMMENT 'id album',
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ảnh',
  `image_desc` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'mô tả',
  `creat_by_userid` int(11) NOT NULL COMMENT 'tạo bởi user id',
  `active` tinyint(2) NOT NULL DEFAULT 1 COMMENT '0: không hiển thị\r\n1: hiển thị',
  `create_time` int(11) NOT NULL COMMENT 'thời gian tạo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `nv4_vi_album_image`
--
ALTER TABLE `nv4_vi_album_image`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `album_id` (`album_id`),
  ADD KEY `creat_by_userid` (`creat_by_userid`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `nv4_vi_album_image`
--
ALTER TABLE `nv4_vi_album_image`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id ảnh';
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
