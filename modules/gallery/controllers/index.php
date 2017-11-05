<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class m_gallery_c_index extends Controller_Module
{
	public function index()
	{
		$this->css('gallery');
		
		$panels = [];
		
		foreach ($this->model()->get_categories() as $category)
		{
			$panels[] = $this	->panel()
								->heading($category['title'], $category['icon_id'] ?: 'fa-photo', 'gallery/'.$category['category_id'].'/'.$category['name'])
								->body($this->view('index', [
									'category_image' => $category['image_id'],
									'gallery'        => $this->model()->get_gallery($category['category_id'])
								]), FALSE);
		}
		
		if (empty($panels))
		{
			$panels[] = $this	->panel()
								->heading($this->lang('gallery'), 'fa-photo')
								->body('<div class="text-center">'.$this->lang('no_category_message').'</div>')
								->color('info');
		}

		return $panels;
	}
	
	public function _category($category_id, $name, $title, $image_id, $icon_id)
	{
		$this->css('gallery');

		return [
			$this	->panel()
					->heading($title, $icon_id ?: 'fa-photo', 'gallery/'.$category_id.'/'.$name)
					->body($this->view('index', [
						'category_image' => $image_id,
						'gallery'        => $this->model()->get_gallery($category_id)
					]), FALSE)
		];
	}
	
	public function _gallery($gallery_id, $category_id, $image_id, $name, $published, $title, $description, $category_name, $category_title, $image, $category_icon, $images)
	{
		$this	->css('gallery')
				->js('gallery')
				->js('modal-carousel');
		
		$panels = [$this->panel()
						->heading('<div class="pull-right"><a class="label label-default" href="'.url('gallery/'.$category_id.'/'.$category_name).'">'.$category_title.'</a></div>'.$title, 'fa-photo')
						->body($this->view('gallery', [
							'title'           => $title,
							'description'     => $description,
							'image_id'        => $image_id,
							'images'          => $images,
							'carousel_images' => $carousel_images = $this->model()->get_images($gallery_id),
							'total_images'    => count($carousel_images),
							'pagination'      => $this->pagination->get_pagination()
						]), FALSE)];
		
		if (empty($images))
		{
			$panels[] = $this	->panel()
								->heading($this->lang('photos'), 'fa-photo')
								->body('<div class="text-center">'.icon('fa-photo fa-4x').'<h4>'.$this->lang('no_images_message').'</h4></div>')
								->color('info');
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

		$panel = $this	->panel()
						->heading('<div class="pull-right"><a class="label label-default" href="'.url('gallery/album/'.$gallery_id.'/'.$gallery_name).'">'.$gallery_title.'</a></div>'.$title, 'fa-photo')
						->body($this->view('image', [
							'image_id'          => $image_id,
							'file_id'           => $file_id,
							'thumbnail_file_id' => $thumbnail_file_id,
							'title'             => $title,
							'description'       => $description,
							'vignettes'         => $vignettes
						]), FALSE);

		if (!empty($description))
		{
			$panel->footer($description, 'left');
		}

		return [
			$this->row($this->col($panel)),
			$this->comments->display('gallery', $image_id),
			$this->panel_back()
		];
	}
}
