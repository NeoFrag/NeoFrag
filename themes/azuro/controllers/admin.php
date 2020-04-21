<?php
/**
 * https://neofr.ag
 * @author: Jérémy VALENTIN <jeremy.valentin@neofr.ag>
 */

namespace NF\Themes\Azuro\Controllers;

use NF\NeoFrag\Loadables\Controller;

class Admin extends Controller
{
	public function index()
	{
		$this->js('admin');

		$form_background = $this->form()
								->add_rules([
									'background' => [
										'label'  => $this->lang('Image de fond'),
										'value'  => $this->config->{'azuro_background'},
										'type'   => 'file',
										'upload' => 'themes/azuro/backgrounds',
										'info'   => $this->lang(' d\'image (max. %d Mo)', file_upload_max_size() / 1024 / 1024),
										'check'  => function($filename, $ext){
											if (!in_array($ext, ['gif', 'jpeg', 'jpg', 'png']))
											{
												return $this->lang('Veuiller choisir un fichier d\'image');
											}
										}
									],
									'repeat' => [
										'label'  => $this->lang('Répéter l\'image'),
										'value'  => $this->config->{'azuro_background_repeat'},
										'values' => [
											'no-repeat' => $this->lang('Non'),
											'repeat-x'  => $this->lang('Horizontalement'),
											'repeat-y'  => $this->lang('Verticalement'),
											'repeat'    => $this->lang('Les deux')
										],
										'type'   => 'radio',
										'rules'  => 'required'
									],
									'positionX' => [
										'label'  => $this->lang('Position'),
										'value'  => explode(' ', $this->config->{'azuro_background_position'})[0],
										'values' => [
											'left'   => $this->lang('Gauche'),
											'center' => $this->lang('Centré'),
											'right'  => $this->lang('Droite')
										],
										'type'   => 'radio',
										'rules'  => 'required'
									],
									'positionY' => [
										'value'  => explode(' ', $this->config->{'azuro_background_position'})[1],
										'values' => [
											'top'    => $this->lang('Haut'),
											'center' => $this->lang('Milieu'),
											'bottom' => $this->lang('Bas')
										],
										'type'   => 'radio',
										'rules'  => 'required'
									],
									'fixed' => [
										'checked' => ['on' => $this->config->{'azuro_background_attachment'} == 'fixed'],
										'values'  => ['on' => $this->lang('Image fixe')],
										'type'    => 'checkbox'
									],
									'color' => [
										'label' => $this->lang('Couleur de fond'),
										'value' => $this->config->{'azuro_background_color'},
										'type'  => 'colorpicker',
										'rules' => 'required',
										'size'  => 'col-3'
									]
								])
								->add_submit($this->lang('Valider'))
								->save();

		$form_colors = $this->form()
							->add_rules([
								'primary' => [
									'label' => $this->lang('Couleur principale'),
									'value' => $this->config->{'azuro_primary_color'},
									'type'  => 'colorpicker',
									'rules' => 'required',
									'size'  => 'col-3'
								],
								'secondary' => [
									'label' => $this->lang('Couleur secondaire'),
									'value' => $this->config->{'azuro_secondary_color'},
									'type'  => 'colorpicker',
									'rules' => 'required',
									'size'  => 'col-3'
								],
								'text' => [
									'label' => $this->lang('Couleur du texte'),
									'value' => $this->config->{'azuro_text_color'},
									'type'  => 'colorpicker',
									'rules' => 'required',
									'size'  => 'col-3'
								]
							])
							->add_submit($this->lang('Valider'))
							->save();

		if ($form_background->is_valid($post))
		{
			if ($post['background'])
			{
				$this->config('azuro_background', $post['background'], 'int');
			}
			else
			{
				$this->config->unset('azuro_background');
			}

			$this	->config('azuro_background_repeat',     $post['repeat'])
					->config('azuro_background_attachment', in_array('on', $post['fixed']) ? 'fixed' : 'scroll')
					->config('azuro_background_position',   $post['positionX'].' '.$post['positionY'])
					->config('azuro_background_color',      $post['color']);

			$this->module('tools')->api()->scss();

			notify($this->lang('Arrière plan de l\'entête mis à jour !'));

			redirect($this->url->location.'#background');
		}
		else if ($form_colors->is_valid($post))
		{
			$this	->config('azuro_primary_color',   $post['primary'])
					->config('azuro_secondary_color', $post['secondary'])
					->config('azuro_text_color',      $post['text']);

			$this->module('tools')->api()->scss();

			notify($this->lang('Couleurs du thème mises à jour !'));

			redirect($this->url->location.'#colors');
		}

		return $this->row(
			$this	->col(
						$this	->panel()
								->body($this->view('admin/menu'), FALSE)
					)
					->size('col-4 col-lg-3'),
			$this	->col(
						$this	->panel()
								->heading($this->lang('Tableau de bord'), 'fas fa-cog')
								->body($this->view('admin/index', [
									'theme'           => $this->__caller->info(),
									'form_background' => $form_background->display(),
									'form_colors'     => $form_colors->display()
								]))
					)
					->size('col-8 col-lg-9')
		);
	}
}
