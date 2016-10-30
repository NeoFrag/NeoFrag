<?php if (!defined('NEOFRAG_CMS')) exit;
/**************************************************************************
Copyright © 2015 Michaël BILCOT & Jérémy VALENTIN

This file is part of NeoFrag.

NeoFrag is free software: you can redistribute it and/or modify
it under the terms of the GNU Lesser General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

NeoFrag is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with NeoFrag. If not, see <http://www.gnu.org/licenses/>.
**************************************************************************/

class m_user extends Module
{
	public $title         = '{lang member_area}';
	public $description   = '';
	public $icon          = 'fa-user';
	public $link          = 'http://www.neofrag.com';
	public $author        = 'Michaël Bilcot <michael.bilcot@neofrag.com>';
	public $licence       = 'http://www.neofrag.com/license.html LGPLv3';
	public $version       = 'Alpha 0.1';
	public $nf_version    = 'Alpha 0.1';
	public $routes        = [
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
		'{id}/{url_title}'                       => '_member',
		'ajax/{id}/{url_title}'                  => '_member',
		
		//Admin
		'admin{pages}'                                    => 'index',
		'admin/{id}/{url_title}'                          => '_edit',
		'admin/ban'                                       => '_ban',
		'admin/ban/{id}/{url_title}'                      => '_ban',
		'admin/groups/add'                                => '_groups_add',
		'admin/groups/edit/(admins|members|visitors)'     => '_groups_edit',
		'admin/groups/edit/{url_title}-{id}/{url_title}'  => '_groups_edit',
		'admin/groups/edit/{id}/{url_title}'              => '_groups_edit',
		'admin/groups/delete/{id}/{url_title}'            => '_groups_delete',
		'admin/sessions{pages}'                           => '_sessions',
		'admin/sessions/delete/{url_title}'               => '_sessions_delete'
	];
	public $path          = __FILE__;
}

/*
NeoFrag Alpha 0.1.5
./neofrag/modules/user/user.php
*/