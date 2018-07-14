<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Events;

use NF\NeoFrag\Addons\Module;

class Events extends Module
{
	protected function __info()
	{
		return [
			'title'       => $this->lang('Événements'),
			'description' => '',
			'icon'        => 'fa-calendar',
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
				'{page}'                                    => 'index',
				'standards{page}'                           => 'standards',
				'matches{page}'                             => 'matches',
				'upcoming{page}'                            => 'upcoming',
				'{id}/{url_title}'                          => '_event',
				'type/{id}/{url_title}{page}'               => '_type',
				'team/{id}/{url_title}{page}'               => '_team',
				'participant/{id}/{url_title}/{id}'         => '_participant_add',
				'participant/delete/{id}/{url_title}/{id}'  => '_participant_delete',

				//Ajax
				'ajax/{id}/{url_title}'                     => '_event',

				//Admin
				'admin{pages}'                              => 'index',
				'admin/{id}/{url_title}'                    => '_edit',
				'admin/types/add'                           => '_types_add',
				'admin/types/{id}/{url_title}'              => '_types_edit',
				'admin/types/delete/{id}/{url_title}'       => '_types_delete',
				'admin/rounds/delete/{id}/{url_title}/{id}' => '_round_delete'
			],
			'settings'    => function(){
				return $this->form2()
							->rule($this->form_number('events_per_page')
										->title($this->lang('Nombre d\'événement par page'))
										->value($this->config->events_per_page ?: '10')
							)
							->rule($this->form_checkbox('events_alert_mp')
										->data([
											'on' => lang('Être averti par message privé des invitations')
										])
										->value([$this->config->events_alert_mp ? 'on' : NULL])
							)
							->success(function($data){
								$this	->config('events_per_page', $data['events_per_page'])
										->config('events_alert_mp', in_array('on', $data['events_alert_mp']));
								notify(lang('Configuration modifiée'));
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
						'title'  => lang('Événements'),
						'icon'   => 'fa-calendar',
						'access' => [
							'add_event' => [
								'title' => lang('Ajouter'),
								'icon'  => 'fa-plus',
								'admin' => TRUE
							],
							'modify_event' => [
								'title' => lang('Modifier'),
								'icon'  => 'fa-edit',
								'admin' => TRUE
							],
							'delete_event' => [
								'title' => lang('Supprimer'),
								'icon'  => 'fa-trash-o',
								'admin' => TRUE
							]
						]
					],
					[
						'title'  => lang('Types'),
						'icon'   => 'fa-bookmark-o',
						'access' => [
							'add_events_type' => [
								'title' => lang('Ajouter un type'),
								'icon'  => 'fa-plus',
								'admin' => TRUE
							],
							'modify_events_type' => [
								'title' => lang('Modifier un type'),
								'icon'  => 'fa-edit',
								'admin' => TRUE
							],
							'delete_events_type' => [
								'title' => lang('Supprimer un type'),
								'icon'  => 'fa-trash-o',
								'admin' => TRUE
							]
						]
					]
				]
			],
			'type' => [
				'get_all' => function(){
					return NeoFrag()->db->select('type_id', 'CONCAT_WS(" ", "Type", title)')->from('nf_events_types')->get();
				},
				'check'   => function($type_id){
					if (($type = NeoFrag()->db->select('title')->from('nf_events_types')->where('type_id', $type_id)->row()) !== [])
					{
						return 'Type '.$type;
					}
				},
				'init'    => [
					'access_events_type' => []
				],
				'access'  => [
					[
						'title'  => lang('Types'),
						'icon'   => 'fa-bookmark-o',
						'access' => [
							'access_events_type' => [
								'title' => lang('Visibilité'),
								'icon'  => 'fa-eye'
							]
						]
					]
				]
			]
		];
	}

	public function comments($event_id)
	{
		$event = $this->db	->select('title')
							->from('nf_events')
							->where('event_id', $event_id)
							->row();

		if ($event)
		{
			return [
				'title' => $event,
				'url'   => 'events/'.$event_id.'/'.url_title($event)
			];
		}
	}
}
