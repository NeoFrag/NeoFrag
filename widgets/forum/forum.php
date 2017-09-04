<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Widgets\Forum;

use NF\NeoFrag\Addons\Widget;

class Forum extends Widget
{
	public $title       = '{lang forum}';
	public $description = '';
	public $link        = 'http://www.neofrag.com';
	public $author      = 'Michaël Bilcot <michael.bilcot@neofrag.com>';
	public $licence     = 'http://www.neofrag.com/license.html LGPLv3';
	public $version     = 'Alpha 0.1';
	public $nf_version  = 'Alpha 0.1';
	public $path        = __FILE__;
	public $types       = [
		'index'      => '{lang last_messages}',
		'topics'     => '{lang last_topics}',
		'statistics' => '{lang statistics}',
		'activity'   => '{lang activity}'
	];
}
