<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Gallery\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module_Checker;

class Admin_Ajax_Checker extends Module_Checker
{
	public function _image_add($gallery_id, $name)
	{
		if ($gallery = $this->model()->check_gallery($gallery_id, $name, 'default'))
		{
			return [$gallery['gallery_id']];
		}
	}
}
