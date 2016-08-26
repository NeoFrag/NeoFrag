<?php if (!defined('NEOFRAG_CMS')) exit;
/**************************************************************************
Copyright © 2015 Michaël BILCOT & Jérémy VALENTIN

This file is part of NeoFrag.

NeoFrag is free software: you can redistribute it and/or modify
it under the terms of the GNU Lesser General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

NeoFrag is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with NeoFrag. If not, see <http://www.gnu.org/licenses/>.
**************************************************************************/

class w_gallery_c_index extends Controller_Widget
{
	public function index($settings = [])
	{
		$categories = $this->model()->get_categories();
		
		if (!empty($categories))
		{
			return new Panel([
				'title'        => $this('ours_galleries'),
				'content'      => $this->load->view('index', [
					'categories' => $categories
				]),
				'body'         => FALSE,
				'footer'       => '<a href="'.url('gallery.html').'">'.icon('fa-arrow-circle-o-right').' '.$this('see_our_gallery').'</a>',
				'footer_align' => 'right'
			]);
		}
		else
		{
			return new Panel([
				'title'   => $this('gallery'),
				'content' => $this('no_category')
			]);
		}
	}
	
	public function albums($settings = [])
	{
		return new Panel([
			'title'        => $this('ours_albums'),
			'content'      => $this->load->view('gallery', [
				'gallery' => $this->model()->get_gallery($settings['category_id'])
			]),
			'body'         => FALSE,
			'footer'       => '<a href="'.url('gallery.html').'">'.icon('fa-arrow-circle-o-right').' '.$this('see_our_gallery').'</a>',
			'footer_align' => 'right'
		]);
	}
	
	public function image($settings = [])
	{
		$image = $this->model()->get_random_image($settings['gallery_id']);
		$href  = url('gallery/image/'.$image['image_id'].'/'.url_title($image['title']).'.html');
		
		if (!empty($image['file_id']))
		{
			return new Panel([
				'title'        => $image['title'],
				'content'      => '<a href="'.$href.'"><img class="img-responsive" src="'.path($image['file_id']).'" alt="" /></a>',
				'body'         => FALSE,
				'footer'       => '<a href="'.$href.'">'.icon('fa-arrow-circle-o-right').' '.$this('details').'</a>',
				'footer_align' => 'right'
			]);
		}
		else
		{
			return new Panel([
				'title'   => $this('random_picture'),
				'content' => $this('no_picture')
			]);
		}
	}
	
	public function slider($settings = [])
	{
		$images = $this->model()->get_images($settings['gallery_id']);
		
		if (!empty($images))
		{
			return new Panel([
				'title'   => $images['title'],
				'content' => $this->load->view('slider', [
					'images' => $images
				]),
				'body'    => FALSE
			]);
		}
		else
		{
			return new Panel([
				'title'   => $this('album'),
				'content' => $this('no_picture')
			]);
		}
	}
}

/*
NeoFrag Alpha 0.1.3
./widgets/gallery/controllers/index.php
*/