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

abstract class Controller extends NeoFrag
{
	public $load;

	public function __construct($name)
	{
		$this->name = $name;
	}

	public function has_method($name)
	{
		$r = new ReflectionClass($this);
		
		try
		{
			$method = $r->getMethod($name);
			return $method->class == ($class = get_class($this)) || substr($class, 0, 2) == 'o_' && substr($class, 2) == $method->class;
		}
		catch (ReflectionException $error)
		{
			
		}
	}

	public function method($name, $args = [])
	{
		if (!is_array($args))
		{
			if ($args === NULL)
			{
				$args = [];
			}
			else
			{
				$args = [$args];
			}
		}

		ob_start();
		$result = call_user_func_array([$this, $name], $args);
		$output = ob_get_clean();
		
		if (!empty($result))
		{
			echo $output;
			return $result;
		}
		else
		{
			return $output;
		}
	}

	public function is_authorized($action)
	{
		return $this->access($this->load->caller->name, $action);
	}
}

/*
NeoFrag Alpha 0.1.6
./neofrag/classes/controller.php
*/