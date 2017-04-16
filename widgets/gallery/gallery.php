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
			'title'       => $this->lang('galleries'),
			'description' => '',
			'link'        => 'https://neofr.ag',
			'author'      => 'Michaël BILCOT & Jérémy VALENTIN <contact@neofrag.com>',
			'license'     => 'LGPLv3 <https://neofr.ag/license>',
			'version'     => '1.0',
			'depends'     => [
				'neofrag' => 'Alpha 0.1.7'
			],
			'types'       => [
				'index'  => '{lang categories_list}',
				'albums' => '{lang albums_from_category}',
				'image'  => '{lang random_picture}',
				'slider' => '{lang album_slide}'
			]
		];
	}
}
