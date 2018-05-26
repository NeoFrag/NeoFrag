<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Themes\Default_;

use NF\NeoFrag\Addons\Theme;

class Default_ extends Theme
{
	protected function __info()
	{
		return [
			'title'       => $this->lang('Thème de base'),
			'description' => $this->lang('Son design est minimaliste mais générique, il peut s\'adapter facilement à n\'importe quel domaine'),
			'thumbnail'   => 'themes/default/images/thumbnail.png',
			'link'        => 'https://neofr.ag',
			'author'      => 'Michaël BILCOT & Jérémy VALENTIN <contact@neofrag.com>',
			'license'     => 'LGPLv3 <https://neofr.ag/license>',
			'zones'       => [$this->lang('Contenu'), $this->lang('Avant-contenu'), $this->lang('Post-contenu'), $this->lang('Entête'), $this->lang('Haut'), $this->lang('Pied de page')]
		];
	}

	public function __init()
	{
		$this	->css('font.open-sans.300.400.600.700.800')
				->css('font.economica.400.700')
				->css('style');
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
												'title' => utf8_htmlentities($this->lang('Accueil')),
												'url'   => ''
											],
											[
												'title' => utf8_htmlentities($this->lang('Forum')),
												'url'   => 'forum'
											],
											[
												'title' => utf8_htmlentities($this->lang('Équipes')),
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
								->size('col-7')
					),
					$this->col(
						$this	->panel_widget($this->db->insert('nf_widgets', [
									'widget' => 'user',
									'type'   => 'index_mini'
								]))
								->size('col-5')
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
								->size('col-8')
					),
					$search ? $this->col(
						$this	->panel_widget($this->db->insert('nf_widgets', [
									'widget' => 'search',
									'type'   => 'index'
								]))
								->size('col-4')
					) : NULL
				)
				->style('row-white');
		};

		$dispositions['*'][$this->lang('Contenu')] = [
			$breadcrumb(),
			$this->row(
					$this->col(
						$this	->panel_widget($this->db->insert('nf_widgets', [
									'widget' => 'module',
									'type'   => 'index'
								]))
								->size('col-8')
					),
					$this	->col(
								$this->panel_widget($this->db->insert('nf_widgets', [
									'widget'   => 'navigation',
									'type'     => 'index',
									'settings' => serialize([
										'display' => FALSE,
										'links'   => [
											[
												'title' => utf8_htmlentities($this->lang('Actualités')),
												'url'   => 'news'
											],
											[
												'title' => utf8_htmlentities($this->lang('Membres')),
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
												'title' => utf8_htmlentities($this->lang('Rechercher')),
												'url'   => 'search'
											],
											[
												'title' => utf8_htmlentities($this->lang('Contact')),
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
							->size('col-4')
				)
				->style('row-light')
		];

		$dispositions['*'][$this->lang('Avant-contenu')] = [
			$this->row(
					$this->col(
						$this	->panel_widget($this->db->insert('nf_widgets', [
									'widget' => 'forum',
									'type'   => 'topics'
								]))
								->size('col-4')
					),
					$this->col(
						$this	->panel_widget($this->db->insert('nf_widgets', [
									'widget' => 'news',
									'type'   => 'index'
								]))
								->style('panel-dark')
								->size('col-4')
					),
					$this->col(
						$this	->panel_widget($this->db->insert('nf_widgets', [
									'widget' => 'members',
									'type'   => 'index'
								]))
								->style('panel-red')
								->size('col-4')
					)
				)
				->style('row-default')
		];

		$dispositions['*'][$this->lang('Post-contenu')] = [];

		$dispositions['*'][$this->lang('Entête')] = [
			$header(),
			$navbar()
		];

		$dispositions['*'][$this->lang('Haut')] = [
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
								->size('col-8')
					),
					$this->col(
						$this->panel_widget($this->db->insert('nf_widgets', [
							'widget' => 'members',
							'type'   => 'online_mini'
						]))
						->size('col-4')
					)
				)
				->style('row-default')
		];

		$dispositions['*'][$this->lang('Pied de page')] = [
			$this->row(
					$this->col(
						$this	->panel_widget($this->db->insert('nf_widgets', [
									'widget'   => 'copyright',
									'type'     => 'index'
								]))
								->style('panel-dark')
					)
				)
				->style('row-default')
		];

		$dispositions['/'][$this->lang('Entête')] = [
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
			$dispositions[$page][$this->lang('Contenu')] = [
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

		$dispositions['forum/*'][$this->lang('Post-contenu')] = [
			$this	->row(
						$this->col(
							$this	->panel_widget($this->db->insert('nf_widgets', [
										'widget' => 'forum',
										'type'   => 'statistics'
									]))
									->style('panel-red')
									->size('col-4')
						),
						$this->col(
							$this	->panel_widget($this->db->insert('nf_widgets', [
										'widget' => 'forum',
										'type'   => 'activity'
									]))
									->style('panel-dark')
									->size('col-8')
						)
					)
					->style('row-light')
		];

		return parent::install($dispositions);
	}

	public function uninstall($remove = TRUE)
	{
		NeoFrag()->model2('file', $this->config->default_background)->delete();
		$this->db->where('name LIKE', 'default_%')->delete('nf_settings');
		return parent::uninstall($remove);
	}
}
