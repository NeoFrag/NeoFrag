<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class i_0_2 extends Install
{
	public function up()
	{
		$this->db->where('name', 'nf_debug')->delete('nf_settings');

		//Comment
		$this->db	->execute('ALTER TABLE `nf_comments` CHANGE `comment_id` `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT')
					->execute('RENAME TABLE `nf_comments` TO `nf_comment`');

		//File
		$this->db	->execute('ALTER TABLE `nf_files` CHANGE `file_id` `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT')
					->execute('RENAME TABLE `nf_files` TO `nf_file`');

		//User
		$this->db	->execute('ALTER TABLE `nf_users` CHANGE `user_id` `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT')
					->execute('RENAME TABLE `nf_users` TO `nf_user`')
					->execute('RENAME TABLE `nf_users_profiles` TO `nf_user_profile`');

		//Addon
		$this->db	->execute('CREATE TABLE `nf_addon_type` (
					  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
					  `name` varchar(100) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8')
					->execute('CREATE TABLE `nf_addon` (
					  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
					  `type_id` int(11) unsigned DEFAULT NULL,
					  `name` varchar(100) NOT NULL,
					  `data` text,
					  PRIMARY KEY (`id`),
					  UNIQUE KEY `name` (`name`,`type_id`),
					  KEY `type_id` (`type_id`),
					  CONSTRAINT `nf_addon_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `nf_addon_type` (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8');

		foreach ($this->db->from('nf_settings_addons')->get() as $addon)
		{

		}
	}
}
