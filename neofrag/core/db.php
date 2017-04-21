<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Core;

use NF\NeoFrag\Core;

class Db extends Core
{
	static protected $_drivers  = [];
	static protected $_requests = [];

	protected $_request = [];

	public function __construct($config)
	{
		array_walk($config, $connect = function($config) use (&$connect){
			if (!isset($config['hostname'], $config['username'], $config['password'], $config['database']))
			{
				return;
			}

			if (!isset($config['type']))
			{
				$config['type'] = 'default';
			}

			if (isset(self::$_drivers[$config['type']]))
			{
				return;
			}

			if (!isset($config['driver']))
			{
				$config['driver'] = 'mysqli';
			}

			if ($driver = NeoFrag()->___load('drivers', $config['driver'], [$config['hostname'], $config['username'], $config['password'], $config['database']]))
			{
				if ($connection = $driver->connect())
				{
					if (is_string($connection))
					{
						$config['driver'] = $connection;
						$connect($config);
						return;
					}
					else
					{
						if (isset($config['init']) && is_a($config['init'], 'closure'))
						{
							call_user_func($config['init'], $connection);
						}

						self::$_drivers[$config['type']] = $driver;

						if (NEOFRAG_DEBUG_BAR || NEOFRAG_LOGS)
						{
							$this->debug('DB', 'Connection established '.$config['type'].' / '.$config['hostname'].' / '.$config['database'].' ('.$config['driver'].')');
						}
					}
				}
			}
		});

		if (empty(self::$_drivers['default']))
		{
			header('HTTP/1.0 503 Service Unavailable');
			exit('Database error check config/db.php');
		}

		$this->debug->bar('database', function(&$label){
			$total_time   = 0;
			$total_errors = 0;

			$result = '<table class="table table-striped">';

			foreach (self::$_requests as $i => $request)
			{
				$result .= '	<tr>
									<td class="col-1"><b>'.($i + 1).'</b><div class="pull-right"><span class="badge badge-'.(!empty($request->error) ? 'danger' : 'success').'">'.round($request->time * 1000, 3).' ms</span></div></td>
									<td class="col-8">'.$request->debug().'</td>
									<td class="col-3 text-right">'.(isset($request->file) ? $request->file.' <code>'.$request->line : '').'</code></td>
								</tr>';

				$total_time   += $request->time;
				$total_errors += (int)!empty($request->error);
			}

			if (!empty(self::$_requests))
			{
				$result .= '	<tr>
									<td><b>Total</b><div class="pull-right"><span class="badge badge-success">'.round($total_time * 1000, 3).' ms</span></div></td>
									<td colspan="2"></td>
								</tr>';
			}

			$result .= '</table>';

			$label = '<span class="badge badge-'.($total_errors > 0 ? 'danger' : 'success').'">'.($total_errors ?: $i + 1).'</span>';

			return $result;
		});
	}

	public function __invoke($type = NULL)
	{
		$db = clone $this;

		if ($type)
		{
			$db->_request['type'] = $type;
		}

		return $db;
	}

	public function __debugInfo()
	{
		return static::$_requests;
	}

	public function get_info($var = NULL)
	{
		static $info;

		if ($info === NULL)
		{
			$info = array_merge($this->_driver('get_info'), [
				'driver' => strtolower($this->_driver())
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
			$size = $this->_driver('get_size');
		}

		return $size;
	}

	public function escape_string($string)
	{
		return $this->_driver('escape_string', $string);
	}

	public function select()
	{
		if ($args = func_get_args())
		{
			$this->_request['select'] = $args;
			return $this;
		}
		else if (isset($this->_request['select']))
		{
			return $this->_request['select'];
		}
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

		return $this->_exec('last_id');
	}

	public function replace($table, $data)
	{
		$this->_request['replace'] = $table;
		$this->_request['values']  = $data;

		return $this->_exec('last_id');
	}

	public function update($table, $data)
	{
		$this->_request['update'] = $table;
		$this->_request['set']    = $data;

		return $this->_exec('affected_rows');
	}

	public function delete($table, $multi_tables = '')
	{
		$this->_request['delete'] = $table;

		if ($multi_tables)
		{
			$this->_request['multi_tables'] = $multi_tables;
		}

		return $this->_exec('affected_rows');
	}

	public function get($cast = TRUE)
	{
		$get = $this->_exec('get');

		if ($cast && !empty($get) && count($get[0]) == 1)
		{
			foreach ($get as &$row)
			{
				$row = current($row);
			}
		}

		return $get;
	}

	public function index()
	{
		$list = [];

		foreach ($this->get(FALSE) as $row)
		{
			$list[array_shift($row)] = array_shift($row);
		}

		return $list;
	}

	public function row($cast = TRUE)
	{
		$row = $this->_exec('row');

		if ($cast && count($row) == 1)
		{
			return current($row);
		}

		return $row;
	}

	public function results()
	{
		return $this->_exec('results');
	}

	public function fetch($results)
	{
		return $this->_driver('fetch', $results);
	}

	public function free($results)
	{
		return $this->_driver('free', $results);
	}

	public function lock($tables)
	{
		$this->_driver('lock', $tables);
		return $this;
	}

	public function unlock($tables)
	{
		$this->_driver('unlock', $tables);
		return $this;
	}

	public function tables()
	{
		return $this->_driver('tables');
	}

	public function table_create($table)
	{
		return $this->_driver('table_create', $table);
	}

	public function table_columns($table)
	{
		return $this->_driver('table_columns', $table);
	}

	public function execute($query)
	{
		$this->query($query);
		$this->_exec();
		return $this;
	}

	public function query($query)
	{
		$this->_request['query'] = $query;
		return $this;
	}

	protected function _driver()
	{
		if (!($args = func_get_args()))
		{
			return isset($this->_request['type']) && isset(self::$_drivers[$this->_request['type']]) ? $this->_request['type'] : 'default';
		}

		return call_user_func_array([self::$_drivers[$this->_driver()], array_shift($args)], $args);
	}

	protected function _exec($callback = NULL)
	{
		$request = $this->_driver('query', $this->_request);

		$this->_request = [];

		if (NEOFRAG_DEBUG_BAR || NEOFRAG_LOGS)
		{
			self::$_requests[] = $request;
		}

		if (empty($request->error) && $callback)
		{
			return $request->$callback();
		}
	}
}
