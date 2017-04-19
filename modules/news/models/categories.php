<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\News\Models;

use NF\NeoFrag\Loadables\Model;

class Categories extends Model
{
	public function check_category($category_id, $name, $lang = 'default')
	{
		if ($lang == 'default')
		{
			$lang = $this->config->lang->info()->name;
		}

		return $this->db->select('c.category_id', 'cl.title', 'c.image_id', 'c.icon_id')
						->from('nf_news_categories c')
						->join('nf_news_categories_lang cl', 'c.category_id = cl.category_id')
						->where('c.category_id', $category_id)
						->where('c.name', $name)
						->where('cl.lang', $lang)
						->row();
	}

	public function get_categories()
	{
		return $this->db->select('c.category_id', 'c.icon_id', 'c.name', 'cl.title', 'COUNT(n.news_id) as nb_news')
						->from('nf_news_categories c')
						->join('nf_news_categories_lang cl', 'c.category_id = cl.category_id')
						->join('nf_news n', 'c.category_id = n.category_id')
						->where('cl.lang', $this->config->lang->info()->name)
						->group_by('c.category_id')
						->order_by('cl.title')
						->get();
	}

	public function get_categories_list()
	{
		$list = [];

		foreach ($this->get_categories() as $category)
		{
			$list[$category['category_id']] = $category['title'];
		}

		array_natsort($list);

		return $list;
	}

	public function add_category($title, $image, $icon)
	{
		$category_id = $this->db->insert('nf_news_categories', [
			'name'        => url_title($title),
			'image_id'    => $image,
			'icon_id'     => $icon
		]);

		$this->db->insert('nf_news_categories_lang', [
			'category_id' => $category_id,
			'lang'        => $this->config->lang->info()->name,
			'title'       => $title
		]);
	}

	public function edit_category($category_id, $title, $image_id, $icon_id)
	{
		$this->db	->where('category_id', $category_id)
					->update('nf_news_categories', [
						'image_id' => $image_id,
						'icon_id'  => $icon_id,
						'name'     => url_title($title)
					]);

		$this->db	->where('category_id', $category_id)
					->where('lang', $this->config->lang->info()->name)
					->update('nf_news_categories_lang', [
						'title'        => $title
					]);
	}

	public function delete_category($category_id)
	{
		$files = array_merge(
			array_values($this->db->select('image_id', 'icon_id')->from('nf_news_categories')->where('category_id', $category_id)->row()),
			$this->db->select('image_id')->from('nf_news')->where('category_id', $category_id)->get()
		);

		foreach ($files as $file)
		{
			NeoFrag()->model2('file', $file)->delete();
		}

		$this->db	->where('category_id', $category_id)
					->delete('nf_news_categories');
	}
}
