<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Widgets\Gallery;

use NF\NeoFrag\Addons\Widget;

class Gallery extends Widget
{
	protected function __info()
	{
		return [
			'title'       => $this->lang('Galeries'),
			'description' => '',
			'link'        => 'https://neofr.ag',
			'author'      => 'Michaël BILCOT & Jérémy VALENTIN <contact@neofrag.com>',
			'license'     => 'LGPLv3 <https://neofr.ag/license>',
			'version'     => '1.0',
			'depends'     => [
				'neofrag' => 'Alpha 0.2'
			],
			'types'       => [
				'index'  => $this->lang('Liste des catégories'),
				'albums' => $this->lang('Albums d\'une catégorie'),
				'image'  => $this->lang('Image aléatoire'),
				'slider' => $this->lang('Slider d\'un album')
			]
		];
	}
}
