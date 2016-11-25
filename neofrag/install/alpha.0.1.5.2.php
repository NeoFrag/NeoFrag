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

class i_0_1_5_2 extends Install
{
	public function up()
	{
		$this->db	->execute('ALTER TABLE `nf_sessions_history` CHANGE `user_agent` `user_agent` VARCHAR(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL')
					->config('nf_cookie_name', 'session')
					->delete('nf_sessions');
	}
}

/*
NeoFrag Alpha 0.1.5.2
./neofrag/install/alpha.0.1.5.2.php
*/