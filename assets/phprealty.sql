-- Generation Time: Aug 31, 2007 at 02:42 PM
-- Server version: 5.0.27
-- PHP Version: 5.1.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- Table structure for table `phprealty_comm_feat`
-- 

CREATE TABLE `phprealty_comm_feat` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(25) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Property Community Features' AUTO_INCREMENT=5 ;

-- 
-- Dumping data for table `phprealty_comm_feat`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `phprealty_features`
-- 

CREATE TABLE `phprealty_features` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(25) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Property Features Table' AUTO_INCREMENT=12 ;

-- 
-- Dumping data for table `phprealty_features`
-- 

INSERT INTO `phprealty_features` (`id`, `name`) VALUES 
(3, 'Den'),
(4, 'Deck'),
(5, 'Kitchen Nook'),
(6, 'Central Air'),
(7, 'Air Filtration'),
(11, 'porch');

-- --------------------------------------------------------

-- 
-- Table structure for table `phprealty_property`
-- 

CREATE TABLE `phprealty_property` (
  `id` int(11) NOT NULL auto_increment,
  `type` varchar(25) NOT NULL default '',
  `title` varchar(50) NOT NULL default '',
  `notes` text NOT NULL,
  `address` varchar(50) NOT NULL default '',
  `city` varchar(25) NOT NULL default '',
  `state` varchar(25) NOT NULL default '',
  `zip` varchar(5) NOT NULL default '',
  `price` varchar(10) NOT NULL default '',
  `full_desc` text NOT NULL,
  `garage` varchar(50) NOT NULL default '',
  `beds` char(3) NOT NULL default '',
  `baths` char(3) NOT NULL default '',
  `floors` char(2) NOT NULL default '',
  `year` varchar(4) NOT NULL default '',
  `sqfeet` varchar(5) NOT NULL default '',
  `lot_w` varchar(5) NOT NULL default '',
  `lot_l` varchar(5) NOT NULL default '',
  `tax` varchar(6) NOT NULL default '',
  `status` char(1) NOT NULL default '',
  `mls` varchar(25) NOT NULL default '',
  `features` varchar(250) NOT NULL,
  `comm_feat` varchar(250) NOT NULL,
  `featured` char(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `full_desc` (`full_desc`),
  FULLTEXT KEY `title` (`title`),
  FULLTEXT KEY `full_desc_2` (`full_desc`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Property table' AUTO_INCREMENT=16 ;

-- 
-- Dumping data for table `phprealty_property`
-- 

INSERT INTO `phprealty_property` (`id`, `type`, `title`, `notes`, `address`, `city`, `state`, `zip`, `price`, `full_desc`, `garage`, `beds`, `baths`, `floors`, `year`, `sqfeet`, `lot_w`, `lot_l`, `tax`, `status`, `mls`, `features`, `comm_feat`, `featured`) VALUES 
(6, 'Home', '2 bdrm 1 1/2 story First time Home', '', '400 west douglas ave', 'fergus falls', 'Minnesota', '56537', '75000', 'This house is a nice 1 1/2 story house with a basement', 'none', '4', '2', '2', '1949', '', '150', '200', '500', '1', '', 'Central Air', '', '0'),
(7, 'Home', '3 bdrm 2 story First time Home', '', '618 west seventh', 'fergus falls', 'Minnesota', '56537', '110000', 'Great house for a single family', 'single stall', '3', '2', '3', '1974', '', '75', '150', '600', '1', '', 'Den,Deck,Kitchen Nook,Cen', 'Swimming Pool', '1'),
(3, 'Home', 'Country Living', '', '400 West Douglas Ave', 'Breckenridge', 'Minnesota', '56522', '100000', 'Great house 1 1/2 story with large lot\r\n', '', '4', '2', '', '', '', '', '', '', '1', '', 'Deck,Central Air', '', '1'),
(8, 'Home', 'Great House', '', '89003 West 8th St', 'Wahpeton', 'North Dakota', '58101', '45000', 'Welcome to this house, it is great', 'single', '2', '1', '3', '1969', '1956', '150', '75', '456', '1', '756-78909', 'Central Air,Deck', '', '1');

-- --------------------------------------------------------

-- 
-- Table structure for table `phprealty_prop_img`
-- 

CREATE TABLE `phprealty_prop_img` (
  `id` int(11) NOT NULL auto_increment,
  `p_id` varchar(5) NOT NULL default '',
  `fn` varchar(150) NOT NULL,
  `def` char(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Property Image Table' AUTO_INCREMENT=28 ;

-- 
-- Dumping data for table `phprealty_prop_img`
-- 

INSERT INTO `phprealty_prop_img` (`id`, `p_id`, `fn`, `def`) VALUES 
(16, '11', '20070828104332_tous.png', '0'),
(15, '11', '20070828103656_ashley_tis.jpg', '0'),
(17, '11', '20070828104346_zach_slag52.png', '1'),
(27, '8', '20070829121652_Otter1.jpg', '1'),
(26, '8', '20070829121546_agguide.jpg', '0');

-- --------------------------------------------------------

-- 
-- Table structure for table `phprealty_user`
-- 

CREATE TABLE `phprealty_user` (
  `id` int(11) NOT NULL auto_increment,
  `uname` varchar(14) NOT NULL,
  `fname` varchar(25) NOT NULL default '',
  `lname` varchar(25) NOT NULL default '',
  `password` varchar(100) NOT NULL default '',
  `email` varchar(100) NOT NULL default '',
  `phone` varchar(12) NOT NULL default '',
  `mobile` varchar(12) NOT NULL default '',
  `fax` varchar(12) NOT NULL default '',
  `homepage` varchar(150) NOT NULL,
  `info` text NOT NULL,
  `mod_date` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `uname` (`uname`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Agent / User Table' AUTO_INCREMENT=16 ;

-- 
-- Dumping data for table `phprealty_user`
-- 

INSERT INTO `phprealty_user` (`id`, `uname`, `fname`, `lname`, `password`, `email`, `phone`, `mobile`, `fax`, `homepage`, `info`, `mod_date`) VALUES 
(12, 'admin', 'Admin', 'Admin', 'dc9aee516255ee070e973b5f1af95f84', 'admin@yourdomain', '', '', '', '', '', '02:04pm Fri, Aug 31st, 2007');
