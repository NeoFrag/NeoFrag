<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class m_settings extends Module
{
	public $title         = '{lang settings}';
	public $description   = '';
	public $icon          = 'fa-cogs';
	public $link          = 'http://www.neofrag.com';
	public $author        = 'Michaël Bilcot <michael.bilcot@neofrag.com>';
	public $licence       = 'http://www.neofrag.com/license.html LGPLv3';
	public $version       = 'Alpha 0.1';
	public $nf_version    = 'Alpha 0.1';
	public $path          = __FILE__;
	public $admin         = FALSE;
	public $routes        = [];
}
