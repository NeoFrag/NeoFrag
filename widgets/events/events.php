<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class w_events extends Widget
{
	public $title       = 'Événements';
	public $description = '';
	public $link        = 'http://www.neofrag.com';
	public $author      = 'Michaël Bilcot <michael.bilcot@neofrag.com>';
	public $licence     = 'http://www.neofrag.com/license.html LGPLv3';
	public $version     = 'Alpha 0.1.4';
	public $nf_version  = 'Alpha 0.1.4';
	public $path        = __FILE__;
	public $types       = [
		'index'       => 'Calendrier des événements',
		'types'       => 'Liste des types d\'événements',
		'events'      => 'Liste des événements',
		'event'       => 'Un événement en détail',
		'matches'     => 'Derniers résultats',
		'upcoming'    => 'Prochains matchs'
	];
}
