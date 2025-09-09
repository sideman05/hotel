-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 09, 2025 at 05:22 PM
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
-- Database: `hotel_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `amenities`
--

CREATE TABLE `amenities` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `amenities`
--

INSERT INTO `amenities` (`id`, `name`, `category`, `description`, `icon`, `image`, `status`, `created_at`, `updated_at`) VALUES
(2, 'elikana', 'food', 'fooods', 'fa-swimming-pool', 'uploads/Screenshot_2025-08-08_12_01_12.png', 'active', '2025-09-08 08:08:07', '2025-09-08 08:10:06'),
(4, 'uyiyuio', 'food', 'tfutuhjyioj', 'fa-spa', 'uploads/1757321638_Screenshot_2025-08-21_11_31_08.png', 'active', '2025-09-08 08:53:58', '2025-09-08 11:06:52'),
(5, 'hfjsijep', 'food', 'j,zxcnzlk', 'fa-snowflake', 'uploads/1757349523_Screenshot_2025-08-14_18_03_52.png', 'active', '2025-09-08 16:38:43', '2025-09-08 16:38:43'),
(6, 'djkajsdaj', 'bathroom', 'kljaskdajsod', 'fa-spa', 'uploads/1757411602_Screenshot_2025-08-12_11_54_34.png', 'active', '2025-09-09 09:53:22', '2025-09-09 09:53:22');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `room_id` int(11) DEFAULT NULL,
  `room_type` varchar(100) DEFAULT NULL,
  `guest_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `check_in` date DEFAULT NULL,
  `check_out` date DEFAULT NULL,
  `guests` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(32) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `room_id`, `room_type`, `guest_name`, `email`, `phone`, `check_in`, `check_out`, `guests`, `created_at`, `status`) VALUES
(1, NULL, 'Deluxe', 'elikana', 'gobbyelly@gmail.com', '0618563507', '2025-09-10', '2025-09-12', '2 Adults', '2025-09-09 07:04:10', 'Confirmed'),
(2, NULL, 'Presidential', 'elly', 'elikanagobanya05@gmail.com', '43850345', '2025-09-10', '2025-09-11', '1 Adult', '2025-09-09 07:46:38', 'Confirmed'),
(3, NULL, 'Presidential', 'DENISA JOHN NYAMHKEKWA', 'gobbyelly@gmail.com', '0618563507', '2025-09-10', '2025-09-11', '1 Adult', '2025-09-09 09:01:47', 'Rejected');

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `category` varchar(50) DEFAULT 'rooms',
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `featured` tinyint(1) DEFAULT 0,
  `uploaded_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gallery`
--

INSERT INTO `gallery` (`id`, `filename`, `category`, `title`, `description`, `featured`, `uploaded_at`) VALUES
(1, '1757344571_Screenshot_2025-08-21_17_39_20.png', 'dining', 'dining halls', 'halhdajodij', 1, '2025-09-08 18:16:11'),
(2, '1757409645_Screenshot_2025-08-14_16_07_23.png', 'rooms', 'room for one hjasdkl', 'jkhasdkijaodmjaiodj', 1, '2025-09-09 12:20:45'),
(3, '1757409661_Screenshot_2025-08-09_18_56_39.png', 'amenities', 'hasodjha', 'ihowudpaodajh', 1, '2025-09-09 12:21:01');

-- --------------------------------------------------------

--
-- Table structure for table `guests`
--

CREATE TABLE `guests` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `id_type` varchar(50) DEFAULT NULL,
  `id_number` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `loyalty_tier` varchar(50) DEFAULT NULL,
  `preferences` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(32) DEFAULT 'currently staying'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guests`
--

INSERT INTO `guests` (`id`, `first_name`, `last_name`, `email`, `phone`, `country`, `id_type`, `id_number`, `address`, `loyalty_tier`, `preferences`, `notes`, `created_at`, `status`) VALUES
(1, 'DENISA', 'NYAMHKEKWA', 'gobbyelly@gmail.com', '0618563507', 'United Kingdom', 'Passport', '21313123', 'mdyanda\nS.L.P 475', 'Gold', '', 'fkljspfopaksd', '2025-09-09 11:50:44', 'currently staying'),
(3, 'HERIET', 'AUGUSTINE', 'gobbyelly@gmail.com', '0755102998', 'Canada', 'Driver\'s License', 'ospfkspoifs', 'Kayanga\n35402', 'Gold', '', 'cl;ksokas;lmd;', '2025-09-09 15:12:10', 'currently staying');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `capacity` varchar(100) NOT NULL,
  `status` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `features` text DEFAULT NULL,
  `images` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `name`, `type`, `price`, `capacity`, `status`, `description`, `features`, `images`, `created_at`) VALUES
(18, 'ellly', 'Suite', 500.00, '2 Adults, 1 Child', 'Available', 'sdfkjksd  hkdskdljfak alkn hojndn bvoafoinvono ', '[\"WiFi\",\"TV\"]', '[\"1757340248_Screenshot_2025-07-31_12_35_11.png\"]', '2025-09-08 14:04:08'),
(19, 'elkana', 'Suite', 500.00, '2 Adults', 'Booked', 'jjsdkljflksj', '[\"Mini Bar\",\"Jacuzzi\"]', '[\"1757340643_Screenshot_2025-08-06_08_25_04.png\"]', '2025-09-08 14:10:43'),
(20, 'jhdsljvk', 'Presidential', 700.00, '1 Adult', 'Available', 'hfdjfpogergj pejr', '[\"WiFi\",\"TV\",\"Air Conditioning\"]', '[\"1757349557_Screenshot_2025-07-31_12_35_11.png\"]', '2025-09-08 16:39:17'),
(21, 'elllllly', 'Deluxe', 300.00, '2 Adults', 'Available', 'available', '[\"WiFi\",\"TV\"]', '[\"1757400313_Screenshot_2025-08-07_14_30_08.png\"]', '2025-09-09 06:45:13');

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `room_type` varchar(50) DEFAULT NULL,
  `stay_date` varchar(20) DEFAULT NULL,
  `rating` int(11) DEFAULT 0,
  `text` text DEFAULT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `testimonial_date` date DEFAULT NULL,
  `featured` tinyint(1) DEFAULT 0,
  `avatar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `testimonials`
--

INSERT INTO `testimonials` (`id`, `name`, `room_type`, `stay_date`, `rating`, `text`, `status`, `testimonial_date`, `featured`, `avatar`) VALUES
(5, 'elikana', 'executive', '2025-07', 0, 'very nice uhrhwieofuojidfkosndfs jkdsuidfkjhsu difkjsudi gduikfjs uidgfjdfiu kjsfuiksjhdfs ujkdfhsudf khsjdf iusdgvb mj', 'pending', '2025-09-10', 1, ''),
(6, 'xjkhas', 'family', '2025-03', 0, 'jklljoikh haskjhauidoha hasoh ufajkhf iuafhuiahf jkaisfu kagf iakujgfidjkfnb idfbiuk jf siyhdgvb idjfs uizdjsbijs uixkdjfosuid fohsfkjs df', 'approved', '2025-09-17', 1, '1757349724_Screenshot_2025-08-14_16_07_23.png'),
(7, 'xjkhas', 'family', '2025-03', 0, 'jklljoikh haskjhauidoha hasoh ufajkhf iuafhuiahf jkaisfu kagf iakujgfidjkfnb idfbiuk jf siyhdgvb idjfs uizdjsbijs uixkdjfosuid fohsfkjs df', 'approved', '2025-09-17', 1, ''),
(8, 'elikana', 'executive', '2025-07', 0, 'very nice uhrhwieofuojidfkosndfs jkdsuidfkjhsu difkjsudi gduikfjs uidgfjdfiu kjsfuiksjhdfs ujkdfhsudf khsjdf iusdgvb mj', 'pending', '2025-09-10', 1, '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `amenities`
--
ALTER TABLE `amenities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `guests`
--
ALTER TABLE `guests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `amenities`
--
ALTER TABLE `amenities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `guests`
--
ALTER TABLE `guests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
