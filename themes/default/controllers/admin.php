<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Themes\Default_\Controllers;

use NF\NeoFrag\Loadables\Controller;

class Admin extends Controller
{
	public function index()
	{
		$this	->js('admin')
				->form()
				->add_rules([
					'background' => [
						'label'  => $this->lang('Image de fond'),
						'value'  => $this->config->{'default_background'},
						'type'   => 'file',
						'upload' => 'themes/default/backgrounds',
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
						'value'  => $this->config->{'default_background_repeat'},
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
						'value'  => explode(' ', $this->config->{'default_background_position'})[0],
						'values' => [
							'left'   => $this->lang('Gauche'),
							'center' => $this->lang('Centré'),
							'right'  => $this->lang('Droite')
						],
						'type'   => 'radio',
						'rules'  => 'required'
					],
					'positionY' => [
						'value'  => explode(' ', $this->config->{'default_background_position'})[1],
						'values' => [
							'top'    => $this->lang('Haut'),
							'center' => $this->lang('Milieu'),
							'bottom' => $this->lang('Bas')
						],
						'type'   => 'radio',
						'rules'  => 'required'
					],
					'fixed' => [
						'value'  => $this->config->{'default_background_attachment'},
						'values' => [
							'on'  => $this->lang('Image fixe')
						],
						'type'   => 'checkbox'
					],
					'color' => [
						'label' => $this->lang('Couleur de fond'),
						'value' => $this->config->{'default_background_color'},
						'type'  => 'colorpicker',
						'rules' => 'required'
					]
				])
				->add_submit($this->lang('Valider'));

		if ($this->form()->is_valid($post))
		{
			if ($post['background'])
			{
				$this->config('default_background', $post['background'], 'int');
			}
			else
			{
				$this->config->unset('default_background');
			}

			$this	->config('default_background_repeat', $post['repeat'])
					->config('default_background_attachment', in_array('on', $post['fixed']) ? 'fixed' : 'scroll')
					->config('default_background_position', $post['positionX'].' '.$post['positionY'])
					->config('default_background_color', $post['color'])
					->config('nf_version_css', time());

			redirect('#background');
		}

		return $this->row(
			$this	->col(
						$this	->panel()
								->body($this->view('admin/menu', [
									'theme_name' => $this->__caller->info()->name
								]), FALSE)
					)
					->size('col-4 col-lg-3'),
			$this	->col(
						$this	->panel()
								->heading($this->lang('Tableau de bord'), 'fa-cog')
								->body($this->view('admin/index', [
									'theme'           => $this->__caller,
									'form_background' => $this->form()->display()
								]))
					)
					->size('col-8 col-lg-9')
		);
	}
}
