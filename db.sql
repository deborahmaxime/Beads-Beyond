-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 18, 2025 at 10:02 AM
-- Server version: 8.0.44-0ubuntu0.24.04.2
-- PHP Version: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `webtech_2025A_deborah_maxime`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int NOT NULL,
  `user_id` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `cart_item_id` int NOT NULL,
  `cart_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int DEFAULT '1',
  `custom_details` text COLLATE utf8mb4_general_ci,
  `price` decimal(10,2) NOT NULL
) ;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `name`) VALUES
(1, 'Bracelets'),
(2, 'Waist Beads'),
(3, 'Anklets'),
(4, 'Beaded Bags'),
(5, 'Necklaces'),
(6, 'Earrings'),
(7, 'Rings'),
(8, 'Hair Accessories'),
(9, 'Pouches'),
(10, 'Lifestyle Accessories'),
(11, 'Special & Custom Pieces');

-- --------------------------------------------------------

--
-- Table structure for table `customization_options`
--

CREATE TABLE `customization_options` (
  `option_id` int NOT NULL,
  `option_name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customization_options`
--

INSERT INTO `customization_options` (`option_id`, `option_name`) VALUES
(1, 'Color'),
(2, 'Size'),
(3, 'Material'),
(4, 'Charm');

-- --------------------------------------------------------

--
-- Table structure for table `customization_values`
--

CREATE TABLE `customization_values` (
  `value_id` int NOT NULL,
  `option_id` int NOT NULL,
  `value_name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `price_modifier` decimal(10,2) DEFAULT '0.00',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int NOT NULL,
  `user_id` int NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `order_status` enum('Pending','Paid','Shipped','Completed','Cancelled') COLLATE utf8mb4_general_ci DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `payment_method` varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'COD'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `total_amount`, `order_status`, `created_at`, `updated_at`, `payment_method`) VALUES
(1, 2, 10.00, 'Pending', '2025-12-14 20:05:26', '2025-12-14 20:05:26', 'COD'),
(3, 2, 13.00, 'Pending', '2025-12-17 20:03:13', '2025-12-17 20:36:38', 'COD'),
(4, 5, 69.00, 'Pending', '2025-12-18 03:28:52', '2025-12-18 03:28:52', 'Bank Transfer'),
(5, 4, 132.00, 'Pending', '2025-12-18 05:04:35', '2025-12-18 05:04:35', 'COD');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int NOT NULL,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `custom_details` text COLLATE utf8mb4_general_ci,
  `price` decimal(10,2) NOT NULL
) ;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `product_id`, `quantity`, `custom_details`, `price`) VALUES
(1, 1, 6, 1, '{\"color\":\"\",\"size\":\"\",\"material\":\"\",\"notes\":\"\"}', 10.00),
(3, 3, 20, 1, '{\"color\":\"\",\"size\":\"\",\"material\":\"\",\"notes\":\"\"}', 13.00),
(4, 4, 24, 3, '{\"color\":\"Gold\",\"size\":\"\",\"material\":\"\",\"notes\":\"\"}', 23.00),
(5, 5, 25, 12, '{\"color\":\"Silver\",\"size\":\"Small\",\"material\":\"\",\"notes\":\"abc\"}', 11.00);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int NOT NULL,
  `order_id` int NOT NULL,
  `reference` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` varchar(20) COLLATE utf8mb4_general_ci DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int NOT NULL,
  `category_id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `base_price` decimal(10,2) NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `is_customizable` tinyint(1) DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `stock_quantity` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `category_id`, `name`, `description`, `base_price`, `image`, `is_customizable`, `created_at`, `updated_at`, `stock_quantity`) VALUES
(4, 4, 'White Perl Hand Bag', 'Very Light Bag For All Occasions', 234.00, 'prod_693eac4f70bf45.61061251.jpeg', 0, '2025-12-14 12:23:43', '2025-12-14 12:23:43', 3),
(6, 1, 'Blue Bracelet', 'light blue perl bracelet', 10.00, 'prod_693eb30f30c1b3.86918978.jpeg', 0, '2025-12-14 12:52:31', '2025-12-14 12:52:31', 12),
(7, 11, 'Special Keychain', 'for every occasions', 5.00, 'prod_693ed6873036c3.39029542.jpeg', 1, '2025-12-14 15:23:51', '2025-12-14 15:46:31', 22),
(8, 4, 'Golden Bags', 'very light bag', 234.00, 'prod_693f1933bb2e17.92782690.jpeg', 1, '2025-12-14 20:08:19', '2025-12-14 20:08:19', 5),
(9, 1, 'bracelet', 'very light and beautiful', 3.00, 'prod_6941cc8b9dafb6.90502892.jpeg', 1, '2025-12-16 21:18:03', '2025-12-16 21:18:03', 45),
(10, 1, 'bracelet', 'Nice', 3.00, 'prod_6941cca3903394.19695322.jpeg', 1, '2025-12-16 21:18:27', '2025-12-16 21:18:27', 45),
(11, 1, 'bracelet', '🤩 waouh', 3.00, 'prod_6941ccbb99df09.82758589.jpeg', 1, '2025-12-16 21:18:51', '2025-12-16 21:18:51', 45),
(12, 1, 'bracelet', 'Hooky', 3.00, 'prod_6941cce8882f75.17611668.jpeg', 1, '2025-12-16 21:19:36', '2025-12-16 21:19:36', 45),
(13, 1, 'bracelet', 'Bridal Bracelet, Metallic Blue', 3.00, 'prod_6941cd07556d89.68260056.jpeg', 1, '2025-12-16 21:20:07', '2025-12-16 21:20:19', 45),
(14, 1, 'bracelet', 'Pulseira com pingente de bola', 3.00, 'prod_6941cd5a94c0c7.40592208.jpeg', 1, '2025-12-16 21:21:30', '2025-12-16 21:21:30', 45),
(15, 1, 'bracelet', 'Wedding Dress accessory', 3.00, 'prod_6941cd8c9338c4.68808731.jpeg', 1, '2025-12-16 21:22:20', '2025-12-16 21:22:20', 45),
(16, 4, 'Bag', 'Beaded Bags', 12.00, 'prod_6941cf5193e596.48599332.jpeg', 0, '2025-12-16 21:29:53', '2025-12-16 21:29:53', 34),
(17, 4, 'Bag', 'Sky brand', 12.00, 'prod_6941cf7c5de8b1.94604830.jpeg', 0, '2025-12-16 21:30:36', '2025-12-16 21:30:36', 34),
(18, 4, 'Bag', 'Pearl Beaded Elegant Wedding Purse', 12.00, 'prod_6941cfe208d579.96133664.jpeg', 0, '2025-12-16 21:32:18', '2025-12-16 21:32:18', 34),
(19, 4, 'Bag', 'beaded bag', 12.00, 'prod_6941d00cafe056.68442505.jpeg', 1, '2025-12-16 21:33:00', '2025-12-16 21:33:00', 34),
(20, 1, 'bracelet', 'moonlight', 13.00, 'prod_694202b99d6aa1.05036926.jpeg', 0, '2025-12-17 01:08:22', '2025-12-17 01:09:13', 23),
(21, 4, 'Bag', 'niiiiice', 3.00, 'prod_694203299c79a6.44885575.jpeg', 0, '2025-12-17 01:10:40', '2025-12-17 01:11:05', 23),
(22, 4, 'Bag', 'gooly', 3.00, 'prod_694204ee5398e0.66311375.jpeg', 0, '2025-12-17 01:15:06', '2025-12-17 01:18:38', 23),
(23, 4, 'Bag', 'hoolah', 3.00, 'prod_69420946578e86.16535210.jpeg', 0, '2025-12-17 01:37:10', '2025-12-17 01:37:10', 23),
(24, 6, 'The whole set', 'light green', 23.00, 'prod_69420991351511.78040859.jpeg', 0, '2025-12-17 01:38:25', '2025-12-17 01:38:25', 34),
(25, 1, 'bracelet', 'Boom', 11.00, 'prod_694313c4b7d1d2.19542101.jpeg', 0, '2025-12-17 20:34:12', '2025-12-17 20:34:12', 12),
(26, 1, 'bracelet', 'OULALAAA', 11.00, 'prod_6943142f8421c5.21751561.jpeg', 1, '2025-12-17 20:35:59', '2025-12-17 20:35:59', 12),
(27, 5, 'Golden Necklace', 'miaouhhhh', 45.00, 'prod_694379fc5b7fd3.13619450.jpeg', 0, '2025-12-18 03:50:20', '2025-12-18 03:50:20', 23);

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

CREATE TABLE `site_settings` (
  `setting_id` int NOT NULL,
  `site_name` varchar(100) NOT NULL,
  `contact_email` varchar(100) NOT NULL,
  `contact_phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `full_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_admin` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `email`, `password_hash`, `phone`, `created_at`, `is_admin`) VALUES
(1, 'Deborah Maxime', 'deb@abc.com', '$2y$10$yIl49cXQvi9Iza1BAx.TjujPb8lz.pVjMC.XLrzZu/TVj5Tg1Vxwq', '81494802', '2025-12-14 00:42:31', 0),
(2, 'Deborah AGOSSOU', 'deborah.maxime@ashesi.edu.gh', '$2y$10$zB35hTGFzlssf7mqUsJ9KOmRui.Iirp2zEhcw5iQw79tpLX.6mqjC', '0503840174', '2025-12-14 02:16:30', 1),
(4, 'Max', 'max@gmail.com', '$2y$10$B6QIUPs7C0fFRDlJlDcxpu7ZLhlFpgHRtI7w8sUuYEFEenPMQTeyy', '81494802', '2025-12-18 03:12:59', 0),
(5, 'Habiba', 'Habiba@gmail.com', '$2y$10$p7uLrzw8o1r.ane3G20r5O8MJVQUr.6sRa3LbCvgPlaOAOgC.PcSi', '22334455', '2025-12-18 03:25:55', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `idx_cart_user` (`user_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`cart_item_id`),
  ADD KEY `idx_cart_items_product` (`product_id`),
  ADD KEY `fk_cart_items_cart` (`cart_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `customization_options`
--
ALTER TABLE `customization_options`
  ADD PRIMARY KEY (`option_id`);

--
-- Indexes for table `customization_values`
--
ALTER TABLE `customization_values`
  ADD PRIMARY KEY (`value_id`),
  ADD KEY `option_id` (`option_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `idx_order_user` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `idx_order_items_product` (`product_id`),
  ADD KEY `fk_order_items_order` (`order_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `fk_products_category` (`category_id`);

--
-- Indexes for table `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`setting_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `cart_item_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `customization_options`
--
ALTER TABLE `customization_options`
  MODIFY `option_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `customization_values`
--
ALTER TABLE `customization_values`
  MODIFY `value_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `setting_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `fk_cart_items_cart` FOREIGN KEY (`cart_id`) REFERENCES `cart` (`cart_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_cart_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `customization_values`
--
ALTER TABLE `customization_values`
  ADD CONSTRAINT `customization_values_ibfk_1` FOREIGN KEY (`option_id`) REFERENCES `customization_options` (`option_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_order_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
