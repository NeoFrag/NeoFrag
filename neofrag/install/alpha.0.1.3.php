<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class i_0_1_3 extends Install
{
	public function up()
	{
		$this->db	->execute('INSERT IGNORE INTO `nf_settings_languages` VALUES(NULL, \'en\', \'.com\', \'English\', \'gb.png\', 2)')
					->execute('INSERT IGNORE INTO `nf_settings_languages` VALUES(NULL, \'de\', \'.de\', \'Deutsch\', \'de.png\', 3)')
					->execute('INSERT IGNORE INTO `nf_settings_languages` VALUES(NULL, \'es\', \'.es\', \'Español\', \'es.png\', 4)')
					->execute('INSERT IGNORE INTO `nf_settings_languages` VALUES(NULL, \'it\', \'.it\', \'Italiano\', \'it.png\', 5)')
					->execute('INSERT IGNORE INTO `nf_settings_languages` VALUES(NULL, \'pt\', \'.pt\', \'Português\', \'pt.png\', 6)');
	}
}
