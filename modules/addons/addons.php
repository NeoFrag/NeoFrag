<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class m_addons extends Module
{
	public $title         = 'Composants';
	public $description   = '';
	public $icon          = 'fa-puzzle-piece';
	public $link          = 'http://www.neofrag.com';
	public $author        = 'Michaël Bilcot <michael.bilcot@neofrag.com>';
	public $licence       = 'http://www.neofrag.com/license.html LGPLv3';
	public $version       = 'Alpha 0.1';
	public $nf_version    = 'Alpha 0.1';
	public $path          = __FILE__;
	public $admin         = FALSE;
	public $routes        = [
		//Modules
		'admin/module/{url_title}'        => '_module_settings',
		'admin/delete/module/{url_title}' => '_module_delete',

		//Thèmes
		'admin/theme/{url_title}'         => '_theme_settings',
		'admin/delete/theme/{url_title}'  => '_theme_delete',
		'admin/ajax/theme/active'         => '_theme_activation',
		'admin/ajax/theme/reset'          => '_theme_reset',
		'admin/ajax/theme/{url_title}'    => '_theme_settings',

		//Languages
		'admin/ajax/language/sort'        => '_language_sort',

		//Authenticators
		'admin/ajax/authenticator/sort'   => '_authenticator_sort',
		'admin/ajax/authenticator/admin'  => '_authenticator_admin',
		'admin/ajax/authenticator/update' => '_authenticator_update'
	];
}
