<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Pages;

use NF\NeoFrag\Addons\Module;

class Pages extends Module
{
	public $title       = '{lang pages}';
	public $description = '';
	public $icon        = 'fa-file-o';
	public $link        = 'http://www.neofrag.com';
	public $author      = 'Michaël Bilcot <michael.bilcot@neofrag.com>';
	public $licence     = 'http://www.neofrag.com/license.html LGPLv3';
	public $version     = 'Alpha 0.1';
	public $nf_version  = 'Alpha 0.1';
	public $path        = __FILE__;
	public $admin       = TRUE;
	public $routes      = [
		//Index
		'{url_title}'             => '_index',

		//Admin
		'admin{pages}'            => 'index',
		'admin/{id}/{url_title*}' => '_edit'
	];

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
			$title = $this->lang($this->title, NULL);
		}

		return $title;
	}
}
