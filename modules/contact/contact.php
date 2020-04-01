<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Contact;

use NF\NeoFrag\Addons\Module;

class Contact extends Module
{
	protected function __info()
	{
		return [
			'title'       => $this->lang('Contact'),
			'description' => '',
			'icon'        => 'far fa-envelope',
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
