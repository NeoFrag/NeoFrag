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
			'icon'        => 'far fa-handshake',
			'link'        => 'https://neofr.ag',
			'author'      => 'Michaël BILCOT & Jérémy VALENTIN <contact@neofrag.com>',
			'license'     => 'LGPLv3 <https://neofr.ag/license>',
			'admin'       => TRUE,
			'version'     => '1.0',
			'depends'     => [
				'neofrag' => 'Alpha 0.2'
			],
			'routes'      => [
				//Index
				'{id}/{url_title}'        => '_partner',

				//Admin
				'admin/{id}/{url_title*}' => '_edit'
			],
			'settings'    => function(){
				return $this->form2()
							->rule($this->form_radio('partners_logo_display')
										->title('Logo')
										->info('Utilisez les logos clairs s\'ils sont affichés sur un fond foncé')
										->value($this->config->partners_logo_display)
										->data([
											'logo_dark'  => 'Foncé',
											'logo_light' => 'Clair'
										])
							)
							->success(function($data){
								$this->config('partners_logo_display', $data['partners_logo_display']);
								notify('Configuration modifiée');
								refresh();
							});
			}
		];
	}

	public function permissions()
	{
		return [
			'default' => [
				'access'  => [
					[
						'title'  => 'Partenaires',
						'icon'   => 'far fa-star',
						'access' => [
							'add_partners' => [
								'title' => 'Ajouter',
								'icon'  => 'fas fa-plus',
								'admin' => TRUE
							],
							'modify_partners' => [
								'title' => 'Modifier',
								'icon'  => 'fas fa-edit',
								'admin' => TRUE
							],
							'delete_partners' => [
								'title' => 'Supprimer',
								'icon'  => 'far fa-trash-alt',
								'admin' => TRUE
							]
						]
					]
				]
			]
		];
	}
}
