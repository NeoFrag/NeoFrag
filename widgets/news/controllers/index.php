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

class w_news_c_index extends Controller_Widget
{
	public function index($config = array())
	{
		$news = array_filter($this->model()->get_news(), function($a){
			return $a['published'];
		});
		
		if (!empty($news))
		{
			return new Panel(array(
				'title'        => 'Actualités récentes',
				'content'      => $this->load->view('index', array(
					'news'     => array_slice($news, 0, 3)
				)),
				'footer'       => '<a href="'.url('news.html').'">'.icon('fa-arrow-circle-o-right').' Voir toutes les actualités</a>',
				'footer_align' => 'right'
			));
		}
		else
		{
			return new Panel(array(
				'title'   => 'Actualités récentes',
				'content' => 'Aucune actualité pour le moment'
			));
		}
	}
	
	public function categories($config = array())
	{
		$categories = $this->model('categories')->get_categories();
		
		if (!empty($categories))
		{
			return new Panel(array(
				'title'   => 'Catégories',
				'content' => $this->load->view('categories', array(
					'categories' => $categories
				)),
				'body'    => FALSE
			));
		}
		else
		{
			return new Panel(array(
				'title'   => 'Catégories',
				'content' => 'Aucune catégorie pour le moment'
			));
		}
	}
}

/*
NeoFrag Alpha 0.1.2
./widgets/news/controllers/index.php
*/