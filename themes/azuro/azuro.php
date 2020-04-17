<?php
/**
 * https://neofr.ag
 * @author: Jérémy VALENTIN <jeremy.valentin@neofr.ag>
 */

namespace NF\Themes\Azuro;

use NF\NeoFrag\Addons\Theme;

class Azuro extends Theme
{
	protected function __info()
	{
		return [
			'title'       => 'Azuro',
			'description' => 'Thème gaming',
			'link'        => 'https://neofr.ag',
			'author'      => 'Jérémy VALENTIN <jeremy.valentin@neofr.ag>',
			'license'     => 'LGPLv3 <https://neofr.ag/license>',
			'version'     => '1.0.0',
			'depends' => [
				'neofrag' => 'Alpha 0.2.1'
			],
			'zones'       => ['Haut', 'Entête', 'Menu', 'Slider', 'Avant-contenu', 'Contenu', 'Post-contenu', 'Pied de page']
		];
	}

	public function __init()
	{
		$this	->css('bootstrap.min')
				->css('icons/fontawesome.min')
				->css('fonts/open-sans')
				->css('fonts/titillium-web')
				->css('style')
				->js('jquery-3.2.1.min')
				->js('popper.min')
				->js('bootstrap.min')
				->js('bootstrap-notify.min')
				->js('modal')
				->js('notify')
				->js('azuro');
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
		$this	->config('azuro_background',            0, 'int')
				->config('azuro_background_repeat',     'no-repeat')
				->config('azuro_background_attachment', 'scroll')
				->config('azuro_background_position',   'center top')
				->config('azuro_background_color',      '#343a40')
				->config('azuro_primary_color',         '#00d7b3')
				->config('azuro_secondary_color',       '#00c7e4')
				->config('azuro_text_color',            '#212529');

		$header = function(){
			return $this->row(
					$this->col(
						$this->widget($this->db->insert('nf_widgets', [
							'widget'   => 'header',
							'type'     => 'index',
							'settings' => serialize([
								'display'           => 'logo',
								'align'             => 'text-center',
								'title'             => '',
								'description'       => '',
								'color_title'       => '#fff',
								'color_description' => '#a4b5c5'
							])
						]))
					)
				)
				->style('align-items-center');
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
												'title' => utf8_htmlentities($this->lang('Matchs')),
												'url'   => 'events/matches'
											],
											[
												'title' => utf8_htmlentities($this->lang('Partenaires')),
												'url'   => 'partners'
											],
											[
												'title' => utf8_htmlentities($this->lang('Palmarès')),
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
				->style('align-items-center');
		};

		$dispositions = $this->array();

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
							->size('col-7')
				),
				$this->col(
					$this->widget($this->db->insert('nf_widgets', [
						'widget' => 'members',
						'type'   => 'online_mini'
					]))
					->size('col-3')
				),
				$this->col(
					$this->widget($this->db->insert('nf_widgets', [
								'widget' => 'search',
								'type'   => 'index'
							]))
					->size('col-2')
				)
			)
			->style('align-items-center')
		]));

		$dispositions->set('*', 'Entête', $this->array([
			$header()
		]));

		$dispositions->set('*', 'Menu', $this->array([
			$navbar()
		]));

		$dispositions->set('*', 'Contenu', $this->array([
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
												'title' => utf8_htmlentities($this->lang('Recrutement')),
												'url'   => 'recruits'
											],
											[
												'title' => utf8_htmlentities($this->lang('Photos')),
												'url'   => 'gallery'
											],
											[
												'title' => utf8_htmlentities($this->lang('Événements')),
												'url'   => 'events'
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
								]))->style('card-dark'),
								$this->widget($this->db->insert('nf_widgets', [
									'widget' => 'partners',
									'type'   => 'column',
									'settings' => serialize([
										'display_style' => 'dark'
									])
								])),
								$this->widget($this->db->insert('nf_widgets', [
									'widget' => 'user',
									'type'   => 'index'
								])),
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
								$this->widget($this->db->insert('nf_widgets', [
									'widget' => 'members',
									'type'   => 'online'
								]))
							)
							->size('col-4')
				)
		]));

		$dispositions->set('*', 'Pied de page', $this->array([
			$this->row(
				$this->col(
					$this	->widget($this->db->insert('nf_widgets', [
								'widget'   => 'copyright',
								'type'     => 'index'
							]))
							->style('card-transparent')
				)
			)
		]));

		$dispositions->set('/', 'Slider', $this->array([
			$this->row(
				$this->col(
					$this->widget($this->db->insert('nf_widgets', [
						'widget'   => 'slider',
						'type'     => 'index'
					]))
				)
			)
		]));

		$dispositions->set('/', 'Avant-contenu', $this->array([
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
							->size('col-4')
				),
				$this->col(
					$this	->widget($this->db->insert('nf_widgets', [
								'widget' => 'members',
								'type'   => 'index'
							]))
							->size('col-4')
				)
			)
		]));

		foreach (['forum/*', 'news/_news/*', 'user/*', 'search/*', 'gallery/*'] as $page)
		{
			$dispositions->set($page, 'Contenu', $this->array([
				$this->row(
					$this->col(
						$this->widget($this->db->insert('nf_widgets', [
							'widget' => 'module',
							'type'   => 'index'
						]))
					)
				)
			]));
		}

		$dispositions->set('forum/*', 'Post-contenu', $this->array([
			$this->row(
				$this->col(
					$this	->widget($this->db->insert('nf_widgets', [
								'widget' => 'forum',
								'type'   => 'statistics'
							]))
							->style('card-dark')
							->size('col-4')
				),
				$this->col(
					$this	->widget($this->db->insert('nf_widgets', [
								'widget' => 'forum',
								'type'   => 'activity'
							]))
							->style('card-dark')
							->size('col-8')
				)
			)
		]));

		return parent::install($dispositions);
	}

	public function uninstall($remove = TRUE)
	{
		NeoFrag()->model2('file', $this->config->azuro_background)->delete();

		$this	->config->unset('azuro_background')
				->config->unset('azuro_background_repeat')
				->config->unset('azuro_background_attachment')
				->config->unset('azuro_background_position')
				->config->unset('azuro_background_color')
				->config->unset('azuro_primary_color')
				->config->unset('azuro_secondary_color')
				->config->unset('azuro_text_color');

		return parent::uninstall($remove);
	}
}
