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
-- Structure de la table `nf_comments`
--

DROP TABLE IF EXISTS `nf_comments`;
CREATE TABLE IF NOT EXISTS `nf_comments` (
  `comment_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) unsigned DEFAULT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `module_id` int(11) unsigned NOT NULL,
  `module` varchar(100) NOT NULL,
  `content` text,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`comment_id`),
  KEY `parent_id` (`parent_id`),
  KEY `user_id` (`user_id`),
  KEY `module` (`module`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nf_dispositions`
--

DROP TABLE IF EXISTS `nf_dispositions`;
CREATE TABLE IF NOT EXISTS `nf_dispositions` (
  `disposition_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `theme` varchar(100) NOT NULL,
  `page` varchar(100) NOT NULL,
  `zone` int(11) unsigned NOT NULL,
  `disposition` text NOT NULL,
  PRIMARY KEY (`disposition_id`),
  UNIQUE KEY `theme` (`theme`,`page`,`zone`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Contenu de la table `nf_dispositions`
--

INSERT INTO `nf_dispositions` VALUES(1, 'default', '*', 0, 'a:2:{i:0;O:3:"Row":2:{s:5:"style";s:9:"row-white";s:4:"cols";a:1:{i:0;O:3:"Col":2:{s:10:"\0Col\0_size";s:9:"col-md-12";s:7:"widgets";a:1:{i:0;O:11:"Widget_View":1:{s:9:"widget_id";i:9;}}}}}i:1;O:3:"Row":2:{s:5:"style";s:9:"row-light";s:4:"cols";a:2:{i:0;O:3:"Col":2:{s:10:"\0Col\0_size";s:8:"col-md-8";s:7:"widgets";a:1:{i:0;O:11:"Widget_View":1:{s:9:"widget_id";i:10;}}}i:1;O:3:"Col":2:{s:10:"\0Col\0_size";s:8:"col-md-4";s:7:"widgets";a:4:{i:0;O:11:"Widget_View":2:{s:9:"widget_id";i:11;s:5:"style";s:9:"panel-red";}i:1;O:11:"Widget_View":2:{s:9:"widget_id";i:12;s:5:"style";s:10:"panel-dark";}i:3;O:11:"Widget_View":2:{s:9:"widget_id";i:16;s:5:"style";s:13:"panel-default";}i:2;O:11:"Widget_View":2:{s:9:"widget_id";i:15;s:5:"style";s:13:"panel-default";}}}}}}');
INSERT INTO `nf_dispositions` VALUES(2, 'default', '*', 1, 'a:1:{i:0;O:3:"Row":2:{s:5:"style";s:11:"row-default";s:4:"cols";a:3:{i:0;O:3:"Col":2:{s:10:"\0Col\0_size";s:8:"col-md-4";s:7:"widgets";a:1:{i:0;O:11:"Widget_View":2:{s:9:"widget_id";i:6;s:5:"style";s:13:"panel-default";}}}i:1;O:3:"Col":2:{s:10:"\0Col\0_size";s:8:"col-md-4";s:7:"widgets";a:1:{i:0;O:11:"Widget_View":2:{s:9:"widget_id";i:7;s:5:"style";s:10:"panel-dark";}}}i:2;O:3:"Col":2:{s:10:"\0Col\0_size";s:8:"col-md-4";s:7:"widgets";a:1:{i:0;O:11:"Widget_View":2:{s:9:"widget_id";i:8;s:5:"style";s:9:"panel-red";}}}}}}');
INSERT INTO `nf_dispositions` VALUES(3, 'default', '*', 2, 'a:0:{}');
INSERT INTO `nf_dispositions` VALUES(4, 'default', '*', 3, 'a:2:{i:0;O:3:"Row":2:{s:5:"style";s:11:"row-default";s:4:"cols";a:1:{i:0;O:3:"Col":2:{s:10:"\0Col\0_size";s:9:"col-md-12";s:7:"widgets";a:1:{i:0;O:11:"Widget_View":1:{s:9:"widget_id";i:54;}}}}}i:1;O:3:"Row":2:{s:5:"style";s:9:"row-black";s:4:"cols";a:2:{i:0;O:3:"Col":2:{s:10:"\0Col\0_size";s:8:"col-md-8";s:7:"widgets";a:1:{i:0;O:11:"Widget_View":1:{s:9:"widget_id";i:2;}}}i:1;O:3:"Col":2:{s:10:"\0Col\0_size";s:8:"col-md-4";s:7:"widgets";a:1:{i:0;O:11:"Widget_View":1:{s:9:"widget_id";i:3;}}}}}}');
INSERT INTO `nf_dispositions` VALUES(5, 'default', '*', 4, 'a:1:{i:0;O:3:"Row":2:{s:5:"style";s:11:"row-default";s:4:"cols";a:2:{i:0;O:3:"Col":2:{s:10:"\0Col\0_size";s:8:"col-md-8";s:7:"widgets";a:1:{i:0;O:11:"Widget_View":1:{s:9:"widget_id";i:13;}}}i:1;O:3:"Col":2:{s:10:"\0Col\0_size";s:8:"col-md-4";s:7:"widgets";a:1:{i:0;O:11:"Widget_View":1:{s:9:"widget_id";i:14;}}}}}}');
INSERT INTO `nf_dispositions` VALUES(6, 'default', '*', 5, 'a:1:{i:0;O:3:"Row":2:{s:5:"style";s:11:"row-default";s:4:"cols";a:1:{i:0;O:3:"Col":2:{s:10:"\0Col\0_size";s:9:"col-md-12";s:7:"widgets";a:1:{i:0;O:11:"Widget_View":2:{s:9:"widget_id";i:17;s:5:"style";s:10:"panel-dark";}}}}}}');
INSERT INTO `nf_dispositions` VALUES(7, 'default', '/', 3, 'a:3:{i:0;O:3:"Row":2:{s:5:"style";s:11:"row-default";s:4:"cols";a:1:{i:0;O:3:"Col":2:{s:10:"\0Col\0_size";s:9:"col-md-12";s:7:"widgets";a:1:{i:0;O:11:"Widget_View":1:{s:9:"widget_id";i:55;}}}}}i:1;O:3:"Row":2:{s:5:"style";s:9:"row-black";s:4:"cols";a:2:{i:0;O:3:"Col":2:{s:10:"\0Col\0_size";s:8:"col-md-8";s:7:"widgets";a:1:{i:0;O:11:"Widget_View":1:{s:9:"widget_id";i:20;}}}i:1;O:3:"Col":2:{s:10:"\0Col\0_size";s:8:"col-md-4";s:7:"widgets";a:1:{i:0;O:11:"Widget_View":1:{s:9:"widget_id";i:21;}}}}}i:2;O:3:"Row":2:{s:5:"style";s:11:"row-default";s:4:"cols";a:1:{i:0;O:3:"Col":2:{s:10:"\0Col\0_size";s:9:"col-md-12";s:7:"widgets";a:1:{i:0;O:11:"Widget_View":1:{s:9:"widget_id";i:22;}}}}}}');
INSERT INTO `nf_dispositions` VALUES(8, 'default', 'forum/*', 0, 'a:2:{i:0;O:3:"Row":2:{s:5:"style";s:9:"row-white";s:4:"cols";a:1:{i:0;O:3:"Col":2:{s:10:"\0Col\0_size";s:9:"col-md-12";s:7:"widgets";a:1:{i:0;O:11:"Widget_View":1:{s:9:"widget_id";i:23;}}}}}i:1;O:3:"Row":2:{s:5:"style";s:9:"row-light";s:4:"cols";a:1:{i:0;O:3:"Col":2:{s:10:"\0Col\0_size";s:9:"col-md-12";s:7:"widgets";a:1:{i:0;O:11:"Widget_View":1:{s:9:"widget_id";i:24;}}}}}}');
INSERT INTO `nf_dispositions` VALUES(9, 'default', 'news/_news/*', 0, 'a:2:{i:0;O:3:"Row":2:{s:5:"style";s:9:"row-white";s:4:"cols";a:1:{i:0;O:3:"Col":2:{s:10:"\0Col\0_size";s:9:"col-md-12";s:7:"widgets";a:1:{i:0;O:11:"Widget_View":1:{s:9:"widget_id";i:31;}}}}}i:1;O:3:"Row":2:{s:5:"style";s:9:"row-light";s:4:"cols";a:1:{i:0;O:3:"Col":2:{s:10:"\0Col\0_size";s:9:"col-md-12";s:7:"widgets";a:1:{i:0;O:11:"Widget_View":1:{s:9:"widget_id";i:32;}}}}}}');
INSERT INTO `nf_dispositions` VALUES(10, 'default', 'contact/*', 0, 'a:2:{i:0;O:3:"Row":2:{s:5:"style";s:9:"row-white";s:4:"cols";a:1:{i:0;O:3:"Col":2:{s:10:"\0Col\0_size";s:9:"col-md-12";s:7:"widgets";a:1:{i:0;O:11:"Widget_View":1:{s:9:"widget_id";i:37;}}}}}i:1;O:3:"Row":2:{s:5:"style";s:9:"row-light";s:4:"cols";a:2:{i:1;O:3:"Col":2:{s:10:"\0Col\0_size";s:8:"col-md-4";s:7:"widgets";a:4:{i:0;O:11:"Widget_View":2:{s:9:"widget_id";i:39;s:5:"style";s:9:"panel-red";}i:1;O:11:"Widget_View":2:{s:9:"widget_id";i:40;s:5:"style";s:10:"panel-dark";}i:3;O:11:"Widget_View":2:{s:9:"widget_id";i:41;s:5:"style";s:13:"panel-default";}i:2;O:11:"Widget_View":2:{s:9:"widget_id";i:42;s:5:"style";s:13:"panel-default";}}}i:0;O:3:"Col":2:{s:10:"\0Col\0_size";s:8:"col-md-8";s:7:"widgets";a:1:{i:0;O:11:"Widget_View":1:{s:9:"widget_id";i:38;}}}}}}');
INSERT INTO `nf_dispositions` VALUES(12, 'default', 'forum/*', 2, 'a:1:{i:0;O:3:"Row":2:{s:5:"style";s:9:"row-light";s:4:"cols";a:2:{i:0;O:3:"Col":2:{s:10:"\0Col\0_size";s:8:"col-md-4";s:7:"widgets";a:1:{i:0;O:11:"Widget_View":2:{s:9:"widget_id";i:52;s:5:"style";s:9:"panel-red";}}}i:1;O:3:"Col":2:{s:10:"\0Col\0_size";s:8:"col-md-8";s:7:"widgets";a:1:{i:0;O:11:"Widget_View":2:{s:9:"widget_id";i:53;s:5:"style";s:10:"panel-dark";}}}}}}');
INSERT INTO `nf_dispositions` VALUES(14, 'default', 'user/*', 0, 'a:2:{i:0;O:3:"Row":2:{s:5:"style";s:9:"row-white";s:4:"cols";a:1:{i:0;O:3:"Col":2:{s:10:"\0Col\0_size";s:9:"col-md-12";s:7:"widgets";a:1:{i:0;O:11:"Widget_View":1:{s:9:"widget_id";i:58;}}}}}i:1;O:3:"Row":2:{s:5:"style";s:9:"row-light";s:4:"cols";a:1:{i:0;O:3:"Col":2:{s:10:"\0Col\0_size";s:9:"col-md-12";s:7:"widgets";a:1:{i:0;O:11:"Widget_View":1:{s:9:"widget_id";i:59;}}}}}}');

-- --------------------------------------------------------

--
-- Structure de la table `nf_files`
--

DROP TABLE IF EXISTS `nf_files`;
CREATE TABLE IF NOT EXISTS `nf_files` (
  `file_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `path` varchar(100) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`file_id`),
  UNIQUE KEY `path` (`path`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Contenu de la table `nf_files`
--

INSERT INTO `nf_files` VALUES(1, 1, 'Sans-titre-2.jpg', './upload/news/categories/ubfuejdfooirqya0pyltfeklja4ew4sn.jpg', '2015-05-29 22:34:16');

-- --------------------------------------------------------

--
-- Structure de la table `nf_forum`
--

DROP TABLE IF EXISTS `nf_forum`;
CREATE TABLE IF NOT EXISTS `nf_forum` (
  `forum_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) unsigned NOT NULL,
  `is_subforum` enum('0','1') NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL,
  `description` varchar(100) NOT NULL,
  `order` smallint(6) unsigned NOT NULL,
  `count_topics` int(11) unsigned NOT NULL DEFAULT '0',
  `count_messages` int(11) unsigned NOT NULL DEFAULT '0',
  `last_message_id` int(11) unsigned DEFAULT NULL,
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
  `category_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `order` smallint(6) unsigned NOT NULL,
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
  `message_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `topic_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `message` text,
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
  `topic_id` int(11) unsigned NOT NULL,
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
  `user_id` int(11) unsigned NOT NULL,
  `forum_id` int(11) unsigned NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`,`forum_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nf_forum_topics`
--

DROP TABLE IF EXISTS `nf_forum_topics`;
CREATE TABLE IF NOT EXISTS `nf_forum_topics` (
  `topic_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `forum_id` int(11) unsigned NOT NULL,
  `message_id` int(11) unsigned NOT NULL,
  `title` varchar(100) NOT NULL,
  `status` enum('-2','-1','0','1') NOT NULL DEFAULT '0',
  `views` int(11) unsigned NOT NULL,
  `count_messages` int(11) unsigned NOT NULL,
  `last_message_id` int(11) unsigned DEFAULT NULL,
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
  `topic_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
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
  `topic_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`topic_id`,`user_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nf_forum_url`
--

DROP TABLE IF EXISTS `nf_forum_url`;
CREATE TABLE IF NOT EXISTS `nf_forum_url` (
  `forum_id` int(11) unsigned NOT NULL,
  `url` varchar(100) NOT NULL,
  `redirects` int(11) unsigned NOT NULL,
  PRIMARY KEY (`forum_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nf_gallery`
--

DROP TABLE IF EXISTS `nf_gallery`;
CREATE TABLE IF NOT EXISTS `nf_gallery` (
  `gallery_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(11) unsigned NOT NULL,
  `image_id` int(11) unsigned DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `published` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`gallery_id`),
  KEY `category_id` (`category_id`),
  KEY `image_id` (`image_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nf_gallery_categories`
--

DROP TABLE IF EXISTS `nf_gallery_categories`;
CREATE TABLE IF NOT EXISTS `nf_gallery_categories` (
  `category_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `image_id` int(11) unsigned DEFAULT NULL,
  `icon_id` int(11) unsigned DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`category_id`),
  KEY `image_id` (`image_id`),
  KEY `icon_id` (`icon_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nf_gallery_categories_lang`
--

DROP TABLE IF EXISTS `nf_gallery_categories_lang`;
CREATE TABLE IF NOT EXISTS `nf_gallery_categories_lang` (
  `category_id` int(11) unsigned NOT NULL,
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
  `image_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `thumbnail_file_id` int(11) unsigned NOT NULL,
  `original_file_id` int(11) unsigned NOT NULL,
  `file_id` int(11) unsigned NOT NULL,
  `gallery_id` int(11) unsigned NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `views` int(11) unsigned NOT NULL,
  PRIMARY KEY (`image_id`),
  KEY `file_id` (`file_id`),
  KEY `gallery_id` (`gallery_id`),
  KEY `original_file_id` (`original_file_id`),
  KEY `thumbnail_file_id` (`thumbnail_file_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nf_gallery_lang`
--

DROP TABLE IF EXISTS `nf_gallery_lang`;
CREATE TABLE IF NOT EXISTS `nf_gallery_lang` (
  `gallery_id` int(11) unsigned NOT NULL,
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
  `game_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) unsigned DEFAULT NULL,
  `image_id` int(11) unsigned DEFAULT NULL,
  `icon_id` int(11) unsigned DEFAULT NULL,
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
  `game_id` int(11) unsigned NOT NULL,
  `lang` varchar(5) NOT NULL,
  `title` varchar(100) NOT NULL,
  PRIMARY KEY (`game_id`,`lang`),
  KEY `lang` (`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nf_groups`
--

DROP TABLE IF EXISTS `nf_groups`;
CREATE TABLE IF NOT EXISTS `nf_groups` (
  `group_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `color` varchar(20) NOT NULL,
  `icon` varchar(20) NOT NULL,
  `auto` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Contenu de la table `nf_groups`
--

INSERT INTO `nf_groups` VALUES(1, 'admins', 'danger', 'fa-users', '1');
INSERT INTO `nf_groups` VALUES(2, 'members', 'success', 'fa-user', '1');
INSERT INTO `nf_groups` VALUES(3, 'visitors', 'info', '', '1');

-- --------------------------------------------------------

--
-- Structure de la table `nf_groups_lang`
--

DROP TABLE IF EXISTS `nf_groups_lang`;
CREATE TABLE IF NOT EXISTS `nf_groups_lang` (
  `group_id` int(11) unsigned NOT NULL,
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
  `news_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `image_id` int(11) unsigned DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `published` enum('0','1') NOT NULL DEFAULT '0',
  `views` int(11) unsigned NOT NULL,
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
  `category_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `image_id` int(11) unsigned DEFAULT NULL,
  `icon_id` int(11) unsigned DEFAULT NULL,
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
  `category_id` int(11) unsigned NOT NULL,
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
  `news_id` int(11) unsigned NOT NULL,
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
  `page_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
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
  `page_id` int(11) unsigned NOT NULL,
  `lang` varchar(5) NOT NULL,
  `title` varchar(100) NOT NULL,
  `subtitle` varchar(100) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`page_id`,`lang`),
  KEY `lang` (`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nf_permissions`
--

DROP TABLE IF EXISTS `nf_permissions`;
CREATE TABLE IF NOT EXISTS `nf_permissions` (
  `permission_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `addon_id` int(11) unsigned NOT NULL,
  `addon` varchar(100) NOT NULL,
  `action` varchar(100) NOT NULL,
  PRIMARY KEY (`permission_id`),
  UNIQUE KEY `module_id` (`addon_id`,`addon`,`action`),
  KEY `module` (`addon`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Contenu de la table `nf_permissions`
--

INSERT INTO `nf_permissions` VALUES(1, 1, 'talks', 'read');
INSERT INTO `nf_permissions` VALUES(2, 1, 'talks', 'write');
INSERT INTO `nf_permissions` VALUES(3, 1, 'talks', 'delete');
INSERT INTO `nf_permissions` VALUES(4, 2, 'talks', 'write');
INSERT INTO `nf_permissions` VALUES(5, 2, 'talks', 'delete');
INSERT INTO `nf_permissions` VALUES(6, 1, 'forum', 'category_write');
INSERT INTO `nf_permissions` VALUES(7, 1, 'forum', 'category_modify');
INSERT INTO `nf_permissions` VALUES(8, 1, 'forum', 'category_delete');
INSERT INTO `nf_permissions` VALUES(9, 1, 'forum', 'category_announce');
INSERT INTO `nf_permissions` VALUES(10, 1, 'forum', 'category_lock');

-- --------------------------------------------------------

--
-- Structure de la table `nf_permissions_details`
--

DROP TABLE IF EXISTS `nf_permissions_details`;
CREATE TABLE IF NOT EXISTS `nf_permissions_details` (
  `permission_id` int(11) unsigned NOT NULL,
  `entity_id` varchar(100) NOT NULL,
  `type` enum('group','user') NOT NULL DEFAULT 'group',
  `authorized` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`permission_id`,`entity_id`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `nf_permissions_details`
--

INSERT INTO `nf_permissions_details` VALUES(1, 'admins', 'group', '1');
INSERT INTO `nf_permissions_details` VALUES(2, 'admins', 'group', '1');
INSERT INTO `nf_permissions_details` VALUES(3, 'admins', 'group', '1');
INSERT INTO `nf_permissions_details` VALUES(4, 'members', 'group', '1');
INSERT INTO `nf_permissions_details` VALUES(5, 'admins', 'group', '1');
INSERT INTO `nf_permissions_details` VALUES(6, 'members', 'group', '1');
INSERT INTO `nf_permissions_details` VALUES(7, 'admins', 'group', '1');
INSERT INTO `nf_permissions_details` VALUES(8, 'admins', 'group', '1');
INSERT INTO `nf_permissions_details` VALUES(9, 'admins', 'group', '1');
INSERT INTO `nf_permissions_details` VALUES(10, 'admins', 'group', '1');

-- --------------------------------------------------------

--
-- Structure de la table `nf_search_keywords`
--

DROP TABLE IF EXISTS `nf_search_keywords`;
CREATE TABLE IF NOT EXISTS `nf_search_keywords` (
  `keyword` varchar(100) NOT NULL,
  `count` int(11) unsigned NOT NULL,
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
  `user_id` int(11) unsigned DEFAULT NULL,
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
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `session_id` varchar(32) DEFAULT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `ip_address` varchar(39) NOT NULL,
  `host_name` varchar(100) NOT NULL,
  `referer` varchar(100) NOT NULL,
  `user_agent` varchar(100) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `session_id` (`session_id`,`user_id`,`ip_address`,`host_name`,`referer`,`user_agent`,`date`),
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
INSERT INTO `nf_settings` VALUES('nf_cookie_name', '', '', 'nf_session', 'string');
INSERT INTO `nf_settings` VALUES('nf_debug', '', '', '0', 'int');
INSERT INTO `nf_settings` VALUES('nf_default_page', 'default', '', 'news', 'string');
INSERT INTO `nf_settings` VALUES('nf_default_theme', 'default', '', 'default', 'string');
INSERT INTO `nf_settings` VALUES('nf_description', 'default', '', 'ALPHA 0.1', 'string');
INSERT INTO `nf_settings` VALUES('nf_humans_txt', '', '', '/* TEAM */\n	NeoFrag CMS for gamers\n	Contact: contact [at] neofrag.fr\n	Twitter: @NeoFragCMS\n	From: France\n\n	Developper: Micha&euml;l BILCOT\n	Contact: michael.bilcot [at] neofrag.fr\n	Twitter: @NeoFragCMS\n	From: Paris, France\n\n	Designer: J&eacute;r&eacute;my VALENTIN\n	Contact: jeremy.valentin [at] neofrag.fr\n	Twitter: @NeoFragCMS\n	From: Caen, France', 'string');
INSERT INTO `nf_settings` VALUES('nf_name', 'default', '', 'NeoFrag CMS', 'string');
INSERT INTO `nf_settings` VALUES('nf_robots_txt', '', '', 'User-agent: *\r\nDisallow:', 'string');
INSERT INTO `nf_settings` VALUES('default_background_attachment', '', '', 'scroll', 'string');
INSERT INTO `nf_settings` VALUES('default_background_color', '', '', '#141d26', 'string');
INSERT INTO `nf_settings` VALUES('default_background_position', '', '', 'center top', 'string');
INSERT INTO `nf_settings` VALUES('default_background_repeat', '', '', 'no-repeat', 'string');

-- --------------------------------------------------------

--
-- Structure de la table `nf_settings_addons`
--

DROP TABLE IF EXISTS `nf_settings_addons`;
CREATE TABLE IF NOT EXISTS `nf_settings_addons` (
  `name` varchar(100) NOT NULL,
  `type` enum('module','theme','widget') NOT NULL,
  `enable` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`name`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `nf_settings_addons`
--

INSERT INTO `nf_settings_addons` VALUES('admin', 'module', '1');
INSERT INTO `nf_settings_addons` VALUES('breadcrumb', 'widget', '1');
INSERT INTO `nf_settings_addons` VALUES('comments', 'module', '1');
INSERT INTO `nf_settings_addons` VALUES('contact', 'module', '1');
INSERT INTO `nf_settings_addons` VALUES('default', 'theme', '1');
INSERT INTO `nf_settings_addons` VALUES('error', 'module', '1');
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
INSERT INTO `nf_settings_addons` VALUES('navigation', 'widget', '1');
INSERT INTO `nf_settings_addons` VALUES('news', 'module', '1');
INSERT INTO `nf_settings_addons` VALUES('news', 'widget', '1');
INSERT INTO `nf_settings_addons` VALUES('pages', 'module', '1');
INSERT INTO `nf_settings_addons` VALUES('search', 'module', '1');
INSERT INTO `nf_settings_addons` VALUES('settings', 'module', '1');
INSERT INTO `nf_settings_addons` VALUES('slider', 'widget', '1');
INSERT INTO `nf_settings_addons` VALUES('talks', 'module', '1');
INSERT INTO `nf_settings_addons` VALUES('talks', 'widget', '1');
INSERT INTO `nf_settings_addons` VALUES('teams', 'module', '1');
INSERT INTO `nf_settings_addons` VALUES('teams', 'widget', '1');
INSERT INTO `nf_settings_addons` VALUES('user', 'module', '1');
INSERT INTO `nf_settings_addons` VALUES('user', 'widget', '1');

-- --------------------------------------------------------

--
-- Structure de la table `nf_settings_languages`
--

DROP TABLE IF EXISTS `nf_settings_languages`;
CREATE TABLE IF NOT EXISTS `nf_settings_languages` (
  `language_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(5) NOT NULL,
  `domain_extension` varchar(6) NOT NULL,
  `name` varchar(100) NOT NULL,
  `flag` varchar(100) NOT NULL,
  `order` smallint(6) unsigned NOT NULL,
  PRIMARY KEY (`language_id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Contenu de la table `nf_settings_languages`
--

INSERT INTO `nf_settings_languages` VALUES(1, 'fr', '.fr', 'Français', 'fr.png', 1);

-- --------------------------------------------------------

--
-- Structure de la table `nf_settings_smileys`
--

DROP TABLE IF EXISTS `nf_settings_smileys`;
CREATE TABLE IF NOT EXISTS `nf_settings_smileys` (
  `smiley_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `file_id` int(11) unsigned NOT NULL,
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
  `talk_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
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
  `message_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `talk_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `message` text,
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
  `team_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `game_id` int(11) unsigned NOT NULL,
  `image_id` int(11) unsigned DEFAULT NULL,
  `icon_id` int(11) unsigned DEFAULT NULL,
  `name` varchar(100) NOT NULL,
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
  `team_id` int(11) unsigned NOT NULL,
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
  `role_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nf_teams_users`
--

DROP TABLE IF EXISTS `nf_teams_users`;
CREATE TABLE IF NOT EXISTS `nf_teams_users` (
  `team_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `role_id` int(10) unsigned DEFAULT NULL,
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
  `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(34) NOT NULL,
  `salt` varchar(32) NOT NULL,
  `email` varchar(100) NOT NULL,
  `registration_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_activity_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `admin` enum('0','1') NOT NULL DEFAULT '0',
  `theme` varchar(100) DEFAULT NULL,
  `language` varchar(5) DEFAULT NULL,
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

INSERT INTO `nf_users` VALUES(1, 'admin', '$H$92EwygSmbdXunbIvoo/V91MWcnHqzX/', '', 'noreply@neofrag.com', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, '1', NULL, NULL, '0');

-- --------------------------------------------------------

--
-- Structure de la table `nf_users_groups`
--

DROP TABLE IF EXISTS `nf_users_groups`;
CREATE TABLE IF NOT EXISTS `nf_users_groups` (
  `user_id` int(11) unsigned NOT NULL,
  `group_id` int(11) unsigned NOT NULL,
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
  `user_id` int(11) unsigned NOT NULL,
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
  `message_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`message_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nf_users_messages_recipients`
--

DROP TABLE IF EXISTS `nf_users_messages_recipients`;
CREATE TABLE IF NOT EXISTS `nf_users_messages_recipients` (
  `user_id` int(11) unsigned NOT NULL,
  `message_id` int(11) unsigned NOT NULL,
  `read` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`,`message_id`),
  KEY `message_id` (`message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nf_users_messages_replies`
--

DROP TABLE IF EXISTS `nf_users_messages_replies`;
CREATE TABLE IF NOT EXISTS `nf_users_messages_replies` (
  `reply_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `message_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `content` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `read` enum('0','1') NOT NULL DEFAULT '0',
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
  `user_id` int(11) unsigned NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `avatar` int(11) unsigned DEFAULT NULL,
  `signature` text NOT NULL,
  `date_of_birth` date NOT NULL,
  `sex` enum('male','female') DEFAULT NULL,
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
  `module_id` int(11) unsigned NOT NULL,
  `module` varchar(100) NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
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
  `widget_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `widget` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `settings` text,
  PRIMARY KEY (`widget_id`),
  KEY `widget_name` (`widget`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Contenu de la table `nf_widgets`
--

INSERT INTO `nf_widgets` VALUES(1, 'talks', 'index', NULL, 'a:1:{s:7:"talk_id";s:1:"1";}');
INSERT INTO `nf_widgets` VALUES(2, 'navigation', 'index', NULL, 'a:2:{s:5:"links";a:7:{i:0;a:2:{s:5:"title";s:7:"Accueil";s:3:"url";s:10:"index.html";}i:1;a:2:{s:5:"title";s:17:"Actualit&eacute;s";s:3:"url";s:9:"news.html";}i:2;a:2:{s:5:"title";s:5:"Forum";s:3:"url";s:10:"forum.html";}i:3;a:2:{s:5:"title";s:14:"&Eacute;quipes";s:3:"url";s:10:"teams.html";}i:4;a:2:{s:5:"title";s:7:"Membres";s:3:"url";s:12:"members.html";}i:5;a:2:{s:5:"title";s:7:"Contact";s:3:"url";s:12:"contact.html";}i:6;a:2:{s:5:"title";s:7:"Galerie";s:3:"url";s:12:"gallery.html";}}s:7:"display";b:1;}');
INSERT INTO `nf_widgets` VALUES(3, 'user', 'index_mini', NULL, NULL);
INSERT INTO `nf_widgets` VALUES(6, 'forum', 'topics', NULL, NULL);
INSERT INTO `nf_widgets` VALUES(7, 'news', 'index', NULL, NULL);
INSERT INTO `nf_widgets` VALUES(8, 'members', 'index', NULL, NULL);
INSERT INTO `nf_widgets` VALUES(9, 'breadcrumb', 'index', NULL, NULL);
INSERT INTO `nf_widgets` VALUES(10, 'module', 'index', NULL, NULL);
INSERT INTO `nf_widgets` VALUES(11, 'members', 'online', NULL, NULL);
INSERT INTO `nf_widgets` VALUES(12, 'user', 'index', NULL, NULL);
INSERT INTO `nf_widgets` VALUES(13, 'navigation', 'index', NULL, 'a:2:{s:5:"links";a:4:{i:0;a:2:{s:5:"title";s:8:"Facebook";s:3:"url";s:1:"#";}i:1;a:2:{s:5:"title";s:7:"Twitter";s:3:"url";s:1:"#";}i:2;a:2:{s:5:"title";s:6:"Origin";s:3:"url";s:1:"#";}i:3;a:2:{s:5:"title";s:5:"Steam";s:3:"url";s:1:"#";}}s:7:"display";b:1;}');
INSERT INTO `nf_widgets` VALUES(14, 'members', 'online_mini', NULL, NULL);
INSERT INTO `nf_widgets` VALUES(15, 'talks', 'index', NULL, 'a:1:{s:7:"talk_id";s:1:"2";}');
INSERT INTO `nf_widgets` VALUES(16, 'news', 'categories', NULL, NULL);
INSERT INTO `nf_widgets` VALUES(17, 'html', 'index', NULL, 'a:1:{s:7:"content";s:99:"[center]Propulsé par [url=http://www.neofrag.fr]NeoFrag CMS[/url]﻿ version Alpha 0.1﻿[/center]";}');
INSERT INTO `nf_widgets` VALUES(20, 'navigation', 'index', NULL, 'a:2:{s:5:"links";a:7:{i:0;a:2:{s:5:"title";s:7:"Accueil";s:3:"url";s:10:"index.html";}i:1;a:2:{s:5:"title";s:17:"Actualit&eacute;s";s:3:"url";s:9:"news.html";}i:2;a:2:{s:5:"title";s:5:"Forum";s:3:"url";s:10:"forum.html";}i:3;a:2:{s:5:"title";s:14:"&Eacute;quipes";s:3:"url";s:10:"teams.html";}i:4;a:2:{s:5:"title";s:7:"Membres";s:3:"url";s:12:"members.html";}i:5;a:2:{s:5:"title";s:7:"Contact";s:3:"url";s:12:"contact.html";}i:6;a:2:{s:5:"title";s:7:"Galerie";s:3:"url";s:12:"gallery.html";}}s:7:"display";b:1;}');
INSERT INTO `nf_widgets` VALUES(21, 'user', 'index_mini', NULL, NULL);
INSERT INTO `nf_widgets` VALUES(22, 'slider', 'index', NULL, NULL);
INSERT INTO `nf_widgets` VALUES(23, 'breadcrumb', 'index', NULL, NULL);
INSERT INTO `nf_widgets` VALUES(24, 'module', 'index', NULL, NULL);
INSERT INTO `nf_widgets` VALUES(31, 'breadcrumb', 'index', NULL, NULL);
INSERT INTO `nf_widgets` VALUES(32, 'module', 'index', NULL, NULL);
INSERT INTO `nf_widgets` VALUES(37, 'breadcrumb', 'index', NULL, NULL);
INSERT INTO `nf_widgets` VALUES(38, 'module', 'index', NULL, NULL);
INSERT INTO `nf_widgets` VALUES(39, 'members', 'online', NULL, NULL);
INSERT INTO `nf_widgets` VALUES(40, 'user', 'index', NULL, NULL);
INSERT INTO `nf_widgets` VALUES(41, 'news', 'categories', NULL, NULL);
INSERT INTO `nf_widgets` VALUES(42, 'talks', 'index', NULL, 'a:1:{s:7:"talk_id";s:1:"2";}');
INSERT INTO `nf_widgets` VALUES(52, 'forum', 'statistics', NULL, NULL);
INSERT INTO `nf_widgets` VALUES(53, 'forum', 'activity', NULL, NULL);
INSERT INTO `nf_widgets` VALUES(54, 'header', 'index', NULL, 'a:5:{s:5:"align";s:11:"text-center";s:5:"title";s:0:"";s:11:"description";s:0:"";s:11:"color-title";s:0:"";s:17:"color-description";s:7:"#DC351E";}');
INSERT INTO `nf_widgets` VALUES(55, 'header', 'index', NULL, 'a:5:{s:5:"align";s:11:"text-center";s:5:"title";s:0:"";s:11:"description";s:0:"";s:11:"color-title";s:0:"";s:17:"color-description";s:7:"#DC351E";}');
INSERT INTO `nf_widgets` VALUES(58, 'breadcrumb', 'index', NULL, NULL);
INSERT INTO `nf_widgets` VALUES(59, 'module', 'index', NULL, NULL);

--
-- Contraintes pour les tables exportées
--

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
  ADD CONSTRAINT `nf_gallery_images_ibfk_4` FOREIGN KEY (`original_file_id`) REFERENCES `nf_files` (`file_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `nf_gallery_images_ibfk_1` FOREIGN KEY (`file_id`) REFERENCES `nf_files` (`file_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `nf_gallery_images_ibfk_2` FOREIGN KEY (`gallery_id`) REFERENCES `nf_gallery` (`gallery_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `nf_gallery_images_ibfk_3` FOREIGN KEY (`thumbnail_file_id`) REFERENCES `nf_files` (`file_id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
-- Contraintes pour la table `nf_permissions`
--
ALTER TABLE `nf_permissions`
  ADD CONSTRAINT `nf_permissions_ibfk_1` FOREIGN KEY (`addon`) REFERENCES `nf_settings_addons` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `nf_permissions_details`
--
ALTER TABLE `nf_permissions_details`
  ADD CONSTRAINT `nf_permissions_details_ibfk_1` FOREIGN KEY (`permission_id`) REFERENCES `nf_permissions` (`permission_id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
  ADD CONSTRAINT `nf_users_messages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `nf_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
