-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 23, 2022 at 10:58 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `eduhall`
--

-- --------------------------------------------------------

--
-- Table structure for table `attachments`
--

CREATE TABLE `attachments` (
  `id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `url` varchar(200) NOT NULL,
  `format` varchar(10) NOT NULL,
  `name` varchar(200) NOT NULL,
  `name_geo` varchar(200) NOT NULL,
  `added_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(200) CHARACTER SET utf8 NOT NULL,
  `title_geo` varchar(200) CHARACTER SET utf8 NOT NULL,
  `authors` varchar(500) CHARACTER SET utf8 NOT NULL,
  `authors_geo` varchar(500) CHARACTER SET utf8 NOT NULL,
  `year` int(11) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `description_geo` varchar(1000) NOT NULL,
  `image` varchar(500) NOT NULL,
  `added_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `user_id`, `title`, `title_geo`, `authors`, `authors_geo`, `year`, `description`, `description_geo`, `image`, `added_date`) VALUES
(1, 1, 'Alpha Rocket - Part 1', 'Alpha Rocket - ნაწილი 1', 'Tea Luarsabishvili, Kristine Turberidze', 'თეა ლუარსაბიშვილი, ქრისტინე თუთბერიძე', 2022, '', '', '62dda8b042936.png', '2022-07-23 20:57:50'),
(2, 1, 'Alpha Rocket - Part 2', 'Alpha Rocket - ნაწილი 2', 'Tea Luarsabishvili, Kristine Turberidze', 'თეა ლუარსაბიშვილი, ქრისტინე თუთბერიძე', 2023, '', '', '62dda8c464e57.png', '2022-07-23 20:58:09');

-- --------------------------------------------------------

--
-- Table structure for table `book_codes`
--

CREATE TABLE `book_codes` (
  `id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `code` varchar(200) NOT NULL,
  `create_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `activate_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `max_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL DEFAULT -1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT -1,
  `email` varchar(200) NOT NULL,
  `message` varchar(2000) NOT NULL,
  `send_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(200) CHARACTER SET utf8 NOT NULL,
  `phone` varchar(100) NOT NULL,
  `password` varchar(200) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `registered_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `phone`, `password`, `is_admin`, `is_active`, `registered_date`) VALUES
(1, 'system', 'system', '2b9cdebb444dbb2fe8380860104f0573', 1, 1, '2022-07-23 20:55:57');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attachments`
--
ALTER TABLE `attachments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `book_codes`
--
ALTER TABLE `book_codes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attachments`
--
ALTER TABLE `attachments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `book_codes`
--
ALTER TABLE `book_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
