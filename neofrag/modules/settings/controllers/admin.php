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

class m_settings_c_admin extends Controller_Module
{
	public function index()
	{
		$this->title('Configuration');
		
		$modules = array();
		foreach ($this->get_modules() as $module)
		{
			if ($module->administrable)
			{
				$modules[$module->get_name()] = $module->name;
			}
		}
		
		natsort($modules);
		
		$this->load->library('form')
				->add_rules(array(
					'name' => array(
						'label'         => 'Titre du site',
						'value'         => $this->config->nf_name,
						'rules'			=> 'required'
					),
					'description' => array(
						'label'         => 'Description du site',
						'value'         => $this->config->nf_description,
						'rules'			=> 'required'
					),
					'contact' => array(
						'label'			=> 'Email de contact',
						'value'			=> $this->config->nf_contact,
						'type'			=> 'email',
						'rules'			=> 'required'
					),
					'default_page' => array(
						'label'			=> 'Page d\'accueil',
						'values'		=> $modules,
						'value'			=> $this->config->nf_default_page,
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
						'label'			=> 'Code analytics',
						'type'			=> 'textarea',
						'value'			=> $this->config->nf_analytics
					),
					'debug' => array(
						'label'			=> 'Mode débug',
						'type'			=> 'radio',
						'value'			=> $this->config->nf_debug,
						'values'        => array('Désactivé', 'Erreurs seulement', 'Complet')
					)
				))
				->add_submit('Valider')
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
			'title'   => 'Préférences générales',
			'icon'    => 'fa-cogs',
			'content' => $this->form->display()
		));
	}

	public function components()
	{
		$this	->title('Gestion des composants')
				->icon('fa-puzzle-piece');
		
		return new Panel(array(
			'title'   => 'Gestion des composants',
			'icon'    => 'fa-puzzle-piece',
			'style'   => 'panel-info',
			'content' => 'Cette fonctionnalité n\'est pas disponible pour l\'instant.',
			'size'    => 'col-md-12'
		));
	}

	public function themes()
	{
		$this	->title('Thèmes')
				->icon('fa-tint');
		
		return new Panel(array(
			'title'   => 'Liste des thèmes installés',
			'icon'    => 'fa-tint',
			'content' => $this->load->view('themes', array(
				'themes' => $this->get_themes()
			)),
			'footer'  => '<button class="btn btn-outline btn-info" data-toggle="modal" data-target=".modal-theme-install">'.icon('fa-download').' Installer / Mettre à jour un thème</button>',
			'size'    => 'col-md-12'
		));
	}
	
	public function _theme_internal($theme, $controller)
	{
		$this	->title($theme->name)
				->subtitle('Personnalisation du thème')
				->icon('fa-paint-brush');
		
		return $controller->index($theme);
	}
}

/*
NeoFrag Alpha 0.1.1
./neofrag/modules/settings/controllers/admin.php
*/