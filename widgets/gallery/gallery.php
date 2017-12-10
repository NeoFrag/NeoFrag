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
				'neofrag' => 'Alpha 0.2'
			],
			'types'       => [
				'index'  => $this->lang('categories_list'),
				'albums' => $this->lang('albums_from_category'),
				'image'  => $this->lang('random_picture'),
				'slider' => $this->lang('album_slide')
			]
		];
	}
}
