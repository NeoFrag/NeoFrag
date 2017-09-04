<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Members;

use NF\NeoFrag\Addons\Module;

class Members extends Module
{
	public $title         = '{lang members_list}';
	public $description   = '';
	public $icon          = 'fa-users';
	public $link          = 'http://www.neofrag.com';
	public $author        = 'Michaël Bilcot <michael.bilcot@neofrag.com>';
	public $licence       = 'http://www.neofrag.com/license.html LGPLv3';
	public $version       = 'Alpha 0.1';
	public $nf_version    = 'Alpha 0.1';
	public $path          = __FILE__;
	public $routes        = [
		'{pages}'                                   => 'index',
		'group/(admins|members){pages}'             => '_group',
		'group/{url_title}-{id}/{url_title}{pages}' => '_group',
		'group/{id}/{url_title}{pages}'             => '_group'
	];
}
