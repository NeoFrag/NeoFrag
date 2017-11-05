<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class m_events extends Module
{
	public $title       = 'Événements';
	public $description = '';
	public $icon        = 'fa-calendar';
	public $link        = 'http://www.neofrag.com';
	public $author      = 'Michaël Bilcot <michael.bilcot@neofrag.com>';
	public $licence     = 'http://www.neofrag.com/license.html LGPLv3';
	public $version     = 'Alpha 0.1.6';
	public $nf_version  = 'Alpha 0.1.6';
	public $path        = __FILE__;
	public $admin       = TRUE;
	public $routes      = [
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
	];

	public function settings()
	{
		$this	->form
				->add_rules([
					'events_per_page' => [
						'label'       => 'Nombre d\'événement par page',
						'value'       => $this->config->events_per_page ?: '10',
						'type'        => 'number',
						'rules'       => 'required',
						'size'        => 'col-md-2'
					],
					'events_alert_mp' => [
						'type'        => 'checkbox',
						'checked'     => ['on' => $this->config->events_alert_mp],
						'values'      => ['on' => 'Être averti par message privé des invitations']
					]
				])
				->add_submit($this->lang('edit'))
				->add_back('admin/addons#modules');

		if ($this->form->is_valid($post))
		{
			$this	->config('events_per_page', $post['events_per_page'])
					->config('events_alert_mp', in_array('on', $post['events_alert_mp']));

			redirect_back('admin/addons#modules');
		}

		return $this->panel()
					->body($this->form->display());
	}

	public static function permissions()
	{
		return [
			'default' => [
				'access'  => [
					[
						'title'  => 'Événements',
						'icon'   => 'fa-calendar',
						'access' => [
							'add_event' => [
								'title' => 'Ajouter',
								'icon'  => 'fa-plus',
								'admin' => TRUE
							],
							'modify_event' => [
								'title' => 'Modifier',
								'icon'  => 'fa-edit',
								'admin' => TRUE
							],
							'delete_event' => [
								'title' => 'Supprimer',
								'icon'  => 'fa-trash-o',
								'admin' => TRUE
							]
						]
					],
					[
						'title'  => 'Types',
						'icon'   => 'fa-bookmark-o',
						'access' => [
							'add_events_type' => [
								'title' => 'Ajouter un type',
								'icon'  => 'fa-plus',
								'admin' => TRUE
							],
							'modify_events_type' => [
								'title' => 'Modifier un type',
								'icon'  => 'fa-edit',
								'admin' => TRUE
							],
							'delete_events_type' => [
								'title' => 'Supprimer un type',
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
						'title'  => 'Types',
						'icon'   => 'fa-bookmark-o',
						'access' => [
							'access_events_type' => [
								'title' => 'Visibilité',
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
