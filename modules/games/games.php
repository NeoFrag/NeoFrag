<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class m_games extends Module
{
	public $title       = '{lang games_maps}';
	public $description = '';
	public $icon        = 'fa-gamepad';
	public $link        = 'http://www.neofrag.com';
	public $author      = 'Michaël Bilcot <michael.bilcot@neofrag.com>';
	public $licence     = 'http://www.neofrag.com/license.html LGPLv3';
	public $version     = 'Alpha 0.1';
	public $nf_version  = 'Alpha 0.1';
	public $path        = __FILE__;
	public $admin       = 'gaming';
	public $routes      = [
		//Admin
		'admin{pages}'                  => 'index',
		'admin/{id}/{url_title}{pages}' => '_edit',
		
		//Maps
		'admin/maps/add(?:/{id}/{url_title})?' => '_maps_add',
		'admin/maps/edit/{id}/{url_title}'     => '_maps_edit',
		'admin/maps/delete/{id}/{url_title}'   => '_maps_delete',
		
		//Modes
		'admin/modes/add/{id}/{url_title}'    => '_modes_add',
		'admin/modes/edit/{id}/{url_title}'   => '_modes_edit',
		'admin/modes/delete/{id}/{url_title}' => '_modes_delete'
	];
}
