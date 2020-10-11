-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 09, 2020 at 07:05 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `boipoka`
--

-- --------------------------------------------------------

--
-- Table structure for table `already_read`
--

CREATE TABLE `already_read` (
  `id` int(8) NOT NULL,
  `user_id` varchar(25) NOT NULL,
  `isbn` varchar(20) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `already_read`
--

INSERT INTO `already_read` (`id`, `user_id`, `isbn`, `start_date`, `end_date`) VALUES
(20, '107349472207635372674', '9780190600129', NULL, '2020-10-07'),
(23, '117209857938904400612', '0195169190', NULL, '2020-10-08'),
(24, '117209857938904400612', '9780593057711', NULL, '2020-10-08'),
(25, '102825436206133879766', '9780190600129', NULL, '2020-10-09');

-- --------------------------------------------------------

--
-- Table structure for table `our_books`
--

CREATE TABLE `our_books` (
  `id` int(8) NOT NULL,
  `isbn` varchar(25) NOT NULL,
  `book_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `our_books`
--

INSERT INTO `our_books` (`id`, `isbn`, `book_name`) VALUES
(1, '0262600293', 'G.H. Mead'),
(2, '9781135262242', 'G.H. Mead'),
(3, '8171564836', 'Himu, the Hindu \"Hero\" of Medieval India'),
(4, '9844590019', 'Himu shamagra'),
(5, '9844140005', 'Amanush'),
(6, '0195169190', 'Alfred Hitchcock\'s Psycho'),
(7, '0826215491', 'The Ivory Tower and Harry Potter'),
(8, '9780190600129', 'Vertigo'),
(9, '9780593057711', 'The Da Vinci Code'),
(10, '9781608870158', 'Inception'),
(11, '9780791488263', 'Reading Seminar XX');

-- --------------------------------------------------------

--
-- Table structure for table `reading`
--

CREATE TABLE `reading` (
  `id` int(8) NOT NULL,
  `user_id` varchar(25) NOT NULL,
  `isbn` varchar(20) NOT NULL,
  `start_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `reading`
--

INSERT INTO `reading` (`id`, `user_id`, `isbn`, `start_date`) VALUES
(7, '107349472207635372674', '0195169190', '2020-10-07'),
(10, '117209857938904400612', '9781608870158', '2020-10-08'),
(11, '117209857938904400612', '9780791488263', '2020-10-08');

-- --------------------------------------------------------

--
-- Table structure for table `read_later`
--

CREATE TABLE `read_later` (
  `id` int(8) NOT NULL,
  `user_id` varchar(25) NOT NULL,
  `isbn` varchar(20) NOT NULL,
  `start_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `read_later`
--

INSERT INTO `read_later` (`id`, `user_id`, `isbn`, `start_date`) VALUES
(10, '107349472207635372674', '0826215491', '2020-10-07');

-- --------------------------------------------------------

--
-- Table structure for table `user_details`
--

CREATE TABLE `user_details` (
  `id` int(8) NOT NULL,
  `google_id` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `image_link` varchar(256) NOT NULL,
  `gender` varchar(20) NOT NULL DEFAULT 'Private'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_details`
--

INSERT INTO `user_details` (`id`, `google_id`, `name`, `email`, `image_link`, `gender`) VALUES
(2, '102825436206133879766', 'S.M. Fahim Foysal Rabby', 'fahim15-5127@diu.edu.bd', 'https://lh3.googleusercontent.com/a-/AOh14Gh74xHgIVTo3ekP6yeJQeMhiyazvwcjzWEgGOeR', ''),
(6, '117209857938904400612', 'Fahim Foysalc', 'ffoysal6@gmail.com', 'https://lh3.googleusercontent.com/a-/AOh14GhXGBYWDkQsi4ooYSnfMXU1NMUUGuEYl85eW4bP1g', 'Male'),
(7, '111329147568622851731', 'Ishrat Jahan', 'ontorabby@gmail.com', 'https://lh3.googleusercontent.com/a-/AOh14GgJnv_DLwsl-ENdc3WpBz9A8Rf03-5CQelbV5vm', ''),
(8, '107349472207635372674', 'Ronald Ross', 'playstore4sk5@gmail.com', 'https://lh3.googleusercontent.com/a-/AOh14GiiT1g8sGpS1uuudyvNbgRiZNPetthW3HHQvXXi', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `already_read`
--
ALTER TABLE `already_read`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `our_books`
--
ALTER TABLE `our_books`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reading`
--
ALTER TABLE `reading`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `read_later`
--
ALTER TABLE `read_later`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_details`
--
ALTER TABLE `user_details`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `g_id` (`google_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `already_read`
--
ALTER TABLE `already_read`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `our_books`
--
ALTER TABLE `our_books`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `reading`
--
ALTER TABLE `reading`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `read_later`
--
ALTER TABLE `read_later`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `user_details`
--
ALTER TABLE `user_details`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
