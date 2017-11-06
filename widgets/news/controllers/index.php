<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Widgets\News\Controllers;

use NF\NeoFrag\Loadables\Controllers\Widget as Controller_Widget;

class Index extends Controller_Widget
{
	public function index($config = [])
	{
		$news = array_filter($this->model()->get_news(), function($a){
			return $a['published'];
		});

		if (!empty($news))
		{
			return $this->panel()
						->heading($this->lang('Actualités récentes'))
						->body($this->view('index', [
							'news' => array_slice($news, 0, 3)
						]))
						->footer('<a href="'.url('news').'">'.icon('fa-arrow-circle-o-right').' '.$this->lang('Voir toutes les actualités').'</a>', 'right');
		}
		else
		{
			return $this->panel()
						->heading($this->lang('Actualités récentes'))
						->body($this->lang('Aucune actualité pour le moment'));
		}
	}

	public function categories($config = [])
	{
		$categories = $this->model('categories')->get_categories();

		if (!empty($categories))
		{
			return $this->panel()
						->heading($this->lang('Catégories'))
						->body($this->view('categories', [
							'categories' => $categories
						]), FALSE);
		}
		else
		{
			return $this->panel()
						->heading($this->lang('Catégories'))
						->body($this->lang('Aucune catégorie pour le moment'));
		}
	}
}
