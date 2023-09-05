-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 24, 2023 at 02:32 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `projectdemo`
--

-- --------------------------------------------------------

--
-- Table structure for table `auth`
--

CREATE TABLE `auth` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `status` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1=active,2=not active',
  `craeted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `user_type` enum('1','2') NOT NULL DEFAULT '2' COMMENT '1=admin,2=user',
  `name` varchar(255) NOT NULL,
  `mobile_no` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `blood_group` varchar(255) NOT NULL,
  `health_insurance` enum('1','2') NOT NULL COMMENT '1=no,2=yes',
  `insurance_cmpy` varchar(255) NOT NULL,
  `insurance_agent` varchar(255) NOT NULL,
  `relative_no` varchar(255) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `count` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `user_type`, `name`, `mobile_no`, `email`, `password`, `blood_group`, `health_insurance`, `insurance_cmpy`, `insurance_agent`, `relative_no`, `updated_at`, `created_at`, `count`) VALUES
(1, '1', 'Admin', '0', 'admin@admin.com', 'e10adc3949ba59abbe56e057f20f883e', '', '', '', '', '', '2023-08-24 11:08:22', '2023-08-24 11:08:22', 0),
(10, '2', '203c6aab16f6ad61269abc54c3ab0ff98853a4604e10a6236da484324774fdea7a95afc961d9f547e5815f58a6f41f1a84935b64825e97c5f2c390c6ac48609fMOH016zhrmDUBsQP1UoXdX3PrQgE6HaLYXPYc2L+gAw=', 'ab5611ccea99388c131df3702a9c8e65ee3f58a107d84e5899a5a428edb14da945c68b856d9a5b53269c5d812c27a40b839d8a4f6d65071fd69acfa7225f46fakgMQJT4+lzSP69P9utDGyfJd+qW1am1XaihFsh3g3wQ=', 'test6@test.com', 'e10adc3949ba59abbe56e057f20f883e', 'e67d990bae394ccce4b857cf09063d1d112b9143f9e08c830198c34e8ddd8827bc537f709e497a2ea554d4d93e7eb83228a175c828dc95b1f1145e7574533da3zB+doLXqoEHQpBV9wwYGkY7jC4/bJ4CFqYQ5hUENS/A=', '1', '', '', '03285e73cbb8f26501cbc6a69a8e07b70f8addecc21559a9db0edd724d625e4dd0185ba609bc21af04a87bdda12c7a2b2f326bbac70de611a800d14a49969d81yyxG1XZbAaULMu5yFN0Wv7WJUqD8XE7Ak9VvscxguCeP5+pgj+EkMd2868Xhcv2m', '2023-08-24 12:12:14', '2023-08-24 12:12:14', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `auth`
--
ALTER TABLE `auth`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `auth`
--
ALTER TABLE `auth`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
