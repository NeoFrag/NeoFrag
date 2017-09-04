<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Gallery\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Admin_Ajax extends Controller_Module
{
	public function _image_add($gallery_id)
	{
		if (!empty($_FILES['file']) && in_array(extension($_FILES['file']['name']), ['gif', 'jpeg', 'jpg', 'png']) && $file_id = $this->file->upload($_FILES['file'], 'gallery'))
		{
			$this->model()->add_image($file_id, $gallery_id, basename($_FILES['file']['name']));
		}

		exit;
	}
}
