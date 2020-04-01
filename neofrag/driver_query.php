<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag;

class Driver_Query
{
	public $driver;
	public $bind = [];

	public function __construct($driver, $request)
	{
		$this->driver = $driver;

		foreach ($request as $key => $value)
		{
			$this->$key = $value;
		}
	}

	public function __call($name, $args)
	{
		return call_user_func_array([$this->driver, $name], array_merge([$this], $args));
	}

	public function __debugInfo()
	{
		$debug = [];

		foreach (['sql', 'bind', 'error'] as $var)
		{
			if (isset($this->$var))
			{
				$debug[$var] = $this->$var;
			}
		}

		return $debug;
	}

	public function build_sql()
	{
		if (!empty($this->query))
		{
			$this->sql = $this->query;
		}
		else if ((!empty($this->insert) || !empty($this->replace)) && !empty($this->values))
		{
			$this->sql = !empty($this->insert) ? 'INSERT INTO '.$this->insert : 'REPLACE INTO '.$this->replace;

			$this->sql .= ' ('.implode(', ', array_map([$this->driver, 'escape_keywords'], array_keys($this->values))).') VALUES ('.implode(', ', array_map(function($a){
				return $this->driver->bind($this, $a);
			}, $this->values)).')';
		}
		else
		{
			if (!empty($this->from))
			{
				$this->sql = 	'SELECT '.(!empty($this->select) ? implode(', ', array_map([$this->driver, 'escape_keywords'], $this->select)) : '*').' '.
								'FROM '.$this->from;

				if (!empty($this->join))
				{
					$this->sql .= ' '.implode(' ', array_unique($this->join));
				}
			}
			else if (!empty($this->update) && !empty($this->set))
			{
				$sets = [];

				if (is_array($this->set))
				{
					foreach ($this->set as $key => $value)
					{
						$sets[] = $this->driver->escape_keywords($key).' = '.$this->driver->bind($this, $value);
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
					$part = '';

					if (is_array($where))
					{
						$last_operator2 = NULL;

						foreach ($where as $where)
						{
							if ($w = $this->where($where))
							{
								if ($last_operator2 !== NULL)
								{
									$part .= ' '.$last_operator2.' ';
								}

								$part .= $w;

								$last_operator2 = $where->operator;
							}
						}

						if ($part)
						{
							$part = '('.$part.')';
						}
					}
					else if ($w = $this->where($where))
					{
						$part .= $w;
					}

					if ($part)
					{
						if ($last_operator !== NULL)
						{
							$sql .= ' '.$last_operator.' ';
						}

						$sql .= $part;

						$last_operator = $where->operator;
					}
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
					$this->sql .= ' ORDER BY '.implode(', ', array_map([$this->driver, 'escape_keywords'], $this->order_by));
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
			$sql = $this->driver->escape_keywords($where->name);
		}
		else
		{
			if (method_exists($where->value, '__toArray'))
			{
				$where->value = $where->value->__toArray();
			}

			if (is_array($where->value))
			{
				$where->value = array_unique($where->value);

				if ($where->value)
				{
					$sql = $this->driver->escape_keywords($where->name).' IN ('.implode(', ', array_map(function($a){
						return $this->bind($a);
					}, $where->value)).')';
				}
				else
				{
					$sql = '';
				}
			}
			else if (preg_match('/^(.+?) FIND_IN_SET$/', $this->driver->escape_keywords($where->name), $match))
			{
				$sql = 'FIND_IN_SET('.$this->bind($where->value).', '.$match[1].')';
			}
			else
			{
				if (preg_match('/^(.+?) (!=|<>|<|>|<=|>=|=|LIKE|NOT LIKE)?$/', $where->name, $match))
				{
					$name = $match[1];
					$op   = $match[2];
				}
				else
				{
					$name = $where->name;
					$op   = '=';
				}

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

				$sql = $this->driver->escape_keywords($name).' '.$op.' '.$this->bind($where->value);
			}
		}

		return $sql;
	}

	public function debug()
	{
		require_once 'lib/SqlFormatter/SqlFormatter.php';
		return \SqlFormatter::format(\SqlFormatter::compress($this->sql)).(!empty($this->bind) ? '<br />'.NeoFrag()->debug->table($this->bind) : '').(!empty($this->error) ? '<div class="alert alert-danger">'.$this->error.'</div>' : '');
	}
}
