-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 09, 2026 at 08:15 PM
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
  `brand_id` int(11) DEFAULT NULL,
  `gaming_upgrade_class` varchar(50) DEFAULT NULL,
  `overall_upgrade_class` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cpus`
--

INSERT INTO `cpus` (`id`, `name`, `overall_performance`, `gaming_performance`, `tier`, `brand_id`, `gaming_upgrade_class`, `overall_upgrade_class`) VALUES
(1, 'Ryzen 9 9950X', 155, 108, 'Enthusiast', 2, 'Marginal Upgrade', 'Upgrade Recommended'),
(2, 'Ryzen 9 7950X', 145, 95, 'Enthusiast', 2, 'Sidegrade', 'Upgrade Recommended'),
(3, 'Core i9-14900K', 140, 100, 'Enthusiast', 3, 'Sidegrade', 'Upgrade Recommended'),
(4, 'Core i9-13900K', 135, 98, 'Enthusiast', 3, 'Sidegrade', 'Upgrade Recommended'),
(5, 'Ryzen 7 7800X3D', 100, 100, 'High-End Gaming', 2, 'Sidegrade', 'Sidegrade'),
(6, 'Ryzen 9 7950X3D', 130, 104, 'High-End Gaming', 2, 'Marginal Upgrade', 'Upgrade Recommended'),
(7, 'Ryzen 7 5800X3D', 75, 90, 'High-End Gaming', 2, 'Downgrade', 'Downgrade'),
(8, 'Core i7-14700K', 125, 98, 'High-End Balanced', 3, 'Sidegrade', 'Upgrade Recommended'),
(9, 'Ryzen 7 7700X', 95, 92, 'High-End Balanced', 2, 'Downgrade', 'Sidegrade'),
(10, 'Core i5-14600K', 105, 95, 'High-End Balanced', 3, 'Sidegrade', 'Marginal Upgrade'),
(11, 'Ryzen 5 7600X', 85, 90, 'Upper Midrange', 2, 'Downgrade', 'Downgrade'),
(12, 'Core i5-13600K', 100, 94, 'Upper Midrange', 3, 'Downgrade', 'Sidegrade'),
(13, 'Ryzen 7 5800X', 80, 82, 'Upper Midrange', 2, 'Downgrade', 'Downgrade'),
(14, 'Ryzen 5 5600X', 65, 75, 'Midrange', 2, 'Downgrade', 'Downgrade'),
(15, 'Core i5-12400F', 60, 72, 'Midrange', 3, 'Downgrade', 'Downgrade'),
(16, 'Ryzen 5 3600', 45, 55, 'Lower Tier', 2, 'Downgrade', 'Downgrade'),
(17, 'Core i7-10700K', 55, 65, 'Lower Tier', 3, 'Downgrade', 'Downgrade');

--
-- Triggers `cpus`
--
DELIMITER $$
CREATE TRIGGER `cpu_upgrade_trigger` BEFORE INSERT ON `cpus` FOR EACH ROW BEGIN
    SET NEW.gaming_upgrade_class = CASE
        WHEN NEW.gaming_performance >= 110 THEN 'Upgrade Recommended'
        WHEN NEW.gaming_performance >= 102 THEN 'Marginal Upgrade'
        WHEN NEW.gaming_performance >= 95 THEN 'Sidegrade'
        ELSE 'Downgrade'
    END;

    SET NEW.overall_upgrade_class = CASE
        WHEN NEW.overall_performance >= 120 THEN 'Upgrade Recommended'
        WHEN NEW.overall_performance >= 105 THEN 'Marginal Upgrade'
        WHEN NEW.overall_performance >= 95 THEN 'Sidegrade'
        ELSE 'Downgrade'
    END;
END
$$
DELIMITER ;

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
,`gaming_upgrade_class` varchar(50)
,`overall_upgrade_class` varchar(50)
);

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

CREATE TABLE `games` (
  `id` int(11) NOT NULL,
  `game_name` varchar(100) DEFAULT NULL,
  `minimum_cpu_score` int(11) DEFAULT NULL,
  `recommended_cpu_score` int(11) DEFAULT NULL,
  `minimum_gpu_score` int(11) DEFAULT NULL,
  `recommended_gpu_score` int(11) DEFAULT NULL,
  `minimum_ram` int(11) DEFAULT NULL,
  `recommended_ram` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `games`
--

INSERT INTO `games` (`id`, `game_name`, `minimum_cpu_score`, `recommended_cpu_score`, `minimum_gpu_score`, `recommended_gpu_score`, `minimum_ram`, `recommended_ram`) VALUES
(1, 'God of War Ragnarok', 55, 95, 47, 75, 8, 16),
(2, 'Cyberpunk 2077 Phantom Liberty', 55, 100, 47, 75, 12, 20),
(3, 'Crimson Desert', 60, 90, 47, 70, 16, 16),
(4, 'Marvel Spider-Man 2', 50, 85, 45, 60, 16, 16);

-- --------------------------------------------------------

--
-- Table structure for table `gpus`
--

CREATE TABLE `gpus` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `relative_performance` int(11) DEFAULT NULL,
  `tier` varchar(50) DEFAULT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `upgrade_class` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gpus`
--

INSERT INTO `gpus` (`id`, `name`, `relative_performance`, `tier`, `brand_id`, `upgrade_class`) VALUES
(1, 'RTX 5090', 174, 'Faster than Baseline', 1, 'Upgrade Recommended'),
(2, 'RTX 4090', 133, 'Faster than Baseline', 1, 'Upgrade Recommended'),
(3, 'RTX 5080', 115, 'Faster than Baseline', 1, 'Upgrade Recommended'),
(4, 'RTX 4080 SUPER', 102, 'Faster than Baseline', 1, 'Sidegrade'),
(5, 'RTX 4080', 102, 'Faster than Baseline', 1, 'Sidegrade'),
(6, 'RX 7900 XTX', 101, 'Faster than Baseline', 2, 'Sidegrade'),
(7, 'RTX 5070 Ti', 100, 'Baseline', 1, 'Sidegrade'),
(8, 'RX 9070 XT', 96, 'Slightly Slower', 2, 'Downgrade'),
(9, 'RX 7900 XT', 87, 'Slightly Slower', 2, 'Downgrade'),
(10, 'RTX 3090 Ti', 86, 'Slightly Slower', 1, 'Downgrade'),
(11, 'RTX 4070 Ti SUPER', 86, 'Slightly Slower', 1, 'Downgrade'),
(12, 'RX 9070', 86, 'Slightly Slower', 2, 'Downgrade'),
(13, 'RTX 4070 Ti', 78, 'Upper Midrange', 1, 'Downgrade'),
(14, 'RTX 5070', 78, 'Upper Midrange', 1, 'Downgrade'),
(15, 'RTX 3090', 77, 'Upper Midrange', 1, 'Downgrade'),
(16, 'RTX 3080 Ti', 75, 'Upper Midrange', 1, 'Downgrade'),
(17, 'RTX 4070 SUPER', 73, 'Upper Midrange', 1, 'Downgrade'),
(18, 'RX 7900 GRE', 72, 'Upper Midrange', 2, 'Downgrade'),
(19, 'RX 6950 XT', 71, 'Upper Midrange', 2, 'Downgrade'),
(20, 'RTX 4070', 70, 'Upper Midrange', 1, 'Downgrade'),
(21, 'RX 6900 XT', 69, 'Midrange', 2, 'Downgrade'),
(22, 'RTX 3080', 68, 'Midrange', 1, 'Downgrade'),
(23, 'RX 7800 XT', 68, 'Midrange', 2, 'Downgrade'),
(24, 'RX 6800 XT', 65, 'Midrange', 2, 'Downgrade'),
(25, 'RTX 5060 Ti (16GB)', 61, 'Midrange', 1, 'Downgrade'),
(26, 'RTX 5060 Ti (8GB)', 60, 'Midrange', 1, 'Downgrade'),
(27, 'RX 7700 XT', 59, 'Midrange', 2, 'Downgrade'),
(28, 'RX 9060 XT (16GB)', 58, 'Midrange', 2, 'Downgrade'),
(29, 'RTX 3070 Ti', 58, 'Midrange', 1, 'Downgrade'),
(30, 'RX 6800', 56, 'Lower Tier', 2, 'Downgrade'),
(31, 'RX 6750 XT', 55, 'Lower Tier', 2, 'Downgrade'),
(32, 'RX 9060 XT (8GB)', 54, 'Lower Tier', 2, 'Downgrade'),
(33, 'RTX 3070', 54, 'Lower Tier', 1, 'Downgrade'),
(34, 'RTX 2080 Ti', 53, 'Lower Tier', 1, 'Downgrade'),
(35, 'RTX 4060 Ti (8GB)', 53, 'Lower Tier', 1, 'Downgrade'),
(36, 'RTX 5060', 52, 'Lower Tier', 1, 'Downgrade'),
(37, 'RTX 3060 Ti', 47, 'Lower Tier', 1, 'Downgrade');

--
-- Triggers `gpus`
--
DELIMITER $$
CREATE TRIGGER `gpu_upgrade_trigger` BEFORE INSERT ON `gpus` FOR EACH ROW BEGIN
    SET NEW.upgrade_class = CASE
        WHEN NEW.relative_performance >= 115 THEN 'Upgrade Recommended'
        WHEN NEW.relative_performance >= 105 THEN 'Marginal Upgrade'
        WHEN NEW.relative_performance >= 100 THEN 'Sidegrade'
        ELSE 'Downgrade'
    END;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `gpu_rankings`
-- (See below for the actual view)
--
CREATE TABLE `gpu_rankings` (
`name` varchar(100)
,`relative_performance` int(11)
,`brand` varchar(50)
,`upgrade_class` varchar(50)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `system_recommendation`
-- (See below for the actual view)
--
CREATE TABLE `system_recommendation` (
`id` int(11)
,`cpu` varchar(100)
,`gpu` varchar(100)
,`recommendation` varchar(15)
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
(6, 'Nate', '$2y$10$Nt3CJw07DsZO9VtCZKDML.g5lG8U8OjcmGHzzqw98LxqoXvV92tiK', '2026-05-09 18:11:06');

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
  `game_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_systems`
--

INSERT INTO `user_systems` (`id`, `cpu_id`, `gpu_id`, `user_id`, `ram_amount`, `ram_performance`, `game_id`) VALUES
(27, 3, 1, 6, 32, 100.00, 3);

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

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `cpu_efficiency`  AS SELECT `c`.`name` AS `name`, `c`.`overall_performance` AS `overall_performance`, `c`.`gaming_performance` AS `gaming_performance`, `c`.`gaming_performance`- `c`.`overall_performance` AS `gaming_bias`, `c`.`gaming_upgrade_class` AS `gaming_upgrade_class`, `c`.`overall_upgrade_class` AS `overall_upgrade_class` FROM `cpus` AS `c` ;

-- --------------------------------------------------------

--
-- Structure for view `gpu_rankings`
--
DROP TABLE IF EXISTS `gpu_rankings`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `gpu_rankings`  AS SELECT `g`.`name` AS `name`, `g`.`relative_performance` AS `relative_performance`, `b`.`name` AS `brand`, `g`.`upgrade_class` AS `upgrade_class` FROM (`gpus` `g` join `brands` `b` on(`g`.`brand_id` = `b`.`id`)) ORDER BY `g`.`relative_performance` DESC ;

-- --------------------------------------------------------

--
-- Structure for view `system_recommendation`
--
DROP TABLE IF EXISTS `system_recommendation`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `system_recommendation`  AS SELECT `u`.`id` AS `id`, `c`.`name` AS `cpu`, `g`.`name` AS `gpu`, CASE WHEN `g`.`relative_performance` < 100 THEN 'Upgrade GPU' WHEN `c`.`gaming_performance` < 100 THEN 'Upgrade CPU' ELSE 'System Balanced' END AS `recommendation` FROM ((`user_systems` `u` join `cpus` `c` on(`u`.`cpu_id` = `c`.`id`)) join `gpus` `g` on(`u`.`gpu_id` = `g`.`id`)) ;

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
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `games`
--
ALTER TABLE `games`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `gpus`
--
ALTER TABLE `gpus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user_systems`
--
ALTER TABLE `user_systems`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cpus`
--
ALTER TABLE `cpus`
  ADD CONSTRAINT `cpus_ibfk_1` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`);

--
-- Constraints for table `gpus`
--
ALTER TABLE `gpus`
  ADD CONSTRAINT `gpus_ibfk_1` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`);

--
-- Constraints for table `user_systems`
--
ALTER TABLE `user_systems`
  ADD CONSTRAINT `fk_game` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`),
  ADD CONSTRAINT `user_systems_ibfk_1` FOREIGN KEY (`cpu_id`) REFERENCES `cpus` (`id`),
  ADD CONSTRAINT `user_systems_ibfk_2` FOREIGN KEY (`gpu_id`) REFERENCES `gpus` (`id`),
  ADD CONSTRAINT `user_systems_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
