-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 03, 2025 at 08:02 AM
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
-- Database: `milkteashop`
--

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `item` varchar(100) NOT NULL,
  `price` int(11) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `item_price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `size` varchar(50) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `notes` text DEFAULT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT 'pending',
  `total_price` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `item_name`, `item_price`, `quantity`, `size`, `customer_name`, `phone`, `address`, `notes`, `order_date`, `status`, `total_price`) VALUES
(8, 'Trà sữa kem trứng nướng', 25000.00, 1, 'Vừa', 'my name is dat', '0913397925', '8/4', 'its da', '2025-05-02 06:22:42', 'pending', 25000.00),
(9, 'Trà sữa kem trứng nướng', 25000.00, 2, 'Vừa', 'my name is dat', '0913397925', '8/4', 'its da', '2025-05-02 06:23:36', 'pending', 50000.00),
(10, 'Trà sữa kem trứng nướng', 25000.00, 1, 'Vừa', 'tao la dat', '00000000000', '123', '321', '2025-05-02 06:40:44', 'pending', 25000.00),
(11, 'Sữa tươi kem trứng nướng', 27000.00, 4, 'Vừa', 'dá', 'đá', 'đá', 'ad', '2025-05-02 06:59:02', 'completed', 108000.00),
(12, 'Trà sữa kem trứng nướng', 25000.00, 1, 'Vừa', 'đá', 'áda', 'đâsd', 'ádad', '2025-05-02 07:04:53', 'completed', 25000.00),
(13, 'Trà sữa kem trứng nướng', 25000.00, 1, 'Vừa', 'dasd', 'asdad', 'asd', 'dasd', '2025-05-02 07:08:58', 'completed', 25000.00),
(15, 'Trà sữa kem trứng nướng', 25000.00, 1, 'Vừa', 'dat', '0000000', '8/4', 'đá', '2025-05-02 13:36:59', 'completed', 25000.00),
(16, 'Trà sữa kem trứng nướng', 25000.00, 1, 'Vừa', 'ádad', 'dá', 'áda', 'ád', '2025-05-02 13:37:19', 'pending', 25000.00),
(17, 'Trà sữa kem trứng nướng', 25000.00, 1, 'Vừa', 'dat', 'đá', 'ádasd', 'adasd', '2025-05-02 13:40:06', 'pending', 25000.00),
(18, 'Sữa tươi than tre', 23000.00, 1, 'Vừa', 'dat', 'đá', 'ádad', 'ád', '2025-05-02 13:42:27', 'pending', 23000.00),
(19, 'Trà sữa trân châu đường đen', 24000.00, 2, 'Vừa', 'dat', 'đa', 'đâ', 'đa', '2025-05-02 13:43:57', 'canceled', 48000.00),
(20, 'Sữa tươi kem trứng nướng', 27000.00, 4, 'Vừa', 'nam', 'ko', 'ko', 'ko', '2025-05-02 15:28:59', 'completed', 108000.00),
(21, 'Sữa tươi kem trứng nướng', 27000.00, 1, 'Vừa', 'dat', 'kok', 'koko', 'koo', '2025-05-02 16:03:57', 'pending', 27000.00),
(22, 'Trà sữa trân châu đường đen', 24000.00, 1, 'Vừa', 'dat', '0913397925', '8/4', 'ko', '2025-05-02 16:05:33', 'pending', 24000.00),
(23, 'Trà sữa kem trứng nướng', 25000.00, 1, 'Vừa', 'dat', 'ada', 'sdasda', 'sdad', '2025-05-02 16:54:12', 'pending', 25000.00),
(24, 'Trà sữa không', 20000.00, 14, 'Vừa', 'dat', 'adas', 'đâs', 'da', '2025-05-02 16:54:33', 'pending', 280000.00),
(25, 'Sữa tươi kem trứng nướng', 27000.00, 1, 'Vừa', 'dat', 'đá', 'ádad', 'áda', '2025-05-02 17:27:42', 'pending', 27000.00);

--
-- Triggers `orders`
--
DELIMITER $$
CREATE TRIGGER `update_total_price_before_insert` BEFORE INSERT ON `orders` FOR EACH ROW BEGIN
  SET NEW.total_price = NEW.item_price * NEW.quantity;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_total_price_before_update` BEFORE UPDATE ON `orders` FOR EACH ROW BEGIN
  SET NEW.total_price = NEW.item_price * NEW.quantity;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `image`) VALUES
(1, 'Trà sữa kem trứng nướng', 25000.00, 'product-1.png'),
(2, 'Trà sữa trân châu đường đen', 24000.00, 'product-3.png'),
(3, 'Sữa tươi trân châu đường đen', 25000.00, ''),
(4, 'Sữa tươi kem trứng nướng', 27000.00, 'product-2.png'),
(5, 'Sữa tươi than tre', 23000.00, 'product-5.png'),
(6, 'Trà sữa không', 20000.00, '');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `item` varchar(100) NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `product_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `username`, `item`, `rating`, `comment`, `created_at`, `product_id`, `user_id`) VALUES
(8, '', 'Trà sữa kem trứng nướng', 5, 'kokokok', '2025-05-02 15:26:05', 1, 5),
(9, '', 'Trà sữa kem trứng nướng', 1, 'ôkokoko', '2025-05-02 15:26:53', 1, 6);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('user','admin') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`, `role`) VALUES
(2, 'fg', '$2y$10$PU/BUw3nkF/Zz/lizek0d.FS/uWIV8HqcjaViC8bJ8xBQyDlOa3yO', '2025-05-01 06:43:03', 'user'),
(3, 'dat543', '$2y$10$w3gVIsnZiTbHCRs9lgGc8.R5lafnGEbCzoLgxdvi3Sh6WAUeIJ5D.', '2025-05-01 06:53:31', 'user'),
(4, 'admin', '$2y$10$haId3H7V9fkEa5kfuGbaK.oAIZZ.0mNVDRJL8P8GoFzhIymSi2sp2', '2025-05-02 04:20:04', 'admin'),
(5, 'dat', '$2y$10$H791o1FO95KhBh.mXFd.4.M2/sIM/oF8YNh1E/qw6VK89.lxezyhe', '2025-05-02 05:51:25', 'user'),
(6, 'nam', '$2y$10$T7nNl5mQBOj9KXqse9XHCOz935uorwgNSV8MKCXwMSoX9aopCT.li', '2025-05-02 15:26:24', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
