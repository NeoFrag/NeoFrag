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

class m_gallery_c_index extends Controller_Module
{
	public function index()
	{
		$this->css('gallery');
		
		$panels = [];
		
		foreach ($this->model()->get_categories() as $category)
		{
			$panel = [
				'title'   => '<a href="'.url('gallery/'.$category['category_id'].'/'.$category['name'].'.html').'">'.$category['title'].'</a>',
				'content' => $this->load->view('index', [
					'category_image' => $category['image_id'],
					'gallery'        => $this->model()->get_gallery($category['category_id'])
				]),
				'body'    => FALSE
			];
			
			if ($category['icon_id'])
			{
				$panel['title'] = '<img src="'.path($category['icon_id']).'" alt="" /> '.$panel['title'];
			}
			else
			{
				$panel['icon'] = 'fa-photo';
			}
			
			$panels[] = new Panel($panel);
		}
		
		if (empty($panels))
		{
			$panels[] = new Panel([
				'title'   => $this('gallery'),
				'icon'    => 'fa-photo',
				'style'   => 'panel-info',
				'content' => '<div class="text-center">'.$this('no_category_message').'</div>'
			]);
		}

		return $panels;
	}
	
	public function _category($category_id, $name, $title, $image_id, $icon_id)
	{
		$this->css('gallery');
		
		$panels = [];
		
		$panel = [
			'title'   => '<a href="'.url('gallery/'.$category_id.'/'.$name.'.html').'">'.$title.'</a>',
			'content' => $this->load->view('index', [
				'category_image' => $image_id,
				'gallery'        => $this->model()->get_gallery($category_id)
			]),
			'body'    => FALSE
		];
		
		if ($icon_id)
		{
			$panel['title'] = '<img src="'.path($icon_id).'" alt="" /> '.$title;
		}
		else
		{
			$panel['icon'] = 'fa-photo';
		}
		
		$panels[] = new Panel($panel);
		
		return $panels;
	}
	
	public function _gallery($gallery_id, $category_id, $image_id, $name, $published, $title, $description, $category_name, $category_title, $image, $category_icon, $images)
	{
		$this	->css('gallery')
				->js('gallery')
				->js('modal-carousel');
		
		$panels = [new Panel([
			'title'   => '<div class="pull-right"><a class="label label-default" href="'.url('gallery/'.$category_id.'/'.$category_name.'.html').'">'.$category_title.'</a></div>'.$title,
			'icon'    => 'fa-photo',
			'content' => $this->load->view('gallery', [
				'title'           => $title,
				'description'     => $description,
				'image_id'        => $image_id,
				'images'          => $images,
				'carousel_images' => $carousel_images = $this->model()->get_images($gallery_id),
				'total_images'    => count($carousel_images),
				'pagination'      => $this->pagination->get_pagination()
			]),
			'body' => FALSE
		])];
		
		if (empty($images))
		{
			$panels[] = new Panel([
				'title'   => $this('photos'),
				'icon'    => 'fa-photo',
				'style'   => 'panel-info',
				'content' => '<div class="text-center">'.icon('fa-photo fa-4x').'<h4>'.$this('no_images_message').'</h4></div>'
			]);
		}

		return $panels;
	}
	
	public function _image($image_id, $thumbnail_file_id, $original_file_id, $file_id, $gallery_id, $title, $description, $date, $views, $gallery_name, $gallery_title)
	{
		$this->css('gallery');
		
		$images         = $this->db->select('image_id')->from('nf_gallery_images')->where('gallery_id', $gallery_id)->get();
		$last_image_id  = max($images);
		$first_image_id = min($images);
		
		if ($last_image_id == $image_id)
		{
			$vignettes = $this->db->from('nf_gallery_images')->where('image_id <=', $last_image_id)->where('gallery_id', $gallery_id)->order_by('image_id DESC')->limit(6)->get();
		}
		else if ($first_image_id == $image_id)
		{
			$vignettes = $this->db->from('nf_gallery_images')->where('gallery_id', $gallery_id)->order_by('image_id ASC')->limit(6)->get();
			$vignettes = array_reverse($vignettes);
		}
		else
		{
			$vignettes = array_merge(
				$this->db->from('nf_gallery_images')->where('image_id >', $image_id)->where('gallery_id', $gallery_id)->limit(1)->get(),
				$this->db->from('nf_gallery_images')->where('image_id <=', $image_id)->where('gallery_id', $gallery_id)->order_by('image_id DESC')->limit(5)->get()
			);
		}
		
		$panel = [
			'title'   => '<div class="pull-right"><a class="label label-default" href="'.url('gallery/album/'.$gallery_id.'/'.$gallery_name.'.html').'">'.$gallery_title.'</a></div>'.$title,
			'icon'    => 'fa-photo',
			'content' => $this->load->view('image', [
				'image_id'          => $image_id,
				'file_id'           => $file_id,
				'thumbnail_file_id' => $thumbnail_file_id,
				'title'             => $title,
				'description'       => $description,
				'vignettes'         => $vignettes
			]),
			'body' => FALSE
		];
		
		if (!empty($description))
		{
			$panel['footer']       = $description;
			$panel['footer_align'] = 'left';
		}
		
		return [
			new Row(
				new Col(
					new Panel($panel)
				)
			),
			$this->comments->display('gallery', $image_id),
			new Button_back()
		];
	}
}

/*
NeoFrag Alpha 0.1.3
./modules/gallery/controllers/index.php
*/