<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Widgets\Gallery\Controllers;

use NF\NeoFrag\Loadables\Controllers\Widget as Controller_Widget;

class Index extends Controller_Widget
{
	public function index($settings = [])
	{
		$categories = $this->model()->get_categories();

		if (!empty($categories))
		{
			return $this->panel()
						->heading($this->lang('Nos galeries'))
						->body($this->view('index', [
							'categories' => $categories
						]), FALSE)
						->footer('<a href="'.url('gallery').'">'.icon('far fa-arrow-alt-circle-right').' '.$this->lang('Voir notre galerie').'</a>', 'right');
		}
		else
		{
			return $this->panel()
						->heading($this->lang('Galerie'))
						->body($this->lang('Aucune catégorie pour le moment'));
		}
	}

	public function albums($settings = [])
	{
		return $this->panel()
					->heading($this->lang('Nos albums'))
					->body($this->view('gallery', [
						'gallery' => $this->model()->get_gallery($settings['category_id'])
					]), FALSE)
					->footer('<a href="'.url('gallery').'">'.icon('far fa-arrow-alt-circle-right').' '.$this->lang('Voir notre galerie').'</a>', 'right');
	}

	public function image($settings = [])
	{
		$image = $this->model()->get_random_image($settings['gallery_id']);
		$href  = url('gallery/image/'.$image['image_id'].'/'.url_title($image['title']));

		if (!empty($image['file_id']))
		{
			return $this->panel()
						->heading($image['title'])
						->body('<a href="'.$href.'"><img class="img-fluid" src="'.NeoFrag()->model2('file', $image['file_id'])->path().'" alt="" /></a>', FALSE)
						->footer('<a href="'.$href.'">'.icon('far fa-arrow-alt-circle-right').' '.$this->lang('Détails').'</a>', 'right');
		}
		else
		{
			return $this->panel()
						->heading($this->lang('Image aléatoire'))
						->body($this->lang('Aucune image pour le moment'));
		}
	}

	public function slider($settings = [])
	{
		$images = $this->model()->get_images($settings['gallery_id']);

		if (!empty($images))
		{
			return $this->panel()
						->body($this->view('slider', [
							'id'     => $settings['gallery_id'],
							'images' => $images
						]), FALSE);
		}
		else
		{
			return $this->panel()
						->heading($this->lang('Album'))
						->body($this->lang('Aucune image pour le moment'));
		}
	}
}
