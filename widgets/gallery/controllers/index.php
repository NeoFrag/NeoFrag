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
			return $this->panel()
						->heading($this->lang('ours_galleries'))
						->body($this->view('index', [
							'categories' => $categories
						]), FALSE)
						->footer('<a href="'.url('gallery').'">'.icon('fa-arrow-circle-o-right').' '.$this->lang('see_our_gallery').'</a>', 'right');
		}
		else
		{
			return $this->panel()
						->heading($this->lang('gallery'))
						->body($this->lang('no_category'));
		}
	}
	
	public function albums($settings = [])
	{
		return $this->panel()
					->heading($this->lang('ours_albums'))
					->body($this->view('gallery', [
						'gallery' => $this->model()->get_gallery($settings['category_id'])
					]), FALSE)
					->footer('<a href="'.url('gallery').'">'.icon('fa-arrow-circle-o-right').' '.$this->lang('see_our_gallery').'</a>', 'right');
	}
	
	public function image($settings = [])
	{
		$image = $this->model()->get_random_image($settings['gallery_id']);
		$href  = url('gallery/image/'.$image['image_id'].'/'.url_title($image['title']));
		
		if (!empty($image['file_id']))
		{
			return $this->panel()
						->heading($image['title'])
						->body('<a href="'.$href.'"><img class="img-responsive" src="'.path($image['file_id']).'" alt="" /></a>', FALSE)
						->footer('<a href="'.$href.'">'.icon('fa-arrow-circle-o-right').' '.$this->lang('details').'</a>', 'right');
		}
		else
		{
			return $this->panel()
						->heading($this->lang('random_picture'))
						->body($this->lang('no_picture'));
		}
	}
	
	public function slider($settings = [])
	{
		$images = $this->model()->get_images($settings['gallery_id']);
		
		if (!empty($images))
		{
			return $this->panel()
						->heading($images['title'])
						->body($this->view('slider', [
							'images' => $images
						]), FALSE);
		}
		else
		{
			return $this->panel()
						->heading($this->lang('album'))
						->body($this->lang('no_picture'));
		}
	}
}

/*
NeoFrag Alpha 0.1.5
./widgets/gallery/controllers/index.php
*/