<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Widgets\News;

use NF\NeoFrag\Addons\Widget;

class News extends Widget
{
	protected function __info()
	{
		return [
			'title'       => $this->lang('Actualités'),
			'description' => '',
			'link'        => 'https://neofr.ag',
			'author'      => 'Michaël BILCOT & Jérémy VALENTIN <contact@neofrag.com>',
			'license'     => 'LGPLv3 <https://neofr.ag/license>',
			'version'     => '1.0',
			'depends'     => [
				'neofrag' => 'Alpha 0.2'
			],
			'types'       => [
				'index'      => $this->lang('Actualités récentes'),
				'categories' => $this->lang('Catégories'),
				'tags'       => $this->lang('Tags')
			]
		];
	}
}
