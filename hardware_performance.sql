-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 11, 2026 at 03:35 AM
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
-- Database: `hardware_performance`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_recommendations` ()   BEGIN
    SELECT * FROM system_recommendation;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `bottleneck_analysis`
-- (See below for the actual view)
--
CREATE TABLE `bottleneck_analysis` (
`cpu` varchar(100)
,`gpu` varchar(100)
,`gap` bigint(12)
,`bottleneck` varchar(14)
);

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`) VALUES
(1, 'NVIDIA'),
(2, 'AMD'),
(3, 'Intel');

-- --------------------------------------------------------

--
-- Table structure for table `cpus`
--

CREATE TABLE `cpus` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `overall_performance` int(11) DEFAULT NULL,
  `gaming_performance` int(11) DEFAULT NULL,
  `tier` varchar(50) DEFAULT NULL,
  `brand_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cpus`
--

INSERT INTO `cpus` (`id`, `name`, `overall_performance`, `gaming_performance`, `tier`, `brand_id`) VALUES
(1, 'Ryzen 9 9950X', 155, 108, 'Enthusiast', 2),
(2, 'Ryzen 9 7950X', 145, 95, 'Enthusiast', 2),
(3, 'Core i9-14900K', 140, 100, 'Enthusiast', 3),
(4, 'Core i9-13900K', 135, 98, 'Enthusiast', 3),
(5, 'Ryzen 7 7800X3D', 100, 100, 'High-End Gaming', 2),
(6, 'Ryzen 9 7950X3D', 130, 104, 'High-End Gaming', 2),
(7, 'Ryzen 7 5800X3D', 75, 90, 'High-End Gaming', 2),
(8, 'Core i7-14700K', 125, 98, 'High-End Balanced', 3),
(9, 'Ryzen 7 7700X', 95, 92, 'High-End Balanced', 2),
(10, 'Core i5-14600K', 105, 95, 'High-End Balanced', 3),
(11, 'Ryzen 5 7600X', 85, 90, 'Upper Midrange', 2),
(12, 'Core i5-13600K', 100, 94, 'Upper Midrange', 3),
(13, 'Ryzen 7 5800X', 80, 82, 'Upper Midrange', 2),
(14, 'Ryzen 5 5600X', 65, 75, 'Midrange', 2),
(15, 'Core i5-12400F', 60, 72, 'Midrange', 3),
(16, 'Ryzen 5 3600', 45, 55, 'Lower Tier', 2),
(17, 'Core i7-10700K', 55, 65, 'Lower Tier', 3),
(18, 'Core i5-8400', 45, 60, 'Lower Midrange', 3),
(19, 'Core i5-8600', 55, 70, 'Midrange', 3),
(20, 'Core i7-12700', 115, 96, 'High-End', 3),
(21, 'Core i5-11600K', 85, 88, 'Upper Midrange', 3),
(22, 'Ryzen 7 7800', 95, 92, 'High-End', 2),
(23, 'Ryzen 5 5600', 70, 80, 'Midrange', 2);

-- --------------------------------------------------------

--
-- Stand-in structure for view `cpu_efficiency`
-- (See below for the actual view)
--
CREATE TABLE `cpu_efficiency` (
`name` varchar(100)
,`overall_performance` int(11)
,`gaming_performance` int(11)
,`gaming_bias` bigint(12)
,`tier` varchar(50)
);

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

CREATE TABLE `games` (
  `game_id` int(11) NOT NULL,
  `game_name` varchar(100) NOT NULL,
  `recommended_cpu_id` int(11) DEFAULT NULL,
  `recommended_gpu_id` int(11) DEFAULT NULL,
  `recommended_ram` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `games`
--

INSERT INTO `games` (`game_id`, `game_name`, `recommended_cpu_id`, `recommended_gpu_id`, `recommended_ram`) VALUES
(1, 'Spider-Man 2', 16, 38, 16),
(2, 'Crimson Desert', 21, 44, 16),
(3, 'Cyberpunk 2077', 20, 43, 16),
(4, 'God of War Ragnarok', 16, 42, 16);

-- --------------------------------------------------------

--
-- Table structure for table `gpus`
--

CREATE TABLE `gpus` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `relative_performance` int(11) DEFAULT NULL,
  `tier` varchar(50) DEFAULT NULL,
  `brand_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gpus`
--

INSERT INTO `gpus` (`id`, `name`, `relative_performance`, `tier`, `brand_id`) VALUES
(1, 'RTX 5090', 174, 'Flagship', 1),
(2, 'RTX 4090', 133, 'Flagship', 1),
(3, 'RTX 5080', 115, 'Flagship', 1),
(4, 'RTX 4080 SUPER', 102, 'Flagship', 1),
(5, 'RTX 4080', 102, 'Flagship', 1),
(6, 'RX 7900 XTX', 101, 'Flagship', 2),
(7, 'RTX 5070 Ti', 100, 'High-End', 1),
(8, 'RX 9070 XT', 96, 'Upper Midrange', 2),
(9, 'RX 7900 XT', 87, 'Upper Midrange', 2),
(10, 'RTX 3090 Ti', 86, 'Upper Midrange', 1),
(11, 'RTX 4070 Ti SUPER', 86, 'Upper Midrange', 1),
(12, 'RX 9070', 86, 'Upper Midrange', 2),
(13, 'RTX 4070 Ti', 78, 'Upper Midrange', 1),
(14, 'RTX 5070', 78, 'Upper Midrange', 1),
(15, 'RTX 3090', 77, 'Upper Midrange', 1),
(16, 'RTX 3080 Ti', 75, 'Upper Midrange', 1),
(17, 'RTX 4070 SUPER', 73, 'Upper Midrange', 1),
(18, 'RX 7900 GRE', 72, 'Upper Midrange', 2),
(19, 'RX 6950 XT', 71, 'Upper Midrange', 2),
(20, 'RTX 4070', 70, 'Upper Midrange', 1),
(21, 'RX 6900 XT', 69, 'Midrange', 2),
(22, 'RTX 3080', 68, 'Midrange', 1),
(23, 'RX 7800 XT', 68, 'Midrange', 2),
(24, 'RX 6800 XT', 65, 'Midrange', 2),
(25, 'RTX 5060 Ti (16GB)', 61, 'Midrange', 1),
(26, 'RTX 5060 Ti (8GB)', 60, 'Midrange', 1),
(27, 'RX 7700 XT', 59, 'Midrange', 2),
(28, 'RX 9060 XT (16GB)', 58, 'Midrange', 2),
(29, 'RTX 3070 Ti', 58, 'Midrange', 1),
(30, 'RX 6800', 56, 'Lower Tier', 2),
(31, 'RX 6750 XT', 55, 'Lower Tier', 2),
(32, 'RX 9060 XT (8GB)', 54, 'Lower Tier', 2),
(33, 'RTX 3070', 54, 'Lower Tier', 1),
(34, 'RTX 2080 Ti', 53, 'Lower Tier', 1),
(35, 'RTX 4060 Ti (8GB)', 53, 'Lower Tier', 1),
(36, 'RTX 5060', 52, 'Lower Tier', 1),
(37, 'RTX 3060 Ti', 47, 'Lower Tier', 1),
(38, 'RTX 3060', 44, 'Lower Tier', 1),
(39, 'RX 5700', 42, 'Lower Tier', 2),
(40, 'RTX 2080', 50, 'Lower Tier', 1),
(41, 'RX 5700 XT', 53, 'Lower Tier', 2),
(42, 'RTX 2060 SUPER', 48, 'Lower Tier', 1),
(43, 'Intel Arc A770', 52, 'Lower Tier', 3),
(44, 'RX 6700 XT', 57, 'Midrange', 2);

-- --------------------------------------------------------

--
-- Stand-in structure for view `gpu_rankings`
-- (See below for the actual view)
--
CREATE TABLE `gpu_rankings` (
`name` varchar(100)
,`relative_performance` int(11)
,`brand` varchar(50)
,`tier` varchar(50)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `system_recommendation`
-- (See below for the actual view)
--
CREATE TABLE `system_recommendation` (
);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'SoaR_Sippy', '$2y$10$eVwvmd7U0CUBOYsFtzI85uX8JFYfxHXG/QO3KQtEhHiwupWpfKPr6', '2026-05-02 14:49:02'),
(2, 'Nate', '$2y$10$RN1HYlTtWDwDyUc2pIMBUOyJZKBl85HsGP7UHook/Ea/cb7RWMYuK', '2026-05-11 00:32:00');

-- --------------------------------------------------------

--
-- Table structure for table `user_systems`
--

CREATE TABLE `user_systems` (
  `id` int(11) NOT NULL,
  `cpu_id` int(11) DEFAULT NULL,
  `gpu_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `ram_amount` int(11) DEFAULT NULL,
  `ram_performance` decimal(5,2) DEFAULT NULL,
  `game_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_systems`
--

INSERT INTO `user_systems` (`id`, `cpu_id`, `gpu_id`, `user_id`, `ram_amount`, `ram_performance`, `game_id`, `created_at`) VALUES
(30, 3, 1, 2, 32, 100.00, 1, '2026-05-11 00:57:46');

--
-- Triggers `user_systems`
--
DELIMITER $$
CREATE TRIGGER `calculate_ram_performance` BEFORE INSERT ON `user_systems` FOR EACH ROW BEGIN
    SET NEW.ram_performance = (NEW.ram_amount / 32) * 100;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_ram_performance` BEFORE UPDATE ON `user_systems` FOR EACH ROW BEGIN
    SET NEW.ram_performance = (NEW.ram_amount / 32) * 100;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure for view `bottleneck_analysis`
--
DROP TABLE IF EXISTS `bottleneck_analysis`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `bottleneck_analysis`  AS SELECT `c`.`name` AS `cpu`, `g`.`name` AS `gpu`, `g`.`relative_performance`- `c`.`gaming_performance` AS `gap`, CASE WHEN `g`.`relative_performance` - `c`.`gaming_performance` > 20 THEN 'CPU Bottleneck' WHEN `g`.`relative_performance` - `c`.`gaming_performance` < -20 THEN 'GPU Bottleneck' ELSE 'Balanced' END AS `bottleneck` FROM ((`user_systems` `u` join `cpus` `c` on(`u`.`cpu_id` = `c`.`id`)) join `gpus` `g` on(`u`.`gpu_id` = `g`.`id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `cpu_efficiency`
--
DROP TABLE IF EXISTS `cpu_efficiency`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `cpu_efficiency`  AS SELECT `c`.`name` AS `name`, `c`.`overall_performance` AS `overall_performance`, `c`.`gaming_performance` AS `gaming_performance`, `c`.`gaming_performance`- `c`.`overall_performance` AS `gaming_bias`, `c`.`tier` AS `tier` FROM `cpus` AS `c` ;

-- --------------------------------------------------------

--
-- Structure for view `gpu_rankings`
--
DROP TABLE IF EXISTS `gpu_rankings`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `gpu_rankings`  AS SELECT `g`.`name` AS `name`, `g`.`relative_performance` AS `relative_performance`, `b`.`name` AS `brand`, `g`.`tier` AS `tier` FROM (`gpus` `g` join `brands` `b` on(`g`.`brand_id` = `b`.`id`)) ORDER BY `g`.`relative_performance` DESC ;

-- --------------------------------------------------------

--
-- Structure for view `system_recommendation`
--
DROP TABLE IF EXISTS `system_recommendation`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `system_recommendation`  AS SELECT `u`.`id` AS `id`, `c`.`name` AS `cpu`, `g`.`name` AS `gpu`, `gm`.`name` AS `game`, CASE WHEN `g`.`relative_performance` < `rg`.`relative_performance` THEN 'Upgrade GPU' WHEN `c`.`gaming_performance` < `rc`.`gaming_performance` THEN 'Upgrade CPU' WHEN `u`.`ram_amount` < `gm`.`recommended_ram` THEN 'Upgrade RAM' ELSE 'System Meets Requirements' END AS `recommendation` FROM (((((`user_systems` `u` join `cpus` `c` on(`u`.`cpu_id` = `c`.`id`)) join `gpus` `g` on(`u`.`gpu_id` = `g`.`id`)) join `games` `gm` on(`u`.`game_id` = `gm`.`id`)) join `cpus` `rc` on(`gm`.`recommended_cpu_id` = `rc`.`id`)) join `gpus` `rg` on(`gm`.`recommended_gpu_id` = `rg`.`id`)) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cpus`
--
ALTER TABLE `cpus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `brand_id` (`brand_id`);

--
-- Indexes for table `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`game_id`),
  ADD KEY `recommended_cpu_id` (`recommended_cpu_id`),
  ADD KEY `recommended_gpu_id` (`recommended_gpu_id`);

--
-- Indexes for table `gpus`
--
ALTER TABLE `gpus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `brand_id` (`brand_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `user_systems`
--
ALTER TABLE `user_systems`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cpu_id` (`cpu_id`),
  ADD KEY `gpu_id` (`gpu_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_game` (`game_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cpus`
--
ALTER TABLE `cpus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `games`
--
ALTER TABLE `games`
  MODIFY `game_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `gpus`
--
ALTER TABLE `gpus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user_systems`
--
ALTER TABLE `user_systems`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cpus`
--
ALTER TABLE `cpus`
  ADD CONSTRAINT `cpus_ibfk_1` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`);

--
-- Constraints for table `games`
--
ALTER TABLE `games`
  ADD CONSTRAINT `games_ibfk_1` FOREIGN KEY (`recommended_cpu_id`) REFERENCES `cpus` (`id`),
  ADD CONSTRAINT `games_ibfk_2` FOREIGN KEY (`recommended_gpu_id`) REFERENCES `gpus` (`id`);

--
-- Constraints for table `gpus`
--
ALTER TABLE `gpus`
  ADD CONSTRAINT `gpus_ibfk_1` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`);

--
-- Constraints for table `user_systems`
--
ALTER TABLE `user_systems`
  ADD CONSTRAINT `fk_game` FOREIGN KEY (`game_id`) REFERENCES `games` (`game_id`),
  ADD CONSTRAINT `user_systems_ibfk_1` FOREIGN KEY (`cpu_id`) REFERENCES `cpus` (`id`),
  ADD CONSTRAINT `user_systems_ibfk_2` FOREIGN KEY (`gpu_id`) REFERENCES `gpus` (`id`),
  ADD CONSTRAINT `user_systems_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
