<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Widgets\Partners;

use NF\NeoFrag\Addons\Widget;

class Partners extends Widget
{
	public $title       = 'Partenaires';
	public $description = '';
	public $link        = 'http://www.neofrag.com';
	public $author      = 'Michaël Bilcot <michael.bilcot@neofrag.com>';
	public $licence     = 'http://www.neofrag.com/license.html LGPLv3';
	public $version     = 'Alpha 0.1.4';
	public $nf_version  = 'Alpha 0.1.4';
	public $path        = __FILE__;
	public $types       = [
		'index'  => 'Affichage horizontal en slider',
		'column' => 'Affichage simple en colonne'
	];
}
