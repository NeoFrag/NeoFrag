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
			'title'       => 'Thème de base',
			'description' => 'Design minimaliste qui peut s\'adapter facilement à n\'importe quel domaine',
			'link'        => 'https://neofr.ag',
			'author'      => 'Michaël BILCOT & Jérémy VALENTIN <contact@neofrag.com>',
			'license'     => 'LGPLv3 <https://neofr.ag/license>',
			'zones'       => ['Contenu','Avant-contenu', 'Post-contenu', 'Entête', 'Haut', 'Pied de page']
		];
	}

	public function __init()
	{
		$this	->css('bootstrap.min')
				->css('icons/font-awesome.min')
				->css('fonts/titillium-web')
				->css('notify')
				->css('style')
				->js('jquery-3.2.1.min')
				->js('popper.min')
				->js('bootstrap.min')
				->js('bootstrap-notify.min')
				->js('modal')
				->js('notify');
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
				->config('default_background_color',      '#dfdfdf')
				->config('nf_version_css',                time());

		$header = function(){
			return $this->row(
					$this->col(
						$this->widget($this->db->insert('nf_widgets', [
							'widget'   => 'header',
							'type'     => 'index',
							'settings' => serialize([
								'align'             => 'text-center',
								'title'             => '',
								'description'       => '',
								'color-title'       => '',
								'color-description' => '#00d7b3'
							])
						]))
					)
				)
				->style('row-default');
		};

		$navbar = function(){
			return $this->row(
					$this->col(
						$this	->widget($this->db->insert('nf_widgets', [
									'widget'   => 'navigation',
									'type'     => 'index',
									'settings' => serialize([
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
						$this	->widget($this->db->insert('nf_widgets', [
									'widget' => 'user',
									'type'   => 'index_mini'
								]))
								->size('col-5')
					)
				)
				->style('row-white');
		};

		$breadcrumb = function($search = TRUE){
			return $this->row(
					$this->col(
						$this	->widget($this->db->insert('nf_widgets', [
										'widget' => 'breadcrumb',
										'type'   => 'index'
								]))
								->size('col-8')
					),
					$search ? $this->col(
						$this	->widget($this->db->insert('nf_widgets', [
									'widget' => 'search',
									'type'   => 'index'
								]))
								->size('col-4')
					) : NULL
				)
				->style('row-white');
		};

		$dispositions = $this->array;

		$dispositions->set('*', 'Contenu', $this->array([
			$breadcrumb(),
			$this->row(
					$this->col(
						$this	->widget($this->db->insert('nf_widgets', [
									'widget' => 'module',
									'type'   => 'index'
								]))
								->size('col-8')
					),
					$this	->col(
								$this->widget($this->db->insert('nf_widgets', [
									'widget'   => 'navigation',
									'type'     => 'vertical',
									'settings' => serialize([
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
								$this	->widget($this->db->insert('nf_widgets', [
											'widget' => 'partners',
											'type'   => 'column',
											'settings' => serialize([
												'display_style' => 'light'
											])
										]))
										->style('panel-dark'),
								$this	->widget($this->db->insert('nf_widgets', [
											'widget' => 'user',
											'type'   => 'index'
										]))
										->style('panel-dark'),
								$this->widget($this->db->insert('nf_widgets', [
									'widget' => 'news',
									'type'   => 'categories'
								])),
								$this->widget($this->db->insert('nf_widgets', [
									'widget'   => 'talks',
									'type'     => 'index',
									'settings' => serialize([
										'talk_id' => 2
									])
								])),
								$this	->widget($this->db->insert('nf_widgets', [
											'widget' => 'members',
											'type'   => 'online'
										]))
										->style('panel-red')
							)
							->size('col-4')
				)
				->style('row-default')
		]));

		$dispositions->set('*', 'Avant-contenu', $this->array([
			$this->row(
					$this->col(
						$this	->widget($this->db->insert('nf_widgets', [
									'widget' => 'forum',
									'type'   => 'topics'
								]))
								->size('col-4')
					),
					$this->col(
						$this	->widget($this->db->insert('nf_widgets', [
									'widget' => 'news',
									'type'   => 'index'
								]))
								->style('panel-dark')
								->size('col-4')
					),
					$this->col(
						$this	->widget($this->db->insert('nf_widgets', [
									'widget' => 'members',
									'type'   => 'index'
								]))
								->style('panel-red')
								->size('col-4')
					)
				)
				->style('row-default')
		]));

		$dispositions->set('*', 'Post-contenu', $this->array);

		$dispositions->set('*', 'Entête', $this->array([
			$header(),
			$navbar()
		]));

		$dispositions->set('*', 'Haut', $this->array([
			$this->row(
					$this->col(
						$this	->widget($this->db->insert('nf_widgets', [
									'widget'   => 'navigation',
									'type'     => 'index',
									'settings' => serialize([
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
						$this->widget($this->db->insert('nf_widgets', [
							'widget' => 'members',
							'type'   => 'online_mini'
						]))
						->size('col-4')
					)
				)
				->style('row-default')
		]));

		$dispositions->set('*', 'Pied de page', $this->array([
			$this->row(
					$this->col(
						$this	->widget($this->db->insert('nf_widgets', [
									'widget'   => 'copyright',
									'type'     => 'index'
								]))
								->style('panel-dark')
					)
				)
				->style('row-default')
		]));

		$dispositions->set('/', 'Entête', $this->array([
			$header(),
			$navbar(),
			$this->row(
					$this->col(
						$this->widget($this->db->insert('nf_widgets', [
							'widget'   => 'slider',
							'type'     => 'index'
						]))
					)
				)
				->style('row-default')
		]));

		foreach (['forum/*', 'news/_news/*', 'user/*', 'search/*'] as $page)
		{
			$dispositions->set($page, 'Contenu', $this->array([
				$breadcrumb($page != 'search/*'),
				$this	->row(
							$this->col(
								$this->widget($this->db->insert('nf_widgets', [
									'widget' => 'module',
									'type'   => 'index'
								]))
							)
						)
						->style('row-default')
			]));
		}

		$dispositions->set('forum/*', 'Post-contenu', $this->array([
			$this	->row(
						$this->col(
							$this	->widget($this->db->insert('nf_widgets', [
										'widget' => 'forum',
										'type'   => 'statistics'
									]))
									->style('panel-red')
									->size('col-4')
						),
						$this->col(
							$this	->widget($this->db->insert('nf_widgets', [
										'widget' => 'forum',
										'type'   => 'activity'
									]))
									->style('panel-dark')
									->size('col-8')
						)
					)
					->style('row-default')
		]));

		return parent::install($dispositions);
	}

	public function uninstall($remove = TRUE)
	{
		NeoFrag()->model2('file', $this->config->default_background)->delete();

		$this	->config->unset('default_background')
				->config->unset('default_background_repeat')
				->config->unset('default_background_attachment')
				->config->unset('default_background_position')
				->config->unset('default_background_color');

		return parent::uninstall($remove);
	}
}
