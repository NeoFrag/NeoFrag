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
						->heading($this->lang('recent_news'))
						->body($this->view('index', [
							'news' => array_slice($news, 0, 3)
						]))
						->footer('<a href="'.url('news').'">'.icon('fa-arrow-circle-o-right').' '.$this->lang('show_more').'</a>', 'right');
		}
		else
		{
			return $this->panel()
						->heading($this->lang('recent_news'))
						->body($this->lang('no_news'));
		}
	}

	public function categories($config = [])
	{
		$categories = $this->model('categories')->get_categories();

		if (!empty($categories))
		{
			return $this->panel()
						->heading($this->lang('categories'))
						->body($this->view('categories', [
							'categories' => $categories
						]), FALSE);
		}
		else
		{
			return $this->panel()
						->heading($this->lang('categories'))
						->body($this->lang('no_category'));
		}
	}
}
