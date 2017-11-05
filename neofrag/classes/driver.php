<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

abstract class Driver
{
	static protected $db;
	static protected $keywords = ['read', 'order'];

	static public function query($request)
	{
		static $check_foreign_keys;

		$request = new static($request);

		if (!$check_foreign_keys && empty($request->ignore_foreign_keys))
		{
			static::check_foreign_keys($check_foreign_keys = TRUE);
		}
		else if ($check_foreign_keys !== FALSE && !empty($request->ignore_foreign_keys))
		{
			static::check_foreign_keys($check_foreign_keys = FALSE);
		}

		$time = microtime(TRUE);

		$request->build_sql()->execute();

		$request->time = microtime(TRUE) - $time;

		$backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);
		$request->file = relative_path($backtrace[2]['file']);
		$request->line = $backtrace[2]['line'];

		return $request;
	}

	static protected function escape_keywords($string)
	{
		if (in_array(strtolower($string), static::$keywords))
		{
			return '`'.$string.'`';
		}
		else
		{
			return $string;
		}
	}

	abstract static public function connect($hostname, $username, $password, $database);
	abstract static public function get_info();
	abstract static public function get_size();
	abstract static public function escape_string($string);
	abstract static public function check_foreign_keys($check);
	abstract static public function fetch($check, $type = 'assoc');
	abstract static public function free($check);
	abstract static public function lock($tables);
	abstract static public function unlock($tables);
	abstract static public function tables();
	abstract static public function table_create($table);
	abstract static public function table_columns($table);

	abstract protected function execute();
	abstract protected function bind($value);

	abstract public function get();
	abstract public function row();
	abstract public function results();
	abstract public function last_id();
	abstract public function affected_rows();

	public $bind = [];

	public function __construct($request)
	{
		foreach ($request as $key => $value)
		{
			$this->$key = $value;
		}
	}

	protected function build_sql()
	{
		if (!empty($this->query))
		{
			$this->sql = $this->query;
		}
		else if ((!empty($this->insert) || !empty($this->replace)) && !empty($this->values))
		{
			$this->sql = !empty($this->insert) ? 'INSERT INTO '.$this->insert : 'REPLACE INTO '.$this->replace;

			$this->sql .= ' ('.implode(', ', array_map('static::escape_keywords', array_keys($this->values))).') VALUES ('.implode(', ', array_map(function($a){
				return $this->bind($a);
			}, $this->values)).')';
		}
		else
		{
			if (!empty($this->from))
			{
				$this->sql = 	'SELECT '.(!empty($this->select) ? implode(', ', array_map('static::escape_keywords', $this->select)) : '*').' '.
								'FROM '.$this->from;

				if (!empty($this->join))
				{
					$this->sql .= ' '.$this->join;
				}
			}
			else if (!empty($this->update) && !empty($this->set))
			{
				$sets = [];

				if (is_array($this->set))
				{
					foreach ($this->set as $key => $value)
					{
						$sets[] = static::escape_keywords($key).' = '.$this->bind($value);
					}
				}
				else
				{
					$sets[] = $this->set;
				}

				$this->sql = 'UPDATE '.$this->update.' SET '.implode(', ', $sets);
			}
			else if (!empty($this->delete))
			{
				$this->sql = 'DELETE ';

				if (!empty($this->multi_tables))
				{
					$this->sql .= $this->delete.' FROM '.$this->multi_tables;
				}
				else
				{
					$this->sql .= 'FROM '.$this->delete;
				}
			}

			if (!empty($this->where))
			{
				$sql = ' WHERE ';

				$last_operator = NULL;

				foreach ($this->where as $where)
				{
					if ($last_operator !== NULL)
					{
						$sql .= ' '.$last_operator.' ';
					}

					if (is_array($where))
					{
						$last_operator2 = NULL;

						$sql .= '(';

						foreach ($where as $where)
						{
							if ($last_operator2 !== NULL)
							{
								$sql .= ' '.$last_operator2.' ';
							}

							$sql .= $this->where($where);

							$last_operator2 = $where->operator;
						}

						$sql .= ')';
					}
					else
					{
						$sql .= $this->where($where);
					}

					$last_operator = $where->operator;
				}

				$this->sql .= $sql;
			}

			if (isset($this->from))
			{
				if (!empty($this->group_by))
				{
					$this->sql .= ' GROUP BY '.implode(', ', $this->group_by);
				}

				if (!empty($this->having))
				{
					$this->sql .= ' HAVING '.implode(', ', $this->having);
				}

				if (!empty($this->order_by))
				{
					$this->sql .= ' ORDER BY '.implode(', ', array_map('static::escape_keywords', $this->order_by));
				}

				if (!empty($this->limit))
				{
					$this->sql .= ' LIMIT '.$this->limit;
				}
			}
		}

		return $this;
	}

	protected function where($where)
	{
		if (!property_exists($where, 'value'))
		{
			$sql = static::escape_keywords($where->name);
		}
		else if (is_array($where->value))
		{
			$sql = static::escape_keywords($where->name).' IN ('.implode(', ', array_map(function($a){
				return $this->bind($a);
			}, $where->value)).')';
		}
		else
		{
			if (preg_match('/^(.+?) FIND_IN_SET$/', static::escape_keywords($where->name), $match))
			{
				$sql = 'FIND_IN_SET('.$this->bind($where->value).', '.$match[1].')';
			}
			else
			{
				if (preg_match('/^(.+?) (!=|<>|<|>|<=|>=|=|LIKE)?$/', $where->name, $match))
				{
					$name = $match[1];
					$op   = $match[2];
				}
				else
				{
					$name = $where->name;
					$op   = '=';
				}

				$sql = static::escape_keywords($name);

				if ($where->value === NULL)
				{
					if ($op == '=')
					{
						$op = 'IS';
					}
					else if ($op == '!=' || $op == '<>')
					{
						$op = 'IS NOT';
					}
				}

				$sql .= ' '.$op.' '.$this->bind($where->value);
			}
		}

		return $sql;
	}

	public function debug()
	{
		require_once 'lib/geshi/geshi.php';
		return geshi_highlight($this->sql, 'sql', NULL, TRUE).(!empty($this->bind) ? '<br />'.NeoFrag()->debug->table($this->bind) : '').(!empty($this->error) ? '<div class="alert alert-danger">'.$this->error.'</div>' : '');
	}
}
