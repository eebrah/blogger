-- phpMyAdmin SQL Dump
-- version 3.5.8.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 08, 2013 at 04:27 AM
-- Server version: 5.5.33a-MariaDB
-- PHP Version: 5.5.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `blog`
--

-- --------------------------------------------------------

--
-- Table structure for table `accountDetails`
--

CREATE TABLE IF NOT EXISTS `accountDetails` (
  `uniqueID` varchar(5) NOT NULL,
  `screenname` varchar(80) NOT NULL,
  `email` varchar(80) NOT NULL,
  `password` varchar(80) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `accessLevel` int(11) NOT NULL DEFAULT '2',
  PRIMARY KEY (`uniqueID`),
  UNIQUE KEY `screenname` (`screenname`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `articleDetails`
--

CREATE TABLE IF NOT EXISTS `articleDetails` (
  `uniqueID` varchar(5) NOT NULL,
  `title` varchar(80) NOT NULL,
  PRIMARY KEY (`uniqueID`),
  UNIQUE KEY `title` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `commentDetails`
--

CREATE TABLE IF NOT EXISTS `commentDetails` (
  `uniqueID` varchar(5) NOT NULL,
  `article` varchar(5) NOT NULL,
  PRIMARY KEY (`uniqueID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `postDetails`
--

CREATE TABLE IF NOT EXISTS `postDetails` (
  `uniqueID` varchar(5) NOT NULL,
  `body` text NOT NULL,
  `dateCreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `datePublished` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `author` varchar(5) NOT NULL DEFAULT '00000',
  `status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`uniqueID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `userDetails`
--

CREATE TABLE IF NOT EXISTS `userDetails` (
  `uniqueID` varchar(5) NOT NULL,
  `name` varchar(80) NOT NULL,
  `dateJoined` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`uniqueID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
