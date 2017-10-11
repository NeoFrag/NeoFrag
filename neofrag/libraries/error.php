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
		throw \NeoFrag()->___load('', 'exception', [function() use ($view){
			return $this->view('errors/unfound');
		}]);
	}

	public function unautorized()
	{
		throw \NeoFrag()->___load('', 'exception', [function() use ($view){
			return $this->view('errors/unautorized');
		}]);
	}

	public function unconnected()
	{
		throw \NeoFrag()->___load('', 'exception', [function() use ($view){
			return $this->view('errors/unconnected');
		}]);
	}
}
