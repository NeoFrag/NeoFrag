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
			$output['notify'] = $notifications;

			$this->session->destroy('notifications');
		}

		array_walk_recursive($output, function(&$a){
			if (is_object($a) && method_exists($a, '__toString'))
			{
				$a = (string)$a;
			}
		});

		if (($output = json_encode($output)) === FALSE)
		{
			$output = json_encode([
				'error' => json_last_error_msg()
			]);
		}

		return $output;
	}
}
