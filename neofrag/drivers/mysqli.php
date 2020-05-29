<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Drivers;

use NF\NeoFrag\Driver;

class Mysqli extends Driver
{
	protected $db;
	protected $stmt = [];

	public function connect()
	{
		$this->db = @new \mysqli($this->info->hostname, $this->info->username, $this->info->password, $this->info->database);

		if (!$this->db->connect_error)
		{
			$this->db->set_charset('utf8');

			$this->db->query('SET sql_mode  = "'.trim(str_replace('ONLY_FULL_GROUP_BY', '', $this->db->query('SELECT @@sql_mode')->fetch_row()[0]), ',').'"');
			$this->db->query('SET time_zone = "+00:00"');
			$this->db->query('SET time_zone = "'.($this->info->time_zone = date_create($this->db->query('SELECT NOW()')->fetch_row()[0])->diff(date_create())->format('%R%H:%I')).'"');

			return $this->db;
		}
	}

	public function get_info()
	{
		$server  = 'MySQL';
		$version = $this->db->server_info;

		if (preg_match('/-([0-9.]+?)-(MariaDB)/', $version, $match))
		{
			list(, $version, $server) = $match;
		}
		else
		{
			$version = preg_replace('/-.*$/', '', $version);
		}

		return [
			'name'      => $this->info->database,
			'time_zone' => $this->info->time_zone,
			'server'    => $server,
			'version'   => $version,
			'innodb'    => ($result = $this->db->query('SELECT SUPPORT FROM INFORMATION_SCHEMA.ENGINES WHERE ENGINE = "InnoDB"')->fetch_row()) && in_array($result[0], ['DEFAULT', 'YES'])
		];
	}

	public function get_size()
	{
		$total = 0;

		$sql = $this->db->query('SHOW TABLE STATUS LIKE "nf\_%"');
		while ($table = $sql->fetch_object())
		{
			$total += $table->Data_length + $table->Index_length;
		}

		return $total;
	}

	public function escape_keywords($string)
	{
		if (in_array(strtolower($string), ['key', 'read', 'order', 'primary']))
		{
			return '`'.$string.'`';
		}
		else
		{
			return $string;
		}
	}

	public function escape_string($string)
	{
		return $this->db->real_escape_string($string);
	}

	public function check_foreign_keys($check)
	{
		return $this->db->query('SET FOREIGN_KEY_CHECKS = '.(int)$check);
	}

	public function fetch($results, $type = 'assoc')
	{
		if ($results[1]->fetch())
		{
			return $this->_row($results[0]);
		}
	}

	public function free($results)
	{
		$results[1]->free_result();
	}

	public function lock($tables)
	{
		$this->db->query('LOCK TABLES '.implode(', ', array_map(function($a){
			return $a.' WRITE';
		}, (array)$tables)));
	}

	public function unlock($tables)
	{
		$this->db->query('UNLOCK TABLES');
	}

	public function tables()
	{
		$tables = [];

		$sql = $this->db->query('SHOW TABLE STATUS LIKE "nf\_%"');
		while ($table = $sql->fetch_object())
		{
			$tables[] = $table->Name;
		}

		return $tables;
	}

	public function table_create($table)
	{
		$result = '';

		$sql = $this->db->query('SHOW CREATE TABLE `'.$table.'`');
		if ($row = $sql->fetch_object())
		{
			$result = $row->{'Create Table'};
		}

		return $result;
	}

	public function table_columns($table)
	{
		$columns = [];

		$sql = $this->db->query('SHOW COLUMNS FROM `'.$table.'`');
		while ($column = $sql->fetch_object())
		{
			$columns[$column->Field] = $column->Type;
		}

		return $columns;
	}

	public function execute($request)
	{
		if (!isset($this->stmt[$request->sql]))
		{
			$this->stmt[$request->sql] = $this->db->prepare($request->sql);
		}

		if ($request->stmt = $this->stmt[$request->sql])
		{
			if (!empty($request->bind))
			{
				$args = [];
				$bind = $request->bind;

				foreach ($bind as $i => $value)
				{
					if ($i)
					{
						$args[] = &$bind[$i];
					}
					else
					{
						$args[] = $bind[$i];
					}
				}

				call_user_func_array([$request->stmt, 'bind_param'], $args);
			}

			if ($request->stmt->execute())
			{
				return;
			}
		}

		$request->error = $this->db->error;
	}

	public function bind($request, $value)
	{
		if ($value === NULL)
		{
			return 'NULL';
		}

		if (empty($request->bind))
		{
			$request->bind = [''];
		}

		if (is_bool($value))
		{
			$value = (int)$value;

			$request->bind[0] .= 's';
		}
		else if (is_integer($value))
		{
			$request->bind[0] .= 'i';
		}
		else if (is_float($value))
		{
			$request->bind[0] .= 'd';
		}
		else
		{
			$request->bind[0] .= 's';
		}

		$request->bind[] = $value;

		return '?';
	}

	public function get($request)
	{
		return $this->_get_results($request, function($request, &$row){
			$results = [];

			while ($request->stmt->fetch())
			{
				$results[] = $this->_row($row);
			}

			return $results;
		});
	}

	public function row($request)
	{
		return $this->_get_results($request, function($request, &$row){
			if ($request->stmt->fetch())
			{
				return $row;
			}
		});
	}

	public function results($request)
	{
		return $this->_get_results($request, function($request, &$row){
			return [$row, $request->stmt];
		}, FALSE);
	}

	public function last_id($request)
	{
		return $request->stmt->insert_id;
	}

	public function affected_rows($request)
	{
		return $request->stmt->affected_rows;
	}

	private function _row($row)
	{
		return array_map(function($a){
			return $a;
		}, $row);
	}

	private function _get_results($request, $callback, $free_results = TRUE)
	{
		$result = $params = [];
		$md = $request->stmt->result_metadata();

		while ($field = $md->fetch_field())
		{
			$params[] = &$result[$field->name];
		}

		call_user_func_array([$request->stmt, 'bind_result'], $params);

		$return = $callback($request, $result);

		if ($free_results)
		{
			$request->stmt->free_result();
		}

		return $return ?: [];
	}
}
