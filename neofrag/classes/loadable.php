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

abstract class Loadable extends Translatable
{
	abstract public function paths();

	public $name;
	public $type;
	public $title;
	public $description;
	public $link;
	public $author;
	public $licence;
	public $version;
	public $nf_version;

	public function __construct($name, $type)
	{
		$this->name = $name;
		$this->type = $type;
	}

	public function __get($name)
	{
		return $name != 'load' ? parent::__get($name) : $this->load = load('loader', $this->paths(), $this);
	}

	public function __isset($name)
	{
		return !in_array($name, ['load', 'override']) ? parent::__isset($name) : FALSE;
	}

	public function is_deactivatable()
	{
		return !empty(static::$core[$this->name]) || $this->is_removable();
	}

	public function is_removable()
	{
		return !isset(static::$core[$this->name]);
	}

	public function get_title($new_title = NULL)
	{
		static $title;

		if ($new_title !== NULL)
		{
			$title = $new_title;
		}
		else if ($title === NULL)
		{
			$title = $this->load->lang($this->title, NULL);
		}
		
		return $title;
	}

	public function debug($class, $title = NULL, $loader = false)
	{
		return parent::debug($class, $this->name, TRUE);
	}
	
	public function install()
	{
		$this->db->insert('nf_settings_addons', [
			'name'       => $this->name,
			'type'       => $this->type,
			'is_enabled' => TRUE
		]);

		return $this;
	}
	
	public function uninstall($remove = TRUE)
	{
		$this->db	->where('name', $this->name)
					->where('type', $this->type)
					->delete('nf_settings_addons');

		if ($remove)
		{
			rmdir_all($this->type.'s/'.$this->name);
		}

		return $this;
	}
	
	public function reset()
	{
		$this->uninstall(FALSE);
		$this->config->reset();
		$this->install();
		
		return $this;
	}
}

/*
NeoFrag Alpha 0.1.4.1
./neofrag/classes/loadable.php
*/