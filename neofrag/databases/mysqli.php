<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class Driver_mysqli extends Driver
{
	static private $_database;
	static private $_time_zone;
	static private $_stmt = [];

	static public function connect($hostname, $username, $password, $database)
	{
		if ((self::$db = @mysqli_connect($hostname, $username, $password, $database)) !== FALSE)
		{
			self::$_database = $database;

			self::$db->set_charset('utf8');

			self::$db->query('SET time_zone = "+00:00"');
			self::$db->query('SET time_zone = "'.(self::$_time_zone = date_create(self::$db->query('SELECT NOW()')->fetch_row()[0])->diff(date_create())->format('%R%H:%I')).'"');

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
			'name'      => self::$_database,
			'time_zone' => self::$_time_zone,
			'server'    => $server,
			'version'   => $version,
			'innodb'    => ($result = self::$db->query('SELECT SUPPORT FROM INFORMATION_SCHEMA.ENGINES WHERE ENGINE = "InnoDB"')->fetch_row()) && in_array($result[0], ['DEFAULT', 'YES'])
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

	static public function lock($tables)
	{
		self::$db->query('LOCK TABLES '.implode(', ', array_map(function($a){
			return '`'.$a.'` READ';
		}, $tables)));
	}

	static public function unlock($tables)
	{
		self::$db->query('UNLOCK TABLES');
	}

	static public function tables()
	{
		$tables = [];

		$sql = self::$db->query('SHOW TABLE STATUS LIKE "nf\_%"');
		while ($table = $sql->fetch_object())
		{
			$tables[] = $table->Name;
		}

		return $tables;
	}

	static public function table_create($table)
	{
		$result = '';

		$sql = self::$db->query('SHOW CREATE TABLE `'.$table.'`');
		if ($row = $sql->fetch_object())
		{
			$result = $row->{'Create Table'};
		}

		return $result;
	}

	static public function table_columns($table)
	{
		$columns = [];

		$sql = self::$db->query('SHOW COLUMNS FROM `'.$table.'`');
		while ($column = $sql->fetch_object())
		{
			$columns[$column->Field] = $column->Type;
		}

		return $columns;
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
