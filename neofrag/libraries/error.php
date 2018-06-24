<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Libraries;

use NF\NeoFrag\Library;

class Error extends Library
{
	public function __invoke()
	{
		throw NeoFrag()->___load('', 'exception', [function(){
			header('HTTP/1.0 404 Not Found');
			return $this->view('errors/unfound');
		}]);
	}

	public function unauthorized()
	{
		throw NeoFrag()->___load('', 'exception', [function(){
			header('HTTP/1.0 403 Forbidden');
			return $this->view('errors/unauthorized');
		}]);
	}

	public function unconnected()
	{
		if (!$this->user())
		{
			throw NeoFrag()->___load('', 'exception', [function(){
				header('HTTP/1.0 401 Unauthorized');
				$this->session->append('modals', 'ajax/user/login');
				redirect();
			}]);
		}
	}
}
