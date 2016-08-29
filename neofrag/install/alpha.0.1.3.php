<?php if (!defined('NEOFRAG_CMS')) exit;
/**************************************************************************
Copyright © 2015 Michaël BILCOT & Jérémy VALENTIN

This file is part of NeoFrag.

NeoFrag is free software: you can redistribute it and/or modify
it under the terms of the GNU Lesser General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

NeoFrag is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with NeoFrag. If not, see <http://www.gnu.org/licenses/>.
**************************************************************************/

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

/*
NeoFrag Alpha 0.1.5
./neofrag/install/alpha.0.1.php
*/