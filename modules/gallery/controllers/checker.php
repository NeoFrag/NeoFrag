<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Gallery\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module_Checker;

class Checker extends Module_Checker
{
	public function _gallery($gallery_id, $name, $page = '')
	{
		if ($this->access('gallery', 'gallery_see', $gallery_id) && ($gallery = $this->model()->check_gallery($gallery_id, $name)))
		{
			return [
				$gallery_id,
				$gallery['category_id'],
				$gallery['image_id'],
				$name,
				$gallery['published'],
				$gallery['title'],
				$gallery['description'],
				$gallery['category_name'],
				$gallery['category_title'],
				$gallery['category_image'],
				$gallery['category_icon'],
				$this->module->pagination->fix_items_per_page($this->config->images_per_page)->get_data($this->model()->get_images($gallery_id), $page)
			];
		}

		$this->error->unauthorized();
	}

	public function _category($category_id, $name)
	{
		if ($category = $this->model()->check_category($category_id, $name))
		{
			return [$category['category_id'], $category['name'], $category['title']];
		}
	}

	public function _image($image_id, $name)
	{
		if ($image = $this->model()->check_image($image_id, $name))
		{
			return $image;
		}
	}
}
