<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class w_gallery extends Widget
{
	public $title       = '{lang galleries}';
	public $description = '';
	public $link        = 'http://www.neofrag.com';
	public $author      = 'Jérémy Valentin <jeremy.valentin@neofrag.com>';
	public $licence     = 'http://www.neofrag.com/license.html LGPLv3';
	public $version     = 'Alpha 0.1';
	public $nf_version  = 'Alpha 0.1';
	public $path        = __FILE__;
	public $types       = [
		'index'  => '{lang categories_list}',
		'albums' => '{lang albums_from_category}',
		'image'  => '{lang random_picture}',
		'slider' => '{lang album_slide}'
	];
}
