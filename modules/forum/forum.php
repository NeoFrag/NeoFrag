<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Forum;

use NF\NeoFrag\Addons\Module;

class Forum extends Module
{
	protected function __info()
	{
		return [
			'title'       => $this->lang('Forum'),
			'description' => '',
			'icon'        => 'fas fa-comments',
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
				'{id}/{url_title}{page}'                   => '_forum',
				'new/{id}/{url_title}'                     => '_new',
				'topic/{id}/{url_title}{page}'             => '_topic',
				'delete/{id}/{url_title}'                  => '_topic_delete',
				'announce/{id}/{url_title}'                => '_topic_announce',
				'lock/{id}/{url_title}'                    => '_topic_lock',
				'topic/move/{id}/{url_title}'              => '_topic_move',
				'ajax/topic/move/{id}/{url_title}'         => '_topic_move',
				'message/edit/{id}/{url_title}'            => '_message_edit',
				'message/delete/{id}/{url_title}'          => '_message_delete',
				'mark-all-as-read/{id}/{url_title}'        => '_mark_all_as_read',

				//Admin
				'admin/{id}/{url_title}'                   => '_edit',
				'admin/categories/add'                     => '_categories_add',
				'admin/categories/{id}/{url_title}'        => '_categories_edit',
				'admin/categories/delete/{id}/{url_title}' => '_categories_delete',
				'admin/ajax/categories/move'               => '_categories_move'
			],
			'settings'    => function(){
				return $this->form2()
							->rule($this->form_number('topics_per_page')
										->title('Sujets par page')
										->value($this->config->forum_topics_per_page)
							)
							->rule($this->form_number('messages_per_page')
										->title('Réponses par page')
										->value($this->config->forum_messages_per_page)
							)
							->success(function($data){
								$this	->config('forum_topics_per_page',   $data['topics_per_page'])
										->config('forum_messages_per_page', $data['messages_per_page']);
								notify('Configuration modifiée');
								refresh();
							});
			}
		];
	}

	public function permissions()
	{
		return [
			'category' => [
				'get_all' => function(){
					return NeoFrag()->db->select('category_id', 'title')->from('nf_forum_categories')->get();
				},
				'check'   => function($category_id){
					if (($category = NeoFrag()->db->select('title')->from('nf_forum_categories')->where('category_id', $category_id)->row()) !== [])
					{
						return $category;
					}
				},
				'init'    => [
					'category_read'     => [
					],
					'category_write'    => [
						['visitors', FALSE]
					],
					'category_modify'   => [
						['admins', TRUE]
					],
					'category_delete'   => [
						['admins', TRUE]
					],
					'category_announce' => [
						['admins', TRUE]
					],
					'category_lock'     => [
						['admins', TRUE]
					],
					'category_move'     => [
						['admins', TRUE]
					]
				],
				'access'  => [
					[
						'title'  => $this->lang('Catégorie'),
						'icon'   => 'fas fa-bars',
						'access' => [
							'category_read' => [
								'title' => $this->lang('Lire'),
								'icon'  => 'far fa-eye'
							],
							'category_write' => [
								'title' => $this->lang('Écrire'),
								'icon'  => 'fas fa-reply'
							]
						]
					],
					[
						'title'  => $this->lang('Modération'),
						'icon'   => 'fas fa-user',
						'access' => [
							'category_modify' => [
								'title' => $this->lang('Éditer un sujet / message'),
								'icon'  => 'fas fa-edit'
							],
							'category_delete' => [
								'title' => $this->lang('Supprimer un sujet / message'),
								'icon'  => 'far fa-trash-alt'
							],
							'category_announce' => [
								'title' => $this->lang('Mettre un sujet en annonce'),
								'icon'  => 'fas fa-flag'
							],
							'category_lock' => [
								'title' => $this->lang('Vérouiller un sujet'),
								'icon'  => 'fas fa-lock'
							],
							'category_move' => [
								'title' => 'Déplacer un sujet',
								'icon'  => 'fas fa-reply fa-flip-horizontal'
							]
						]
					]
				]
			]
		];
	}

	public function __init()
	{
		if (!$this->url->admin && !$this->url->ajax)
		{
			$this->css('forum');
		}
	}

	public function get_profile($user_id = NULL, &$data = [])
	{
		static $profiles = [];

		$user_id = (int)$user_id;

		if (!isset($profiles[$user_id]))
		{
			$profiles[$user_id] = $this->db	->select('u.id as user_id', 'u.username', 'up.avatar', 'up.signature', 'up.sex', 'u.admin', 'MAX(s.last_activity) > DATE_SUB(NOW(), INTERVAL 5 MINUTE) as online')
											->from('nf_user u')
											->join('nf_user_profile up', 'u.id = up.id')
											->join('nf_session      s',  'u.id = s.user_id')
											->where('u.id', $user_id)
											->where('u.deleted', FALSE)
											->group_by('u.id')
											->row();

			if (empty($profiles[$user_id]))
			{
				$profiles[$user_id] = [];
			}
			else
			{
				$profiles[$user_id]['topics'] = $this->db	->from('nf_forum_topics t')
															->join('nf_forum_messages m', 't.message_id = m.message_id')
															->where('m.user_id', $user_id)
															->count();

				$profiles[$user_id]['replies'] = $this->db->from('nf_forum_messages')->where('user_id', $user_id)->count() - $profiles[$user_id]['topics'];
			}
		}

		return $this->view('profile', $data = $profiles[$user_id]);
	}
}
