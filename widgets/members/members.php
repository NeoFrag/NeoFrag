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
			'title'       => $this->lang('Membres'),
			'description' => '',
			'link'        => 'https://neofr.ag',
			'author'      => 'Michaël BILCOT & Jérémy VALENTIN <contact@neofrag.com>',
			'license'     => 'LGPLv3 <https://neofr.ag/license>',
			'version'     => '1.0',
			'depends'     => [
				'neofrag' => 'Alpha 0.2'
			],
			'types'       => [
				'index'       => $this->lang('Derniers membres'),
				'online'      => $this->lang('Qui est en ligne ?'),
				'online_mini' => $this->lang('Qui est en ligne ? (mini)')
			]
		];
	}
}
