SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE IF NOT EXISTS `event` (
  `event_id` int(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `group_id` int(20) NOT NULL,
  `lname` varchar(20) NOT NULL,
  `zip` int(5) NOT NULL,
  PRIMARY KEY (`event_id`),
  KEY `group_id` (`group_id`),
  KEY `event_ibfk_2` (`lname`,`zip`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

CREATE TABLE IF NOT EXISTS `eventuser` (
  `event_id` int(20) NOT NULL,
  `username` varchar(20) NOT NULL DEFAULT '',
  `rsvp` tinyint(1) NOT NULL,
  `rating` int(1) NOT NULL,
  PRIMARY KEY (`event_id`,`username`),
  KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `group` (
  `group_id` int(20) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(20) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `username` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`group_id`),
  KEY `group_ibfk_1` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29 ;

CREATE TABLE IF NOT EXISTS `groupannouncement` (
  `announcement_id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `title` varchar(20) NOT NULL,
  `announcement` text NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`announcement_id`),
  KEY `groupindex` (`group_id`),
  KEY `usernameindex` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

CREATE TABLE IF NOT EXISTS `groupinterest` (
  `interest_name` varchar(20) NOT NULL DEFAULT '',
  `group_id` int(20) NOT NULL,
  PRIMARY KEY (`group_id`,`interest_name`),
  KEY `groupinterest_ibfk_2` (`interest_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `groupuser` (
  `group_id` int(20) NOT NULL,
  `username` varchar(20) NOT NULL DEFAULT '',
  `authorized` tinyint(1) NOT NULL,
  PRIMARY KEY (`group_id`,`username`),
  KEY `groupuser_ibfk_2` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `interest` (
  `interest_name` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`interest_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `location` (
  `lname` varchar(20) NOT NULL DEFAULT '',
  `zip` int(5) NOT NULL,
  `street` varchar(50) NOT NULL DEFAULT '',
  `city` varchar(20) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `latitude` bigint(50) NOT NULL,
  `longitude` bigint(50) NOT NULL,
  PRIMARY KEY (`lname`,`zip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `member` (
  `username` varchar(20) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  `firstname` varchar(20) NOT NULL DEFAULT '',
  `lastname` varchar(20) NOT NULL DEFAULT '',
  `zipcode` int(5) NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `memberinterest` (
  `username` varchar(20) NOT NULL DEFAULT '',
  `interest_name` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`username`,`interest_name`),
  KEY `userinterest_ibfk_2` (`interest_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `event`
  ADD CONSTRAINT `event_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `group` (`group_id`),
  ADD CONSTRAINT `event_ibfk_2` FOREIGN KEY (`lname`, `zip`) REFERENCES `location` (`lname`, `zip`);

ALTER TABLE `eventuser`
  ADD CONSTRAINT `eventuser_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `event` (`event_id`),
  ADD CONSTRAINT `eventuser_ibfk_2` FOREIGN KEY (`username`) REFERENCES `member` (`username`);

ALTER TABLE `group`
  ADD CONSTRAINT `group_ibfk_1` FOREIGN KEY (`username`) REFERENCES `member` (`username`);

ALTER TABLE `groupannouncement`
  ADD CONSTRAINT `groupannouncement_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `group` (`group_id`);

ALTER TABLE `groupinterest`
  ADD CONSTRAINT `groupinterest_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `group` (`group_id`),
  ADD CONSTRAINT `groupinterest_ibfk_2` FOREIGN KEY (`interest_name`) REFERENCES `interest` (`interest_name`);

ALTER TABLE `groupuser`
  ADD CONSTRAINT `groupuser_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `group` (`group_id`),
  ADD CONSTRAINT `groupuser_ibfk_2` FOREIGN KEY (`username`) REFERENCES `member` (`username`);

ALTER TABLE `memberinterest`
  ADD CONSTRAINT `userinterest_ibfk_1` FOREIGN KEY (`username`) REFERENCES `member` (`username`),
  ADD CONSTRAINT `userinterest_ibfk_2` FOREIGN KEY (`interest_name`) REFERENCES `interest` (`interest_name`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
