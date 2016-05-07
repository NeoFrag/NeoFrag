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

class Library extends NeoFrag
{
	static public $ID;

	public $id;
	public $load;
	public $name = '';

	public function reset()
	{
		if (isset($this->load->libraries[$this->name]))
		{
			unset($this->load->libraries[$this->name]);
			$this->load->library($this->name);
		}
	}

	public function save()
	{
		$clone = clone $this;

		$this->reset();

		return $clone;
	}

	public function set_id($id = NULL)
	{
		$this->id = $id ?: md5($this->name.++self::$ID);
		return $this;
	}
}

/*
NeoFrag Alpha 0.1.4
./neofrag/classes/library.php
*/