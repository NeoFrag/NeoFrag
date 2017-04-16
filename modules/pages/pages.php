<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Pages;

use NF\NeoFrag\Addons\Module;

class Pages extends Module
{
	protected function __info()
	{
		return [
			'title'       => $this->lang('pages'),
			'description' => '',
			'icon'        => 'fa-file-o',
			'link'        => 'https://neofr.ag',
			'author'      => 'Michaël BILCOT & Jérémy VALENTIN <contact@neofrag.com>',
			'license'     => 'LGPLv3 <https://neofr.ag/license>',
			'admin'       => TRUE,
			'routes'      => [
				//Index
				'{url_title}'             => '_index',

				//Admin
				'admin{pages}'            => 'index',
				'admin/{id}/{url_title*}' => '_edit'
			]
		];
	}

	public function get_title($new_title = NULL)
	{
		if (!empty($this->data['module_title']))
		{
			return $this->data['module_title'];
		}

		/* TODO
			Bug dans la liste des modules quand un module est désactivé et que le module Page est activé
			return parent::get_title($new_title);
		*/

		static $title;

		if ($new_title !== NULL)
		{
			$title = $new_title;
		}
		else if ($title === NULL)
		{
			$title = $this->lang($this->info()->title, NULL);
		}

		return $title;
	}
}
