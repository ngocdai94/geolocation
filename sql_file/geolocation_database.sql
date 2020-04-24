-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 24, 2020 at 03:52 PM
-- Server version: 8.0.18
-- PHP Version: 7.3.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `geolocation_database`
--

-- --------------------------------------------------------

--
-- Table structure for table `geolocation_data`
--

CREATE TABLE `geolocation_data` (
  `id` int(11) NOT NULL,
  `name` varchar(225) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `lat_degree` int(11) DEFAULT NULL,
  `lat_minute` int(11) DEFAULT NULL,
  `lat_seconds` double DEFAULT NULL,
  `lat_direction` varchar(45) DEFAULT NULL,
  `long_degree` int(11) DEFAULT NULL,
  `long_minute` int(11) DEFAULT NULL,
  `long_seconds` double DEFAULT NULL,
  `long_direction` varchar(45) DEFAULT NULL,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  `altitude` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Geolocation Data From GPS';

--
-- Dumping data for table `geolocation_data`
--

INSERT INTO `geolocation_data` (`id`, `name`, `lat_degree`, `lat_minute`, `lat_seconds`, `lat_direction`, `long_degree`, `long_minute`, `long_seconds`, `long_direction`, `latitude`, `longitude`, `altitude`) VALUES
(1, 'E7C, Quito 170138, Ecuador', 0, 8, 9.357, 'S', 78, 28, 31.945, 'W', -0.1359325, -78.475540277778, 2866.0385742188),
(2, 'Macoya, Trinidad and Tobago', 10, 38, 27.42, 'N', 61, 22, 51.969, 'W', 10.64095, -61.3811025, 2866.0385742188),
(3, 'De La Prosa 136, Cercado de Lima 15034, Peru', 12, 5, 7.52, 'S', 77, 0, 26.258, 'W', -12.085422222222, -77.007293888889, 2866.0385742188),
(4, 'De La Prosa 136, Cercado de Lima 15034, Peru', 12, 5, 7.52, 'S', 77, 0, 26.262, 'W', -12.085422222222, -77.007295, 159.98495483398),
(5, 'Metrologia Legal, Calle Circunvalacion Universitaria, San Salvador, El Salvador', 13, 43, 13.213, 'N', 89, 12, 0.835, 'W', 13.720336944444, -89.200231944444, 692.10961914062),
(6, 'Saint, Bisee, St Lucia', 14, 1, 26.419, 'N', 60, 58, 33.704, 'W', 14.024005277778, -60.976028888889, 30.417591094971);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `geolocation_data`
--
ALTER TABLE `geolocation_data`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `geolocation_data`
--
ALTER TABLE `geolocation_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
