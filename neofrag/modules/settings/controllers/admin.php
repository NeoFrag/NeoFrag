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

class m_settings_c_admin extends Controller_Module
{
	public function index()
	{
		$this->title($this('configuration'));
		
		$modules = array();
		foreach ($this->get_modules() as $module)
		{
			if ($module->administrable)
			{
				$modules[$module->name] = $module->get_title();
			}
		}
		
		natsort($modules);

		$langs = array();
		foreach (preg_grep('/\.php$/', scandir('./neofrag/lang/')) as $file)
		{
			$lang = array();
			include './neofrag/lang/'.$file;

			$langs[substr($file, 0, -4)] = $lang['lang'];
		}

		natsort($langs);

		$this->load->library('form')
				->add_rules(array(
					'name' => array(
						'label'         => $this('site_title'),
						'value'         => $this->config->nf_name,
						'rules'			=> 'required'
					),
					'description' => array(
						'label'         => $this('site_description'),
						'value'         => $this->config->nf_description,
						'rules'			=> 'required'
					),
					'contact' => array(
						'label'			=> $this('contact_email'),
						'value'			=> $this->config->nf_contact,
						'type'			=> 'email',
						'rules'			=> 'required'
					),
					'default_page' => array(
						'label'			=> $this('default_page'),
						'values'		=> $modules,
						'value'			=> $this->config->nf_default_page,
						'type'			=> 'select',
						'rules'			=> 'required'
					),
					'default_language' => array(
						'label'			=> $this('language'),
						'values'		=> $langs,
						'value'			=> $this->config->nf_default_language,
						'type'			=> 'select',
						'rules'			=> 'required'
					),
					'humans_txt' => array(
						'label'			=> '<a href="http://humanstxt.org/">humans.txt</a>',
						'type'			=> 'textarea',
						'value'			=> $this->config->nf_humans_txt
					),
					'robots_txt' => array(
						'label'			=> '<a href="http://www.robotstxt.org//">robots.txt</a>',
						'type'			=> 'textarea',
						'value'			=> $this->config->nf_robots_txt
					),
					'analytics' => array(
						'label'			=> $this('code_analytics'),
						'type'			=> 'textarea',
						'value'			=> $this->config->nf_analytics
					),
					'debug' => array(
						'label'			=> $this('debug_mode'),
						'type'			=> 'radio',
						'value'			=> $this->config->nf_debug,
						'values'        => array($this('debug_disabled'), $this('debug_errors_only'), $this('debug_full'))
					)
				))
				->add_submit($this('save'))
				->display_required(FALSE);

		if ($this->form->is_valid($post))
		{
			foreach ($post as $var => $value)
			{
				if ($var == 'analytics')
				{
					$value = implode("\r\n", array_map('trim', explode("\r\n", trim(preg_replace('#&lt;script.*?&gt;(.*?)&lt;/script&gt;#is', '\1', $value)))));
				}
				
				$this->config('nf_'.$var, $value);
			}
			
			refresh();
		}
		
		return new Panel(array(
			'title'   => $this('general_settings'),
			'icon'    => 'fa-cogs',
			'content' => $this->form->display()
		));
	}

	public function components()
	{
		$this	->title($this('components_management'))
				->icon('fa-puzzle-piece');
		
		return new Panel(array(
			'title'   => $this('components_management'),
			'icon'    => 'fa-puzzle-piece',
			'style'   => 'panel-info',
			'content' => $this('unavailable_feature'),
			'size'    => 'col-md-12'
		));
	}

	public function maintenance()
	{
		$this	->title($this('maintenance'))
				->icon('fa-power-off')
				->css('maintenance')
				->js('maintenance');
				
		$form_opening = $this->load->library('form')
			->add_rules(array(
				'opening' => array(
					'type'  => 'datetime',
					'value' => $this->config->nf_maintenance_opening
				)
			))
			->fast_mode()
			->add_submit($this('save'))
			->save();

		$form_maintenance = $this->form
			->add_rules(array(
				'title' => array(
					'label' => $this('title'),
					'type'  => 'text',
					'value' => $this->config->nf_maintenance_title
				),
				'content' => array(
					'label' => $this('content'),
					'type'  => 'editor',
					'value' => $this->config->nf_maintenance_content
				),
				'logo' => array(
					'label'  => $this('logo'),
					'value'  => $this->config->nf_maintenance_logo,
					'type'   => 'file',
					'upload' => 'maintenance',
					'info'   => $this('file_picture', file_upload_max_size() / 1024 / 1024),
					'check'  => function($filename, $ext){
						if (!in_array($ext, array('gif', 'jpeg', 'jpg', 'png')))
						{
							return i18n('select_image_file');
						}
					}
				),
				'background' => array(
					'label'  => $this('background'),
					'value'  => $this->config->nf_maintenance_background,
					'type'   => 'file',
					'upload' => 'maintenance',
					'info'   => $this('file_picture', file_upload_max_size() / 1024 / 1024),
					'check'  => function($filename, $ext){
						if (!in_array($ext, array('gif', 'jpeg', 'jpg', 'png')))
						{
							return i18n('select_image_file');
						}
					}
				),
				'repeat' => array(
					'label'  => $this('background_repeat'),
					'value'  => $this->config->nf_maintenance_background_repeat,
					'values' => array(
						'no-repeat' => $this('no'),
						'repeat-x'  => $this('horizontally'),
						'repeat-y'  => $this('vertically'),
						'repeat'    => $this('both')
					),
					'type'   => 'radio'
				),
				'positionX' => array(
					'label'  => $this('position'),
					'value'  => $this->config->nf_maintenance_background_position ? explode(' ', $this->config->nf_maintenance_background_position)[0] : '',
					'values' => array(
						'left'   => $this('left'),
						'center' => $this('center'),
						'right'  => $this('right')
					),
					'type'   => 'radio'
				),
				'positionY' => array(
					'value'  => $this->config->nf_maintenance_background_position ? explode(' ', $this->config->nf_maintenance_background_position)[1] : '',
					'values' => array(
						'top'    => $this('top'),
						'center' => $this('middle'),
						'bottom' => $this('bottom')
					),
					'type'   => 'radio'
				),
				'background_color' => array(
					'label' => $this('background_color'),
					'value' => $this->config->nf_maintenance_background_color,
					'type'  => 'colorpicker'
				),
				'text_color' => array(
					'label' => $this('text_color'),
					'value' => $this->config->nf_maintenance_text_color,
					'type'  => 'colorpicker'
				),
				'facebook' => array(
					'label' => 'Facebook',
					'icon'  => 'fa-facebook',
					'value' => $this->config->nf_maintenance_facebook,
					'type'  => 'url'
				),
				'twitter' => array(
					'label' => 'Twitter',
					'icon'  => 'fa-twitter',
					'value' => $this->config->nf_maintenance_twitter,
					'type'  => 'url'
				),
				'google' => array(
					'label' => 'Google+',
					'icon'  => 'fa-google-plus',
					'value' => $this->config->{'nf_maintenance_google-plus'},
					'type'  => 'url'
				),
				'steam' => array(
					'label' => 'Steam',
					'icon'  => 'fa-steam',
					'value' => $this->config->nf_maintenance_steam,
					'type'  => 'url'
				),
				'twitch' => array(
					'label' => 'Twitch',
					'icon'  => 'fa-twitch',
					'value' => $this->config->nf_maintenance_twitch,
					'type'  => 'url'
				)
			))
			->add_submit($this('save'))
			->save();
			
		if ($form_opening->is_valid($post))
		{
			$this->config('nf_maintenance_opening', $post['opening']);
			refresh();
		}
		else if ($form_maintenance->is_valid($post))
		{
			$this	->config('nf_maintenance_title',               $post['title'])
					->config('nf_maintenance_content',             $post['content'])
					->config('nf_maintenance_logo',                $post['logo'], 'int')
					->config('nf_maintenance_background',          $post['background'], 'int')
					->config('nf_maintenance_background_repeat',   $post['repeat'])
					->config('nf_maintenance_background_position', $post['positionX'].' '.$post['positionY'])
					->config('nf_maintenance_background_color',    $post['background_color'])
					->config('nf_maintenance_text_color',          $post['text_color'])
					->config('nf_maintenance_facebook',            $post['facebook'])
					->config('nf_maintenance_twitter',             $post['twitter'])
					->config('nf_maintenance_google-plus',         $post['google'])
					->config('nf_maintenance_steam',               $post['steam'])
					->config('nf_maintenance_twitch',              $post['twitch']);

			refresh();
		}

		return new Row(
			new Col(
				new Panel(array(
					'title'   => $this('website_status'),
					'icon'    => 'fa-power-off',
					'content' => $this->load->view('maintenance')
				)),
				new Panel(array(
					'title'   => $this('planned_opening'),
					'icon'    => 'fa-clock-o',
					'content' => $form_opening->display()
				))
				, 'col-md-3'
			),
			new Col(
				new Panel(array(
					'title'   => $this('customizing_maintenance_page'),
					'icon'    => 'fa-paint-brush',
					'content' => $form_maintenance->display()
				))
				, 'col-md-9'
			)
		);
	}

	public function themes()
	{
		$this	->title($this('themes'))
				->icon('fa-tint');
		
		return new Panel(array(
			'title'   => $this('list_installed_themes'),
			'icon'    => 'fa-tint',
			'content' => $this->load->view('themes', array(
				'themes' => $this->get_themes()
			)),
			'footer'  => '<button class="btn btn-outline btn-info" data-toggle="modal" data-target=".modal-theme-install">'.icon('fa-download').' '.$this('install_theme_btn').'</button>',
			'size'    => 'col-md-12'
		));
	}
	
	public function _theme_internal($theme, $controller)
	{
		$this	->title($theme->get_title())
				->subtitle($this('theme_customize'))
				->icon('fa-paint-brush');
		
		return $controller->index($theme);
	}
}

/*
NeoFrag Alpha 0.1.3
./neofrag/modules/settings/controllers/admin.php
*/