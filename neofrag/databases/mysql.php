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

class Driver_mysql extends Driver
{
	public function connect($hostname, $username, $password, $database)
	{
		$this->_connect_id = @mysql_connect($hostname, $username, $password);

		if ($this->_connect_id !== FALSE && mysql_select_db($database, $this->_connect_id))
		{
			mysql_query('SET NAMES UTF8');

			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	public function get_server_info()
	{
		return 'MySQL '.mysql_get_server_info();
	}
	
	public function set_time_zone($time_zone)
	{
		return mysql_query('SET time_zone = \''.$time_zone.'\'');
	}

	public function builder($request)
	{
		if (isset($request['query']))
		{
			return $request['query'];
		}
		else if (isset($request['from']))
		{
			return 'SELECT '.(!empty($request['select']) && is_array($request['select']) ? trim_word(implode(', ', array_map(array(&$this, 'escape_keyword'), $request['select'])), ', ') : '*').' FROM '.$request['from'].((isset($request['join'])) ? ' '.$request['join'] : '').((isset($request['where'])) ? ' WHERE '.trim_word($request['where'], ' AND ', ' OR ') : '').((isset($request['group_by'])) ? ' GROUP BY '.trim_word(implode(', ', $request['group_by']), ', ') : '').((isset($request['having'])) ? ' HAVING '.trim_word(implode(', ', $request['having']), ', ') : '').((isset($request['order_by'])) ? ' ORDER BY '.trim_word(implode(', ', array_map(array(&$this, 'escape_keyword'), $request['order_by'])), ', ') : '').((isset($request['limit'])) ? ' LIMIT '.$request['limit'] : '');
		}
		else if (isset($request['insert'], $request['values']))
		{
			$keys = '';
			foreach (array_keys($request['values']) as $key)
			{
				$keys .= $this->escape_keyword($key).', ';
			}

			$values = '';
			foreach ($request['values'] as $value)
			{
				$values .= $this->escape_string($value).', ';
			}

			return 'INSERT INTO '.$request['insert'].' ('.trim_word($keys, ', ').') VALUES ('.trim_word($values, ', ').')';
		}
		else if (isset($request['update'], $request['set']))
		{
			if (is_array($request['set']))
			{
				$sets = '';
				foreach ($request['set'] as $key => $value)
				{
					$sets .= $this->escape_keyword($key).' = '.$this->escape_string($value).', ';
				}
			}
			else
			{
				$sets = $request['set'];
			}

			return 'UPDATE '.$request['update'].' SET '.trim_word($sets, ', ').((isset($request['where'])) ? ' WHERE '.trim_word($request['where'], ' AND ', ' OR ') : '');
		}
		else if (isset($request['delete']))
		{
			$query = 'DELETE ';
			
			if (isset($request['multi_tables']))
			{
				$query .= $request['delete'].' FROM '.$request['multi_tables'];
			}
			else
			{
				$query .= 'FROM '.$request['delete'];
			}
			
			return $query.((isset($request['where'])) ? ' WHERE '.trim_word($request['where'], ' AND ', ' OR ') : '');
		}

		return '';
	}

	public function query($request)
	{
		$start    = microtime(TRUE);
		
		$resource = mysql_query($request);
		
		$backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);

		$this->_db->requests[] = array($request, microtime(TRUE) - $start, mysql_error(), (!is_bool($resource) && !empty($resource)) ? mysql_num_rows($resource) : 0, relative_path($backtrace[2]['file']), $backtrace[2]['line']);

		return $resource;
	}
	
	public function free_result($result)
	{
		return mysql_free_result($result);
	}

	public function get($request)
	{
		$result = array();
		
		if (is_resource($request))
		{
			while ($data = mysql_fetch_array($request, MYSQL_ASSOC))
			{
				$result[] = $data;
			}
		}

		return $result;
	}

	public function row($request)
	{
		if (!is_resource($request))
		{
			return array();
		}
		
		$result = mysql_fetch_array($request, MYSQL_ASSOC);
		
		return $result ?: array();
	}

	public function escape_string($string)
	{
		if (is_bool($string))
		{
			return '\''.intval($string).'\'';
		}
		else if ($string === NULL)
		{
			return 'NULL';
		}
		else if (!is_integer($string))
		{
			return '\''.mysql_real_escape_string($string).'\'';
		}

		return $string;
	}

	public function escape_keyword($string)
	{
		if (in_array(strtolower($string), array('order')))
		{
			return '`'.$string.'`';
		}
		else
		{
			return $string;
		}
	}

	public function get_last_id()
	{
		return mysql_insert_id($this->_connect_id);
	}

	public function affected_rows()
	{
		return mysql_affected_rows();
	}
		
	public function check_foreign_keys($check)
	{
		mysql_query('SET FOREIGN_KEY_CHECKS='.(int)$check.';');
		
		return $this;
	}

}

/*
NeoFrag Alpha 0.1
./neofrag/databases/mysql.php
*/