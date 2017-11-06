<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Widgets\Forum;

use NF\NeoFrag\Addons\Widget;

class Forum extends Widget
{
	protected function __info()
	{
		return [
			'title'       => $this->lang('Forum'),
			'description' => '',
			'link'        => 'https://neofr.ag',
			'author'      => 'Michaël BILCOT & Jérémy VALENTIN <contact@neofrag.com>',
			'license'     => 'LGPLv3 <https://neofr.ag/license>',
			'version'     => '1.0',
			'depends'     => [
				'neofrag' => 'Alpha 0.2'
			],
			'types'       => [
				'index'      => $this->lang('Derniers messages'),
				'topics'     => $this->lang('Derniers sujets'),
				'statistics' => $this->lang('Statistiques'),
				'activity'   => $this->lang('Activité')
			]
		];
	}
}
