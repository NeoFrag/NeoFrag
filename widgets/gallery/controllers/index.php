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
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with NeoFrag. If not, see <http://www.gnu.org/licenses/>.
**************************************************************************/

class w_gallery_c_index extends Controller_Widget
{
	public function index($settings = array())
	{
		$categories = $this->model()->get_categories();
		
		if (!empty($categories))
		{
			return new Panel(array(
				'title'        => 'Nos galeries',
				'content'      => $this->load->view('index', array(
					'categories' => $categories
				)),
				'body'         => FALSE,
				'footer'       => '<a href="{base_url}gallery.html"><i class="fa fa-arrow-circle-o-right"></i> Voir notre galerie</a>',
				'footer_align' => 'right'
			));
		}
		else
		{
			return new Panel(array(
				'title'   => 'Galerie',
				'content' => 'Aucune catégorie pour le moment'
			));
		}
	}
	
	public function albums($settings = array())
	{
		return new Panel(array(
			'title'        => 'Nos albums',
			'content'      => $this->load->view('gallery', array(
				'gallery' => $this->model()->get_gallery($settings['category_id'])
			)),
			'body'         => FALSE,
			'footer'       => '<a href="{base_url}gallery.html"><i class="fa fa-arrow-circle-o-right"></i> Voir notre galerie</a>',
			'footer_align' => 'right'
		));
	}
	
	public function image($settings = array())
	{
		$image = $this->model()->get_random_image($settings['gallery_id']);
		$href  = '{base_url}gallery/image/'.$image['image_id'].'/'.url_title($image['title']).'.html';
		
		if (!empty($image['file_id']))
		{
			return new Panel(array(
				'title'        => $image['title'],
				'content'      => '<a href="'.$href.'"><img class="img-responsive" src="{image '.$image['file_id'].'}" alt="" /></a>',
				'body'         => FALSE,
				'footer'       => '<a href="'.$href.'"><i class="fa fa-arrow-circle-o-right"></i> Détails</a>',
				'footer_align' => 'right'
			));
		}
		else
		{
			return new Panel(array(
				'title'   => 'Image aléatoire',
				'content' => 'Aucune image à afficher.'
			));
		}
	}
	
	public function slider($settings = array())
	{
		$images = $this->model()->get_images($settings['gallery_id']);
		
		if (!empty($images))
		{
			return new Panel(array(
				'title'   => $images['title'],
				'content' => $this->load->view('slider', array(
					'images' => $images
				)),
				'body'    => FALSE
			));
		}
		else
		{
			return new Panel(array(
				'title'   => 'Album',
				'content' => 'Aucune image pour le moment.'
			));
		}
	}
}

/*
NeoFrag Alpha 0.1
./widgets/gallery/controllers/index.php
*/