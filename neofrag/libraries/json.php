<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Libraries;

use NF\NeoFrag\Library;

class Json extends Library
{
	protected $_output;
	protected $_notifications;

	public function __invoke($output, $notifications = TRUE)
	{
		$this->_output        = $output;
		$this->_notifications = $notifications;

		return $this;
	}

	public function __toString()
	{
		header('Content-Type: application/json; charset=UTF-8');

		$output = $this->_output;

		if ($this->_notifications && ($notifications = $this->session('notifications')))
		{
			$output['notify'] = array_map(function($a){
				$a['message'] = (string)$a['message'];
				return $a;
			}, $notifications);

			$this->session->destroy('notifications');
		}

		if (($output = json_encode($output)) === FALSE)
		{
			$output = json_encode([
				'error' => json_last_error_msg()
			]);
		}

		return $output;
	}
}
