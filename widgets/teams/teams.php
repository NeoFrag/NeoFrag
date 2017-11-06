<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Widgets\Teams;

use NF\NeoFrag\Addons\Widget;

class Teams extends Widget
{
	protected function __info()
	{
		return [
			'title'       => $this->lang('Équipes'),
			'description' => '',
			'link'        => 'https://neofr.ag',
			'author'      => 'Michaël BILCOT & Jérémy VALENTIN <contact@neofrag.com>',
			'license'     => 'LGPLv3 <https://neofr.ag/license>',
			'version'     => '1.0',
			'depends'     => [
				'neofrag' => 'Alpha 0.2'
			]
		];
	}
}
