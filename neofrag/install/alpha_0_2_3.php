<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Install;

use NF\NeoFrag\Loadables\Install;

class Alpha_0_2_3 extends Install
{
	public function up()
	{
		$this->db()	->where('type_id', 5)
					->where('name',    ['battle_net', 'linkedin', 'twitch', 'twitter'])
					->update('nf_addon', 'name = CONCAT("_", name)');

		$this->db()->execute('ALTER TABLE `nf_forum_messages` CHANGE `message` `message` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL');
	}
}
