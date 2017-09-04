<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Widgets\Awards;

use NF\NeoFrag\Addons\Widget;

class Awards extends Widget
{
	public $title       = 'Palmarès';
	public $description = '';
	public $link        = 'http://www.neofrag.com';
	public $author      = 'Michaël Bilcot <michael.bilcot@neofrag.com>';
	public $licence     = 'http://www.neofrag.com/license.html LGPLv3';
	public $version     = '1.0';
	public $nf_version  = 'Alpha 0.1.4';
	public $path        = __FILE__;
	public $types       = [
		'index'     => 'Derniers palmarès',
		'best_team' => 'Équipe la plus récompensée',
		'best_game' => 'Jeu le plus récompensé'
	];
}
