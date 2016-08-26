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

class m_partners extends Module
{
	public $title       = 'Partenaires';
	public $description = '';
	public $icon        = 'fa-star-o';
	public $link        = 'http://www.neofrag.com';
	public $author      = 'Michaël Bilcot <michael.bilcot@neofrag.com>';
	public $licence     = 'http://www.neofrag.com/license.html LGPLv3';
	public $version     = 'Alpha 0.1';
	public $nf_version  = 'Alpha 0.1.4';
	public $path        = __FILE__;
	public $routes      = [
		//Index
		'{id}/{url_title}'        => '_partner',

		//Admin
		'admin/{id}/{url_title*}' => '_edit'
	];

	public function settings()
	{
		$this	->form
				->add_rules([
					'partners_logo_display' => [
						'label'       => 'Logo',
						'value'       => $this->config->partners_logo_display,
						'values'      => [
							'logo_dark'  => 'Foncé',
							'logo_light' => 'Clair'
						],
						'type'        => 'radio',
						'description' => 'Utilisez les logos clairs s\'ils sont affichés sur un fond foncé',
						'size'        => 'col-md-4'
					]
				])
				->add_submit($this('edit'))
				->add_back('admin/addons.html#modules');

		if ($this->form->is_valid($post))
		{
			$this->config('partners_logo_display', $post['partners_logo_display']);
			
			redirect_back('admin/addons.html#modules');
		}

		return new Panel([
			'content' => $this->form->display()
		]);
	}
}

/*
NeoFrag Alpha 0.1.4
./modules/partners/partners.php
*/