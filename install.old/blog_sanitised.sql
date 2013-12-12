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

CREATE TABLE IF NOT EXISTS `articleDetails` (
  `uniqueID` varchar(5) NOT NULL,
  `title` varchar(80) NOT NULL,
  PRIMARY KEY (`uniqueID`),
  UNIQUE KEY `title` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `commentDetails` (
  `uniqueID` varchar(5) NOT NULL,
  `article` varchar(5) NOT NULL,
  `name` varchar(80) NOT NULL,
  `email` varchar(80) NOT NULL,
  PRIMARY KEY (`uniqueID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `postDetails` (
  `uniqueID` varchar(5) NOT NULL,
  `body` text NOT NULL,
  `dateCreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `datePublished` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `author` varchar(5) NOT NULL DEFAULT '00000',
  `status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`uniqueID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `userDetails` (
  `uniqueID` varchar(5) NOT NULL,
  `name` varchar(80) NOT NULL,
  `dateJoined` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`uniqueID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
