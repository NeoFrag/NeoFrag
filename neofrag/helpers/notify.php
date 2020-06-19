<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

function notify($message, $type = 'success')
{
	NeoFrag()->session->append('notifications', [
		'message' => (string)$message,
		'type'    => get_colors($type) ? $type : 'success'
	]);
}

function notifications()
{
	if ($notifications = NeoFrag()->session('notifications'))
	{
		foreach ($notifications as $notification)
		{
			NeoFrag()->js_load('notify(\''.addcslashes($notification['message'], '\'').'\', \''.$notification['type'].'\');');
		}

		NeoFrag()->session->destroy('notifications');
	}
}
