<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Widgets\Gallery\Controllers;

use NF\NeoFrag\Loadables\Controllers\Widget_Checker;

class Checker extends Widget_Checker
{
	public function album($settings = [])
	{
		if (in_array($settings['categorie_id'], array_map(function($a){
			return $a['categorie_id'];
		}, $this->model()->get_categories())))
		{
			return [
				'categorie_id' => $settings['categorie_id']
			];
		}
	}

	public function image($settings = [])
	{
		if (in_array($settings['gallery_id'], array_merge(array_map(function($a){
			return $a['gallery_id'];
		}, $this->model()->get_gallery()), [0])))
		{
			return [
				'gallery_id' => $settings['gallery_id']
			];
		}
	}

	public function slider($settings = [])
	{
		if (in_array($settings['gallery_id'], array_map(function($a){
			return $a['gallery_id'];
		}, $this->model()->get_gallery())))
		{
			return [
				'gallery_id' => $settings['gallery_id']
			];
		}
	}
}
