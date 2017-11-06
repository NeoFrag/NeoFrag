<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Access;

use NF\NeoFrag\Addons\Module;

class Access extends Module
{
	public $title         = '{lang permissions}';
	public $description   = '';
	public $icon          = 'fa-unlock-alt';
	public $link          = 'http://www.neofrag.com';
	public $author        = 'Michaël Bilcot <michael.bilcot@neofrag.com>';
	public $licence       = 'http://www.neofrag.com/license.html LGPLv3';
	public $version       = 'Alpha 0.1';
	public $nf_version    = 'Alpha 0.1';
	public $path          = __FILE__;
	public $admin         = FALSE;
	public $routes        = [
		'admin/edit/{url_title*}'  => '_edit',
		'admin/([a-z0-9-]*?){pages}' => 'index'
	];
}
