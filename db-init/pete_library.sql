-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 07, 2024 at 11:21 PM
-- Server version: 10.6.16-MariaDB
-- PHP Version: 8.1.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pete_library`
--

-- --------------------------------------------------------

--
-- Table structure for table `book`
--

CREATE TABLE `book` (
  `ID` int(11) NOT NULL,
  `title` varchar(127) NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `author_ident` varchar(127) NOT NULL,
  `owned` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `book`
--

INSERT INTO `book` (`ID`, `title`, `subtitle`, `author_ident`, `owned`) VALUES
(1, 'First One Missing', NULL, 'Cohen', 1),
(2, 'Dreadnought', NULL, 'Larson', 1),
(3, 'Dreadnought', NULL, 'Daniels', 1),
(4, 'Dragons of a Fallen Sun', NULL, 'Weis/Hickman', 1),
(5, 'Garbage Man@The', NULL, 'Irving', 1),
(6, 'What I Mean When I Say I\'m Autistic', 'Unpuzzling a Life on the Autism Spectrum', 'Kotowicz', 1),
(7, 'Witch of Tophet County@The', 'A Comedy of Horrors', 'Schiller', 1),
(8, 'Touch of Twilight@The', 'The Third Sign of the Zodiac', 'Pettersson', 1),
(9, 'Unleashed', NULL, 'Kimelman', 1),
(10, 'Death in the Dark', NULL, 'Kimelman', 1),
(11, 'Insatiable', NULL, 'Kimelman', 1),
(12, 'Taste of Night@The', 'The Second Sign of the Zodiac', 'Pettersson', 1);

-- --------------------------------------------------------

--
-- Table structure for table `book_by`
--

CREATE TABLE `book_by` (
  `book_id` int(11) NOT NULL,
  `person_id` int(11) NOT NULL,
  `role` enum('author','editor','narrator','') NOT NULL,
  `sort_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `book_by`
--

INSERT INTO `book_by` (`book_id`, `person_id`, `role`, `sort_by`) VALUES
(1, 1, 'author', 1),
(2, 2, 'author', 1),
(3, 3, 'author', 1),
(3, 4, 'narrator', 1),
(4, 5, 'author', 1),
(4, 6, 'author', 2),
(5, 7, 'author', 1),
(6, 8, 'author', 1),
(7, 9, 'author', 1),
(7, 10, 'narrator', 1),
(8, 11, 'author', 1),
(9, 12, 'author', 1),
(10, 12, 'author', 1),
(11, 12, 'author', 1),
(12, 11, 'author', 1);

-- --------------------------------------------------------

--
-- Table structure for table `book_read`
--

CREATE TABLE `book_read` (
  `book_id` int(11) NOT NULL,
  `date_started` date DEFAULT NULL,
  `date_finished` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `book_read`
--

INSERT INTO `book_read` (`book_id`, `date_started`, `date_finished`) VALUES
(5, '2024-01-29', '2024-02-04'),
(6, '2024-01-01', '2024-01-05'),
(7, '2024-01-24', NULL),
(8, '2024-01-07', '2024-01-28'),
(9, NULL, '2024-01-04'),
(10, NULL, '2024-01-17'),
(11, NULL, '2024-01-23'),
(12, NULL, '2024-01-01');

-- --------------------------------------------------------

--
-- Table structure for table `person`
--

CREATE TABLE `person` (
  `ID` int(11) NOT NULL,
  `given_name` varchar(80) NOT NULL,
  `family_name` varchar(80) NOT NULL,
  `honorific` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `person`
--

INSERT INTO `person` (`ID`, `given_name`, `family_name`, `honorific`) VALUES
(1, 'Tammy', 'Cohen', NULL),
(2, 'B V', 'Larson', NULL),
(3, 'April', 'Daniels', NULL),
(4, 'Natasha', 'Soudek', NULL),
(5, 'Margaret', 'Weis', NULL),
(6, 'Tracy', 'Hickman', NULL),
(7, 'Candace', 'Irving', NULL),
(8, 'Annie', 'Kotowicz', NULL),
(9, 'J H', 'Schiller', NULL),
(10, 'Soneela', 'Nankani', NULL),
(11, 'Vicki', 'Pettersson', NULL),
(12, 'Emily', 'Kimelman', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `series`
--

CREATE TABLE `series` (
  `ID` int(11) NOT NULL,
  `series` varchar(80) NOT NULL,
  `author` int(11) DEFAULT NULL,
  `parent` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `series`
--

INSERT INTO `series` (`ID`, `series`, `author`, `parent`) VALUES
(1, 'Lost Colonies Trilogy', 2, NULL),
(2, 'Dragonlance', NULL, NULL),
(3, 'War of Souls', NULL, 2),
(4, 'Hidden Valor', 7, NULL),
(5, 'Witch of Tophet County@The', 9, NULL),
(6, 'Nemesis', 3, NULL),
(7, 'Signs of the Zodiac', 11, NULL),
(8, 'Sydney Rye Mysteries', 12, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `series_book`
--

CREATE TABLE `series_book` (
  `series_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `number` decimal(11,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `series_book`
--

INSERT INTO `series_book` (`series_id`, `book_id`, `number`) VALUES
(1, 2, 2.00),
(3, 4, 1.00),
(4, 5, 1.00),
(5, 7, 1.00),
(6, 3, 1.00),
(7, 8, 3.00),
(7, 12, 2.00),
(8, 9, 1.00),
(8, 10, 2.00),
(8, 11, 3.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `unique_book` (`title`,`author_ident`) USING BTREE;

--
-- Indexes for table `book_by`
--
ALTER TABLE `book_by`
  ADD UNIQUE KEY `book_id` (`book_id`,`person_id`,`role`);

--
-- Indexes for table `person`
--
ALTER TABLE `person`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `given_name` (`given_name`,`family_name`);

--
-- Indexes for table `series`
--
ALTER TABLE `series`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `series_book`
--
ALTER TABLE `series_book`
  ADD UNIQUE KEY `series_id` (`series_id`,`book_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `book`
--
ALTER TABLE `book`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `person`
--
ALTER TABLE `person`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `series`
--
ALTER TABLE `series`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
