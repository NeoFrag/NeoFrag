<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\User;

use NF\NeoFrag\Addons\Module;

class User extends Module
{
	protected function __info()
	{
		return [
			'title'       => $this->lang('Espace membre'),
			'description' => '',
			'icon'        => 'fa-user',
			'link'        => 'https://neofr.ag',
			'author'      => 'Michaël BILCOT & Jérémy VALENTIN <contact@neofrag.com>',
			'license'     => 'LGPLv3 <https://neofr.ag/license>',
			'admin'       => FALSE,
			'routes'      => [
				//Index
				'sessions{pages}'                        => 'sessions',
				'sessions/delete/{key_id}'               => '_session_delete',
				'messages{pages}'                        => '_messages_inbox',
				'messages/sent{pages}'                   => '_messages_sent',
				'messages/archives{pages}'               => '_messages_archives',
				'messages/{id}/{url_title}'              => '_messages_read',
				'messages/compose(?:/{id}/{url_title})?' => '_messages_compose',
				'messages/delete/{id}/{url_title}'       => '_messages_delete',
				'lost-password/{key_id}'                 => '_lost_password',
				'auth/{url_title}'                       => '_auth',
				'{id}/{url_title}'                       => '_member',
				'ajax/{id}/{url_title}'                  => '_member',

				//Admin
				'admin{pages}'                                   => 'index',
				'admin/{id}/{url_title}'                         => '_edit',
				'admin/ban'                                      => '_ban',
				'admin/ban/{id}/{url_title}'                     => '_ban',
				'admin/groups/add'                               => '_groups_add',
				'admin/groups/edit/(admins|members|visitors)'    => '_groups_edit',
				'admin/groups/edit/{url_title}-{id}/{url_title}' => '_groups_edit',
				'admin/groups/edit/{id}/{url_title}'             => '_groups_edit',
				'admin/groups/delete/{id}/{url_title}'           => '_groups_delete',
				'admin/ajax/groups/sort'                         => '_groups_sort',
				'admin/sessions{pages}'                          => '_sessions',
				'admin/sessions/delete/{url_title}'              => '_sessions_delete'
			],
			'crud'        => [
				'session_history'
			]
		];
	}
}
