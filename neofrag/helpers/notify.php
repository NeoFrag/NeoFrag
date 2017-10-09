<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

function notify($message, $type = 'success')
{
	if (!in_array($type, array_keys(get_colors())))
	{
		$type = 'success';
	}

	NeoFrag()->session->append('notifications', [
		'message' => (string)$message,
		'type'    => $type
	]);
}

function notifications()
{
	if ($notifications = NeoFrag()->session('notifications'))
	{
		foreach ($notifications as $notification)
		{
			NeoFrag()->js_load('notify(\''.$notification['message'].'\', \''.$notification['type'].'\');');
		}

		NeoFrag()->session->destroy('notifications');
	}
}
