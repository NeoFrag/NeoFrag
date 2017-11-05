<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class m_gallery_c_admin_checker extends Controller_Module
{
	public function index($page = '')
	{
		return [$this->pagination->get_data($this->model()->get_gallery(), $page)];
	}

	public function _edit($gallery_id, $title)
	{
		if ($gallery = $this->model()->check_gallery($gallery_id, $title, 'default'))
		{
			return $gallery;
		}
	}

	public function delete($gallery_id, $title)
	{
		if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')
		{
			$this->ajax();
		}

		if ($gallery = $this->model()->check_gallery($gallery_id, $title, 'default'))
		{
			return [$gallery['gallery_id'], $gallery['title']];
		}
	}

	public function _categories_edit($category_id, $name)
	{
		if ($category = $this->model()->check_category($category_id, $name, 'default'))
		{
			return $category;
		}
	}

	public function _categories_delete($category_id, $name)
	{
		if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')
		{
			$this->ajax();
		}

		if ($category = $this->model()->check_category($category_id, $name, 'default'))
		{
			return [$category_id, $category['title']];
		}
	}

	public function _image_edit($image_id, $name)
	{
		if ($image = $this->model()->check_image($image_id, $name))
		{
			return [
				$image_id,
				$image['thumbnail_file_id'],
				$image['title'],
				$image['description'],
				$image['gallery_id'],
				$image['gallery_title']
			];
		}
	}

	public function _image_delete($image_id, $name)
	{
		$this->ajax();

		if ($image = $this->model()->check_image($image_id, $name))
		{
			return [$image_id, $image['title']];
		}
	}
}
