<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class i_0_1_2 extends Install
{
	public function up()
	{
		$this->db	->execute('SET FOREIGN_KEY_CHECKS=0')
					->execute('ALTER TABLE `nf_sessions` ADD `is_crawler` ENUM(\'0\',\'1\') NOT NULL DEFAULT \'0\' AFTER `user_id`')
					->execute('DROP TABLE IF EXISTS `nf_crawlers`')
					->execute('CREATE TABLE IF NOT EXISTS `nf_crawlers` (
						`name` varchar(100) NOT NULL,
						`path` varchar(100) NOT NULL,
						`date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
					) ENGINE=InnoDB DEFAULT CHARSET=utf8')
					->execute('ALTER TABLE `nf_teams_roles` ADD `order` smallint(6) unsigned NOT NULL AFTER `title`')
					->execute('ALTER TABLE `nf_teams` ADD `order` smallint(6) unsigned NOT NULL AFTER `name`')
					->execute('ALTER TABLE `nf_talks_messages` CHANGE `user_id` `user_id` INT(10) UNSIGNED NULL DEFAULT NULL')
					->execute('ALTER TABLE `nf_forum_messages` CHANGE `user_id` `user_id` INT(11) UNSIGNED NULL DEFAULT NULL')
					->execute('INSERT IGNORE INTO `nf_settings_addons` (`name`, `type`, `enable`) VALUES (\'access\', \'module\', \'1\')')
					->execute('DROP TABLE IF EXISTS `nf_access`')
					->execute('CREATE TABLE IF NOT EXISTS `nf_access` (
						`access_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
						`id` int(11) unsigned NOT NULL,
						`module` varchar(100) NOT NULL,
						`action` varchar(100) NOT NULL,
						PRIMARY KEY (`access_id`),
						UNIQUE KEY `module_id` (`id`,`module`,`action`),
						KEY `module` (`module`)
					) ENGINE=InnoDB  DEFAULT CHARSET=utf8')
					->execute('DROP TABLE IF EXISTS `nf_access_details`')
					->execute('CREATE TABLE IF NOT EXISTS `nf_access_details` (
						`access_id` int(11) unsigned NOT NULL,
						`entity` varchar(100) NOT NULL,
						`type` enum(\'group\',\'user\') NOT NULL DEFAULT \'group\',
						`authorized` enum(\'0\',\'1\') NOT NULL DEFAULT \'0\',
						PRIMARY KEY (`access_id`,`entity`,`type`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8')
					->execute('ALTER TABLE `nf_access`
						ADD CONSTRAINT `nf_access_ibfk_1` FOREIGN KEY (`module`) REFERENCES `nf_settings_addons` (`name`) ON DELETE CASCADE ON UPDATE CASCADE')
					->execute('ALTER TABLE `nf_access_details`
						ADD CONSTRAINT `nf_access_details_ibfk_1` FOREIGN KEY (`access_id`) REFERENCES `nf_access` (`access_id`) ON DELETE CASCADE ON UPDATE CASCADE')
					->execute('INSERT INTO `nf_access` SELECT * FROM `nf_permissions`')
					->execute('INSERT INTO `nf_access_details` SELECT * FROM `nf_permissions_details`')
					->execute('DROP TABLE IF EXISTS `nf_permissions`')
					->execute('DROP TABLE IF EXISTS `nf_permissions_details`')
					->execute('SET FOREIGN_KEY_CHECKS=1');
	}
}
