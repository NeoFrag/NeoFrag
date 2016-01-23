INSERT INTO `nf_settings_addons` VALUES('addons', 'module', '1');
INSERT INTO `nf_settings_addons` VALUES('error', 'widget', '1');
ALTER TABLE `nf_settings_addons` CHANGE `enable` `is_enabled` ENUM('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0';
ALTER TABLE `nf_users` DROP `theme`;
ALTER TABLE `nf_settings_languages` DROP COLUMN `language_id`, DROP COLUMN `domain_extension`, DROP INDEX `code`, DROP PRIMARY KEY, ADD PRIMARY KEY (`code`);