<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

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
