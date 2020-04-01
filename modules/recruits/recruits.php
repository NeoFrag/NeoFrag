<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Recruits;

use NF\NeoFrag\Addons\Module;

class Recruits extends Module
{
	protected function __info()
	{
		return [
			'title'       => 'Recrutements',
			'description' => '',
			'icon'        => 'fas fa-bullhorn',
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
				'{page}'                                  => 'index',
				'{id}/{url_title}'                        => '_recruit',
				'postulate/{id}/{url_title}'              => '_postulate',
				'candidacy/{id}/{url_title}'              => '_candidacy',
				//Admin
				'admin{pages}'                            => 'index',
				'admin/{id}/{url_title}'                  => '_edit',
				'admin/candidacies/{id}/{url_title}'      => '_candidacies',
				'admin/candidacy/{id}/{url_title}'        => '_candidacies_edit',
				'admin/candidacy/delete/{id}/{url_title}' => '_candidacies_delete'
			],
			'settings'    => function(){
				return $this->form2()
							->legend('Paramètres des offres')
							->rule($this->form_number('recruits_per_page')
										->title('Nombre d\'offre par page')
										->value($this->config->recruits_per_page ?: '5')
							)
							->rule($this->form_checkbox('recruits_hide_unavailable')
										->data(['on' => 'Masquer les offres indisponibles'])
										->value([$this->config->recruits_hide_unavailable ? 'on' : NULL])
							)
							->rule($this->form_checkbox('recruits_alert')
										->data(['on' => 'Être avertis par message privé des nouvelles candidatures'])
										->value([$this->config->recruits_alert ? 'on' : NULL])
							)
							->legend('Réponse aux candidats')
							->rule($this->form_checkbox('recruits_send')
										->data([
											'mp'   => 'Par message privé',
											'mail' => 'Par e-mail'
										])
										->value([
											$this->config->recruits_send_mp ? 'mp' : NULL,
											$this->config->recruits_send_mail ? 'mail' : NULL
										])
							)
							->success(function($data){
								$this	->config('recruits_per_page', $data['recruits_per_page'])
										->config('recruits_hide_unavailable', in_array('on', $data['recruits_hide_unavailable']))
										->config('recruits_alert', in_array('on', $data['recruits_alert']))
										->config('recruits_send_mp', in_array('mp', $data['recruits_send']))
										->config('recruits_send_mail', in_array('mail', $data['recruits_send']));
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
						'title'  => 'Offres de recrutement',
						'icon'   => 'fas fa-bullhorn',
						'access' => [
							'add_recruit' => [
								'title' => 'Ajouter',
								'icon'  => 'fas fa-plus',
								'admin' => TRUE
							],
							'modify_recruit' => [
								'title' => 'Modifier',
								'icon'  => 'fas fa-edit',
								'admin' => TRUE
							],
							'delete_recruit' => [
								'title' => 'Supprimer',
								'icon'  => 'far fa-trash-alt',
								'admin' => TRUE
							]
						]
					],
					[
						'title'  => 'Candidatures',
						'icon'   => 'fab fa-black-tie',
						'access' => [
							'candidacy_vote' => [
								'title' => 'Déposer son avis',
								'icon'  => 'far fa-star',
								'admin' => TRUE
							],
							'candidacy_reply' => [
								'title' => 'Accepter / Refuser les candidats',
								'icon'  => 'fas fa-lock',
								'admin' => TRUE
							],
							'candidacy_delete' => [
								'title' => 'Supprimer',
								'icon'  => 'far fa-trash-alt',
								'admin' => TRUE
							]
						]
					]
				]
			],
			'recruit' => [
				'get_all' => function(){
					return NeoFrag()->db->select('recruit_id', 'CONCAT_WS(" ", "Offre", title)')->from('nf_recruits')->get();
				},
				'check' => function($recruit_id){
					if (($recruit = NeoFrag()->db->select('title')->from('nf_recruits')->where('recruit_id', $recruit_id)->row()) !== [])
					{
						return 'Offre '.$recruit;
					}
				},
				'init' => [
					'recruit_postulate' => [
						['visitors', FALSE]
					]
				],
				'access' => [
					[
						'title'  => 'Postulants',
						'icon'   => 'fab fa-black-tie',
						'access' => [
							'recruit_postulate' => [
								'title' => 'Déposer une candidature',
								'icon'  => 'fas fa-briefcase'
							]
						]
					]
				]
			]
		];
	}
}
