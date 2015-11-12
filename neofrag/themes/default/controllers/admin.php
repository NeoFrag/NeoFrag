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
 
class t_default_c_admin extends Controller
{
	public function index($theme)
	{
		$this	->css('admin')
				->js('admin')
				->load->library('form')
				->add_rules(array(
					'background' => array(
						'label'  => $this('background'),
						'value'  => $this->config->{'default_background'},
						'type'   => 'file',
						'upload' => 'themes/default/backgrounds',
						'info'   => i18n('file_picture', file_upload_max_size() / 1024 / 1024),
						'check'  => function($filename, $ext){
							if (!in_array($ext, array('gif', 'jpeg', 'jpg', 'png')))
							{
								return i18n('select_image_file');
							}
						}
					),
					'repeat' => array(
						'label'  => $this('background_repeat'),
						'value'  => $this->config->{'default_background_repeat'},
						'values' => array(
							'no-repeat' => $this('no'),
							'repeat-x'  => $this('horizontally'),
							'repeat-y'  => $this('vertically'),
							'repeat'    => $this('both')
						),
						'type'   => 'radio',
						'rules'  => 'required'
					),
					'positionX' => array(
						'label'  => $this('position'),
						'value'  => explode(' ', $this->config->{'default_background_position'})[0],
						'values' => array(
							'left'   => $this('left'),
							'center' => $this('center'),
							'right'  => $this('right')
						),
						'type'   => 'radio',
						'rules'  => 'required'
					),
					'positionY' => array(
						'value'  => explode(' ', $this->config->{'default_background_position'})[1],
						'values' => array(
							'top'    => $this('top'),
							'center' => $this('middle'),
							'bottom' => $this('bottom')
						),
						'type'   => 'radio',
						'rules'  => 'required'
					),
					'fixed' => array(
						'value'  => $this->config->{'default_background_attachment'},
						'values' => array(
							'on'  => $this('background_fixed')
						),
						'type'   => 'checkbox'
					),
					'color' => array(
						'label' => $this('background_color'),
						'value' => $this->config->{'default_background_color'},
						'type'  => 'colorpicker',
						'rules' => 'required'
					)
				))
				->add_submit($this('save'));

		if ($this->form->is_valid($post))
		{
			if ($post['background'])
			{
				$this->config('default_background', $post['background'], 'int');
			}
			else
			{
				$this->db->where('name', 'default_background')->delete('nf_settings');
			}
			
			$this	->config('default_background_repeat', $post['repeat'])
					->config('default_background_attachment', in_array('on', $post['fixed']) ? 'fixed' : 'scroll')
					->config('default_background_position', $post['positionX'].' '.$post['positionY'])
					->config('default_background_color', $post['color']);

			refresh();
		}
		
		return new Row(
			new Col(
				new Panel(array(
					'content' => $this->load->view('admin/menu', array(
						'theme_name' => $theme->name
					)),
					'body'    => FALSE
				))
				, 'col-md-4 col-lg-3'
			),
			new Col(
				new Panel(array(
					'title'   => $this('dashboard'),
					'icon'    => 'fa-cog',
					'content' => $this->load->view('admin/index', array(
						'theme'           => $theme,
						'form_background' => $this->form->display()
					))
				))
				, 'col-md-8 col-lg-9'
			)
		);
	}
}

/*
NeoFrag Alpha 0.1.1
./neofrag/themes/default/controllers/admin.php
*/