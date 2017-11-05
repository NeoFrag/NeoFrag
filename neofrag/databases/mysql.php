<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class Driver_mysql extends Driver
{
	static private $_database;
	static private $_time_zone;

	static public function connect($hostname, $username, $password, $database)
	{
		if (function_exists('mysqli_connect'))
		{
			return 'mysqli';
		}

		self::$db = @mysql_connect($hostname, $username, $password);

		if (self::$db !== FALSE && mysql_select_db($database, self::$db))
		{
			self::$_database = $database;

			mysql_set_charset('UTF8');

			mysql_query('SET time_zone = "+00:00"');
			mysql_query('SET time_zone = "'.(self::$_time_zone = date_create(mysql_fetch_row(mysql_query('SELECT NOW()'))[0])->diff(date_create())->format('%R%H:%I')).'"');

			return TRUE;
		}
	}

	static public function get_info()
	{
		$server  = 'MySQL';
		$version = mysql_get_server_info();

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
			'innodb'    => ($result = mysql_fetch_row(mysql_query('SELECT SUPPORT FROM INFORMATION_SCHEMA.ENGINES WHERE ENGINE = "InnoDB"'))) && in_array($result[0], ['DEFAULT', 'YES'])
		];
	}
	
	static public function get_size()
	{
		$total = 0;

		$sql = mysql_query('SHOW TABLE STATUS LIKE "nf\_%"');
		while ($table = mysql_fetch_object($sql))
		{
			$total += $table->Data_length + $table->Index_length;
		}

		return $total;
	}

	static public function escape_string($string)
	{
		return mysql_real_escape_string($string);
	}

	static public function check_foreign_keys($check)
	{
		return mysql_query('SET FOREIGN_KEY_CHECKS = '.(int)$check);
	}
	
	static public function fetch($results, $type = 'assoc')
	{
		return mysql_fetch_assoc($results);
	}

	static public function free($results)
	{
		mysql_free_result($results);
	}

	static public function lock($tables)
	{
		mysql_query('LOCK TABLES '.implode(', ', array_map(function($a){
			return '`'.$a.'` READ';
		}, $tables)));
	}

	static public function unlock($tables)
	{
		mysql_query('UNLOCK TABLES');
	}

	static public function tables()
	{
		$tables = [];

		$sql = mysql_query('SHOW TABLE STATUS LIKE "nf\_%"');
		while ($table = mysql_fetch_object($sql))
		{
			$tables[] = $table->Name;
		}

		return $tables;
	}

	static public function table_create($table)
	{
		$result = '';

		$sql = mysql_query('SHOW CREATE TABLE `'.$table.'`');
		if ($row = mysql_fetch_object($sql))
		{
			$result = $row->{'Create Table'};
		}

		return $result;
	}

	static public function table_columns($table)
	{
		$columns = [];

		$sql = mysql_query('SHOW COLUMNS FROM `'.$table.'`');
		while ($column = mysql_fetch_object($sql))
		{
			$columns[$column->Field] = $column->Type;
		}

		return $columns;
	}

	protected function execute()
	{
		if (!$this->result = mysql_query($this->sql))
		{
			$this->error = mysql_error();
		}
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

	public function results()
	{
		return $this->result;
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
