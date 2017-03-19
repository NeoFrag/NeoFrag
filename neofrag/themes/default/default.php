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
		return $this->view('live_editor/row');
	}
	
	public function styles_widget()
	{
		return $this->view('live_editor/widget');
	}
	
	public function install($dispositions = [])
	{
		$this	->config('default_background',            0, 'int')
				->config('default_background_repeat',     'no-repeat')
				->config('default_background_attachment', 'scroll')
				->config('default_background_position',   'center top')
				->config('default_background_color',      '#141d26')
				->config('nf_version_css',                time());
		
		$header = function(){
			return $this->row(
					$this->col(
						$this->panel_widget($this->db->insert('nf_widgets', [
							'widget'   => 'header',
							'type'     => 'index',
							'settings' => serialize([
								'align'             => 'text-center',
								'title'             => '',
								'description'       => '',
								'color-title'       => '',
								'color-description' => '#DC351E'
							])
						]))
					)
				)
				->style('row-default');
		};
		
		$navbar = function(){
			return $this->row(
					$this->col(
						$this	->panel_widget($this->db->insert('nf_widgets', [
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
								]))
								->size('col-md-7')
					),
					$this->col(
						$this	->panel_widget($this->db->insert('nf_widgets', [
									'widget' => 'user',
									'type'   => 'index_mini'
								]))
								->size('col-md-5')
					)
				)
				->style('row-black');
		};
		
		$breadcrumb = function($search = TRUE){
			return $this->row(
					$this->col(
						$this	->panel_widget($this->db->insert('nf_widgets', [
										'widget' => 'breadcrumb',
										'type'   => 'index'
								]))
								->size('col-md-8')
					),
					$search ? $this->col(
						$this	->panel_widget($this->db->insert('nf_widgets', [
									'widget' => 'search',
									'type'   => 'index'
								]))
								->size('col-md-4')
					) : NULL
				)
				->style('row-white');
		};
		
		$dispositions['*']['{lang content}'] = [
			$breadcrumb(),
			$this->row(
					$this->col(
						$this	->panel_widget($this->db->insert('nf_widgets', [
									'widget' => 'module',
									'type'   => 'index'
								]))
								->size('col-md-8')
					),
					$this	->col(
								$this->panel_widget($this->db->insert('nf_widgets', [
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
								])),
								$this	->panel_widget($this->db->insert('nf_widgets', [
											'widget' => 'partners',
											'type'   => 'column',
											'settings' => serialize([
												'display_style' => 'light'
											])
										]))
										->color('dark'),
								$this	->panel_widget($this->db->insert('nf_widgets', [
											'widget' => 'user',
											'type'   => 'index'
										]))
										->color('dark'),
								$this->panel_widget($this->db->insert('nf_widgets', [
									'widget' => 'news',
									'type'   => 'categories'
								])),
								$this->panel_widget($this->db->insert('nf_widgets', [
									'widget'   => 'talks',
									'type'     => 'index',
									'settings' => serialize([
										'talk_id' => 2
									])
								])),
								$this	->panel_widget($this->db->insert('nf_widgets', [
											'widget' => 'members',
											'type'   => 'online'
										]))
										->color('red')
							)
							->size('col-md-4')
				)
				->style('row-light')
		];
		
		$dispositions['*']['{lang pre_content}'] = [
			$this->row(
					$this->col(
						$this	->panel_widget($this->db->insert('nf_widgets', [
									'widget' => 'forum',
									'type'   => 'topics'
								]))
								->size('col-md-4')
					),
					$this->col(
						$this	->panel_widget($this->db->insert('nf_widgets', [
									'widget' => 'news',
									'type'   => 'index'
								]))
								->color('dark')
								->size('col-md-4')
					),
					$this->col(
						$this	->panel_widget($this->db->insert('nf_widgets', [
									'widget' => 'members',
									'type'   => 'index'
								]))
								->color('red')
								->size('col-md-4')
					)
				)
				->style('row-default')
		];
		
		$dispositions['*']['{lang post_content}'] = [];
		
		$dispositions['*']['{lang header}'] = [
			$header(),
			$navbar()
		];

		$dispositions['*']['{lang top}'] = [
			$this->row(
					$this->col(
						$this	->panel_widget($this->db->insert('nf_widgets', [
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
								]))
								->size('col-md-8')
					),
					$this->col(
						$this->panel_widget($this->db->insert('nf_widgets', [
							'widget' => 'members',
							'type'   => 'online_mini'
						]))
						->size('col-md-4')
					)
				)
				->style('row-default')
		];
		
		$dispositions['*']['{lang footer}'] = [
			$this->row(
					$this->col(
						$this	->panel_widget($this->db->insert('nf_widgets', [
									'widget'   => 'html',
									'type'     => 'index',
									'settings' => serialize([
										'content' => utf8_htmlentities($this('powered_by_neofrag'))
									])
								]))
								->color('dark')
					)
				)
				->style('row-default')
		];
		
		$dispositions['/']['{lang header}'] = [
			$header(),
			$navbar(),
			$this->row(
					$this->col(
						$this->panel_widget($this->db->insert('nf_widgets', [
							'widget'   => 'slider',
							'type'     => 'index'
						]))
					)
				)
				->style('row-default')
		];
		
		foreach (['forum/*', 'news/_news/*', 'user/*', 'search/*'] as $page)
		{
			$dispositions[$page]['{lang content}'] = [
				$breadcrumb($page != 'search/*'),
				$this	->row(
							$this->col(
								$this->panel_widget($this->db->insert('nf_widgets', [
									'widget' => 'module',
									'type'   => 'index'
								]))
							)
						)
						->style('row-light')
			];
		}
		
		$dispositions['forum/*']['{lang post_content}'] = [
			$this	->row(
						$this->col(
							$this	->panel_widget($this->db->insert('nf_widgets', [
										'widget' => 'forum',
										'type'   => 'statistics'
									]))
									->color('red')
									->size('col-md-4')
						),
						$this->col(
							$this	->panel_widget($this->db->insert('nf_widgets', [
										'widget' => 'forum',
										'type'   => 'activity'
									]))
									->color('dark')
									->size('col-md-8')
						)
					)
					->style('row-light')
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
NeoFrag Alpha 0.1.5
./neofrag/themes/default/default.php
*/