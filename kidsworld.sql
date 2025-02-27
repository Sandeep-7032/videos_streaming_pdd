-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 24, 2025 at 11:36 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kidsworld`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `category_description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_name`, `category_description`) VALUES
(1, 'Preschool', 'A group for younger children aged 3-5, where basic learning begins.'),
(2, 'Youngers', 'A group for children aged 6-8, focusing on basic skills development.'),
(3, 'Olders', 'A group for children aged 9-12, where more advanced learning activities are introduced.');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `screen_time_limit` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `screen_time_limit`, `created_at`) VALUES
(1, 30, '2025-02-17 05:58:19'),
(2, 2, '2025-02-17 05:58:29'),
(3, 4, '2025-02-17 05:58:45'),
(4, 5, '2025-02-17 06:12:36'),
(5, 6, '2025-02-17 06:22:03'),
(6, 2, '2025-02-18 03:00:43'),
(7, 8, '2025-02-18 03:24:28'),
(8, 1, '2025-02-20 06:11:44'),
(9, 2, '2025-02-20 06:12:20'),
(10, 1, '2025-02-20 06:14:09'),
(11, 1, '2025-02-22 11:11:25');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `child_name` varchar(100) NOT NULL,
  `age` int(11) NOT NULL,
  `dob` date NOT NULL,
  `usertype` int(11) DEFAULT 2,
  `screen_time_limit` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `mobile`, `child_name`, `age`, `dob`, `usertype`, `screen_time_limit`) VALUES
(1, 'padma', 'padma@gmail.com', '123', '8019642061', 'muniswary', 4, '2020-02-20', 2, 0),
(2, 'sandeep1', 'sandeep@gmail.com', '123', '7032467985', 'muniswary', 4, '2020-02-20', 2, 0),
(3, 'ADMIN', 'admin@gmail.com', '123', '8019642061', 'muniswary', 5, '2019-05-05', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `videos`
--

CREATE TABLE `videos` (
  `id` int(11) NOT NULL,
  `video_title` varchar(255) NOT NULL,
  `video_url` varchar(255) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `category_name` varchar(255) DEFAULT NULL,
  `upload_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `videos`
--

INSERT INTO `videos` (`id`, `video_title`, `video_url`, `category_id`, `category_name`, `upload_date`) VALUES
(10, 'padma7', '../uploads/video1.mp4', 1, 'Preschool', '2025-02-18 10:16:14'),
(11, 'sound alarm making', '../uploads/video1.mp4', 3, 'Olders', '2025-02-18 10:17:05'),
(13, 'padmad', '../uploads/video1.mp4', 1, 'Preschool', '2025-02-20 05:52:19'),
(14, 'padma7', '../uploads/video1.mp4', 1, 'Preschool', '2025-02-20 06:03:19'),
(16, 'mahendra', '../uploads/video1.mp4', 3, 'Olders', '2025-02-21 01:57:44'),
(19, 'mahi', '../uploads/videos/video1.mp4', 2, 'Youngers', '2025-02-22 11:03:34');

-- --------------------------------------------------------

--
-- Table structure for table `video_ratings`
--

CREATE TABLE `video_ratings` (
  `id` int(11) NOT NULL,
  `video_title` varchar(255) NOT NULL,
  `video_rating` int(11) NOT NULL,
  `video_feedback` text DEFAULT NULL,
  `video_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `video_ratings`
--

INSERT INTO `video_ratings` (`id`, `video_title`, `video_rating`, `video_feedback`, `video_id`) VALUES
(1, 'sound alarm making', 5, 'uqwertyuiop', NULL),
(2, 'sandeep0', 5, 'hii', NULL),
(3, 'sandeep00', 5, 'hello', NULL),
(4, 'sandeep000', 5, 'hii hello', NULL),
(5, 'sandeep000', 5, 'hii hello', NULL),
(6, 'mahi', 5, 'asdfghjk', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `video_suggestions`
--

CREATE TABLE `video_suggestions` (
  `id` int(11) NOT NULL,
  `video_title` varchar(255) NOT NULL,
  `video_url` varchar(255) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `video_category_name` varchar(255) DEFAULT NULL,
  `upload_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `custom_category` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `video_suggestions`
--

INSERT INTO `video_suggestions` (`id`, `video_title`, `video_url`, `category_id`, `video_category_name`, `upload_date`, `custom_category`) VALUES
(11, 'mahi', '../uploads/videos/video1.mp4', 1, 'Preschool', '2025-02-22 11:03:10', NULL),
(13, 'mahi', '../uploads/videos/video1.mp4', 3, 'Olders', '2025-02-22 11:04:04', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `video_ratings`
--
ALTER TABLE `video_ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `video_id` (`video_id`);

--
-- Indexes for table `video_suggestions`
--
ALTER TABLE `video_suggestions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `videos`
--
ALTER TABLE `videos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `video_ratings`
--
ALTER TABLE `video_ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `video_suggestions`
--
ALTER TABLE `video_suggestions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `videos`
--
ALTER TABLE `videos`
  ADD CONSTRAINT `videos_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `video_ratings`
--
ALTER TABLE `video_ratings`
  ADD CONSTRAINT `video_ratings_ibfk_1` FOREIGN KEY (`video_id`) REFERENCES `videos` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `video_suggestions`
--
ALTER TABLE `video_suggestions`
  ADD CONSTRAINT `video_suggestions_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
