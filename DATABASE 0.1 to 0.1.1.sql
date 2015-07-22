ALTER TABLE `nf_permissions_details` CHANGE `entity_id` `entity_id` VARCHAR(100) NOT NULL;
UPDATE `nf_permissions_details` SET `entity_id` = 'admins' WHERE `entity_id` = '1' AND `type` = 'group';
UPDATE `nf_permissions_details` SET `entity_id` = 'members' WHERE `entity_id` = '2' AND `type` = 'group';
INSERT INTO `nf_settings` VALUES('default_background_attachment', '', '', 'scroll', 'string');
INSERT INTO `nf_settings` VALUES('default_background_color', '', '', '#141d26', 'string');
INSERT INTO `nf_settings` VALUES('default_background_position', '', '', 'center top', 'string');
INSERT INTO `nf_settings` VALUES('default_background_repeat', '', '', 'no-repeat', 'string');
INSERT INTO `nf_settings_addons` VALUES('gallery', 'module', '1');
INSERT INTO `nf_settings_addons` VALUES('gallery', 'widget', '1');

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
