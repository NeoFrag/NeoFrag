<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Widgets\Members;

use NF\NeoFrag\Addons\Widget;

class Members extends Widget
{
	protected function __info()
	{
		return [
			'title'       => $this->lang('members'),
			'description' => '',
			'link'        => 'https://neofr.ag',
			'author'      => 'Michaël BILCOT & Jérémy VALENTIN <contact@neofrag.com>',
			'license'     => 'LGPLv3 <https://neofr.ag/license>',
			'version'     => '1.0',
			'depends'     => [
				'neofrag' => 'Alpha 0.1.7'
			],
			'types'       => [
				'index'       => '{lang last_members}',
				'online'      => '{lang whos_online}',
				'online_mini' => '{lang whos_online_mini}'
			]
		];
	}
}
