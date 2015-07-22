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

class Library extends NeoFrag
{
	public function reset($trace = 2)
	{
		if ($key = array_search($this, $this->load->libraries))
		{
			unset($this->load->libraries[$key]);
			$this->load->library($key, $trace);
		}
	}

	public function copy()
	{
		$backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1];
		$id = $backtrace['file'].$backtrace['line'];

		$clone = clone $this;
		$clone->set_id($backtrace['file'].$backtrace['line'].$clone->get_name());
		
		return $clone;
	}

	public function save()
	{
		$clone = clone $this;
		$this->reset(4);
		return $clone;
	}
}

/*
NeoFrag Alpha 0.1
./neofrag/classes/library.php
*/