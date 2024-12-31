-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 28, 2024 at 06:40 AM
-- Server version: 10.5.26-MariaDB-0+deb11u2
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `digital_twin`
--

-- --------------------------------------------------------

--
-- Table structure for table `action_transfer`
--

CREATE TABLE `action_transfer` (
  `id` int(11) NOT NULL,
  `family_id` int(11) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `image` varchar(100) NOT NULL,
  `driving` varchar(100) NOT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'NO'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `action_transfer`
--

INSERT INTO `action_transfer` (`id`, `family_id`, `user_name`, `image`, `driving`, `status`) VALUES
(1, 1, 'yimin20240909082413', 'yimin20240909082413.jpg', 'yimin20240909082413.mp4', 'YES'),
(2, 2, 'yimin20240909082509', 'yimin20240909082509.jpg', 'yimin20240909082509.mp4', 'YES'),
(3, 3, 'yimin20240909082515', 'yimin20240909082515.jpg', 'yimin20240909082515.mp4', 'YES'),
(4, 4, 'a148720240925084045', 'a148720240925084045.jpg', 'a148720240925084045.mp4', 'NO'),
(5, 5, 'a148720240925125756', 'a148720240925125756.jpg', 'a148720240925125756.mp4', 'NO'),
(6, 6, 's1102701320240925130133', 's1102701320240925130133.jpg', 's1102701320240925130133.mp4', 'NO');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `action_transfer`
--
ALTER TABLE `action_transfer`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `action_transfer`
--
ALTER TABLE `action_transfer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
