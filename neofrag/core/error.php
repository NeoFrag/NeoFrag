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
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with NeoFrag. If not, see <http://www.gnu.org/licenses/>.
**************************************************************************/

class Error extends Core
{
	static private $_errors = '';

	static public function error_handler($errno, $errstr, $errfile, $errline)
	{
		$error = '<p>';
		
		if (in_array($errno, array(E_USER_WARNING, E_USER_NOTICE)))
		{
			foreach (debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS) as $debug)
			{
				if (isset($found))
				{
					$errfile = $debug['file'];
					$errline = $debug['line'];

					break;
				}

				if ($debug['function'] == 'error')
				{
					$found = TRUE;
				}
			}
		}

		if ($errno == E_USER_ERROR)
		{
			$error .= '<span class="label label-danger">Error</span> ';
		}
		else if (in_array($errno, array(E_USER_WARNING, E_WARNING)))
		{
			$error .= '<span class="label label-warning">Warning</span> ';
		}
		else if (in_array($errno, array(E_USER_NOTICE, E_NOTICE)))
		{
			$error .= '<span class="label label-info">Notice</span> ';
		}
		else if (in_array($errno, array(E_DEPRECATED)))
		{
			//$error .= '<span class="label label-info">Deprecated</span> ';
			return TRUE;
		}
		else if ($errno == E_STRICT)
		{
			$error .= '<span class="label label-default">Strict Standards</span> ';
		}

		Error::$_errors .= $error.$errstr.' in <b>'.$errfile.'</b> on line <b>'.$errline.'</b></p>';
		
		return TRUE;
	}

	public function __construct()
	{
		parent::__construct();

		set_error_handler(array('Error', 'error_handler'));
	}

	public function __invoke($error_msg, $error_type = E_USER_NOTICE)
	{
		trigger_error($error_msg, $error_type);
	}

	public function has_errors()
	{
		return !empty(Error::$_errors);
	}

	public function display()
	{
		return Error::$_errors;
	}
	
	public function profiler()
	{
		if (!$this->has_errors())
		{
			return '';
		}

		return '	<a href="#" data-profiler="error"><i class="icon-chevron-'.(($this->session('profiler', 'error')) ? 'down' : 'up').' pull-right"></i></a>
					<h2>Errors</h2>
					<div class="profiler-block">
						'.$this->display().'
					</div>';
	}
}

/*
NeoFrag Alpha 0.1
./neofrag/core/error.php
*/