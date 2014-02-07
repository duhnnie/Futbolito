-- phpMyAdmin SQL Dump
-- version 3.5.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 07, 2014 at 04:14 AM
-- Server version: 5.5.31-0ubuntu0.13.04.1
-- PHP Version: 5.4.9-4ubuntu2.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `futbolito`
--

-- --------------------------------------------------------

--
-- Table structure for table `game`
--

CREATE TABLE IF NOT EXISTS `game` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_match` int(11) NOT NULL,
  `open` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `id_match` (`id_match`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=295 ;

--
-- Dumping data for table `game`
--

INSERT INTO `game` (`id`, `id_match`, `open`) VALUES
(280, 71, 1),
(281, 71, 1),
(282, 71, 1),
(283, 71, 1),
(284, 71, 1),
(285, 72, 1),
(286, 72, 1),
(287, 72, 1),
(288, 72, 1),
(289, 72, 1),
(290, 73, 1),
(291, 73, 1),
(292, 73, 1),
(293, 73, 1),
(294, 73, 1);

-- --------------------------------------------------------

--
-- Table structure for table `score`
--

CREATE TABLE IF NOT EXISTS `score` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_game` int(11) NOT NULL,
  `id_team` int(11) NOT NULL,
  `score` int(11) DEFAULT NULL,
  `open` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `id_game` (`id_game`),
  KEY `id_team` (`id_team`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=461 ;

--
-- Dumping data for table `score`
--

INSERT INTO `score` (`id`, `id_game`, `id_team`, `score`, `open`) VALUES
(431, 280, 30, 12, 0),
(432, 280, 31, 3, 0),
(433, 281, 30, 0, 0),
(434, 281, 31, 2, 0),
(437, 283, 30, 22, 0),
(438, 283, 31, 22, 0),
(439, 284, 30, 13, 0),
(440, 284, 31, 2, 0),
(441, 285, 30, 67, 0),
(442, 285, 32, 67, 0),
(443, 286, 30, NULL, 1),
(444, 286, 32, NULL, 1),
(445, 287, 30, 23, 0),
(446, 287, 32, 1, 0),
(447, 288, 30, 2, 0),
(448, 288, 32, 34, 0),
(449, 289, 30, NULL, 1),
(450, 289, 32, NULL, 1),
(451, 290, 31, NULL, 1),
(452, 290, 32, NULL, 1),
(453, 291, 31, NULL, 1),
(454, 291, 32, NULL, 1),
(455, 292, 31, NULL, 1),
(456, 292, 32, NULL, 1),
(457, 293, 31, NULL, 1),
(458, 293, 32, NULL, 1),
(459, 294, 31, NULL, 1),
(460, 294, 32, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `team`
--

CREATE TABLE IF NOT EXISTS `team` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `member_1` varchar(50) NOT NULL,
  `member_2` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`,`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=33 ;

--
-- Dumping data for table `team`
--

INSERT INTO `team` (`id`, `name`, `member_1`, `member_2`) VALUES
(30, 'A', 'asdsdf', 'sdfsdf'),
(31, 'B', 'sdfdsfg', 'sdfadf'),
(32, 'sadfsad', 'sadfasdf', 'sdfsadf');

-- --------------------------------------------------------

--
-- Table structure for table `team_match`
--

CREATE TABLE IF NOT EXISTS `team_match` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_team_1` int(11) NOT NULL,
  `id_team_2` int(11) NOT NULL,
  `team_1_score` int(11) NOT NULL DEFAULT '0',
  `team_2_score` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_team_1_2` (`id_team_1`,`id_team_2`),
  KEY `id_team_1` (`id_team_1`),
  KEY `id_team_2` (`id_team_2`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=74 ;

--
-- Dumping data for table `team_match`
--

INSERT INTO `team_match` (`id`, `id_team_1`, `id_team_2`, `team_1_score`, `team_2_score`) VALUES
(71, 30, 31, 2, 1),
(72, 30, 32, 1, 1),
(73, 31, 32, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `codigo` varchar(100) NOT NULL,
  `estado` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `game`
--
ALTER TABLE `game`
  ADD CONSTRAINT `game_ibfk_2` FOREIGN KEY (`id_match`) REFERENCES `team_match` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `score`
--
ALTER TABLE `score`
  ADD CONSTRAINT `score_ibfk_1` FOREIGN KEY (`id_game`) REFERENCES `game` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `team_match`
--
ALTER TABLE `team_match`
  ADD CONSTRAINT `team_match_ibfk_4` FOREIGN KEY (`id_team_2`) REFERENCES `team` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `team_match_ibfk_3` FOREIGN KEY (`id_team_1`) REFERENCES `team` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
