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

class m_gallery extends Module
{
	public $title       = '{lang gallery_module}';
	public $description = '';
	public $icon        = 'fa-photo';
	public $link        = 'http://www.neofrag.com';
	public $author      = 'Jérémy Valentin <jeremy.valentin@neofrag.com>';
	public $licence     = 'http://www.neofrag.com/license.html LGPLv3';
	public $version     = 'Alpha 0.1';
	public $nf_version  = 'Alpha 0.1';
	public $path        = __FILE__;
	public $routes      = [
		//Index
		'{id}/{url_title}'                         => '_category',
		'album/{id}/{url_title}{page}'             => '_gallery',
		'image/{id}/{url_title}'                   => '_image',
		//Admin
		'admin{pages}'                             => 'index',
		'admin/{id}/{url_title}'                   => '_edit',
		'admin/categories/add'                     => '_categories_add',
		'admin/categories/{id}/{url_title}'        => '_categories_edit',
		'admin/categories/delete/{id}/{url_title}' => '_categories_delete',
		'admin/ajax/image/add/{id}/{url_title}'    => '_image_add',
		'admin/image/{id}/{url_title}'             => '_image_edit',
		'admin/image/delete/{id}/{url_title}'      => '_image_delete'
	];
	
	public function comments($image_id)
	{
		$image = $this->db	->select('title')
							->from('nf_gallery_images')
							->where('image_id', $image_id)
							->row();

		if ($image)
		{
			return [
				'title' => $image,
				'url'   => 'gallery/image/'.$image_id.'/'.url_title($image).'.html'
			];
		}
	}
}

/*
NeoFrag Alpha 0.1.4.1
./modules/gallery/gallery.php
*/