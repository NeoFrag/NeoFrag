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
			'title'       => 'Utilisateur',
			'description' => '',
			'icon'        => 'fas fa-user',
			'link'        => 'https://neofr.ag',
			'author'      => 'Michaël BILCOT & Jérémy VALENTIN <contact@neofrag.com>',
			'license'     => 'LGPLv3 <https://neofr.ag/license>',
			'admin'       => FALSE,
			'routes'      => [
				//Index
				'sessions{pages}'                            => 'sessions',
				'auth{pages}'                                => '_auth',
				'sessions/delete/{key_id}'                   => '_session_delete',
				'messages'                                   => '_messages_inbox',
				'messages/sent'                              => '_messages_sent',
				'messages/archives'                          => '_messages_archives',
				'messages/{id}/{url_title}(?:/{url_title})?' => '_messages_read',
				'messages/compose(?:/{id}/{url_title})?'     => '_messages_compose',
				'messages/delete/{id}/{url_title}'           => '_messages_delete',
				'{id}/{url_title}'                           => '_member',
				'ajax/{id}/{url_title}'                      => '_member',
				'ajax/lost-password/{url_title}'             => '_lost_password',

				//Admin
				'admin{pages}'                                   => 'index',
				'admin/groups/add'                               => '_groups_add',
				'admin/groups/edit/(admins|members|visitors)'    => '_groups_edit',
				'admin/groups/edit/{url_title}-{id}/{url_title}' => '_groups_edit',
				'admin/groups/edit/{id}/{url_title}'             => '_groups_edit',
				'admin/groups/delete/{id}/{url_title}'           => '_groups_delete',
				'admin/ajax/groups/sort'                         => '_groups_sort',
				'admin/sessions{pages}'                          => '_sessions',
				'admin/sessions/delete/{url_title}'              => '_sessions_delete'
			]
		];
	}
}
