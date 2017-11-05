<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class m_forum extends Module
{
	public $title       = '{lang forum}';
	public $description = '';
	public $icon        = 'fa-comments';
	public $link        = 'http://www.neofrag.com';
	public $author      = 'Michaël Bilcot <michael.bilcot@neofrag.com>';
	public $licence     = 'http://www.neofrag.com/license.html LGPLv3';
	public $version     = 'Alpha 0.1';
	public $nf_version  = 'Alpha 0.1';
	public $path        = __FILE__;
	public $admin       = TRUE;
	public $routes      = [
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
	];

	public static function permissions()
	{
		return [
			'category' => [
				'get_all' => function(){
					return NeoFrag()->db->select('category_id', 'CONCAT_WS(" ", "{lang category}", title)')->from('nf_forum_categories')->get();
				},
				'check'   => function($category_id){
					if (($category = NeoFrag()->db->select('title')->from('nf_forum_categories')->where('category_id', $category_id)->row()) !== [])
					{
						return '{lang category} '.$category;
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
						'title'  => '{lang category}',
						'icon'   => 'fa-navicon',
						'access' => [
							'category_read' => [
								'title' => '{lang read}',
								'icon'  => 'fa-eye'
							],
							'category_write' => [
								'title' => '{lang write}',
								'icon'  => 'fa-reply'
							]
						]
					],
					[
						'title'  => '{lang moderation}',
						'icon'   => 'fa-user',
						'access' => [
							'category_modify' => [
								'title' => '{lang edit_topic_message}',
								'icon'  => 'fa-edit'
							],
							'category_delete' => [
								'title' => '{lang remove_topic_message}',
								'icon'  => 'fa-trash-o'
							],
							'category_announce' => [
								'title' => '{lang set_topic_announce}',
								'icon'  => 'fa-flag'
							],
							'category_lock' => [
								'title' => '{lang lock_a_topic}',
								'icon'  => 'fa-lock'
							],
							'category_move' => [
								'title' => 'Déplacer un sujet',
								'icon'  => 'fa-reply fa-flip-horizontal'
							]
						]
					]
				]
			]
		];
	}
	
	public function settings()
	{
		$this	->form
				->add_rules([
					'topics_per_page' => [
						'label' => '{lang topics_per_page}',
						'value' => $this->config->forum_topics_per_page,
						'type'  => 'number',
						'rules' => 'required'
					],
					'messages_per_page' => [
						'label' => '{lang messages_per_page}',
						'value' => $this->config->forum_messages_per_page,
						'type'  => 'number',
						'rules' => 'required'
					]
				])
				->add_submit($this->lang('edit'))
				->add_back('admin/addons#modules');

		if ($this->form->is_valid($post))
		{
			$this	->config('forum_topics_per_page',   $post['topics_per_page'])
					->config('forum_messages_per_page', $post['messages_per_page']);
			
			redirect_back('admin/addons#modules');
		}

		return $this->panel()->body($this->form->display());
	}
	
	public function load()
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
			$profiles[$user_id] = $this->db	->select('u.user_id', 'u.username', 'up.avatar', 'up.signature', 'up.sex', 'u.admin', 'MAX(s.last_activity) > DATE_SUB(NOW(), INTERVAL 5 MINUTE) as online')
											->from('nf_users u')
											->join('nf_users_profiles up', 'u.user_id = up.user_id')
											->join('nf_sessions       s',  'u.user_id = s.user_id')
											->where('u.user_id', $user_id)
											->where('u.deleted', FALSE)
											->group_by('u.user_id')
											->row();

			if (empty($profiles[$user_id]))
			{
				$profiles[$user_id] = [];
			}
			else
			{
				$profiles[$user_id]['topics'] = $this->db	->select('COUNT(*)')
															->from('nf_forum_topics t')
															->join('nf_forum_messages m', 't.message_id = m.message_id')
															->where('m.user_id', $user_id)
															->row();
				
				$profiles[$user_id]['replies'] = $this->db->select('COUNT(*)')->from('nf_forum_messages')->where('user_id', $user_id)->row() - $profiles[$user_id]['topics'];
			}
		}
		
		return $this->view('profile', $data = $profiles[$user_id]);
	}
}
