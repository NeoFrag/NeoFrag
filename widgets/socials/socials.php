<?php
/**
 * https://neofr.ag
 * @author: Jérémy VALENTIN <jeremy.valentin@neofr.ag>
 */

namespace NF\Widgets\Socials;

use NF\NeoFrag\Addons\Widget;

class Socials extends Widget
{
	protected function __info()
	{
		return [
			'title'       => $this->lang('Réseaux sociaux'),
			'description' => '',
			'link'        => 'https://neofr.ag',
			'author'      => 'Jérémy VALENTIN <jeremy.valentin@neofr.ag>',
			'license'     => 'LGPLv3 <https://neofr.ag/license>',
			'version'     => '1.0',
			'depends'     => [
				'neofrag' => 'Alpha 0.2.2'
			]
		];
	}
}
