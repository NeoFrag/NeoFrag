<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

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
												'title' => utf8_htmlentities($this->lang('home')),
												'url'   => ''
											],
											[
												'title' => utf8_htmlentities($this->lang('forum')),
												'url'   => 'forum'
											],
											[
												'title' => utf8_htmlentities($this->lang('teams')),
												'url'   => 'teams'
											],
											[
												'title' => utf8_htmlentities('Matchs'),
												'url'   => 'events/matches'
											],
											[
												'title' => utf8_htmlentities('Partenaires'),
												'url'   => 'partners'
											],
											[
												'title' => utf8_htmlentities('Palmarès'),
												'url'   => 'awards'
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
												'title' => utf8_htmlentities($this->lang('news')),
												'url'   => 'news'
											],
											[
												'title' => utf8_htmlentities($this->lang('members')),
												'url'   => 'members'
											],
											[
												'title' => utf8_htmlentities('Recrutement'),
												'url'   => 'recruits'
											],
											[
												'title' => utf8_htmlentities('Photos'),
												'url'   => 'gallery'
											],
											[
												'title' => utf8_htmlentities($this->lang('search')),
												'url'   => 'search'
											],
											[
												'title' => utf8_htmlentities($this->lang('contact')),
												'url'   => 'contact'
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
										->style('panel-dark'),
								$this	->panel_widget($this->db->insert('nf_widgets', [
											'widget' => 'user',
											'type'   => 'index'
										]))
										->style('panel-dark'),
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
										->style('panel-red')
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
								->style('panel-dark')
								->size('col-md-4')
					),
					$this->col(
						$this	->panel_widget($this->db->insert('nf_widgets', [
									'widget' => 'members',
									'type'   => 'index'
								]))
								->style('panel-red')
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
										'content' => utf8_htmlentities($this->lang('powered_by_neofrag'))
									])
								]))
								->style('panel-dark')
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
									->style('panel-red')
									->size('col-md-4')
						),
						$this->col(
							$this	->panel_widget($this->db->insert('nf_widgets', [
										'widget' => 'forum',
										'type'   => 'activity'
									]))
									->style('panel-dark')
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
