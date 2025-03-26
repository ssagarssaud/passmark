-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 26, 2025 at 04:36 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `passmark`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookmarks`
--

CREATE TABLE `bookmarks` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `url` varchar(255) NOT NULL,
  `website_name` varchar(255) NOT NULL,
  `website_photo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `bookmarks`
--

INSERT INTO `bookmarks` (`id`, `user_id`, `url`, `website_name`, `website_photo`, `created_at`) VALUES
(55, 24, 'https://www.instagram.com/', 'Instagram', '../uploads/67b2e1d287644_instagram.jpg', '2025-02-17 07:14:26'),
(59, 27, 'https://www.instagram.com/', 'Instagram', '../uploads/67b435413f01a_instagram.jpg', '2025-02-18 07:22:41'),
(62, 32, 'https://www.facebook.com/', 'Facebookkkkk', '../uploads/67bdf453e80ba_facebook.png', '2025-02-25 16:48:19');

-- --------------------------------------------------------

--
-- Table structure for table `passwords`
--

CREATE TABLE `passwords` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `site_name` varchar(255) NOT NULL,
  `site_url` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `encrypted_password` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `passwords`
--

INSERT INTO `passwords` (`id`, `user_id`, `site_name`, `site_url`, `username`, `encrypted_password`, `created_at`) VALUES
(24, 24, 'Facebook', 'https://www.facebook.com/', 'Sagar Saud', 'Hl5F+ixW5bo=', '2025-02-15 10:27:13'),
(30, 27, 'Instagram', 'https://www.instagram.com/', 'sagar__saud', 'Hl5F+ixW5bo=', '2025-02-18 07:11:53'),
(32, 30, 'Facebook', 'https://www.facebook.com/', 'Sagar Saud', 'Hl5F+ixW5bo=', '2025-02-24 17:02:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int UNSIGNED NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `remember_token` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `created_at`, `remember_token`) VALUES
(24, 'Sagar', 'Saud', 'sagarsaud7021@gmail.com', '$2y$10$LybUVIgH07Wqtg1LAhQGqeUBSoKXgR9uF.xkRVsE8hwuzT3AdO7U2', '2025-02-15 10:25:27', '269590af0fe7524ee32af7a1de6410d2e8644ee11979fe843bad99ee80a22c15'),
(25, 'Gunjan', 'CB', 'gunjancb@gmail.com', '$2y$10$WNeJhPfJPFEBDAmA/89v9.cmw6iBIZsx04hZ0eUqoBI43t1DPuaKy', '2025-02-17 01:57:36', NULL),
(26, 'Sagar', 'Sagar', 'sagarsaud111@gmail.com', '$2y$10$D5efluBauRfwCRXKtXrdjOCD1.O4e4LoJGlf9mgz8MQPoAA1z7Xf2', '2025-02-18 06:37:30', NULL),
(27, 'Sagar', 'Saud', 'sagarsaud123@gmail.com', '$2y$10$BkOh2thkv9RhOCzaSapYP.v.jH6Q/HSyHjyHrASLVJyXl8/Nmouwe', '2025-02-18 06:38:53', 'e529c596f53e0944200c850e8ecfb6638d526a63cf93e38f3fd68f6231fff78f'),
(28, 'sagar', 'saud', 'sagarsaud@gmail.com', '$2y$10$0o8B2Xe0drXerNTsCpuDse3ae82BJ5P3Yqs18gWqteG5YTlxnWhtK', '2025-02-18 14:36:48', NULL),
(29, 'sagar', 'saud', 'sagars@mail.com', '$2y$10$bza4SpHSRlOnR4ZCp0hf3OjGWXr/Kidjm67IV7unl8xn.kQi8C49G', '2025-02-18 14:37:40', NULL),
(30, 'Arjun', 'Bhandari', 'arjun@gmail.com', '$2y$10$IPvUHz33RyPmMyWzLsW0MeH7eiokx0UpN/vQPfwlsyYV/yMWD6AEe', '2025-02-24 16:58:10', '1e0390e70f602ac0e43f9fb56759240f8cce90761b0d478470aa926111c16640'),
(31, 'Manoj', 'Saud', 'sagar1@gmail.com', '$2y$10$owWq.hApA1V7yy87Pvj74.Ih5I7EaYSIIoP5CQK4bcU/CCzk8RVDq', '2025-02-25 03:47:02', '89ea33d7c0e036c6afcf390f61c8c50aad5e60f467c9859516c92c4545a5f15f'),
(32, 'Bikash', 'Bohara', 'bikashbohara@gmail.com', '$2y$10$9Zytao9OCZyDT2aM7s7AfuFrEimqhjNTglOHQk9ikpJdByScJ5Qoq', '2025-02-25 16:47:35', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookmarks`
--
ALTER TABLE `bookmarks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `passwords`
--
ALTER TABLE `passwords`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookmarks`
--
ALTER TABLE `bookmarks`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `passwords`
--
ALTER TABLE `passwords`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookmarks`
--
ALTER TABLE `bookmarks`
  ADD CONSTRAINT `bookmarks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `passwords`
--
ALTER TABLE `passwords`
  ADD CONSTRAINT `passwords_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
