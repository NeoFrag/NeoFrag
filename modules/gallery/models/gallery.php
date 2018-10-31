<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Gallery\Models;

use NF\NeoFrag\Loadables\Model;

class Gallery extends Model
{
	public function get_gallery($category_id = FALSE)
	{
		$this->db	->select('g.*', 'gl.title', 'gl.description', 'g.image_id as image', 'c.icon_id as category_icon', 'c.name as category_name', 'cl.title as category_title', 'COUNT(DISTINCT gi.image_id) as images')
					->from('nf_gallery g')
					->join('nf_gallery_lang gl',            'g.gallery_id  = gl.gallery_id')
					->join('nf_gallery_categories c',       'g.category_id = c.category_id')
					->join('nf_gallery_categories_lang cl', 'c.category_id = cl.category_id')
					->join('nf_gallery_images gi',          'g.gallery_id  = gi.gallery_id')
					->where('gl.lang', $this->config->lang->info()->name)
					->where('cl.lang', $this->config->lang->info()->name)
					->group_by('g.gallery_id')
					->order_by('g.gallery_id DESC');

		if (!empty($category_id))
		{
			$this->db->where('g.category_id', $category_id);
		}

		if (!$this->url->admin)
		{
			$this->db->where('g.published', TRUE);
		}

		return $this->db->get();
	}

	public function check_gallery($gallery_id, $name, $lang = 'default')
	{
		if ($lang == 'default')
		{
			$lang = $this->config->lang->info()->name;
		}

		$this->db	->select('g.*', 'gl.title', 'gl.description', 'c.name as category_name', 'cl.title as category_title', 'c.image_id as category_image', 'c.icon_id as category_icon')
					->from('nf_gallery g')
					->join('nf_gallery_lang gl',            'g.gallery_id  = gl.gallery_id')
					->join('nf_gallery_categories c',       'g.category_id = c.category_id')
					->join('nf_gallery_categories_lang cl', 'c.category_id = cl.category_id')
					->where('g.gallery_id', $gallery_id)
					->where('g.name', $name)
					->where('gl.lang', $lang)
					->where('cl.lang', $lang);

		if (!$this->url->admin)
		{
			$this->db->where('g.published', TRUE);
		}

		if ($gallery = $this->db->row())
		{
			return $gallery;
		}
		else
		{
			return FALSE;
		}
	}

	public function add_gallery($title, $category_id, $image_id, $description, $published)
	{
		$gallery_id = 	$this->db->insert('nf_gallery', [
							'category_id' => $category_id,
							'image_id'    => $image_id,
							'name'        => url_title($title),
							'published'   => $published
						]);

		$this->db	->insert('nf_gallery_lang', [
						'gallery_id'  => $gallery_id,
						'lang'        => $this->config->lang->info()->name,
						'title'       => $title,
						'description' => $description
					]);

		$this->access->init('gallery', 'gallery', $gallery_id);

		return $gallery_id;
	}

	public function edit_gallery($gallery_id, $category_id, $image_id, $published, $title, $description, $lang)
	{
		$this->db	->where('gallery_id', $gallery_id)
					->update('nf_gallery', [
						'category_id' => $category_id,
						'image_id'    => $image_id,
						'name'        => url_title($title),
						'published'   => $published
					]);

		$this->db	->where('gallery_id', $gallery_id)
					->where('lang', $lang)
					->update('nf_gallery_lang', [
						'title'       => $title,
						'description' => $description
					]);
	}

	public function delete_gallery($gallery_id)
	{
		NeoFrag()->model2('file', $this->db->select('image_id')->from('nf_gallery')->where('gallery_id', $gallery_id)->row())->delete();

		foreach ($this->db->select('image_id')->from('nf_gallery_images')->where('gallery_id', $gallery_id)->get() as $image_id)
		{
			$this->delete_image($image_id);
		}

		$this->db	->where('gallery_id', $gallery_id)
					->delete('nf_gallery');
	}

	public function get_images($gallery_id)
	{
		return $this->db->from('nf_gallery_images')
						->where('gallery_id', $gallery_id)
						->order_by('date DESC')
						->get();
	}

	public function check_image($image_id, $name)
	{
		$this->db	->select('i.*', 'g.name as gallery_name', 'gl.title as gallery_title', 'g.published')
					->from('nf_gallery_images i')
					->join('nf_gallery g', 'i.gallery_id  = g.gallery_id')
					->join('nf_gallery_lang gl', 'i.gallery_id  = gl.gallery_id')
					->where('i.image_id', $image_id);

		$image = $this->db->row();

		if ($image && url_title($image['title']) == $name)
		{
			return $image;
		}
		else
		{
			return FALSE;
		}
	}

	public function add_image($file_id, $gallery_id, $title, $description = '')
	{
		$file = $this->db	->select('name', 'path')
							->from('nf_file')
							->where('id', $file_id)
							->row();

		dir_create('upload/gallery/thumbnails', 'upload/gallery/originals');

		copy($file['path'], $thumbnail = str_replace('upload/gallery/', 'upload/gallery/thumbnails/', $file['path']));
		copy($file['path'], $original  = str_replace('upload/gallery/', 'upload/gallery/originals/', $file['path']));

		list($thumbnail_width, $thumbnail_height, $thumbnail_type, $thumbnail_attr) = getimagesize($thumbnail);

		image_resize($thumbnail, 300);
		image_resize($file['path'], 1250);

		$title = empty($title) ? $file['name'] : $title;

		$this->db->insert('nf_gallery_images', [
			'thumbnail_file_id' => NeoFrag()->model2('file')->static_add($thumbnail, $title)->id,
			'original_file_id'  => NeoFrag()->model2('file')->static_add($original, $title)->id,
			'file_id'           => $file_id,
			'gallery_id'        => $gallery_id,
			'title'             => $title,
			'description'       => $description
		]);
	}

	public function edit_image($image_id, $title, $description)
	{
		$this->db	->where('image_id', $image_id)
					->update('nf_gallery_images', [
						'title'       => $title,
						'description' => $description
					]);
	}

	public function delete_image($image_id)
	{
		foreach ($this->db->select('file_id', 'thumbnail_file_id', 'original_file_id')->from('nf_gallery_images')->where('image_id', $image_id)->row() as $file_id)
		{
			NeoFrag()->model2('file', $file_id)->delete();
		}
	}

	public function check_category($category_id, $name, $lang = 'default')
	{
		if ($lang == 'default')
		{
			$lang = $this->config->lang->info()->name;
		}

		return $this->db->select('c.category_id', 'c.name', 'cl.title', 'c.image_id', 'c.icon_id')
						->from('nf_gallery_categories c')
						->join('nf_gallery_categories_lang cl', 'c.category_id = cl.category_id')
						->where('c.category_id', $category_id)
						->where('c.name', $name)
						->where('cl.lang', $lang)
						->row();
	}

	public function get_categories()
	{
		return $this->db->select('c.category_id', 'c.image_id', 'c.icon_id', 'c.name', 'cl.title', 'COUNT(g.gallery_id) as nb_gallery')
						->from('nf_gallery_categories c')
						->join('nf_gallery_categories_lang cl', 'c.category_id = cl.category_id')
						->join('nf_gallery g', 'c.category_id = g.category_id')
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
		$category_id = $this->db->insert('nf_gallery_categories', [
			'name'        => url_title($title),
			'image_id'    => $image,
			'icon_id'     => $icon
		]);

		$this->db->insert('nf_gallery_categories_lang', [
			'category_id' => $category_id,
			'lang'        => $this->config->lang->info()->name,
			'title'       => $title
		]);
	}

	public function edit_category($category_id, $title, $image_id, $icon_id)
	{
		$this->db	->where('category_id', $category_id)
					->update('nf_gallery_categories', [
						'image_id' => $image_id,
						'icon_id'  => $icon_id,
						'name'     => url_title($title)
					]);

		$this->db	->where('category_id', $category_id)
					->where('lang', $this->config->lang->info()->name)
					->update('nf_gallery_categories_lang', [
						'title' => $title
					]);
	}

	public function delete_category($category_id)
	{
		NeoFrag()->model2('file', $this->db->select('image_id')->from('nf_gallery_categories')->where('category_id', $category_id)->row())->delete();

		foreach ($this->db->select('gallery_id')->from('nf_gallery')->where('category_id', $category_id)->get() as $gallery_id)
		{
			$this->delete_gallery($gallery_id);
		}

		$this->db	->where('category_id', $category_id)
					->delete('nf_gallery_categories');
	}
}
