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

abstract class Controller extends Translatable
{
	public $load;

	public function __construct($name)
	{
		$this->name = $name;
	}

	public function method($name, $args = array())
	{
		if (method_exists($this, $name))
		{
			if (!is_array($args))
			{
				if ($args === NULL)
				{
					$args = array();
				}
				else
				{
					$args = array($args);
				}
			}

			ob_start();
			$result = call_user_func_array(array($this, $name), $args);
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
		else
		{
			return FALSE;
		}
	}

	public function model($model = NULL)
	{
		return $this->load->object->load->model($model);
	}
}

/*
NeoFrag Alpha 0.1.4
./neofrag/classes/controller.php
*/