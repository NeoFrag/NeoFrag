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

class t_default extends Theme
{
	public $title       = '{lang default_theme}';
	public $description = '{lang default_theme_description}';
	public $thumbnail   = 'neofrag/themes/default/images/thumbnail.png';
	public $link        = 'http://www.neofrag.fr';
	public $author      = 'Michaël BILCOT & Jérémy VALENTIN <contact@neofrag.com>';
	public $licence     = 'http://www.neofrag.com/license.html LGPLv3';
	public $version     = 'Alpha 0.1';
	public $nf_version  = 'Alpha 0.1';
	public $path        = __FILE__;
	public $zones       = array('{lang content}', '{lang pre_content}', '{lang post_content}', '{lang header}', '{lang top}', '{lang footer}');

	public function load()
	{
		$this	->css('font.open-sans.300.400.600.700.800')
				->css('font.economica.400.700')
				->css('style')
				->css('neofrag.user');
				
		return parent::load();
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
		
		$header = function(){
			return new Row(
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
			, 'row-default');
		};
		
		$navbar = function(){
			return new Row(
					new Col(
						new Widget_View(array(
							'widget_id' => $this->db->insert('nf_widgets', array(
								'widget'   => 'navigation',
								'type'     => 'index',
								'settings' => serialize(array(
									'display' => TRUE,
									'links'   => array(
										array(
											'title' => utf8_htmlentities($this('home')),
											'url'   => 'index.html'
										),
										array(
											'title' => utf8_htmlentities($this('news')),
											'url'   => 'news.html'
										),
										array(
											'title' => utf8_htmlentities($this('forum')),
											'url'   => 'forum.html'
										),
										array(
											'title' => utf8_htmlentities($this('teams')),
											'url'   => 'teams.html'
										),
										array(
											'title' => utf8_htmlentities($this('members')),
											'url'   => 'members.html'
										),
										array(
											'title' => utf8_htmlentities($this('search')),
											'url'   => 'search.html'
										),
										array(
											'title' => utf8_htmlentities($this('contact')),
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
				, 'row-black'
			);
		};
		
		$breadcrumb = function($search = TRUE){
			return new Row(
				new Col(
					new Widget_View(array(
						'widget_id' => $this->db->insert('nf_widgets', array(
							'widget' => 'breadcrumb',
							'type'   => 'index'
						))
					)), 'col-md-8'
				),
				$search ? new Col(
					new Widget_View(array(
						'widget_id' => $this->db->insert('nf_widgets', array(
							'widget' => 'search',
							'type'   => 'index'
						))
					)), 'col-md-4'
				) : NULL,
				'row-white'
			);
		};
		
		$dispositions['*']['{lang content}'] = array(
			$breadcrumb(),
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
		
		$dispositions['*']['{lang pre_content}'] = array(
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
		
		$dispositions['*']['{lang post_content}'] = array();
		
		$dispositions['*']['{lang header}'] = array(
			$header(),
			$navbar()
		);

		$dispositions['*']['{lang top}'] = array(
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
		
		$dispositions['*']['{lang footer}'] = array(
			new Row(
				new Col(new Widget_View(array(
					'widget_id' => $this->db->insert('nf_widgets', array(
						'widget'   => 'html',
						'type'     => 'index',
						'settings' => serialize(array(
							'content' => utf8_htmlentities($this('powered_by_neofrag'))
						))
					)),
					'style' => 'panel-dark'
				)))
			, 'row-default')
		);
		
		$dispositions['/']['{lang header}'] = array(
			$header(),
			$navbar(),
			new Row(
				new Col(new Widget_View(array(
					'widget_id' => $this->db->insert('nf_widgets', array(
						'widget'   => 'slider',
						'type'     => 'index'
					))
				)), 'col-md-12')
			, 'row-default')
		);
		
		foreach (array('forum/*', 'news/_news/*', 'user/*', 'search/*') as $page)
		{
			$dispositions[$page]['{lang content}'] = array(
				$breadcrumb($page != 'search/*'),
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
		
		$dispositions['forum/*']['{lang post_content}'] = array(
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
	
	public function uninstall($remove = TRUE)
	{
		$this->load->library('file')->delete($this->config->default_background);
		$this->db->where('name LIKE', 'default_%')->delete('nf_settings');
		return parent::uninstall($remove);
	}
}

/*
NeoFrag Alpha 0.1.3
./neofrag/themes/default/default.php
*/