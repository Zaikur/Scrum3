-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 16, 2024 at 07:22 PM
-- Server version: 8.2.0
-- PHP Version: 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `scrum3_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `CommentID` int NOT NULL AUTO_INCREMENT,
  `PostID` int DEFAULT NULL,
  `UserID` int DEFAULT NULL,
  `Comment` text NOT NULL,
  `CommentDate` date NOT NULL,
  PRIMARY KEY (`CommentID`),
  KEY `PostID` (`PostID`),
  KEY `UserID` (`UserID`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`CommentID`, `PostID`, `UserID`, `Comment`, `CommentDate`) VALUES
(1, 1, 2, 'This is a comment on the first post by the second user', '2023-03-01'),
(2, 1, 3, 'Another comment on the first post by the third user', '2023-03-02'),
(3, 2, 1, 'A comment on the second post by the first user', '2023-03-03'),
(4, 2, 4, 'Commenting on the second post by the fourth user', '2023-03-04'),
(5, 3, 2, 'The second user comments on the third post', '2023-03-05'),
(6, 4, 3, 'The third user comments on the fourth post', '2023-03-06'),
(7, 5, 1, 'First user back again, commenting on the fifth post', '2023-03-07');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
CREATE TABLE IF NOT EXISTS `posts` (
  `PostID` int NOT NULL AUTO_INCREMENT,
  `UserID` int DEFAULT NULL,
  `Title` varchar(100) NOT NULL,
  `Content` text NOT NULL,
  `PostDate` date NOT NULL,
  PRIMARY KEY (`PostID`),
  KEY `UserID` (`UserID`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`PostID`, `UserID`, `Title`, `Content`, `PostDate`) VALUES
(1, 1, 'First Post', 'This is the content of the first post', '2023-02-01'),
(2, 2, 'Second Post', 'This is the content of the second post', '2023-02-02'),
(3, 3, 'Third Post', 'This is the content of the third post', '2023-02-03'),
(4, 4, 'Fourth Post', 'This is the content of the fourth post', '2023-02-04'),
(5, 5, 'Fifth Post', 'This is the content of the fifth post', '2023-02-05'),
(6, 1, 'Sixth Post', 'This is the content of the sixth post by the first user', '2023-02-06'),
(7, 2, 'Seventh Post', 'This is the content of the seventh post by the second user', '2023-02-07');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `UserID` int NOT NULL AUTO_INCREMENT,
  `Username` varchar(50) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `JoinDate` date NOT NULL,
  PRIMARY KEY (`UserID`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `Username`, `Email`, `Password`, `JoinDate`) VALUES
(1, 'johnDoe', 'john@example.com', 'hashedpassword1', '2023-01-01'),
(2, 'janeDoe', 'jane@example.com', 'hashedpassword2', '2023-01-02'),
(3, 'bobSmith', 'bob@example.com', 'hashedpassword3', '2023-01-03'),
(4, 'aliceJones', 'alice@example.com', 'hashedpassword4', '2023-01-04'),
(5, 'mikeBrown', 'mike@example.com', 'hashedpassword5', '2023-01-05'),
(6, 'sarahMiller', 'sarah@example.com', 'hashedpassword6', '2023-01-06'),
(7, 'williamDavis', 'william@example.com', 'hashedpassword7', '2023-01-07');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
