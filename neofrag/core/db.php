<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

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
			header('HTTP/1.0 503 Service Unavailable');
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

			if ($connect = $driver::connect($config['hostname'], $config['username'], $config['password'], $config['database']))
			{
				if (is_string($connect))
				{
					$config['driver'] = $connect;
					return $this->_connect($config);
				}
				else
				{
					$this->_connected = TRUE;
					$this->debug->log('DB '.$config['hostname'].' '.$config['database'].' ('.$config['driver'].')');
				}
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
		
		if (!empty($request->error))
		{
			trigger_error($request->error.' ['.$request->sql.']'.(!empty($request->bind) ? ' '.json_encode($request->bind) : '').' in '.$request->file.' on line '.$request->line, E_USER_WARNING);
		}
		else if ($callback)
		{
			return $request->$callback();
		}
	}
	
	public function get_info($var = NULL)
	{
		static $info;
		
		if ($info === NULL)
		{
			$driver = $this->_driver;
			$info = array_merge($driver::get_info(), [
				'driver' => strtolower(preg_replace('/^Driver_/', '', $driver))
			]);
		}

		if ($var !== NULL && isset($info[$var]))
		{
			return $info[$var];
		}

		return $info;
	}

	public function get_size()
	{
		static $size;
		
		if ($size === NULL)
		{
			$driver = $this->_driver;
			$size = $driver::get_size();
		}

		return $size;
	}

	public function escape_string($string)
	{
		$driver = $this->_driver;
		return $driver::escape_string($string);
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

	public function replace($table, $data)
	{
		$this->_request['replace'] = $table;
		$this->_request['values']  = $data;

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

	public function results()
	{
		return $this->_request('results');
	}

	public function fetch($results)
	{
		$driver = $this->_driver;
		return $driver::fetch($results);
	}

	public function free($results)
	{
		$driver = $this->_driver;
		return $driver::free($results);
	}

	public function lock($tables)
	{
		$driver = $this->_driver;
		$driver::lock($tables);
		return $this;
	}

	public function unlock($tables)
	{
		$driver = $this->_driver;
		$driver::unlock($tables);
		return $this;
	}

	public function tables()
	{
		$driver = $this->_driver;
		return $driver::tables();
	}

	public function table_create($table)
	{
		$driver = $this->_driver;
		return $driver::table_create($table);
	}

	public function table_columns($table)
	{
		$driver = $this->_driver;
		return $driver::table_columns($table);
	}

	public function execute($query)
	{
		$this->query($query);
		$this->_request();
		return $this;
	}

	public function query($query)
	{
		$this->_request['query'] = $query;
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
