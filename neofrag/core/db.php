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

class Db extends Core
{
	private $_request   = [];
	private $_connected = FALSE;
	private $_driver;

	public  $requests   = [];

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
			exit('Database error check config/db.php');
		}
	}

	private function _connect($config)
	{
		if (!isset($config['hostname'], $config['username'], $config['password'], $config['database']))
		{
			$this->debug->log('Fichier de configuration mal formé : ./neofrag/config/db.php', Debug::WARNING);
			return;
		}

		if (check_file($path = 'neofrag/databases/'.$config['driver'].'.php'))
		{
			require_once $path;

			$driver = $this->_driver = 'Driver_'.$config['driver'];
			
			if ($this->_connected = $driver::connect($config['hostname'], $config['username'], $config['password'], $config['database']))
			{
				$this->debug->log('DB '.$config['hostname'].' '.$config['database'].' ('.$config['driver'].')');
			}
		}
		else
		{
			$this->debug->log('Pilote de base de données introuvable : ./neofrag/databases/'.$config['driver'].'.php', Debug::WARNING);
		}
	}

	private function _request($callback = NULL)
	{
		$driver = $this->_driver;

		$request = $this->requests[] = $driver::query($this->_request);
		
		$this->_request = [];
		
		if ($callback && empty($request->error))
		{
			return $request->$callback();
		}
	}
	
	public function get_info()
	{
		$driver = $this->_driver;
		return $driver::get_server_info();
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
		if (func_num_args() > 3 && in_array(func_num_args() % 3, [0, 2]))
		{
			$args = [];
			
			foreach (func_get_args() as $i => $arg)
			{
				if ($i % 3 == 0)
				{
					$args[] = [$arg];
				}
				else
				{
					$args[array_last_key($args)][] = $arg;
				}
			}
			
			$this->_request['where'][] = array_map(function($a){
				return (object)[
					'name'     => $a[0],
					'value'    => $a[1],
					'operator' => !empty($a[2]) ? $a[2] : 'AND'
				];
			}, $args);
		}
		else
		{
			$where = (object)[
				'name'     => $name,
				'value'    => $value,
				'operator' => $operator
			];
			
			if (func_num_args() == 1)
			{
				unset($where->value);
			}

			$this->_request['where'][] = $where;
		}

		return $this;
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
	
	public function ignore_foreign_keys()
	{
		$this->_request['ignore_foreign_keys'] = TRUE;
		return $this;
	}

	public function insert($table, $data)
	{
		$this->_request['insert'] = $table;
		$this->_request['values'] = $data;

		return $this->_request('last_id');
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
	
	public function query($query)
	{
		$this->_request['query'] = $query;
		$this->_request();
		return $this;
	}

	public function debugbar(&$output = '')
	{
		if (!$this->_connected)
		{
			return '';
		}
		
		$total_time   = 0;
		$total_errors = 0;

		$result = '<table class="table table-striped">';

		foreach ($this->requests as $i => $request)
		{
			$result .= '	<tr>
								<td class="col-md-1"><b>'.($i + 1).'</b><div class="pull-right"><span class="label label-'.(!empty($request->error) ? 'danger' : 'success').'">'.round($request->time * 1000, 3).' ms</span></div></td>
								<td class="col-md-8">'.$request->debug().'</td>
								<td class="col-md-3 text-right">'.$request->file.' <code>'.$request->line.'</code></td>
							</tr>';

			$total_time   += $request->time;
			$total_errors += (int)!empty($request->error);
		}

		if (!empty($this->requests))
		{
			$result .= '	<tr>
								<td><b>Total</b><div class="pull-right"><span class="label label-success">'.round($total_time * 1000, 3).' ms</span></div></td>
								<td colspan="2"></td>
							</tr>';
		}

		$result .= '</table>';
		
		$output = '<span class="label label-'.($total_errors > 0 ? 'danger' : 'success').'">'.($total_errors ?: $i + 1).'</span>';
		
		return $result;
	}
}

/*
NeoFrag Alpha 0.1.4.2
./neofrag/core/db.php
*/