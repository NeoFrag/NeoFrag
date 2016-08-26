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

class w_gallery_c_checker extends Controller
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

/*
NeoFrag Alpha 0.1
./widgets/gallery/controllers/checker.php
*/