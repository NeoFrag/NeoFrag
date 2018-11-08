<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Widgets\Awards;

use NF\NeoFrag\Addons\Widget;

class Awards extends Widget
{
	protected function __info()
	{
		return [
			'title'       => 'Palmarès',
			'description' => '',
			'link'        => 'https://neofr.ag',
			'author'      => 'Michaël BILCOT & Jérémy VALENTIN <contact@neofrag.com>',
			'license'     => 'LGPLv3 <https://neofr.ag/license>',
			'version'     => '1.0',
			'types'       => [
				'index'     => 'Derniers palmarès',
				'best_team' => 'Équipe la plus récompensée',
				'best_game' => 'Jeu le plus récompensé'
			]
		];
	}
}
