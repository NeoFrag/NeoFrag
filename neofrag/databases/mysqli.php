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

class Driver_mysqli extends Driver
{
	static private $_stmt = [];

	static public function connect($hostname, $username, $password, $database)
	{
		if ((self::$db = @mysqli_connect($hostname, $username, $password, $database)) !== FALSE)
		{
			self::$db->set_charset('utf8');

			return TRUE;
		}
	}
	
	static public function get_server_info()
	{
		return 'MySQL '.self::$db->server_info;
	}

	static public function check_foreign_keys($check)
	{
		return self::$db->query('SET FOREIGN_KEY_CHECKS = '.(int)$check);
	}

	protected function execute()
	{
		if (!isset(self::$_stmt[$this->sql]))
		{
			self::$_stmt[$this->sql] = self::$db->prepare($this->sql);
		}
		
		$this->stmt = self::$_stmt[$this->sql];
		
		if ($this->stmt)
		{
			if (!empty($this->bind) && ($count = count($this->bind)) > 1)
			{
				$bind = $this->bind;

				foreach (range(0, $count - 1) as $i)
				{
					$bind[$i] = &$bind[$i];
				}
				
				call_user_func_array([$this->stmt, 'bind_param'], $bind);
			}

			if (!$this->stmt->execute())
			{
				$this->error = $this->stmt->error;
			}
		}
		else
		{
			$this->error = self::$db->error;
		}
	}

	protected function bind($value)
	{
		if (empty($this->bind))
		{
			$this->bind = [''];
		}

		if ($value === NULL)
		{
			return 'NULL';
		}
		else if (is_bool($value))
		{
			$value = (int)$value;
			
			$this->bind[0] .= 's';
		}
		else if (is_integer($value))
		{
			$this->bind[0] .= 'i';
		}
		else if (is_float($value))
		{
			$this->bind[0] .= 'd';
		}
		else
		{
			$this->bind[0] .= 's';
		}

		$this->bind[] = $value;

		return '?';
	}

	public function get()
	{
		$result = $this->stmt->get_result();
		
		$return = $result->fetch_all(MYSQLI_ASSOC) ?: [];
		
		$result->free();
		
		return $return;
	}

	public function row()
	{
		$result = $this->stmt->get_result();
		
		$return = $result->fetch_assoc() ?: [];
		
		$result->free();
		
		return $return;
	}

	public function last_id()
	{
		return $this->stmt->insert_id;
	}

	public function affected_rows()
	{
		return $this->stmt->affected_rows;
	}
}

/*
NeoFrag Alpha 0.1.4
./neofrag/databases/mysqli.php
*/