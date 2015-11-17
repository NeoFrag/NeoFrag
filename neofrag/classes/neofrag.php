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
	
	const ZONES        = 1;
	const ROWS         = 2;
	const COLS         = 4;
	const WIDGETS      = 8;

	public $id;
	public $load;
	public $path;
	
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
		global $NeoFrag;
		
		if (!is_null($live_editor = post('live_editor')) && $NeoFrag->user('admin'))
		{
			$NeoFrag->session->set('live_editor', $live_editor);
			return $live_editor;
		}
		
		return FALSE;
	}

	static public function get_last($class)
	{
		foreach (debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT | DEBUG_BACKTRACE_IGNORE_ARGS) as $call)
		{
			if (isset($call['object']) && is_a($call['object'], $class))
			{
				return $call['object'];
			}
		}

		return NULL;
	}

	static public function get_loader()
	{
		return NeoFrag::get_last('Loader');
	}

	static public function get_module()
	{
		return NeoFrag::get_last('Module');
	}

	public function __construct()
	{
		//On ajoute le chemin
		$this->set_path();

		//On ajoute une référence vers le loader courant
		$this->load = NeoFrag::get_loader();

		if (is_a($this, 'Library'))
		{
			$name = !empty($this->name) ? $this->name : cc2u(get_class($this));
			
			array_unshift($this->load->paths['views'], './overrides/views/'.$name, './neofrag/views/'.$name);
		
			//On ajoute la librairie nouvellement chargée aux librairies déjà chargées
			$this->load->libraries[$name] =& $this;
		}
	}

	public function __wakeup()
	{
		$this->load = new Loader(array(), NeoFrag::loader());
	}

	public function __isset($name)
	{
		return !is_null($this->load->get_library($name));
	}

	public function __get($name)
	{
		if (is_null($library = $this->load->get_library($name)))
		{
			$this->error($this->load->lang('unknown_property', get_class($this), $name));
		}

		return $library;
	}

	public function __call($name, $args)
	{
		$library = $this->load->get_library($name);

		if (!is_null($library) && is_callable($library))
		{
			return call_user_func_array($library, $args);
		}
		else
		{
			$this->error($this->load->lang('unknown_method', get_class($this), $name));
			return NULL;
		}
	}

	protected function set_path()
	{
		if (!$this->path)
		{
			$this->path = relative_path(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1]['file']);
		}
		
		return $this;
	}

	public function ajax()
	{
		$this->config->ajax_url = TRUE;
		return $this;
	}

	public function add_data($data, $content)
	{
		$this->load->data[$data] = $content;
		return $this;
	}

	public function css($file, $media = 'screen')
	{
		NeoFrag::loader()->css[] = array($file, $media, $this->load);
		return $this;
	}

	public function js($file)
	{
		NeoFrag::loader()->js[] = array($file, $this->load);
		return $this;
	}

	public function js_load($function)
	{
		NeoFrag::loader()->js_load[] = $function;
		return $this;
	}
	
	public function set_id($id)
	{
		$this->id = preg_match('/[a-f0-9]{32}/', $id) ? $id : md5($id);
		return $this;
	}

	public function get_modules($get_all = FALSE)
	{
		$list = array();
		
		foreach ($this->addons('module') as $module => $enable)
		{
			$module_instance = NeoFrag::loader()->init_module($module);

			if (!is_null($module_instance) && ($enable || !$module_instance->deactivatable || $get_all) && $this->access($module, 'module_access'))
			{
				$list[] = $module_instance;
			}
			else
			{
				unset($module_instance);
			}
		}

		return $list;
	}

	public function get_themes()
	{
		$list = array();
		
		foreach ($this->addons('theme') as $theme => $enable)
		{
			$theme_instance = NeoFrag::loader()->theme($theme, FALSE);

			if (!is_null($theme_instance))
			{
				$list[] = $theme_instance;
			}
			else
			{
				unset($theme_instance);
			}
		}
		
		usort($list, function($a, $b){
			if ($a->name == 'default')
			{
				return -1;
			}
			else
			{
				return strnatcmp($a->get_title(), $b->get_title());
			}
		});

		return $list;
	}
	
	static public function unset_module()
	{
		unset(NeoFrag::loader()->module);
		NeoFrag::loader()->config->extension_url = 'html';
		NeoFrag::loader()->module = NULL;
	}

	public function is_core()
	{
		return strpos($this->path, './neofrag/') === 0;
	}
	
	public function reset()
	{
		$this->css     = array();
		$this->js      = array();
		$this->js_load = array();
		$this->module  = NULL;
		
		return $this;
	}
}

/*
NeoFrag Alpha 0.1.2
./neofrag/classes/neofrag.php
*/