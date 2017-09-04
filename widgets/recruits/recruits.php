<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Widgets\Recruits;

use NF\NeoFrag\Addons\Widget;

class Recruits extends Widget
{
	public $title       = 'Recrutement';
	public $description = '';
	public $link        = 'http://www.neofrag.com';
	public $author      = 'Michaël Bilcot <michael.bilcot@neofrag.com>';
	public $licence     = 'http://www.neofrag.com/license.html LGPLv3';
	public $version     = 'Alpha 0.1.4';
	public $nf_version  = 'Alpha 0.1.4';
	public $path        = __FILE__;
	public $types       = [
		'index'   => 'Dernières annonces',
		'recruit' => 'Une annonce en détail'
	];
}
