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

class Driver_mysql extends Driver
{
	static public function connect($hostname, $username, $password, $database)
	{
		self::$db = @mysql_connect($hostname, $username, $password);

		if (self::$db !== FALSE && mysql_select_db($database, self::$db))
		{
			mysql_set_charset('UTF8');

			return TRUE;
		}
	}
	
	static public function get_server_info()
	{
		return 'MySQL '.mysql_get_server_info();
	}

	static public function check_foreign_keys($check)
	{
		return mysql_query('SET FOREIGN_KEY_CHECKS = '.(int)$check);
	}
	
	protected function build_sql()
	{
		parent::build_sql();
		
		if (!empty($this->bind))
		{
			$this->sql = vsprintf($this->sql, $this->bind);
		}
		
		return $this;
	}

	protected function execute()
	{
		if (!$this->result = mysql_query($this->sql))
		{
			$this->error = mysql_error();
		}
	}

	protected function bind($value)
	{
		$return = '%d';

		if ($value === NULL)
		{
			$return = '%s';
			$value  = 'NULL';
		}
		else if (is_bool($value))
		{
			$return = '%s';
			$value  = '"'.(int)$value.'"';
		}
		else if (!is_integer($value))
		{
			$return = '%s';
			$value  = '"'.mysql_real_escape_string($value).'"';
		}

		$this->bind[] = $value;

		return $return;
	}

	public function get()
	{
		$return = [];
		
		while ($data = mysql_fetch_array($this->result, MYSQL_ASSOC))
		{
			$return[] = $data;
		}
		
		mysql_free_result($this->result);

		return $return;
	}

	public function row()
	{
		$return = mysql_fetch_array($this->result, MYSQL_ASSOC);

		mysql_free_result($this->result);

		return $return;
	}

	public function last_id()
	{
		return mysql_insert_id(self::$db);
	}

	public function affected_rows()
	{
		return mysql_affected_rows();
	}
}

/*
NeoFrag Alpha 0.1.4
./neofrag/databases/mysql.php
*/