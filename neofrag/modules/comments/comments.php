<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class m_comments extends Module
{
	public $title       = '{lang comments}';
	public $description = '';
	public $icon        = 'fa-comments-o';
	public $link        = 'http://www.neofrag.com';
	public $author      = 'Michaël Bilcot <michael.bilcot@neofrag.com>';
	public $licence     = 'http://www.neofrag.com/license.html LGPLv3';
	public $version     = 'Alpha 0.1';
	public $nf_version  = 'Alpha 0.1';
	public $path        = __FILE__;
	public $admin       = TRUE;
	public $routes      = [
		'admin/([a-z0-9-]*?){pages}' => 'index'
	];
}
