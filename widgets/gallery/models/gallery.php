<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Widgets\Gallery\Models;

use NF\NeoFrag\Loadables\Model;

class Gallery extends Model
{
	public function get_gallery($category_id = FALSE)
	{
		$this->db	->select('g.*', 'gl.title', 'gl.description', 'g.image_id as image', 'COUNT(DISTINCT gi.image_id) as images')
					->from('nf_gallery g')
					->join('nf_gallery_lang gl',            'g.gallery_id  = gl.gallery_id')
					->join('nf_gallery_images gi',          'g.gallery_id  = gi.gallery_id')
					->where('gl.lang', $this->config->lang->info()->name)
					->group_by('g.gallery_id')
					->order_by('g.gallery_id DESC');

		if (!empty($category_id))
		{
			$this->db->where('g.category_id', $category_id);
		}

		return $this->db->get();
	}

	public function get_random_image($gallery_id = FALSE)
	{
		$this->db	->from('nf_gallery_images')
					->order_by('RAND()');

		if (!empty($gallery_id) || ($gallery_id > 0))
		{
			$this->db->where('gallery_id', $gallery_id);
		}

		return $this->db->row();
	}

	public function get_images($gallery_id)
	{
		return $this->db->from('nf_gallery_images')
						->where('gallery_id', $gallery_id)
						->order_by('date DESC')
						->get();
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
}
