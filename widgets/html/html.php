<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Widgets\Html;

use NF\NeoFrag\Addons\Widget;

class Html extends Widget
{
	protected function __info()
	{
		return [
			'title'       => $this->lang('free_content_html_code'),
			'description' => '',
			'link'        => 'https://neofr.ag',
			'author'      => 'Michaël BILCOT & Jérémy VALENTIN <contact@neofrag.com>',
			'license'     => 'LGPLv3 <https://neofr.ag/license>',
			'types'       => [
				'index' => '{lang free_content}',
				'html'  => '{lang html_code}'
			]
		];
	}
}
