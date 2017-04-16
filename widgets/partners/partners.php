<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Widgets\Partners;

use NF\NeoFrag\Addons\Widget;

class Partners extends Widget
{
	protected function __info()
	{
		return [
			'title'       => 'Partenaires',
			'description' => '',
			'link'        => 'https://neofr.ag',
			'author'      => 'Michaël BILCOT & Jérémy VALENTIN <contact@neofrag.com>',
			'license'     => 'LGPLv3 <https://neofr.ag/license>',
			'version'     => '1.0',
			'depends'     => [
				'neofrag' => 'Alpha 0.2'
			],
			'types'       => [
				'index'  => 'Affichage horizontal en slider',
				'column' => 'Affichage simple en colonne'
			]
		];
	}
}
