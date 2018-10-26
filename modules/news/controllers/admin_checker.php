<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\News\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module_Checker;

class Admin_Checker extends Module_Checker
{
	public function index($page = '')
	{
		return [$this->module->pagination->get_data($this->model()->get_news(), $page)];
	}

	public function add()
	{
		if (!$this->is_authorized('add_news'))
		{
			$this->error->unauthorized();
		}

		return [];
	}

	public function _edit($news_id, $title, $tab = 'default')
	{
		if (!$this->is_authorized('modify_news'))
		{
			$this->error->unauthorized();
		}

		if ($news = $this->model()->check_news($news_id, $title, $tab))
		{
			return $news + [$tab];
		}
	}

	public function delete($news_id, $title)
	{
		if (!$this->is_authorized('delete_news'))
		{
			$this->error->unauthorized();
		}

		$this->ajax();

		if ($news = $this->model()->check_news($news_id, $title))
		{
			return [$news['news_id'], $news['title']];
		}
	}

	public function _categories_add()
	{
		if (!$this->is_authorized('add_news_category'))
		{
			$this->error->unauthorized();
		}

		return [];
	}

	public function _categories_edit($category_id, $name)
	{
		if (!$this->is_authorized('modify_news_category'))
		{
			$this->error->unauthorized();
		}

		if ($category = $this->model('categories')->check_category($category_id, $name, 'default'))
		{
			return $category;
		}
	}

	public function _categories_delete($category_id, $name)
	{
		if (!$this->is_authorized('delete_news_category'))
		{
			$this->error->unauthorized();
		}

		$this->ajax();

		if ($category = $this->model('categories')->check_category($category_id, $name, 'default'))
		{
			return [$category_id, $category['title']];
		}
	}
}
