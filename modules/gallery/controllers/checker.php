<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class m_gallery_c_checker extends Controller_Module
{
	public function _gallery($gallery_id, $name, $page = '')
	{
		if ($gallery = $this->model()->check_gallery($gallery_id, $name))
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
				$gallery['image'],
				$gallery['category_icon'],
				$this->pagination->fix_items_per_page($this->config->forum_messages_per_page)->get_data($this->model()->get_images($gallery_id), $page)
			];
		}
	}
		public function _category($category_id, $name)
	{
		if ($category = $this->model()->check_category($category_id, $name))
		{
			return $category;
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
