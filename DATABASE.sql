-- NeoFrag Alpha 0.2
-- https://neofr.ag
--
-- Host: neofrag.localhost
-- Generation Time: Sat, 23 Jun 2018 08:23:34 +0000
-- Server version: MySQL 5.7.21
-- PHP Version: 5.6.35

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET TIME_ZONE = "+02:00";

--
-- Database: `neofrag`
--

-- --------------------------------------------------------

--
-- Table structure for table `nf_access`
--

DROP TABLE IF EXISTS `nf_access`;
CREATE TABLE `nf_access` (
  `access_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id` int(11) unsigned NOT NULL,
  `module` varchar(100) NOT NULL,
  `action` varchar(100) NOT NULL,
  PRIMARY KEY (`access_id`),
  UNIQUE KEY `module_id` (`id`,`module`,`action`),
  KEY `module` (`module`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `nf_access`
--

INSERT INTO `nf_access` (`access_id`, `id`, `module`, `action`) VALUES
(11, 1, 'events', 'access_events_type'),
(8, 1, 'forum', 'category_announce'),
(7, 1, 'forum', 'category_delete'),
(9, 1, 'forum', 'category_lock'),
(6, 1, 'forum', 'category_modify'),
(10, 1, 'forum', 'category_move'),
(4, 1, 'forum', 'category_read'),
(5, 1, 'forum', 'category_write'),
(12, 2, 'events', 'access_events_type'),
(3, 2, 'talks', 'delete'),
(1, 2, 'talks', 'read'),
(2, 2, 'talks', 'write'),
(13, 3, 'events', 'access_events_type'),
(14, 4, 'events', 'access_events_type'),
(15, 5, 'events', 'access_events_type');

-- --------------------------------------------------------

--
-- Table structure for table `nf_access_details`
--

DROP TABLE IF EXISTS `nf_access_details`;
CREATE TABLE `nf_access_details` (
  `access_id` int(11) unsigned NOT NULL,
  `entity` varchar(100) NOT NULL,
  `type` enum('group','user') NOT NULL DEFAULT 'group',
  `authorized` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`access_id`,`entity`,`type`),
  CONSTRAINT `nf_access_details_ibfk_1` FOREIGN KEY (`access_id`) REFERENCES `nf_access` (`access_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `nf_access_details`
--

INSERT INTO `nf_access_details` (`access_id`, `entity`, `type`, `authorized`) VALUES
(2, 'visitors', 'group', '0'),
(3, 'admins', 'group', '1'),
(5, 'visitors', 'group', '0'),
(6, 'admins', 'group', '1'),
(7, 'admins', 'group', '1'),
(8, 'admins', 'group', '1'),
(9, 'admins', 'group', '1'),
(10, 'admins', 'group', '1');

-- --------------------------------------------------------

--
-- Table structure for table `nf_addon`
--

DROP TABLE IF EXISTS `nf_addon`;
CREATE TABLE `nf_addon` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type_id` int(11) unsigned DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `data` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`,`type_id`),
  KEY `type_id` (`type_id`),
  CONSTRAINT `nf_addon_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `nf_addon_type` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `nf_addon`
--

INSERT INTO `nf_addon` (`id`, `type_id`, `name`, `data`) VALUES
(1, NULL, 'authenticator', NULL),
(2, 1, 'access', 'a:1:{s:7:\"enabled\";b:1;}'),
(3, 1, 'addons', 'a:1:{s:7:\"enabled\";b:1;}'),
(4, 1, 'admin', 'a:1:{s:7:\"enabled\";b:1;}'),
(5, 1, 'awards', 'a:1:{s:7:\"enabled\";b:1;}'),
(6, 1, 'comments', 'a:1:{s:7:\"enabled\";b:1;}'),
(7, 1, 'contact', 'a:1:{s:7:\"enabled\";b:1;}'),
(8, 1, 'events', 'a:1:{s:7:\"enabled\";b:1;}'),
(9, 1, 'forum', 'a:1:{s:7:\"enabled\";b:1;}'),
(10, 1, 'gallery', 'a:1:{s:7:\"enabled\";b:1;}'),
(11, 1, 'games', 'a:1:{s:7:\"enabled\";b:1;}'),
(12, 1, 'live_editor', 'a:1:{s:7:\"enabled\";b:1;}'),
(13, 1, 'members', 'a:1:{s:7:\"enabled\";b:1;}'),
(14, 1, 'monitoring', 'a:1:{s:7:\"enabled\";b:1;}'),
(15, 1, 'news', 'a:1:{s:7:\"enabled\";b:1;}'),
(16, 1, 'pages', 'a:1:{s:7:\"enabled\";b:1;}'),
(17, 1, 'partners', 'a:1:{s:7:\"enabled\";b:1;}'),
(18, 1, 'recruits', 'a:1:{s:7:\"enabled\";b:1;}'),
(19, 1, 'search', 'a:1:{s:7:\"enabled\";b:1;}'),
(20, 1, 'settings', 'a:1:{s:7:\"enabled\";b:1;}'),
(21, 1, 'statistics', 'a:1:{s:7:\"enabled\";b:1;}'),
(22, 1, 'talks', 'a:1:{s:7:\"enabled\";b:1;}'),
(23, 1, 'teams', 'a:1:{s:7:\"enabled\";b:1;}'),
(24, 1, 'user', 'a:1:{s:7:\"enabled\";b:1;}'),
(25, 2, 'admin', NULL),
(26, 2, 'default', NULL),
(27, 3, 'awards', 'a:1:{s:7:\"enabled\";b:1;}'),
(28, 3, 'breadcrumb', 'a:1:{s:7:\"enabled\";b:1;}'),
(29, 3, 'events', 'a:1:{s:7:\"enabled\";b:1;}'),
(30, 3, 'forum', 'a:1:{s:7:\"enabled\";b:1;}'),
(31, 3, 'gallery', 'a:1:{s:7:\"enabled\";b:1;}'),
(32, 3, 'header', 'a:1:{s:7:\"enabled\";b:1;}'),
(33, 3, 'html', 'a:1:{s:7:\"enabled\";b:1;}'),
(34, 3, 'members', 'a:1:{s:7:\"enabled\";b:1;}'),
(35, 3, 'module', 'a:1:{s:7:\"enabled\";b:1;}'),
(36, 3, 'navigation', 'a:1:{s:7:\"enabled\";b:1;}'),
(37, 3, 'news', 'a:1:{s:7:\"enabled\";b:1;}'),
(38, 3, 'partners', 'a:1:{s:7:\"enabled\";b:1;}'),
(39, 3, 'recruits', 'a:1:{s:7:\"enabled\";b:1;}'),
(40, 3, 'search', 'a:1:{s:7:\"enabled\";b:1;}'),
(41, 3, 'slider', 'a:1:{s:7:\"enabled\";b:1;}'),
(42, 3, 'talks', 'a:1:{s:7:\"enabled\";b:1;}'),
(43, 3, 'teams', 'a:1:{s:7:\"enabled\";b:1;}'),
(44, 3, 'user', 'a:1:{s:7:\"enabled\";b:1;}'),
(45, 4, 'de', 'a:2:{s:5:\"order\";i:3;s:7:\"enabled\";b:1;}'),
(46, 4, 'en', 'a:2:{s:5:\"order\";i:2;s:7:\"enabled\";b:1;}'),
(47, 4, 'es', 'a:2:{s:5:\"order\";i:4;s:7:\"enabled\";b:1;}'),
(48, 4, 'fr', 'a:2:{s:5:\"order\";i:1;s:7:\"enabled\";b:1;}'),
(49, 4, 'it', 'a:2:{s:5:\"order\";i:5;s:7:\"enabled\";b:1;}'),
(50, 4, 'pt', 'a:2:{s:5:\"order\";i:6;s:7:\"enabled\";b:1;}'),
(51, 5, 'battle_net', 'a:4:{s:5:\"order\";i:3;s:7:\"enabled\";b:0;s:3:\"dev\";a:2:{s:2:\"id\";s:0:\"\";s:6:\"secret\";s:0:\"\";}s:4:\"prod\";a:2:{s:2:\"id\";s:0:\"\";s:6:\"secret\";s:0:\"\";}}'),
(52, 5, 'facebook', 'a:4:{s:5:\"order\";i:0;s:7:\"enabled\";b:0;s:3:\"dev\";a:2:{s:2:\"id\";s:0:\"\";s:6:\"secret\";s:0:\"\";}s:4:\"prod\";a:2:{s:2:\"id\";s:0:\"\";s:6:\"secret\";s:0:\"\";}}'),
(53, 5, 'github', 'a:4:{s:5:\"order\";i:6;s:7:\"enabled\";b:0;s:3:\"dev\";a:2:{s:2:\"id\";s:0:\"\";s:6:\"secret\";s:0:\"\";}s:4:\"prod\";a:2:{s:2:\"id\";s:0:\"\";s:6:\"secret\";s:0:\"\";}}'),
(54, 5, 'google', 'a:4:{s:5:\"order\";i:2;s:7:\"enabled\";b:0;s:3:\"dev\";a:2:{s:2:\"id\";s:0:\"\";s:6:\"secret\";s:0:\"\";}s:4:\"prod\";a:2:{s:2:\"id\";s:0:\"\";s:6:\"secret\";s:0:\"\";}}'),
(55, 5, 'linkedin', 'a:4:{s:5:\"order\";i:7;s:7:\"enabled\";b:0;s:3:\"dev\";a:2:{s:2:\"id\";s:0:\"\";s:6:\"secret\";s:0:\"\";}s:4:\"prod\";a:2:{s:2:\"id\";s:0:\"\";s:6:\"secret\";s:0:\"\";}}'),
(56, 5, 'steam', 'a:4:{s:5:\"order\";i:4;s:7:\"enabled\";b:0;s:3:\"dev\";a:1:{s:3:\"key\";s:0:\"\";}s:4:\"prod\";a:1:{s:3:\"key\";s:0:\"\";}}'),
(57, 5, 'twitch', 'a:4:{s:5:\"order\";i:5;s:7:\"enabled\";b:0;s:3:\"dev\";a:2:{s:2:\"id\";s:0:\"\";s:6:\"secret\";s:0:\"\";}s:4:\"prod\";a:2:{s:2:\"id\";s:0:\"\";s:6:\"secret\";s:0:\"\";}}'),
(58, 5, 'twitter', 'a:4:{s:5:\"order\";i:1;s:7:\"enabled\";b:0;s:3:\"dev\";a:2:{s:2:\"id\";s:0:\"\";s:6:\"secret\";s:0:\"\";}s:4:\"prod\";a:2:{s:2:\"id\";s:0:\"\";s:6:\"secret\";s:0:\"\";}}'),
(59, 3, 'copyright', 'a:1:{s:7:\"enabled\";b:1;}');

-- --------------------------------------------------------

--
-- Table structure for table `nf_addon_type`
--

DROP TABLE IF EXISTS `nf_addon_type`;
CREATE TABLE `nf_addon_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `nf_addon_type`
--

INSERT INTO `nf_addon_type` (`id`, `name`) VALUES
(1, 'module'),
(2, 'theme'),
(3, 'widget'),
(4, 'language'),
(5, 'authenticator');

-- --------------------------------------------------------

--
-- Table structure for table `nf_awards`
--

DROP TABLE IF EXISTS `nf_awards`;
CREATE TABLE `nf_awards` (
  `award_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `team_id` int(11) unsigned DEFAULT NULL,
  `game_id` int(11) unsigned NOT NULL,
  `image_id` int(11) unsigned DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `location` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `description` text NOT NULL,
  `platform` varchar(100) NOT NULL,
  `ranking` int(11) unsigned NOT NULL,
  `participants` int(11) unsigned NOT NULL,
  PRIMARY KEY (`award_id`),
  KEY `image_id` (`image_id`),
  KEY `game_id` (`game_id`),
  KEY `team_id` (`team_id`),
  CONSTRAINT `nf_awards_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `nf_teams` (`team_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `nf_awards_ibfk_2` FOREIGN KEY (`game_id`) REFERENCES `nf_games` (`game_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `nf_awards_ibfk_3` FOREIGN KEY (`image_id`) REFERENCES `nf_file` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nf_comment`
--

DROP TABLE IF EXISTS `nf_comment`;
CREATE TABLE `nf_comment` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) unsigned DEFAULT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `module_id` int(11) unsigned NOT NULL,
  `module` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `user_id` (`user_id`),
  KEY `module` (`module`),
  CONSTRAINT `nf_comment_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `nf_comment` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `nf_comment_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `nf_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nf_dispositions`
--

DROP TABLE IF EXISTS `nf_dispositions`;
CREATE TABLE `nf_dispositions` (
  `disposition_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `theme` varchar(100) NOT NULL,
  `page` varchar(100) NOT NULL,
  `zone` int(11) unsigned NOT NULL,
  `disposition` text NOT NULL,
  PRIMARY KEY (`disposition_id`),
  UNIQUE KEY `theme` (`theme`,`page`,`zone`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `nf_dispositions`
--

INSERT INTO `nf_dispositions` (`disposition_id`, `theme`, `page`, `zone`, `disposition`) VALUES
(1, 'default', '*', 0, 'O:27:\"NF\\NeoFrag\\Libraries\\Array_\":1:{s:9:\"\0*\0_array\";a:2:{i:0;O:27:\"NF\\NeoFrag\\Displayables\\Row\":2:{s:9:\"\0*\0_array\";a:2:{i:0;O:27:\"NF\\NeoFrag\\Displayables\\Col\":2:{s:9:\"\0*\0_array\";a:1:{i:0;O:30:\"NF\\NeoFrag\\Displayables\\Widget\":3:{s:10:\"\0*\0_widget\";i:1;s:9:\"\0*\0_style\";N;s:8:\"\0*\0_size\";s:5:\"col-8\";}}s:8:\"\0*\0_size\";N;}i:1;O:27:\"NF\\NeoFrag\\Displayables\\Col\":2:{s:9:\"\0*\0_array\";a:1:{i:0;O:30:\"NF\\NeoFrag\\Displayables\\Widget\":3:{s:10:\"\0*\0_widget\";i:2;s:9:\"\0*\0_style\";N;s:8:\"\0*\0_size\";s:5:\"col-4\";}}s:8:\"\0*\0_size\";N;}}s:9:\"\0*\0_style\";s:9:\"row-white\";}i:1;O:27:\"NF\\NeoFrag\\Displayables\\Row\":2:{s:9:\"\0*\0_array\";a:2:{i:0;O:27:\"NF\\NeoFrag\\Displayables\\Col\":2:{s:9:\"\0*\0_array\";a:1:{i:0;O:30:\"NF\\NeoFrag\\Displayables\\Widget\":3:{s:10:\"\0*\0_widget\";i:3;s:9:\"\0*\0_style\";N;s:8:\"\0*\0_size\";s:5:\"col-8\";}}s:8:\"\0*\0_size\";N;}i:1;O:27:\"NF\\NeoFrag\\Displayables\\Col\":2:{s:9:\"\0*\0_array\";a:6:{i:0;O:30:\"NF\\NeoFrag\\Displayables\\Widget\":3:{s:10:\"\0*\0_widget\";i:4;s:9:\"\0*\0_style\";N;s:8:\"\0*\0_size\";N;}i:1;O:30:\"NF\\NeoFrag\\Displayables\\Widget\":3:{s:10:\"\0*\0_widget\";i:5;s:9:\"\0*\0_style\";s:10:\"panel-dark\";s:8:\"\0*\0_size\";N;}i:2;O:30:\"NF\\NeoFrag\\Displayables\\Widget\":3:{s:10:\"\0*\0_widget\";i:6;s:9:\"\0*\0_style\";s:10:\"panel-dark\";s:8:\"\0*\0_size\";N;}i:3;O:30:\"NF\\NeoFrag\\Displayables\\Widget\":3:{s:10:\"\0*\0_widget\";i:7;s:9:\"\0*\0_style\";N;s:8:\"\0*\0_size\";N;}i:4;O:30:\"NF\\NeoFrag\\Displayables\\Widget\":3:{s:10:\"\0*\0_widget\";i:8;s:9:\"\0*\0_style\";N;s:8:\"\0*\0_size\";N;}i:5;O:30:\"NF\\NeoFrag\\Displayables\\Widget\":3:{s:10:\"\0*\0_widget\";i:9;s:9:\"\0*\0_style\";s:9:\"panel-red\";s:8:\"\0*\0_size\";N;}}s:8:\"\0*\0_size\";s:5:\"col-4\";}}s:9:\"\0*\0_style\";s:11:\"row-default\";}}}'),
(2, 'default', '*', 1, 'O:27:\"NF\\NeoFrag\\Libraries\\Array_\":1:{s:9:\"\0*\0_array\";a:1:{i:0;O:27:\"NF\\NeoFrag\\Displayables\\Row\":2:{s:9:\"\0*\0_array\";a:3:{i:0;O:27:\"NF\\NeoFrag\\Displayables\\Col\":2:{s:9:\"\0*\0_array\";a:1:{i:0;O:30:\"NF\\NeoFrag\\Displayables\\Widget\":3:{s:10:\"\0*\0_widget\";i:10;s:9:\"\0*\0_style\";N;s:8:\"\0*\0_size\";s:5:\"col-4\";}}s:8:\"\0*\0_size\";N;}i:1;O:27:\"NF\\NeoFrag\\Displayables\\Col\":2:{s:9:\"\0*\0_array\";a:1:{i:0;O:30:\"NF\\NeoFrag\\Displayables\\Widget\":3:{s:10:\"\0*\0_widget\";i:11;s:9:\"\0*\0_style\";s:10:\"panel-dark\";s:8:\"\0*\0_size\";s:5:\"col-4\";}}s:8:\"\0*\0_size\";N;}i:2;O:27:\"NF\\NeoFrag\\Displayables\\Col\":2:{s:9:\"\0*\0_array\";a:1:{i:0;O:30:\"NF\\NeoFrag\\Displayables\\Widget\":3:{s:10:\"\0*\0_widget\";i:12;s:9:\"\0*\0_style\";s:9:\"panel-red\";s:8:\"\0*\0_size\";s:5:\"col-4\";}}s:8:\"\0*\0_size\";N;}}s:9:\"\0*\0_style\";s:11:\"row-default\";}}}'),
(3, 'default', '*', 2, 'O:27:\"NF\\NeoFrag\\Libraries\\Array_\":1:{s:9:\"\0*\0_array\";a:0:{}}'),
(4, 'default', '*', 3, 'O:27:\"NF\\NeoFrag\\Libraries\\Array_\":1:{s:9:\"\0*\0_array\";a:2:{i:0;O:27:\"NF\\NeoFrag\\Displayables\\Row\":2:{s:9:\"\0*\0_array\";a:1:{i:0;O:27:\"NF\\NeoFrag\\Displayables\\Col\":2:{s:9:\"\0*\0_array\";a:1:{i:0;O:30:\"NF\\NeoFrag\\Displayables\\Widget\":3:{s:10:\"\0*\0_widget\";i:13;s:9:\"\0*\0_style\";N;s:8:\"\0*\0_size\";N;}}s:8:\"\0*\0_size\";N;}}s:9:\"\0*\0_style\";s:11:\"row-default\";}i:1;O:27:\"NF\\NeoFrag\\Displayables\\Row\":2:{s:9:\"\0*\0_array\";a:2:{i:0;O:27:\"NF\\NeoFrag\\Displayables\\Col\":2:{s:9:\"\0*\0_array\";a:1:{i:0;O:30:\"NF\\NeoFrag\\Displayables\\Widget\":3:{s:10:\"\0*\0_widget\";i:14;s:9:\"\0*\0_style\";N;s:8:\"\0*\0_size\";s:5:\"col-7\";}}s:8:\"\0*\0_size\";N;}i:1;O:27:\"NF\\NeoFrag\\Displayables\\Col\":2:{s:9:\"\0*\0_array\";a:1:{i:0;O:30:\"NF\\NeoFrag\\Displayables\\Widget\":3:{s:10:\"\0*\0_widget\";i:15;s:9:\"\0*\0_style\";N;s:8:\"\0*\0_size\";s:5:\"col-5\";}}s:8:\"\0*\0_size\";N;}}s:9:\"\0*\0_style\";s:9:\"row-white\";}}}'),
(5, 'default', '*', 4, 'O:27:\"NF\\NeoFrag\\Libraries\\Array_\":1:{s:9:\"\0*\0_array\";a:1:{i:0;O:27:\"NF\\NeoFrag\\Displayables\\Row\":2:{s:9:\"\0*\0_array\";a:2:{i:0;O:27:\"NF\\NeoFrag\\Displayables\\Col\":2:{s:9:\"\0*\0_array\";a:1:{i:0;O:30:\"NF\\NeoFrag\\Displayables\\Widget\":3:{s:10:\"\0*\0_widget\";i:16;s:9:\"\0*\0_style\";N;s:8:\"\0*\0_size\";s:5:\"col-8\";}}s:8:\"\0*\0_size\";N;}i:1;O:27:\"NF\\NeoFrag\\Displayables\\Col\":2:{s:9:\"\0*\0_array\";a:1:{i:0;O:30:\"NF\\NeoFrag\\Displayables\\Widget\":3:{s:10:\"\0*\0_widget\";i:17;s:9:\"\0*\0_style\";N;s:8:\"\0*\0_size\";s:5:\"col-4\";}}s:8:\"\0*\0_size\";N;}}s:9:\"\0*\0_style\";s:11:\"row-default\";}}}'),
(6, 'default', '*', 5, 'O:27:\"NF\\NeoFrag\\Libraries\\Array_\":1:{s:9:\"\0*\0_array\";a:1:{i:0;O:27:\"NF\\NeoFrag\\Displayables\\Row\":2:{s:9:\"\0*\0_array\";a:1:{i:0;O:27:\"NF\\NeoFrag\\Displayables\\Col\":2:{s:9:\"\0*\0_array\";a:1:{i:0;O:30:\"NF\\NeoFrag\\Displayables\\Widget\":3:{s:10:\"\0*\0_widget\";i:18;s:9:\"\0*\0_style\";s:10:\"panel-dark\";s:8:\"\0*\0_size\";N;}}s:8:\"\0*\0_size\";N;}}s:9:\"\0*\0_style\";s:11:\"row-default\";}}}'),
(7, 'default', '/', 3, 'O:27:\"NF\\NeoFrag\\Libraries\\Array_\":1:{s:9:\"\0*\0_array\";a:3:{i:0;O:27:\"NF\\NeoFrag\\Displayables\\Row\":2:{s:9:\"\0*\0_array\";a:1:{i:0;O:27:\"NF\\NeoFrag\\Displayables\\Col\":2:{s:9:\"\0*\0_array\";a:1:{i:0;O:30:\"NF\\NeoFrag\\Displayables\\Widget\":3:{s:10:\"\0*\0_widget\";i:19;s:9:\"\0*\0_style\";N;s:8:\"\0*\0_size\";N;}}s:8:\"\0*\0_size\";N;}}s:9:\"\0*\0_style\";s:11:\"row-default\";}i:1;O:27:\"NF\\NeoFrag\\Displayables\\Row\":2:{s:9:\"\0*\0_array\";a:2:{i:0;O:27:\"NF\\NeoFrag\\Displayables\\Col\":2:{s:9:\"\0*\0_array\";a:1:{i:0;O:30:\"NF\\NeoFrag\\Displayables\\Widget\":3:{s:10:\"\0*\0_widget\";i:20;s:9:\"\0*\0_style\";N;s:8:\"\0*\0_size\";s:5:\"col-7\";}}s:8:\"\0*\0_size\";N;}i:1;O:27:\"NF\\NeoFrag\\Displayables\\Col\":2:{s:9:\"\0*\0_array\";a:1:{i:0;O:30:\"NF\\NeoFrag\\Displayables\\Widget\":3:{s:10:\"\0*\0_widget\";i:21;s:9:\"\0*\0_style\";N;s:8:\"\0*\0_size\";s:5:\"col-5\";}}s:8:\"\0*\0_size\";N;}}s:9:\"\0*\0_style\";s:9:\"row-white\";}i:2;O:27:\"NF\\NeoFrag\\Displayables\\Row\":2:{s:9:\"\0*\0_array\";a:1:{i:0;O:27:\"NF\\NeoFrag\\Displayables\\Col\":2:{s:9:\"\0*\0_array\";a:1:{i:0;O:30:\"NF\\NeoFrag\\Displayables\\Widget\":3:{s:10:\"\0*\0_widget\";i:22;s:9:\"\0*\0_style\";N;s:8:\"\0*\0_size\";N;}}s:8:\"\0*\0_size\";N;}}s:9:\"\0*\0_style\";s:11:\"row-default\";}}}'),
(8, 'default', 'forum/*', 0, 'O:27:\"NF\\NeoFrag\\Libraries\\Array_\":1:{s:9:\"\0*\0_array\";a:2:{i:0;O:27:\"NF\\NeoFrag\\Displayables\\Row\":2:{s:9:\"\0*\0_array\";a:2:{i:0;O:27:\"NF\\NeoFrag\\Displayables\\Col\":2:{s:9:\"\0*\0_array\";a:1:{i:0;O:30:\"NF\\NeoFrag\\Displayables\\Widget\":3:{s:10:\"\0*\0_widget\";i:23;s:9:\"\0*\0_style\";N;s:8:\"\0*\0_size\";s:5:\"col-8\";}}s:8:\"\0*\0_size\";N;}i:1;O:27:\"NF\\NeoFrag\\Displayables\\Col\":2:{s:9:\"\0*\0_array\";a:1:{i:0;O:30:\"NF\\NeoFrag\\Displayables\\Widget\":3:{s:10:\"\0*\0_widget\";i:24;s:9:\"\0*\0_style\";N;s:8:\"\0*\0_size\";s:5:\"col-4\";}}s:8:\"\0*\0_size\";N;}}s:9:\"\0*\0_style\";s:9:\"row-white\";}i:1;O:27:\"NF\\NeoFrag\\Displayables\\Row\":2:{s:9:\"\0*\0_array\";a:1:{i:0;O:27:\"NF\\NeoFrag\\Displayables\\Col\":2:{s:9:\"\0*\0_array\";a:1:{i:0;O:30:\"NF\\NeoFrag\\Displayables\\Widget\":3:{s:10:\"\0*\0_widget\";i:25;s:9:\"\0*\0_style\";N;s:8:\"\0*\0_size\";N;}}s:8:\"\0*\0_size\";N;}}s:9:\"\0*\0_style\";s:11:\"row-default\";}}}'),
(9, 'default', 'forum/*', 2, 'O:27:\"NF\\NeoFrag\\Libraries\\Array_\":1:{s:9:\"\0*\0_array\";a:1:{i:0;O:27:\"NF\\NeoFrag\\Displayables\\Row\":2:{s:9:\"\0*\0_array\";a:2:{i:0;O:27:\"NF\\NeoFrag\\Displayables\\Col\":2:{s:9:\"\0*\0_array\";a:1:{i:0;O:30:\"NF\\NeoFrag\\Displayables\\Widget\":3:{s:10:\"\0*\0_widget\";i:34;s:9:\"\0*\0_style\";s:9:\"panel-red\";s:8:\"\0*\0_size\";s:5:\"col-4\";}}s:8:\"\0*\0_size\";N;}i:1;O:27:\"NF\\NeoFrag\\Displayables\\Col\":2:{s:9:\"\0*\0_array\";a:1:{i:0;O:30:\"NF\\NeoFrag\\Displayables\\Widget\":3:{s:10:\"\0*\0_widget\";i:35;s:9:\"\0*\0_style\";s:10:\"panel-dark\";s:8:\"\0*\0_size\";s:5:\"col-8\";}}s:8:\"\0*\0_size\";N;}}s:9:\"\0*\0_style\";s:11:\"row-default\";}}}'),
(10, 'default', 'news/_news/*', 0, 'O:27:\"NF\\NeoFrag\\Libraries\\Array_\":1:{s:9:\"\0*\0_array\";a:2:{i:0;O:27:\"NF\\NeoFrag\\Displayables\\Row\":2:{s:9:\"\0*\0_array\";a:2:{i:0;O:27:\"NF\\NeoFrag\\Displayables\\Col\":2:{s:9:\"\0*\0_array\";a:1:{i:0;O:30:\"NF\\NeoFrag\\Displayables\\Widget\":3:{s:10:\"\0*\0_widget\";i:26;s:9:\"\0*\0_style\";N;s:8:\"\0*\0_size\";s:5:\"col-8\";}}s:8:\"\0*\0_size\";N;}i:1;O:27:\"NF\\NeoFrag\\Displayables\\Col\":2:{s:9:\"\0*\0_array\";a:1:{i:0;O:30:\"NF\\NeoFrag\\Displayables\\Widget\":3:{s:10:\"\0*\0_widget\";i:27;s:9:\"\0*\0_style\";N;s:8:\"\0*\0_size\";s:5:\"col-4\";}}s:8:\"\0*\0_size\";N;}}s:9:\"\0*\0_style\";s:9:\"row-white\";}i:1;O:27:\"NF\\NeoFrag\\Displayables\\Row\":2:{s:9:\"\0*\0_array\";a:1:{i:0;O:27:\"NF\\NeoFrag\\Displayables\\Col\":2:{s:9:\"\0*\0_array\";a:1:{i:0;O:30:\"NF\\NeoFrag\\Displayables\\Widget\":3:{s:10:\"\0*\0_widget\";i:28;s:9:\"\0*\0_style\";N;s:8:\"\0*\0_size\";N;}}s:8:\"\0*\0_size\";N;}}s:9:\"\0*\0_style\";s:11:\"row-default\";}}}'),
(11, 'default', 'user/*', 0, 'O:27:\"NF\\NeoFrag\\Libraries\\Array_\":1:{s:9:\"\0*\0_array\";a:2:{i:0;O:27:\"NF\\NeoFrag\\Displayables\\Row\":2:{s:9:\"\0*\0_array\";a:2:{i:0;O:27:\"NF\\NeoFrag\\Displayables\\Col\":2:{s:9:\"\0*\0_array\";a:1:{i:0;O:30:\"NF\\NeoFrag\\Displayables\\Widget\":3:{s:10:\"\0*\0_widget\";i:29;s:9:\"\0*\0_style\";N;s:8:\"\0*\0_size\";s:5:\"col-8\";}}s:8:\"\0*\0_size\";N;}i:1;O:27:\"NF\\NeoFrag\\Displayables\\Col\":2:{s:9:\"\0*\0_array\";a:1:{i:0;O:30:\"NF\\NeoFrag\\Displayables\\Widget\":3:{s:10:\"\0*\0_widget\";i:30;s:9:\"\0*\0_style\";N;s:8:\"\0*\0_size\";s:5:\"col-4\";}}s:8:\"\0*\0_size\";N;}}s:9:\"\0*\0_style\";s:9:\"row-white\";}i:1;O:27:\"NF\\NeoFrag\\Displayables\\Row\":2:{s:9:\"\0*\0_array\";a:1:{i:0;O:27:\"NF\\NeoFrag\\Displayables\\Col\":2:{s:9:\"\0*\0_array\";a:1:{i:0;O:30:\"NF\\NeoFrag\\Displayables\\Widget\":3:{s:10:\"\0*\0_widget\";i:31;s:9:\"\0*\0_style\";N;s:8:\"\0*\0_size\";N;}}s:8:\"\0*\0_size\";N;}}s:9:\"\0*\0_style\";s:11:\"row-default\";}}}'),
(12, 'default', 'search/*', 0, 'O:27:\"NF\\NeoFrag\\Libraries\\Array_\":1:{s:9:\"\0*\0_array\";a:2:{i:0;O:27:\"NF\\NeoFrag\\Displayables\\Row\":2:{s:9:\"\0*\0_array\";a:2:{i:0;O:27:\"NF\\NeoFrag\\Displayables\\Col\":2:{s:9:\"\0*\0_array\";a:1:{i:0;O:30:\"NF\\NeoFrag\\Displayables\\Widget\":3:{s:10:\"\0*\0_widget\";i:32;s:9:\"\0*\0_style\";N;s:8:\"\0*\0_size\";s:5:\"col-8\";}}s:8:\"\0*\0_size\";N;}i:1;N;}s:9:\"\0*\0_style\";s:9:\"row-white\";}i:1;O:27:\"NF\\NeoFrag\\Displayables\\Row\":2:{s:9:\"\0*\0_array\";a:1:{i:0;O:27:\"NF\\NeoFrag\\Displayables\\Col\":2:{s:9:\"\0*\0_array\";a:1:{i:0;O:30:\"NF\\NeoFrag\\Displayables\\Widget\":3:{s:10:\"\0*\0_widget\";i:33;s:9:\"\0*\0_style\";N;s:8:\"\0*\0_size\";N;}}s:8:\"\0*\0_size\";N;}}s:9:\"\0*\0_style\";s:11:\"row-default\";}}}');

-- --------------------------------------------------------

--
-- Table structure for table `nf_events`
--

DROP TABLE IF EXISTS `nf_events`;
CREATE TABLE `nf_events` (
  `event_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `image_id` int(11) unsigned DEFAULT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `private_description` text NOT NULL,
  `location` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `date_end` timestamp NULL DEFAULT NULL,
  `published` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`event_id`),
  KEY `user_id` (`user_id`),
  KEY `type_id` (`type_id`) USING BTREE,
  KEY `image_id` (`image_id`),
  CONSTRAINT `nf_events_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `nf_events_types` (`type_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `nf_events_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `nf_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `nf_events_ibfk_3` FOREIGN KEY (`image_id`) REFERENCES `nf_file` (`id`) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nf_events_matches`
--

DROP TABLE IF EXISTS `nf_events_matches`;
CREATE TABLE `nf_events_matches` (
  `event_id` int(11) unsigned NOT NULL,
  `team_id` int(11) unsigned NOT NULL,
  `opponent_id` int(11) unsigned NOT NULL,
  `mode_id` int(11) unsigned DEFAULT NULL,
  `webtv` varchar(100) NOT NULL,
  `website` varchar(100) NOT NULL,
  PRIMARY KEY (`event_id`),
  KEY `opponent_id` (`opponent_id`),
  KEY `mode_id` (`mode_id`),
  KEY `team_id` (`team_id`),
  CONSTRAINT `nf_events_matches_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `nf_events` (`event_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `nf_events_matches_ibfk_2` FOREIGN KEY (`opponent_id`) REFERENCES `nf_events_matches_opponents` (`opponent_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `nf_events_matches_ibfk_3` FOREIGN KEY (`mode_id`) REFERENCES `nf_games_modes` (`mode_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nf_events_matches_opponents`
--

DROP TABLE IF EXISTS `nf_events_matches_opponents`;
CREATE TABLE `nf_events_matches_opponents` (
  `opponent_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `image_id` int(11) unsigned DEFAULT NULL,
  `title` varchar(100) NOT NULL,
  `website` varchar(100) NOT NULL,
  `country` varchar(5) NOT NULL,
  PRIMARY KEY (`opponent_id`),
  KEY `image_id` (`image_id`),
  CONSTRAINT `nf_events_matches_opponents_ibfk_1` FOREIGN KEY (`image_id`) REFERENCES `nf_file` (`id`) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nf_events_matches_rounds`
--

DROP TABLE IF EXISTS `nf_events_matches_rounds`;
CREATE TABLE `nf_events_matches_rounds` (
  `round_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` int(11) unsigned NOT NULL,
  `map_id` int(11) unsigned DEFAULT NULL,
  `score1` int(11) NOT NULL,
  `score2` int(11) NOT NULL,
  PRIMARY KEY (`round_id`),
  KEY `event_id` (`event_id`),
  KEY `map_id` (`map_id`),
  CONSTRAINT `nf_events_matches_rounds_ibfk_1` FOREIGN KEY (`map_id`) REFERENCES `nf_games_maps` (`map_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `nf_events_matches_rounds_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `nf_events` (`event_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nf_events_participants`
--

DROP TABLE IF EXISTS `nf_events_participants`;
CREATE TABLE `nf_events_participants` (
  `event_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `status` smallint(6) unsigned NOT NULL,
  PRIMARY KEY (`event_id`,`user_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `nf_events_participants_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `nf_events` (`event_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `nf_events_participants_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `nf_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nf_events_types`
--

DROP TABLE IF EXISTS `nf_events_types`;
CREATE TABLE `nf_events_types` (
  `type_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL,
  `color` varchar(20) NOT NULL,
  `icon` varchar(20) NOT NULL,
  PRIMARY KEY (`type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `nf_events_types`
--

INSERT INTO `nf_events_types` (`type_id`, `type`, `title`, `color`, `icon`) VALUES
(1, 1, 'Entrainement', 'success', 'fa-gamepad'),
(2, 1, 'Match amical', 'info', 'fa-angellist'),
(3, 1, 'Match officiel', 'warning', 'fa-trophy'),
(4, 0, 'IRL', 'primary', 'fa-glass'),
(5, 0, 'Divers', 'default', 'fa-comments'),
(6, 1, 'Réunion', 'danger', 'fa-briefcase');

-- --------------------------------------------------------

--
-- Table structure for table `nf_file`
--

DROP TABLE IF EXISTS `nf_file`;
CREATE TABLE `nf_file` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `path` varchar(100) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `path` (`path`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `nf_file_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `nf_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `nf_file`
--

INSERT INTO `nf_file` (`id`, `user_id`, `name`, `path`, `date`) VALUES
(1, 1, 'Sans-titre-2.jpg', './upload/news/categories/ubfuejdfooirqya0pyltfeklja4ew4sn.jpg', '2015-05-30 00:34:16'),
(2, 1, 'logo.png', 'upload/partners/zwvmsjijfljaka4rdblgvlype1lnbwaw.png', '2016-05-07 18:51:53'),
(3, 1, 'logo_black.png', 'upload/partners/y4ofwq2ekppwnfpmnrmnafeivszlg5bd.png', '2016-05-07 18:51:53');

-- --------------------------------------------------------

--
-- Table structure for table `nf_forum`
--

DROP TABLE IF EXISTS `nf_forum`;
CREATE TABLE `nf_forum` (
  `forum_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) unsigned NOT NULL,
  `is_subforum` enum('0','1') NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL,
  `description` varchar(100) NOT NULL,
  `order` smallint(6) unsigned NOT NULL DEFAULT '0',
  `count_topics` int(11) unsigned NOT NULL DEFAULT '0',
  `count_messages` int(11) unsigned NOT NULL DEFAULT '0',
  `last_message_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`forum_id`),
  KEY `last_message_id` (`last_message_id`),
  CONSTRAINT `nf_forum_ibfk_1` FOREIGN KEY (`last_message_id`) REFERENCES `nf_forum_messages` (`message_id`) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `nf_forum`
--

INSERT INTO `nf_forum` (`forum_id`, `parent_id`, `is_subforum`, `title`, `description`, `order`, `count_topics`, `count_messages`, `last_message_id`) VALUES
(1, 1, '0', 'Discussions g&eacute;n&eacute;rales', 'Ceci est votre tout premier forum !', 0, 0, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `nf_forum_categories`
--

DROP TABLE IF EXISTS `nf_forum_categories`;
CREATE TABLE `nf_forum_categories` (
  `category_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `order` smallint(6) unsigned DEFAULT '0',
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `nf_forum_categories`
--

INSERT INTO `nf_forum_categories` (`category_id`, `title`, `order`) VALUES
(1, 'G&eacute;n&eacute;ral', 0);

-- --------------------------------------------------------

--
-- Table structure for table `nf_forum_messages`
--

DROP TABLE IF EXISTS `nf_forum_messages`;
CREATE TABLE `nf_forum_messages` (
  `message_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `topic_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned DEFAULT NULL,
  `message` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`message_id`),
  KEY `topic_id` (`topic_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `nf_forum_messages_ibfk_1` FOREIGN KEY (`topic_id`) REFERENCES `nf_forum_topics` (`topic_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `nf_forum_messages_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `nf_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nf_forum_polls`
--

DROP TABLE IF EXISTS `nf_forum_polls`;
CREATE TABLE `nf_forum_polls` (
  `topic_id` int(11) unsigned NOT NULL,
  `question` varchar(100) NOT NULL,
  `answers` text NOT NULL,
  `is_multiple_choice` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nf_forum_read`
--

DROP TABLE IF EXISTS `nf_forum_read`;
CREATE TABLE `nf_forum_read` (
  `user_id` int(11) unsigned NOT NULL,
  `forum_id` int(11) unsigned NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`,`forum_id`),
  CONSTRAINT `nf_forum_read_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `nf_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nf_forum_topics`
--

DROP TABLE IF EXISTS `nf_forum_topics`;
CREATE TABLE `nf_forum_topics` (
  `topic_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `forum_id` int(11) unsigned NOT NULL,
  `message_id` int(11) unsigned DEFAULT NULL,
  `title` varchar(100) NOT NULL,
  `status` enum('-2','-1','0','1') NOT NULL DEFAULT '0',
  `views` int(11) unsigned NOT NULL DEFAULT '0',
  `count_messages` int(11) unsigned NOT NULL DEFAULT '0',
  `last_message_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`topic_id`),
  UNIQUE KEY `last_message_id` (`last_message_id`),
  KEY `forum_id` (`forum_id`),
  KEY `message_id` (`message_id`),
  CONSTRAINT `nf_forum_topics_ibfk_1` FOREIGN KEY (`forum_id`) REFERENCES `nf_forum` (`forum_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `nf_forum_topics_ibfk_2` FOREIGN KEY (`message_id`) REFERENCES `nf_forum_messages` (`message_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `nf_forum_topics_ibfk_3` FOREIGN KEY (`last_message_id`) REFERENCES `nf_forum_messages` (`message_id`) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nf_forum_topics_read`
--

DROP TABLE IF EXISTS `nf_forum_topics_read`;
CREATE TABLE `nf_forum_topics_read` (
  `topic_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`topic_id`,`user_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `nf_forum_topics_read_ibfk_1` FOREIGN KEY (`topic_id`) REFERENCES `nf_forum_topics` (`topic_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `nf_forum_topics_read_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `nf_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nf_forum_track`
--

DROP TABLE IF EXISTS `nf_forum_track`;
CREATE TABLE `nf_forum_track` (
  `topic_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`topic_id`,`user_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `nf_forum_track_ibfk_1` FOREIGN KEY (`topic_id`) REFERENCES `nf_forum_topics` (`topic_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `nf_forum_track_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `nf_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nf_forum_url`
--

DROP TABLE IF EXISTS `nf_forum_url`;
CREATE TABLE `nf_forum_url` (
  `forum_id` int(11) unsigned NOT NULL,
  `url` varchar(100) NOT NULL,
  `redirects` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`forum_id`),
  CONSTRAINT `nf_forum_url_ibfk_1` FOREIGN KEY (`forum_id`) REFERENCES `nf_forum` (`forum_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nf_gallery`
--

DROP TABLE IF EXISTS `nf_gallery`;
CREATE TABLE `nf_gallery` (
  `gallery_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(11) unsigned NOT NULL,
  `image_id` int(11) unsigned DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `published` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`gallery_id`),
  KEY `category_id` (`category_id`),
  KEY `image_id` (`image_id`),
  CONSTRAINT `nf_gallery_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `nf_gallery_categories` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `nf_gallery_ibfk_2` FOREIGN KEY (`image_id`) REFERENCES `nf_file` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nf_gallery_categories`
--

DROP TABLE IF EXISTS `nf_gallery_categories`;
CREATE TABLE `nf_gallery_categories` (
  `category_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `image_id` int(11) unsigned DEFAULT NULL,
  `icon_id` int(11) unsigned DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`category_id`),
  KEY `image_id` (`image_id`),
  KEY `icon_id` (`icon_id`),
  CONSTRAINT `nf_gallery_categories_ibfk_1` FOREIGN KEY (`image_id`) REFERENCES `nf_file` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `nf_gallery_categories_ibfk_2` FOREIGN KEY (`icon_id`) REFERENCES `nf_file` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nf_gallery_categories_lang`
--

DROP TABLE IF EXISTS `nf_gallery_categories_lang`;
CREATE TABLE `nf_gallery_categories_lang` (
  `category_id` int(11) unsigned NOT NULL,
  `lang` varchar(5) NOT NULL,
  `title` varchar(100) NOT NULL,
  PRIMARY KEY (`category_id`,`lang`),
  KEY `lang` (`lang`),
  CONSTRAINT `nf_gallery_categories_lang_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `nf_gallery_categories` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nf_gallery_images`
--

DROP TABLE IF EXISTS `nf_gallery_images`;
CREATE TABLE `nf_gallery_images` (
  `image_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `thumbnail_file_id` int(11) unsigned NOT NULL,
  `original_file_id` int(11) unsigned NOT NULL,
  `file_id` int(11) unsigned NOT NULL,
  `gallery_id` int(11) unsigned NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `views` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`image_id`),
  KEY `file_id` (`file_id`),
  KEY `gallery_id` (`gallery_id`),
  KEY `original_file_id` (`original_file_id`),
  KEY `thumbnail_file_id` (`thumbnail_file_id`),
  CONSTRAINT `nf_gallery_images_ibfk_1` FOREIGN KEY (`file_id`) REFERENCES `nf_file` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `nf_gallery_images_ibfk_2` FOREIGN KEY (`gallery_id`) REFERENCES `nf_gallery` (`gallery_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `nf_gallery_images_ibfk_3` FOREIGN KEY (`thumbnail_file_id`) REFERENCES `nf_file` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `nf_gallery_images_ibfk_4` FOREIGN KEY (`original_file_id`) REFERENCES `nf_file` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nf_gallery_lang`
--

DROP TABLE IF EXISTS `nf_gallery_lang`;
CREATE TABLE `nf_gallery_lang` (
  `gallery_id` int(11) unsigned NOT NULL,
  `lang` varchar(5) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`gallery_id`,`lang`),
  KEY `lang` (`lang`),
  CONSTRAINT `nf_gallery_lang_ibfk_1` FOREIGN KEY (`gallery_id`) REFERENCES `nf_gallery` (`gallery_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nf_games`
--

DROP TABLE IF EXISTS `nf_games`;
CREATE TABLE `nf_games` (
  `game_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) unsigned DEFAULT NULL,
  `image_id` int(11) unsigned DEFAULT NULL,
  `icon_id` int(11) unsigned DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`game_id`),
  KEY `image_id` (`image_id`),
  KEY `icon_id` (`icon_id`),
  KEY `parent_id` (`parent_id`),
  CONSTRAINT `nf_games_ibfk_1` FOREIGN KEY (`image_id`) REFERENCES `nf_file` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `nf_games_ibfk_2` FOREIGN KEY (`icon_id`) REFERENCES `nf_file` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `nf_games_ibfk_3` FOREIGN KEY (`parent_id`) REFERENCES `nf_games` (`game_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nf_games_lang`
--

DROP TABLE IF EXISTS `nf_games_lang`;
CREATE TABLE `nf_games_lang` (
  `game_id` int(11) unsigned NOT NULL,
  `lang` varchar(5) NOT NULL,
  `title` varchar(100) NOT NULL,
  PRIMARY KEY (`game_id`,`lang`),
  KEY `lang` (`lang`),
  CONSTRAINT `nf_games_lang_ibfk_1` FOREIGN KEY (`game_id`) REFERENCES `nf_games` (`game_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nf_games_maps`
--

DROP TABLE IF EXISTS `nf_games_maps`;
CREATE TABLE `nf_games_maps` (
  `map_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `game_id` int(11) unsigned NOT NULL,
  `image_id` int(11) unsigned DEFAULT NULL,
  `title` varchar(100) NOT NULL,
  PRIMARY KEY (`map_id`),
  KEY `game_id` (`game_id`),
  KEY `image_id` (`image_id`),
  CONSTRAINT `nf_games_maps_ibfk_1` FOREIGN KEY (`game_id`) REFERENCES `nf_games` (`game_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `nf_games_maps_ibfk_2` FOREIGN KEY (`image_id`) REFERENCES `nf_file` (`id`) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nf_games_modes`
--

DROP TABLE IF EXISTS `nf_games_modes`;
CREATE TABLE `nf_games_modes` (
  `mode_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `game_id` int(11) unsigned NOT NULL,
  `title` varchar(100) NOT NULL,
  PRIMARY KEY (`mode_id`),
  KEY `game_id` (`game_id`),
  CONSTRAINT `nf_games_modes_ibfk_1` FOREIGN KEY (`game_id`) REFERENCES `nf_games` (`game_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nf_groups`
--

DROP TABLE IF EXISTS `nf_groups`;
CREATE TABLE `nf_groups` (
  `group_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `color` varchar(20) NOT NULL,
  `icon` varchar(20) NOT NULL,
  `hidden` enum('0','1') NOT NULL DEFAULT '0',
  `auto` enum('0','1') NOT NULL DEFAULT '0',
  `order` smallint(6) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nf_groups_lang`
--

DROP TABLE IF EXISTS `nf_groups_lang`;
CREATE TABLE `nf_groups_lang` (
  `group_id` int(11) unsigned NOT NULL,
  `lang` varchar(5) NOT NULL,
  `title` varchar(100) NOT NULL,
  PRIMARY KEY (`group_id`,`lang`),
  KEY `lang` (`lang`),
  CONSTRAINT `nf_groups_lang_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `nf_groups` (`group_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nf_i18n`
--

DROP TABLE IF EXISTS `nf_i18n`;
CREATE TABLE `nf_i18n` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lang_id` int(10) unsigned NOT NULL,
  `model` varchar(100) DEFAULT NULL,
  `model_id` int(10) unsigned DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lang_id` (`lang_id`,`model`,`model_id`,`name`) USING BTREE,
  KEY `lang_id_2` (`lang_id`),
  KEY `model` (`model`),
  KEY `model_id` (`model_id`),
  KEY `name` (`name`),
  CONSTRAINT `nf_i18n_ibfk_1` FOREIGN KEY (`lang_id`) REFERENCES `nf_addon` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nf_log_db`
--

DROP TABLE IF EXISTS `nf_log_db`;
CREATE TABLE `nf_log_db` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `action` enum('0','1','2') NOT NULL,
  `model` varchar(100) NOT NULL,
  `primaries` varchar(100) DEFAULT NULL,
  `data` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nf_log_i18n`
--

DROP TABLE IF EXISTS `nf_log_i18n`;
CREATE TABLE `nf_log_i18n` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `language` char(2) NOT NULL,
  `key` char(32) NOT NULL,
  `locale` text NOT NULL,
  `file` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `language` (`language`,`key`,`file`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nf_news`
--

DROP TABLE IF EXISTS `nf_news`;
CREATE TABLE `nf_news` (
  `news_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `image_id` int(11) unsigned DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `published` enum('0','1') NOT NULL DEFAULT '0',
  `views` int(11) unsigned NOT NULL DEFAULT '0',
  `vote` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`news_id`),
  KEY `category_id` (`category_id`),
  KEY `user_id` (`user_id`),
  KEY `image_id` (`image_id`),
  CONSTRAINT `nf_news_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `nf_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `nf_news_ibfk_2` FOREIGN KEY (`image_id`) REFERENCES `nf_file` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `nf_news_ibfk_4` FOREIGN KEY (`category_id`) REFERENCES `nf_news_categories` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `nf_news`
--

INSERT INTO `nf_news` (`news_id`, `category_id`, `user_id`, `image_id`, `date`, `published`, `views`, `vote`) VALUES
(1, 1, 1, NULL, CURRENT_TIMESTAMP, '1', 0, '0');

-- --------------------------------------------------------

--
-- Table structure for table `nf_news_categories`
--

DROP TABLE IF EXISTS `nf_news_categories`;
CREATE TABLE `nf_news_categories` (
  `category_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `image_id` int(11) unsigned DEFAULT NULL,
  `icon_id` int(11) unsigned DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`category_id`),
  KEY `image_id` (`image_id`),
  KEY `icon_id` (`icon_id`),
  CONSTRAINT `nf_news_categories_ibfk_1` FOREIGN KEY (`image_id`) REFERENCES `nf_file` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `nf_news_categories_ibfk_2` FOREIGN KEY (`icon_id`) REFERENCES `nf_file` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `nf_news_categories`
--

INSERT INTO `nf_news_categories` (`category_id`, `image_id`, `icon_id`, `name`) VALUES
(1, 1, NULL, 'general');

-- --------------------------------------------------------

--
-- Table structure for table `nf_news_categories_lang`
--

DROP TABLE IF EXISTS `nf_news_categories_lang`;
CREATE TABLE `nf_news_categories_lang` (
  `category_id` int(11) unsigned NOT NULL,
  `lang` varchar(5) NOT NULL,
  `title` varchar(100) NOT NULL,
  PRIMARY KEY (`category_id`,`lang`),
  KEY `lang` (`lang`),
  CONSTRAINT `nf_news_categories_lang_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `nf_news_categories` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `nf_news_categories_lang`
--

INSERT INTO `nf_news_categories_lang` (`category_id`, `lang`, `title`) VALUES
(1, 'fr', 'G&eacute;n&eacute;ral');

-- --------------------------------------------------------

--
-- Table structure for table `nf_news_lang`
--

DROP TABLE IF EXISTS `nf_news_lang`;
CREATE TABLE `nf_news_lang` (
  `news_id` int(11) unsigned NOT NULL,
  `lang` varchar(5) NOT NULL,
  `title` varchar(100) NOT NULL,
  `introduction` text NOT NULL,
  `content` text NOT NULL,
  `tags` text NOT NULL,
  PRIMARY KEY (`news_id`,`lang`),
  KEY `lang` (`lang`),
  CONSTRAINT `nf_news_lang_ibfk_1` FOREIGN KEY (`news_id`) REFERENCES `nf_news` (`news_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `nf_news_lang`
--

INSERT INTO `nf_news_lang` (`news_id`, `lang`, `title`, `introduction`, `content`, `tags`) VALUES
(1, 'fr', 'Bienvenue sur votre site NeoFrag Alpha !', 'Nec vox accusatoris ulla licet subditicii in his malorum quaerebatur acervis ut saltem specie tenus crimina praescriptis legum \r\ncommitterentur, quod aliquotiens fecere principes saevi: sed quicquid \r\nCaesaris mplacabilitati sedisset, id velut fas iusque perpensum \r\nconfestim urgebatur impleri.', 'Omitto iuris dictionem in libera civitate contra leges senatusque consulta; caedes relinquo; libidines praetereo, quarum acerbissimum extat indicium et ad insignem memoriam turpitudinis et paene ad iustum odium imperii nostri, quod constat nobilissimas virgines se in puteos abiecisse et morte voluntaria necessariam turpitudinem depulisse. Nec haec idcirco omitto, quod non gravissima sint, sed quia nunc sine teste dico.', 'NeoFrag,Cms,Alpha');

-- --------------------------------------------------------

--
-- Table structure for table `nf_pages`
--

DROP TABLE IF EXISTS `nf_pages`;
CREATE TABLE `nf_pages` (
  `page_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `published` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`page_id`),
  UNIQUE KEY `page` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nf_pages_lang`
--

DROP TABLE IF EXISTS `nf_pages_lang`;
CREATE TABLE `nf_pages_lang` (
  `page_id` int(11) unsigned NOT NULL,
  `lang` varchar(5) NOT NULL,
  `title` varchar(100) NOT NULL,
  `subtitle` varchar(100) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`page_id`,`lang`),
  KEY `lang` (`lang`),
  CONSTRAINT `nf_pages_lang_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `nf_pages` (`page_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nf_partners`
--

DROP TABLE IF EXISTS `nf_partners`;
CREATE TABLE `nf_partners` (
  `partner_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `logo_light` int(11) unsigned DEFAULT NULL,
  `logo_dark` int(11) unsigned DEFAULT NULL,
  `website` varchar(100) NOT NULL,
  `facebook` varchar(100) NOT NULL,
  `twitter` varchar(100) NOT NULL,
  `code` varchar(50) NOT NULL,
  `count` int(11) unsigned NOT NULL DEFAULT '0',
  `order` tinyint(6) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`partner_id`),
  KEY `image_id` (`logo_light`),
  KEY `logo_dark` (`logo_dark`),
  CONSTRAINT `nf_partners_ibfk_1` FOREIGN KEY (`logo_light`) REFERENCES `nf_file` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  CONSTRAINT `nf_partners_ibfk_2` FOREIGN KEY (`logo_dark`) REFERENCES `nf_file` (`id`) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `nf_partners`
--

INSERT INTO `nf_partners` (`partner_id`, `name`, `logo_light`, `logo_dark`, `website`, `facebook`, `twitter`, `code`, `count`, `order`) VALUES
(1, 'neofrag', 2, 3, 'https://neofr.ag', 'https://www.facebook.com/NeoFrag-CMS-345511868808600/', 'https://twitter.com/NeoFragCMS', '', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `nf_partners_lang`
--

DROP TABLE IF EXISTS `nf_partners_lang`;
CREATE TABLE `nf_partners_lang` (
  `partner_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `lang` varchar(5) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`partner_id`),
  KEY `lang` (`lang`),
  CONSTRAINT `nf_partners_lang_ibfk_1` FOREIGN KEY (`partner_id`) REFERENCES `nf_partners` (`partner_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `nf_partners_lang`
--

INSERT INTO `nf_partners_lang` (`partner_id`, `lang`, `title`, `description`) VALUES
(1, 'fr', 'NeoFrag', 'NeoFrag est un CMS (syst&egrave;me de gestion de contenu) &agrave; la fois puissant, compact et performant, pour cr&eacute;er votre site web orient&eacute; eSport !\r\n\r\n[b]C\'est enti&egrave;rement gratuit et personnalisable ![/b]\r\nPeu importe votre niveau dans le domaine du web, ce projet a pour but de vous proposer une solution cl&eacute;s en main pour cr&eacute;er votre site &agrave; l\'aide d\'interfaces modernes, personnalisables et &eacute;volutives pour correspondre &agrave; un maximum d\'univers.');

-- --------------------------------------------------------

--
-- Table structure for table `nf_recruits`
--

DROP TABLE IF EXISTS `nf_recruits`;
CREATE TABLE `nf_recruits` (
  `recruit_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `introduction` text NOT NULL,
  `description` text NOT NULL,
  `requierments` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(11) unsigned NOT NULL,
  `size` int(11) NOT NULL,
  `role` varchar(60) NOT NULL,
  `icon` varchar(60) NOT NULL,
  `date_end` date DEFAULT NULL,
  `closed` enum('0','1') NOT NULL DEFAULT '0',
  `team_id` int(11) unsigned DEFAULT NULL,
  `image_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`recruit_id`),
  KEY `image_id` (`image_id`),
  KEY `user_id` (`user_id`),
  KEY `team_id` (`team_id`),
  CONSTRAINT `nf_recruits_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `nf_teams` (`team_id`) ON DELETE SET NULL ON UPDATE SET NULL,
  CONSTRAINT `nf_recruits_ibfk_2` FOREIGN KEY (`image_id`) REFERENCES `nf_file` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  CONSTRAINT `nf_recruits_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `nf_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nf_recruits_candidacies`
--

DROP TABLE IF EXISTS `nf_recruits_candidacies`;
CREATE TABLE `nf_recruits_candidacies` (
  `candidacy_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `recruit_id` int(11) unsigned NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(11) unsigned DEFAULT NULL,
  `pseudo` varchar(60) NOT NULL,
  `email` varchar(100) NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `presentation` text NOT NULL,
  `motivations` text NOT NULL,
  `experiences` text NOT NULL,
  `status` enum('1','2','3') NOT NULL DEFAULT '1',
  `reply` text,
  PRIMARY KEY (`candidacy_id`),
  KEY `recruit_id` (`recruit_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `nf_recruits_candidacies_ibfk_1` FOREIGN KEY (`recruit_id`) REFERENCES `nf_recruits` (`recruit_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `nf_recruits_candidacies_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `nf_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nf_recruits_candidacies_votes`
--

DROP TABLE IF EXISTS `nf_recruits_candidacies_votes`;
CREATE TABLE `nf_recruits_candidacies_votes` (
  `vote_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `candidacy_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `vote` enum('0','1') NOT NULL DEFAULT '0',
  `comment` text NOT NULL,
  PRIMARY KEY (`vote_id`),
  KEY `candidacy_id` (`candidacy_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `nf_recruits_candidacies_votes_ibfk_1` FOREIGN KEY (`candidacy_id`) REFERENCES `nf_recruits_candidacies` (`candidacy_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `nf_recruits_candidacies_votes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `nf_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nf_search_keywords`
--

DROP TABLE IF EXISTS `nf_search_keywords`;
CREATE TABLE `nf_search_keywords` (
  `keyword` varchar(100) NOT NULL,
  `count` int(11) unsigned NOT NULL,
  PRIMARY KEY (`keyword`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nf_session`
--

DROP TABLE IF EXISTS `nf_session`;
CREATE TABLE `nf_session` (
  `id` varchar(32) NOT NULL,
  `user_id` int(11) unsigned DEFAULT NULL,
  `remember` enum('0','1') NOT NULL DEFAULT '0',
  `last_activity` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `nf_session_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `nf_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nf_session_history`
--

DROP TABLE IF EXISTS `nf_session_history`;
CREATE TABLE `nf_session_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `ip_address` varchar(39) NOT NULL,
  `host_name` varchar(100) NOT NULL,
  `referer` varchar(100) NOT NULL,
  `user_agent` varchar(250) NOT NULL,
  `auth` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `nf_session_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `nf_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nf_settings`
--

DROP TABLE IF EXISTS `nf_settings`;
CREATE TABLE `nf_settings` (
  `name` varchar(100) NOT NULL,
  `site` varchar(100) NOT NULL DEFAULT '',
  `lang` varchar(5) NOT NULL DEFAULT '',
  `value` text NOT NULL,
  `type` enum('string','bool','int','list','array','float') NOT NULL DEFAULT 'string',
  PRIMARY KEY (`name`,`site`,`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `nf_settings`
--

INSERT INTO `nf_settings` (`name`, `site`, `lang`, `value`, `type`) VALUES
('default_background', '', '', '0', 'int'),
('default_background_attachment', '', '', 'scroll', 'string'),
('default_background_color', '', '', '#141d26', 'string'),
('default_background_position', '', '', 'center top', 'string'),
('default_background_repeat', '', '', 'no-repeat', 'string'),
('events_alert_mp', '', '', '1', 'string'),
('events_per_page', '', '', '10', 'string'),
('forum_messages_per_page', '', '', '15', 'int'),
('forum_topics_per_page', '', '', '20', 'int'),
('news_per_page', '', '', '5', 'int'),
('nf_analytics', '', '', '', 'string'),
('nf_captcha_private_key', '', '', '', 'string'),
('nf_captcha_public_key', '', '', '', 'string'),
('nf_contact', '', '', 'noreply@neofrag.com', 'string'),
('nf_cookie_expire', '', '', '1 hour', 'string'),
('nf_cookie_name', '', '', 'session', 'string'),
('nf_default_page', '', '', 'news', 'string'),
('nf_default_theme', '', '', 'default', 'string'),
('nf_description', '', '', 'ALPHA 0.1.6.1', 'string'),
('nf_email_password', '', '', '', 'string'),
('nf_email_port', '', '', '25', 'int'),
('nf_email_secure', '', '', '', 'string'),
('nf_email_smtp', '', '', '', 'string'),
('nf_email_username', '', '', '', 'string'),
('nf_http_authentication', '', '', '0', 'bool'),
('nf_http_authentication_name', '', '', '', 'string'),
('nf_humans_txt', '', '', '/* TEAM */\n	NeoFrag CMS for gamers\n	Contact: contact [at] neofrag.fr\n	Twitter: @NeoFragCMS\n	From: France\n\n	Developper: Micha&euml;l BILCOT\n	Contact: michael.bilcot [at] neofrag.fr\n	Twitter: @NeoFragCMS\n	From: Paris, France\n\n	Designer: J&eacute;r&eacute;my VALENTIN\n	Contact: jeremy.valentin [at] neofrag.fr\n	Twitter: @NeoFragCMS\n	From: Caen, France', 'string'),
('nf_maintenance', '', '', '0', 'bool'),
('nf_maintenance_background', '', '', '0', 'int'),
('nf_maintenance_background_color', '', '', '', 'string'),
('nf_maintenance_background_position', '', '', '', 'string'),
('nf_maintenance_background_repeat', '', '', '', 'string'),
('nf_maintenance_content', '', '', '', 'string'),
('nf_maintenance_facebook', '', '', '', 'string'),
('nf_maintenance_google-plus', '', '', '', 'string'),
('nf_maintenance_logo', '', '', '0', 'int'),
('nf_maintenance_opening', '', '', '', 'string'),
('nf_maintenance_steam', '', '', '', 'string'),
('nf_maintenance_text_color', '', '', '', 'string'),
('nf_maintenance_title', '', '', '', 'string'),
('nf_maintenance_twitch', '', '', '', 'string'),
('nf_maintenance_twitter', '', '', '', 'string'),
('nf_monitoring_last_check', '', '', '0', 'int'),
('nf_name', '', '', 'NeoFrag CMS', 'string'),
('nf_registration_charte', '', '', '', 'string'),
('nf_registration_status', '', '', '0', 'string'),
('nf_robots_txt', '', '', 'User-agent: *\r\nDisallow:', 'string'),
('nf_social_behance', '', '', '', 'string'),
('nf_social_deviantart', '', '', '', 'string'),
('nf_social_dribble', '', '', '', 'string'),
('nf_social_facebook', '', '', '', 'string'),
('nf_social_flickr', '', '', '', 'string'),
('nf_social_github', '', '', '', 'string'),
('nf_social_google', '', '', '', 'string'),
('nf_social_instagram', '', '', '', 'string'),
('nf_social_steam', '', '', '', 'string'),
('nf_social_twitch', '', '', '', 'string'),
('nf_social_twitter', '', '', '', 'string'),
('nf_social_youtube', '', '', '', 'string'),
('nf_team_biographie', '', '', '', 'string'),
('nf_team_creation', '', '', '', 'string'),
('nf_team_logo', '', '', '0', 'int'),
('nf_team_name', '', '', '', 'string'),
('nf_team_type', '', '', '', 'string'),
('nf_version_css', '', '', '0', 'int'),
('nf_welcome', '', '', '0', 'bool'),
('nf_welcome_content', '', '', '', 'string'),
('nf_welcome_title', '', '', '', 'string'),
('nf_welcome_user_id', '', '', '0', 'int'),
('partners_logo_display', '', '', 'logo_dark', 'string'),
('recruits_alert', '', '', '1', 'bool'),
('recruits_hide_unavailable', '', '', '1', 'bool'),
('recruits_per_page', '', '', '5', 'int'),
('recruits_send_mail', '', '', '1', 'bool'),
('recruits_send_mp', '', '', '1', 'bool');

-- --------------------------------------------------------

--
-- Table structure for table `nf_statistics`
--

DROP TABLE IF EXISTS `nf_statistics`;
CREATE TABLE `nf_statistics` (
  `name` varchar(100) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `nf_statistics`
--

INSERT INTO `nf_statistics` (`name`, `value`) VALUES
('nf_sessions_max_simultaneous', '0');

-- --------------------------------------------------------

--
-- Table structure for table `nf_talks`
--

DROP TABLE IF EXISTS `nf_talks`;
CREATE TABLE `nf_talks` (
  `talk_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`talk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `nf_talks`
--

INSERT INTO `nf_talks` (`talk_id`, `name`) VALUES
(1, 'admin'),
(2, 'public');

-- --------------------------------------------------------

--
-- Table structure for table `nf_talks_messages`
--

DROP TABLE IF EXISTS `nf_talks_messages`;
CREATE TABLE `nf_talks_messages` (
  `message_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `talk_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `message` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`message_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `nf_talks_messages`
--

INSERT INTO `nf_talks_messages` (`message_id`, `talk_id`, `user_id`, `message`, `date`) VALUES
(1, 2, 1, 'Bienvenue sur votre nouveau site !', CURRENT_TIMESTAMP);

-- --------------------------------------------------------

--
-- Table structure for table `nf_teams`
--

DROP TABLE IF EXISTS `nf_teams`;
CREATE TABLE `nf_teams` (
  `team_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `game_id` int(11) unsigned NOT NULL,
  `image_id` int(11) unsigned DEFAULT NULL,
  `icon_id` int(11) unsigned DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `order` smallint(6) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`team_id`),
  KEY `activity_id` (`game_id`),
  KEY `image_id` (`image_id`),
  KEY `icon_id` (`icon_id`),
  CONSTRAINT `nf_teams_ibfk_1` FOREIGN KEY (`image_id`) REFERENCES `nf_file` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `nf_teams_ibfk_2` FOREIGN KEY (`icon_id`) REFERENCES `nf_file` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `nf_teams_ibfk_3` FOREIGN KEY (`game_id`) REFERENCES `nf_games` (`game_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nf_teams_lang`
--

DROP TABLE IF EXISTS `nf_teams_lang`;
CREATE TABLE `nf_teams_lang` (
  `team_id` int(11) unsigned NOT NULL,
  `lang` varchar(5) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`team_id`,`lang`),
  KEY `lang` (`lang`),
  CONSTRAINT `nf_teams_lang_ibfk_2` FOREIGN KEY (`team_id`) REFERENCES `nf_teams` (`team_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nf_teams_roles`
--

DROP TABLE IF EXISTS `nf_teams_roles`;
CREATE TABLE `nf_teams_roles` (
  `role_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `order` smallint(6) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nf_teams_users`
--

DROP TABLE IF EXISTS `nf_teams_users`;
CREATE TABLE `nf_teams_users` (
  `team_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `role_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`team_id`,`user_id`),
  KEY `user_id` (`user_id`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `nf_teams_users_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `nf_teams` (`team_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `nf_teams_users_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `nf_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `nf_teams_users_ibfk_3` FOREIGN KEY (`role_id`) REFERENCES `nf_teams_roles` (`role_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nf_tracking`
--

DROP TABLE IF EXISTS `nf_tracking`;
CREATE TABLE `nf_tracking` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `model` varchar(100) NOT NULL,
  `model_id` int(10) unsigned DEFAULT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`,`model`,`model_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nf_user`
--

DROP TABLE IF EXISTS `nf_user`;
CREATE TABLE `nf_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(34) NOT NULL,
  `salt` varchar(32) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `registration_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_activity_date` timestamp NULL DEFAULT NULL,
  `admin` enum('0','1') NOT NULL DEFAULT '0',
  `language` varchar(5) DEFAULT NULL,
  `data` text NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `language` (`language`),
  KEY `deleted` (`deleted`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `nf_user`
--

INSERT INTO `nf_user` (`id`, `username`, `password`, `salt`, `email`, `registration_date`, `last_activity_date`, `admin`, `language`, `data`, `deleted`) VALUES
(1, 'admin', '$H$92EwygSmbdXunbIvoo/V91MWcnHqzX/', '', 'noreply@neofrag.com', CURRENT_TIMESTAMP, NULL, '1', NULL, '', '0');

-- --------------------------------------------------------

--
-- Table structure for table `nf_user_auth`
--

DROP TABLE IF EXISTS `nf_user_auth`;
CREATE TABLE `nf_user_auth` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `authenticator_id` int(11) unsigned NOT NULL,
  `key` varchar(100) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `avatar` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`,`authenticator_id`,`key`),
  KEY `authenticator_id` (`authenticator_id`),
  CONSTRAINT `nf_user_auth_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `nf_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `nf_user_auth_ibfk_2` FOREIGN KEY (`authenticator_id`) REFERENCES `nf_addon` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nf_user_profile`
--

DROP TABLE IF EXISTS `nf_user_profile`;
CREATE TABLE `nf_user_profile` (
  `user_id` int(11) unsigned NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `avatar` int(11) unsigned DEFAULT NULL,
  `signature` text NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `sex` enum('male','female') DEFAULT NULL,
  `location` varchar(100) NOT NULL,
  `quote` varchar(100) NOT NULL,
  `website` varchar(100) NOT NULL,
  PRIMARY KEY (`user_id`),
  KEY `avatar` (`avatar`),
  CONSTRAINT `nf_user_profile_ibfk_2` FOREIGN KEY (`avatar`) REFERENCES `nf_file` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `nf_user_profile_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `nf_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nf_user_token`
--

DROP TABLE IF EXISTS `nf_user_token`;
CREATE TABLE `nf_user_token` (
  `id` varchar(32) NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `nf_user_token_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `nf_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nf_users_groups`
--

DROP TABLE IF EXISTS `nf_users_groups`;
CREATE TABLE `nf_users_groups` (
  `user_id` int(11) unsigned NOT NULL,
  `group_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`group_id`),
  KEY `group_id` (`group_id`),
  CONSTRAINT `nf_users_groups_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `nf_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `nf_users_groups_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `nf_groups` (`group_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nf_users_messages`
--

DROP TABLE IF EXISTS `nf_users_messages`;
CREATE TABLE `nf_users_messages` (
  `message_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `reply_id` int(11) unsigned DEFAULT NULL,
  `title` varchar(100) NOT NULL,
  `last_reply_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`message_id`),
  KEY `reply_id` (`reply_id`),
  KEY `last_reply_id` (`last_reply_id`),
  CONSTRAINT `nf_users_messages_ibfk_1` FOREIGN KEY (`reply_id`) REFERENCES `nf_users_messages_replies` (`reply_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `nf_users_messages_ibfk_2` FOREIGN KEY (`last_reply_id`) REFERENCES `nf_users_messages_replies` (`reply_id`) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nf_users_messages_recipients`
--

DROP TABLE IF EXISTS `nf_users_messages_recipients`;
CREATE TABLE `nf_users_messages_recipients` (
  `user_id` int(11) unsigned NOT NULL,
  `message_id` int(11) unsigned NOT NULL,
  `date` timestamp NULL DEFAULT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`,`message_id`),
  KEY `message_id` (`message_id`),
  CONSTRAINT `nf_users_messages_recipients_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `nf_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `nf_users_messages_recipients_ibfk_2` FOREIGN KEY (`message_id`) REFERENCES `nf_users_messages` (`message_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nf_users_messages_replies`
--

DROP TABLE IF EXISTS `nf_users_messages_replies`;
CREATE TABLE `nf_users_messages_replies` (
  `reply_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `message_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `message` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`reply_id`),
  KEY `message_id` (`message_id`,`user_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `nf_users_messages_replies_ibfk_1` FOREIGN KEY (`message_id`) REFERENCES `nf_users_messages` (`message_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `nf_users_messages_replies_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `nf_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nf_widgets`
--

DROP TABLE IF EXISTS `nf_widgets`;
CREATE TABLE `nf_widgets` (
  `widget_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `widget` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `settings` text,
  PRIMARY KEY (`widget_id`),
  KEY `widget_name` (`widget`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `nf_widgets`
--

INSERT INTO `nf_widgets` (`widget_id`, `widget`, `type`, `title`, `settings`) VALUES
(1, 'breadcrumb', 'index', NULL, NULL),
(2, 'search', 'index', NULL, NULL),
(3, 'module', 'index', NULL, NULL),
(4, 'navigation', 'vertical', NULL, 'a:1:{s:5:\"links\";a:6:{i:0;a:2:{s:5:\"title\";s:17:\"Actualit&eacute;s\";s:3:\"url\";s:4:\"news\";}i:1;a:2:{s:5:\"title\";s:7:\"Membres\";s:3:\"url\";s:7:\"members\";}i:2;a:2:{s:5:\"title\";s:11:\"Recrutement\";s:3:\"url\";s:8:\"recruits\";}i:3;a:2:{s:5:\"title\";s:6:\"Photos\";s:3:\"url\";s:7:\"gallery\";}i:4;a:2:{s:5:\"title\";s:10:\"Rechercher\";s:3:\"url\";s:6:\"search\";}i:5;a:2:{s:5:\"title\";s:7:\"Contact\";s:3:\"url\";s:7:\"contact\";}}}'),
(5, 'partners', 'column', NULL, 'a:1:{s:13:\"display_style\";s:5:\"light\";}'),
(6, 'user', 'index', NULL, NULL),
(7, 'news', 'categories', NULL, NULL),
(8, 'talks', 'index', NULL, 'a:1:{s:7:\"talk_id\";i:2;}'),
(9, 'members', 'online', NULL, NULL),
(10, 'forum', 'topics', NULL, NULL),
(11, 'news', 'index', NULL, NULL),
(12, 'members', 'index', NULL, NULL),
(13, 'header', 'index', NULL, 'a:5:{s:5:\"align\";s:11:\"text-center\";s:5:\"title\";s:0:\"\";s:11:\"description\";s:0:\"\";s:11:\"color-title\";s:0:\"\";s:17:\"color-description\";s:7:\"#DC351E\";}'),
(14, 'navigation', 'index', NULL, 'a:1:{s:5:\"links\";a:6:{i:0;a:2:{s:5:\"title\";s:7:\"Accueil\";s:3:\"url\";s:0:\"\";}i:1;a:2:{s:5:\"title\";s:5:\"Forum\";s:3:\"url\";s:5:\"forum\";}i:2;a:2:{s:5:\"title\";s:14:\"&Eacute;quipes\";s:3:\"url\";s:5:\"teams\";}i:3;a:2:{s:5:\"title\";s:6:\"Matchs\";s:3:\"url\";s:14:\"events/matches\";}i:4;a:2:{s:5:\"title\";s:11:\"Partenaires\";s:3:\"url\";s:8:\"partners\";}i:5;a:2:{s:5:\"title\";s:15:\"Palmar&egrave;s\";s:3:\"url\";s:6:\"awards\";}}}'),
(15, 'user', 'index_mini', NULL, NULL),
(16, 'navigation', 'index', NULL, 'a:1:{s:5:\"links\";a:4:{i:0;a:2:{s:5:\"title\";s:8:\"Facebook\";s:3:\"url\";s:1:\"#\";}i:1;a:2:{s:5:\"title\";s:7:\"Twitter\";s:3:\"url\";s:1:\"#\";}i:2;a:2:{s:5:\"title\";s:6:\"Origin\";s:3:\"url\";s:1:\"#\";}i:3;a:2:{s:5:\"title\";s:5:\"Steam\";s:3:\"url\";s:1:\"#\";}}}'),
(17, 'members', 'online_mini', NULL, NULL),
(18, 'html', 'index', NULL, 'a:1:{s:7:\"content\";s:98:\"[center]Propuls&eacute; par [url=https://neofr.ag]NeoFrag CMS[/url] version Alpha 0.1.6.1[/center]\";}'),
(19, 'header', 'index', NULL, 'a:5:{s:5:\"align\";s:11:\"text-center\";s:5:\"title\";s:0:\"\";s:11:\"description\";s:0:\"\";s:11:\"color-title\";s:0:\"\";s:17:\"color-description\";s:7:\"#DC351E\";}'),
(20, 'navigation', 'index', NULL, 'a:1:{s:5:\"links\";a:6:{i:0;a:2:{s:5:\"title\";s:7:\"Accueil\";s:3:\"url\";s:0:\"\";}i:1;a:2:{s:5:\"title\";s:5:\"Forum\";s:3:\"url\";s:5:\"forum\";}i:2;a:2:{s:5:\"title\";s:14:\"&Eacute;quipes\";s:3:\"url\";s:5:\"teams\";}i:3;a:2:{s:5:\"title\";s:6:\"Matchs\";s:3:\"url\";s:14:\"events/matches\";}i:4;a:2:{s:5:\"title\";s:11:\"Partenaires\";s:3:\"url\";s:8:\"partners\";}i:5;a:2:{s:5:\"title\";s:15:\"Palmar&egrave;s\";s:3:\"url\";s:6:\"awards\";}}}'),
(21, 'user', 'index_mini', NULL, NULL),
(22, 'slider', 'index', NULL, NULL),
(23, 'breadcrumb', 'index', NULL, NULL),
(24, 'search', 'index', NULL, NULL),
(25, 'module', 'index', NULL, NULL),
(26, 'breadcrumb', 'index', NULL, NULL),
(27, 'search', 'index', NULL, NULL),
(28, 'module', 'index', NULL, NULL),
(29, 'breadcrumb', 'index', NULL, NULL),
(30, 'search', 'index', NULL, NULL),
(31, 'module', 'index', NULL, NULL),
(32, 'breadcrumb', 'index', NULL, NULL),
(33, 'module', 'index', NULL, NULL),
(34, 'forum', 'statistics', NULL, NULL),
(35, 'forum', 'activity', NULL, NULL);

-- --------------------------------------------------------

SET FOREIGN_KEY_CHECKS = 1;
