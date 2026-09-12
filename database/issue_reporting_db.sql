-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 12, 2026 at 11:08 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `issue_reporting_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `report_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `detail` text NOT NULL,
  `status` enum('pending','in_progress','resolved') DEFAULT 'pending',
  `report_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`report_id`, `user_id`, `type_id`, `image`, `title`, `location`, `detail`, `status`, `report_date`, `updated_at`) VALUES
(1, 2, 1, 'electric.jpg', 'ไฟห้องเรียนดับ', 'อาคาร 1 ชั้น 2 ห้อง 201', 'หลอดไฟไม่ติดหลายจุด ต้องการให้ช่างเข้าตรวจสอบ', 'pending', '2026-06-28 13:33:11', '2026-06-28 13:33:11'),
(2, 2, 2, 'wifi.jpg', 'อินเทอร์เน็ตใช้งานไม่ได้', 'ห้องคอม 305', 'ไม่สามารถเชื่อมต่อ WiFi ได้หลายเครื่อง', 'resolved', '2026-06-28 13:33:11', '2026-09-12 09:03:11'),
(3, 3, 3, NULL, 'โปรเจคเตอร์เสีย', 'ห้องประชุมใหญ่', 'เปิดเครื่องแล้วไม่มีภาพแสดงผล', 'resolved', '2026-06-28 13:33:11', '2026-06-28 13:33:11'),
(4, 3, 4, 'door.jpg', 'ประตูชำรุด', 'อาคาร 3 ชั้น 1', 'บานพับประตูหลวม ปิดไม่สนิท', 'pending', '2026-06-28 13:33:11', '2026-06-28 13:33:11'),
(5, 4, 5, NULL, 'เก้าอี้เสียหาย', 'ห้องเรียน 402', 'เก้าอี้หักจำนวน 2 ตัว', 'in_progress', '2026-06-28 13:33:11', '2026-06-28 13:33:11'),
(6, 2, 1, 'light_fixed.jpg', 'ไฟห้องเรียนซ่อมเรียบร้อย', 'อาคาร 1 ชั้น 2 ห้อง 201', 'เปลี่ยนหลอดไฟใหม่และตรวจสอบระบบไฟเรียบร้อยแล้ว', 'resolved', '2026-06-28 13:35:37', '2026-06-28 13:35:37'),
(7, 3, 2, 'network_fixed.jpg', 'อินเทอร์เน็ตกลับมาใช้งานได้', 'ห้องคอม 305', 'แก้ไขอุปกรณ์ Network และทดสอบการเชื่อมต่อเรียบร้อย', 'resolved', '2026-06-28 13:35:37', '2026-06-28 13:35:37'),
(8, 4, 3, 'projector_fixed.jpg', 'โปรเจคเตอร์ซ่อมแล้ว', 'ห้องประชุมใหญ่', 'เปลี่ยนสาย HDMI และตรวจสอบการแสดงผลเรียบร้อย', 'resolved', '2026-06-28 13:35:37', '2026-06-28 13:35:37'),
(9, 2, 4, 'door_fixed.jpg', 'ประตูได้รับการซ่อมแซมแล้ว', 'อาคาร 3 ชั้น 1', 'เปลี่ยนบานพับและปรับระดับประตูเรียบร้อย', 'resolved', '2026-06-28 13:35:37', '2026-06-28 13:35:37'),
(10, 3, 5, 'chair_fixed.jpg', 'เปลี่ยนเก้าอี้ใหม่แล้ว', 'ห้องเรียน 402', 'นำเก้าอี้ที่เสียออกและเปลี่ยนใหม่จำนวน 2 ตัว', 'resolved', '2026-06-28 13:35:37', '2026-06-28 13:35:37');

-- --------------------------------------------------------

--
-- Table structure for table `report_type`
--

CREATE TABLE `report_type` (
  `type_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `report_type`
--

INSERT INTO `report_type` (`type_id`, `name`) VALUES
(1, 'ไฟฟ้า'),
(2, 'อินเทอร์เน็ต'),
(3, 'อุปกรณ์เสียหาย'),
(4, 'อาคารสถานที่'),
(5, 'อื่นๆ');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role` enum('admin','staff','user') DEFAULT 'user',
  `token` varchar(255) DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `phone`, `role`, `token`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$ogDXQOsHge15faunVIQWN.3qwnzesAQHxKQoXwhgzkeheBeDMemre', 'nipitpon.thamromgsuk@e-tech.ac.th', '0929970527', 'admin', NULL, '2026-06-16 04:20:44', '2026-09-12 09:03:00'),
(2, 'user1', '$2y$10$ik7ynjkZZf.1FjCCY9zswuBj1kSONRmqYp201oYSIVpjng5ZqpAfa', 'user1@gmail.con', '09xxxxxxxx', 'user', '44a6b649eb0a6ccb32485aac0eb8a940', '2026-06-20 06:08:27', '2026-09-12 09:03:25'),
(3, 'staff1', '$2y$10$Q6xBvwiC3n5LQ88YNPxQ..sWJ3Pii2G7pYAD6s6w9F6W3q5qDKXl6', 'staff01@test.com', '0811111111', 'staff', NULL, '2026-06-28 13:33:11', '2026-09-12 09:03:16'),
(4, 'user2', '$2y$10$8YxcrUAyxo39v8kOGgMR8uPcWFK2TnJ7LErVnbsGP2Ih2OZTqTVpm', 'user02@test.com', '0822222222', 'user', NULL, '2026-06-28 13:33:11', '2026-09-12 08:56:25'),
(5, 'user3', '$2y$10$Vgji6LKH39y.7wzw3OxUl.WnDw3h.JLRRLHFUxnsnwgPKd5FO6bXe', 'user03@test.com', '0833333333', 'user', NULL, '2026-06-28 13:33:11', '2026-09-12 08:59:23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `fk_report_user` (`user_id`),
  ADD KEY `fk_report_type` (`type_id`);

--
-- Indexes for table `report_type`
--
ALTER TABLE `report_type`
  ADD PRIMARY KEY (`type_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `report_type`
--
ALTER TABLE `report_type`
  MODIFY `type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `fk_report_type` FOREIGN KEY (`type_id`) REFERENCES `report_type` (`type_id`),
  ADD CONSTRAINT `fk_report_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
