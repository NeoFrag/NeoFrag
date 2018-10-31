<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Gallery\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Index extends Controller_Module
{
	public function index($category_id = NULL, $name = NULL, $breadcrumb = NULL)
	{
		$this->breadcrumb_if($breadcrumb, $breadcrumb);

		$gallery = $this->model()->get_gallery($category_id);

		if ($category_id && $name)
		{
			$category = $this->model()->check_category($category_id, $name);
		}

		foreach ($gallery as $key => $galerie)
		{
			if (!$this->access('gallery', 'gallery_see', $galerie['gallery_id']))
			{
				unset($gallery[$key]);
			}
		}

		return $this->row()
					->append(
						$this	->col()
								->append(
									$this	->widget('navigation')
											->output('vertical', [
												'links' => $this->array()
																->append([
																	'title' => 'Tous les albums',
																	'url'   => 'gallery'
																])
																->exec(function($array){
																	foreach ($this->model()->get_categories() as $category)
																	{
																		$array->append([
																			'title' => $category['title'],
																			'url'   => 'gallery/'.$category['category_id'].'/'.$category['name']
																		]);
																	}
																})
																->__toArray()
											])
											->title('Galeries', 'fa-photo')
								)
								->size('col-md-4 col-lg-3')
					)
					->append(
						$this	->col()
								->append(
									$this->view('gallery', [
										'category_id' => $category_id,
										'category'    => isset($category) ? $category : NULL,
										'gallery'     => $gallery
									])
								)
								->size('col-md-8 col-lg-9')
					);
	}

	public function _category($category_id, $name, $title)
	{
		return $this->index($category_id, $name, $title);
	}

	public function _gallery($gallery_id, $category_id, $image_id, $name, $published, $title, $description, $category_name, $category_title, $image, $category_icon, $images)
	{
		$this	->breadcrumb($category_title, 'gallery/'.$category_id.'/'.url_title($category_name))
				->breadcrumb($title);

		return $this->row(
			$this->col(
				$this	->panel()
						->body($this->view('album', [
							'image_id'       => $image_id,
							'title'          => $title,
							'description'    => $description,
							'category_id'    => $category_id,
							'category_name'  => $category_name,
							'category_title' => $category_title,
							'image'          => $image,
							'count'          => count($this->model()->get_images($gallery_id))
						]), FALSE)
						->footer_if($this->access('gallery', 'gallery_post', $gallery_id), $this->button($this->lang('Poster une image'), 'fa-plus', 'primary btn-block')->modal_ajax('ajax/gallery/post/'.$gallery_id.'/'.url_title($name)))
						->size('col-12 col-lg-4')
			),
			$this->col(
				$this->view('images', [
					'images'     => $images,
					'pagination' => $this->module->pagination->get_pagination()
				])
			)->size('col-12 col-lg-8')
		);
	}

	/*
	public function _image($image_id, $thumbnail_file_id, $original_file_id, $file_id, $gallery_id, $title, $description, $date, $views, $gallery_name, $gallery_title)
	{
		return $this->row(
			$this->col(
				$this	->panel()
						->heading()
						->body($this->view('image', [
							'file_id'       => $file_id,
							'title'         => $title,
							'description'   => $description,
							'date'          => $date,
							'views'         => $views,
							'gallery_id'    => $gallery_id,
							'gallery_name'  => $gallery_name,
							'gallery_title' => $gallery_title
						]))
						->size('col-12')
			)
		);

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
						->heading('<div class="pull-right"><a class="badge badge-default" href="'.url('gallery/album/'.$gallery_id.'/'.$gallery_name).'">'.$gallery_title.'</a></div>'.$title, 'fa-photo')
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

		return $this->array
					->append($this->row($this->col($panel)))
					->append_if(($comments = $this->module('comments')) && $comments->is_enabled(), function() use (&$comments, $image_id){
						return $comments('gallery', $image_id);
					})
					->append($this->panel_back());
	}
	*/
}
