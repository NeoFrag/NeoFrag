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

class Database extends Core
{
	private $_config    = array();
	private $_request   = array();
	private $_connected = FALSE;
	private $_driver;

	public  $name       = 'db';
	public  $requests   = array();

	public function __construct($config)
	{
		parent::__construct();

		if (is_array($config))
		{
			if (isset($config[0]) && is_array($config[0]))
			{
				while (!$this->_connected && list(, $conf) = each($config))
				{
					$this->_connect($conf);
				}
			}
			else
			{
				$this->_connect($config);
			}
		}

		if (!$this->_connected)
		{
			exit('Database error check config/database.php');
		}
	}

	public function __toString()
	{
		$request        = $this->_request;
		$this->_request = array();

		return $this->_driver->builder($request);
	}

	public function is_connected()
	{
		return $this->_connected;
	}

	private function _connect($config)
	{
		if (!isset($config['hostname'], $config['username'], $config['password'], $config['database']))
		{
			$this->profiler->log('Fichier de configuration mal formé : ./neofrag/config/database.php', Profiler::WARNING);
			return;
		}

		if (file_exists($driver_path = './neofrag/databases/'.$config['driver'].'.php'))
		{
			require_once $driver_path;

			$driver_name      = 'Driver_'.$config['driver'];
			$this->_driver    = new $driver_name($this);
			$this->_connected = $this->_driver->connect($config['hostname'], $config['username'], $config['password'], $config['database']);

			if ($this->_connected)
			{
				$this->profiler->log('Connexion à la base de données : hostname="'.$config['hostname'].'" username="'.$config['username'].'" password="******" database="'.$config['database'].'" driver="'.$config['driver'].'"', Profiler::INFO);
				$this->_config = $config;
			}
			else
			{
				$this->profiler->log('Erreur de connexion à la base de données : hostname="'.$config['hostname'].'" username="'.$config['username'].'" password="******" database="'.$config['database'].'" driver="'.$config['driver'].'"', Profiler::WARNING);
			}
		}
		else
		{
			$this->profiler->log('Pilote de base de données introuvable : ./neofrag/databases/'.$config['driver'].'.php', Profiler::WARNING);
		}
	}

	private function _request($type)
	{
		if (!$this->_connected)
		{
			//TODO essayer de charger à partir du cache
			$this->profiler->log('Aucune base de données n\'est connectée, requête impossible', Profiler::ERROR, 2);
			return;
		}

		$request        = $this->_driver->builder($this->_request);
		$resource       = $this->_driver->query($request);
		$this->_request = array();
		
		if (!empty($resource))
		{
			$result = $this->_driver->$type($resource);
			
			if (is_resource($resource))
			{
				$this->_driver->free_result($resource);
			}
			
			if (0)//TODO
			{
				$this->cache->set($request, serialize($result), time(), 'sql');
			}
			
			return $result;
		}
		else
		{
			//TODO essayer de charger à partir du cache
		}
	}

	public function query($query)
	{
		$args = array();

		foreach (array_offset_left(func_get_args()) as $arg)
		{
			$args[] = $this->_driver->escape_string($arg);
		}

		$this->_request['query'] = vsprintf($query, $args);
		return $this;
	}

	public function select()
	{
		$this->_request['select'] = func_get_args();
		return $this;
	}

	public function from($from)
	{
		$this->_request['from'] = $from;
		return $this;
	}

	public function where($name, $value = NULL, $operator = 'AND')
	{
		if (func_num_args() > 3 && in_array(func_num_args() % 3, array(0, 2)))
		{
			$args = array();
			
			foreach (func_get_args() as $i => $arg)
			{
				if ($i % 3 == 0)
				{
					$args[] = array($arg);
				}
				else
				{
					$args[array_last_key($args)][] = $arg;
				}
			}

			$this->_request['where'] .= '(';

			foreach ($args as $arg)
			{
				call_user_func_array(array($this, 'where'), $arg);
			}
			
			$this->_request['where'] = trim_word($this->_request['where'], ' AND ', ' OR ').') AND ';

			return $this;
		}
	
		if (is_array($value))
		{
			$where = $name.' IN ('.trim_word(implode(', ', array_map(array($this->_driver, 'escape_string'), $value)), ', ').') '.$operator.' ';

			if (isset($this->_request['where']))
			{
				$this->_request['where'] .= $where;
			}
			else
			{
				$this->_request['where'] = $where;
			}

			return $this;
		}
		else
		{
			$where = $name;

			if (func_num_args() > 1)
			{
				if (preg_match('/^(.+?) FIND_IN_SET$/', $name, $match))
				{
					$where = 'FIND_IN_SET('.$this->_driver->escape_string($value).', '.$match[1].')';
				}
				else
				{
					if ($value === NULL)
					{
						$where .= ' IS ';
					}
					else if (preg_match('/^(.+?) (!=|<>|<|>|<=|>=|=|LIKE)?$/', $name))
					{
						$where .= ' ';
					}
					else
					{
						$where .= ' = ';
					}
					
					$where .= $this->_driver->escape_string($value);
				}
			}

			$where .= ' '.$operator.' ';

			if (isset($this->_request['where']))
			{
				$this->_request['where'] .= $where;
			}
			else
			{
				$this->_request['where'] = $where;
			}

			return $this;
		}
	}

	public function where_or($name, $value)
	{
		return $this->where($name, $value, 'OR');
	}

	public function join($table, $on, $type = '')
	{
		$join = '';

		if ($on == 'NATURAL')
		{
			$join .= 'NATURAL ';
		}
		else if (!$type)
		{
			$type = 'LEFT';
		}

		$join .= $type.' JOIN '.$table;

		if ($on != 'NATURAL')
		{
			$join .= ' ON '.$on;
		}

		if (isset($this->_request['join']))
		{
			$this->_request['join'] .= ' '.$join;
		}
		else
		{
			$this->_request['join'] = $join;
		}

		return $this;
	}

	public function group_by()
	{
		$this->_request['group_by'] = func_get_args();
		return $this;
	}

	public function having()
	{
		$this->_request['having'] = func_get_args();
		return $this;
	}

	public function order_by()
	{
		$this->_request['order_by'] = func_get_args();
		return $this;
	}
	
	public function limit()
	{
		$this->_request['limit'] = implode(', ', func_get_args());
		return $this;
	}

	public function insert($table, $data)
	{
		$this->_request['insert'] = $table;
		$this->_request['values'] = $data;

		return $this->_request('get_last_id');
	}

	public function update($table, $data)
	{
		$this->_request['update'] = $table;
		$this->_request['set']    = $data;

		return $this->_request('affected_rows');
	}

	public function delete($table, $multi_tables = '')
	{
		$this->_request['delete'] = $table;
		
		if ($multi_tables)
		{
			$this->_request['multi_tables'] = $multi_tables;
		}

		return $this->_request('affected_rows');
	}

	public function set()
	{
		return $this;
	}

	public function get($cast = TRUE)
	{
		$get = $this->_request('get');

		if ($cast && !empty($get) && count($get[0]) == 1)
		{
			foreach ($get as &$row)
			{
				$row = current($row);
			}
		}

		return $get;
	}

	public function row($cast = TRUE)
	{
		$row = $this->_request('row');

		if ($cast && count($row) == 1)
		{
			return current($row);
		}

		return $row;
	}

	public function num_rows()
	{
		return count($this->get());
	}

	public function __invoke()
	{
		return end($this->requests);
	}

	public function get_last_id()
	{
		return $this->_driver->get_last_id();
	}
	
	public function get_info()
	{
		return $this->_driver->get_server_info();
	}

	public function profiler()
	{
		if (!$this->_connected)
		{
			return '';
		}

		$total_time   = 0;
		$total_errors = 0;
		$total_rows   = 0;
		$output       = '';

		foreach ($this->requests as $key => $request)
		{
			list($request, $time, $error, $rows, $file, $line) = $request;

			$output .= '	<tr>
								<td style="width: 80px;"><b>'.$key.'</b></td>
								<td style="width: 10px;"><span class="orange">'.$rows.'</span></td>
								<td style="width: 80px;"><span class="pull-right label label-'.(($error) ? 'danger' : 'success').'" style="text-transform: inherit;">'.round($time * 1000, 3).' ms</span></td>
								<td>'.$request.($error ? '<br />'.$error : '').'</td>
								<td style="width: 300px;">'.$file.' <span class="orange">'.$line.'</span></td>
							</tr>';

			$total_time   += $time;
			$total_errors += ($error) ? 1 : 0;
			$total_rows   += $rows;
		}

		if (!empty($this->requests))
		{
			$output .= '	<tr>
								<td style="width: 60px;"><b>Total rows</b></td>
								<td colspan="4"><b>'.$total_rows.'</b></td>
							</tr>';
		}

		return '	<a href="#" data-profiler="db"><i class="icon-chevron-'.($this->session('profiler', 'db') ? 'down' : 'up').' pull-right"></i></a>
					<h2>Database</h2>
					<div class="profiler-block">
						<table class="table table-striped">
							<tbody>
								<tr>
									<td colspan="3" style="width: 200px;"><b>Hostname</b></td>
									<td colspan="2">'.$this->_config['hostname'].'</td>
								</tr>
								<tr>
									<td colspan="3" style="width: 200px;"><b>Username</b></td>
									<td colspan="2">'.$this->_config['username'].'</td>
								</tr>
								<tr>
									<td colspan="3" style="width: 200px;"><b>Password</b></td>
									<td colspan="2">******</td>
								</tr>
								<tr>
									<td colspan="3" style="width: 200px;"><b>Database</b></td>
									<td colspan="2">'.$this->_config['database'].'</td>
								</tr>
								<tr>
									<td colspan="3" style="width: 200px;"><b>Driver</b></td>
									<td colspan="2">'.$this->_config['driver'].'</td>
								</tr>
								<tr>
									<td colspan="3" style="width: 200px;"><b>Prefix</b></td>
									<td colspan="2">nf_</td>
								</tr>
								<tr>
									<td colspan="3" style="width: 200px;"><b>Total queries time</b></td>
									<td colspan="2">'.round($total_time * 1000, 3).' ms ('.round($total_time / (microtime(TRUE) - NEOFRAG_TIME) * 100, 1).'% of total execution time)</td>
								</tr>
								<tr>
									<td colspan="3" style="width: 200px;"><b>Queries</b></td>
									<td colspan="2">'.count($this->requests).($total_errors > 0 ? ' ('.$total_errors.' error'.($total_errors > 1 ? 's' : '').', '.round($total_errors / count($this->requests) * 100, 1).'% errors)' : '').'</td>
								</tr>
								'.$output.'
							</tbody>
						</table>
					</div>';
	}
	
	public function update_time_zone()
	{
		foreach (array(date_default_timezone_get(), date('P')) as $time_zone)
		{
			if ($this->_driver->set_time_zone($time_zone))
			{
				break;
			}
		}

		return $this;
	}
	
	public function check_foreign_keys()
	{
		$this->_driver->check_foreign_keys(TRUE);
		
		return $this;
	}
	
	public function ignore_foreign_keys()
	{
		$this->_driver->check_foreign_keys(FALSE);
		
		return $this;
	}
}

/*
NeoFrag Alpha 0.1.1
./neofrag/core/database.php
*/