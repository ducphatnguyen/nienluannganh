-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th1 29, 2023 lúc 02:26 PM
-- Phiên bản máy phục vụ: 10.4.27-MariaDB
-- Phiên bản PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `lms`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_admin`
--

CREATE TABLE `tbl_admin` (
  `admin_id` int(11) NOT NULL,
  `admin_email` varchar(200) NOT NULL,
  `admin_password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_admin`
--

INSERT INTO `tbl_admin` (`admin_id`, `admin_email`, `admin_password`) VALUES
(1, 'ducadmin@gmail.com', '5f4dcc3b5aa765d61d8327deb882cf99');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_author`
--

CREATE TABLE `tbl_author` (
  `author_id` int(11) NOT NULL,
  `author_name` varchar(200) NOT NULL,
  `author_status` enum('Enable','Disable') NOT NULL,
  `author_created_on` datetime DEFAULT NULL,
  `author_updated_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_author`
--

INSERT INTO `tbl_author` (`author_id`, `author_name`, `author_status`, `author_created_on`, `author_updated_on`) VALUES
(20, 'Tác Giả 1', 'Enable', '2023-01-29 19:52:01', NULL),
(21, 'Tác Giả 2', 'Enable', '2023-01-29 19:52:07', NULL),
(22, 'Tác Giả 3', 'Enable', '2023-01-29 19:52:12', NULL),
(23, 'Tác Giả 4', 'Enable', '2023-01-29 19:52:19', NULL),
(24, 'Tác Giả 5', 'Enable', '2023-01-29 19:52:26', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_book`
--

CREATE TABLE `tbl_book` (
  `book_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `publisher_id` int(11) NOT NULL,
  `location_rack_id` int(11) NOT NULL,
  `book_name` text NOT NULL,
  `book_isbn_number` varchar(30) NOT NULL,
  `book_no_of_copy` int(5) NOT NULL,
  `book_status` enum('Enable','Disable') NOT NULL,
  `book_created_on` datetime DEFAULT NULL,
  `book_updated_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_book`
--

INSERT INTO `tbl_book` (`book_id`, `category_id`, `author_id`, `publisher_id`, `location_rack_id`, `book_name`, `book_isbn_number`, `book_no_of_copy`, `book_status`, `book_created_on`, `book_updated_on`) VALUES
(18, 9, 20, 1, 12, 'Sách 1', '3880531013', 4, 'Enable', '2023-01-29 19:54:51', '2023-01-29 19:56:56'),
(19, 7, 21, 2, 13, 'Sách 2', '3880531014', 4, 'Enable', '2023-01-29 19:55:37', '2023-01-29 19:57:07'),
(20, 8, 22, 3, 14, 'Sách 3', '3880531015', 4, 'Enable', '2023-01-29 19:55:54', '2023-01-29 19:57:12'),
(21, 10, 23, 4, 15, 'Sách 4', '3880531016', 5, 'Enable', '2023-01-29 19:56:15', NULL),
(22, 11, 24, 5, 16, 'Sách 5', '3880531017', 5, 'Enable', '2023-01-29 19:56:39', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_category`
--

CREATE TABLE `tbl_category` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(200) NOT NULL,
  `category_status` enum('Enable','Disable') NOT NULL,
  `category_created_on` datetime DEFAULT NULL,
  `category_updated_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_category`
--

INSERT INTO `tbl_category` (`category_id`, `category_name`, `category_status`, `category_created_on`, `category_updated_on`) VALUES
(7, 'Thể Loại 2', 'Enable', '2023-01-29 19:50:07', NULL),
(8, 'Thể Loại 3', 'Enable', '2023-01-29 19:50:18', NULL),
(9, 'Thể Loại 1', 'Enable', '2023-01-29 19:51:36', NULL),
(10, 'Thể Loại 4', 'Enable', '2023-01-29 19:51:46', NULL),
(11, 'Thể Loại 5', 'Enable', '2023-01-29 19:51:53', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_issue_book`
--

CREATE TABLE `tbl_issue_book` (
  `issue_book_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `issue_date_time` datetime DEFAULT NULL,
  `expected_return_date` datetime DEFAULT NULL,
  `return_date_time` datetime DEFAULT NULL,
  `book_fines` float NOT NULL,
  `book_issue_status` enum('Issue','Return','Not Return') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_issue_book`
--

INSERT INTO `tbl_issue_book` (`issue_book_id`, `book_id`, `user_id`, `issue_date_time`, `expected_return_date`, `return_date_time`, `book_fines`, `book_issue_status`) VALUES
(10, 18, 16, '2023-01-29 19:56:56', '2023-02-08 19:56:56', NULL, 0, 'Issue'),
(11, 19, 16, '2023-01-29 19:57:07', '2023-02-08 19:57:07', NULL, 0, 'Issue'),
(12, 20, 16, '2023-01-29 19:57:12', '2023-02-08 19:57:12', NULL, 0, 'Issue');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_location_rack`
--

CREATE TABLE `tbl_location_rack` (
  `location_rack_id` int(11) NOT NULL,
  `location_rack_name` varchar(200) NOT NULL,
  `location_rack_status` enum('Enable','Disable') NOT NULL,
  `location_rack_created_on` datetime DEFAULT NULL,
  `location_rack_updated_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_location_rack`
--

INSERT INTO `tbl_location_rack` (`location_rack_id`, `location_rack_name`, `location_rack_status`, `location_rack_created_on`, `location_rack_updated_on`) VALUES
(12, 'A1', 'Enable', '2023-01-29 19:53:15', NULL),
(13, 'A2', 'Enable', '2023-01-29 19:53:20', NULL),
(14, 'A3', 'Enable', '2023-01-29 19:53:24', NULL),
(15, 'B1', 'Enable', '2023-01-29 19:53:27', NULL),
(16, 'B2', 'Enable', '2023-01-29 19:53:32', NULL),
(17, 'B3', 'Enable', '2023-01-29 19:53:37', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_publisher`
--

CREATE TABLE `tbl_publisher` (
  `publisher_id` int(11) NOT NULL,
  `publisher_name` varchar(200) NOT NULL,
  `publisher_status` enum('Enable','Disable') NOT NULL,
  `publisher_created_on` datetime DEFAULT NULL,
  `publisher_updated_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_publisher`
--

INSERT INTO `tbl_publisher` (`publisher_id`, `publisher_name`, `publisher_status`, `publisher_created_on`, `publisher_updated_on`) VALUES
(1, 'NXB 1', 'Enable', '2023-01-29 19:52:43', NULL),
(2, 'NXB 2', 'Enable', '2023-01-29 19:52:49', NULL),
(3, 'NXB 3', 'Enable', '2023-01-29 19:52:54', NULL),
(4, 'NXB 4', 'Enable', '2023-01-29 19:52:59', NULL),
(5, 'NXB 5', 'Enable', '2023-01-29 19:53:06', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_setting`
--

CREATE TABLE `tbl_setting` (
  `setting_id` int(11) NOT NULL,
  `library_name` varchar(200) NOT NULL,
  `library_address` text NOT NULL,
  `library_contact_number` varchar(30) NOT NULL,
  `library_email_address` varchar(100) NOT NULL,
  `library_total_book_issue_day` int(11) NOT NULL,
  `library_one_day_fine` float NOT NULL,
  `library_issue_total_book_per_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_setting`
--

INSERT INTO `tbl_setting` (`setting_id`, `library_name`, `library_address`, `library_contact_number`, `library_email_address`, `library_total_book_issue_day`, `library_one_day_fine`, `library_issue_total_book_per_user`) VALUES
(1, 'ABC Library', 'Business Street 105, NY 0256', '7539518521', 'abc_library@gmail.com', 10, 1, 3);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_user`
--

CREATE TABLE `tbl_user` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(200) NOT NULL,
  `user_address` text NOT NULL,
  `user_contact_no` varchar(30) NOT NULL,
  `user_profile` varchar(100) NOT NULL,
  `user_email_address` varchar(200) NOT NULL,
  `user_password` varchar(50) NOT NULL,
  `user_verification_code` varchar(100) NOT NULL,
  `user_verification_status` enum('No','Yes') NOT NULL,
  `user_unique_id` varchar(30) NOT NULL,
  `user_status` enum('Enable','Disable') NOT NULL,
  `user_created_on` varchar(30) NOT NULL,
  `user_updated_on` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_user`
--

INSERT INTO `tbl_user` (`user_id`, `user_name`, `user_address`, `user_contact_no`, `user_profile`, `user_email_address`, `user_password`, `user_verification_code`, `user_verification_status`, `user_unique_id`, `user_status`, `user_created_on`, `user_updated_on`) VALUES
(16, 'Nguyễn Đức', 'Vĩnh Long', '0789691002', '1636699865-32499.jpg', 'ducb1910213@student.ctu.edu.vn', '25f9e794323b453885f5181f1b624d0b', 'fda591015a749feb2c3aff48334f901e', 'Yes', 'U50862330', 'Enable', '2023-01-29 19:44:33', '');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `tbl_admin`
--
ALTER TABLE `tbl_admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Chỉ mục cho bảng `tbl_author`
--
ALTER TABLE `tbl_author`
  ADD PRIMARY KEY (`author_id`);

--
-- Chỉ mục cho bảng `tbl_book`
--
ALTER TABLE `tbl_book`
  ADD PRIMARY KEY (`book_id`),
  ADD KEY `author_id` (`author_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `publisher_id` (`publisher_id`),
  ADD KEY `location_rack_id` (`location_rack_id`);

--
-- Chỉ mục cho bảng `tbl_category`
--
ALTER TABLE `tbl_category`
  ADD PRIMARY KEY (`category_id`);

--
-- Chỉ mục cho bảng `tbl_issue_book`
--
ALTER TABLE `tbl_issue_book`
  ADD PRIMARY KEY (`issue_book_id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `tbl_location_rack`
--
ALTER TABLE `tbl_location_rack`
  ADD PRIMARY KEY (`location_rack_id`);

--
-- Chỉ mục cho bảng `tbl_publisher`
--
ALTER TABLE `tbl_publisher`
  ADD PRIMARY KEY (`publisher_id`);

--
-- Chỉ mục cho bảng `tbl_setting`
--
ALTER TABLE `tbl_setting`
  ADD PRIMARY KEY (`setting_id`);

--
-- Chỉ mục cho bảng `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `tbl_admin`
--
ALTER TABLE `tbl_admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `tbl_author`
--
ALTER TABLE `tbl_author`
  MODIFY `author_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT cho bảng `tbl_book`
--
ALTER TABLE `tbl_book`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT cho bảng `tbl_category`
--
ALTER TABLE `tbl_category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `tbl_issue_book`
--
ALTER TABLE `tbl_issue_book`
  MODIFY `issue_book_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `tbl_location_rack`
--
ALTER TABLE `tbl_location_rack`
  MODIFY `location_rack_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT cho bảng `tbl_publisher`
--
ALTER TABLE `tbl_publisher`
  MODIFY `publisher_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `tbl_setting`
--
ALTER TABLE `tbl_setting`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `tbl_book`
--
ALTER TABLE `tbl_book`
  ADD CONSTRAINT `tbl_book_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `tbl_author` (`author_id`),
  ADD CONSTRAINT `tbl_book_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `tbl_category` (`category_id`),
  ADD CONSTRAINT `tbl_book_ibfk_3` FOREIGN KEY (`publisher_id`) REFERENCES `tbl_publisher` (`publisher_id`),
  ADD CONSTRAINT `tbl_book_ibfk_4` FOREIGN KEY (`location_rack_id`) REFERENCES `tbl_location_rack` (`location_rack_id`);

--
-- Các ràng buộc cho bảng `tbl_issue_book`
--
ALTER TABLE `tbl_issue_book`
  ADD CONSTRAINT `tbl_issue_book_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `tbl_book` (`book_id`),
  ADD CONSTRAINT `tbl_issue_book_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
