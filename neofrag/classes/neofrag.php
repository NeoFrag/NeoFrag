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

abstract class NeoFrag
{
	const UNFOUND      = 0;
	const UNAUTHORIZED = 1;
	const UNCONNECTED  = 2;

	const LIVE_EDITOR  = 1;
	const ZONES        = 2;
	const ROWS         = 4;
	const COLS         = 8;
	const WIDGETS      = 16;

	static public $route_patterns = [
		'id'         => '([0-9]+?)',
		'key_id'     => '([a-z0-9]+?)',
		'url_title'  => '([a-z0-9-]+?)',
		'url_title*' => '([a-z0-9-/]+?)',
		'page'       => '((?:/?page/[0-9]+?)?)',
		'pages'      => '((?:/?(?:all|page/[0-9]+?(?:/(?:10|25|50|100))?))?)'
	];

	static public function loader()
	{
		static $NF;
		
		if ($NF === NULL)
		{
			global $NeoFrag;
			$NF = $NeoFrag;
		}
		
		return $NF;
	}
	
	static public function live_editor()
	{
		if (($live_editor = post('live_editor')) && NeoFrag::loader()->user('admin'))
		{
			NeoFrag::loader()->session->set('live_editor', $live_editor);
			return $live_editor;
		}
		
		return FALSE;
	}

	public function __isset($name)
	{
		$loader = is_a($this, 'Loader') ? $this : $this->load;
		return isset($loader->libraries[$name]) || isset(NeoFrag::loader()->libraries[$name]);
	}

	public function __get($name)
	{
		$loader = is_a($this, 'Loader') ? $this : $this->load;

		if (isset($loader->libraries[$name]))
		{
			return $loader->libraries[$name];
		}
		else if (isset(NeoFrag::loader()->libraries[$name]))
		{
			return NeoFrag::loader()->libraries[$name];
		}
		else
		{
			$type = 'libraries';
			
			if (preg_match('/^core_(.+)/', $name, $match))
			{
				$name = $match[1];
				$type = 'core';
			}
			
			foreach ($loader->paths[$type] as $dir)
			{
				if (!check_file($path = $dir.'/'.$name.'.php'))
				{
					continue;
				}

				require_once $path;

				foreach ($loader->paths['config'] as $dir)
				{
					if (check_file($path = $dir.'/'.$name.'.php'))
					{
						include $path;
					}
				}

				$class = u2ucc($name);

				if (isset($$name))
				{
					$library = load($class, $$name);
				}
				else
				{
					$library = load($class);
				}

				if (!isset($library->load))
				{
					$library->load = $loader;
				}

				array_unshift($loader->paths['views'], 'overrides/views/'.$name, 'neofrag/views/'.$name);

				return $loader->libraries[$library->name = $name] = $library->set_id();
			}
		}

		trigger_error('Undefined property: '.get_class($this).'::$'.$name, E_USER_WARNING);
	}

	public function __call($name, $args)
	{
		if (is_callable($library = $this->$name ?: NeoFrag::loader()->$name))
		{
			return call_user_func_array($library, $args);
		}

		trigger_error('Call to undefined method '.get_class($this).'::'.$name.'()', E_USER_WARNING);
	}

	public function ajax()
	{
		$this->url->ajax_allowed = TRUE;
		return $this;
	}

	public function extension($extension)
	{
		if (in_array($extension, ['json', 'xml', 'txt']))
		{
			if ($this->url->extension != $extension)
			{
				throw new Exception(NeoFrag::UNFOUND);
			}

			$this->url->extension     = $extension;
			$this->url->extension_allowed = TRUE;

			$this->ajax();
		}

		return $this;
	}

	public function add_data($data, $content)
	{
		$loader = is_a($this, 'Loader') ? $this : $this->load;
		$loader->data[$data] = $content;
		return $this;
	}

	public function css($file, $media = 'screen')
	{
		$loader = is_a($this, 'Loader') ? $this : $this->load;
		NeoFrag::loader()->css[] = [$file, $media, $loader->paths];
		return $this;
	}

	public function js($file)
	{
		$loader = is_a($this, 'Loader') ? $this : $this->load;
		NeoFrag::loader()->js[] = [$file, $loader->paths];
		return $this;
	}

	public function js_load($function)
	{
		NeoFrag::loader()->js_load[] = $function;
		return $this;
	}

	public function debug($class, $title = NULL, $loader = FALSE)
	{
		if ($title === NUll)
		{
			$title = get_class($this);
		}
		
		if (!empty($this->override))
		{
			$title .= ' '.icon('fa-code-fork');
		}

		$output = '<span class="label label-'.$class.'" data-toggle="tooltip" data-html="true" title="'.utf8_htmlentities(icon('fa-clock-o').' '.round(($this->time[1] - $this->time[0]) * 1000, 2).' ms&nbsp;&nbsp;&nbsp;'.icon('fa-cogs').' '.ceil(($this->memory[1] - $this->memory[0]) / 1024).' kB').'">'.$title.'</span>';
		
		NeoFrag::loader()->debug->timeline($output, $this->time[0], $this->time[1]);
	
		if ($loader && isset($this->load))
		{
			$output .= $this->load->debugbar();
		}
	
		return $output;
	}
}

/*
NeoFrag Alpha 0.1.5
./neofrag/classes/neofrag.php
*/