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
	
	static public function get_info()
	{
		$server  = 'MySQL';
		$version = self::$db->server_info;

		if (preg_match('/-([0-9.]+?)-(MariaDB)/', $version, $match))
		{
			list(, $version, $server) = $match;
		}
		else
		{
			$version = preg_replace('/-.*$/', '', $version);
		}

		return [
			'server'  => $server,
			'version' => $version,
			'innodb'  => ($result = self::$db->query('SELECT SUPPORT FROM INFORMATION_SCHEMA.ENGINES WHERE ENGINE = "InnoDB"')->fetch_row()) && in_array($result[0], array('DEFAULT', 'YES'))
		];
	}
	
	static public function get_size()
	{
		$total = 0;

		$sql = self::$db->query('SHOW TABLE STATUS LIKE "nf\_%"');
		while ($table = $sql->fetch_object())
		{
			$total += $table->Data_length + $table->Index_length;
		}

		return $total;
	}

	static public function escape_string($string)
	{
		return self::$db->real_escape_string($string);
	}

	static public function check_foreign_keys($check)
	{
		return self::$db->query('SET FOREIGN_KEY_CHECKS = '.(int)$check);
	}

	static public function fetch($results, $type = 'assoc')
	{
		if ($results[1]->fetch())
		{
			return self::_get_result($results[0]);
		}
	}

	static public function free($results)
	{
		$results[1]->free_result();
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

			if ($this->stmt->execute())
			{
				return;
			}
		}

		$this->error = self::$db->error;
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
		return $this->_get_results(function(&$row){
			$results = [];

			while ($this->stmt->fetch())
			{
				$results[] = self::_get_result($row);
			}

			return $results;
		});
	}

	public function row()
	{
		return $this->_get_results(function(&$row){
			if ($this->stmt->fetch())
			{
				return $row;
			}
		});
	}

	public function results()
	{
		return $this->_get_results(function(&$row){
			return [$row, $this->stmt];
		}, FALSE);
	}

	public function last_id()
	{
		return $this->stmt->insert_id;
	}

	public function affected_rows()
	{
		return $this->stmt->affected_rows;
	}

	static private function _get_result($row)
	{
		return array_map(function($a){
			return $a;
		}, $row);
	}

	private function _get_results($callback, $free_results = TRUE)
	{
		$result = $params = [];
		$md = $this->stmt->result_metadata();

		while ($field = $md->fetch_field())
		{
			$params[] = &$result[$field->name];
		}

		call_user_func_array([$this->stmt, 'bind_result'], $params);

		$return = $callback($result);

		if ($free_results)
		{
			$this->stmt->free_result();
		}

		return $return ?: [];
	}
}

/*
NeoFrag Alpha 0.1.4
./neofrag/databases/mysqli.php
*/