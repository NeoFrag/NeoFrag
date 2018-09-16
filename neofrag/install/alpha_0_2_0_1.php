<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Install;

use NF\NeoFrag\Loadables\Install;

class Alpha_0_2_0_1 extends Install
{
	public function up()
	{
		$this->db	->execute('ALTER TABLE `nf_session_history` CHANGE `auth` `auth` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL')
					->execute('ALTER TABLE `nf_talks_messages` CHANGE `message` `message` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL')
					->execute('ALTER TABLE `nf_user` CHANGE `language` `language` INT(10) UNSIGNED NULL DEFAULT NULL')
					->execute('ALTER TABLE `nf_user` ADD FOREIGN KEY (`language`) REFERENCES `nf_addon`(`id`) ON DELETE SET NULL ON UPDATE SET NULL');

		if (!$this->db->select('COUNT(*)')->from('INFORMATION_SCHEMA.COLUMNS')->where('TABLE_NAME', 'nf_user_profile')->where('COLUMN_NAME', ['cover', 'country', 'linkedin', 'github', 'instagram', 'twitch'])->row())
		{
			$this->db	->execute('ALTER TABLE `nf_user_profile` ADD `cover` INT(11) UNSIGNED NULL DEFAULT NULL AFTER `avatar`')
						->execute('ALTER TABLE `nf_user_profile` ADD `country` VARCHAR(100) NOT NULL AFTER `sex`')
						->execute('ALTER TABLE `nf_user_profile` ADD `linkedin` VARCHAR(100) NOT NULL AFTER `website`, ADD `github` VARCHAR(100) NOT NULL AFTER `linkedin`, ADD `instagram` VARCHAR(100) NOT NULL AFTER `github`, ADD `twitch` VARCHAR(100) NOT NULL AFTER `instagram`');
		}
	}
}
