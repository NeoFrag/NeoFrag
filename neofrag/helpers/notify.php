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

function notify($message, $type = 'success')
{
	if (!in_array($type, array_keys(get_colors())))
	{
		$type = 'success';
	}

	NeoFrag::loader()->session->add('notifications', [
		'message' => $message,
		'type'    => $type
	]);
}

function notifications()
{
	if ($notifications = NeoFrag::loader()->session('notifications'))
	{
		foreach ($notifications as $notification)
		{
			NeoFrag::loader()->js_load('notify(\''.$notification['message'].'\', \''.$notification['type'].'\');');
		}

		NeoFrag::loader()->session->destroy('notifications');
	}
}

/*
NeoFrag Alpha 0.1.4.2
./neofrag/helpers/notify.php
*/