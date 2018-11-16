<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Widgets\Gallery\Controllers;

use NF\NeoFrag\Loadables\Controller;

class Admin extends Controller
{
	public function albums($settings = [])
	{
		return $this->view('admin_gallery', [
			'category_id' => isset($settings['category_id']) ? $settings['category_id'] : 0,
			'categories'  => $this->model()->get_categories()
		]);
	}

	public function image($settings = [])
	{
		return $this->view('admin_image', [
			'gallery_id' => isset($settings['gallery_id']) ? $settings['gallery_id'] : 0,
			'gallery'    => $this->model()->get_gallery()
		]);
	}

	public function slider($settings = [])
	{
		return $this->view('admin_slider', [
			'gallery_id' => isset($settings['gallery_id']) ? $settings['gallery_id'] : 0,
			'gallery'    => $this->model()->get_gallery()
		]);
	}
}
