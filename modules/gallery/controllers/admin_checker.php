<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Gallery\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module_Checker;

class Admin_Checker extends Module_Checker
{
	public function index($page = '')
	{
		return [$this->module->pagination->get_data($this->model()->get_gallery(), $page)];
	}

	public function add()
	{
		if (!$this->is_authorized('add_gallery'))
		{
			$this->error->unauthorized();
		}

		return [];
	}

	public function _edit($gallery_id, $title)
	{
		if (!$this->is_authorized('modify_gallery'))
		{
			$this->error->unauthorized();
		}

		if ($gallery = $this->model()->check_gallery($gallery_id, $title, 'default'))
		{
			return $gallery;
		}
	}

	public function delete($gallery_id, $title)
	{
		if (!$this->is_authorized('delete_gallery'))
		{
			$this->error->unauthorized();
		}

		$this->ajax();

		if ($gallery = $this->model()->check_gallery($gallery_id, $title, 'default'))
		{
			return [$gallery['gallery_id'], $gallery['title']];
		}
	}

	public function _categories_add()
	{
		if (!$this->is_authorized('add_gallery_category'))
		{
			$this->error->unauthorized();
		}

		return [];
	}

	public function _categories_edit($category_id, $name)
	{
		if (!$this->is_authorized('modify_gallery_category'))
		{
			$this->error->unauthorized();
		}

		if ($category = $this->model()->check_category($category_id, $name, 'default'))
		{
			return $category;
		}
	}

	public function _categories_delete($category_id, $name)
	{
		if (!$this->is_authorized('delete_gallery_category'))
		{
			$this->error->unauthorized();
		}

		$this->ajax();

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
				$image['gallery_name'],
				$image['file_id'],
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
