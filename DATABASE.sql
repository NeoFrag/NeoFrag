-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Sam 30 Mai 2015 à 01:40
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `neofrag`
--

-- --------------------------------------------------------

--
-- Structure de la table `nf_access`
--

DROP TABLE IF EXISTS `nf_access`;
CREATE TABLE IF NOT EXISTS `nf_access` (
  `access_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id` int(11) UNSIGNED NOT NULL,
  `module` varchar(100) NOT NULL,
  `action` varchar(100) NOT NULL,
  PRIMARY KEY (`access_id`),
  UNIQUE KEY `module_id` (`id`,`module`,`action`),
  KEY `module` (`module`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Contenu de la table `nf_access`
--

INSERT INTO `nf_access` VALUES(1, 2, 'talks', 'read');
INSERT INTO `nf_access` VALUES(2, 2, 'talks', 'write');
INSERT INTO `nf_access` VALUES(3, 2, 'talks', 'delete');
INSERT INTO `nf_access` VALUES(4, 1, 'forum', 'category_read');
INSERT INTO `nf_access` VALUES(5, 1, 'forum', 'category_write');
INSERT INTO `nf_access` VALUES(6, 1, 'forum', 'category_modify');
INSERT INTO `nf_access` VALUES(7, 1, 'forum', 'category_delete');
INSERT INTO `nf_access` VALUES(8, 1, 'forum', 'category_announce');
INSERT INTO `nf_access` VALUES(9, 1, 'forum', 'category_lock');
INSERT INTO `nf_access` VALUES(10, 1, 'forum', 'category_move');

-- --------------------------------------------------------

--
-- Structure de la table `nf_access_details`
--

DROP TABLE IF EXISTS `nf_access_details`;
CREATE TABLE IF NOT EXISTS `nf_access_details` (
  `access_id` int(11) UNSIGNED NOT NULL,
  `entity` varchar(100) NOT NULL,
  `type` enum('group','user') NOT NULL DEFAULT 'group',
  `authorized` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`access_id`,`entity`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `nf_access_details`
--

INSERT INTO `nf_access_details` VALUES(2, 'visitors', 'group', '0');
INSERT INTO `nf_access_details` VALUES(3, 'admins', 'group', '1');
INSERT INTO `nf_access_details` VALUES(5, 'visitors', 'group', '0');
INSERT INTO `nf_access_details` VALUES(6, 'admins', 'group', '1');
INSERT INTO `nf_access_details` VALUES(7, 'admins', 'group', '1');
INSERT INTO `nf_access_details` VALUES(8, 'admins', 'group', '1');
INSERT INTO `nf_access_details` VALUES(9, 'admins', 'group', '1');
INSERT INTO `nf_access_details` VALUES(10, 'admins', 'group', '1');

-- --------------------------------------------------------

--
-- Structure de la table `nf_awards`
--

DROP TABLE IF EXISTS `nf_awards`;
CREATE TABLE IF NOT EXISTS `nf_awards` (
  `award_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `team_id` int(11) UNSIGNED NULL DEFAULT NULL,
  `game_id` int(11) UNSIGNED NOT NULL,
  `image_id` int(11) UNSIGNED NULL DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `location` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `description` text NOT NULL,
  `platform` varchar(100) NOT NULL,
  `ranking` int(11) UNSIGNED NOT NULL,
  `participants` int(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`award_id`),
  KEY `image_id` (`image_id`),
  KEY `game_id` (`game_id`),
  KEY `team_id` (`team_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nf_comments`
--

DROP TABLE IF EXISTS `nf_comments`;
CREATE TABLE IF NOT EXISTS `nf_comments` (
  `comment_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) UNSIGNED NULL DEFAULT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `module_id` int(11) UNSIGNED NOT NULL,
  `module` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`comment_id`),
  KEY `parent_id` (`parent_id`),
  KEY `user_id` (`user_id`),
  KEY `module` (`module`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nf_crawlers`
--

DROP TABLE IF EXISTS `nf_crawlers`;
CREATE TABLE IF NOT EXISTS `nf_crawlers` (
  `name` varchar(100) NOT NULL,
  `path` varchar(100) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nf_dispositions`
--

DROP TABLE IF EXISTS `nf_dispositions`;
CREATE TABLE IF NOT EXISTS `nf_dispositions` (
  `disposition_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `theme` varchar(100) NOT NULL,
  `page` varchar(100) NOT NULL,
  `zone` int(11) UNSIGNED NOT NULL,
  `disposition` text NOT NULL,
  PRIMARY KEY (`disposition_id`),
  UNIQUE KEY `theme` (`theme`,`page`,`zone`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Contenu de la table `nf_dispositions`
--

INSERT INTO `nf_dispositions` VALUES(1, 'default', '*', 0, 'a:2:{i:0;O:3:"Row":3:{s:9:"\0*\0_style";s:9:"row-white";s:6:"\0*\0_id";N;s:12:"\0*\0_children";a:2:{i:0;O:3:"Col":3:{s:8:"\0*\0_size";N;s:6:"\0*\0_id";N;s:12:"\0*\0_children";a:1:{i:0;O:12:"Panel_widget":8:{s:6:"\0*\0_id";N;s:10:"\0*\0_widget";i:2;s:11:"\0*\0_heading";a:0:{}s:10:"\0*\0_footer";a:0:{}s:8:"\0*\0_body";N;s:13:"\0*\0_body_tags";N;s:9:"\0*\0_color";N;s:8:"\0*\0_size";s:8:"col-md-8";}}}i:1;O:3:"Col":3:{s:8:"\0*\0_size";N;s:6:"\0*\0_id";N;s:12:"\0*\0_children";a:1:{i:0;O:12:"Panel_widget":8:{s:6:"\0*\0_id";N;s:10:"\0*\0_widget";i:3;s:11:"\0*\0_heading";a:0:{}s:10:"\0*\0_footer";a:0:{}s:8:"\0*\0_body";N;s:13:"\0*\0_body_tags";N;s:9:"\0*\0_color";N;s:8:"\0*\0_size";s:8:"col-md-4";}}}}}i:1;O:3:"Row":3:{s:9:"\0*\0_style";s:9:"row-light";s:6:"\0*\0_id";N;s:12:"\0*\0_children";a:2:{i:0;O:3:"Col":3:{s:8:"\0*\0_size";N;s:6:"\0*\0_id";N;s:12:"\0*\0_children";a:1:{i:0;O:12:"Panel_widget":8:{s:6:"\0*\0_id";N;s:10:"\0*\0_widget";i:4;s:11:"\0*\0_heading";a:0:{}s:10:"\0*\0_footer";a:0:{}s:8:"\0*\0_body";N;s:13:"\0*\0_body_tags";N;s:9:"\0*\0_color";N;s:8:"\0*\0_size";s:8:"col-md-8";}}}i:1;O:3:"Col":3:{s:8:"\0*\0_size";s:8:"col-md-4";s:6:"\0*\0_id";N;s:12:"\0*\0_children";a:6:{i:0;O:12:"Panel_widget":8:{s:6:"\0*\0_id";N;s:10:"\0*\0_widget";i:5;s:11:"\0*\0_heading";a:0:{}s:10:"\0*\0_footer";a:0:{}s:8:"\0*\0_body";N;s:13:"\0*\0_body_tags";N;s:9:"\0*\0_color";N;s:8:"\0*\0_size";N;}i:1;O:12:"Panel_widget":8:{s:6:"\0*\0_id";N;s:10:"\0*\0_widget";i:6;s:11:"\0*\0_heading";a:0:{}s:10:"\0*\0_footer";a:0:{}s:8:"\0*\0_body";N;s:13:"\0*\0_body_tags";N;s:9:"\0*\0_color";s:4:"dark";s:8:"\0*\0_size";N;}i:2;O:12:"Panel_widget":8:{s:6:"\0*\0_id";N;s:10:"\0*\0_widget";i:7;s:11:"\0*\0_heading";a:0:{}s:10:"\0*\0_footer";a:0:{}s:8:"\0*\0_body";N;s:13:"\0*\0_body_tags";N;s:9:"\0*\0_color";s:4:"dark";s:8:"\0*\0_size";N;}i:3;O:12:"Panel_widget":8:{s:6:"\0*\0_id";N;s:10:"\0*\0_widget";i:8;s:11:"\0*\0_heading";a:0:{}s:10:"\0*\0_footer";a:0:{}s:8:"\0*\0_body";N;s:13:"\0*\0_body_tags";N;s:9:"\0*\0_color";N;s:8:"\0*\0_size";N;}i:4;O:12:"Panel_widget":8:{s:6:"\0*\0_id";N;s:10:"\0*\0_widget";i:9;s:11:"\0*\0_heading";a:0:{}s:10:"\0*\0_footer";a:0:{}s:8:"\0*\0_body";N;s:13:"\0*\0_body_tags";N;s:9:"\0*\0_color";N;s:8:"\0*\0_size";N;}i:5;O:12:"Panel_widget":8:{s:6:"\0*\0_id";N;s:10:"\0*\0_widget";i:10;s:11:"\0*\0_heading";a:0:{}s:10:"\0*\0_footer";a:0:{}s:8:"\0*\0_body";N;s:13:"\0*\0_body_tags";N;s:9:"\0*\0_color";s:3:"red";s:8:"\0*\0_size";N;}}}}}}');
INSERT INTO `nf_dispositions` VALUES(2, 'default', '*', 1, 'a:1:{i:0;O:3:"Row":3:{s:9:"\0*\0_style";s:11:"row-default";s:6:"\0*\0_id";N;s:12:"\0*\0_children";a:3:{i:0;O:3:"Col":3:{s:8:"\0*\0_size";N;s:6:"\0*\0_id";N;s:12:"\0*\0_children";a:1:{i:0;O:12:"Panel_widget":8:{s:6:"\0*\0_id";N;s:10:"\0*\0_widget";i:11;s:11:"\0*\0_heading";a:0:{}s:10:"\0*\0_footer";a:0:{}s:8:"\0*\0_body";N;s:13:"\0*\0_body_tags";N;s:9:"\0*\0_color";N;s:8:"\0*\0_size";s:8:"col-md-4";}}}i:1;O:3:"Col":3:{s:8:"\0*\0_size";N;s:6:"\0*\0_id";N;s:12:"\0*\0_children";a:1:{i:0;O:12:"Panel_widget":8:{s:6:"\0*\0_id";N;s:10:"\0*\0_widget";i:12;s:11:"\0*\0_heading";a:0:{}s:10:"\0*\0_footer";a:0:{}s:8:"\0*\0_body";N;s:13:"\0*\0_body_tags";N;s:9:"\0*\0_color";s:4:"dark";s:8:"\0*\0_size";s:8:"col-md-4";}}}i:2;O:3:"Col":3:{s:8:"\0*\0_size";N;s:6:"\0*\0_id";N;s:12:"\0*\0_children";a:1:{i:0;O:12:"Panel_widget":8:{s:6:"\0*\0_id";N;s:10:"\0*\0_widget";i:13;s:11:"\0*\0_heading";a:0:{}s:10:"\0*\0_footer";a:0:{}s:8:"\0*\0_body";N;s:13:"\0*\0_body_tags";N;s:9:"\0*\0_color";s:3:"red";s:8:"\0*\0_size";s:8:"col-md-4";}}}}}}');
INSERT INTO `nf_dispositions` VALUES(3, 'default', '*', 2, 'a:0:{}');
INSERT INTO `nf_dispositions` VALUES(4, 'default', '*', 3, 'a:2:{i:0;O:3:"Row":3:{s:9:"\0*\0_style";s:11:"row-default";s:6:"\0*\0_id";N;s:12:"\0*\0_children";a:1:{i:0;O:3:"Col":3:{s:8:"\0*\0_size";N;s:6:"\0*\0_id";N;s:12:"\0*\0_children";a:1:{i:0;O:12:"Panel_widget":8:{s:6:"\0*\0_id";N;s:10:"\0*\0_widget";i:14;s:11:"\0*\0_heading";a:0:{}s:10:"\0*\0_footer";a:0:{}s:8:"\0*\0_body";N;s:13:"\0*\0_body_tags";N;s:9:"\0*\0_color";N;s:8:"\0*\0_size";N;}}}}}i:1;O:3:"Row":3:{s:9:"\0*\0_style";s:9:"row-black";s:6:"\0*\0_id";N;s:12:"\0*\0_children";a:2:{i:0;O:3:"Col":3:{s:8:"\0*\0_size";N;s:6:"\0*\0_id";N;s:12:"\0*\0_children";a:1:{i:0;O:12:"Panel_widget":8:{s:6:"\0*\0_id";N;s:10:"\0*\0_widget";i:15;s:11:"\0*\0_heading";a:0:{}s:10:"\0*\0_footer";a:0:{}s:8:"\0*\0_body";N;s:13:"\0*\0_body_tags";N;s:9:"\0*\0_color";N;s:8:"\0*\0_size";s:8:"col-md-7";}}}i:1;O:3:"Col":3:{s:8:"\0*\0_size";N;s:6:"\0*\0_id";N;s:12:"\0*\0_children";a:1:{i:0;O:12:"Panel_widget":8:{s:6:"\0*\0_id";N;s:10:"\0*\0_widget";i:16;s:11:"\0*\0_heading";a:0:{}s:10:"\0*\0_footer";a:0:{}s:8:"\0*\0_body";N;s:13:"\0*\0_body_tags";N;s:9:"\0*\0_color";N;s:8:"\0*\0_size";s:8:"col-md-5";}}}}}}');
INSERT INTO `nf_dispositions` VALUES(5, 'default', '*', 4, 'a:1:{i:0;O:3:"Row":3:{s:9:"\0*\0_style";s:11:"row-default";s:6:"\0*\0_id";N;s:12:"\0*\0_children";a:2:{i:0;O:3:"Col":3:{s:8:"\0*\0_size";N;s:6:"\0*\0_id";N;s:12:"\0*\0_children";a:1:{i:0;O:12:"Panel_widget":8:{s:6:"\0*\0_id";N;s:10:"\0*\0_widget";i:17;s:11:"\0*\0_heading";a:0:{}s:10:"\0*\0_footer";a:0:{}s:8:"\0*\0_body";N;s:13:"\0*\0_body_tags";N;s:9:"\0*\0_color";N;s:8:"\0*\0_size";s:8:"col-md-8";}}}i:1;O:3:"Col":3:{s:8:"\0*\0_size";N;s:6:"\0*\0_id";N;s:12:"\0*\0_children";a:1:{i:0;O:12:"Panel_widget":8:{s:6:"\0*\0_id";N;s:10:"\0*\0_widget";i:18;s:11:"\0*\0_heading";a:0:{}s:10:"\0*\0_footer";a:0:{}s:8:"\0*\0_body";N;s:13:"\0*\0_body_tags";N;s:9:"\0*\0_color";N;s:8:"\0*\0_size";s:8:"col-md-4";}}}}}}');
INSERT INTO `nf_dispositions` VALUES(6, 'default', '*', 5, 'a:1:{i:0;O:3:"Row":3:{s:9:"\0*\0_style";s:11:"row-default";s:6:"\0*\0_id";N;s:12:"\0*\0_children";a:1:{i:0;O:3:"Col":3:{s:8:"\0*\0_size";N;s:6:"\0*\0_id";N;s:12:"\0*\0_children";a:1:{i:0;O:12:"Panel_widget":8:{s:6:"\0*\0_id";N;s:10:"\0*\0_widget";i:19;s:11:"\0*\0_heading";a:0:{}s:10:"\0*\0_footer";a:0:{}s:8:"\0*\0_body";N;s:13:"\0*\0_body_tags";N;s:9:"\0*\0_color";s:4:"dark";s:8:"\0*\0_size";N;}}}}}}');
INSERT INTO `nf_dispositions` VALUES(7, 'default', '/', 3, 'a:3:{i:0;O:3:"Row":3:{s:9:"\0*\0_style";s:11:"row-default";s:6:"\0*\0_id";N;s:12:"\0*\0_children";a:1:{i:0;O:3:"Col":3:{s:8:"\0*\0_size";N;s:6:"\0*\0_id";N;s:12:"\0*\0_children";a:1:{i:0;O:12:"Panel_widget":8:{s:6:"\0*\0_id";N;s:10:"\0*\0_widget";i:20;s:11:"\0*\0_heading";a:0:{}s:10:"\0*\0_footer";a:0:{}s:8:"\0*\0_body";N;s:13:"\0*\0_body_tags";N;s:9:"\0*\0_color";N;s:8:"\0*\0_size";N;}}}}}i:1;O:3:"Row":3:{s:9:"\0*\0_style";s:9:"row-black";s:6:"\0*\0_id";N;s:12:"\0*\0_children";a:2:{i:0;O:3:"Col":3:{s:8:"\0*\0_size";N;s:6:"\0*\0_id";N;s:12:"\0*\0_children";a:1:{i:0;O:12:"Panel_widget":8:{s:6:"\0*\0_id";N;s:10:"\0*\0_widget";i:21;s:11:"\0*\0_heading";a:0:{}s:10:"\0*\0_footer";a:0:{}s:8:"\0*\0_body";N;s:13:"\0*\0_body_tags";N;s:9:"\0*\0_color";N;s:8:"\0*\0_size";s:8:"col-md-7";}}}i:1;O:3:"Col":3:{s:8:"\0*\0_size";N;s:6:"\0*\0_id";N;s:12:"\0*\0_children";a:1:{i:0;O:12:"Panel_widget":8:{s:6:"\0*\0_id";N;s:10:"\0*\0_widget";i:22;s:11:"\0*\0_heading";a:0:{}s:10:"\0*\0_footer";a:0:{}s:8:"\0*\0_body";N;s:13:"\0*\0_body_tags";N;s:9:"\0*\0_color";N;s:8:"\0*\0_size";s:8:"col-md-5";}}}}}i:2;O:3:"Row":3:{s:9:"\0*\0_style";s:11:"row-default";s:6:"\0*\0_id";N;s:12:"\0*\0_children";a:1:{i:0;O:3:"Col":3:{s:8:"\0*\0_size";N;s:6:"\0*\0_id";N;s:12:"\0*\0_children";a:1:{i:0;O:12:"Panel_widget":8:{s:6:"\0*\0_id";N;s:10:"\0*\0_widget";i:23;s:11:"\0*\0_heading";a:0:{}s:10:"\0*\0_footer";a:0:{}s:8:"\0*\0_body";N;s:13:"\0*\0_body_tags";N;s:9:"\0*\0_color";N;s:8:"\0*\0_size";N;}}}}}}');
INSERT INTO `nf_dispositions` VALUES(8, 'default', 'forum/*', 0, 'a:2:{i:0;O:3:"Row":3:{s:9:"\0*\0_style";s:9:"row-white";s:6:"\0*\0_id";N;s:12:"\0*\0_children";a:2:{i:0;O:3:"Col":3:{s:8:"\0*\0_size";N;s:6:"\0*\0_id";N;s:12:"\0*\0_children";a:1:{i:0;O:12:"Panel_widget":8:{s:6:"\0*\0_id";N;s:10:"\0*\0_widget";i:24;s:11:"\0*\0_heading";a:0:{}s:10:"\0*\0_footer";a:0:{}s:8:"\0*\0_body";N;s:13:"\0*\0_body_tags";N;s:9:"\0*\0_color";N;s:8:"\0*\0_size";s:8:"col-md-8";}}}i:1;O:3:"Col":3:{s:8:"\0*\0_size";N;s:6:"\0*\0_id";N;s:12:"\0*\0_children";a:1:{i:0;O:12:"Panel_widget":8:{s:6:"\0*\0_id";N;s:10:"\0*\0_widget";i:25;s:11:"\0*\0_heading";a:0:{}s:10:"\0*\0_footer";a:0:{}s:8:"\0*\0_body";N;s:13:"\0*\0_body_tags";N;s:9:"\0*\0_color";N;s:8:"\0*\0_size";s:8:"col-md-4";}}}}}i:1;O:3:"Row":3:{s:9:"\0*\0_style";s:9:"row-light";s:6:"\0*\0_id";N;s:12:"\0*\0_children";a:1:{i:0;O:3:"Col":3:{s:8:"\0*\0_size";N;s:6:"\0*\0_id";N;s:12:"\0*\0_children";a:1:{i:0;O:12:"Panel_widget":8:{s:6:"\0*\0_id";N;s:10:"\0*\0_widget";i:26;s:11:"\0*\0_heading";a:0:{}s:10:"\0*\0_footer";a:0:{}s:8:"\0*\0_body";N;s:13:"\0*\0_body_tags";N;s:9:"\0*\0_color";N;s:8:"\0*\0_size";N;}}}}}}');
INSERT INTO `nf_dispositions` VALUES(9, 'default', 'forum/*', 2, 'a:1:{i:0;O:3:"Row":3:{s:9:"\0*\0_style";s:9:"row-light";s:6:"\0*\0_id";N;s:12:"\0*\0_children";a:2:{i:0;O:3:"Col":3:{s:8:"\0*\0_size";N;s:6:"\0*\0_id";N;s:12:"\0*\0_children";a:1:{i:0;O:12:"Panel_widget":8:{s:6:"\0*\0_id";N;s:10:"\0*\0_widget";i:35;s:11:"\0*\0_heading";a:0:{}s:10:"\0*\0_footer";a:0:{}s:8:"\0*\0_body";N;s:13:"\0*\0_body_tags";N;s:9:"\0*\0_color";s:3:"red";s:8:"\0*\0_size";s:8:"col-md-4";}}}i:1;O:3:"Col":3:{s:8:"\0*\0_size";N;s:6:"\0*\0_id";N;s:12:"\0*\0_children";a:1:{i:0;O:12:"Panel_widget":8:{s:6:"\0*\0_id";N;s:10:"\0*\0_widget";i:36;s:11:"\0*\0_heading";a:0:{}s:10:"\0*\0_footer";a:0:{}s:8:"\0*\0_body";N;s:13:"\0*\0_body_tags";N;s:9:"\0*\0_color";s:4:"dark";s:8:"\0*\0_size";s:8:"col-md-8";}}}}}}');
INSERT INTO `nf_dispositions` VALUES(10, 'default', 'news/_news/*', 0, 'a:2:{i:0;O:3:"Row":3:{s:9:"\0*\0_style";s:9:"row-white";s:6:"\0*\0_id";N;s:12:"\0*\0_children";a:2:{i:0;O:3:"Col":3:{s:8:"\0*\0_size";N;s:6:"\0*\0_id";N;s:12:"\0*\0_children";a:1:{i:0;O:12:"Panel_widget":8:{s:6:"\0*\0_id";N;s:10:"\0*\0_widget";i:27;s:11:"\0*\0_heading";a:0:{}s:10:"\0*\0_footer";a:0:{}s:8:"\0*\0_body";N;s:13:"\0*\0_body_tags";N;s:9:"\0*\0_color";N;s:8:"\0*\0_size";s:8:"col-md-8";}}}i:1;O:3:"Col":3:{s:8:"\0*\0_size";N;s:6:"\0*\0_id";N;s:12:"\0*\0_children";a:1:{i:0;O:12:"Panel_widget":8:{s:6:"\0*\0_id";N;s:10:"\0*\0_widget";i:28;s:11:"\0*\0_heading";a:0:{}s:10:"\0*\0_footer";a:0:{}s:8:"\0*\0_body";N;s:13:"\0*\0_body_tags";N;s:9:"\0*\0_color";N;s:8:"\0*\0_size";s:8:"col-md-4";}}}}}i:1;O:3:"Row":3:{s:9:"\0*\0_style";s:9:"row-light";s:6:"\0*\0_id";N;s:12:"\0*\0_children";a:1:{i:0;O:3:"Col":3:{s:8:"\0*\0_size";N;s:6:"\0*\0_id";N;s:12:"\0*\0_children";a:1:{i:0;O:12:"Panel_widget":8:{s:6:"\0*\0_id";N;s:10:"\0*\0_widget";i:29;s:11:"\0*\0_heading";a:0:{}s:10:"\0*\0_footer";a:0:{}s:8:"\0*\0_body";N;s:13:"\0*\0_body_tags";N;s:9:"\0*\0_color";N;s:8:"\0*\0_size";N;}}}}}}');
INSERT INTO `nf_dispositions` VALUES(11, 'default', 'user/*', 0, 'a:2:{i:0;O:3:"Row":3:{s:9:"\0*\0_style";s:9:"row-white";s:6:"\0*\0_id";N;s:12:"\0*\0_children";a:2:{i:0;O:3:"Col":3:{s:8:"\0*\0_size";N;s:6:"\0*\0_id";N;s:12:"\0*\0_children";a:1:{i:0;O:12:"Panel_widget":8:{s:6:"\0*\0_id";N;s:10:"\0*\0_widget";i:30;s:11:"\0*\0_heading";a:0:{}s:10:"\0*\0_footer";a:0:{}s:8:"\0*\0_body";N;s:13:"\0*\0_body_tags";N;s:9:"\0*\0_color";N;s:8:"\0*\0_size";s:8:"col-md-8";}}}i:1;O:3:"Col":3:{s:8:"\0*\0_size";N;s:6:"\0*\0_id";N;s:12:"\0*\0_children";a:1:{i:0;O:12:"Panel_widget":8:{s:6:"\0*\0_id";N;s:10:"\0*\0_widget";i:31;s:11:"\0*\0_heading";a:0:{}s:10:"\0*\0_footer";a:0:{}s:8:"\0*\0_body";N;s:13:"\0*\0_body_tags";N;s:9:"\0*\0_color";N;s:8:"\0*\0_size";s:8:"col-md-4";}}}}}i:1;O:3:"Row":3:{s:9:"\0*\0_style";s:9:"row-light";s:6:"\0*\0_id";N;s:12:"\0*\0_children";a:1:{i:0;O:3:"Col":3:{s:8:"\0*\0_size";N;s:6:"\0*\0_id";N;s:12:"\0*\0_children";a:1:{i:0;O:12:"Panel_widget":8:{s:6:"\0*\0_id";N;s:10:"\0*\0_widget";i:32;s:11:"\0*\0_heading";a:0:{}s:10:"\0*\0_footer";a:0:{}s:8:"\0*\0_body";N;s:13:"\0*\0_body_tags";N;s:9:"\0*\0_color";N;s:8:"\0*\0_size";N;}}}}}}');
INSERT INTO `nf_dispositions` VALUES(12, 'default', 'search/*', 0, 'a:2:{i:0;O:3:"Row":3:{s:9:"\0*\0_style";s:9:"row-white";s:6:"\0*\0_id";N;s:12:"\0*\0_children";a:2:{i:0;O:3:"Col":3:{s:8:"\0*\0_size";N;s:6:"\0*\0_id";N;s:12:"\0*\0_children";a:1:{i:0;O:12:"Panel_widget":8:{s:6:"\0*\0_id";N;s:10:"\0*\0_widget";i:33;s:11:"\0*\0_heading";a:0:{}s:10:"\0*\0_footer";a:0:{}s:8:"\0*\0_body";N;s:13:"\0*\0_body_tags";N;s:9:"\0*\0_color";N;s:8:"\0*\0_size";s:8:"col-md-8";}}}i:1;N;}}i:1;O:3:"Row":3:{s:9:"\0*\0_style";s:9:"row-light";s:6:"\0*\0_id";N;s:12:"\0*\0_children";a:1:{i:0;O:3:"Col":3:{s:8:"\0*\0_size";N;s:6:"\0*\0_id";N;s:12:"\0*\0_children";a:1:{i:0;O:12:"Panel_widget":8:{s:6:"\0*\0_id";N;s:10:"\0*\0_widget";i:34;s:11:"\0*\0_heading";a:0:{}s:10:"\0*\0_footer";a:0:{}s:8:"\0*\0_body";N;s:13:"\0*\0_body_tags";N;s:9:"\0*\0_color";N;s:8:"\0*\0_size";N;}}}}}}');

-- --------------------------------------------------------

--
-- Structure de la table `nf_files`
--

DROP TABLE IF EXISTS `nf_files`;
CREATE TABLE IF NOT EXISTS `nf_files` (
  `file_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NULL DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `path` varchar(100) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`file_id`),
  UNIQUE KEY `path` (`path`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Contenu de la table `nf_files`
--

INSERT INTO `nf_files` VALUES(1, 1, 'Sans-titre-2.jpg', './upload/news/categories/ubfuejdfooirqya0pyltfeklja4ew4sn.jpg', '2015-05-29 22:34:16');
INSERT INTO `nf_files` VALUES(2, 1, 'logo.png', 'upload/partners/zwvmsjijfljaka4rdblgvlype1lnbwaw.png', '2016-05-07 16:51:53');
INSERT INTO `nf_files` VALUES(3, 1, 'logo_black.png', 'upload/partners/y4ofwq2ekppwnfpmnrmnafeivszlg5bd.png', '2016-05-07 16:51:53');

-- --------------------------------------------------------

--
-- Structure de la table `nf_forum`
--

DROP TABLE IF EXISTS `nf_forum`;
CREATE TABLE IF NOT EXISTS `nf_forum` (
  `forum_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) UNSIGNED NOT NULL,
  `is_subforum` enum('0','1') NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL,
  `description` varchar(100) NOT NULL,
  `order` smallint(6) UNSIGNED NOT NULL DEFAULT '0',
  `count_topics` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `count_messages` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `last_message_id` int(11) UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`forum_id`),
  KEY `last_message_id` (`last_message_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Contenu de la table `nf_forum`
--

INSERT INTO `nf_forum` VALUES(1, 1, '0', 'Discussions g&eacute;n&eacute;rales', 'Ceci est votre tout premier forum !', 0, 0, 0, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `nf_forum_categories`
--

DROP TABLE IF EXISTS `nf_forum_categories`;
CREATE TABLE IF NOT EXISTS `nf_forum_categories` (
  `category_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `order` smallint(6) UNSIGNED NOT NULL NULL DEFAULT '0',
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Contenu de la table `nf_forum_categories`
--

INSERT INTO `nf_forum_categories` VALUES(1, 'G&eacute;n&eacute;ral', 0);

-- --------------------------------------------------------

--
-- Structure de la table `nf_forum_messages`
--

DROP TABLE IF EXISTS `nf_forum_messages`;
CREATE TABLE IF NOT EXISTS `nf_forum_messages` (
  `message_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `topic_id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NULL DEFAULT NULL,
  `message` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`message_id`),
  KEY `topic_id` (`topic_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nf_forum_polls`
--

DROP TABLE IF EXISTS `nf_forum_polls`;
CREATE TABLE IF NOT EXISTS `nf_forum_polls` (
  `topic_id` int(11) UNSIGNED NOT NULL,
  `question` varchar(100) NOT NULL,
  `answers` text NOT NULL,
  `is_multiple_choice` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nf_forum_read`
--

DROP TABLE IF EXISTS `nf_forum_read`;
CREATE TABLE IF NOT EXISTS `nf_forum_read` (
  `user_id` int(11) UNSIGNED NOT NULL,
  `forum_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`,`forum_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nf_forum_topics`
--

DROP TABLE IF EXISTS `nf_forum_topics`;
CREATE TABLE IF NOT EXISTS `nf_forum_topics` (
  `topic_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `forum_id` int(11) UNSIGNED NOT NULL,
  `message_id` int(11) UNSIGNED NULL DEFAULT NULL,
  `title` varchar(100) NOT NULL,
  `status` enum('-2','-1','0','1') NOT NULL DEFAULT '0',
  `views` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `count_messages` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `last_message_id` int(11) UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`topic_id`),
  UNIQUE KEY `last_message_id` (`last_message_id`),
  KEY `forum_id` (`forum_id`),
  KEY `message_id` (`message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nf_forum_topics_read`
--

DROP TABLE IF EXISTS `nf_forum_topics_read`;
CREATE TABLE IF NOT EXISTS `nf_forum_topics_read` (
  `topic_id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`topic_id`,`user_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nf_forum_track`
--

DROP TABLE IF EXISTS `nf_forum_track`;
CREATE TABLE IF NOT EXISTS `nf_forum_track` (
  `topic_id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`topic_id`,`user_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nf_forum_url`
--

DROP TABLE IF EXISTS `nf_forum_url`;
CREATE TABLE IF NOT EXISTS `nf_forum_url` (
  `forum_id` int(11) UNSIGNED NOT NULL,
  `url` varchar(100) NOT NULL,
  `redirects` int(11) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`forum_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nf_gallery`
--

DROP TABLE IF EXISTS `nf_gallery`;
CREATE TABLE IF NOT EXISTS `nf_gallery` (
  `gallery_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` int(11) UNSIGNED NOT NULL,
  `image_id` int(11) UNSIGNED NULL DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `published` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`gallery_id`),
  KEY `category_id` (`category_id`),
  KEY `image_id` (`image_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nf_gallery_categories`
--

DROP TABLE IF EXISTS `nf_gallery_categories`;
CREATE TABLE IF NOT EXISTS `nf_gallery_categories` (
  `category_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `image_id` int(11) UNSIGNED NULL DEFAULT NULL,
  `icon_id` int(11) UNSIGNED NULL DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`category_id`),
  KEY `image_id` (`image_id`),
  KEY `icon_id` (`icon_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nf_gallery_categories_lang`
--

DROP TABLE IF EXISTS `nf_gallery_categories_lang`;
CREATE TABLE IF NOT EXISTS `nf_gallery_categories_lang` (
  `category_id` int(11) UNSIGNED NOT NULL,
  `lang` varchar(5) NOT NULL,
  `title` varchar(100) NOT NULL,
  PRIMARY KEY (`category_id`,`lang`),
  KEY `lang` (`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nf_gallery_images`
--

DROP TABLE IF EXISTS `nf_gallery_images`;
CREATE TABLE IF NOT EXISTS `nf_gallery_images` (
  `image_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `thumbnail_file_id` int(11) UNSIGNED NOT NULL,
  `original_file_id` int(11) UNSIGNED NOT NULL,
  `file_id` int(11) UNSIGNED NOT NULL,
  `gallery_id` int(11) UNSIGNED NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `views` int(11) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`image_id`),
  KEY `file_id` (`file_id`),
  KEY `gallery_id` (`gallery_id`),
  KEY `original_file_id` (`original_file_id`),
  KEY `thumbnail_file_id` (`thumbnail_file_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nf_gallery_lang`
--

DROP TABLE IF EXISTS `nf_gallery_lang`;
CREATE TABLE IF NOT EXISTS `nf_gallery_lang` (
  `gallery_id` int(11) UNSIGNED NOT NULL,
  `lang` varchar(5) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`gallery_id`,`lang`),
  KEY `lang` (`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nf_games`
--

DROP TABLE IF EXISTS `nf_games`;
CREATE TABLE IF NOT EXISTS `nf_games` (
  `game_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) UNSIGNED NULL DEFAULT NULL,
  `image_id` int(11) UNSIGNED NULL DEFAULT NULL,
  `icon_id` int(11) UNSIGNED NULL DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`game_id`),
  KEY `image_id` (`image_id`),
  KEY `icon_id` (`icon_id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nf_games_lang`
--

DROP TABLE IF EXISTS `nf_games_lang`;
CREATE TABLE IF NOT EXISTS `nf_games_lang` (
  `game_id` int(11) UNSIGNED NOT NULL,
  `lang` varchar(5) NOT NULL,
  `title` varchar(100) NOT NULL,
  PRIMARY KEY (`game_id`,`lang`),
  KEY `lang` (`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nf_games_maps`
--

DROP TABLE IF EXISTS `nf_games_maps`;
CREATE TABLE IF NOT EXISTS `nf_games_maps` (
  `map_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `game_id` int(11) UNSIGNED NOT NULL,
  `image_id` int(11) UNSIGNED NULL DEFAULT NULL,
  `title` varchar(100) NOT NULL,
  PRIMARY KEY (`map_id`),
  KEY `game_id` (`game_id`),
  KEY `image_id` (`image_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nf_games_modes`
--

DROP TABLE IF EXISTS `nf_games_modes`;
CREATE TABLE IF NOT EXISTS `nf_games_modes` (
  `mode_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `game_id` int(11) UNSIGNED NOT NULL,
  `title` varchar(100) NOT NULL,
  PRIMARY KEY (`mode_id`),
  KEY `game_id` (`game_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nf_groups`
--

DROP TABLE IF EXISTS `nf_groups`;
CREATE TABLE IF NOT EXISTS `nf_groups` (
  `group_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `color` varchar(20) NOT NULL,
  `icon` varchar(20) NOT NULL,
  `hidden` enum('0','1') NOT NULL DEFAULT '0',
  `auto` enum('0','1') NOT NULL DEFAULT '0',
  `order` smallint(6) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Structure de la table `nf_groups_lang`
--

DROP TABLE IF EXISTS `nf_groups_lang`;
CREATE TABLE IF NOT EXISTS `nf_groups_lang` (
  `group_id` int(11) UNSIGNED NOT NULL,
  `lang` varchar(5) NOT NULL,
  `title` varchar(100) NOT NULL,
  PRIMARY KEY (`group_id`,`lang`),
  KEY `lang` (`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nf_news`
--

DROP TABLE IF EXISTS `nf_news`;
CREATE TABLE IF NOT EXISTS `nf_news` (
  `news_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `image_id` int(11) UNSIGNED NULL DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `published` enum('0','1') NOT NULL DEFAULT '0',
  `views` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `vote` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`news_id`),
  KEY `category_id` (`category_id`),
  KEY `user_id` (`user_id`),
  KEY `image_id` (`image_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Contenu de la table `nf_news`
--

INSERT INTO `nf_news` VALUES(1, 1, 1, NULL, CURRENT_TIMESTAMP, '1', 0, '0');

-- --------------------------------------------------------

--
-- Structure de la table `nf_news_categories`
--

DROP TABLE IF EXISTS `nf_news_categories`;
CREATE TABLE IF NOT EXISTS `nf_news_categories` (
  `category_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `image_id` int(11) UNSIGNED NULL DEFAULT NULL,
  `icon_id` int(11) UNSIGNED NULL DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`category_id`),
  KEY `image_id` (`image_id`),
  KEY `icon_id` (`icon_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Contenu de la table `nf_news_categories`
--

INSERT INTO `nf_news_categories` VALUES(1, 1, NULL, 'general');

-- --------------------------------------------------------

--
-- Structure de la table `nf_news_categories_lang`
--

DROP TABLE IF EXISTS `nf_news_categories_lang`;
CREATE TABLE IF NOT EXISTS `nf_news_categories_lang` (
  `category_id` int(11) UNSIGNED NOT NULL,
  `lang` varchar(5) NOT NULL,
  `title` varchar(100) NOT NULL,
  PRIMARY KEY (`category_id`,`lang`),
  KEY `lang` (`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `nf_news_categories_lang`
--

INSERT INTO `nf_news_categories_lang` VALUES(1, 'fr', 'G&eacute;n&eacute;ral');

-- --------------------------------------------------------

--
-- Structure de la table `nf_news_lang`
--

DROP TABLE IF EXISTS `nf_news_lang`;
CREATE TABLE IF NOT EXISTS `nf_news_lang` (
  `news_id` int(11) UNSIGNED NOT NULL,
  `lang` varchar(5) NOT NULL,
  `title` varchar(100) NOT NULL,
  `introduction` text NOT NULL,
  `content` text NOT NULL,
  `tags` text NOT NULL,
  PRIMARY KEY (`news_id`,`lang`),
  KEY `lang` (`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `nf_news_lang`
--

INSERT INTO `nf_news_lang` VALUES(1, 'fr', 'Bienvenue sur votre site NeoFrag Alpha !', 'Nec vox accusatoris ulla licet subditicii in his malorum quaerebatur acervis ut saltem specie tenus crimina praescriptis legum \r\ncommitterentur, quod aliquotiens fecere principes saevi: sed quicquid \r\nCaesaris mplacabilitati sedisset, id velut fas iusque perpensum \r\nconfestim urgebatur impleri.', 'Omitto iuris dictionem in libera civitate contra leges senatusque consulta; caedes relinquo; libidines praetereo, quarum acerbissimum extat indicium et ad insignem memoriam turpitudinis et paene ad iustum odium imperii nostri, quod constat nobilissimas virgines se in puteos abiecisse et morte voluntaria necessariam turpitudinem depulisse. Nec haec idcirco omitto, quod non gravissima sint, sed quia nunc sine teste dico.', 'NeoFrag,Cms,Alpha');

-- --------------------------------------------------------

--
-- Structure de la table `nf_pages`
--

DROP TABLE IF EXISTS `nf_pages`;
CREATE TABLE IF NOT EXISTS `nf_pages` (
  `page_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `published` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`page_id`),
  UNIQUE KEY `page` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nf_pages_lang`
--

DROP TABLE IF EXISTS `nf_pages_lang`;
CREATE TABLE IF NOT EXISTS `nf_pages_lang` (
  `page_id` int(11) UNSIGNED NOT NULL,
  `lang` varchar(5) NOT NULL,
  `title` varchar(100) NOT NULL,
  `subtitle` varchar(100) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`page_id`,`lang`),
  KEY `lang` (`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nf_partners`
--

DROP TABLE IF EXISTS `nf_partners`;
CREATE TABLE IF NOT EXISTS `nf_partners` (
  `partner_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `logo_light` int(11) UNSIGNED NULL DEFAULT NULL,
  `logo_dark` int(11) UNSIGNED NULL DEFAULT NULL,
  `website` varchar(100) NOT NULL,
  `facebook` varchar(100) NOT NULL,
  `twitter` varchar(100) NOT NULL,
  `code` varchar(50) NOT NULL,
  `count` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `order` tinyint(6) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`partner_id`),
  KEY `image_id` (`logo_light`),
  KEY `logo_dark` (`logo_dark`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `nf_partners`
--

INSERT INTO `nf_partners` VALUES(1, 'neofrag', 2, 3, 'https://neofr.ag', 'https://www.facebook.com/NeoFrag-CMS-345511868808600/', 'https://twitter.com/NeoFragCMS', '', 1, 0);

-- --------------------------------------------------------

--
-- Structure de la table `nf_partners_lang`
--

DROP TABLE IF EXISTS `nf_partners_lang`;
CREATE TABLE IF NOT EXISTS `nf_partners_lang` (
  `partner_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `lang` varchar(5) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`partner_id`),
  KEY `lang` (`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `nf_partners_lang`
--

INSERT INTO `nf_partners_lang` VALUES(1, 'fr', 'NeoFrag', 'NeoFrag est un CMS (syst&egrave;me de gestion de contenu) &agrave; la fois puissant, compact et performant, pour cr&eacute;er votre site web orient&eacute; eSport !\r\n\r\n[b]C''est enti&egrave;rement gratuit et personnalisable ![/b]\r\nPeu importe votre niveau dans le domaine du web, ce projet a pour but de vous proposer une solution cl&eacute;s en main pour cr&eacute;er votre site &agrave; l''aide d''interfaces modernes, personnalisables et &eacute;volutives pour correspondre &agrave; un maximum d''univers.');

-- --------------------------------------------------------

--
-- Structure de la table `nf_recruits`
--

DROP TABLE IF EXISTS `nf_recruits`;
CREATE TABLE IF NOT EXISTS `nf_recruits` (
  `recruit_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `introduction` text NOT NULL,
  `description` text NOT NULL,
  `requierments` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(11) UNSIGNED NOT NULL,
  `size` int(11) NOT NULL,
  `role` varchar(60) NOT NULL,
  `icon` varchar(60) NOT NULL,
  `date_end` date DEFAULT NULL,
  `closed` enum('0','1') NOT NULL DEFAULT '0',
  `team_id` int(11) UNSIGNED DEFAULT NULL,
  `image_id` int(11) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`recruit_id`),
  KEY `image_id` (`image_id`),
  KEY `user_id` (`user_id`),
  KEY `team_id` (`team_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nf_recruits_candidacies`
--

DROP TABLE IF EXISTS `nf_recruits_candidacies`;
CREATE TABLE IF NOT EXISTS `nf_recruits_candidacies` (
  `candidacy_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `recruit_id` int(11) UNSIGNED NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(11) UNSIGNED DEFAULT NULL,
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
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nf_recruits_candidacies_votes`
--

DROP TABLE IF EXISTS `nf_recruits_candidacies_votes`;
CREATE TABLE IF NOT EXISTS `nf_recruits_candidacies_votes` (
  `vote_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `candidacy_id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `vote` enum('0','1') NOT NULL DEFAULT '0',
  `comment` text NOT NULL,
  PRIMARY KEY (`vote_id`),
  KEY `candidacy_id` (`candidacy_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nf_search_keywords`
--

DROP TABLE IF EXISTS `nf_search_keywords`;
CREATE TABLE IF NOT EXISTS `nf_search_keywords` (
  `keyword` varchar(100) NOT NULL,
  `count` int(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`keyword`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nf_sessions`
--

DROP TABLE IF EXISTS `nf_sessions`;
CREATE TABLE IF NOT EXISTS `nf_sessions` (
  `session_id` varchar(32) NOT NULL,
  `ip_address` varchar(39) NOT NULL,
  `host_name` varchar(100) NOT NULL,
  `user_id` int(11) UNSIGNED NULL DEFAULT NULL,
  `is_crawler` enum('0','1') NOT NULL DEFAULT '0',
  `last_activity` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_data` text NOT NULL,
  `remember_me` enum('0','1') NOT NULL DEFAULT '0',
  UNIQUE KEY `session_id` (`session_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nf_sessions_history`
--

DROP TABLE IF EXISTS `nf_sessions_history`;
CREATE TABLE IF NOT EXISTS `nf_sessions_history` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `session_id` varchar(32) NULL DEFAULT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `ip_address` varchar(39) NOT NULL,
  `host_name` varchar(100) NOT NULL,
  `authenticator` varchar(100) NOT NULL,
  `referer` varchar(100) NOT NULL,
  `user_agent` varchar(250) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nf_settings`
--

DROP TABLE IF EXISTS `nf_settings`;
CREATE TABLE IF NOT EXISTS `nf_settings` (
  `name` varchar(100) NOT NULL,
  `site` varchar(100) NOT NULL DEFAULT '',
  `lang` varchar(5) NOT NULL DEFAULT '',
  `value` text NOT NULL,
  `type` enum('string','bool','int','list','array','float') NOT NULL DEFAULT 'string',
  PRIMARY KEY (`name`,`site`,`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `nf_settings`
--

INSERT INTO `nf_settings` VALUES('forum_messages_per_page', '', '', '15', 'int');
INSERT INTO `nf_settings` VALUES('forum_topics_per_page', '', '', '20', 'int');
INSERT INTO `nf_settings` VALUES('news_per_page', '', '', '5', 'int');
INSERT INTO `nf_settings` VALUES('nf_analytics', '', '', '', 'string');
INSERT INTO `nf_settings` VALUES('nf_contact', '', '', 'noreply@neofrag.com', 'string');
INSERT INTO `nf_settings` VALUES('nf_cookie_expire', '', '', '1 hour', 'string');
INSERT INTO `nf_settings` VALUES('nf_cookie_name', '', '', 'session', 'string');
INSERT INTO `nf_settings` VALUES('nf_debug', '', '', '0', 'int');
INSERT INTO `nf_settings` VALUES('nf_default_page', 'default', '', 'news', 'string');
INSERT INTO `nf_settings` VALUES('nf_default_theme', 'default', '', 'default', 'string');
INSERT INTO `nf_settings` VALUES('nf_description', 'default', '', 'ALPHA 0.1.5.3', 'string');
INSERT INTO `nf_settings` VALUES('nf_humans_txt', '', '', '/* TEAM */\n	NeoFrag CMS for gamers\n	Contact: contact [at] neofrag.fr\n	Twitter: @NeoFragCMS\n	From: France\n\n	Developper: Micha&euml;l BILCOT\n	Contact: michael.bilcot [at] neofrag.fr\n	Twitter: @NeoFragCMS\n	From: Paris, France\n\n	Designer: J&eacute;r&eacute;my VALENTIN\n	Contact: jeremy.valentin [at] neofrag.fr\n	Twitter: @NeoFragCMS\n	From: Caen, France', 'string');
INSERT INTO `nf_settings` VALUES('nf_name', 'default', '', 'NeoFrag CMS', 'string');
INSERT INTO `nf_settings` VALUES('nf_robots_txt', '', '', 'User-agent: *\r\nDisallow:', 'string');
INSERT INTO `nf_settings` VALUES('default_background', '', '', '0', 'int');
INSERT INTO `nf_settings` VALUES('default_background_attachment', '', '', 'scroll', 'string');
INSERT INTO `nf_settings` VALUES('default_background_color', '', '', '#141d26', 'string');
INSERT INTO `nf_settings` VALUES('default_background_position', '', '', 'center top', 'string');
INSERT INTO `nf_settings` VALUES('default_background_repeat', '', '', 'no-repeat', 'string');
INSERT INTO `nf_settings` VALUES('partners_logo_display', '', '', 'logo_dark', 'string');
INSERT INTO `nf_settings` VALUES('nf_captcha_private_key', '', '', '', 'string');
INSERT INTO `nf_settings` VALUES('nf_captcha_public_key', '', '', '', 'string');
INSERT INTO `nf_settings` VALUES('nf_email_password', '', '', '', 'string');
INSERT INTO `nf_settings` VALUES('nf_email_port', '', '', '25', 'int');
INSERT INTO `nf_settings` VALUES('nf_email_secure', '', '', '', 'string');
INSERT INTO `nf_settings` VALUES('nf_email_smtp', '', '', '', 'string');
INSERT INTO `nf_settings` VALUES('nf_email_username', '', '', '', 'string');
INSERT INTO `nf_settings` VALUES('nf_registration_charte', '', '', '', 'string');
INSERT INTO `nf_settings` VALUES('nf_registration_status', '', '', '0', 'string');
INSERT INTO `nf_settings` VALUES('nf_social_behance', '', '', '', 'string');
INSERT INTO `nf_settings` VALUES('nf_social_deviantart', '', '', '', 'string');
INSERT INTO `nf_settings` VALUES('nf_social_dribble', '', '', '', 'string');
INSERT INTO `nf_settings` VALUES('nf_social_facebook', '', '', '', 'string');
INSERT INTO `nf_settings` VALUES('nf_social_flickr', '', '', '', 'string');
INSERT INTO `nf_settings` VALUES('nf_social_github', '', '', '', 'string');
INSERT INTO `nf_settings` VALUES('nf_social_google', '', '', '', 'string');
INSERT INTO `nf_settings` VALUES('nf_social_instagram', '', '', '', 'string');
INSERT INTO `nf_settings` VALUES('nf_social_steam', '', '', '', 'string');
INSERT INTO `nf_settings` VALUES('nf_social_twitch', '', '', '', 'string');
INSERT INTO `nf_settings` VALUES('nf_social_twitter', '', '', '', 'string');
INSERT INTO `nf_settings` VALUES('nf_social_youtube', '', '', '', 'string');
INSERT INTO `nf_settings` VALUES('nf_team_logo', '', '', '0', 'int');
INSERT INTO `nf_settings` VALUES('nf_team_biographie', '', '', '', 'string');
INSERT INTO `nf_settings` VALUES('nf_team_creation', '', '', '', 'string');
INSERT INTO `nf_settings` VALUES('nf_team_name', '', '', '', 'string');
INSERT INTO `nf_settings` VALUES('nf_team_type', '', '', '', 'string');
INSERT INTO `nf_settings` VALUES('nf_welcome', '', '', '0', 'bool');
INSERT INTO `nf_settings` VALUES('nf_welcome_content', '', '', '', 'string');
INSERT INTO `nf_settings` VALUES('nf_welcome_title', '', '', '', 'string');
INSERT INTO `nf_settings` VALUES('nf_welcome_user_id', '', '', '0', 'int');
INSERT INTO `nf_settings` VALUES('nf_version_css', '', '', '0', 'int');
INSERT INTO `nf_settings` VALUES('nf_monitoring_last_check', '', '', '0', 'int');
INSERT INTO `nf_settings` VALUES('nf_http_authentication', '', '', '0', 'bool');
INSERT INTO `nf_settings` VALUES('nf_http_authentication_name', '', '', '', 'string');
INSERT INTO `nf_settings` VALUES('nf_maintenance', '', '', '0', 'bool');
INSERT INTO `nf_settings` VALUES('nf_maintenance_opening', '', '', '', 'string');
INSERT INTO `nf_settings` VALUES('nf_maintenance_title', '', '', '', 'string');
INSERT INTO `nf_settings` VALUES('nf_maintenance_content', '', '', '', 'string');
INSERT INTO `nf_settings` VALUES('nf_maintenance_logo', '', '', '0', 'int');
INSERT INTO `nf_settings` VALUES('nf_maintenance_background', '', '', '0', 'int');
INSERT INTO `nf_settings` VALUES('nf_maintenance_background_repeat', '', '', '', 'string');
INSERT INTO `nf_settings` VALUES('nf_maintenance_background_position', '', '', '', 'string');
INSERT INTO `nf_settings` VALUES('nf_maintenance_background_color', '', '', '', 'string');
INSERT INTO `nf_settings` VALUES('nf_maintenance_text_color', '', '', '', 'string');
INSERT INTO `nf_settings` VALUES('nf_maintenance_facebook', '', '', '', 'string');
INSERT INTO `nf_settings` VALUES('nf_maintenance_twitter', '', '', '', 'string');
INSERT INTO `nf_settings` VALUES('nf_maintenance_google-plus', '', '', '', 'string');
INSERT INTO `nf_settings` VALUES('nf_maintenance_steam', '', '', '', 'string');
INSERT INTO `nf_settings` VALUES('nf_maintenance_twitch', '', '', '', 'string');
INSERT INTO `nf_settings` VALUES('recruits_alert', '', '', '1', 'bool');
INSERT INTO `nf_settings` VALUES('recruits_hide_unavailable', '', '', '1', 'bool');
INSERT INTO `nf_settings` VALUES('recruits_per_page', '', '', '5', 'int');
INSERT INTO `nf_settings` VALUES('recruits_send_mail', '', '', '1', 'bool');
INSERT INTO `nf_settings` VALUES('recruits_send_mp', '', '', '1', 'bool');

-- --------------------------------------------------------

--
-- Structure de la table `nf_settings_addons`
--

DROP TABLE IF EXISTS `nf_settings_addons`;
CREATE TABLE IF NOT EXISTS `nf_settings_addons` (
  `name` varchar(100) NOT NULL,
  `type` enum('module','theme','widget') NOT NULL,
  `is_enabled` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`name`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `nf_settings_addons`
--

INSERT INTO `nf_settings_addons` VALUES('access', 'module', '1');
INSERT INTO `nf_settings_addons` VALUES('addons', 'module', '1');
INSERT INTO `nf_settings_addons` VALUES('admin', 'module', '1');
INSERT INTO `nf_settings_addons` VALUES('awards', 'module', '1');
INSERT INTO `nf_settings_addons` VALUES('awards', 'widget', '1');
INSERT INTO `nf_settings_addons` VALUES('breadcrumb', 'widget', '1');
INSERT INTO `nf_settings_addons` VALUES('comments', 'module', '1');
INSERT INTO `nf_settings_addons` VALUES('contact', 'module', '1');
INSERT INTO `nf_settings_addons` VALUES('default', 'theme', '1');
INSERT INTO `nf_settings_addons` VALUES('error', 'module', '1');
INSERT INTO `nf_settings_addons` VALUES('error', 'widget', '1');
INSERT INTO `nf_settings_addons` VALUES('forum', 'module', '1');
INSERT INTO `nf_settings_addons` VALUES('forum', 'widget', '1');
INSERT INTO `nf_settings_addons` VALUES('gallery', 'module', '1');
INSERT INTO `nf_settings_addons` VALUES('gallery', 'widget', '1');
INSERT INTO `nf_settings_addons` VALUES('games', 'module', '1');
INSERT INTO `nf_settings_addons` VALUES('header', 'widget', '1');
INSERT INTO `nf_settings_addons` VALUES('html', 'widget', '1');
INSERT INTO `nf_settings_addons` VALUES('live_editor', 'module', '1');
INSERT INTO `nf_settings_addons` VALUES('members', 'module', '1');
INSERT INTO `nf_settings_addons` VALUES('members', 'widget', '1');
INSERT INTO `nf_settings_addons` VALUES('module', 'widget', '1');
INSERT INTO `nf_settings_addons` VALUES('monitoring', 'module', '1');
INSERT INTO `nf_settings_addons` VALUES('navigation', 'widget', '1');
INSERT INTO `nf_settings_addons` VALUES('news', 'module', '1');
INSERT INTO `nf_settings_addons` VALUES('news', 'widget', '1');
INSERT INTO `nf_settings_addons` VALUES('pages', 'module', '1');
INSERT INTO `nf_settings_addons` VALUES('partners', 'module', '1');
INSERT INTO `nf_settings_addons` VALUES('partners', 'widget', '1');
INSERT INTO `nf_settings_addons` VALUES('recruits', 'module', '1');
INSERT INTO `nf_settings_addons` VALUES('recruits', 'widget', '1');
INSERT INTO `nf_settings_addons` VALUES('search', 'module', '1');
INSERT INTO `nf_settings_addons` VALUES('search', 'widget', '1');
INSERT INTO `nf_settings_addons` VALUES('settings', 'module', '1');
INSERT INTO `nf_settings_addons` VALUES('slider', 'widget', '1');
INSERT INTO `nf_settings_addons` VALUES('statistics', 'module', '1');
INSERT INTO `nf_settings_addons` VALUES('talks', 'module', '1');
INSERT INTO `nf_settings_addons` VALUES('talks', 'widget', '1');
INSERT INTO `nf_settings_addons` VALUES('teams', 'module', '1');
INSERT INTO `nf_settings_addons` VALUES('teams', 'widget', '1');
INSERT INTO `nf_settings_addons` VALUES('user', 'module', '1');
INSERT INTO `nf_settings_addons` VALUES('user', 'widget', '1');

-- --------------------------------------------------------

--
-- Structure de la table `nf_settings_authenticators`
--

DROP TABLE IF EXISTS `nf_settings_authenticators`;
CREATE TABLE IF NOT EXISTS `nf_settings_authenticators` (
  `name` varchar(100) NOT NULL,
  `settings` text NOT NULL,
  `is_enabled` enum('0','1') NOT NULL DEFAULT '0',
  `order` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `nf_settings_authenticators`
--

INSERT INTO `nf_settings_authenticators` VALUES('facebook', 'a:0:{}', '0', 0);
INSERT INTO `nf_settings_authenticators` VALUES('twitter', 'a:0:{}', '0', 1);
INSERT INTO `nf_settings_authenticators` VALUES('google', 'a:0:{}', '0', 2);
INSERT INTO `nf_settings_authenticators` VALUES('battle_net', 'a:0:{}', '0', 3);
INSERT INTO `nf_settings_authenticators` VALUES('steam', 'a:0:{}', '0', 4);
INSERT INTO `nf_settings_authenticators` VALUES('twitch', 'a:0:{}', '0', 5);
INSERT INTO `nf_settings_authenticators` VALUES('github', 'a:0:{}', '0', 6);
INSERT INTO `nf_settings_authenticators` VALUES('linkedin', 'a:0:{}', '0', 7);

-- --------------------------------------------------------

--
-- Structure de la table `nf_settings_languages`
--

DROP TABLE IF EXISTS `nf_settings_languages`;
CREATE TABLE IF NOT EXISTS `nf_settings_languages` (
  `code` varchar(5) NOT NULL,
  `name` varchar(100) NOT NULL,
  `flag` varchar(100) NOT NULL,
  `order` smallint(6) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Contenu de la table `nf_settings_languages`
--

INSERT INTO `nf_settings_languages` VALUES('fr', 'Français', 'fr.png', 1);
INSERT INTO `nf_settings_languages` VALUES('en', 'English', 'gb.png', 2);
INSERT INTO `nf_settings_languages` VALUES('de', 'Deutsch', 'de.png', 3);
INSERT INTO `nf_settings_languages` VALUES('es', 'Español', 'es.png', 4);
INSERT INTO `nf_settings_languages` VALUES('it', 'Italiano', 'it.png', 5);
INSERT INTO `nf_settings_languages` VALUES('pt', 'Português', 'pt.png', 6);

-- --------------------------------------------------------

--
-- Structure de la table `nf_settings_smileys`
--

DROP TABLE IF EXISTS `nf_settings_smileys`;
CREATE TABLE IF NOT EXISTS `nf_settings_smileys` (
  `smiley_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `file_id` int(11) UNSIGNED NOT NULL,
  `code` varchar(15) NOT NULL,
  PRIMARY KEY (`smiley_id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nf_statistics`
--

DROP TABLE IF EXISTS `nf_statistics`;
CREATE TABLE IF NOT EXISTS `nf_statistics` (
  `name` varchar(100) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `nf_statistics`
--

INSERT INTO `nf_statistics` VALUES('nf_sessions_max_simultaneous', '0');

-- --------------------------------------------------------

--
-- Structure de la table `nf_talks`
--

DROP TABLE IF EXISTS `nf_talks`;
CREATE TABLE IF NOT EXISTS `nf_talks` (
  `talk_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`talk_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Contenu de la table `nf_talks`
--

INSERT INTO `nf_talks` VALUES(1, 'admin');
INSERT INTO `nf_talks` VALUES(2, 'public');

-- --------------------------------------------------------

--
-- Structure de la table `nf_talks_messages`
--

DROP TABLE IF EXISTS `nf_talks_messages`;
CREATE TABLE IF NOT EXISTS `nf_talks_messages` (
  `message_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `talk_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NULL DEFAULT NULL,
  `message` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`message_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Contenu de la table `nf_talks_messages`
--

INSERT INTO `nf_talks_messages` VALUES(1, 2, 1, 'Bienvenue sur votre nouveau site !', CURRENT_TIMESTAMP);

-- --------------------------------------------------------

--
-- Structure de la table `nf_teams`
--

DROP TABLE IF EXISTS `nf_teams`;
CREATE TABLE IF NOT EXISTS `nf_teams` (
  `team_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `game_id` int(11) UNSIGNED NOT NULL,
  `image_id` int(11) UNSIGNED NULL DEFAULT NULL,
  `icon_id` int(11) UNSIGNED NULL DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `order` smallint(6) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`team_id`),
  KEY `activity_id` (`game_id`),
  KEY `image_id` (`image_id`),
  KEY `icon_id` (`icon_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nf_teams_lang`
--

DROP TABLE IF EXISTS `nf_teams_lang`;
CREATE TABLE IF NOT EXISTS `nf_teams_lang` (
  `team_id` int(11) UNSIGNED NOT NULL,
  `lang` varchar(5) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`team_id`,`lang`),
  KEY `lang` (`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nf_teams_roles`
--

DROP TABLE IF EXISTS `nf_teams_roles`;
CREATE TABLE IF NOT EXISTS `nf_teams_roles` (
  `role_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `order` smallint(6) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nf_teams_users`
--

DROP TABLE IF EXISTS `nf_teams_users`;
CREATE TABLE IF NOT EXISTS `nf_teams_users` (
  `team_id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`team_id`,`user_id`),
  KEY `user_id` (`user_id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nf_users`
--

DROP TABLE IF EXISTS `nf_users`;
CREATE TABLE IF NOT EXISTS `nf_users` (
  `user_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(34) NOT NULL,
  `salt` varchar(32) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `registration_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_activity_date` timestamp NULL DEFAULT NULL,
  `admin` enum('0','1') NOT NULL DEFAULT '0',
  `language` varchar(5) NULL DEFAULT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `language` (`language`),
  KEY `deleted` (`deleted`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Contenu de la table `nf_users`
--

INSERT INTO `nf_users` VALUES(1, 'admin', '$H$92EwygSmbdXunbIvoo/V91MWcnHqzX/', '', 'noreply@neofrag.com', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, '1', NULL, '0');

-- --------------------------------------------------------

--
-- Structure de la table `nf_users_auth`
--

DROP TABLE IF EXISTS `nf_users_auth`;
CREATE TABLE IF NOT EXISTS `nf_users_auth` (
  `user_id` int(11) UNSIGNED NOT NULL,
  `authenticator` varchar(100) NOT NULL,
  `id` varchar(250) NOT NULL,
  PRIMARY KEY (`authenticator`,`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nf_users_groups`
--

DROP TABLE IF EXISTS `nf_users_groups`;
CREATE TABLE IF NOT EXISTS `nf_users_groups` (
  `user_id` int(11) UNSIGNED NOT NULL,
  `group_id` int(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`user_id`,`group_id`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nf_users_keys`
--

DROP TABLE IF EXISTS `nf_users_keys`;
CREATE TABLE IF NOT EXISTS `nf_users_keys` (
  `key_id` varchar(32) NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `session_id` varchar(32) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`key_id`),
  KEY `user_id` (`user_id`),
  KEY `session_id` (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nf_users_messages`
--

DROP TABLE IF EXISTS `nf_users_messages`;
CREATE TABLE IF NOT EXISTS `nf_users_messages` (
  `message_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `reply_id` int(11) UNSIGNED NULL DEFAULT NULL,
  `title` varchar(100) NOT NULL,
  `last_reply_id` int(11) UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`message_id`),
  KEY `reply_id` (`reply_id`),
  KEY `last_reply_id` (`last_reply_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nf_users_messages_recipients`
--

DROP TABLE IF EXISTS `nf_users_messages_recipients`;
CREATE TABLE IF NOT EXISTS `nf_users_messages_recipients` (
  `user_id` int(11) UNSIGNED NOT NULL,
  `message_id` int(11) UNSIGNED NOT NULL,
  `date` timestamp NULL DEFAULT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`,`message_id`),
  KEY `message_id` (`message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nf_users_messages_replies`
--

DROP TABLE IF EXISTS `nf_users_messages_replies`;
CREATE TABLE IF NOT EXISTS `nf_users_messages_replies` (
  `reply_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `message_id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `message` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`reply_id`),
  KEY `message_id` (`message_id`,`user_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nf_users_profiles`
--

DROP TABLE IF EXISTS `nf_users_profiles`;
CREATE TABLE IF NOT EXISTS `nf_users_profiles` (
  `user_id` int(11) UNSIGNED NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `avatar` int(11) UNSIGNED NULL DEFAULT NULL,
  `signature` text NOT NULL,
  `date_of_birth` date NULL DEFAULT NULL,
  `sex` enum('male','female') NULL DEFAULT NULL,
  `location` varchar(100) NOT NULL,
  `quote` varchar(100) NOT NULL,
  `website` varchar(100) NOT NULL,
  PRIMARY KEY (`user_id`),
  KEY `avatar` (`avatar`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nf_votes`
--

DROP TABLE IF EXISTS `nf_votes`;
CREATE TABLE IF NOT EXISTS `nf_votes` (
  `module_id` int(11) UNSIGNED NOT NULL,
  `module` varchar(100) NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `note` tinyint(4) NOT NULL,
  PRIMARY KEY (`module_id`,`module`,`user_id`),
  KEY `module` (`module`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nf_widgets`
--

DROP TABLE IF EXISTS `nf_widgets`;
CREATE TABLE IF NOT EXISTS `nf_widgets` (
  `widget_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `widget` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `title` varchar(100) NULL DEFAULT NULL,
  `settings` text,
  PRIMARY KEY (`widget_id`),
  KEY `widget_name` (`widget`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Contenu de la table `nf_widgets`
--

INSERT INTO `nf_widgets` VALUES(1, 'talks', 'index', NULL, 'a:1:{s:7:"talk_id";s:1:"1";}');
INSERT INTO `nf_widgets` VALUES(2, 'breadcrumb', 'index', NULL, NULL);
INSERT INTO `nf_widgets` VALUES(3, 'search', 'index', NULL, NULL);
INSERT INTO `nf_widgets` VALUES(4, 'module', 'index', NULL, NULL);
INSERT INTO `nf_widgets` VALUES(5, 'navigation', 'index', NULL, 'a:2:{s:7:"display";b:0;s:5:"links";a:5:{i:0;a:2:{s:5:"title";s:17:"Actualit&eacute;s";s:3:"url";s:4:"news";}i:1;a:2:{s:5:"title";s:7:"Membres";s:3:"url";s:7:"members";}i:2;a:2:{s:5:"title";s:11:"Recrutement";s:3:"url";s:8:"recruits";}i:3;a:2:{s:5:"title";s:10:"Rechercher";s:3:"url";s:6:"search";}i:4;a:2:{s:5:"title";s:7:"Contact";s:3:"url";s:7:"contact";}}}');
INSERT INTO `nf_widgets` VALUES(6, 'partners', 'column', NULL, 'a:1:{s:13:"display_style";s:5:"light";}');
INSERT INTO `nf_widgets` VALUES(7, 'user', 'index', NULL, NULL);
INSERT INTO `nf_widgets` VALUES(8, 'news', 'categories', NULL, NULL);
INSERT INTO `nf_widgets` VALUES(9, 'talks', 'index', NULL, 'a:1:{s:7:"talk_id";i:2;}');
INSERT INTO `nf_widgets` VALUES(10, 'members', 'online', NULL, NULL);
INSERT INTO `nf_widgets` VALUES(11, 'forum', 'topics', NULL, NULL);
INSERT INTO `nf_widgets` VALUES(12, 'news', 'index', NULL, NULL);
INSERT INTO `nf_widgets` VALUES(13, 'members', 'index', NULL, NULL);
INSERT INTO `nf_widgets` VALUES(14, 'header', 'index', NULL, 'a:5:{s:5:"align";s:11:"text-center";s:5:"title";s:0:"";s:11:"description";s:0:"";s:11:"color-title";s:0:"";s:17:"color-description";s:7:"#DC351E";}');
INSERT INTO `nf_widgets` VALUES(15, 'navigation', 'index', NULL, 'a:2:{s:7:"display";b:1;s:5:"links";a:6:{i:0;a:2:{s:5:"title";s:7:"Accueil";s:3:"url";s:0:"";}i:1;a:2:{s:5:"title";s:5:"Forum";s:3:"url";s:5:"forum";}i:2;a:2:{s:5:"title";s:14:"&Eacute;quipes";s:3:"url";s:5:"teams";}i:3;a:2:{s:5:"title";s:6:"Photos";s:3:"url";s:7:"gallery";}i:4;a:2:{s:5:"title";s:11:"Partenaires";s:3:"url";s:8:"partners";}i:5;a:2:{s:5:"title";s:15:"Palmar&egrave;s";s:3:"url";s:6:"awards";}}}');
INSERT INTO `nf_widgets` VALUES(16, 'user', 'index_mini', NULL, NULL);
INSERT INTO `nf_widgets` VALUES(17, 'navigation', 'index', NULL, 'a:2:{s:7:"display";b:1;s:5:"links";a:4:{i:0;a:2:{s:5:"title";s:8:"Facebook";s:3:"url";s:1:"#";}i:1;a:2:{s:5:"title";s:7:"Twitter";s:3:"url";s:1:"#";}i:2;a:2:{s:5:"title";s:6:"Origin";s:3:"url";s:1:"#";}i:3;a:2:{s:5:"title";s:5:"Steam";s:3:"url";s:1:"#";}}}');
INSERT INTO `nf_widgets` VALUES(18, 'members', 'online_mini', NULL, NULL);
INSERT INTO `nf_widgets` VALUES(19, 'html', 'index', NULL, 'a:1:{s:7:"content";s:98:"[center]Propuls&eacute; par [url=https://neofr.ag]NeoFrag CMS[/url] version Alpha 0.1.5.3[/center]";}');
INSERT INTO `nf_widgets` VALUES(20, 'header', 'index', NULL, 'a:5:{s:5:"align";s:11:"text-center";s:5:"title";s:0:"";s:11:"description";s:0:"";s:11:"color-title";s:0:"";s:17:"color-description";s:7:"#DC351E";}');
INSERT INTO `nf_widgets` VALUES(21, 'navigation', 'index', NULL, 'a:2:{s:7:"display";b:1;s:5:"links";a:6:{i:0;a:2:{s:5:"title";s:7:"Accueil";s:3:"url";s:0:"";}i:1;a:2:{s:5:"title";s:5:"Forum";s:3:"url";s:5:"forum";}i:2;a:2:{s:5:"title";s:14:"&Eacute;quipes";s:3:"url";s:5:"teams";}i:3;a:2:{s:5:"title";s:6:"Photos";s:3:"url";s:7:"gallery";}i:4;a:2:{s:5:"title";s:11:"Partenaires";s:3:"url";s:8:"partners";}i:5;a:2:{s:5:"title";s:15:"Palmar&egrave;s";s:3:"url";s:6:"awards";}}}');
INSERT INTO `nf_widgets` VALUES(22, 'user', 'index_mini', NULL, NULL);
INSERT INTO `nf_widgets` VALUES(23, 'slider', 'index', NULL, NULL);
INSERT INTO `nf_widgets` VALUES(24, 'breadcrumb', 'index', NULL, NULL);
INSERT INTO `nf_widgets` VALUES(25, 'search', 'index', NULL, NULL);
INSERT INTO `nf_widgets` VALUES(26, 'module', 'index', NULL, NULL);
INSERT INTO `nf_widgets` VALUES(27, 'breadcrumb', 'index', NULL, NULL);
INSERT INTO `nf_widgets` VALUES(28, 'search', 'index', NULL, NULL);
INSERT INTO `nf_widgets` VALUES(29, 'module', 'index', NULL, NULL);
INSERT INTO `nf_widgets` VALUES(30, 'breadcrumb', 'index', NULL, NULL);
INSERT INTO `nf_widgets` VALUES(31, 'search', 'index', NULL, NULL);
INSERT INTO `nf_widgets` VALUES(32, 'module', 'index', NULL, NULL);
INSERT INTO `nf_widgets` VALUES(33, 'breadcrumb', 'index', NULL, NULL);
INSERT INTO `nf_widgets` VALUES(34, 'module', 'index', NULL, NULL);
INSERT INTO `nf_widgets` VALUES(35, 'forum', 'statistics', NULL, NULL);
INSERT INTO `nf_widgets` VALUES(36, 'forum', 'activity', NULL, NULL);

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `nf_access`
--
ALTER TABLE `nf_access`
  ADD CONSTRAINT `nf_access_ibfk_1` FOREIGN KEY (`module`) REFERENCES `nf_settings_addons` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `nf_access_details`
--
ALTER TABLE `nf_access_details`
  ADD CONSTRAINT `nf_access_details_ibfk_1` FOREIGN KEY (`access_id`) REFERENCES `nf_access` (`access_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `nf_awards`
--
ALTER TABLE `nf_awards`
  ADD CONSTRAINT `nf_awards_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `nf_teams` (`team_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `nf_awards_ibfk_2` FOREIGN KEY (`game_id`) REFERENCES `nf_games` (`game_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `nf_awards_ibfk_3` FOREIGN KEY (`image_id`) REFERENCES `nf_files` (`file_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `nf_comments`
--
ALTER TABLE `nf_comments`
  ADD CONSTRAINT `nf_comments_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `nf_comments` (`comment_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `nf_comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `nf_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `nf_comments_ibfk_3` FOREIGN KEY (`module`) REFERENCES `nf_settings_addons` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `nf_files`
--
ALTER TABLE `nf_files`
  ADD CONSTRAINT `nf_files_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `nf_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `nf_forum`
--
ALTER TABLE `nf_forum`
  ADD CONSTRAINT `nf_forum_ibfk_1` FOREIGN KEY (`last_message_id`) REFERENCES `nf_forum_messages` (`message_id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Contraintes pour la table `nf_forum_messages`
--
ALTER TABLE `nf_forum_messages`
  ADD CONSTRAINT `nf_forum_messages_ibfk_1` FOREIGN KEY (`topic_id`) REFERENCES `nf_forum_topics` (`topic_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `nf_forum_messages_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `nf_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `nf_forum_read`
--
ALTER TABLE `nf_forum_read`
  ADD CONSTRAINT `nf_forum_read_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `nf_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `nf_forum_topics`
--
ALTER TABLE `nf_forum_topics`
  ADD CONSTRAINT `nf_forum_topics_ibfk_1` FOREIGN KEY (`forum_id`) REFERENCES `nf_forum` (`forum_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `nf_forum_topics_ibfk_2` FOREIGN KEY (`message_id`) REFERENCES `nf_forum_messages` (`message_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `nf_forum_topics_ibfk_3` FOREIGN KEY (`last_message_id`) REFERENCES `nf_forum_messages` (`message_id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Contraintes pour la table `nf_forum_topics_read`
--
ALTER TABLE `nf_forum_topics_read`
  ADD CONSTRAINT `nf_forum_topics_read_ibfk_1` FOREIGN KEY (`topic_id`) REFERENCES `nf_forum_topics` (`topic_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `nf_forum_topics_read_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `nf_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `nf_forum_track`
--
ALTER TABLE `nf_forum_track`
  ADD CONSTRAINT `nf_forum_track_ibfk_1` FOREIGN KEY (`topic_id`) REFERENCES `nf_forum_topics` (`topic_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `nf_forum_track_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `nf_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `nf_forum_url`
--
ALTER TABLE `nf_forum_url`
  ADD CONSTRAINT `nf_forum_url_ibfk_1` FOREIGN KEY (`forum_id`) REFERENCES `nf_forum` (`forum_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `nf_gallery`
--
ALTER TABLE `nf_gallery`
  ADD CONSTRAINT `nf_gallery_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `nf_gallery_categories` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `nf_gallery_ibfk_2` FOREIGN KEY (`image_id`) REFERENCES `nf_files` (`file_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `nf_gallery_categories`
--
ALTER TABLE `nf_gallery_categories`
  ADD CONSTRAINT `nf_gallery_categories_ibfk_1` FOREIGN KEY (`image_id`) REFERENCES `nf_files` (`file_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `nf_gallery_categories_ibfk_2` FOREIGN KEY (`icon_id`) REFERENCES `nf_files` (`file_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `nf_gallery_categories_lang`
--
ALTER TABLE `nf_gallery_categories_lang`
  ADD CONSTRAINT `nf_gallery_categories_lang_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `nf_gallery_categories` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `nf_gallery_categories_lang_ibfk_2` FOREIGN KEY (`lang`) REFERENCES `nf_settings_languages` (`code`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `nf_gallery_images`
--
ALTER TABLE `nf_gallery_images`
  ADD CONSTRAINT `nf_gallery_images_ibfk_1` FOREIGN KEY (`file_id`) REFERENCES `nf_files` (`file_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `nf_gallery_images_ibfk_2` FOREIGN KEY (`gallery_id`) REFERENCES `nf_gallery` (`gallery_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `nf_gallery_images_ibfk_3` FOREIGN KEY (`thumbnail_file_id`) REFERENCES `nf_files` (`file_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `nf_gallery_images_ibfk_4` FOREIGN KEY (`original_file_id`) REFERENCES `nf_files` (`file_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `nf_gallery_lang`
--
ALTER TABLE `nf_gallery_lang`
  ADD CONSTRAINT `nf_gallery_lang_ibfk_1` FOREIGN KEY (`gallery_id`) REFERENCES `nf_gallery` (`gallery_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `nf_gallery_lang_ibfk_2` FOREIGN KEY (`lang`) REFERENCES `nf_settings_languages` (`code`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `nf_games`
--
ALTER TABLE `nf_games`
  ADD CONSTRAINT `nf_games_ibfk_1` FOREIGN KEY (`image_id`) REFERENCES `nf_files` (`file_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `nf_games_ibfk_2` FOREIGN KEY (`icon_id`) REFERENCES `nf_files` (`file_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `nf_games_ibfk_3` FOREIGN KEY (`parent_id`) REFERENCES `nf_games` (`game_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `nf_games_lang`
--
ALTER TABLE `nf_games_lang`
  ADD CONSTRAINT `nf_games_lang_ibfk_1` FOREIGN KEY (`game_id`) REFERENCES `nf_games` (`game_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `nf_games_lang_ibfk_2` FOREIGN KEY (`lang`) REFERENCES `nf_settings_languages` (`code`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `nf_groups_lang`
--
ALTER TABLE `nf_groups_lang`
  ADD CONSTRAINT `nf_groups_lang_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `nf_groups` (`group_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `nf_groups_lang_ibfk_2` FOREIGN KEY (`lang`) REFERENCES `nf_settings_languages` (`code`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `nf_games_maps`
--
ALTER TABLE `nf_games_maps`
  ADD CONSTRAINT `nf_games_maps_ibfk_1` FOREIGN KEY (`game_id`) REFERENCES `nf_games` (`game_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `nf_games_maps_ibfk_2` FOREIGN KEY (`image_id`) REFERENCES `nf_files` (`file_id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Contraintes pour la table `nf_games_modes`
--
ALTER TABLE `nf_games_modes`
  ADD CONSTRAINT `nf_games_modes_ibfk_1` FOREIGN KEY (`game_id`) REFERENCES `nf_games` (`game_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `nf_news`
--
ALTER TABLE `nf_news`
  ADD CONSTRAINT `nf_news_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `nf_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `nf_news_ibfk_2` FOREIGN KEY (`image_id`) REFERENCES `nf_files` (`file_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `nf_news_ibfk_4` FOREIGN KEY (`category_id`) REFERENCES `nf_news_categories` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `nf_news_categories`
--
ALTER TABLE `nf_news_categories`
  ADD CONSTRAINT `nf_news_categories_ibfk_1` FOREIGN KEY (`image_id`) REFERENCES `nf_files` (`file_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `nf_news_categories_ibfk_2` FOREIGN KEY (`icon_id`) REFERENCES `nf_files` (`file_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `nf_news_categories_lang`
--
ALTER TABLE `nf_news_categories_lang`
  ADD CONSTRAINT `nf_news_categories_lang_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `nf_news_categories` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `nf_news_categories_lang_ibfk_2` FOREIGN KEY (`lang`) REFERENCES `nf_settings_languages` (`code`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `nf_news_lang`
--
ALTER TABLE `nf_news_lang`
  ADD CONSTRAINT `nf_news_lang_ibfk_1` FOREIGN KEY (`news_id`) REFERENCES `nf_news` (`news_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `nf_news_lang_ibfk_2` FOREIGN KEY (`lang`) REFERENCES `nf_settings_languages` (`code`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `nf_pages_lang`
--
ALTER TABLE `nf_pages_lang`
  ADD CONSTRAINT `nf_pages_lang_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `nf_pages` (`page_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `nf_pages_lang_ibfk_2` FOREIGN KEY (`lang`) REFERENCES `nf_settings_languages` (`code`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `nf_partners`
--
ALTER TABLE `nf_partners`
  ADD CONSTRAINT `nf_partners_ibfk_1` FOREIGN KEY (`logo_light`) REFERENCES `nf_files` (`file_id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `nf_partners_ibfk_2` FOREIGN KEY (`logo_dark`) REFERENCES `nf_files` (`file_id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Contraintes pour la table `nf_partners_lang`
--
ALTER TABLE `nf_partners_lang`
  ADD CONSTRAINT `nf_partners_lang_ibfk_1` FOREIGN KEY (`partner_id`) REFERENCES `nf_partners` (`partner_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `nf_partners_lang_ibfk_2` FOREIGN KEY (`lang`) REFERENCES `nf_settings_languages` (`code`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `nf_recruits`
--
ALTER TABLE `nf_recruits`
  ADD CONSTRAINT `nf_recruits_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `nf_teams` (`team_id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `nf_recruits_ibfk_2` FOREIGN KEY (`image_id`) REFERENCES `nf_files` (`file_id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `nf_recruits_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `nf_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `nf_recruits_candidacies`
--
ALTER TABLE `nf_recruits_candidacies`
  ADD CONSTRAINT `nf_recruits_candidacies_ibfk_1` FOREIGN KEY (`recruit_id`) REFERENCES `nf_recruits` (`recruit_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `nf_recruits_candidacies_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `nf_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `nf_recruits_candidacies_votes`
--
ALTER TABLE `nf_recruits_candidacies_votes`
  ADD CONSTRAINT `nf_recruits_candidacies_votes_ibfk_1` FOREIGN KEY (`candidacy_id`) REFERENCES `nf_recruits_candidacies` (`candidacy_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `nf_recruits_candidacies_votes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `nf_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `nf_sessions`
--
ALTER TABLE `nf_sessions`
  ADD CONSTRAINT `nf_sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `nf_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `nf_sessions_history`
--
ALTER TABLE `nf_sessions_history`
  ADD CONSTRAINT `nf_sessions_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `nf_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `nf_sessions_history_ibfk_2` FOREIGN KEY (`session_id`) REFERENCES `nf_sessions` (`session_id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Contraintes pour la table `nf_teams`
--
ALTER TABLE `nf_teams`
  ADD CONSTRAINT `nf_teams_ibfk_1` FOREIGN KEY (`image_id`) REFERENCES `nf_files` (`file_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `nf_teams_ibfk_2` FOREIGN KEY (`icon_id`) REFERENCES `nf_files` (`file_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `nf_teams_ibfk_3` FOREIGN KEY (`game_id`) REFERENCES `nf_games` (`game_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `nf_teams_lang`
--
ALTER TABLE `nf_teams_lang`
  ADD CONSTRAINT `nf_teams_lang_ibfk_1` FOREIGN KEY (`lang`) REFERENCES `nf_settings_languages` (`code`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `nf_teams_lang_ibfk_2` FOREIGN KEY (`team_id`) REFERENCES `nf_teams` (`team_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `nf_teams_users`
--
ALTER TABLE `nf_teams_users`
  ADD CONSTRAINT `nf_teams_users_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `nf_teams` (`team_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `nf_teams_users_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `nf_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `nf_teams_users_ibfk_3` FOREIGN KEY (`role_id`) REFERENCES `nf_teams_roles` (`role_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `nf_users`
--
ALTER TABLE `nf_users`
  ADD CONSTRAINT `nf_users_ibfk_1` FOREIGN KEY (`language`) REFERENCES `nf_settings_languages` (`code`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Contraintes pour la table `nf_users_auth`
--
ALTER TABLE `nf_users_auth`
  ADD CONSTRAINT `nf_users_auth_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `nf_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `nf_users_auth_ibfk_2` FOREIGN KEY (`authenticator`) REFERENCES `nf_settings_authenticators` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `nf_users_groups`
--
ALTER TABLE `nf_users_groups`
  ADD CONSTRAINT `nf_users_groups_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `nf_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `nf_users_groups_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `nf_groups` (`group_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `nf_users_keys`
--
ALTER TABLE `nf_users_keys`
  ADD CONSTRAINT `nf_users_keys_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `nf_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `nf_users_keys_ibfk_2` FOREIGN KEY (`session_id`) REFERENCES `nf_sessions` (`session_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `nf_users_messages`
--
ALTER TABLE `nf_users_messages`
  ADD CONSTRAINT `nf_users_messages_ibfk_1` FOREIGN KEY (`reply_id`) REFERENCES `nf_users_messages_replies` (`reply_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `nf_users_messages_ibfk_2` FOREIGN KEY (`last_reply_id`) REFERENCES `nf_users_messages_replies` (`reply_id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Contraintes pour la table `nf_users_messages_recipients`
--
ALTER TABLE `nf_users_messages_recipients`
  ADD CONSTRAINT `nf_users_messages_recipients_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `nf_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `nf_users_messages_recipients_ibfk_2` FOREIGN KEY (`message_id`) REFERENCES `nf_users_messages` (`message_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `nf_users_messages_replies`
--
ALTER TABLE `nf_users_messages_replies`
  ADD CONSTRAINT `nf_users_messages_replies_ibfk_1` FOREIGN KEY (`message_id`) REFERENCES `nf_users_messages` (`message_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `nf_users_messages_replies_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `nf_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `nf_users_profiles`
--
ALTER TABLE `nf_users_profiles`
  ADD CONSTRAINT `nf_users_profiles_ibfk_2` FOREIGN KEY (`avatar`) REFERENCES `nf_files` (`file_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `nf_users_profiles_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `nf_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `nf_votes`
--
ALTER TABLE `nf_votes`
  ADD CONSTRAINT `nf_votes_ibfk_1` FOREIGN KEY (`module`) REFERENCES `nf_settings_addons` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `nf_votes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `nf_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `nf_widgets`
--
ALTER TABLE `nf_widgets`
  ADD CONSTRAINT `nf_widgets_ibfk_1` FOREIGN KEY (`widget`) REFERENCES `nf_settings_addons` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;
SET FOREIGN_KEY_CHECKS=1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
