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

abstract class Controller extends Translatable
{
	protected $_parent;

	public function __construct($parent)
	{
		$this->load    = NeoFrag::get_loader();
		$this->_parent = $parent;
	}

	public function method($name, $args = array())
	{
		if (method_exists($this, $name))
		{
			if (!is_array($args))
			{
				if (is_null($args))
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
			$this->profiler->log($this('unknown_method', get_class($this), $name), Profiler::WARNING);
			return FALSE;
		}
	}

	public function model($model = '')
	{
		if (!$model)
		{
			$model = substr(get_class($this->_parent), strlen('m_'));
		}

		return $this->load->model($this->_parent, $model);
	}

	public function extension($extension)
	{
		if ($this->config->extension_url != $extension)
		{
			throw new Exception(NeoFrag::UNFOUND);
		}
		
		
		$this->config->extension_url = $extension;

		return $this;
	}
}

/*
NeoFrag Alpha 0.1
./neofrag/classes/controller.php
*/