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
NeoFrag Alpha 0.1.2
./neofrag/modules/settings/controllers/admin.php
*/