<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Live_Editor;

use NF\NeoFrag\Addons\Module;

class Live_Editor extends Module
{
	public $title         = '{lang live_editor}';
	public $description   = '';
	public $icon          = 'fa-desktop';
	public $link          = 'http://www.neofrag.com';
	public $author        = 'Michaël Bilcot <michael.bilcot@neofrag.com>';
	public $licence       = 'http://www.neofrag.com/license.html LGPLv3';
	public $version       = 'Alpha 0.1';
	public $nf_version    = 'Alpha 0.1';
	public $path          = __FILE__;
}
