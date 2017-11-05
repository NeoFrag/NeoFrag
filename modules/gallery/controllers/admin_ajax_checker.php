<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class m_gallery_c_admin_ajax_checker extends Controller_Module
{
	public function _image_add($gallery_id, $name)
	{
		if ($gallery = $this->model()->check_gallery($gallery_id, $name, 'default'))
		{
			return [$gallery['gallery_id']];
		}
	}
}
