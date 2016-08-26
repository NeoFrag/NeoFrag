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
	public $zones       = ['{lang content}', '{lang pre_content}', '{lang post_content}', '{lang header}', '{lang top}', '{lang footer}'];

	public function load()
	{
		$this	->css('font.open-sans.300.400.600.700.800')
				->css('font.economica.400.700')
				->css('style');
				
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
	
	public function install($dispositions = [])
	{
		$this	->config('default_background_repeat',     'no-repeat')
				->config('default_background_attachment', 'scroll')
				->config('default_background_position',   'center top')
				->config('default_background_color',      '#141d26');
		
		$header = function(){
			return new Row(
				new Col(new Widget_View([
					'widget_id' => $this->db->insert('nf_widgets', [
						'widget'   => 'header',
						'type'     => 'index',
						'settings' => serialize([
							'align'             => 'text-center',
							'title'             => '',
							'description'       => '',
							'color-title'       => '',
							'color-description' => '#DC351E'
						])
					])
				]), 'col-md-12')
			, 'row-default');
		};
		
		$navbar = function(){
			return new Row(
					new Col(
						new Widget_View([
							'widget_id' => $this->db->insert('nf_widgets', [
								'widget'   => 'navigation',
								'type'     => 'index',
								'settings' => serialize([
									'display' => TRUE,
									'links'   => [
										[
											'title' => utf8_htmlentities($this('home')),
											'url'   => 'index.html'
										],
										[
											'title' => utf8_htmlentities($this('forum')),
											'url'   => 'forum.html'
										],
										[
											'title' => utf8_htmlentities($this('teams')),
											'url'   => 'teams.html'
										],
										[
											'title' => utf8_htmlentities('Photos'),
											'url'   => 'gallery.html'
										],
										[
											'title' => utf8_htmlentities('Partenaires'),
											'url'   => 'partners.html'
										],
										[
											'title' => utf8_htmlentities('Palmarès'),
											'url'   => 'awards.html'
										]
									]
								])
							])
						]), 'col-md-7'
					),
					new Col(
						new Widget_View([
							'widget_id' => $this->db->insert('nf_widgets', [
								'widget' => 'user',
								'type'   => 'index_mini'
							])
						]), 'col-md-5'
					)
				, 'row-black'
			);
		};
		
		$breadcrumb = function($search = TRUE){
			return new Row(
				new Col(
					new Widget_View([
						'widget_id' => $this->db->insert('nf_widgets', [
							'widget' => 'breadcrumb',
							'type'   => 'index'
						])
					]), 'col-md-8'
				),
				$search ? new Col(
					new Widget_View([
						'widget_id' => $this->db->insert('nf_widgets', [
							'widget' => 'search',
							'type'   => 'index'
						])
					]), 'col-md-4'
				) : NULL,
				'row-white'
			);
		};
		
		$dispositions['*']['{lang content}'] = [
			$breadcrumb(),
			new Row(
				new Col(new Widget_View([
					'widget_id' => $this->db->insert('nf_widgets', [
						'widget' => 'module',
						'type'   => 'index'
					])
				]), 'col-md-8'),
				new Col(
						new Widget_View([
							'widget_id' => $this->db->insert('nf_widgets', [
								'widget'   => 'navigation',
								'type'     => 'index',
								'settings' => serialize([
									'display' => FALSE,
									'links'   => [
										[
											'title' => utf8_htmlentities($this('news')),
											'url'   => 'news.html'
										],
										[
											'title' => utf8_htmlentities($this('members')),
											'url'   => 'members.html'
										],
										[
											'title' => utf8_htmlentities($this('search')),
											'url'   => 'search.html'
										],
										[
											'title' => utf8_htmlentities($this('contact')),
											'url'   => 'contact.html'
										]
									]
								])
							])
						]),
						new Widget_View([
							'widget_id' => $this->db->insert('nf_widgets', [
								'widget' => 'partners',
								'type'   => 'column',
								'settings' => serialize([
									'display_style' => 'light'
								])
							]),
							'style'     => 'panel-dark'
						]),
						new Widget_View([
							'widget_id' => $this->db->insert('nf_widgets', [
								'widget' => 'user',
								'type'   => 'index'
							]),
							'style'     => 'panel-dark'
						]),
						new Widget_View([
							'widget_id' => $this->db->insert('nf_widgets', [
								'widget' => 'news',
								'type'   => 'categories'
							]),
							'style'     => 'panel-default'
						]),
						new Widget_View([
							'widget_id' => $this->db->insert('nf_widgets', [
								'widget'   => 'talks',
								'type'     => 'index',
								'settings' => serialize([
									'talk_id' => 2
								])
							]),
							'style'     => 'panel-default'
						]),
						new Widget_View([
							'widget_id' => $this->db->insert('nf_widgets', [
								'widget' => 'members',
								'type'   => 'online'
							]),
							'style'     => 'panel-red'
						])
				, 'col-md-4')
			, 'row-light')
		];
		
		$dispositions['*']['{lang pre_content}'] = [
			new Row(
				new Col(
					new Widget_View([
						'widget_id' => $this->db->insert('nf_widgets', [
							'widget' => 'forum',
							'type'   => 'topics'
						]),
						'style' => 'panel-default'
					]), 'col-md-4'
				),
				new Col(
					new Widget_View([
						'widget_id' => $this->db->insert('nf_widgets', [
							'widget' => 'news',
							'type'   => 'index'
						]),
						'style' => 'panel-dark'
					]), 'col-md-4'
				),
				new Col(
					new Widget_View([
						'widget_id' => $this->db->insert('nf_widgets', [
							'widget' => 'members',
							'type'   => 'index'
						]),
						'style' => 'panel-red'
					]), 'col-md-4'
				)
			, 'row-default')
		];
		
		$dispositions['*']['{lang post_content}'] = [];
		
		$dispositions['*']['{lang header}'] = [
			$header(),
			$navbar()
		];

		$dispositions['*']['{lang top}'] = [
			new Row(
				new Col(new Widget_View([
					'widget_id' => $this->db->insert('nf_widgets', [
						'widget'   => 'navigation',
						'type'     => 'index',
						'settings' => serialize([
							'display' => TRUE,
							'links'   => [
								[
									'title' => 'Facebook',
									'url'   => '#'
								],
								[
									'title' => 'Twitter',
									'url'   => '#'
								],
								[
									'title' => 'Origin',
									'url'   => '#'
								],
								[
									'title' => 'Steam',
									'url'   => '#'
								]
							]
						])
					])
				]), 'col-md-8'),
				new Col(new Widget_View([
					'widget_id' => $this->db->insert('nf_widgets', [
						'widget' => 'members',
						'type'   => 'online_mini'
					])
				]), 'col-md-4')
			, 'row-default')
		];
		
		$dispositions['*']['{lang footer}'] = [
			new Row(
				new Col(new Widget_View([
					'widget_id' => $this->db->insert('nf_widgets', [
						'widget'   => 'html',
						'type'     => 'index',
						'settings' => serialize([
							'content' => utf8_htmlentities($this('powered_by_neofrag'))
						])
					]),
					'style' => 'panel-dark'
				]))
			, 'row-default')
		];
		
		$dispositions['/']['{lang header}'] = [
			$header(),
			$navbar(),
			new Row(
				new Col(new Widget_View([
					'widget_id' => $this->db->insert('nf_widgets', [
						'widget'   => 'slider',
						'type'     => 'index'
					])
				]), 'col-md-12')
			, 'row-default')
		];
		
		foreach (['forum/*', 'news/_news/*', 'user/*', 'search/*'] as $page)
		{
			$dispositions[$page]['{lang content}'] = [
				$breadcrumb($page != 'search/*'),
				new Row(
					new Col(new Widget_View([
						'widget_id' => $this->db->insert('nf_widgets', [
							'widget' => 'module',
							'type'   => 'index'
						])
					]), 'col-md-12')
				, 'row-light')
			];
		}
		
		$dispositions['forum/*']['{lang post_content}'] = [
			new Row(
				new Col(
					new Widget_View([
						'widget_id' => $this->db->insert('nf_widgets', [
							'widget' => 'forum',
							'type'   => 'statistics'
						]),
						'style' => 'panel-red'
					]), 'col-md-4'
				),
				new Col(
					new Widget_View([
						'widget_id' => $this->db->insert('nf_widgets', [
							'widget' => 'forum',
							'type'   => 'activity'
						]),
						'style' => 'panel-dark'
					]), 'col-md-8'
				)
			, 'row-light')
		];
		
		return parent::install($dispositions);
	}
	
	public function uninstall($remove = TRUE)
	{
		$this->file->delete($this->config->default_background);
		$this->db->where('name LIKE', 'default_%')->delete('nf_settings');
		return parent::uninstall($remove);
	}
}

/*
NeoFrag Alpha 0.1.4.1
./neofrag/themes/default/default.php
*/