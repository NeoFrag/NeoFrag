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
																			'title' => ($category['icon_id'] ? '<img src="'.NeoFrag()->model2('file', $category['icon_id'])->path().'" class="img-icon mr-2" alt="" />' : '').$category['title'],
																			'url'   => 'gallery/'.$category['category_id'].'/'.$category['name']
																		]);
																	}
																})
																->__toArray()
											])
											->title('Galeries', 'far fa-image')
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
						->footer_if($this->access('gallery', 'gallery_post', $gallery_id), $this->button($this->lang('Poster une image'), 'fas fa-plus', 'primary btn-block')->modal_ajax('ajax/gallery/post/'.$gallery_id.'/'.url_title($name)))
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
}
