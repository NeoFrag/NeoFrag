<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Partners;

use NF\NeoFrag\Addons\Module;

class Partners extends Module
{
	protected function __info()
	{
		return [
			'title'       => 'Partenaires',
			'description' => '',
			'icon'        => 'fa-star-o',
			'link'        => 'https://neofr.ag',
			'author'      => 'Michaël BILCOT & Jérémy VALENTIN <contact@neofrag.com>',
			'license'     => 'LGPLv3 <https://neofr.ag/license>',
			'admin'       => TRUE,
			'version'     => '1.0',
			'depends'     => [
				'neofrag' => 'Alpha 0.1.7'
			],
			'routes'      => [
				//Index
				'{id}/{url_title}'        => '_partner',

				//Admin
				'admin/{id}/{url_title*}' => '_edit'
			]
		];
	}

	public function settings()
	{
		$this	->form
				->add_rules([
					'partners_logo_display' => [
						'label'       => 'Logo',
						'value'       => $this->config->partners_logo_display,
						'values'      => [
							'logo_dark'  => 'Foncé',
							'logo_light' => 'Clair'
						],
						'type'        => 'radio',
						'description' => 'Utilisez les logos clairs s\'ils sont affichés sur un fond foncé',
						'size'        => 'col-md-4'
					]
				])
				->add_submit($this->lang('edit'))
				->add_back('admin/addons#modules');

		if ($this->form->is_valid($post))
		{
			$this->config('partners_logo_display', $post['partners_logo_display']);

			redirect_back('admin/addons#modules');
		}

		return $this->panel()->body($this->form->display());
	}
}
