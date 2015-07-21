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

class t_default extends Theme
{
	public $name        = 'Default';
	public $description = 'Son design est minimaliste mais générique, il peut s\'adapter facilement à n\'importe quel domaine.';
	public $thumbnail   = 'neofrag/themes/default/images/thumbnail.png';
	public $link        = 'http://www.neofrag.fr';
	public $author      = 'Michaël BILCOT & Jérémy VALENTIN <contact@neofrag.com>';
	public $licence     = 'http://www.neofrag.com/license.html LGPLv3';
	public $version     = 'Alpha 0.1';
	public $nf_version  = 'Alpha 0.1';
	public $path        = __FILE__;
	public $zones       = array('Contenu', 'Avant-contenu', 'Post-contenu', 'Header', 'Top', 'Footer');

	public function load()
	{
		$this	->css('style')
				->css('neofrag.user');
	}
	
	public function styles_row()
	{
		return $this->load->view('live_editor/row');
	}
	
	public function styles_widget()
	{
		return $this->load->view('live_editor/widget');
	}
	
	public function install($dispositions = array())
	{
		$this	->config('default_background_repeat',     'no-repeat')
				->config('default_background_attachment', 'scroll')
				->config('default_background_position',   'center top')
				->config('default_background_color',      '#141d26');
		
		$dispositions['*']['Contenu'] = array(
			new Row(
				new Col(
					new Widget_View(array(
						'widget_id' => $this->db->insert('nf_widgets', array(
							'widget' => 'breadcrumb',
							'type'   => 'index'
						))
					)), 'col-md-12'
				), 'row-white'
			),
			new Row(
				new Col(new Widget_View(array(
					'widget_id' => $this->db->insert('nf_widgets', array(
						'widget' => 'module',
						'type'   => 'index'
					))
				)), 'col-md-8'),
				new Col(
						new Widget_View(array(
							'widget_id' => $this->db->insert('nf_widgets', array(
								'widget' => 'members',
								'type'   => 'online'
							)),
							'style'     => 'panel-red'
						)),
						new Widget_View(array(
							'widget_id' => $this->db->insert('nf_widgets', array(
								'widget' => 'user',
								'type'   => 'index'
							)),
							'style'     => 'panel-dark'
						)),
						new Widget_View(array(
							'widget_id' => $this->db->insert('nf_widgets', array(
								'widget' => 'news',
								'type'   => 'categories'
							)),
							'style'     => 'panel-default'
						)),
						new Widget_View(array(
							'widget_id' => $this->db->insert('nf_widgets', array(
								'widget'   => 'talks',
								'type'     => 'index',
								'settings' => serialize(array(
									'talk_id' => 2
								))
							)),
							'style'     => 'panel-default'
						))
				, 'col-md-4')
			, 'row-light')
		);
		
		$dispositions['*']['Avant-contenu'] = array(
			new Row(
				new Col(
					new Widget_View(array(
						'widget_id' => $this->db->insert('nf_widgets', array(
							'widget' => 'forum',
							'type'   => 'topics'
						)),
						'style' => 'panel-default'
					)), 'col-md-4'
				),
				new Col(
					new Widget_View(array(
						'widget_id' => $this->db->insert('nf_widgets', array(
							'widget' => 'news',
							'type'   => 'index'
						)),
						'style' => 'panel-dark'
					)), 'col-md-4'
				),
				new Col(
					new Widget_View(array(
						'widget_id' => $this->db->insert('nf_widgets', array(
							'widget' => 'members',
							'type'   => 'index'
						)),
						'style' => 'panel-red'
					)), 'col-md-4'
				)
			, 'row-default')
		);
		
		$dispositions['*']['Post-contenu'] = array();
		
		$dispositions['*']['Header'] = array(
			new Row(
				new Col(new Widget_View(array(
					'widget_id' => $this->db->insert('nf_widgets', array(
						'widget'   => 'header',
						'type'     => 'index',
						'settings' => serialize(array(
							'align'             => 'text-center',
							'title'             => '',
							'description'       => '',
							'color-title'       => '',
							'color-description' => '#DC351E'
						))
					))
				)), 'col-md-12')
			, 'row-default'),
			new Row(
				new Col(
					new Widget_View(array(
						'widget_id' => $this->db->insert('nf_widgets', array(
							'widget'   => 'navigation',
							'type'     => 'index',
							'settings' => serialize(array(
								'display' => TRUE,
								'links'   => array(
									array(
										'title' => 'Accueil',
										'url'   => 'index.html'
									),
									array(
										'title' => 'Actualit&eacute;s',
										'url'   => 'news.html'
									),
									array(
										'title' => 'Forum',
										'url'   => 'forum.html'
									),
									array(
										'title' => '&Eacute;quipes',
										'url'   => 'teams.html'
									),
									array(
										'title' => 'Membres',
										'url'   => 'members.html'
									),
									array(
										'title' => 'Contact',
										'url'   => 'contact.html'
									)
								)
							))
						))
					)), 'col-md-8'
				),
				new Col(
					new Widget_View(array(
						'widget_id' => $this->db->insert('nf_widgets', array(
							'widget' => 'user',
							'type'   => 'index_mini'
						))
					)), 'col-md-4'
				)
			, 'row-black')
		);

		$dispositions['*']['Top'] = array(
			new Row(
				new Col(new Widget_View(array(
					'widget_id' => $this->db->insert('nf_widgets', array(
						'widget'   => 'navigation',
						'type'     => 'index',
						'settings' => serialize(array(
							'display' => TRUE,
							'links'   => array(
								array(
									'title' => 'Facebook',
									'url'   => '#'
								),
								array(
									'title' => 'Twitter',
									'url'   => '#'
								),
								array(
									'title' => 'Origin',
									'url'   => '#'
								),
								array(
									'title' => 'Steam',
									'url'   => '#'
								)
							)
						))
					))
				)), 'col-md-8'),
				new Col(new Widget_View(array(
					'widget_id' => $this->db->insert('nf_widgets', array(
						'widget' => 'members',
						'type'   => 'online_mini'
					))
				)), 'col-md-4')
			, 'row-default')
		);
		
		$dispositions['*']['Footer'] = array(
			new Row(
				new Col(new Widget_View(array(
					'widget_id' => $this->db->insert('nf_widgets', array(
						'widget'   => 'html',
						'type'     => 'index',
						'settings' => serialize(array(
							'content' => '[center]Propuls&eacute; par [url=http://www.neofrag.fr]NeoFrag CMS[/url]﻿ version Alpha 0.1﻿.1[/center]'
						))
					)),
					'style' => 'panel-dark'
				)))
			, 'row-default')
		);
		
		$dispositions['/']['Header'] = array(
			new Row(
				new Col(new Widget_View(array(
					'widget_id' => $this->db->insert('nf_widgets', array(
						'widget'   => 'header',
						'type'     => 'index',
						'settings' => serialize(array(
							'align'             => 'text-center',
							'title'             => '',
							'description'       => '',
							'color-title'       => '',
							'color-description' => '#DC351E'
						))
					))
				)), 'col-md-12')
			, 'row-default'),
			new Row(
				new Col(
					new Widget_View(array(
						'widget_id' => $this->db->insert('nf_widgets', array(
							'widget'   => 'navigation',
							'type'     => 'index',
							'settings' => serialize(array(
								'display' => TRUE,
								'links'   => array(
									array(
										'title' => 'Accueil',
										'url'   => 'index.html'
									),
									array(
										'title' => 'Actualit&eacute;s',
										'url'   => 'news.html'
									),
									array(
										'title' => 'Forum',
										'url'   => 'forum.html'
									),
									array(
										'title' => '&Eacute;quipes',
										'url'   => 'teams.html'
									),
									array(
										'title' => 'Membres',
										'url'   => 'members.html'
									),
									array(
										'title' => 'Contact',
										'url'   => 'contact.html'
									)
								)
							))
						))
					)), 'col-md-8'
				),
				new Col(
					new Widget_View(array(
						'widget_id' => $this->db->insert('nf_widgets', array(
							'widget' => 'user',
							'type'   => 'index_mini'
						))
					)), 'col-md-4'
				)
			, 'row-black'),
			new Row(
				new Col(new Widget_View(array(
					'widget_id' => $this->db->insert('nf_widgets', array(
						'widget'   => 'slider',
						'type'     => 'index'
					))
				)), 'col-md-12')
			, 'row-default')
		);
		
		foreach (array('forum/*', 'news/_news/*', 'user/*') as $page)
		{
			$dispositions[$page]['Contenu'] = array(
				new Row(
					new Col(
						new Widget_View(array(
							'widget_id' => $this->db->insert('nf_widgets', array(
								'widget' => 'breadcrumb',
								'type'   => 'index'
							))
						)), 'col-md-12'
					), 'row-white'
				),
				new Row(
					new Col(new Widget_View(array(
						'widget_id' => $this->db->insert('nf_widgets', array(
							'widget' => 'module',
							'type'   => 'index'
						))
					)), 'col-md-12')
				, 'row-light')
			);
		}
		
		$dispositions['forum/*']['Post-contenu'] = array(
			new Row(
				new Col(
					new Widget_View(array(
						'widget_id' => $this->db->insert('nf_widgets', array(
							'widget' => 'forum',
							'type'   => 'statistics'
						)),
						'style' => 'panel-red'
					)), 'col-md-4'
				),
				new Col(
					new Widget_View(array(
						'widget_id' => $this->db->insert('nf_widgets', array(
							'widget' => 'forum',
							'type'   => 'activity'
						)),
						'style' => 'panel-dark'
					)), 'col-md-8'
				)
			, 'row-light')
		);
		
		return parent::install($dispositions);
	}
	
	public function uninstall()
	{
		$this->load->library('file')->delete($this->config->default_background);
		$this->db->where('name LIKE', 'default_%')->delete('nf_settings');
		return parent::uninstall();
	}
}

/*
NeoFrag Alpha 0.1
./neofrag/themes/default/default.php
*/