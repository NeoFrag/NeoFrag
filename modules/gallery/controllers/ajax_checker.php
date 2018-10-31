<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Gallery\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module_Checker;

class Ajax_Checker extends Module_Checker
{
	public function post($gallery_id, $name)
	{
		if ($this->access('gallery', 'gallery_see', $gallery_id) && ($gallery = $this->model()->check_gallery($gallery_id, $name)))
		{
			return [$gallery['gallery_id']];
		}
	}

	public function image($image_id, $title)
	{
		if ($image = $this->model()->check_image($image_id, $title))
		{
			return [$image];
		}
	}
}
