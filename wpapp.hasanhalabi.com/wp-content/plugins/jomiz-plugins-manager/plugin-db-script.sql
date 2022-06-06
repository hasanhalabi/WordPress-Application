-- phpMyAdmin SQL Dump
-- version 4.4.0-beta1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 04, 2016 at 03:18 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `jclient_mifm_finance_team`
--

-- --------------------------------------------------------

--
-- Table structure for table `WPDBPFIX_jsys_appsettings_module`
--

CREATE TABLE IF NOT EXISTS `WPDBPFIX_jsys_appsettings_module` (
  `id` int(11) NOT NULL,
  `module_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `WPDBPFIX_jsys_objects_log`
--

CREATE TABLE IF NOT EXISTS `WPDBPFIX_jsys_objects_log` (
  `id` mediumint(9) NOT NULL,
  `object_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `object_id` int(11) NOT NULL DEFAULT '0',
  `object_data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_new` tinyint(1) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `WPDBPFIX_jsys_appsettings_module`
--
ALTER TABLE `WPDBPFIX_jsys_appsettings_module`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `WPDBPFIX_jsys_objects_log`
--
ALTER TABLE `WPDBPFIX_jsys_objects_log`
  ADD UNIQUE KEY `id` (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `WPDBPFIX_jsys_appsettings_module`
--
ALTER TABLE `WPDBPFIX_jsys_appsettings_module`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `WPDBPFIX_jsys_objects_log`
--
ALTER TABLE `WPDBPFIX_jsys_objects_log`
  MODIFY `id` mediumint(9) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
